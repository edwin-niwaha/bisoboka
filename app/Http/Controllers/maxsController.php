<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\maxs;

 class maxsController extends Controller{

public function index(){
    return view('maxs/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from maxs ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from maxs limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmaxs=new maxs();
$Objmaxs->id=$request['id'];
$Objmaxs->firstname=$request['firstname'];
$Objmaxs->secondname=$request['secondname'];
$Objmaxs->created_at=$request['created_at'];
$Objmaxs->updated_at=$request['updated_at'];
$Objmaxs->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmaxs=maxs::find($id);

$Objmaxs->id=$request['id'];
$Objmaxs->firstname=$request['firstname'];
$Objmaxs->secondname=$request['secondname'];
$Objmaxs->created_at=$request['created_at'];
$Objmaxs->updated_at=$request['updated_at'];
$Objmaxs->save();
}
 public function destroy($id){
        $Objmaxs=maxs::find($id);
        $Objmaxs->delete();



    }

public function viewcombo(){


    return maxs::all();
}
}