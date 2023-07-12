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
 use App\loanproducts;
 use App\audits;

 class allsavingsController extends Controller{

public function index(){
    return view('allsavings/index');
}
public function view(){
    if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])  && empty($_GET['reciept']) ){
        $today=date("'Y-m-d'");
        $this->allsaving("date=$today");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2'])  && empty($_GET['reciept']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->allsaving("date <='$date1'");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])  && empty($_GET['reciept']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $this->allsaving("date BETWEEN '$date1' AND '$date2' ");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['reciept'])){
         $reciept=$_GET['reciept'];
        $this->allsaving("recieptno= $reciept");
     }
    
}
public function save(Request $request){
    $Objallsavings=new allsavings();
    $id=$request['name'];
    DB::beginTransaction();
    $audit="";
    $Nam="";
try{
    $recp=DB::select("select recieptno from allsavings where recieptno=$request[paymentdetails] ");
if(count($recp)>0){
return ['isExists'=>'yes'];
}else{
    // Geting member id
$member=DB::select("select name from customers where id=$id");
foreach($member as $mem){
       $branch=auth()->user()->branchid;
       // Obtaining header number from purchaseheaders table 
       $objheaders= new purchaseheaders();
       $objheaders->transdates=date("Y-m-d", strtotime($request['date']));
       $objheaders->save();
      // extracting name
      $Nam=$mem->name;
      $audit=$Nam." ".
   $objallsavings= new allsavings();
   // saving into savings table for clear statements 
   $objsaving1= new savings();
   $objshareamt= new savings();
   $objallsavings->client_no=$request['name'];
   $objallsavings->postby='allsavings';
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
        $audit.=$result->productname."Deposit of ".$request['savingpdt1'];
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
   ################################# Shares ###############################################
if(str_replace( ',', '',$request['shares'])>0){
    $audit.=" ,Deposit of ".$request['shares'];
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
                    $objshareamt->date=date("Y-m-d", strtotime($request['date']));
                    $objshareamt->client_no=$request['name'];
                    $objshareamt->paydet=$request['paymentdetails'];
                    $objshareamt->isCharge=0;
                    $objshareamt->total=0;
                    $objshareamt->branchno=$branch;
                    $objshareamt->category='shares';
                    $objshareamt->moneyin=0;
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
########################################################### LOANS #######################################################
$amount=str_replace( ',', '',$request['loanpdt1']);
$mem1=DB::select("select intmethod,loancat,customers.name as client,sum(loan) as loanbal,sum(interestcredit) as creditinterest,abs(sum(if(interestcredit<0,interestcredit,0))) as interestcredit,abs(sum(if(loan<0,loan,0))) as loanpaid,abs(sum(if(loan<0,loan,0))) as loan,loanid from loantrans inner join customers on loantrans.memid=customers.id inner join loaninfos on loantrans.loanid=loaninfos.id where loantrans.isActive=1 and loantrans.branchno=$branch and memid=$id group by loanid order by loanid desc limit 1");
foreach($mem1 as $memberid){  
//GLOBAL VARIABLES
   $interest=0;
   $loan=0;

    $loandet=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where  branchno=$branch and loanid=$memberid->loanid  order by loanrepayments.id ");
   //$isIntbal=$this->getInterestRBal($memberid->loanid,$memberid->interestcredit,$memberid->creditinterest,$memberid->loanpaid);
   $interestiteration=$memberid->interestcredit;
   $loaniteration=$memberid->loanpaid;
   
   if($amount<=$memberid->creditinterest){
       $interest=$amount;
   }else if($amount>$memberid->creditinterest){
       $interest=$memberid->creditinterest;
       
       if($amount-$memberid->creditinterest>$memberid->loanbal){
        $loan=$memberid->loanbal;
       }else{
        $loan=$amount-$memberid->creditinterest;
      }
   }else if($memberid->creditinterest==0){
       $loan=$amount;
   }

$loanpdts=loanproducts::find($memberid->loancat);
   //inserting into purchase headers and transloans
               ###################################################################################################
               // Posting Interest Alone 
                       if(round($interest,0)>0 && $loan<=0){
                        $audit.=",Interest repay of ".number_format($interest,0);
                           if($loanpdts->loanpdt=="loanpdt1"){
                            $objallsavings->loanint1=$interest*-1;
                            $objallsavings->loanpdt1=0;
                            $objallsavings->headerid=$objheaders->id;
                            $objallsavings->save();
                            }
                                                   // posting in loantrans;
                           $objloantrans= new loantrans();
                           $objloantrans->memid=$request['name'];
                           $objloantrans->interestcredit=$interest*-1;
                           $objloantrans->loancredit=$interest*-1;
                           $objloantrans->date=date("Y-m-d", strtotime($request['date']));
                           $objloantrans->narration= "Interest Repayment";
                           $objloantrans->loanid=$memberid->loanid;
                           $objloantrans->isLoan=1;
                           $objloantrans->branchno=$branch;
                           $objloantrans->headerid=$objheaders->id;
                           $objloantrans->user=auth()->user()->name;
                           $objloantrans->isRepayment=1;
                           $objloantrans->paydet=$request['paymentdetails'];
                           $objloantrans->isActive=1;
                       
                          // DB::statement("update loantrans set isLastPay=0 where memid=$request[name]");
                          // $objloantrans->isLastPay=1;
                           $objloantrans->save();
                           // Inserting into accounts
                           $objloaninfo= new loaninfo();
                           $objloaninfo->isInterestPay=1;
                           $objloaninfo->save();
                ###########################################################################################################
               // inserting into accountrans  interest recivable 
               $objaccountrans=new accounttrans;
               $objaccountrans->purchaseheaderid=$objheaders->id;
               $objaccountrans->amount=$interest;
               $objaccountrans->total=$interest*-1;
               $objaccountrans->accountcode="122";
               $objaccountrans->narration= $memberid->client." -Interest Repayment";
               $objaccountrans->ttype="C";
               $objaccountrans->bracid=auth()->user()->branchid;
               $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
               $objaccountrans->cat='loanpdt1';
               $objaccountrans->save();
              
               // Recieving Account                      
               ############################################# Cash Account ##########################################################   
                           // inserting into accountrans  
                           $objaccountrans=new accounttrans;
                           $objaccountrans->purchaseheaderid=$objheaders->id;
                           $objaccountrans->amount=$interest;
                           $objaccountrans->accountcode=$loanpdts->disbursingac;// Disbursing Account
                           $objaccountrans->narration=$memberid->client." -Interest Repayment";
                           $objaccountrans->ttype="D";
                           $objaccountrans->total=$interest;
                           $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                           $objaccountrans->bracid=auth()->user()->branchid;
                           $objaccountrans->cat='loanpdt1';
                           $objaccountrans->save();
               ######################## Dammy Account for Editing #############################################
                           $objaccountrans=new accounttrans;
                           $objaccountrans->purchaseheaderid=$objheaders->id;
                           $objaccountrans->amount="";
                           $objaccountrans->accountcode=$loanpdts->accountcode;
                           $objaccountrans->narration="";
                           $objaccountrans->ttype="";
                           $objaccountrans->total="";
                           $objaccountrans->cat='loanpdt1';
                           $objaccountrans->transdate="";
                           $objaccountrans->bracid=auth()->user()->branchid;
                           $objaccountrans->save();
               
                         
               #######################################################################################################
               
                       }else if(round($interest,0)>0 && $loan >0){
                               // purchaseing into purchae headers 
                    $audit.=",Interest & Loan repay of ".number_format($interest,0).",".number_format($loan,0);
               ########################################################################################################
               if($loanpdts->loanpdt=="loanpdt1"){
               // $objsaving1->narration="Interest & Loan Repayment";
               $objallsavings->loanint1=$interest*-1;
               $objallsavings->loanpdt1=$loan*-1;
               $objallsavings->headerid=$objheaders->id;
               $objallsavings->save();
             }
                           $objloantrans= new loantrans();
                           $objloantrans->memid=$request['name'];
                           $objloantrans->interestcredit=$interest*-1;
                           $objloantrans->loancredit=($interest+$loan)*-1;
                           $objloantrans->date=date("Y-m-d", strtotime($request['date']));
                           $objloantrans->narration="Interest & Loan Repayment";
                           $objloantrans->loanid=$memberid->loanid;
                           $objloantrans->branchno=$branch;
                           $objloantrans->isLoan=1;
                           $objloantrans->headerid=$objheaders->id;
                           $objloantrans->isActive=1;
                           $objloantrans->user=auth()->user()->name;
                           $objloantrans->isRepayment=1;
                           $objloantrans->paydet=$request['paymentdetails'];
                           $objloantrans->loan=$loan*-1;
                           $objloantrans->save();
               
                            // Inserting into accounts
                            $objloaninfo= new loaninfo();
                            $objloaninfo->isInterestPay=1;
                            $objloaninfo->save();
                        
                            // Inserting into  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$interest+$loan;
                            $objaccountrans->total=$interest+$loan;
                            $objaccountrans->accountcode=$loanpdts->disbursingac;// Disburing Account
                            $objaccountrans->narration=$memberid->client." -Loan and Interest Repayment";
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='loanpdt1';
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->save();
                            // Inserting into income account
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$interest;
                            $objaccountrans->accountcode="122";
                            $objaccountrans->ttype="C";
                            $objaccountrans->cat='loanpdt1';
                            $objaccountrans->total=$interest*-1;
                            $objaccountrans->narration=$memberid->client." -Interest Repayment";
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->save();
                            // inserting / reducing loan account
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=abs($loan*-1);
                            $objaccountrans->cat='loanpdt1';
                            $objaccountrans->total=$loan*-1;
                            $objaccountrans->accountcode=$loanpdts->accountcode;// Accout code
                            $objaccountrans->narration=$memberid->client." -Loan Repayment ";
                            $objaccountrans->ttype="C";
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
                            echo "Hey";
               
               
                       }
                       else if($loan>0 && round($interest,0)<=0){
                          
                        $audit.=", Loan repay of ".number_format($loan,0);
               ############################################################################################################               
                          // $newloan=$request['amount']-$memberid->interestcredit;
                           //$newloan=$request['amount']-$newinterest;
                           if($loanpdts->loanpdt=="loanpdt1"){
                           // $objallsavings->narration="Loan Repayment";
                           $objallsavings->loanpdt1=$loan*-1;
                           $objallsavings->headerid=$objheaders->id;
                           $objallsavings->save();
                         }
                           
                           $objloantrans= new loantrans();
                           $objloantrans->memid=$request['name'];
                           $objloantrans->interestcredit=$interest*-1;
                           $objloantrans->loancredit=$interest*-1+$loan*-1;
                           $objloantrans->date=date("Y-m-d", strtotime($request['date']));
                           $objloantrans->narration=" Loan Repayment";
                           $objloantrans->user=auth()->user()->name;
                           $objloantrans->loanid=$memberid->loanid;
                           $objloantrans->isLoan=1;
                           $objloantrans->branchno=$branch;
                           $objloantrans->isRepayment=1;
                           $objloantrans->headerid=$objheaders->id;
                           $objloantrans->isActive=1;
                           $objloantrans->paydet=$request['paymentdetails'];
                           $objloantrans->loan=$loan*-1;
                           $objloantrans->save();
                           //get loan info
                           $objloaninfo= new loaninfo();
                            $objloaninfo->isInterestPay=1;
                            $objloaninfo->save();
                        
                            // Inserting into  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$loan;
                            $objaccountrans->total=$loan;
                            $objaccountrans->cat='loanpdt1';
                            $objaccountrans->accountcode=$loanpdts->disbursingac;// Disbursing Account
                            $objaccountrans->narration=$memberid->client." -Loan Repayment";
                            $objaccountrans->ttype="D";
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->save();
                            //inserting into loan account
                              // Inserting into  cash account 
                              $objaccountrans=new accounttrans;
                              $objaccountrans->purchaseheaderid=$objheaders->id;
                              $objaccountrans->amount=abs($loan*-1);
                              $objaccountrans->total=abs($loan*-1)*-1;
                              $objaccountrans->cat='loanpdt1';
                              $objaccountrans->accountcode=$loanpdts->accountcode; // Loan Code
                              $objaccountrans->ttype="C";
                              $objaccountrans->narration=$memberid->client." -Loan Repayment";
                              $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                              $objaccountrans->bracid=auth()->user()->branchid;
                              $objaccountrans->save();
               
                ######################## Dammy Account for Editing #############################################
                           $objaccountrans=new accounttrans;
                           $objaccountrans->purchaseheaderid=$objheaders->id;
                           $objaccountrans->amount="";
                           $objaccountrans->accountcode="122";
                           $objaccountrans->narration="";
                           $objaccountrans->ttype="";
                           $objaccountrans->total="";
                           $objaccountrans->cat='loanpdt1';
                           $objaccountrans->transdate="";
                           $objaccountrans->bracid=auth()->user()->branchid;
                           $objaccountrans->save();
                       }

                       if($interest==0 && $loan==0){
                        if($loanpdts->loanpdt=="loanpdt1"){
                             // Inserting into  cash account 
                             $objaccountrans=new accounttrans;
                             $objaccountrans->purchaseheaderid=$objheaders->id;
                             $objaccountrans->amount=0;
                             $objaccountrans->total=0;
                             $objaccountrans->cat='loanpdt1';
                             $objaccountrans->accountcode=$loanpdts->disbursingac;// Disbursing Account
                             $objaccountrans->narration=$memberid->client." -Loan Repayment";
                             $objaccountrans->ttype="D";
                             $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                             $objaccountrans->bracid=auth()->user()->branchid;
                             $objaccountrans->save();
                             //inserting into loan account
                               // Inserting into  cash account 
                               $objaccountrans=new accounttrans;
                               $objaccountrans->purchaseheaderid=$objheaders->id;
                               $objaccountrans->amount=0;
                               $objaccountrans->total=0;
                               $objaccountrans->cat='loanpdt1';
                               $objaccountrans->accountcode=$loanpdts->accountcode; // Loan Code
                               $objaccountrans->ttype="C";
                               $objaccountrans->narration=$memberid->client." -Loan Repayment";
                               $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                               $objaccountrans->bracid=auth()->user()->branchid;
                               $objaccountrans->save();
                
                 ######################## Dammy Account for Editing #############################################
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount="";
                            $objaccountrans->accountcode="122";
                            $objaccountrans->narration="";
                            $objaccountrans->ttype="";
                            $objaccountrans->total="";
                            $objaccountrans->cat='loanpdt1';
                            $objaccountrans->transdate="";
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->save();
                            
                        }
                       }


                       

}  

################################################# ANNUAL SUBSCRIPTIONS ######################################
if(str_replace( ',', '',$request['ansub'])>0){
    $audit.=",Ansub  of ".$request['ansub'];
    ####################### START OF SAVING PDT 1 ######################################################
        $objallsavings->ansub=str_replace( ',', '',$request['ansub']);
        $rs=savingdefinations::where('savingpdt','=','ansub')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
            $narration= $result->productname;
            
            // Accounts 
                        // inserting into accountrans  savings 1 
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=str_replace( ',', '',$request['ansub']);
         $objaccountrans->total=str_replace( ',', '',$request['ansub']);
         $objaccountrans->accountcode=$result->savingac;
         $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
         $objaccountrans->ttype="C";
         $objaccountrans->bracid=auth()->user()->branchid;
         $objaccountrans->cat='ansub';
         $objaccountrans->stockidentify=$request['paymentdetails'];
         $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
         $objaccountrans->save();
          // inserting into accountrans  cash account 
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=str_replace( ',', '',$request['ansub']);
         $objaccountrans->total=str_replace( ',', '',$request['ansub']);
         $objaccountrans->accountcode=$result->operatingac;
         $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
         $objaccountrans->ttype="D";
         $objaccountrans->cat='ansub';
         $objaccountrans->stockidentify=$request['paymentdetails'];
         $objaccountrans->bracid=auth()->user()->branchid;
         $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
         $objaccountrans->save();
                     // savings Table 
        /*$objsaving1->date=date("Y-m-d", strtotime($request['date']));
        $objsaving1->client_no=$request['name'];
        $objsaving1->paydet=$request['paymentdetails'];
        $objsaving1->isCharge=0;
        $objsaving1->branchno=$branch;
        $objsaving1->total=str_replace( ',', '',$request['savingpdt1']);
        $objsaving1->category='savingpdt1';
        $objsaving1->moneyin=str_replace( ',', '',$request['savingpdt1']);*/
        }
    
        
    ####################### END OF SAVING PDT 1 ######################################################
       }else{
    
        $rs=savingdefinations::where('savingpdt','=','ansub')->where('branchno','=',$branch)->get();
        foreach($rs as $result){  
          /*  $objsaving1->date=date("Y-m-d", strtotime($request['date']));
            $objsaving1->client_no=$request['name'];
            $objsaving1->paydet=$request['paymentdetails'];
            $objsaving1->isCharge=0;
            $objsaving1->total=0;
            $objsaving1->branchno=$branch;
            $objsaving1->category='savingpdt1';
            $objsaving1->moneyin=0;*/
        // Place holders 
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$objheaders->id;
        $objaccountrans->amount=0;
        $objaccountrans->total=0;
        $objaccountrans->accountcode=$result->savingac;
        $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
        $objaccountrans->ttype="C";
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->cat='ansub';
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
        $objaccountrans->cat='ansub';
        $objaccountrans->stockidentify=$request['paymentdetails'];
        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
        $objaccountrans->save();
        }
        
       }
################################################# MEMBERSHIP  ######################################
if(str_replace( ',', '',$request['memship'])>0){
    $audit.=",Membership  of ".$request['memship'];
    ####################### START OF SAVING PDT 1 ######################################################
        $objallsavings->memship=str_replace( ',', '',$request['memship']);
        $rs=savingdefinations::where('savingpdt','=','memship')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
            $narration= $result->productname;
            
            // Accounts 
                        // inserting into accountrans  savings 1 
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=str_replace( ',', '',$request['memship']);
         $objaccountrans->total=str_replace( ',', '',$request['memship']);
         $objaccountrans->accountcode=$result->savingac;
         $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
         $objaccountrans->ttype="C";
         $objaccountrans->bracid=auth()->user()->branchid;
         $objaccountrans->cat='memship';
         $objaccountrans->stockidentify=$request['paymentdetails'];
         $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
         $objaccountrans->save();
          // inserting into accountrans  cash account 
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=str_replace( ',', '',$request['memship']);
         $objaccountrans->total=str_replace( ',', '',$request['memship']);
         $objaccountrans->accountcode=$result->operatingac;
         $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
         $objaccountrans->ttype="D";
         $objaccountrans->cat='memship';
         $objaccountrans->stockidentify=$request['paymentdetails'];
         $objaccountrans->bracid=auth()->user()->branchid;
         $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
         $objaccountrans->save();
                     // savings Table 
        /*$objsaving1->date=date("Y-m-d", strtotime($request['date']));
        $objsaving1->client_no=$request['name'];
        $objsaving1->paydet=$request['paymentdetails'];
        $objsaving1->isCharge=0;
        $objsaving1->branchno=$branch;
        $objsaving1->total=str_replace( ',', '',$request['savingpdt1']);
        $objsaving1->category='savingpdt1';
        $objsaving1->moneyin=str_replace( ',', '',$request['savingpdt1']);*/
        }
    
        
    ####################### END OF SAVING PDT 1 ######################################################
       }else{
    
        $rs=savingdefinations::where('savingpdt','=','memship')->where('branchno','=',$branch)->get();
        foreach($rs as $result){  
          /*  $objsaving1->date=date("Y-m-d", strtotime($request['date']));
            $objsaving1->client_no=$request['name'];
            $objsaving1->paydet=$request['paymentdetails'];
            $objsaving1->isCharge=0;
            $objsaving1->total=0;
            $objsaving1->branchno=$branch;
            $objsaving1->category='savingpdt1';
            $objsaving1->moneyin=0;*/
        // Place holders 
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$objheaders->id;
        $objaccountrans->amount=0;
        $objaccountrans->total=0;
        $objaccountrans->accountcode=$result->savingac;
        $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
        $objaccountrans->ttype="C";
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->cat='memship';
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
        $objaccountrans->cat='memship';
        $objaccountrans->stockidentify=$request['paymentdetails'];
        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
        $objaccountrans->save();
        }
        
       }
// General savings
   $objallsavings->narration=$narration." Deposit";
   $objallsavings->headerid=$objheaders->id;
   $objallsavings->save();
   // saving id in savings table
   $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs1->count()>0){
    $objsaving1->savingsid=$objallsavings->id; 
    $objsaving1->save();
   }
   $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs4->count()>0){
    $objshareamt->savingsid=$objallsavings->id; 
    $objshareamt->save();
   }
}
}
$objaudits= new audits();
$objaudits->event=$audit." Reciept NO: ".$request['paymentdetails'];
$objaudits->branchno=auth()->user()->branchid;
$objaudits->username=auth()->user()->name;
$objaudits->save();
}catch(\Exception $e){
    DB::rollBack();
    echo "Failed ".$e;
}
DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    $clientid=$request['name'];
    $branch=auth()->user()->branchid;
   
    DB::beginTransaction();
