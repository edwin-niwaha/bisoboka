<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\memberships;

 class membershipsController extends Controller{

public function index(){
    return view('memberships/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from memberships ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from memberships limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmemberships=new memberships();
$Objmemberships->id=$request['id'];
$Objmemberships->raccount=$request['raccount'];
$Objmemberships->isActive=$request['isActive'];
$Objmemberships->created_at=$request['created_at'];
$Objmemberships->updated_at=$request['updated_at'];
$Objmemberships->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmemberships=memberships::find($id);

$Objmemberships->id=$request['id'];
$Objmemberships->raccount=$request['raccount'];
$Objmemberships->isActive=$request['isActive'];
$Objmemberships->created_at=$request['created_at'];
$Objmemberships->updated_at=$request['updated_at'];
$Objmemberships->save();
}
 public function destroy($id){
        $Objmemberships=memberships::find($id);
        $Objmemberships->delete();



    }

public function viewcombo(){


    return memberships::all();
}
}