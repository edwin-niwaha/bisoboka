<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\fixedtypes;

 class fixedtypesController extends Controller{

public function index(){
    return view('fixedtypes/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from fixedtypes ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from fixedtypes limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objfixedtypes=new fixedtypes();
$Objfixedtypes->id=$request['id'];
$Objfixedtypes->mode=$request['mode'];
$Objfixedtypes->name=$request['name'];
$Objfixedtypes->updated_at=$request['updated_at'];
$Objfixedtypes->created_at=$request['created_at'];
$Objfixedtypes->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objfixedtypes=fixedtypes::find($id);

$Objfixedtypes->id=$request['id'];
$Objfixedtypes->mode=$request['mode'];
$Objfixedtypes->name=$request['name'];
$Objfixedtypes->updated_at=$request['updated_at'];
$Objfixedtypes->created_at=$request['created_at'];
$Objfixedtypes->save();
}
 public function destroy($id){
        $Objfixedtypes=fixedtypes::find($id);
        $Objfixedtypes->delete();



    }

public function viewcombo(){


    return fixedtypes::all();
}
}