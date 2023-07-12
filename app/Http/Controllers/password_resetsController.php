<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use App\password_resets;

 class password_resetsController extends Controller{

public function index(){
    

    return view('password_resets/index');
}
public function view(){

    return password_resets::all();
}
public function save(Request $request){
    $Objpassword_resets=new password_resets();
$Objpassword_resets->email=$request['email'];
$Objpassword_resets->token=$request['token'];
$Objpassword_resets->created_at=$request['created_at'];
$Objpassword_resets->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objpassword_resets=password_resets::find($id);

$Objpassword_resets->email=$request['email'];
$Objpassword_resets->token=$request['token'];
$Objpassword_resets->created_at=$request['created_at'];
$Objpassword_resets->save();
}
 public function destroy($id){
        $Objpassword_resets=password_resets::find($id);
        $Objpassword_resets->delete();



    }
}