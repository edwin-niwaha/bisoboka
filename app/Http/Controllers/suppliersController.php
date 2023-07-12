<?php 
 namespace App\Http\Controllers;
 date_default_timezone_set("Africa/Nairobi");
use Illuminate\Http\Request;
 use App\memberinfos;
 use App\loantrans;
 use App\loaninfo;
 use App\purchaseheaders;
 use App\accounttrans;
 use App\loanschedules;
 use App\loanproducts;
 use App\allsavings;
 use App\savings;
 use App\loanfees;
 use App\loanrepayments;
 use Illuminate\Support\Facades\DB;
 class suppliersController extends Controller{

public function index(){
$bra=auth()->user()->branchid;  
$loanfees=loanfees::where('isActive','=','1')->where('branchno',$bra)->get();
    return view('suppliers/index1')->with('loanfees',$loanfees);
}
public function view(){
   
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2'])){
       
        $today=date("'Y-m-d'");//, strtotime($_GET['date1']));
       // $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $this->balShit(" BETWEEN '$date1' AND '$date2' ");
        $this->loandisbursement(" and date=$today");

       
     
     }
    
     if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->loandisbursement("and date  BETWEEN '$date1' AND '$date2' ");
     
     }
   
     /*if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) && !empty($_GET['branch'])){
        $branch=$_GET['branch'];
        $today=date("'Y-m-d'");
        $this->authloans(" and users.branchid=$branch and date=$today");;

     }
     if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['date1']) && !empty($_GET['date2']) && !empty($_GET['branch'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $branch=$_GET['branch'];
        $this->authloans("and date  BETWEEN '$date1' AND '$date2' AND  users.branchid=$branch ");;

     }*/
    

    
  

   

}
public function save(Request $request){

    DB::beginTransaction();
    try{
        $memberid=$request['name'];
        $clientname="";
        $id=DB::select("select customers.name as client,sum(loan) as loan from loantrans inner join customers on loantrans.memid=customers.id where loantrans.isActive=1 and memid=$memberid");
        foreach($id as $finalid){
            if($finalid->loan!=0 ){
                return ['results'=>'true'];
            }else{
        $clientname=$finalid->client;
        // Inserting into purchaseheaders 
        $objheaders= new purchaseheaders();
        $objheaders->transdates=date("Y-m-d", strtotime($request['date']));
        $objheaders->isActive=1;
        $objheaders->save();
        $branch=auth()->user()->branchid;
         
        // Inserting into Loaninfos 
        $objloaninfo= new loaninfo();
        $objloaninfo->loanrepay=$request['repay'];
        $objloaninfo->period=$request['mode'];
        $objloaninfo->loaninterest=$request['interest'];
        $objloaninfo->mode=$request['branch'];
        $objloaninfo->collateral=$request['security'];
        $objloaninfo->guanter=$request['gauranter'];
        $objloaninfo->memeberid=$request['name'];
        $objloaninfo->intmethod=$request['method'];
        $objloaninfo->loancat=$request['loancat'];
        $objloaninfo->loanfee1=$request['loanfee1'];
        $objloaninfo->loanfee2=$request['loanfee2'];
        $objloaninfo->loanfee3=$request['loanfee3'];
        $objloaninfo->save();
        
        #######################  Loan Schedule ##########################################
        $months=0;
        $rate=0;
        $totalinterest=0;
        $newinterest=0;
        $newloan=0;
        if($request['mode']=='Month'){
            $months=$request['repay'];
            $rate=$request['interest']/100; 
        }else if($request['mode']=='Year'){
            $rate=($request['interest']/100)/12;
            $months=$request['repay']*12;
        }
        $objloancat=loanproducts::find($request['loancat']);// just added
        if($request['method']==2){
            $interestbal=0;
            $loanbal=0;
            $newinterest=str_replace( ',', '',$request['amount'])*$rate;
            $newloan=str_replace( ',', '',$request['amount'])/$months;
            $total=$newinterest+$newloan;
               // intial loan schedule
               $loansch1 = new loanschedules();
               $loansch1->scheduledate=date('Y-m-d',strtotime($request['date']));
               $loansch1->branchno=$branch;
               $loansch1->loanid=$objloaninfo->id;
               $loansch1->runningbal=str_replace( ',', '',$request['amount'])+(($request['interest']/100)*str_replace( ',', '',$request['amount']))*$request['repay'];
               $loansch1->save();
            for($x=1;$x<=$months;$x++){
                $newdates=date('Y-m-d',strtotime($request['date'] ."+$x months"));
              // Second loan schedule posting
                $loansch= new loanschedules();
                $loansch->loanid=$objloaninfo->id;
                $loansch->branchno=$branch;
                $loansch->loanamount=$newloan;
                $loansch->interest=$newinterest;;
                $loansch->loancat=$objloancat->loanpdt;// just added 25-05-2020
                $loansch->intmeth=2; // today
                $loansch->scheduledate=$newdates;
                $loansch->runningbal=($newloan+$newinterest)*-1;
                $loansch->nopayments=$x;
                $loansch->save();
                $totalinterest=$totalinterest+$newinterest;
                // Posting into Loan Repayments 
                $loanrepay= new loanrepayments();
                $interestbal=$interestbal+$newinterest;
                $loanbal=$loanbal+$newloan;
                $loanrepay->branchno=$branch;
                $loanrepay->intrunbal=$interestbal;
                $loanrepay->loanrunbal=$loanbal;
                $loanrepay->loanid=$objloaninfo->id;
                $loanrepay->save();
            }
        }

        if($request['method']==1){
            $loanamount=str_replace( ',', '',$request['amount']);
            $loaninterest=str_replace( ',', '',$request['amount']);
            $emi=$this->emi_calculator(str_replace( ',', '',$request['amount']),$request['interest'],$request['repay'],$request['mode']);
           // intial loan schedule
             $loansch1 = new loanschedules();
             $loansch1->scheduledate=date('Y-m-d',strtotime($request['date']));
             $loansch1->branchno=$branch;
             $loansch1->loanid=$objloaninfo->id;
             // getting total interest
             $ttinterest=0; 
             for($x=1;$x<=$months;$x++){
                $newinterest=$loaninterest*$rate;
                $monthlyprinciple=$emi-$newinterest;
                $ttinterest=$ttinterest+$newinterest;
                $loaninterest=$loaninterest-$monthlyprinciple;
               
             }
             $loansch1->runningbal=str_replace( ',', '',$request['amount'])+$ttinterest;
             $totalinterest= $ttinterest;
             $loansch1->save();
             $interestbal=0;
             $loanbal=0;
            for($x=1;$x<=$months;$x++){
                $newinterest=$loanamount*$rate;
                $monthlyprinciple=$emi-$newinterest;
                $total=$newinterest+$monthlyprinciple;
                $newdates=date('Y-m-d',strtotime($request['date'] ."+$x months"));
                 // Second loan schedule posting
                 $loansch= new loanschedules();
                 $loansch->loanid=$objloaninfo->id;
                 $loansch->loanamount=$monthlyprinciple;
                 $loansch->interest=$newinterest;
                 $loansch->scheduledate=$newdates;
                 $loansch->branchno=$branch;
                 $loansch->runningbal=($total)*-1;
                 $loansch->loancat=$objloancat->loanpdt;// just added 25-05-2020
                 $loansch->intmeth=1; // today
                 $loansch->nopayments=$x;
                 $loansch->save(); 
                 $loanamount=$loanamount-$monthlyprinciple;
                 //calculating interest running bal
                 $interestbal=$interestbal+$newinterest;
                 $loanbal=$loanbal+$monthlyprinciple;
                 $loanrepay= new loanrepayments();
                 $loanrepay->intrunbal=$interestbal;
                 $loanrepay->branchno=$branch;
                 $loanrepay->loanrunbal=$loanbal;
                 $loanrepay->loanid=$objloaninfo->id;
                 $loanrepay->save();
            }
         }

########################################## END OF LOAN SCHEDULE #################################################

        //Saving into loantrans
$objloantrans= new loantrans();
$objloantrans->loancredit=str_replace( ',', '',$request['amount']);
$objloantrans->loan=str_replace( ',', '',$request['amount']);
$objloantrans->isLoan=1;
$objloantrans->isDisbursement=1;
$objloantrans->branchno=$branch;
$objloantrans->user=auth()->user()->name;
//$objloantrans->interestcredit=$interest;

$objloantrans->narration="Loan Disbursement ";
$objloantrans->headerid=$objheaders->id;
$objloantrans->date=date("Y-m-d", strtotime($request['date']));
$objloantrans->loanid=$objloaninfo->id;
$objloantrans->memid=$request['name'];
$objloantrans->paydet=$request['paydet'];
$objloantrans->isActive=1;
$objloantrans->expecteddate=date('Y-m-d',strtotime($request['date'] ."+$months months"));
$objloantrans->newdate=date('Y-m-d',strtotime($request['date'] ."+$months months"));
$objloantrans->save();

//Saving Interest 
 //Saving into loantrans
 $objloantrans1= new loantrans();
 $objloantrans1->loancredit=$totalinterest;
 $objloantrans1->interestcredit=$totalinterest;
 $objloantrans1->isLoan=0;
 $objloantrans1->branchno=$branch;
 $objloantrans1->user=auth()->user()->name;
 $objloantrans1->isDisbursement=1;
 $objloantrans1->headerid=$objheaders->id;
 $objloantrans1->interestcredit=$totalinterest;
 $objloantrans1->narration="Loan Interest Charged";
 $objloantrans1->date=date("Y-m-d", strtotime($request['date']));
 $objloantrans1->loanid=$objloaninfo->id;
 $objloantrans1->memid=$request['name'];
 $objloantrans1->paydet=$request['paydet'];
 $objloantrans1->isActive=1;
 $objloantrans1->save();

 ########################################## ALL SAVINGS ##########################
 $objloancat=loanproducts::find($request['loancat']);
 $objallsavings= new allsavings();
 $objallsavings->client_no=$memberid;
 $objallsavings->branchno=$branch;
 $objallsavings->headerid=$objheaders->id;
 $objallsavings->recieptno=str_replace( ',', '',$request['date']);
 $objallsavings->date=date("Y-m-d", strtotime($request['date']));
 $objallsavings->narration="Loan Disbursement ";

     if($objloancat->loanpdt=="loanpdt1"){
        $objallsavings->loanpdt1=str_replace( ',', '',$request['amount']);
        $objallsavings->loanint1=$totalinterest;
      
     }else if($objloancat->loanpdt=="loanpdt2"){
        $objallsavings->loanpdt2=str_replace( ',', '',$request['amount']);
        $objallsavings->loanint2=$totalinterest;        
     }
    else if($objloancat->loanpdt=="loanpdt3"){
        $objallsavings->loanpdt3=str_replace( ',', '',$request['amount']);
        $objallsavings->loanint3=$totalinterest;        
     }
    else if($objloancat->loanpdt=="loanpdt4"){
        $objallsavings->loanpdt4=str_replace( ',', '',$request['amount']);
        $objallsavings->loanint4=$totalinterest;        
     }
    else if($objloancat->loanpdt=="loanpdt5"){
        $objallsavings->loanpdt5=str_replace( ',', '',$request['amount']);
        $objallsavings->loanint5=$totalinterest;        
    }

    // ######################### Savings Deducts ###################
    $isSavin=DB::select("SELECT * FROM `loanfees`inner join savingdefinations on savingdefinations.savingac=loanfees.savingac where loanfees.isActive=1 and loanfees.branchno=$branch");
    foreach($isSavin as $say){
        if($say->feevar=="loanfee1" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee1'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee1'])*-1;
        }
        if($say->feevar=="loanfee2" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee2'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee2'])*-1;
        }
        if($say->feevar=="loanfee3" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee3'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee3'])*-1;
        }    

    }
 
 $objallsavings->save();
 // Savings Statments 
