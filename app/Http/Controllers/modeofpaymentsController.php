<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\modeofpayments;

 class modeofpaymentsController extends Controller{

public function index(){
    return view('modeofpayments/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from modeofpayments ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from modeofpayments limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objmodeofpayments=new modeofpayments();
$Objmodeofpayments->id=$request['id'];
$Objmodeofpayments->name=$request['name'];
$Objmodeofpayments->isActive=$request['isActive'];
$Objmodeofpayments->created_at=$request['created_at'];
$Objmodeofpayments->updated_at=$request['updated_at'];
$Objmodeofpayments->isDefault['isDefault'];
$Objmodeofpayments->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmodeofpayments=modeofpayments::find($id);

$Objmodeofpayments->id=$request['id'];
$Objmodeofpayments->name=$request['name'];
$Objmodeofpayments->isActive=$request['isActive'];
$Objmodeofpayments->created_at=$request['created_at'];
$Objmodeofpayments->updated_at=$request['updated_at'];
$Objmodeofpayments->isDefault['isDefault'];
$Objmodeofpayments->save();
}
 public function destroy($id){
        $Objmodeofpayments=modeofpayments::find($id);
        $Objmodeofpayments->delete();



    }

public function viewcombo(){


    return modeofpayments::all();
}
}