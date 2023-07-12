<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\companynames;
use App\loantrans;
use App\loaninfo;
use App\accounttrans;
use App\purchaseheaders;
use App\savings;
use App\savingdefinations;
use App\allsavings;

 class savingsController extends Controller{

public function savingsindex(){
    $branch=auth()->user()->branchid;
$results=savingdefinations::where('isActive','=',1)->where('branchno',$branch)->where('productname','!=','Fixed Deposits')->where('savingac','!=',604)->where('savingac','!=',603)->get();
    return view('savingdeposits/saving')->with('results',$results);
}
public function view(){
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) ){
       
        $today=date("'Y-m-d'");
$this->savingview(" and savings.date=$today  ","$today");
    }
    if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2']) ){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $branch=$_GET['branch'];
        $this->savingview("and savings.date  BETWEEN '$date1' AND '$date2' ,'$date1' AND '$date2' ");
     
     }
     if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2']) && !empty($_GET['branch'])){
       
        $today=date("'Y-m-d'");
        $branch=$_GET['branch'];
        $this->authsavingview("and savings.date=$today and users.branchid=$branch ");
     
     }
 

    
}
public function savesavings(Request $request){
            $id=$request['name'];
            DB::beginTransaction();
  try{
            // Geting member id
       $member=DB::select("select name from customers where id=$id");
       foreach($member as $mem){
               $branch=auth()->user()->branchid;
               // Obtaining header number from purchaseheaders table 
               $objheaders= new purchaseheaders();
               $objheaders->transdates=date("Y-m-d", strtotime($request['date']));
               $objheaders->save();

           $objallsavings= new allsavings();
           // saving into savings table for clear statements 
           $objsaving1= new savings();
           $objsaving2= new savings();
           $objsaving3= new savings();
           $objsaving4= new savings();
           $objshareamt= new savings();
           $objallsavings->client_no=$request['name'];
           $objallsavings->recieptno=$request['paymentdetails'];
           $objallsavings->branchno=$branch;
           $objallsavings->date=date("Y-m-d", strtotime($request['date']));
           $narration="";
           if(str_replace( ',', '',$request['savingpdt1'])>0){
####################### START OF SAVING PDT 1 ######################################################
            $objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt1']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration= $result->productname;
                $objsaving1->narration="Cash Deposit-".$result->productname;
                // Accounts 
                            // inserting into accountrans  savings 1 
             $objaccountrans=new accounttrans;
             $objaccountrans->purchaseheaderid=$objheaders->id;
             $objaccountrans->amount=str_replace( ',', '',$request['savingpdt1']);
             $objaccountrans->total=str_replace( ',', '',$request['savingpdt1']);
             $objaccountrans->accountcode=$result->savingac;
             $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
             $objaccountrans->ttype="C";
             $objaccountrans->bracid=auth()->user()->branchid;
             $objaccountrans->cat='savingpdt1';
             $objaccountrans->stockidentify=$request['paymentdetails'];
             $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
             $objaccountrans->save();
              // inserting into accountrans  cash account 
             $objaccountrans=new accounttrans;
             $objaccountrans->purchaseheaderid=$objheaders->id;
             $objaccountrans->amount=str_replace( ',', '',$request['savingpdt1']);
             $objaccountrans->total=str_replace( ',', '',$request['savingpdt1']);
             $objaccountrans->accountcode=$result->operatingac;
             $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
             $objaccountrans->ttype="D";
             $objaccountrans->cat='savingpdt1';
             $objaccountrans->stockidentify=$request['paymentdetails'];
             $objaccountrans->bracid=auth()->user()->branchid;
             $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
             $objaccountrans->save();
                         // savings Table 
            $objsaving1->date=date("Y-m-d", strtotime($request['date']));
            $objsaving1->client_no=$request['name'];
            $objsaving1->paydet=$request['paymentdetails'];
            $objsaving1->isCharge=0;
            $objsaving1->branchno=$branch;
            $objsaving1->total=str_replace( ',', '',$request['savingpdt1']);
            $objsaving1->category='savingpdt1';
            $objsaving1->moneyin=str_replace( ',', '',$request['savingpdt1']);
            }

            
####################### END OF SAVING PDT 1 ######################################################
           }else{

            $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){  
                $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                $objsaving1->client_no=$request['name'];
                $objsaving1->paydet=$request['paymentdetails'];
                $objsaving1->isCharge=0;
                $objsaving1->total=0;
                $objsaving1->branchno=$branch;
                $objsaving1->category='savingpdt1';
                $objsaving1->moneyin=0;
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt1';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            // second place holder 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->operatingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt1';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            }
           }
           if(str_replace( ',', '',$request['savingpdt2'])>0){
###################### START OF SAVING PDT 2 ####################################################        
            $objallsavings->savingpdt2=str_replace( ',', '',$request['savingpdt2']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving2->narration="Cash Deposit-".$result->productname;
                                         // savings Table 
            
            $objsaving2->date=date("Y-m-d", strtotime($request['date']));
            $objsaving2->client_no=$request['name'];
            $objsaving2->paydet=$request['paymentdetails'];
            $objsaving2->isCharge=0;
            $objsaving2->branchno=$branch;
            $objsaving2->total=str_replace( ',', '',$request['savingpdt2']);;
            $objsaving2->category='savingpdt2';
            $objsaving2->moneyin=str_replace( ',', '',$request['savingpdt2']);;
                            // Accounts 
                            // inserting into accountrans  savings 1 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$request['savingpdt2']);
                            $objaccountrans->total=str_replace( ',', '',$request['savingpdt2']);
                            $objaccountrans->accountcode=$result->savingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->cat='savingpdt2';
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
                             // inserting into accountrans  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$request['savingpdt2']);
                            $objaccountrans->total=str_replace( ',', '',$request['savingpdt2']);
                            $objaccountrans->accountcode=$result->operatingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='savingpdt2';
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();

########################### END OF SAVING PDT 2 ################################################## 
            }
     
           }else{

            $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){ 
                $objsaving2->date=date("Y-m-d", strtotime($request['date']));
                $objsaving2->client_no=$request['name'];
                $objsaving2->paydet=$request['paymentdetails'];
                $objsaving2->isCharge=0;
                $objsaving2->total=0;
                $objsaving2->branchno=$branch;
                $objsaving2->category='savingpdt2';
                $objsaving2->moneyin=0;  
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt2';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            // second place holder 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->operatingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt2';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            } 
           }
           if(str_replace( ',', '',$request['savingpdt3'])>0){
########################## sTART OF SAVING PDT 3 ##############################################            
            $objallsavings->savingpdt3=str_replace( ',', '',$request['savingpdt3']);;
            $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving3->narration="Cash Deposit-".$result->productname;
                $objsaving3->date=date("Y-m-d", strtotime($request['date']));
                $objsaving3->client_no=$request['name'];
                $objsaving3->paydet=$request['paymentdetails'];
                $objsaving3->isCharge=0;
                $objsaving3->branchno=$branch;
                $objsaving3->total=str_replace( ',', '',$request['savingpdt3']);;
                $objsaving3->category='savingpdt3';
                $objsaving3->moneyin=str_replace( ',', '',$request['savingpdt3']);;
                                        
 // Accounts 
                            // inserting into accountrans  savings 1 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$request['savingpdt3']);
                            $objaccountrans->total=str_replace( ',', '',$request['savingpdt3']);
                            $objaccountrans->accountcode=$result->savingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->cat='savingpdt3';
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
                             // inserting into accountrans  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$request['savingpdt3']);
                            $objaccountrans->total=str_replace( ',', '',$request['savingpdt3']);
                            $objaccountrans->accountcode=$result->operatingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='savingpdt3';
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
################################## end OF SAVING PDT 3 ##########################################
            }

           }else{
            $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){  
                $objsaving3->date=date("Y-m-d", strtotime($request['date']));
                $objsaving3->client_no=$request['name'];
                $objsaving3->paydet=$request['paymentdetails'];
                $objsaving3->isCharge=0;
                $objsaving3->branchno=$branch;
                $objsaving3->total=0;
                $objsaving3->category='savingpdt3';
                $objsaving3->moneyin=0; 
              
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt3';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            // second place holder 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->operatingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt3';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            } 
           }
           if(str_replace( ',', '',$request['savingpdt4'])>0){
################################# START OF SAVING PDT 4 #########################################
            $objallsavings->savingpdt4=str_replace( ',', '',$request['savingpdt4']);;
            $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving4->narration="Cash Deposit-".$result->productname;
                $objsaving4->date=date("Y-m-d", strtotime($request['date']));
                $objsaving4->client_no=$request['name'];
                $objsaving4->paydet=$request['paymentdetails'];
                $objsaving4->isCharge=0;
                $objsaving4->branchno=$branch;
                $objsaving4->total=str_replace( ',', '',$request['savingpdt4']);;
                $objsaving4->category='savingpdt4';
                $objsaving4->moneyin=str_replace( ',', '',$request['savingpdt4']);;
                                // Accounts 
                                // inserting into accountrans  savings 1 
                                $objaccountrans=new accounttrans;
                                $objaccountrans->purchaseheaderid=$objheaders->id;
                                $objaccountrans->amount=str_replace( ',', '',$request['savingpdt4']);
                                $objaccountrans->total=str_replace( ',', '',$request['savingpdt4']);
                                $objaccountrans->accountcode=$result->savingac;
                                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                $objaccountrans->ttype="C";
                                $objaccountrans->cat='savingpdt4';
                                $objaccountrans->bracid=auth()->user()->branchid;
                                $objaccountrans->stockidentify=$request['paymentdetails'];
                                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                $objaccountrans->save();
                                 // inserting into accountrans  cash account 
                                $objaccountrans=new accounttrans;
                                $objaccountrans->purchaseheaderid=$objheaders->id;
                                $objaccountrans->amount=str_replace( ',', '',$request['savingpdt4']);
                                $objaccountrans->total=str_replace( ',', '',$request['savingpdt4']);
                                $objaccountrans->accountcode=$result->operatingac;
                                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                $objaccountrans->ttype="D";
                                $objaccountrans->cat='savingpdt4';
                                $objaccountrans->stockidentify=$request['paymentdetails'];
                                $objaccountrans->bracid=auth()->user()->branchid;
                                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                $objaccountrans->save();
             
    ############################# END OF SAVING PDT 4 ######################################################
            }
                        

           }
           else{
 
            $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){ 
                $objsaving4->date=date("Y-m-d", strtotime($request['date']));
                $objsaving4->client_no=$request['name'];
                $objsaving4->paydet=$request['paymentdetails'];
                $objsaving4->isCharge=0;
                $objsaving4->total=0;
                $objsaving4->branchno=$branch;
                $objsaving4->category='savingpdt4';
                $objsaving4->moneyin=0; 
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt4';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            // second place holder 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->operatingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt4';
            $objaccountrans->stockidentify=$request['paymentdetails'];
            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccountrans->save();
            } 
           }
