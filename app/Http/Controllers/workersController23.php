<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\workers;
use App\savingdefinations;

 class workersController extends Controller{

public function ledger($acode,$date1,$date2,$day1,$day2){
    $start=date("Y-m-d", strtotime($date1));
    $end=date("Y-m-d", strtotime($date2));
    $thedate='';
    
    $asof='';
    if($day1=="Ledger" && $day2=="Ledger"){
        $today=date("Y-m-d");
        $thedate=" and transdates <='$today'";
        $asof= "As of ".date("d-M-Y");

    }
    else if($day1!=''  && $day2=='Ledger'){
        $days=date("Y-m-d", strtotime($day1)); 
        $thedate=" and transdates <='$days'";
        $asof="As of ".$day1;
    }
    else if($day1!='' && $day2!=''){
        $dates1=date("Y-m-d", strtotime($day1));
        $dates2=date("Y-m-d", strtotime($day2));
        $thedate=" and transdates between '$dates1' and '$dates2'";
        $asof="From ".$day1." To ".$day2;

    }
    //echo $end;
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $chart=DB::select("select accountname from chartofaccounts where accountcode=$acode and branchno=$branch");
    DB::statement("set @CumulativeSum := 0;");
    $today=date('d-m-Y');
    $ledger =  DB::select("select format((@CumulativeSum := @CumulativeSum + total),0) as runningbal, purchaseheaders.id ,DATE_FORMAT(transdates,'%d-%m-%Y') transdates,chartofaccounts.accountname,accounttrans.accountcode,narration,format(if(ttype='D',amount,''),0)as debits,format(if(ttype='C',amount,''),0)as credits,format(amount,0) as amount from (select * from chartofaccounts where branchno=$branch)as chartofaccounts inner join accounttrans on chartofaccounts.accountcode=accounttrans.accountcode inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where  accounttrans.accountcode=$acode AND amount>0 and bracid=$branch and  transdates between '$start' AND '$end' $thedate");
    $ledgerfooter =  DB::select("select  format(sum(if(ttype='D',amount,'')),0)as debits,format(sum(if(ttype='C',amount,'')),0)as credits from (select * from chartofaccounts where branchno=$branch)as chartofaccounts inner join accounttrans on chartofaccounts.accountcode=accounttrans.accountcode inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where  accounttrans.accountcode=$acode and bracid=$branch and  transdates between '$start' AND '$end' $thedate");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('ledgerpdfs/index')->with('company',$company)->with('ledgerfooter',$ledgerfooter)->with('ledger',$ledger)->with('name',$chart)->with('dat',$today)->with('asof',$asof));
   return $pdf->stream();
   
    
    
}

