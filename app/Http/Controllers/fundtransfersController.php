<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\purchaseheaders;

 class fundtransfersController extends Controller{

public function index(){

    return view('fundtransfers/index');
}

public function savetransferheaders(Request $request){
$purchaseheaders= new purchaseheaders();
$purchaseheaders->transdates=$request['transdates'];
$purchaseheaders->remarks=$request['remarks'];
$purchaseheaders->save();

}

public function savetransferaccounts(Request $request){


    
}
 }

 