############################################# Saving Statments ######################
$savingT=DB::select("SELECT * FROM `loanfees`inner join savingdefinations on savingdefinations.savingac=loanfees.savingac where loanfees.isActive=1 and loanfees.branchno=$branch");
foreach($savingT as $say){
    if($say->feevar=="loanfee1" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee1'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstrans(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee1']),$savingpdt,str_replace( ',', '',$request['loanfee1']),$objallsavings->id,$say->name,auth()->user()->branchid);
    }
    if($say->feevar=="loanfee2" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee2'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstrans(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee2']),$savingpdt,str_replace( ',', '',$request['loanfee2']),$objallsavings->id,$say->name,auth()->user()->branchid);
    }
    if($say->feevar=="loanfee3" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee3'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstrans(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee3']),$savingpdt,str_replace( ',', '',$request['loanfee3']),$objallsavings->id,$say->name,auth()->user()->branchid);
    }
}
 ########################################## If no processing fees ###################################################
 $loanfees=loanfees::where('isActive','=',1)->get();
 $loanaccounts=loanproducts::find($request['loancat']);
// echo $loanfees->count();
 if($loanfees->count()<1){
     
     /*****************    Accounts ********************************************************/
// inserting into accountrans  Loans 
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=str_replace( ',', '',$request['amount']);
$objaccountrans->accountcode=$loanaccounts->accountcode;
$objaccountrans->narration= $finalid->client." -Loan Disbursement "."($loanaccounts->name)";
$objaccountrans->ttype="D";
$objaccountrans->total=str_replace( ',', '',$request['amount']);
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

// inserting into accountrans Cash Account 
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=str_replace( ',', '',$request['amount']);
$objaccountrans->accountcode=$loanaccounts->disbursingac;
$objaccountrans->narration=$finalid->client." -Loan Disbursement "."($loanaccounts->name)";
$objaccountrans->ttype="C";
$objaccountrans->total=str_replace( ',', '',$request['amount'])*-1;
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

// Loan interest Recivables
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=$totalinterest;
$objaccountrans->total=$totalinterest;
$objaccountrans->accountcode="122";
$objaccountrans->narration=$finalid->client." -Loan Interest Recievable "."($loanaccounts->name)";
$objaccountrans->ttype="D";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

// Loan interest Income
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=$totalinterest;
$objaccountrans->total=$totalinterest;
$objaccountrans->accountcode="600";
$objaccountrans->narration=$finalid->client." -Loan Interest income "."($loanaccounts->name)";
$objaccountrans->ttype="C";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

 }else{
 /*****************    Accounts ********************************************************/
// inserting into accountrans  Loans 
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=str_replace( ',', '',$request['amount']);
$objaccountrans->accountcode=$loanaccounts->accountcode;
$objaccountrans->narration= $finalid->client." -Loan Disbursement "."($loanaccounts->name)";
$objaccountrans->ttype="D";
$objaccountrans->total=str_replace( ',', '',$request['amount']);
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();
// Loan interest Recivables
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=$totalinterest;
$objaccountrans->total=$totalinterest;
$objaccountrans->accountcode="122";
$objaccountrans->narration=$finalid->client." -Loan Interest Recievable "."($loanaccounts->name)";
$objaccountrans->ttype="D";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();
// Loan interest Income
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=$totalinterest;
$objaccountrans->total=$totalinterest;
$objaccountrans->accountcode="600";
$objaccountrans->narration=$finalid->client." -Loan Interest income "."($loanaccounts->name)";
$objaccountrans->ttype="C";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();
###########################################  LOAN FEESS #########################
$resultfees=DB::select("select * from loanfees");
$resultz=DB::select("select * from loanfees where isSavings=1");
if(count($resultz)>0){// if deduct from savings
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objheaders->id;
$objaccountrans->amount=str_replace( ',', '',$request['amount']);
$objaccountrans->total=str_replace( ',', '',$request['amount'])*-1;
$objaccountrans->accountcode=$loanaccounts->disbursingac;
$objaccountrans->narration=$finalid->client." -Loan Disbursement- "."($loanaccounts->name)";
$objaccountrans->ttype="C";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();
// selecting loan fees
foreach($resultz as $rs){
    if($rs->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
        // Loan Fee1 
        $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee1']),str_replace( ',', '',$request['loanfee1']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
        // Savings Ac
        $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee1']),str_replace( ',', '',$request['loanfee1'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }
    if($rs->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
        // Loan Fee2 
        $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee2']),str_replace( ',', '',$request['loanfee2']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
        // Savings Ac
        $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee2']),str_replace( ',', '',$request['loanfee2'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }
    if($rs->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
                // Loan Fee1 
     $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee3']),str_replace( ',', '',$request['loanfee3']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
                // Savings Ac
     $this->isSavingDeduct($objheaders->id,str_replace( ',', '',$request['loanfee3']),str_replace( ',', '',$request['loanfee3'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }

}


}else{
$resultscount=DB::select("select  sum(if(isDeduct=1,isDeduct,0)) as Deductnew,sum(if(isDeduct=0,isDeduct,0)) as Nodeductnew from loanfees");
$number=1;
$totalamount=0;
foreach($resultfees as $finalfees){
    if($finalfees->isDeduct==1){
        if($finalfees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
            $totalamount=$totalamount+str_replace( ',', '',$request['loanfee1']);
        }
        if($finalfees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
           $totalamount=$totalamount+str_replace( ',', '',$request['loanfee2']);
        }
        if($finalfees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
            $totalamount=$totalamount+str_replace( ',', '',$request['loanfee3']);
        }
    }
}
$ded=0;$noded=0;
foreach($resultscount as $ct){
    $ded=$ct->Deductnew;
    $noded=$ct->Nodeductnew;
}
foreach($resultfees as $fees){
    // if loan fee is ISDeduct
    
    if($ded>0 && $noded==0){
        if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
            if($fees->isDeduct==0){  
                $answer="No";   
            }else{
                $answer="Yes";
            }
            $this->deductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee1");
        }
        if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
            if($fees->isDeduct==0){  
                $answer="No";   
            }else{
                $answer="Yes";
            }
            $this->deductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee2");
        }
        if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
            if($fees->isDeduct==0){  
                $answer="No";   
            }else{
                $answer="Yes";
            }
            $this->deductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee3");
        }
        if($number==1){
            $this->cashaccount($request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount'])-$totalamount,date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
            }
            echo "Both ";
}
    else if($fees->isDeduct==0){
        if($number==1){
        $this->cashaccount($request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
        }
        if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
            $this->deductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee1");
        }
        if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
            $this->deductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee2");
        }
        if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
            $this->deductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee3");
        }
       // echo "NOt Deducted";
    }else if($fees->isDeduct==1){
            if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
                $this->deductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee1");
            }
            if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
                $this->deductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee2");
            }
            if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
                $this->deductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee3");
            }
            if($number==1){
                $this->cashaccount($request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount'])-$totalamount,date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
                }
               // echo "Deducted";
    }

    $number=$number+1;
}

    }
 }
            }
        }
    }catch(\Exception $e){
        DB::rollback();
        echo "Failed".$e;
    }
    DB::commit();

}
 
