<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\loaninfo;
use App\accounttrans;
use App\purchaseheaders;
use App\loantrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    //
    public function index(){
        return view('auth/login');
    }
   public function login(Request $request){
        //dd($request->all());
        if(Auth::attempt([
            'email'=>$request->email3,
        'password'=>$request->password]) ){
            $user= User::where('email',$request->email)->first();

            if($user->isAdmin()){
                // Auto Computation of Maintainence Fees 
                
               

                //return redirect()->route('dashboard');
            }
            //return redirect()->route('home');

        }


    }
    public function loansdue(){
        $today=date("'Y/m/d'");  
       $keys=DB::select("select if(COUNT(name)>0,COUNT(name),0) as count from loandues inner join loanschedules on loanschedules.loanid=loandues.loanid where loanamount!=0  AND scheduledate=$today");
       foreach($keys as $key){
           return $key->count;
       }
    }


    public function registerindex(){

        return view('auth/register');
    }
}
