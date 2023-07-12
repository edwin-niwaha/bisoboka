<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\interestmethods;

 class interestmethodsController extends Controller{

public function index(){
    return view('interestmethods/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from interestmethods ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from interestmethods limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objinterestmethods=new interestmethods();
$Objinterestmethods->id=$request['id'];
$Objinterestmethods->name=$request['name'];
$Objinterestmethods->created_at=$request['created_at'];
$Objinterestmethods->updated_at=$request['updated_at'];
$Objinterestmethods->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objinterestmethods=interestmethods::find($id);

$Objinterestmethods->id=$request['id'];
$Objinterestmethods->name=$request['name'];
$Objinterestmethods->created_at=$request['created_at'];
$Objinterestmethods->updated_at=$request['updated_at'];
$Objinterestmethods->save();
}
 public function destroy($id){
        $Objinterestmethods=interestmethods::find($id);
        $Objinterestmethods->delete();



    }

public function viewcombo(){


    return interestmethods::all();
}
}