//Auto generated code for updating
public function update(Request $request,$id2,$headerid){
    DB::beginTransaction();
    try{
    $month=$request['repay'];
    $memberid=$request['memid'];
    $branch=auth()->user()->branchid;
  $id=DB::select("select customers.name as client,sum(loan) as loan,branchnumber from loantrans inner join customers on loantrans.memid=customers.id where loantrans.isActive=1 and memid='$memberid'");
  foreach($id as $finalid){
    $clientname=$finalid->client;
    // Inserting into purchaseheaders 
    $objheaders= purchaseheaders::find($headerid);
    $objheaders->transdates=date("Y-m-d", strtotime($request['date']));
    $objheaders->isActive=1;
    $objheaders->save();
    // Inserting into Loaninfos  
     $objloaninfo= loaninfo::find($id2);
     $objloaninfo->loanrepay=$request['repay'];
     $objloaninfo->period=$request['mode'];
     $objloaninfo->loaninterest=$request['interest'];
     $objloaninfo->mode=$request['branch'];
     $objloaninfo->collateral=$request['security'];
     $objloaninfo->guanter=$request['gauranter'];
    // $objloaninfo->memeberid=$request['name'];
     $objloaninfo->intmethod=$request['method'];
     $objloaninfo->loancat=$request['loancat'];
     $objloaninfo->loanfee1=$request['loanfee1'];
     $objloaninfo->loanfee2=$request['loanfee2'];
     $objloaninfo->loanfee3=$request['loanfee3'];
     $objloaninfo->save();
     // Deleting Loan Schdeule
DB::delete("delete  from loanschedules where loanid=$id2");
DB::delete("delete  from loanrepayments where loanid=$id2");
     #######################  Loan Schedule ##########################################
     $months=0;
     $rate=0;
     $totalinterest=0;
     $newinterest=0;
     $newloan=0;
     if($request['mode']=='Month'){
         $months=$request['repay'];
         $rate=$request['interest']/100; 
     }else if($request['mode']=='Year'){
         $rate=($request['interest']/100)/12;
         $months=$request['repay']*12;
     }
     $objloancat=loanproducts::find($request['loancat']);// just added
     if($request['method']==2){
         $interestbal=0;
         $loanbal=0;
         $newinterest=str_replace( ',', '',$request['amount'])*$rate;
         $newloan=str_replace( ',', '',$request['amount'])/$months;
         $total=$newinterest+$newloan;
            // intial loan schedule
            $loansch1 = new loanschedules();
            $loansch1->scheduledate=date('Y-m-d',strtotime($request['date']));
            $loansch1->loanid=$objloaninfo->id;
            $loansch1->runningbal=str_replace( ',', '',$request['amount'])+(($request['interest']/100)*str_replace( ',', '',$request['amount']))*$request['repay'];
            $loansch1->save();
         for($x=1;$x<=$months;$x++){
             $newdates=date('Y-m-d',strtotime($request['date'] ."+$x months"));
           // Second loan schedule posting
             $loansch= new loanschedules();
             $loansch->loanid=$objloaninfo->id;
             $loansch->loanamount=$newloan;
             $loansch->interest=$newinterest;;
             $loansch->loancat=$objloancat->loanpdt;// just added 25-05-2020
             $loansch->intmeth=2; // today
             $loansch->scheduledate=$newdates;
             $loansch->runningbal=($newloan+$newinterest)*-1;
             $loansch->nopayments=$x;
             $loansch->save();
             $totalinterest=$totalinterest+$newinterest;
             // Posting into Loan Repayments 
             $loanrepay= new loanrepayments();
             $interestbal=$interestbal+$newinterest;
             $loanbal=$loanbal+$newloan;
             $loanrepay->intrunbal=$interestbal;
             $loanrepay->loanrunbal=$loanbal;
             $loanrepay->loanid=$objloaninfo->id;
             $loanrepay->save();
         }
     }

     if($request['method']==1){
         $loanamount=str_replace( ',', '',$request['amount']);
         $loaninterest=str_replace( ',', '',$request['amount']);
         $emi=$this->emi_calculator(str_replace( ',', '',$request['amount']),$request['interest'],$request['repay'],$request['mode']);
        // intial loan schedule
          $loansch1 = new loanschedules();
          $loansch1->scheduledate=date('Y-m-d',strtotime($request['date']));
          $loansch1->loanid=$objloaninfo->id;
          // getting total interest
          $ttinterest=0; 
          for($x=1;$x<=$months;$x++){
             $newinterest=$loaninterest*$rate;
             $monthlyprinciple=$emi-$newinterest;
             $ttinterest=$ttinterest+$newinterest;
             $loaninterest=$loaninterest-$monthlyprinciple;
            
          }
          $loansch1->runningbal=str_replace( ',', '',$request['amount'])+$ttinterest;
          $totalinterest= $ttinterest;
          $loansch1->save();
          $interestbal=0;
          $loanbal=0;
         for($x=1;$x<=$months;$x++){
             $newinterest=$loanamount*$rate;
             $monthlyprinciple=$emi-$newinterest;
             $total=$newinterest+$monthlyprinciple;
             $newdates=date('Y-m-d',strtotime($request['date'] ."+$x months"));
              // Second loan schedule posting
              $loansch= new loanschedules();
              $loansch->loanid=$objloaninfo->id;
              $loansch->loanamount=$monthlyprinciple;
              $loansch->interest=$newinterest;
              $loansch->scheduledate=$newdates;
              $loansch->runningbal=($total)*-1;
              $loansch->loancat=$objloancat->loanpdt;// just added 25-05-2020
              $loansch->intmeth=1; // today
              $loansch->nopayments=$x;
              $loansch->save(); 
              $loanamount=$loanamount-$monthlyprinciple;
              //calculating interest running bal
              $interestbal=$interestbal+$newinterest;
              $loanbal=$loanbal+$monthlyprinciple;
              $loanrepay= new loanrepayments();
              $loanrepay->intrunbal=$interestbal;
              $loanrepay->loanrunbal=$loanbal;
              $loanrepay->loanid=$objloaninfo->id;
              $loanrepay->save();
         }
      }


########################################## END OF LOAN SCHEDULE #################################################

    //Saving into loantrans
    loantrans::where('loanid','=',$id2)->where('narration','=','Loan Disbursement')->update(

        ['loancredit'=>str_replace( ',', '',$request['amount']),
        'loan'=>str_replace( ',', '',$request['amount']),
        'isLoan'=>1,
        'isDisbursement'=>1,
        'memid'=>$request['memid'],
        'paydet'=>$request['paydet'],
        'isActive'=>1,
        'interestcredit'=>0,
        'date'=>date('Y-m-d',strtotime($request['date'])),
        
        ]);
//Saving Interest 

loantrans::where('loanid','=',$id2)->where('narration','=','Loan Interest Charged')->update(

    ['loancredit'=>$totalinterest,
    'loan'=>0,
    'isLoan'=>0,
    'isDisbursement'=>1,
    'loanid'=>$objloaninfo->id,
    'memid'=>$request['memid'],
    'paydet'=>$request['paydet'],
    'isActive'=>1,
    'interestcredit'=>$totalinterest,
    'date'=>date('Y-m-d',strtotime($request['date'])),
  


    ]);

########################################## ALL SAVINGS ##########################
$savingids=0;
$objloancat=loanproducts::find($request['loancat']);
$idallsaving=allsavings::where('headerid','=',$headerid)->get();
foreach($idallsaving as $savingid){
    $savingids=$savingid->id;
$objallsavings= allsavings::find($savingid->id);
$objallsavings->client_no=$request['memid'];
$objallsavings->recieptno=str_replace( ',', '',$request['date']);
$objallsavings->date=date("Y-m-d", strtotime($request['date']));
$objallsavings->narration="Loan Disbursement ";

 if($objloancat->loanpdt=="loanpdt1"){
    $objallsavings->loanpdt1=str_replace( ',', '',$request['amount']);
    $objallsavings->loanint1=$totalinterest;
  
 }else if($objloancat->loanpdt=="loanpdt2"){
    $objallsavings->loanpdt2=str_replace( ',', '',$request['amount']);
    $objallsavings->loanint2=$totalinterest;        
 }
else if($objloancat->loanpdt=="loanpdt3"){
    $objallsavings->loanpdt3=str_replace( ',', '',$request['amount']);
    $objallsavings->loanint3=$totalinterest;        
 }
else if($objloancat->loanpdt=="loanpdt4"){
    $objallsavings->loanpdt4=str_replace( ',', '',$request['amount']);
    $objallsavings->loanint4=$totalinterest;        
 }
else if($objloancat->loanpdt=="loanpdt5"){
    $objallsavings->loanpdt5=str_replace( ',', '',$request['amount']);
    $objallsavings->loanint5=$totalinterest;        
}
    // ######################### Savings Deducts ###################
    $isSavin=DB::select("SELECT * FROM `loanfees`inner join savingdefinations on savingdefinations.savingac=loanfees.savingac where loanfees.isActive=1 and loanfees.branchno=$branch");
    foreach($isSavin as $say){
        if($say->feevar=="loanfee1" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee1'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee1'])*-1;
        }
        if($say->feevar=="loanfee2" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee2'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee2'])*-1;
        }
        if($say->feevar=="loanfee3" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee3'])>0){
            $savingpdt=$say->savingpdt;
            $objallsavings->$savingpdt=str_replace( ',', '',$request['loanfee3'])*-1;
        }    

    }
