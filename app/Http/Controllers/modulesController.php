<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use App\modules;

 class modulesController extends Controller{

public function index(){
    
    $objmod=modules::all();
    return view('modules/index')->with('sidemenu',$objmod);
}
public function view(){

    return modules::all();
}
public function save(Request $request){
    $Objmodules=new modules();
$Objmodules->id=$request['id'];
$Objmodules->name=$request['name'];
$Objmodules->created_at=$request['created_at'];
$Objmodules->updated_at=$request['updated_at'];
$Objmodules->isActive=$request['isActive'];
$Objmodules->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objmodules=modules::find($id);

$Objmodules->id=$request['id'];
$Objmodules->name=$request['name'];
$Objmodules->created_at=$request['created_at'];
$Objmodules->updated_at=$request['updated_at'];
$Objmodules->isActive=$request['isActive'];
$Objmodules->save();
}
 public function destroy($id){
        $Objmodules=modules::find($id);
        $Objmodules->delete();



    }


 public function sidemenu(){
  $objmod=modules::all();
  return view('layouts/layout')->with('sidemenu',$objmod);


 }
 
 public function combomodules(){
     return modules::all();
 }
}