################################# Shares ###############################################
if(str_replace( ',', '',$request['shares'])>0){
    ################################# START OF SHARES #########################################
                $objallsavings->shares=str_replace( ',', '',$request['shares']);;
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
               
                foreach($rs as $result){
                    $narration.=" & ". $result->productname;
                    $objshareamt->narration="Cash Deposit-".$result->productname;
                    $objshareamt->date=date("Y-m-d", strtotime($request['date']));
                    $objshareamt->client_no=$request['name'];
                    $objshareamt->paydet=$request['paymentdetails'];
                    $objshareamt->isCharge=0;
                    $objshareamt->branchno=$branch;
                    $objshareamt->total=str_replace( ',', '',$request['shares']);;
                    $objshareamt->category='shares';
                    $objshareamt->moneyin=str_replace( ',', '',$request['shares']);;

                                    // Accounts 
                                    // inserting into accountrans  savings 1 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->total=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->accountcode=$result->savingac;
                                    $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                    $objaccountrans->ttype="C";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->stockidentify=$request['paymentdetails'];
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                    $objaccountrans->save();
                                     // inserting into accountrans  cash account 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->total=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->accountcode=$result->operatingac;
                                    $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                    $objaccountrans->ttype="D";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->stockidentify=$request['paymentdetails'];
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                    $objaccountrans->save();
                 
        ############################# Shares ######################################################
                }
                            
    
               }
               else{
     
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                foreach($rs as $result){ 
                    $objsaving4->date=date("Y-m-d", strtotime($request['date']));
                    $objsaving4->client_no=$request['name'];
                    $objsaving4->paydet=$request['paymentdetails'];
                    $objsaving4->isCharge=0;
                    $objsaving4->total=0;
                    $objsaving4->branchno=$branch;
                    $objsaving4->category='shares';
                    $objsaving4->moneyin=0;
                // Place holders 
                $objaccountrans=new accounttrans;
                $objaccountrans->purchaseheaderid=$objheaders->id;
                $objaccountrans->amount=0;
                $objaccountrans->total=0;
                $objaccountrans->accountcode=$result->savingac;
                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                $objaccountrans->ttype="C";
                $objaccountrans->bracid=auth()->user()->branchid;
                $objaccountrans->cat='shares';
                $objaccountrans->stockidentify=$request['paymentdetails'];
                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                $objaccountrans->save();
                // second place holder 
                $objaccountrans=new accounttrans;
                $objaccountrans->purchaseheaderid=$objheaders->id;
                $objaccountrans->amount=0;
                $objaccountrans->total=0;
                $objaccountrans->accountcode=$result->operatingac;
                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                $objaccountrans->ttype="C";
                $objaccountrans->bracid=auth()->user()->branchid;
                $objaccountrans->cat='shares';
                $objaccountrans->stockidentify=$request['paymentdetails'];
                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                $objaccountrans->save();
                } 
               }
           $objallsavings->narration=$narration." Deposit";
           $objallsavings->headerid=$objheaders->id;
           $objallsavings->save();
           // saving id in savings table
           $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs1->count()>0){
            $objsaving1->savingsid=$objallsavings->id; 
            $objsaving1->save();
           }
           $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs2->count()>0){
            $objsaving2->savingsid=$objallsavings->id; 
            $objsaving2->save();
           }
           $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs3->count()>0){
            $objsaving3->savingsid=$objallsavings->id; 
            $objsaving3->save();
           }
           $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs4->count()>0){
            $objsaving4->savingsid=$objallsavings->id; 
            $objsaving4->save();
           }
           $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs4->count()>0){
            $objshareamt->savingsid=$objallsavings->id; 
            $objshareamt->save();
           }

           


       }
 }catch(\Exception $e){
    DB::rollback();
      echo "Failed ".$e;
  }
  DB::commit();
     


}