$objallsavings->save();
 // Savings Statments 
############################################# Saving Statments ######################
$savingT=DB::select("SELECT * FROM `loanfees`inner join savingdefinations on savingdefinations.savingac=loanfees.savingac where loanfees.isActive=1 and loanfees.branchno=$branch");
foreach($savingT as $say){
    if($say->feevar=="loanfee1" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee1'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstransupdate(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee1']),$savingpdt,str_replace( ',', '',$request['loanfee1']),$savingids,$say->name,auth()->user()->branchid);
    }
    if($say->feevar=="loanfee2" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee2'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstransupdate(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee2']),$savingpdt,str_replace( ',', '',$request['loanfee2']),$savingids,$say->name,auth()->user()->branchid);
    }
    if($say->feevar=="loanfee3" && $say->isSavings==1 && str_replace( ',', '',$request['loanfee3'])>0){
        $savingpdt=$say->savingpdt;
        $this->savingstransupdate(date("Y-m-d", strtotime($request['date'])),$memberid,$request['paydet'],str_replace( ',', '',$request['loanfee3']),$savingpdt,str_replace( ',', '',$request['loanfee3']),$savingids,$say->name,auth()->user()->branchid);
    }
}
}
########################################## If no processing fees ###################################################
$loanfees=loanfees::where('isActive','=',1)->get();
$loanaccounts=loanproducts::find($request['loancat']);
if($loanfees->count()<1){
 
 #####################    Accounts ##################################
// inserting into Loan Account
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
'=',$loanaccounts->accountcode)->update(
     ['amount'=>str_replace( ',', '',$request['amount']),
    'total'=>str_replace( ',', '',$request['amount']),
    'narration'=>$finalid->client.' -Loan Disbursement '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),   
    ]);
