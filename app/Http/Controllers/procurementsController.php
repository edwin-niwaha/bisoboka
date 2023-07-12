<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use App\procurements;

 class procurementsController extends Controller{

public function index(){
    

    return view('procurements/index');
}
public function view(){

    return procurements::all();
}
public function save(Request $request){
    $Objprocurements=new procurements();
$Objprocurements->id=$request['id'];
$Objprocurements->name=$request['name'];
$Objprocurements->role=$request['role'];
$Objprocurements->created_at=$request['created_at'];
$Objprocurements->updated_at=$request['updated_at'];
$Objprocurements->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objprocurements=procurements::find($id);

$Objprocurements->id=$request['id'];
$Objprocurements->name=$request['name'];
$Objprocurements->role=$request['role'];
$Objprocurements->created_at=$request['created_at'];
$Objprocurements->updated_at=$request['updated_at'];
$Objprocurements->save();
}
 public function destroy($id){
        $Objprocurements=procurements::find($id);
        $Objprocurements->delete();



    }
}