public function allsavings($id){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $client=DB::select("select * from customers where id=$id");
    $active=DB::select("select interest,productname, savingpdt,intActive,isActive from savingdefinations where branchno=$branch ");
    $loan=DB::select("select * from loanproducts where branchno=$branch");
    $ledger =  DB::select("select id, DATE_FORMAT(date,'%d-%m-%Y')date,narration,recieptno,format(savingpdt1,0)as savingpdt1,format(intpdt1,0)as intpdt1,format(savingpdt2,0)as savingpdt2,format(intpdt2,0)as intpdt2,format(savingpdt3,0)as savingpdt3,format(intpdt3,0)as intpdt3,format(savingpdt4,0)as savingpdt4,format(intpdt4,0)as intpdt4,format(savingpdt5,0)as savingpdt5,format(intpdt5,0)as intpdt5,
    format(loanpdt1,0)as loanpdt1,format(loanint1,0)as loanint1,format(loanpdt2,0)as loanpdt2,format(loanint2,0)as loanint2,format(loanpdt3,0)as loanpdt3,format(loanint3,0)as loanint3,format(loanpdt4,0)as loanpdt4,format(loanint4,0)as loanint4,format(loanpdt5,0)as loanpdt5,format(loanint5,0)as loanint5,format(shares,0)as shares,format(ansub,0) as ansub, format(memship,0) as memship from allsavings where client_no=$id ");
    $footer=DB::select("select format(sum(savingpdt1),0)as savingpdt1,format(sum(intpdt1),0)as intpdt1,format(sum(savingpdt2),0)as savingpdt2,format(sum(intpdt2),0)as intpdt2,format(sum(savingpdt3),0)as savingpdt3,format(sum(intpdt3),0)as intpdt3,format(sum(savingpdt4),0)as savingpdt4,format(sum(intpdt4),0)as intpdt4,format(sum(savingpdt5),0)as savingpdt5,format(sum(intpdt5),0)as intpdt5,
    format(sum(loanpdt1),0)as loanpdt1,format(sum(loanint1),0)as loanint1,format(sum(loanpdt2),0)as loanpdt2,format(sum(loanint2),0)as loanint2,format(sum(loanpdt3),0)as loanpdt3,format(sum(loanint3),0)as loanint3,format(sum(loanpdt4),0)as loanpdt4,format(sum(loanint4),0)as loanint4,format(sum(loanpdt5),0)as loanpdt5,format(sum(loanint5),0)as loanint5,format(sum(shares),0)as shares,format(sum(ansub),0) as ansub, format(sum(memship),0) as memship from allsavings where client_no=$id ");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    $pdf->loadHTML(view('allsavingpdfs/index')->with('company',$company)->with('da',$da)->with('client',$client)->with('active',$active)->with('rpt',$ledger)->with('loans',$loan)->with('footer',$footer))->setPaper('a4', 'landscape');
    return $pdf->stream();
    //return $active;
  
}
public function allsavepdt(){
    return DB::select("select * from allsavings");
}

public function statementpdf($id,$pdt){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $client=DB::select("select * from customers where id=$id");
    $savingpdt=DB::select("select productname from savingdefinations where savingpdt='$pdt' and branchno=$branch");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    DB::statement("set @CumulativeSum := 0;");
    $ledger =  DB::select("select format((@CumulativeSum := @CumulativeSum + total),0) as runningbal,DATE_FORMAT(date,'%d-%m-%Y') date,format(moneyin,0) as moneyin, format(moneyout,0) as moneyout,paydet,narration from savings where client_no=$id and category='$pdt' having abs(moneyout+moneyin)>0");
    $pdf->loadHTML(view('statementpdfs/index')->with('company',$company)->with('da',$da)->with('client',$client)->with('ledger',$ledger)->with('pdt',$savingpdt));
    return $pdf->stream();
}

public function trialbalancepdfs($date1,$date2,$day1,$day2){
    $date1=date("Y-m-d", strtotime($date1));
    $date2=date("Y-m-d", strtotime($date2));
    $thedate='';
    $asof='';
    if($day1=="Tbal" && $day2=="Tbal"){
        
        $asof= "As of ".date("d-M-Y");

    }
    else if($day1!=''  && $day2=='Tbal'){
        $date=date("Y-m-d", strtotime($day1)); 
        $thedate=" and transdate <='$date'";
        $asof="As of ".$day1;
    }
    else if($day1!='' && $day2!=''){
        $date1=date("Y-m-d", strtotime($day1));
        $date2=date("Y-m-d", strtotime($day2));
        $thedate=" and transdate between '$date1' and '$date2'";
        $asof="From ".$day1." To ".$day2;

    }

    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $pdf = \App::make('dompdf.wrapper');
    DB::statement("CREATE OR REPLACE VIEW  trialfooter as  SELECT transdate,accountname,accountcode, if(Sum(If(ttype='D',amount,-amount))<0,0,Sum(If(ttype='D',amount,-amount))) as Debits,if(Sum(If(ttype='D',amount,-amount))>0,0,abs(Sum(If(ttype='D',amount,-amount)))) as Credits  from `trialpdfs` where transdate between '$date1' AND '$date2' $thedate group by accountcode");
    $trial=DB::select("SELECT transdate,accountname,accountcode, format(if(Sum(If(ttype='D',amount,-amount))<0,0,Sum(If(ttype='D',amount,-amount))),0) as Debits,format(if(Sum(If(ttype='D',amount,-amount))>0,0,abs(Sum(If(ttype='D',amount,-amount)))),0) as Credits  from `trialpdfs` where transdate between '$date1' AND '$date2' $thedate group by accountcode");
    $footer=DB::select("SELECT  format(sum(debits),0) Debits,format(sum(credits),0) Credits from `trialfooter`  ");
    $pdf->loadHTML(view('trialbalancepdfs/index')->with('company',$company)->with('trial',$trial)->with('footer',$footer)->with('asof',$asof));
    return $pdf->stream();   
}

public function summarypdf(){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $client=DB::select("select * from customers  where branchnumber=$branch");
    $active=DB::select("select interest,productname, savingpdt,intActive,isActive from savingdefinations where branchno=$branch ");
    $loan=DB::select("select * from loanproducts where branchno=$branch");
    $ledger =  DB::select("select name,client_no,format(sum(savingpdt1)+sum(intpdt1),0)as savingpdt1,format(sum(savingpdt2)+sum(intpdt2),0)as savingpdt2,format(sum(savingpdt3)+sum(intpdt3),0)as savingpdt3,format(sum(savingpdt4)+sum(intpdt4),0)as savingpdt4,format(sum(savingpdt5)+sum(intpdt5),0)as savingpdt5,format(sum(shares),0)as shares from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch  group by client_no order by name asc ");
    $footer=DB::select("select client_no,format(sum(savingpdt1)+sum(intpdt1),0)as savingpdt1,format(sum(savingpdt2)+sum(intpdt2),0)as savingpdt2,format(sum(savingpdt3)+sum(intpdt3),0)as savingpdt3,format(sum(savingpdt4)+sum(intpdt4),0)as savingpdt4,format(sum(savingpdt5)+sum(intpdt5),0)as savingpdt5,format(sum(shares),0)as shares from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    $pdf->loadHTML(view('summarypdfs/index')->with('company',$company)->with('da',$da)->with('client',$client)->with('active',$active)->with('rpt',$ledger)->with('loans',$loan)->with('footer',$footer))->setPaper('a4', 'landscape');
    return $pdf->stream();
}
public function bsheetpdf($date1,$date2,$date3){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $finaldate='';
    if($date3=='Bsheet'){
        $finaldate=date('Y-m-d');
    }else{
        $finaldate=date("Y-m-d", strtotime($date3));
    }
    $asof=date("d-m-Y",strtotime($finaldate));
    

    $totalincome=DB::select("select if(amount is Null,0,format(sum(amount),0)) as amount,ttype,accountname,bracid,accountcode from incomepdfs where bracid=$branch and transdate between '$date1' AND '$date2'  AND transdate <='$finaldate'");
    $totalexpense=DB::select("select  if(amount is Null,0,format(sum(amount),0)) as amount,ttype,accountname,bracid,accountcode from expensepdfs where bracid=$branch and transdate between '$date1' AND '$date2'  AND transdate <='$finaldate'");
    $cass=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=1 group by accountcode");
    $tcass=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=1");
    #####
    $fass=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=2 group by accountcode");
    $tfass=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=2");
    $totalass=DB::select(" SELECT accountname,accounttype,accountcode,format(sum(Debit),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'   and bracid=$branch and accounttype!=3 and accounttype!=4 and accounttype!=5 and accounttype!=6 and accounttype!=7 AND transdates <='$finaldate'");
    $liabilites=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=3 or accounttype=4 group by accountcode");
    $totallia=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=3 or accounttype=4");
    $equity=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=5 group by accountcode");
    $totalequity=DB::select(" SELECT accountname,accounttype,accountcode,format(abs(sum(Debit)),0) as amount FROM `bsheetpdfs` where transdates between '$date1' AND '$date2'  AND transdates <='$finaldate' and bracid=$branch and accounttype=5");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('bsheetpdfs/index')->with('totalexpense',$totalexpense)->with('totalincome',$totalincome)->with('equity',$equity)->with('totalequity',$totalequity)->with('company',$company)->with('totallia',$totallia)->with('liabilites',$liabilites)->with('totalass',$totalass)->with('fass',$fass)->with('tfass',$tfass)->with('cass',$cass)->with('tcass',$tcass)->with('asof',$asof))->setPaper('a4', 'potrait');
    return $pdf->stream();
}
public function expensepreview($date1,$date2){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $where='';
    $asof='';
    if($date1=='today' && $date2==0){
        $today=date("Y-m-d");
        $asof="As of ".date("d-m-Y");
        $where="transdate='$today'";
    }
    else if($date1!="" && $date2==0){
        $asof="As of ".date("d-m-Y", strtotime($date1));
        $finaldate=date("Y-m-d", strtotime($date1));
        $where="transdate<='$finaldate'";
    }
    else if($date1!='' && $date2!=''){
        $asof="From ".date("d-m-Y", strtotime($date1))." To ".date("d-m-Y", strtotime($date2));
        $finaldate1=date("Y-m-d", strtotime($date1));
        $finaldate2=date("Y-m-d", strtotime($date2));
        $where="transdate BETWEEN '$finaldate1' AND '$finaldate2' ";
    }
   $expense= DB::select("select *,format(amount,0) as amount,DATE_FORMAT(transdate,'%d-%m-%Y') transdate from expensepdfs where  amount>0 and $where order by transdate asc ");
   $expensetotal= DB::select("select format(sum(amount),0) as amount from expensepdfs where $where ");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    $pdf->loadHTML(view('expensepreview/index')->with('company',$company)->with('expense',$expense)->with('expensetotal',$expensetotal)->with('asof',$asof));
    return $pdf->stream();   
}

public function incomepreview($date1,$date2){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $where='';
    $asof='';
    if($date1=='today' && $date2==0){
        $today=date("Y-m-d");
        $asof="As of ".date("d-m-Y");
        $where="transdate='$today'";
    }
    else if($date1!="" && $date2==0){
        $asof="As of ".date("d-m-Y", strtotime($date1));
        $finaldate=date("Y-m-d", strtotime($date1));
        $where="transdate<='$finaldate'";
    }
    else if($date1!='' && $date2!=''){
        $asof="From ".date("d-m-Y", strtotime($date1))." To ".date("d-m-Y", strtotime($date2));
        $finaldate1=date("Y-m-d", strtotime($date1));
        $finaldate2=date("Y-m-d", strtotime($date2));
        $where="transdate BETWEEN '$finaldate1' AND '$finaldate2' ";
    }
   $expense= DB::select("select *,format(amount,0) as amount,DATE_FORMAT(transdate,'%d-%m-%Y') transdate from incomepdfs where amount>0 and  $where order by transdate asc ");
   $expensetotal= DB::select("select format(sum(amount),0) as amount from incomepdfs where $where ");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    $pdf->loadHTML(view('incomepreview/index')->with('company',$company)->with('expense',$expense)->with('expensetotal',$expensetotal)->with('asof',$asof));
    return $pdf->stream();   
}
public function memberPreview($field){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $where='';
    if($field=='memship'){
        $where='Membership Defaulters';
    }else if($field=='shares'){
        $where='Shares Defaulters';
    }
    $details=DB::select("SELECT if($field  is Null,0,sum($field )) pays,  format(if($field  is Null,0,sum($field )),0) paid,name,acno,format(10000-if($field  is Null,0,sum($field )),0) as bal
    FROM customers
    left outer JOIN allsavings ON customers.id=allsavings.client_no  where branchnumber=$branch   group by customers.id having pays<10000  
    ORDER BY `paid`  DESC ");
    DB::statement("create or replace view totaldefaults as SELECT if($field  is Null,0,sum($field )) paid,name,acno,10000-if($field  is Null,0,sum($field )) as bal
    FROM customers left outer JOIN allsavings ON customers.id=allsavings.client_no  where branchnumber=$branch   group by customers.id having paid<10000  
     ORDER BY `paid`");
     $total=DB::select("select format(sum(paid),0) as paid, format(sum(bal),0) as bal from totaldefaults");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('memshipdefaulterpdfs/index')->with('company',$company)->with('details',$details)->with('total',$total)->with('heading',$where));
    return $pdf->stream(); 
}
}