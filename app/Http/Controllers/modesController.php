<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\modes;

 class modesController extends Controller{

public function index(){
    return view('modes/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from modes ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from modes limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmodes=new modes();
$Objmodes->id=$request['id'];
$Objmodes->name=$request['name'];
$Objmodes->created_at=$request['created_at'];
$Objmodes->updated_at=$request['updated_at'];
$Objmodes->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmodes=modes::find($id);

$Objmodes->id=$request['id'];
$Objmodes->name=$request['name'];
$Objmodes->created_at=$request['created_at'];
$Objmodes->updated_at=$request['updated_at'];
$Objmodes->save();
}
 public function destroy($id){
        $Objmodes=modes::find($id);
        $Objmodes->delete();



    }

public function viewcombo(){


    return modes::all();
}
}