//Auto generated code for updating
public function update(Request $request,$id2){
            $id=$request['clino'];
            $branch=auth()->user()->branchid;
           
            DB::beginTransaction();
  try{
            // Geting member id
       $member=DB::select("select name from customers where id=$id");
       foreach($member as $mem){
            purchaseheaders::where('id','=',$request['headerid'])->update([
             'transdates'=>date("Y-m-d", strtotime($request['date'])),
            ]);
           $objallsavings= allsavings::find($request['id']);
           $objallsavings->client_no=$id;
           $objallsavings->recieptno=$request['paymentdetails'];
           $objallsavings->date=date("Y-m-d", strtotime($request['date']));
           $narration="";
           if(str_replace( ',', '',$request['savingpdt1'])>0){
####################### START OF SAVING PDT 1 ######################################################
            $objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt1']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration= $result->productname;
                // Accounts 
                  accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt1')->update([
                      'amount'=>str_replace( ',', '',$request['savingpdt1']),
                      'total'=>str_replace( ',', '',$request['savingpdt1']),
                      'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                      'stockidentify'=>$request['paymentdetails'],
                      'transdate'=>date("Y-m-d", strtotime($request['date'])),
                      'ttype'=>'C',

                  ]) ;        
              // inserting into accountrans  cash account 
              accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt1')->update([
                'amount'=>str_replace( ',', '',$request['savingpdt1']),
                'total'=>str_replace( ',', '',$request['savingpdt1']),
                'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                'stockidentify'=>$request['paymentdetails'],
                'ttype'=>'D',
                'transdate'=>date("Y-m-d", strtotime($request['date']))

            ]) ;
            }
            // savings Table 
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt1')->update([
                'date'=>date('Y-m-d', strtotime($request['date'])),
                'client_no'=>$id,
                'paydet'=>$request['paymentdetails'],
                'total'=>str_replace( ',', '',$request['savingpdt1']),
                'moneyin'=>str_replace( ',', '',$request['savingpdt1']),
                'narration'=>'Cash Deposit-'.$result->productname,
   
                ]); 
            
