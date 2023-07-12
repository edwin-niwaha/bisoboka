<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\stuff;

 class loanreportpdfsController extends Controller{

    public function scheduleindex($id){
        $branch=auth()->user()->branchid;
        $company=DB::select("select *,companys.name from companys where id=$branch");
        $loaninfo=DB::select("select name,nopayments,loaninterest,period,loanrepay,format(loan,2) as loan, DATE_FORMAT(date,'%d-%m-%Y') as date , format(sum(interest),2) as interest from loaninfos inner join customers on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join loanschedules on loanschedules.loanid=loaninfos.id where loaninfos.id=$id and isLoan=1 limit 1");
        //$loaninfo=DB::select("select name,loaninterest,loanrepay,format(loan,2) as loan, DATE_FORMAT(date,'%d-%m-%Y') as date , format((((loaninterest/100)*loan)*loanrepay),2) as interest from loaninfos inner join customers on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where loaninfos.id=$id and isLoan=1  limit 1 ");
        $schedule=DB::select("select nopayments, B.id,loanid,format(loanamount,2) as loanamount,format(interest,2) as interest,DATE_FORMAT(scheduledate,'%d-%m-%Y') as date,format(loanamount+interest,2) as total,(SELECT format(SUM(A.runningbal),2) FROM loanschedules AS A where loanid=$id AND  A.id <= B.id ORDER BY B.id  asc) as runningbal   from loanschedules  as B where loanid=$id");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML(view('loanschedulepdf/index')->with('company',$company)->with('schedule',$schedule)->with('loaninfo',$loaninfo));
        return $pdf->stream();
    }

    public function loanarrears(){
        $branch=auth()->user()->branchid;
        $company=DB::select("select * from companys where id=$branch");
        $arrears=DB::select("select name,format(sum(loan),0) as loan,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id where branchnumber=$branch group by loanid having sum(loan)+sum(interestcredit)>0 ");
        $total=DB::select("select format(sum(loan),0) as loan,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id where branchnumber=$branch having sum(loan)+sum(interestcredit)>0");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML(view('loanarrearspdf/default')->with('company',$company)->with('arrears',$arrears)->with('total',$total));
        return $pdf->stream();
        
    }

    public function allloanspdfs($date1,$date2){
        
        $today=date("'Y/m/d'");
        $branch=auth()->user()->branchid;
        $company=DB::select("select * from companys where  id=$branch ");
        if($date1=='day' && $date2=='day'){
            $loans=DB::select("select loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,acno,memid,paydet,customers.name,concat(loaninterest,' ','%' )as loaninterest,concat(loanrepay,' ','month (s)') as loanrepay,collateral,FORMAT(loancredit,0) as loancredit,guanter,headerid from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where loantrans.branchno=$branch and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 AND date=$today");
            $total=DB::select("select format(sum(loancredit),0) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id   where  branchnumber=$branch AND   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 and date=$today");
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML(view('allloanspdf/index')->with('company',$company)->with('loans',$loans)->with('total',$total));
            return $pdf->stream();

        }else{
            $branch=auth()->user()->branchid;
            $company=DB::select("select * from companys where id=$branch ");
            $date1=date("Y-m-d", strtotime($date1));
            $date2=date("Y-m-d", strtotime($date2));
            $loans=DB::select("select loanid,acno,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,paydet,customers.name,concat(loaninterest,' ','%' )as loaninterest,concat(loanrepay,' ','month (s)') as loanrepay,collateral,FORMAT(loancredit,0) as loancredit,guanter,headerid from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id   where loantrans.branchno=$branch and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 AND date Between '$date1' AND '$date2' ");
            $total=DB::select("select format(sum(loancredit),0) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id   where  branchnumber=$branch AND   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 and date Between '$date1' AND '$date2'");
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML(view('allloanspdf/index')->with('loans',$loans)->with('total',$total)->with('company',$company));
            return $pdf->stream();
        }

      
    }
public function loanduepdf($date){

    if($date=='today'){
        $date=date("Y-m-d");
    }else{
    $date=date("Y-m-d", strtotime($date));  
    }
    
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where  id=$branch ");
       $display=date("d-M-Y", strtotime($date));
        $loansdue=DB::select("select DATE_FORMAT(date,'%d-%m-%Y')  date,DATE_FORMAT(date_add(date,INTERVAL loanrepay month),'%d-%m-%Y') maturity,acno,loanrepay,name,format(sum(loan),0) as loan,format(loan,0) loancredit,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where branchnumber=1    group by loanid having sum(loan)+sum(interestcredit)>0");
        $total=DB::select("select DATE_FORMAT(date,'%d-%m-%Y')  date,DATE_FORMAT(date_add(date,INTERVAL loanrepay month),'%d-%m-%Y') maturity,acno,loanrepay,name,format(sum(loan),0) as loan,format(loan,0) loancredit,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where branchnumber=1 and date_add(date,INTERVAL loanrepay month)<'$date'  ");
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML(view('loanduepdfs/index')->with('company',$company)->with('loansdue',$loansdue)->with('total',$total)->with('display',$display))->setPaper('a4', 'landscape');
        return $pdf->stream();
		//and date_add(date,INTERVAL loanrepay month)<'$date' on 23th june 2022

   
}

}