try{
    // Geting member id
$member=DB::select("select id,name from customers where name='$clientid' ");
foreach($member as $memb){
    purchaseheaders::where('id','=',$request['headerid'])->update([
     'transdates'=>date("Y-m-d", strtotime($request['date'])),
    ]);
   $objallsavings= allsavings::find($id);
   $objallsavings->client_no=$memb->id;
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
              'narration'=>$memb->name." -Cash Deposits ".$result->productname,
              'stockidentify'=>$request['paymentdetails'],
              'transdate'=>date("Y-m-d", strtotime($request['date'])),
              'ttype'=>'C',

          ]) ;        
      // inserting into accountrans  cash account 
      accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt1')->update([
        'amount'=>str_replace( ',', '',$request['savingpdt1']),
        'total'=>str_replace( ',', '',$request['savingpdt1']),
        'narration'=>$memb->name." -Cash Deposits ".$result->productname,
        'stockidentify'=>$request['paymentdetails'],
        'ttype'=>'D',
        'transdate'=>date("Y-m-d", strtotime($request['date']))

    ]) ;
    }
    // savings Table 
    savings::where('savingsid','=',$id)->where('category','=','savingpdt1')->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$memb->id,
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
   // General settings 

       // Shares
       if(str_replace( ',', '',$request['shares'])>0){
        ################################# SHARES  #########################################
                    $objallsavings->shares=str_replace( ',', '',$request['shares']);
                    $rs=savingdefinations::where('savingpdt','=','shares')->where('branchno','=',$branch)->get();
                   
                    foreach($rs as $result){
                        $narration.=" & ". $result->productname;
                    }
                                
                   $chuq= savings::where('savingsid','=',$id)->where('category','=','shares')->update([
                        'date'=>date('Y-m-d', strtotime($request['date'])),
                        'client_no'=>$memb->id,
                        'paydet'=>$request['paymentdetails'],
                        'total'=>str_replace( ',', '',$request['shares']),
                        'moneyin'=>str_replace( ',', '',$request['shares']),
                        'narration'=>'Cash Deposit-'.$result->productname,
                        ]); 
                        if($chuq==0){
                            savings::where('savingsid','=',$id)->whereNull('category')->update([
                                'date'=>date('Y-m-d', strtotime($request['date'])),
                                'client_no'=>$memb->id,
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
                            'narration'=>$memb->name." -Cash Deposits ".$result->productname,
                            'stockidentify'=>$request['paymentdetails'],
                            'ttype'=>'C',
                            'transdate'=>date("Y-m-d", strtotime($request['date']))
        
                        ]) ;        
                    // inserting into accountrans  cash account 
                    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','shares')->update([
                      'amount'=>str_replace( ',', '',$request['shares']),
                      'total'=>str_replace( ',', '',$request['shares']),
                      'narration'=>$memb->name." -Cash Deposits ".$result->productname,
                      'stockidentify'=>$request['paymentdetails'],
                      'ttype'=>'D',
                      'transdate'=>date("Y-m-d", strtotime($request['date']))
        
                  ]) ;
                 
        ############################# SHAREs ######################################################
                   }else{
                    // Delete if amount is zero
                    savings::where('savingsid','=',$id)->where('category','=','shares')->update([
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
 ################################################################ EDITING LOANS ##############################
    $head=$request['headerid'];
        $amount=str_replace( ',', '',$request['loanpdt1']);
        $memz=DB::select("select intmethod,loancat,sum(loan) as loanbal, customers.id as clientid,customers.name as client,sum(interestcredit) as creditinterest,abs(sum(if(interestcredit<0,interestcredit,0))) as interestcredit,abs(sum(if(loan<0,loan,0))) as loanpaid,abs(sum(if(loan<0,loan,0))) as loan,loanid from loantrans inner join customers on loantrans.memid=customers.id inner join loaninfos on loantrans.loanid=loaninfos.id where loantrans.isActive=1 and name='$memb->name' and loantrans.branchno=$branch and loantrans.headerid!=$head");
        foreach($memz as $memberid){
    //GLOBAL VARIABLES
    $interest=0;
    $loan=0;
    
       if($amount<=$memberid->creditinterest){
        $interest=$amount;
    }else if($amount>$memberid->creditinterest){
        $interest=$memberid->creditinterest;
        
        if($amount-$memberid->creditinterest>$memberid->loanbal){
         $loan=$memberid->loanbal;
        }else{
         $loan=$amount-$memberid->creditinterest;
       }
    }else if($memberid->creditinterest==0){
        $loan=$amount;
    }
    
    // code for taking off only the interest 
    
    $loanpdts=loanproducts::find($memberid->loancat);
    ###################################################################################################
            if($interest>0 && $loan<=0){
                if($loanpdts->loanpdt=="loanpdt1"){
                    $objallsavings->loanint1=$interest*-1;
                    $objallsavings->loanpdt1=0;
                   // $objallsavings->headerid=$objpurchaseheaders->id;
                   // $objallsavings->save();
                 }
                
                // Emptying already filled up fields in the database
                loantrans::where('headerid','=',$request['headerid'])->update([
                    'memid'=>'',
                    'interestcredit'=>'',
                    'loancredit'=>'',
                    'date'=>'',
                    'paydet'=>'',
                    'isActive'=>'',
                    'narration'=>'',
    
                ]);
                loantrans::where('headerid','=',$request['headerid'])->update([
                    'memid'=>$memberid->clientid,
                    'interestcredit'=>$interest*-1,
                    'loancredit'=>$interest*-1,
                    'date'=>date("Y-m-d", strtotime($request['date'])),
                    'paydet'=>$request['paymentdetails'],
                    'isActive'=>1,
                    'narration'=>$memberid->client.' -Interest Repayment',
                    'loan'=>0,
    
                ]);
                
              ####################### stopped here today for editing  11/09/2019  ###################################
              
     ###########################################################################################################
     //updating or emptying the fields in the database 
     accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('bracid','=',$branch)->update([
        'amount'=>'',
        'ttype'=>'',
        'total'=>'',
        'narration'=>'',
        'transdate'=>'',
        //'bracid'=>auth()->user()->branchid,
        
        ]);
    // inserting into accountrans  interest recivable 
    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',122)->where('bracid','=',$branch)->update([
    'amount'=>$interest,
    'total'=>$interest*-1,
    'narration'=>$memberid->client.' -Interest Repayment',
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    'ttype'=>'C',
    'bracid'=>auth()->user()->branchid,
    
    ]);
    
    // Recieving Account                      
    ############################################# Cash Account ##########################################################   
                // inserting into accountrans  
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',$loanpdts->disbursingac)->where('bracid','=',$branch)->update([
                    'amount'=>$interest,
                    'narration'=>$memberid->client.' -Interest Repayment',
                    'total'=>$interest,
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'ttype'=>'D',
                    'bracid'=>auth()->user()->branchid,
    
    
                ]);
    
                                        // Dammy Account 
                                        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',$loanpdts->accountcode)->where('bracid','=',$branch)->update([
                                            'amount'=>'',
                                            'total'=>'',
                                            'narration'=>'',
                                            'ttype'=>'',
                                            'transdate'=>'',
                                            'bracid'=>auth()->user()->branchid,
                            
                                        ]);
             
    
    #######################################################################################################
    
                                         }else if($interest>0 && $loan >0 ){
                                            if($loanpdts->loanpdt=="loanpdt1"){
                                                $narration="Interest & Loan Repayment";
                                                $objallsavings->loanint1=$interest*-1;
                                                $objallsavings->loanpdt1=$loan*-1;
                                                //$objallsavings->headerid=$objpurchaseheaders->id;
                                            }
                
                    // purchaseing into purchae headers 
                   loantrans::where('headerid','=',$request['headerid'])->where('branchno','=',$branch)->update([
                        'memid'=>'',
                        'interestcredit'=>'',
                        'loancredit'=>'',
                        'date'=>'',
                        'paydet'=>'',
                        'isActive'=>'',
                        'narration'=>'',
                        'loan'=>'',
        
                    ]);
                   
    
    ########################################################################################################
                
               // $newloan=$request['amount']-$memberid->interestcredit;
               // echo $newloan;
               
               //$newloan=$request['amount']-$newinterest;
                loantrans::where('headerid','=',$request['headerid'])->where('branchno','=',$branch)->update([
                    'memid'=>$memberid->clientid,
                    'interestcredit'=>$interest*-1,
                    'loancredit'=>$interest*-1+$loan*-1,
                    'date'=>date("Y-m-d", strtotime($request['date'])),
                    'isRepayment'=>1,
                    'narration'=>'Interest & Loan Repayment',
                    'paydet'=>$request['paymentdetails'],
                    'loan'=>$loan*-1,
                    'isActive'=>1,
    
    
                ]);
         
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',$loanpdts->accountcode)->where('bracid','=',$branch)->update([
                    'amount'=>$loan,
                    'ttype'=>'C',
                    'total'=>$loan*-1,
                    'narration'=>$memberid->client.' -Loan  Repayment',
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'bracid'=>auth()->user()->branchid,
                    
                    ]);
             
                 // Inserting into  cash account 
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',$loanpdts->disbursingac)->where('bracid','=',$branch)->update([
                     'amount'=>abs($interest*-1+$loan*-1),
                     'total'=>abs($interest*-1+$loan*-1),
                     'narration'=>$memberid->client.' -Loan and Interest Repayment',
                     'ttype'=>'D',
                     'transdate'=>date("Y-m-d", strtotime($request['date'])),
                     'bracid'=>auth()->user()->branchid,
    
    
    
                 ]);
                
                 // Inserting into income account
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',122)->where('bracid','=',$branch)->update([
                    'amount'=>abs($interest*-1),
                    'total'=>abs($interest*-1)*-1,
                    'narration'=>$memberid->client.' -Interest Repayment',
                    'ttype'=>'C',
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'bracid'=>auth()->user()->branchid,
    
    
    
                ]);
              
                 // inserting / reducing loan account
    
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','loanpdt1')->where('accountcode','=',$loanpdts->accountcode)->where('bracid','=',$branch)->update([
                    'amount'=>abs($loan*-1),
                    'total'=>abs($loan*-1)*-1,
                    'narration'=>$memberid->client.' -Loan Repayment',
                    'ttype'=>'C',
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'bracid'=>auth()->user()->branchid,
    
    
    
                ]);
    
     
    
    
                 }
            else if($loan>0 && $interest<=0){
                if($loanpdts->loanpdt=="loanpdt1"){
                    $narration="Loan Repayment";
                    $objallsavings->loanpdt1=$loan*-1;
 
                 }
                loantrans::where('headerid','=',$request['headerid'])->where('branchno','=',$branch)->update([
                    'memid'=>'',
                    'interestcredit'=>'',
                    'loancredit'=>'',
                    'date'=>'',
                    'paydet'=>'',
                    'isActive'=>'',
                    'narration'=>'',
    
                ]);          
    ############################################################################################################ 
                 
    
               // $newloan=$request['amount']-$memberid->interestcredit;
                //$newloan=$request['amount']-$newinterest;
                loantrans::where('headerid','=',$request['headerid'])->where('branchno','=',$branch)->update([
                    'memid'=>$memberid->clientid,
                    'loan'=>$interest*-1+$loan*-1,
                    'loancredit'=>$interest*-1+$loan*-1,
                    'date'=>date("Y-m-d", strtotime($request['date'])),
                    'paydet'=>$request['paymentdetails'],
                    'isActive'=>1,
                    'narration'=>$memberid->client.' -Loan Repayment',
    
                ]);
               
    
                accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('bracid','=',$branch)->where('cat','=','loanpdt1')->update([
                    'amount'=>'',
                    'ttype'=>'',
                    'total'=>'',
                    'narration'=>'',
                    'transdate'=>'',
                    'bracid'=>auth()->user()->branchid,
                    
                    ]);
                 // Inserting into  cash account 
    
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$loanpdts->disbursingac)->where('bracid','=',$branch)->where('cat','=','loanpdt1')->update([
                    'amount'=>abs($interest*-1+$loan*-1),
                    'total'=>abs($interest*-1+$loan*-1),
                    'narration'=>$memberid->client.' -Loan Repayment',
                    'ttype'=>'D',
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'bracid'=>auth()->user()->branchid,
                ]);
              
                 //inserting into loan account
                   // Inserting into  cash account 
                   accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$loanpdts->accountcode)->where('bracid','=',$branch)->where('cat','=','loanpdt1')->update([
                    'amount'=>abs($loan*-1),
                    'total'=>abs($loan*-1)*-1,
                    'narration'=>$memberid->client.' -Loan Repayment',
                    'ttype'=>'C',
                    'transdate'=>date("Y-m-d", strtotime($request['date'])),
                    'bracid'=>auth()->user()->branchid,
                ]);
    
                            // Dammy Account 
                            accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','122')->where('bracid','=',$branch)->where('cat','=','loanpdt1')->update([
                                'amount'=>'',
                                'total'=>'',
                                'narration'=>'',
                                'ttype'=>'',
                                'transdate'=>'',
                                'bracid'=>auth()->user()->branchid,
                
                            ]);
                
    
            }
    
        }