####################### END OF SAVING PDT 1 ######################################################
           }else{
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt1')->update([
                'date'=>'',
                'client_no'=>'',
                'paydet'=>'',
                'total'=>0,
                'moneyin'=>0,
   
                ]);
                $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('branchno','=',$branch)->get();
                foreach($rs as $result){
                     // Accounts 
                  accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt1')->update([
                                    'amount'=>0,
                                    'total'=>0,
                                    'stockidentify'=>$request['paymentdetails'],
                                    'transdate'=>date("Y-m-d", strtotime($request['date']))
              
                                ]) ;        
                 // inserting into accountrans  cash account 
                     accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt1')->update([
                              'amount'=>0,
                              'total'=>0,
                              'stockidentify'=>$request['paymentdetails'],
                              'transdate'=>date("Y-m-d", strtotime($request['date']))
              
                          ]) ;
                     }
               $objallsavings->savingpdt1=0;
           }
           if(str_replace( ',', '',$request['savingpdt2'])>0){
###################### START OF SAVING PDT 2 ####################################################        
            $objallsavings->savingpdt2=str_replace( ',', '',$request['savingpdt2']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt2')->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;

            }
                         // savings Table 
            
                         savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt2')->update([
                            'date'=>date('Y-m-d', strtotime($request['date'])),
                            'client_no'=>$id,
                            'paydet'=>$request['paymentdetails'],
                            'total'=>str_replace( ',', '',$request['savingpdt2']),
                            'moneyin'=>str_replace( ',', '',$request['savingpdt2']),
                            'narration'=>'Cash Deposit-'.$result->productname,
                            ]); 
                            // Accounts 
                // Accounts 
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt2')->update([
                    'amount'=>str_replace( ',', '',$request['savingpdt2']),
                    'total'=>str_replace( ',', '',$request['savingpdt2']),
                    'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                    'stockidentify'=>$request['paymentdetails'],
                    'transdate'=>date("Y-m-d", strtotime($request['date']))

                ]) ;        
            // inserting into accountrans  cash account 
            accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt2')->update([
              'amount'=>str_replace( ',', '',$request['savingpdt2']),
              'total'=>str_replace( ',', '',$request['savingpdt2']),
              'narration'=>$mem->name." -Cash Deposits ".$result->productname,
              'stockidentify'=>$request['paymentdetails'],
              'transdate'=>date("Y-m-d", strtotime($request['date']))

          ]) ;

