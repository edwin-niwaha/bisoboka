<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\branches;

 class branchesController extends Controller{

public function index(){
    return view('branches/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from branches where id!=5 ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from branches where id!=5 limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objbranches=new branches();
$Objbranches->id=$request['id'];
$Objbranches->branchname=$request['branchname'];
$Objbranches->contactPerson=$request['contactPerson'];
$Objbranches->Tel=$request['Tel'];
$Objbranches->Address=$request['Address'];
$Objbranches->isActive=$request['isActive'];
$Objbranches->isDefault=$request['isDefault'];
$Objbranches->created_at=$request['created_at'];
$Objbranches->updated_at=$request['updated_at'];
$Objbranches->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objbranches=branches::find($id);

//$Objbranches->id=$request['id'];
$Objbranches->branchname=$request['branchname'];
$Objbranches->contactPerson=$request['contactPerson'];
$Objbranches->Tel=$request['Tel'];
$Objbranches->Address=$request['Address'];
$Objbranches->isActive=$request['isActive'];
$Objbranches->isDefault=$request['isDefault'];
$Objbranches->created_at=$request['created_at'];
$Objbranches->updated_at=$request['updated_at'];
$Objbranches->save();
}
 public function destroy($id){
        $Objbranches=branches::find($id);
        $Objbranches->delete();



    }

public function viewcombo(){


    return branches::where('id','!=',5)->get();
}
}