################################################# ANNUAL SUBSCRIPTIONS ######################################
if(str_replace( ',', '',$request['ansub'])>0){
    ####################### START OF SAVING PDT 1 ######################################################
        $objallsavings->ansub=str_replace( ',', '',$request['ansub']);
        $rs=savingdefinations::where('savingpdt','=','ansub')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
            $narration= $result->productname;
            

         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','ansub')->where('accountcode','=',$result->savingac)->where('bracid','=',$branch)->update([
            'amount'=>str_replace( ',', '',$request['ansub']),
            'total'=>str_replace( ',', '',$request['ansub']),
            'narration'=>$memberid->client.' -Annual Sub Payment',
            'transdate'=>date("Y-m-d", strtotime($request['date'])),
            'ttype'=>'C',
            'bracid'=>auth()->user()->branchid,
            
            ]);
          // inserting into accountrans  cash account 
          
         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','ansub')->where('accountcode','=',$result->operatingac)->where('bracid','=',$branch)->update([
            'amount'=>str_replace( ',', '',$request['ansub']),
            'total'=>str_replace( ',', '',$request['ansub']),
            'narration'=>$memberid->client.' -Annual Sub Payment',
            'transdate'=>date("Y-m-d", strtotime($request['date'])),
            'ttype'=>'D',
            'bracid'=>auth()->user()->branchid,
            
            ]);


        }
    
        
    ####################### END OF SAVING PDT 1 ######################################################
       }else{
        $objallsavings->ansub=0;
        $rs=savingdefinations::where('savingpdt','=','ansub')->where('branchno','=',$branch)->get();
        foreach($rs as $result){  

        // Place holders 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','ansub')->where('accountcode','=',$result->savingac)->where('bracid','=',$branch)->update([
            'amount'=>0,
            'total'=>0,
            'narration'=>'',
            'transdate'=>'',
            'ttype'=>'C',
            'bracid'=>auth()->user()->branchid,
            
            ]);
        // second place holder 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','ansub')->where('accountcode','=',$result->operatingac)->where('bracid','=',$branch)->update([
            'amount'=>0,
            'total'=>0,
            'narration'=>'',
            'transdate'=>'',
            'ttype'=>'D',
            'bracid'=>auth()->user()->branchid,
            
            ]);
        }
        
       }

       ################################################# Member ship fees ######################################
