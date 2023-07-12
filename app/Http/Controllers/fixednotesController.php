<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\fixednotes;

 class fixednotesController extends Controller{

public function index(){
    return view('fixednotes/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from fixednotes ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from fixednotes limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objfixednotes=new fixednotes();
$Objfixednotes->id=$request['id'];
$Objfixednotes->name=$request['name'];
$Objfixednotes->branchno=$request['branchno'];
$Objfixednotes->date=$request['date'];
$Objfixednotes->created_at=$request['created_at'];
$Objfixednotes->updated_at=$request['updated_at'];
$Objfixednotes->done=$request['done'];
$Objfixednotes->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objfixednotes=fixednotes::find($id);

$Objfixednotes->id=$request['id'];
$Objfixednotes->name=$request['name'];
$Objfixednotes->branchno=$request['branchno'];
$Objfixednotes->date=$request['date'];
$Objfixednotes->created_at=$request['created_at'];
$Objfixednotes->updated_at=$request['updated_at'];
$Objfixednotes->done=$request['done'];
$Objfixednotes->save();
}
 public function destroy($id){
        $Objfixednotes=fixednotes::find($id);
        $Objfixednotes->delete();



    }

public function viewcombo(){


    return fixednotes::all();
}
}