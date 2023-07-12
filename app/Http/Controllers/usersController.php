<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\users;

 class usersController extends Controller{

public function index(){
    return view('users/index');
}
public function view(){
$bra=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from users where branchid=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select id,name,email,if(admin=1,'Administrator','User') as level,admin from users where branchid=$bra limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
    $bra=auth()->user()->branchid;
    $company=DB::select("select name from companys where id=$bra");
    $companyname='';
    foreach($company as $c){
        $companyname=$c->name;
    }
    $Objusers=new users();
$Objusers->id=$request['id'];
$Objusers->name=$request['name'];
$Objusers->email=$request['email'];
$Objusers->password=bcrypt($request['password']);
$Objusers->admin=1;
$Objusers->branchname=$companyname;
$Objusers->branchid=$bra;
$Objusers->isAdmin=0;
$Objusers->admin=$request['admin'];
$Objusers->remember_token=$request['remember_token'];
$Objusers->created_at=$request['created_at'];
$Objusers->updated_at=$request['updated_at'];
//$Objusers->branchid=$request['branch'];//$auth()->user()->name;//

$Objusers->save();
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
        $company=DB::select("select name from companys where id=$bra");
        $companyname='';
        foreach($company as $c){
            $companyname=$c->name;
        }
        $Objusers=users::find($id);

//$Objusers->id=$request['id'];
$Objusers->name=$request['name'];
//$Objusers->email=$request['email'];
$Objusers->password=bcrypt($request['password']);
$Objusers->admin=$request['admin'];
$Objusers->isAdmin=0;
$Objusers->branchname=$companyname;
$Objusers->branchid=$bra;
$Objusers->remember_token=$request['remember_token'];
$Objusers->created_at=$request['created_at'];
$Objusers->updated_at=$request['updated_at'];
$Objusers->save();
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
 public function destroy($id){
        $Objusers=users::find($id);
        $Objusers->delete();



    }

public function viewcombo(){


    return users::all();
}
}