if(str_replace( ',', '',$request['memship'])>0){
    ####################### START OF SAVING PDT 1 ######################################################
        $objallsavings->memship=str_replace( ',', '',$request['memship']);
        $rs=savingdefinations::where('savingpdt','=','memship')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
            $narration= $result->productname;
            

         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','memship')->where('accountcode','=',$result->savingac)->where('bracid','=',$branch)->update([
            'amount'=>str_replace( ',', '',$request['memship']),
            'total'=>str_replace( ',', '',$request['memship']),
            'narration'=>$memberid->client.' -Membership  Payment',
            'transdate'=>date("Y-m-d", strtotime($request['date'])),
            'ttype'=>'C',
            'bracid'=>auth()->user()->branchid,
            
            ]);
          // inserting into accountrans  cash account 
          
         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','memship')->where('accountcode','=',$result->operatingac)->where('bracid','=',$branch)->update([
            'amount'=>str_replace( ',', '',$request['memship']),
            'total'=>str_replace( ',', '',$request['memship']),
            'narration'=>$memberid->client.' -Membership Fees',
            'transdate'=>date("Y-m-d", strtotime($request['date'])),
            'ttype'=>'D',
            'bracid'=>auth()->user()->branchid,
            
            ]);


        }
    
        
    ####################### END OF SAVING PDT 1 ######################################################
       }else{
    $objallsavings->memship=0;
        $rs=savingdefinations::where('savingpdt','=','memship')->where('branchno','=',$branch)->get();
        foreach($rs as $result){  

        // Place holders 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','memship')->where('accountcode','=',$result->savingac)->where('bracid','=',$branch)->update([
            'amount'=>0,
            'total'=>0,
            'narration'=>'',
            'transdate'=>'',
            'ttype'=>'C',
            'bracid'=>auth()->user()->branchid,
            
            ]);
        // second place holder 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('cat','=','memship')->where('accountcode','=',$result->operatingac)->where('bracid','=',$branch)->update([
            'amount'=>0,
            'total'=>0,
            'narration'=>'',
            'transdate'=>'',
            'ttype'=>'D',
            'bracid'=>auth()->user()->branchid,
            
            ]);
        }
        
       }
   //saving in allsavings tble
   $objallsavings->narration=$narration." Deposit";
   $objallsavings->save();
}
}catch(\Exception $e){
    DB::rollBack();
    echo "Faied ".$e;
}
DB::commit();
        
}
 public function destroy($id){
    $user=auth()->user()->name;
    DB::beginTransaction();
   try{
       $deleteitems=DB::select("select id,headerid from allsavings where id=$id");
       foreach($deleteitems as $items){
       DB::delete("delete from purchaseheaders where id=$items->headerid");
       DB::delete("delete from savings where savingsid=$id");
       DB::delete("delete from accounttrans where purchaseheaderid=$items->headerid");
       DB::delete("delete from allsavings where id=$id");
       DB::delete("delete from loantrans where headerid=$items->headerid");

       }


   }catch(\Exception $e){
       DB::rollback();
       echo "Failed".$e;
   }
   DB::commit();






    }

