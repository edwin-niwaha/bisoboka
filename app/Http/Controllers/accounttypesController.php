<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\accounttypes;

 class accounttypesController extends Controller{

public function index(){
    return view('accounttypes/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from accounttypes ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from accounttypes limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objaccounttypes=new accounttypes();
$Objaccounttypes->id=$request['id'];
$Objaccounttypes->accounttype=$request['accounttype'];
$Objaccounttypes->isActive=$request['isActive'];
$Objaccounttypes->created_at=$request['created_at'];
$Objaccounttypes->updated_at=$request['updated_at'];
$Objaccounttypes->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objaccounttypes=accounttypes::find($id);

$Objaccounttypes->id=$request['id'];
$Objaccounttypes->accounttype=$request['accounttype'];
$Objaccounttypes->isActive=$request['isActive'];
$Objaccounttypes->created_at=$request['created_at'];
$Objaccounttypes->updated_at=$request['updated_at'];
$Objaccounttypes->save();
}
 public function destroy($id){
        $Objaccounttypes=accounttypes::find($id);
        $Objaccounttypes->delete();



    }

public function viewcombo(){


    return accounttypes::all();
}
}