########################### END OF SAVING PDT 2 ##################################################      
           }else{
            // Delete if amount is zero
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt2')->update([
                'date'=>'',
                'client_no'=>'',
                'paydet'=>'',
                'total'=>0,
                'moneyin'=>0,
   
                ]);
                  // Accounts 
                  $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('branchno','=',$branch)->get();
                  foreach($rs as $result){
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt2')->update([
                                        'amount'=>0,
                                        'total'=>0,
                                        'stockidentify'=>$request['paymentdetails'],
                                        'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                                    ]) ;        
                     // inserting into accountrans  cash account 
                         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt2')->update([
                                  'amount'=>0,
                                  'total'=>0,
                                  'stockidentify'=>$request['paymentdetails'],
                                  'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                              ]) ;
            $objallsavings->savingpdt2=0;
        }
    }
           if(str_replace( ',', '',$request['savingpdt3'])>0){
########################## sTART OF SAVING PDT 3 ##############################################            
            $objallsavings->savingpdt3=str_replace( ',', '',$request['savingpdt3']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;

            }
                        
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt3')->update([
                'date'=>date('Y-m-d', strtotime($request['date'])),
                'client_no'=>$id,
                'paydet'=>$request['paymentdetails'],
                'total'=>str_replace( ',', '',$request['savingpdt3']),
                'moneyin'=>str_replace( ',', '',$request['savingpdt3']),
                'narration'=>'Cash Deposit-'.$result->productname,
                ]); 
                           // Accounts 
                // Accounts 
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt3')->update([
                    'amount'=>str_replace( ',', '',$request['savingpdt3']),
                    'total'=>str_replace( ',', '',$request['savingpdt3']),
                    'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                    'stockidentify'=>$request['paymentdetails'],
                    'transdate'=>date("Y-m-d", strtotime($request['date']))

                ]) ;        
            // inserting into accountrans  cash account 
            accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt3')->update([
              'amount'=>str_replace( ',', '',$request['savingpdt3']),
              'total'=>str_replace( ',', '',$request['savingpdt3']),
              'narration'=>$mem->name." -Cash Deposits ".$result->productname,
              'stockidentify'=>$request['paymentdetails'],
              'transdate'=>date("Y-m-d", strtotime($request['date']))

          ]) ;
