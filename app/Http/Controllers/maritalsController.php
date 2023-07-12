<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\maritals;

 class maritalsController extends Controller{

public function index(){
    return view('maritals/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from maritals ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from maritals limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmaritals=new maritals();
$Objmaritals->id=$request['id'];
$Objmaritals->name=$request['name'];
$Objmaritals->created_at=$request['created_at'];
$Objmaritals->updated_at=$request['updated_at'];
$Objmaritals->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmaritals=maritals::find($id);

$Objmaritals->id=$request['id'];
$Objmaritals->name=$request['name'];
$Objmaritals->created_at=$request['created_at'];
$Objmaritals->updated_at=$request['updated_at'];
$Objmaritals->save();
}
 public function destroy($id){
        $Objmaritals=maritals::find($id);
        $Objmaritals->delete();



    }

public function viewcombo(){


    return maritals::all();
}
}