// inserting into Cash Account
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
'=',$loanaccounts->disbursingac)->update(
     ['amount'=>str_replace( ',', '',$request['amount']),
    'total'=>str_replace( ',', '',$request['amount'])*-1,
    'narration'=>$finalid->client.' -Loan Disbursement '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    ]);
// inserting into Loan Interest Recievables
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
'=','122')->update(
     ['amount'=>$totalinterest,
    'total'=>$totalinterest,
    'narration'=>$finalid->client.' -Loan Interest Rec '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    
    
    ]);
//Loan Interest Income
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
'=','600')->update(
     ['amount'=>$totalinterest,
    'total'=>$totalinterest,
    'narration'=>$finalid->client.' -Loan Interest Income '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    
    
    ]);

}else{
    
########################################    Accounts ##################################
// inserting into accountrans  Loans 
// inserting into Loan Account
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
'=',$loanaccounts->accountcode)->update(
     ['amount'=>str_replace( ',', '',$request['amount']),
    'total'=>str_replace( ',', '',$request['amount']),
    'narration'=>$finalid->client.' -Loan Disbursement '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),   
    ]);
// inserting into Loan Interest Recievables
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
'=','122')->update(
     ['amount'=>$totalinterest,
    'total'=>$totalinterest,
    'narration'=>$finalid->client.' -Loan Interest Rec '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    
    
    ]);