################################## end OF SAVING PDT 3 ##########################################
           }else{
            // Delete if amount is zero
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt3')->update([
                'date'=>'',
                'client_no'=>'',
                'paydet'=>'',
                'total'=>0,
                'moneyin'=>0,
   
                ]);
                // Accounts 
                $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('branchno','=',$branch)->get();
                foreach($rs as $result){
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt3')->update([
                                        'amount'=>0,
                                        'total'=>0,
                                        'stockidentify'=>$request['paymentdetails'],
                                        'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                                    ]) ;        
                     // inserting into accountrans  cash account 
                         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt3')->update([
                                  'amount'=>0,
                                  'total'=>0,
                                  'stockidentify'=>$request['paymentdetails'],
                                  'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                              ]) ;
            $objallsavings->savingpdt3=0;
        }
    }
           if(str_replace( ',', '',$request['savingpdt4'])>0){
################################# START OF SAVING PDT 4 #########################################
            $objallsavings->savingpdt4=str_replace( ',', '',$request['savingpdt4']);
            $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('branchno','=',$branch)->get();
           
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
            }
                        
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt4')->update([
                'date'=>date('Y-m-d', strtotime($request['date'])),
                'client_no'=>$id,
                'paydet'=>$request['paymentdetails'],
                'total'=>str_replace( ',', '',$request['savingpdt4']),
                'moneyin'=>str_replace( ',', '',$request['savingpdt4']),
                'narration'=>'Cash Deposit-'.$result->productname,
                ]); 
                            // Accounts 
                // Accounts 
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt4')->update([
                    'amount'=>str_replace( ',', '',$request['savingpdt4']),
                    'total'=>str_replace( ',', '',$request['savingpdt4']),
                    'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                    'stockidentify'=>$request['paymentdetails'],
                    'transdate'=>date("Y-m-d", strtotime($request['date']))

                ]) ;        
            // inserting into accountrans  cash account 
            accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt4')->update([
              'amount'=>str_replace( ',', '',$request['savingpdt4']),
              'total'=>str_replace( ',', '',$request['savingpdt4']),
              'narration'=>$mem->name." -Cash Deposits ".$result->productname,
              'stockidentify'=>$request['paymentdetails'],
              'transdate'=>date("Y-m-d", strtotime($request['date']))

          ]) ;
         
