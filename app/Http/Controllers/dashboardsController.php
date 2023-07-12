<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\savingdefinations;
use App\allsavings;
use App\loanschedules;
use App\loantrans;
use App\savings;
use App\purchaseheaders;
use App\loanproducts;
use App\accounttrans;
use App\loanrepayments;
use App\loaninfo;
use App\fixeddeposits;
use App\fixednotes;
use Session;

 class dashboardsController extends Controller{
     //public function __construct(){
        // $this->middleware('auth');
    // }

public function index(){
    $bra=auth()->user()->branchid;
    $today=date("'Y/m/d'");
   $totalsales= DB::select("select if(sum(totalamt)is Null,0,sum(totalamt)) as totalamt,sum(totalpaid) as totalpaid ,if(sum(totaldue)<0,0,sum(totaldue)) as totaldue  from purchaseheaders inner join stocktrans on purchaseheaders.id=stocktrans.purchaseheaderid inner join stocks on stocks.id=stocktrans.stockname where transdate=$today And stocktrans.type='O'");


   // Auto computation of Accounts AND Ledger Fees
   DB::beginTransaction();
   try{
    $this->notesfixed();
    $this->computefixedDeposit();
    $a=$this->three();
    if($this->three()!=0){
Session::put('inactive'," $a Dormant Account (s)");
    }
    $b=$this->loandu();
    if($this->loandu()>0){
Session::put('loandue'," $b Loan Due (s) ");      
    }


}catch(\Exception $e){
   DB::rollback();
   echo "Failed ".$e;
}
DB::commit(); 


    return view('dashboard/index')->with('totalsales',$this->loans())->with('totalincome',$this->totalincome())->with('totalexpenses',$this->totalexpenses())->with('outstanding',$this->totaloutstanding())->with('loansdue',$this->loansdue());

}

public function totalincome(){
    $bra=auth()->user()->branchid;
    $today=date("'Y/m/d'");
    if(auth()->user()->isAdmin==1){
    return DB::select("select format(abs(sum(interestcredit)),0) as interest ,date from loantrans where interestcredit<0 and loantrans.isActive=1 and date=$today");
}else{
    
    return DB::select("select format(abs(sum(interestcredit)),0) as interest ,date from loantrans inner join customers on customers.id=loantrans.memid where  loantrans.isActive=1 and interestcredit<0 and date=$today and branchnumber=$bra");    
}
   
    
}

public function totalexpenses(){
    $bra=auth()->user()->branchid;
    $today=date("'Y/m/d'");
    return DB::select("select if(sum(if(ttype='D',amount,'')) is Null,0,sum(if(ttype='D',amount,'')))as amount from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype
    where purchaseheaders.isActive=1 and transdates= $today AND chartofaccounts.accountcode!=1001  AND accounttypes.id=7 AND ttype='D' AND credit is Null AND bracid=$bra");



}

public function totaloutstanding(){
    $today=date("'Y/m/d'");
    $bra=auth()->user()->branchid;
    if(auth()->user()->isAdmin==1){
    return DB::select("select format(sum(interestcredit),0) as interest ,date from loantrans where interestcredit>0 and loantrans.isActive=1 and date=$today and loantrans.branchno=$bra");
}else{
    $bra=auth()->user()->branchid;
    return DB::select("select format(sum(interestcredit),0) as interest ,date from loantrans inner join customers on customers.id=loantrans.memid where interestcredit>0 and loantrans.isActive=1 and date=$today and branchnumber=$bra");    
}

    //return DB::select("select if(sum(totalamt) is Null,0,sum(totalamt)) as totalamt,sum(totalpaid) as totalpaid ,if(if(sum(totaldue)<0,0,sum(totaldue)) is Null,0,if(sum(totaldue)<0,0,sum(totaldue))) as totaldue  from purchaseheaders inner join stocktrans on purchaseheaders.id=stocktrans.purchaseheaderid inner join stocks on stocks.id=stocktrans.stockname inner join customers on customers.id=purchaseheaders.customer_id inner join stockbals on purchaseheaders.id=stockbals.headno where  transdates=$today  AND balance!=0 ");
}

public function loans(){
    $bra=auth()->user()->branchid;
    $today=date("'Y/m/d'");
    if(auth()->user()->isAdmin==1){
        return DB::select("select format(sum(loan),0) as loan from loantrans inner join customers on customers.id=loantrans.memid where isDisbursement=1 AND loantrans.isActive=1 and date=$today and loantrans.branchno=$bra");
    }else{
        $bra=auth()->user()->branchid;
    return DB::select("select format(sum(loan),0) as loan from loantrans inner join customers on customers.id=loantrans.memid where branchnumber=$bra and isDisbursement=1 AND loantrans.isActive=1 and date=$today");
    }
}

public function loansdue(){
    $bra=auth()->user()->branchid;
    $today=date("'Y/m/d'");
   //return  DB::select("select if(COUNT(name)>0,COUNT(name),0) as count from loandues inner join loanschedules on loanschedules.loanid=loandues.loanid where loanamount!=0  AND scheduledate=$today and loantrans.branchno=$bra");
}
############################################### START OF COMPUTING LOANS ###########################
/*public function computeloans(){
    DB::beginTransaction();
    try{ 
    $globalisActive=0;
    $currentdate=strtotime(date("d-m-Y"));
    //Generating the loan id 
    $genid=DB::select("select loanschedules.loanid as loanid,loanschedules.branchno, intmeth from loanschedules inner join loantrans on loantrans.loanid=loanschedules.loanid where intmeth is not null and  isDisbursement=1 group by loanid");
    foreach($genid as $genID){

    $schedule=DB::select("select *,scheduledate from loanschedules where nopayments is not null and loanid=$genID->loanid and payvalue='E'");
            // if a customer is active compute
            $isActive=DB::select("select customers.isActive from loantrans inner join customers on customers.id=loantrans.memid where  loanid=$genID->loanid AND customers.isActive=1 group by memid having sum(loan)>1");
            foreach($isActive as $act)
            {   
                if($act->isActive==1){
    if(count($schedule)>0){
    foreach($schedule as $shed){
        if($currentdate>=strtotime($shed->scheduledate)){
            $loanz=DB::select("select sum(loan) as loan from loantrans where loanid=$genID->loanid");
            foreach($loanz as $ln){
                $bal=$ln->loan;
                // checking if reducing balanace
                if($genID->intmeth==1){
                $this->computeinterestRBal($bal,$genID->loanid,$shed->scheduledate,$shed->loancat,$genID->branchno);
                DB::update("update loanschedules set payvalue='D' where id=$shed->id");
                }
                if($genID->intmeth==2){
                   DB::update("update loanschedules set payvalue='D' where id=$shed->id");   
                }

            }
            
        }

    }
}else{
    $loanz=DB::select("select sum(loan) as loan from loantrans where loanid=$genID->loanid");
    foreach($loanz as $ln){
        if($ln->loan>0){
            // check if expected is already posted
           $exp= DB::select("select expecteddate from loanschedules where expecteddate is not null and loanid=$genID->loanid");
           if(count($exp)<=0){
              $sch= DB::select("select scheduledate,loancat from loanschedules where loanid=$genID->loanid order by scheduledate desc limit 1");
              foreach($sch as $she){
               $objloansch= new loanschedules();
               $objloansch->loanid=$genID->loanid;
               $objloansch->loancat=$she->loancat;
               $objloansch->branchno=$genID->branchno;
               $objloansch->expecteddate=date('Y-m-d',strtotime($she->scheduledate."1 months"));
               $objloansch->save();
                
              }

           }
           //final date 
          // if($shed->intmeth==1){
           $pect=DB::select("select expecteddate,loancat from loanschedules where expecteddate is not null and  payvalue='E' and loanid=$genID->loanid limit 1");
           foreach($pect as $et){
               $pec=strtotime($et->expecteddate);
               $pecsec=strtotime($et->expecteddate);
               $x=1;
               while($currentdate>=$pec){
                   //echo date('Y-m-d',$pec)."<br><br>";
                   $Rdate=date('Y-m-d',$pec);
                   // For only reducing balance
                   $this->computeinterestRBal($ln->loan,$genID->loanid,$Rdate,$et->loancat,$genID->branchno);
                   // you must update
                   $Edate=date('Y-m-d',$pec);
                   DB::update("update loanschedules set expecteddate='$Edate', payvalue='D'  where loanid=$genID->loanid and runningbal is null and nopayments is null");
                   
                 $pec=date('Y-m-d',strtotime($et->expecteddate));
                $pec=strtotime($pec."$x months");
                $x++;
               }
               // Making it not to repeat 
               if($currentdate>=$pecsec){
               $sch= DB::select("select expecteddate from loanschedules where loanid=$genID->loanid order by expecteddate desc limit 1");
               foreach($sch as $shu){
                $Edate=date('Y-m-d',strtotime($shu->expecteddate."1 months"));
                // For only reducing Balance
               // if($genID->intmeth==1){
                   DB::update("update loanschedules set expecteddate='$Edate', payvalue='E'  where loanid=$genID->loanid and runningbal is null and nopayments is null ");
                //}
               }
            }

        }
               
           //}

        }
    }
}
                }
   
            }
        }
    }catch(\Exception $e){
        DB::rollback();
        echo "Failed ".$e;
     }
     DB::commit(); 

}*/

public function computeinterestRBal($balance,$loanid,$date,$loancat,$branchid){
    DB::beginTransaction();
    try{
    $memberid=DB::select("select name,memid,intmethod,loancredit as loan,loaninterest,sum(loan) as loancredit,abs(sum(if(loan<0,loan,0))) as loanpaid from loantrans inner join loaninfos on loaninfos.id=loantrans.loanid inner join customers on customers.id=loantrans.memid where loanid=$loanid and intmethod is not null");
                           // Obtaining header number from purchaseheaders table 
         $objheaders= new purchaseheaders();
         $objheaders->transdates=$date;
         $objheaders->save();
    foreach($memberid as $id){
        if($id->intmethod==1){
        $calinterest=($id->loaninterest/100)*$balance;
        }else{
            $calinterest=$id->loan*($id->loaninterest/100);
        }
        $this->incrementrepaymentsch($calinterest,$loanid,$id->loanpaid);
        $objallsavings= new allsavings();
        $objallsavings->client_no=$id->memid;
        $objallsavings->recieptno=$date;
        $objallsavings->date=$date;
        $objallsavings->branchno=$branchid;
        $objallsavings->headerid=$objheaders->id;
        $objallsavings->narration="Auto Computed Interest";
        if($loancat=="loanpdt1"){
            $objallsavings->loanint1=$calinterest;
        }
        if($loancat=="loanpdt2"){
            $objallsavings->loanint2=$calinterest;
        }
        if($loancat=="loanpdt3"){
            $objallsavings->loanint3=$calinterest;
        }
        if($loancat=="loanpdt4"){
            $objallsavings->loanint4=$calinterest;
        }
        if($loancat=="loanpdt5"){
            $objallsavings->loanint5=$calinterest;
        }
        $objallsavings->save();
        // Insertting into loantrans table 
        $loantrans= new loantrans();
        $loantrans->memid=$id->memid;
        $loantrans->headerid=$objheaders->id;
        $loantrans->loancredit=$calinterest;
        $loantrans->interestcredit=$calinterest;
        $loantrans->narration="Auto Computed Interest";
        $loantrans->date=$date;
        $loantrans->paydet="Int";
        $loantrans->isLoan=0;
        $loantrans->isRepayment=0;
        $loantrans->isActive=1;
        $loantrans->branchno=$branchid;
        $loantrans->save();
        $objloaninfo= new loaninfo();
        $objloaninfo->isInterestPay=1;
        $objloaninfo->save();
        // All savings 
        $objsave= new savings();
        $objsave->date=$date;
        $objsave->client_no=$id->memid;
        $objsave->paydet=$date;
        $objsave->isCharge=0;
        $objsave->branchno=$branchid;
        $objsave->total=$calinterest;
        $objsave->category="loan";
        $objsave->moneyin=$calinterest;
        $objsave->savingsid=$objallsavings->id; 
        $objsave->save();
        $lnpdts=DB::select("select loanpdt,disbursingac from loanproducts where loanpdt='$loancat' and branchno=$branchid ");
        foreach($lnpdts as $pdt){
                                // Inserting into income account
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=$calinterest;
         $objaccountrans->accountcode="5000";
         $objaccountrans->ttype="D";
         $objaccountrans->total=$calinterest;
         $objaccountrans->narration=$id->name." -Auto interest Computation";
         $objaccountrans->transdate=$date;
         $objaccountrans->bracid=$branchid;
         $objaccountrans->save();
    // inserting / reducing loan account
         $objaccountrans=new accounttrans;
         $objaccountrans->purchaseheaderid=$objheaders->id;
         $objaccountrans->amount=$calinterest;
         $objaccountrans->total=$calinterest;
         $objaccountrans->accountcode="4000";
         $objaccountrans->narration=$id->name." -Auto interest Computation ";
         $objaccountrans->ttype="C";
         $objaccountrans->bracid=$branchid;
         $objaccountrans->transdate=$date;
         $objaccountrans->save();
        }
       
        
        
    }
    
    
}catch(\Exception $e){
    DB::rollback();
    echo "Failed ".$e;
 }
 DB::commit(); 
}

public function incrementrepaymentsch($interest,$loanid,$loanbalance){
    DB::beginTransaction();
    try{  
    $amount=$this->getLoanRBal($loanid,$this->getPosition($loanbalance,$this->getLoanRBal($loanid,$loanbalance),$loanid,"loan"));
    $getid=DB::select("select id from loanrepayments where loanrunbal=$amount");
    foreach($getid as $gid){
        //if($loanbalance>0){
        DB::update("update loanrepayments set intrunbal=intrunbal+$interest where loanid=$loanid and id>=$gid->id"); 
        //}      
}
}catch(\Exception $e){
    DB::rollback();
    echo "Failed ".$e;
 }
 DB::commit();
}

// Processing current loan standing
public function getInterestRBal($loanId,$interestBal,$creditinterest){
    $loandet2=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where loanid=$loanId  order by loanrepayments.id ");
    foreach($loandet2 as $loandet){
       // return $loandet->intrunbal. "the last";
        if($interestBal==$loandet->intrunbal){ //&& $loandet->intrunbal>$interestBal
            //if($creditinterest==0){
               // return 0;
                //break;
            //}else{
            return true;// ($loandet->intrunbal-$interestBal);
            break; 
           // }
           }
           if($interestBal!=$loandet->intrunbal && $loandet->intrunbal>$interestBal){
               return ($loandet->intrunbal-$interestBal);
           }

}
}
public function getLoanRBal($loanId,$loanBal){
    $loandet2=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where loanid=$loanId  order by loanrepayments.id ");
    foreach($loandet2 as $loandet){
        if($loanBal==$loandet->loanrunbal){ //&& $loandet->intrunbal>$interestBal
            return $loandet->loanrunbal;
            break; 
           }
           if($loanBal!=$loandet->loanrunbal && $loandet->loanrunbal>$loanBal){
               return ($loandet->loanrunbal);
           }
}
}
public function getPosition($loanbal,$loanrun,$loanid,$type){
   if($type=="loan"){
    if($loanbal==$loanrun){
        return ($this->getLoanRBal($loanid,$loanbal)+1);
    }else{
        return $this->getLoanRBal($loanid,$loanbal);
    }
}
}
############################################### END OF COMPUTING LOANS ###########################
public function computefixedDeposit(){
    DB::beginTransaction();
    try{
    $bra=auth()->user()->branchid;
    $FD=DB::select("select fixeddeposits.id as id,name,acno, client_id,fixdate,fixamount,fixinterest,fixperiod,maturitydate,maturityinterest from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where customers.isActive=1 and isComplete=0 and isPay=0");
    foreach($FD as $deposits){
        $today=strtotime("now");
        $objpurchaseheaders= new purchaseheaders();
        $objpurchaseheaders->transdates=date("Y-m-d",strtotime($deposits->maturitydate));
        $objpurchaseheaders->branch_id=$bra;
        $objpurchaseheaders->save();
        if($today>=strtotime($deposits->maturitydate)){

            // Account trans 
            // Savings Interest Expense
            $account= new accounttrans();
            $account->accountcode="701";
            $account->narration=$deposits->name." Savings Interest Expense ";
            $account->amount=$deposits->maturityinterest;
            $account->total=$deposits->maturityinterest;
            $account->ttype="D";
            $account->purchaseheaderid=$objpurchaseheaders->id;
            $account->transdate=date("Y-m-d",strtotime($deposits->maturitydate));
            $account->bracid=$bra;
            $account->save();
                        // Savings Interest Payable
                        $account= new accounttrans();
                        $account->accountcode="404";
                        $account->narration=$deposits->name." Savings Interest Payable";
                        $account->amount=$deposits->maturityinterest;
                        $account->total=$deposits->maturityinterest;
                        $account->ttype="C";
                        $account->purchaseheaderid=$objpurchaseheaders->id;
                        $account->transdate=date("Y-m-d",strtotime($deposits->maturitydate));
                        $account->bracid=$bra;
                        $account->save();
$Objallsavings= new allsavings();
$Objallsavings->client_no=$deposits->client_id;
$Objallsavings->narration="Fixed Deposit-Interest";
$Objallsavings->date=date("Y-m-d",strtotime($deposits->maturitydate));
$Objallsavings->recieptno=$objpurchaseheaders->id;
$Objallsavings->branchno=$bra;
$Objallsavings->headerid=$objpurchaseheaders->id;
//$Objallsavings->savingpdt5=str_replace( ',', '',$request['fixamount']);
$Objallsavings->intpdt5=$deposits->maturityinterest;
//$Objallsavings->fixdepositid=$Objfixeddeposits->id;
$Objallsavings->save();          
DB::update("update fixeddeposits set isComplete=1 where id=$deposits->id");
$objfixednotes= new fixednotes();
$objfixednotes->name=$deposits->name;
$objfixednotes->acno=$deposits->acno;
$objfixednotes->branchno=$bra;
$objfixednotes->date=date("Y-m-d",strtotime($deposits->maturitydate));
$objfixednotes->save();
            
        }
    }

}catch(\Exception $e){
    echo "Failed ".$e;
    DB::rollBack();
} 
DB::commit(); 
}
public function three(){
    $months= DB::select("select name,acno,format(savingpdt1,0)as savingpdt1,client_no,max(DATE_FORMAT(date,'%d-%m-%Y')) as date from allsavings inner join customers on customers.id=allsavings.client_no where savingpdt1>0  group by client_no order by max(date)");
    $a=0;
    foreach($months as $mon){
       $today=date('Y-m-d');
       
      if(strtotime($today)>strtotime(date('Y-m-d',strtotime($mon->date."+3 months")))){

        $a=$a+1;
      }
     
    }
    return $a;
}
public function loandu(){
    $today=date('Y-m-d');
    $bra=auth()->user()->branchid;
    $loans=DB::select("select DATE_FORMAT(date,'%d-%m-%Y')  date,DATE_FORMAT(date_add(date,INTERVAL loanrepay month),'%d-%m-%Y') maturity,acno,loanrepay,name,format(sum(loan),0) as loan,format(loan,0) loancredit,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where branchnumber=$bra and date_add(date,INTERVAL loanrepay month)<'$today'  and loantrans.branchno=$bra group by loanid having sum(loan)+sum(interestcredit)>0");
    return count($loans);

}
public function notesfixed(){
    $results=array();
   $fixes= DB::select("select * from fixednotes where done=0");
   $today=date('Y-m-d');
   foreach($fixes as $fix){
       //$yes=collect($fix);
   if(strtotime(date('Y-m-d',strtotime($fix->date."+5 day")))>strtotime($today)){
       $results[]=$fix;
        Session::put('fixz',$results);


    }else{
        DB::update("update fixednotes set done=1 where acno='$fix->acno' ");
    }

   }

}
}