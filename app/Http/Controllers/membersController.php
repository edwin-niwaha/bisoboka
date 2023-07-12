<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\members;

 class membersController extends Controller{

public function index(){
    return view('members/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from members ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from members limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmembers=new members();
$Objmembers->id=$request['id'];
$Objmembers->firstname=$request['firstname'];
$Objmembers->lastname=$request['lastname'];
$Objmembers->created_at=$request['created_at'];
$Objmembers->updatated_at=$request['updatated_at'];
$Objmembers->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmembers=members::find($id);

$Objmembers->id=$request['id'];
$Objmembers->firstname=$request['firstname'];
$Objmembers->lastname=$request['lastname'];
$Objmembers->created_at=$request['created_at'];
$Objmembers->updatated_at=$request['updatated_at'];
$Objmembers->save();
}
 public function destroy($id){
        $Objmembers=members::find($id);
        $Objmembers->delete();



    }

public function viewcombo(){


    return members::all();
}
}