############################# END OF SAVING PDT 4 ######################################################
           }else{
            // Delete if amount is zero
            savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt4')->update([
                'date'=>'',
                'client_no'=>'',
                'paydet'=>'',
                'total'=>0,
                'moneyin'=>0,
   
                ]);
                // Accounts 
                $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('branchno','=',$branch)->get();
                foreach($rs as $result){
                                     accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt4')->update([
                                        'amount'=>0,
                                        'total'=>0,
                                        'stockidentify'=>$request['paymentdetails'],
                                        'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                                    ]) ;        
                     // inserting into accountrans  cash account 
                         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt4')->update([
                                  'amount'=>0,
                                  'total'=>0,
                                  'stockidentify'=>$request['paymentdetails'],
                                  'transdate'=>date("Y-m-d", strtotime($request['date']))
                  
                              ]) ;
            $objallsavings->savingpdt4=0;
        }
    }
    // Shares
    if(str_replace( ',', '',$request['shares'])>0){
        ################################# SHARES  #########################################
                    $objallsavings->shares=str_replace( ',', '',$request['shares']);
                    $rs=savingdefinations::where('savingpdt','=','shares')->where('branchno','=',$branch)->get();
                   
                    foreach($rs as $result){
                        $narration.=" & ". $result->productname;
                    }
                                
                   $chuq= savings::where('savingsid','=',$request['id'])->where('category','=','shares')->update([
                        'date'=>date('Y-m-d', strtotime($request['date'])),
                        'client_no'=>$id,
                        'paydet'=>$request['paymentdetails'],
                        'total'=>str_replace( ',', '',$request['shares']),
                        'moneyin'=>str_replace( ',', '',$request['shares']),
                        'narration'=>'Cash Deposit-'.$result->productname,
                        ]); 
                        if($chuq==0){
                            savings::where('savingsid','=',$request['id'])->whereNull('category')->update([
                                'date'=>date('Y-m-d', strtotime($request['date'])),
                                'client_no'=>$id,
                                'paydet'=>$request['paymentdetails'],
                                'total'=>str_replace( ',', '',$request['shares']),
                                'moneyin'=>str_replace( ',', '',$request['shares']),
                                'narration'=>'Cash Deposit-'.$result->productname,
                                'branchno'=>$branch,
                                'category'=>'shares',
                                ]); 
                        }
                                    // Accounts 
                        // Accounts 
                        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','shares')->update([
                            'amount'=>str_replace( ',', '',$request['shares']),
                            'total'=>str_replace( ',', '',$request['shares']),
                            'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                            'stockidentify'=>$request['paymentdetails'],
                            'ttype'=>'C',
                            'transdate'=>date("Y-m-d", strtotime($request['date']))
        
                        ]) ;        
                    // inserting into accountrans  cash account 
                    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','shares')->update([
                      'amount'=>str_replace( ',', '',$request['shares']),
                      'total'=>str_replace( ',', '',$request['shares']),
                      'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                      'stockidentify'=>$request['paymentdetails'],
                      'ttype'=>'D',
                      'transdate'=>date("Y-m-d", strtotime($request['date']))
        
                  ]) ;
                 
        ############################# SHAREs ######################################################
                   }else{
                    // Delete if amount is zero
                    savings::where('savingsid','=',$request['id'])->where('category','=','shares')->update([
                        'date'=>'',
                        'client_no'=>'',
                        'paydet'=>'',
                        'total'=>0,
                        'moneyin'=>0,
           
                        ]);
                        // Accounts 
                        $rs=savingdefinations::where('savingpdt','=','shares')->where('branchno','=',$branch)->get();
                        foreach($rs as $result){
                                             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','shares')->update([
                                                'amount'=>0,
                                                'total'=>0,
                                                'stockidentify'=>$request['paymentdetails'],
                                                'transdate'=>date("Y-m-d", strtotime($request['date']))
                          
                                            ]) ;        
                             // inserting into accountrans  cash account 
                                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','shares')->update([
                                          'amount'=>0,
                                          'total'=>0,
                                          'stockidentify'=>$request['paymentdetails'],
                                          'transdate'=>date("Y-m-d", strtotime($request['date']))
                          
                                      ]) ;
                    $objallsavings->shares=0;
                }
            }
           if($request['savingpdt5']>0){
            $objallsavings->savingpdt5=$request['savingpdt5'];
      
            $rs=savingdefinations::where('savingpdt','=','savingpdt5')->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
            }
             savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt5')->update([
             'date'=>date('Y-m-d', strtotime($request['date'])),
             'client_no'=>$id,
             'paydet'=>$request['paymentdetails'],
             'total'=>str_replace( ',', '',$request['savingpdt5']),
             'moneyin'=>str_replace( ',', '',$request['savingpdt5']),

             ]);         

           }
           $objallsavings->narration=$narration." Deposit";
           $objallsavings->save();



       }
      
     }catch(\Exception $e){
    DB::rollback();
      echo "Failed ".$e;
  }
  DB::commit();
     
    
}
 public function destroy(Request $request,$id){
    $user=auth()->user()->name;
    DB::beginTransaction();
   try{
       $deleteitems=DB::select("select id from allsavings where headerid=$id");
       foreach($deleteitems as $items){
       DB::delete("delete from allsavings where headerid=$id");
       DB::delete("delete from purchaseheaders where id=$id");
       DB::delete("delete from savings where savingsid=$items->id");
       DB::delete("delete from accounttrans where purchaseheaderid=$id");

       }


   }catch(\Exception $e){
       DB::rollback();
       echo "Failed".$e;
   }
   DB::commit();



    }