public function viewcombo(){


    return allsavings::all();
}
public function allsaving($where){
    $branch=auth()->user()->branchid;
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch and postby='allsavings' and $where");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select format(if(loanpdt1<0,abs(loanpdt1),0)+if(loanint1<0,abs(loanint1),0)+ansub+memship+if(savingpdt1>0,savingpdt1,0)+if(shares>0,shares,0),0) as total, format(if(loanint1<0,abs(loanint1),0),0) as loanint1,headerid, format(if(loanpdt1<0,abs(loanpdt1),0),0) as loan, format(if(loanint1<0,abs(loanint1),0)+if(loanpdt1<0,abs(loanpdt1),0),0) as loanpdt1,format(ansub,0) as ansub,format(memship,0) memship, date,narration,name,recieptno,if(savingpdt1>0,format(savingpdt1,0),0) as savingpdt1,if(shares>0,format(shares,0),0) as shares,allsavings.id as id,DATE_FORMAT(allsavings.date,'%d-%m-%Y') as date from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch and postby='allsavings' and $where limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
    //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(if(loanint1<0,abs(loanint1),0)),0) as loanint1,headerid, format(sum(if(loanpdt1<0,abs(loanpdt1),0)),0) as loan, format(if(loanint1<0,abs(loanint1),0)+if(loanpdt1<0,abs(loanpdt1),0),0) as loanpdt1,format(sum(ansub),0) as ansub,format(sum(memship),0) memship,if(savingpdt1>0,format(sum(savingpdt1),0),0) as savingpdt1,format(sum(if(shares>0,shares,0)),0) as shares
,format(sum(if(loanint1<0,abs(loanint1),0))+sum(if(loanpdt1<0,abs(loanpdt1),0))+sum(ansub)+sum(memship)+if(savingpdt1>0,sum(savingpdt1),0)+sum(if(shares>0,shares,0)) ,0)as total 

from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch and postby='allsavings' and $where");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);
}

