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
            'email'=>$request->email,
        'password'=>$request->password]) ){
            $user= User::where('email',$request->email)->first();

            if($user->isAdmin()){
                // Auto Computation of interest

              //  $time=DB::select('select customers.name as client,branches.id as branch,loaninfos.id as lnid,date as loandate,loaninterest,period,loanrepay,sum(loan) as loan,memid,mode from loaninfos inner join loantrans on loantrans.loanid=loaninfos.id inner join  customers on customers.id=loantrans.memid inner join branches on branches.id=customers.branchnumber where  isLoan = 1 and loantrans.isActive=1 group by memid');
    

                return redirect()->route('dashboard');
            }
            return redirect()->route('home');

        }

//return redirect()->back();
    }


    public function registerindex(){

        return view('auth/register');
    }
}