public function viewcombo(){


    return companynames::all();
}

public function savingview($where,$allsavings){
    $results=array();
    $bra=auth()->user()->branchid;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count  from savings inner join customers on customers.id=savings.client_no inner join allsavings on allsavings.id=savings.savingsid $where  and moneyin>0 and allsavings.branchno=$bra");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select format(if(category='shares',moneyin,0),0) as shares,headerid, allsavings.id as id,purchase_headerid as header,DATE_FORMAT(savings.date,'%d-%m-%Y') as date,paydet,customers.name as name,customers.id as clino,savings.narration,format(moneyin,2) as savings,format(savingpdt1,0) as savingpdt1,format(savingpdt2,0) as savingpdt2,format(savingpdt3,0) as savingpdt3,format(savingpdt4,0) as savingpdt4,format(savingpdt5,0) as savingpdt5  from savings inner join customers on customers.id=savings.client_no inner join allsavings on allsavings.id=savings.savingsid $where  and moneyin>0 and allsavings.branchno=$bra  order by date asc limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
        //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select allsavings.id, allsavings.client_no, format(sum(if(savingpdt1>0,savingpdt1,0)+if(savingpdt2>0,savingpdt2,0)+if(savingpdt3>0,savingpdt3,0)+if(savingpdt4>0,savingpdt4,0)+if(shares>0,shares,0)),2) as savings from allsavings where date=$allsavings");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
}

public function authsavingview($where){
    $admin=auth()->user()->isAdmin;
        if($admin==1){
    $results=array();
    $bra=auth()->user()->branchid;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count  from loantrans inner join customers on loantrans.memid=customers.id inner join users on users.branchid=customers.branchnumber where isSavingDep=1  and loantrans.isActive=1  $where ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select DATE_FORMAT(date,'%d-%m-%Y') as date,narration  from savings inner join customers on customers.id=savings.client_no join users on users.branchid=customers.branchnumber   $where  AND moneyin>0 limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
        //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select format(sum(abs(savings)),0) as savings from loantrans inner join customers on loantrans.memid=customers.id inner join users on users.branchid=customers.branchnumber where isSavingDep=1 and loantrans.isActive=1   $where limit  $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
        }
}
}