public function threemonths(){
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $a=0;
   $months= DB::select("select name,acno,format(savingpdt1,0)as savingpdt1,client_no,max(DATE_FORMAT(date,'%d-%m-%Y')) as date from allsavings inner join customers on customers.id=allsavings.client_no where savingpdt1>0  group by client_no order by max(date)");
  
   foreach($months as $mon){
       
       //echo $mon->date;
     // echo  date('Y-m-d',strtotime($mon->date."+3 months"))."<br><br>";
      $today=date('Y-m-d');
      
     // echo strtotime($today);;
     if(strtotime($today)>strtotime(date('Y-m-d',strtotime($mon->date."+3 months")))){
        //$results['total']=$a;
        
      // echo $mon->client_no;
       $results['rows'][]=$mon;
       $a=$a+1;
     }

   }
   $results['total']=$a;
   echo json_encode($results);
}
public function threemonthsview(){
    return view('threemonths/index');
}
public function memshipdefaulters(){
    return view("memshipdefaulters/index");
}
public function viewsharesdefaulters(){
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['client'])){
       $this->defaults('memship');
       //echo "Me";
    }
    else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['client'])){
        $g=$_GET['client'];
        $this->defaults($g);

    }
}
public function defaults($field){
    $branch=auth()->user()->branchid;
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("SELECT COUNT(*) as count from ( select  if($field is Null,0,sum($field )) paid,name,acno,10000-if($field  is Null,0,sum($field )) as bal
    FROM customers
    left outer JOIN allsavings ON customers.id=allsavings.client_no where branchnumber=$branch  group by customers.id having paid<10000  
    ORDER BY `paid`  DESC) as das ");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("SELECT if($field  is Null,0,sum($field )) pays,  format(if($field  is Null,0,sum($field )),0) paid,name,acno,format(10000-if($field  is Null,0,sum($field )),0) as bal
    FROM customers
    left outer JOIN allsavings ON customers.id=allsavings.client_no  where branchnumber=$branch   group by customers.id having pays<10000  
    ORDER BY `paid`  DESC limit $offset,$rows");
    $rst->execute();
    DB::statement("create or replace view totaldefaults as SELECT if($field  is Null,0,sum($field )) paid,name,acno,10000-if($field  is Null,0,sum($field )) as bal
    FROM customers left outer JOIN allsavings ON customers.id=allsavings.client_no  where branchnumber=$branch   group by customers.id having paid<10000  
    ORDER BY `paid`");

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
    //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(paid),0) as paid, format(sum(bal),0) as bal from totaldefaults ");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);
}
}