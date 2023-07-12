<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\requirements;

 class requirementsController extends Controller{

public function index(){
    return view('requirements/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from requirements ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from requirements limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objrequirements=new requirements();
$Objrequirements->id=$request['id'];
$Objrequirements->name=$request['name'];
$Objrequirements->module_id=$request['module_id'];
$Objrequirements->Urls=$request['Urls'];
$Objrequirements->created_at=$request['created_at'];
$Objrequirements->updated_at=$request['updated_at'];
$Objrequirements->isActive=$request['isActive'];
$Objrequirements->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objrequirements=requirements::find($id);

$Objrequirements->id=$request['id'];
$Objrequirements->name=$request['name'];
$Objrequirements->module_id=$request['module_id'];
$Objrequirements->Urls=$request['Urls'];
$Objrequirements->created_at=$request['created_at'];
$Objrequirements->updated_at=$request['updated_at'];
$Objrequirements->isActive=$request['isActive'];
$Objrequirements->save();
}
 public function destroy($id){
     if($id==4){

     }else{
        $Objrequirements=requirements::find($id);
        $Objrequirements->delete();
     }
        
        

        



    }
}