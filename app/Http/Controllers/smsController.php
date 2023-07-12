<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\branches;

 class smsController extends Controller{

public function sms(){
 $a=file("http://www.egosms.co/api/v1/plain/?number=%2B256757971100&message=My+First+Message+Through+Egosms&username=Denha&password=0704531731Denis.&sender=Egosms");
 return $a;
}

}