//Loan Interest Income
accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
'=','600')->update(
     ['amount'=>$totalinterest,
    'total'=>$totalinterest,
    'narration'=>$finalid->client.' -Loan Interest Income '."($loanaccounts->name)",
    'transdate'=>date("Y-m-d", strtotime($request['date'])),
    
    
    ]);
###########################################  LOAN FEESS #########################
$resultfees=DB::select("select * from loanfees");
$resultz=DB::select("select * from loanfees where isSavings=1");
if(count($resultz)>0){// if deduct from savings
    accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
    '=',$loanaccounts->disbursingac)->update(
         ['amount'=>str_replace( ',', '',$request['amount']),
        'total'=>str_replace( ',', '',$request['amount'])*-1,
        'narration'=>$finalid->client." -Loan Disbursement- "."($loanaccounts->name)",
        'transdate'=>date("Y-m-d", strtotime($request['date'])),
        
        
        ]);
// selecting loan fees
foreach($resultz as $rs){
    if($rs->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
        // Loan Fee1 
        $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee1']),str_replace( ',', '',$request['loanfee1']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
        // Savings Ac
        $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee1']),str_replace( ',', '',$request['loanfee1'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }
    if($rs->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
        // Loan Fee2 
        $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee2']),str_replace( ',', '',$request['loanfee2']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
        // Savings Ac
        $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee2']),str_replace( ',', '',$request['loanfee2'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }
    if($rs->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
                // Loan Fee1 
     $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee3']),str_replace( ',', '',$request['loanfee3']),$rs->code,$clientname."-".$rs->name,'C',date("Y-m-d", strtotime($request['date'])));
                // Savings Ac
     $this->isSavingDeductupdate($headerid,str_replace( ',', '',$request['loanfee3']),str_replace( ',', '',$request['loanfee3'])*-1,$rs->savingac,$clientname."-".$rs->name,'D',date("Y-m-d", strtotime($request['date'])));
    }

}


}else{

$resultscount=DB::select("select  sum(if(isDeduct=1,isDeduct,0)) as Deductnew,sum(if(isDeduct=0,isDeduct,0)) as Nodeductnew from loanfees");
$number=1;
$totalamount=0;
foreach($resultfees as $finalfees){
if($finalfees->isDeduct==1){
    if($finalfees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
        $totalamount=$totalamount+str_replace( ',', '',$request['loanfee1']);
    }
    if($finalfees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
       $totalamount=$totalamount+str_replace( ',', '',$request['loanfee2']);
    }
    if($finalfees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
        $totalamount=$totalamount+str_replace( ',', '',$request['loanfee3']);
    }
}
}
$ded=0;$noded=0;
foreach($resultscount as $ct){
    $ded=$ct->Deductnew;
    $noded=$ct->Nodeductnew;
}
foreach($resultfees as $fees){
// if loan fee is ISDeduct
if($ded>0 && $noded==0){
    if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
        if($fees->isDeduct==0){  
            $answer="No";   
        }else{
            $answer="Yes";
        }
        $this->updatedeductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee1");
    }
    if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
        if($fees->isDeduct==0){  
            $answer="No";   
        }else{
            $answer="Yes";
        }
        $this->updatedeductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee2");
    }
    if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
        if($fees->isDeduct==0){  
            $answer="No";   
        }else{
            $answer="Yes";
        }
        $this->updatedeductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,$answer,"loanfee3");
    }
    if($number==1){
        $this->updatecashaccount($request['loancat'],$objheaders->id,str_replace( ',', '',$request['amount'])-$totalamount,date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
        }
        echo "Both ";
}
else if($fees->isDeduct==0){
    if($number==1){
    $this->updatecashaccount($request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
    }
    if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
        $this->updatedeductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee1");
    }
    if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
        $this->updatedeductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee2");
    }
    if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
        $this->updatedeductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"No","loanfee3");
    }
}else{
        if($fees->feevar=="loanfee1" && str_replace( ',', '',$request['loanfee1'])>0){
            $this->updatedeductions(str_replace( ',', '',$request['loanfee1']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee1");
        }
        if($fees->feevar=="loanfee2" && str_replace( ',', '',$request['loanfee2'])>0){
            $this->updatedeductions(str_replace( ',', '',$request['loanfee2']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee2");
        }
        if($fees->feevar=="loanfee3" && str_replace( ',', '',$request['loanfee3'])>0){
            $this->upatedeductions(str_replace( ',', '',$request['loanfee3']),$request['loancat'],$headerid,str_replace( ',', '',$request['amount']),date("Y-m-d", strtotime($request['date'])),$clientname,$fees,"Yes","loanfee3");
        }
        if($number==1){
            $this->updatecashaccount($request['loancat'],$headerid,str_replace( ',', '',$request['amount'])-$totalamount,date("Y-m-d", strtotime($request['date'])),$clientname,"Deduct");
            }
}
$number=$number+1;
}



}      
}
}
  
}catch(\Exception $e){
    DB::rollback();
    echo "Failed".$e;
}
DB::commit();

    }

    
 


 public function destroy($id,$headerid){
     DB::beginTransaction();
     try{
$user=auth()->user()->name;
   DB::delete("delete from loantrans where loanid='$id'");
   DB::delete("delete from accounttrans  where purchaseheaderid='$headerid'");
   DB::delete("delete from purchaseheaders where id='$headerid'");
   DB::delete("delete from loaninfos where id='$id'");
   DB::delete("delete from loanschedules where loanid=$id");
   DB::delete("delete from loanrepayments where loanid=$id");
  $results= DB::select("select id from allsavings where headerid='$headerid'");
  foreach($results as $rs){
  DB::delete("delete from savings where savingsid=$rs->id");
  }
   
   DB::delete("delete from allsavings where headerid='$headerid'");
     }catch(\Exception $e){
         echo "Failed".$e;
         DB::rollback();
     }
     DB::commit();

    }
    public function viewcombo(){

        return suppliers::all();

    }

    public function loandisbursement($where){
        $results=array();
        $branch=auth()->user()->branchid;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $bra=auth()->user()->branchid;
        $admin=auth()->user()->isAdmin;
       // if($admin==0){
        $krows = DB::select("select COUNT(*) as count  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where AND loantrans.branchno=$branch");
        $results["total"]=$krows[0]->count;
        
        $sth =  DB::getPdo()->prepare("select intmethod as method,loanfee1,loanfee2,loanfee3,period as mode,loancat,loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,paydet,customers.name,loaninterest,loanrepay,collateral,FORMAT(loancredit,0) as loancredit,guanter,headerid from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id  inner join loanproducts on loanproducts.id=loaninfos.loancat inner join interestmethods on interestmethods.id=loaninfos.intmethod where loantrans.branchno=$bra and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
        $sth->execute();
           $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
        $results["rows"]=$dogs;
      
                     //Showing The footer and totals 
   //$footer =  DB::getPdo()->prepare("select format(sum(loancredit),0) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 AND loantrans.branchno=$branch $where limit $offset,$rows");
   $footer =  DB::getPdo()->prepare(" select format(sum(loancredit),0) as loancredit from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id  inner join loanproducts on loanproducts.id=loaninfos.loancat inner join interestmethods on interestmethods.id=loaninfos.intmethod where loantrans.branchno=1 and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where ");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
    }
    /*public function authloans($where){
        $admin=auth()->user()->isAdmin;
        if($admin==1){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $bra=auth()->user()->branchid;
        $admin=auth()->user()->isAdmin;
       // if($admin==0){
        $krows = DB::select("select COUNT(*) as count from  customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where isLoan=1 and isDisbursement=1 and loantrans.isActive=1  $where");
        $results["total"]=$krows[0]->count;
        
        $sth =  DB::getPdo()->prepare("select loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,headerid,memid,paydet,customers.name,loaninterest,loanrepay,collateral,format(loancredit,0) as loancredit,guanter from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
        $sth->execute();
           $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
        $results["rows"]=$dogs;
              //Showing The footer and totals 
              $footer =  DB::getPdo()->prepare("select format(sum(loancredit),0) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);







        }

    }*/
    // Function to calculate EMI 
   public  function emi_calculator($p, $r, $t,$yearormonth) 
    { 
        $emi; 
        $r;$t;
      if($yearormonth=="Year"){
        // one month interest 
        $r = $r / (12 * 100); 
          
        // one month period 
        $t = $t * 12;  
      }else if($yearormonth=="Month"){ 
                $r = $r / ( 100); 
                $t = $t ;  
      }
        $emi = ($p * $r * pow(1 + $r, $t)) /  
                      (pow(1 + $r, $t) - 1); 
      
        return ($emi); 
    }

    public function deductions($loanfees,$loancategory,$headerid,$amount,$rdate,$client,$fees,$isDeduct,$catidentify){
        $loanaccounts=loanproducts::find($loancategory); 
        // Loan Fee1 
        if($isDeduct=="No"){
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$headerid;
        $objaccountrans->amount=$loanfees;//str_replace( ',', '',$request['loanfee1']);
        $objaccountrans->total=$loanfees;
        $objaccountrans->accountcode=$fees->code;
        $objaccountrans->narration=$client." -$fees->name "."($loanaccounts->name)";
        $objaccountrans->ttype="C";
        $objaccountrans->transdate=$rdate;
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->cat=$catidentify;
        $objaccountrans->save();
        // inserting into accountrans Cash Account for fee1
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$headerid;
        $objaccountrans->amount=$loanfees;
        $objaccountrans->accountcode=$loanaccounts->disbursingac;
        $objaccountrans->narration=$client." -$fees->name "."($loanaccounts->name)";
        $objaccountrans->ttype="D";
        $objaccountrans->total=$loanfees;
        $objaccountrans->transdate=$rdate;
        $objaccountrans->cat=$catidentify;
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->save();
        }
        if($isDeduct=="Yes"){
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$headerid;
        $objaccountrans->amount=$loanfees;//str_replace( ',', '',$request['loanfee1']);
        $objaccountrans->total=$loanfees;
        $objaccountrans->accountcode=$fees->code;
        $objaccountrans->narration=$client." -$fees->name "."($loanaccounts->name)";
        $objaccountrans->ttype="C";
        $objaccountrans->cat=$catidentify;
        $objaccountrans->transdate=$rdate;
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->save();    
        }
    }
    public function cashaccount($loancategory,$headerid,$amount,$rdate,$client,$cashaccount){
        $loanaccounts=loanproducts::find($loancategory);
        if($cashaccount=="Deduct"){
            // inserting into accountrans Cash Account 
       $objaccountrans=new accounttrans;
       $objaccountrans->purchaseheaderid=$headerid;
       $objaccountrans->amount=abs($amount);
       $objaccountrans->accountcode=$loanaccounts->disbursingac;
       $objaccountrans->narration=$client." -Loan Disbursement "."($loanaccounts->name)";
       $objaccountrans->ttype="C";
       $objaccountrans->total=$amount*-1;
       $objaccountrans->transdate=$rdate;
       $objaccountrans->bracid=auth()->user()->branchid;
       $objaccountrans->save();
        }
    }
    public function isSavingDeduct($headerid,$amount,$total,$accountcode,$narration,$ttype,$tdate){
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$headerid;
$objaccountrans->amount=$amount;
$objaccountrans->total=$total;
$objaccountrans->accountcode=$accountcode;
$objaccountrans->narration=$narration;
$objaccountrans->ttype=$ttype;
$objaccountrans->transdate=$tdate;
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();
    }
    public function isSavingDeductupdate($headerid,$amount,$total,$accountcode,$narration,$ttype,$tdate){
        accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=',$ttype)->where('accountcode','=',$accountcode)->update(
     ['amount'=>$amount,
    'total'=>$total,
    'narration'=>$narration,
    'transdate'=>$tdate,
    
    
    ]);

            }
    public function savingstrans($tdate,$clientid,$paydet,$total,$savingcat,$moneyout,$savingid,$narration,$branch){
        $objsaving1=new savings();
        $objsaving1->date=$tdate;
        $objsaving1->client_no=$clientid;
        $objsaving1->paydet=$paydet;
        $objsaving1->isCharge=0;
        $objsaving1->isFee=1;
        $objsaving1->branchno=$branch;
        $objsaving1->total=$total*-1;
        $objsaving1->category=$savingcat;
        $objsaving1->moneyout=$moneyout;
        $objsaving1->savingsid=$savingid;
        $objsaving1->narration=$narration;
        $objsaving1->save();
    }
    public function savingstransupdate($tdate,$clientid,$paydet,$total,$savingcat,$moneyout,$savingid,$narration,$branch){
       $saving= DB::select("select id from savings where savingsid=$savingid");
       foreach($saving as $sy){
        $objsaving1= savings::find($sy->id);
        $objsaving1->date=$tdate;
        $objsaving1->client_no=$clientid;
        $objsaving1->paydet=$paydet;
        $objsaving1->isCharge=0;
        $objsaving1->branchno=$branch;
        $objsaving1->total=$total*-1;
        $objsaving1->category=$savingcat;
        $objsaving1->moneyout=$moneyout;
        $objsaving1->savingsid=$savingid;
        $objsaving1->narration=$narration;
        $objsaving1->save();
       }
    }
    public function updatecashaccount($loancategory,$headerid,$amount,$rdate,$client,$cashaccount){
        $loanaccounts=loanproducts::find($loancategory);
        if($cashaccount=="Deduct"){
            // inserting into accountrans Cash Account 
       accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
       '=',$loanaccounts->disbursingac)->update(
            ['amount'=>abs($amount),
           'total'=>$amount*-1,
           'narration'=>$client." -Loan Disbursement "."($loanaccounts->name)",
           'transdate'=>$rdate,
           
           
           ]);
        }
    }
    public function updatedeductions($loanfees,$loancategory,$headerid,$amount,$rdate,$client,$fees,$isDeduct,$loancatid){
        $loanaccounts=loanproducts::find($loancategory); 
        // Loan Fee1 
        if($isDeduct=="No"){
        // Inserting into loan processing one 
        accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
        '=',$fees->code)->where('cat','=',$loancatid)->update(
             ['amount'=>$loanfees,
            'total'=>$loanfees,
            'narration'=>$client." -$fees->name "."($loanaccounts->name)",
            'transdate'=>$rdate,
            
            
            ]);    
                            // inserting  Cash Account 
       accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
       '=',$loanaccounts->disbursingac)->where('cat','=',$loancatid)->update(
            ['amount'=>$loanfees,
           'total'=>$loanfees,
           'narration'=>$client." -$fees->name "."($loanaccounts->name)",
           'transdate'=>$rdate,
           
           
           ]);
        }
        if($isDeduct=="Yes"){
                    // inserting  one sided loanfees 
       accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->where('accountcode',
       '=',$fees->code)->where('cat','=',$loancatid)->update(
            ['amount'=>$loanfees,
           'total'=>$loanfees,
           'narration'=>$client." -$fees->name "."($loanaccounts->name)",
           'transdate'=>$rdate,
           
           
           ]); 
                               // inserting  one sided loanfees 
       accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode',
       '=',$loanaccounts->disbursingac)->where('cat','=',$loancatid)->update(
            ['amount'=>0,
           'total'=>0,
           'narration'=>$client." -$fees->name "."($loanaccounts->name)",
           'transdate'=>$rdate,
           
           
           ]);   
        }
    }
}
