<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\stuff;
use App\loanschedules;
use App\interestbals;
use App\loanbals;
use App\loandetails;
use App\mantains;
use App\purchaseheaders;
use App\savings;
use App\accounttrans;

 class loanschedulesController extends Controller{

public function index(){
    return view('stuff/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from stuff ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from stuff limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objstuff=new stuff();
$Objstuff->id=$request['id'];
$Objstuff->name=$request['name'];
$Objstuff->created_at=$request['created_at'];
$Objstuff->updated_at=$request['updated_at'];
$Objstuff->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objstuff=stuff::find($id);

$Objstuff->id=$request['id'];
$Objstuff->name=$request['name'];
$Objstuff->created_at=$request['created_at'];
$Objstuff->updated_at=$request['updated_at'];
$Objstuff->save();
}
 public function destroy($id){
        $Objstuff=stuff::find($id);
        $Objstuff->delete();



    }

public function viewcombo(){


    return stuff::all();
}
public function getdate1(){
    DB::beginTransaction();
    try{

    //$date="01-10-2019";
    //$date1= "29-12-2019";//strtotime('+2 months',strtotime($date));
    $lastdate=DB::select("select lastdate from mantains");
    foreach($lastdate as $lastdate){
      $diff=strtotime(date('d-m-Y'))-strtotime($lastdate->lastdate);
      $months=intdiv($diff,(((60*60)*24)*30));
     // $days=round($diff/(21*24),1);
     
     if($months>0){ // months is reachead
        if($months>=1){
            // First date
              // Obtaining header number from purchaseheaders table 
              $objheaders= new purchaseheaders();
              $objheaders->transdates=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30);
              $objheaders->save();
           $savingsid= DB::select("select customers.id as clientid,name from savings inner join customers on savings.client_no=customers.id group by savings.client_no");
           foreach($savingsid as $savingsid){
             // Ledger Fees 
             $objsavings= new savings();
             $objsavings->client_no=$savingsid->clientid;
             $objsavings->moneyout=1000;
             $objsavings->total=1000*-1;
             $objsavings->paydet=date('d-m-Y')."-".$objheaders->id;
             $objsavings->date=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30);
             $objsavings->narration= "Account Mantainence & Ledger Fees";
             $objsavings->purchase_headerid=$objheaders->id;
             $objsavings->save();

             // Acounts Tables
               // inserting into accountrans  Cash Account
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=1000;
            $objaccountrans->total=1000;
            $objaccountrans->accountcode="21";
            $objaccountrans->narration=$savingsid->name." -Accounts Maintainence";
            $objaccountrans->ttype="D";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->transdate=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30);
            $objaccountrans->save();
            
            // Withdraw charges 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=1000;
            $objaccountrans->total=1000*-1;
            $objaccountrans->accountcode="6004";
            $objaccountrans->narration=$savingsid->name." -Accounts Maintainence";
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->transdate=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30);
            $objaccountrans->save();

            
           
           }
           $days=28*$months;
           $updateddate=date('Y-m-d',strtotime("+$days days",strtotime($lastdate->lastdate)));
          DB::update("update mantains set lastdate='$updateddate' where id=1");
           
            } 
            
            if($months>1){
                  // Obtaining header number from purchaseheaders table 
                  
                $month=$months;    
            for($month;$month>0;$month--){ 
                if($month==1)
                continue;
                $objheaders= new purchaseheaders();
                $objheaders->transdates=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30*$month);
                $objheaders->save();
                ################################## WHEn months are more than one ########################
                $savingsid= DB::select("select customers.id as clientid,name from savings inner join customers on savings.client_no=customers.id group by savings.client_no");
                foreach($savingsid as $savingsid){
     
                
      
                  // Ledger Fees 
                  $objsavings= new savings();
                  $objsavings->client_no=$savingsid->clientid;
                  $objsavings->moneyout=1000;
                  $objsavings->total=1000*-1;
                  $objsavings->paydet=date('d-m-Y')."-".$objheaders->id;
                  $objsavings->date=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30*$month);
                  $objsavings->narration= "Account Mantainence & Ledger Fees";
                  $objsavings->purchase_headerid=$objheaders->id;
                  $objsavings->save();
     
                  // Acounts Tables
                    // inserting into accountrans  Cash Account
                 $objaccountrans=new accounttrans;
                 $objaccountrans->purchaseheaderid=$objheaders->id;
                 $objaccountrans->amount=1000;
                 $objaccountrans->total=1000;
                 $objaccountrans->accountcode="21";
                 $objaccountrans->narration=$savingsid->name." -Accounts Maintainence";
                 $objaccountrans->ttype="D";
                 $objaccountrans->bracid=auth()->user()->branchid;
                 $objaccountrans->transdate=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30*$month);
                 $objaccountrans->save();
                 
                 // Withdraw charges 
                 $objaccountrans=new accounttrans;
                 $objaccountrans->purchaseheaderid=$objheaders->id;
                 $objaccountrans->amount=1000;
                 $objaccountrans->total=1000*-1;
                 $objaccountrans->accountcode="6004";
                 $objaccountrans->narration=$savingsid->name." -Accounts Maintainence";
                 $objaccountrans->ttype="C";
                 $objaccountrans->bracid=auth()->user()->branchid;
                 $objaccountrans->transdate=date("Y-m-d", strtotime($lastdate->lastdate)+(21*24)*30*$month);
                 $objaccountrans->save();
     
                 
                
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

}
public function getdate(){

    //$number=3000;
    $workedoninterest=$this->totalIntLoan(330000,160000);
    $totalinterest=$workedoninterest;
    $totalloan=0;
    $interest=0;
    $amount=1500000;//1500000;
    $loan=0;
    //echo $this->totalinterest(57,160000);

    while($amount>0){
#################################### INTEREST ###########################################
        if($amount<=160000){
            $interest=$interest+$amount;
            $totalinterest=$totalinterest+$amount;
            $amount=$amount-$amount;

        }else{
            $a=160000-$totalinterest;
            if($a<=0){
            $interest=$interest+160000;
            $totalinterest=$totalinterest+$interest;
            $amount=$amount-160000;
        }else{
            $interest=160000-$totalinterest;
            $totalinterest=$totalinterest+$interest;
            $amount=$amount-$interest; 
        }
            
        }
################################## LOANS ######################################################        
        if($amount<=500000){
            $loan=$loan+$amount;
            $totalloan=$totalloan+$amount;
            $amount=$amount-$amount;
        }else{
            //$loan=500000-$totalloan;
            $b=500000-$totalloan;
            if($b<=0){
                $loan=$loan+500000;
                $totalloan=$totalloan+$loan;
                $amount=$amount-500000; 
                
            }else{
                $loan=500000-$totalloan;
                $totalloan=$totalloan+$loan;
                $amount=$amount-$loan;

            }

           
        }
        

 

}// End of while loop 

echo " <br>The interest is ".$interest."";
echo "<br>The Loan is ".$loan;
echo "<br>The Total Amount  is ".($loan+$interest);

    
   



}

public function totalIntLoan($totalloan,$scheduleloan){
    while($totalloan>$scheduleloan){
        $totalloan=$totalloan-$scheduleloan;
        if($totalloan==0){
            $totalloan=$scheduleloan;
        }
    }
    return $totalloan;
}

public function loanindex($id){
    $branch=auth()->user()->branchid;
    $clientdetails=DB::select("select name,nopayments,loaninterest,period,loanrepay,format(loan,2) as loan, DATE_FORMAT(date,'%d-%m-%Y') as date , format(sum(interest),2) as interest from loaninfos inner join customers on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join loanschedules on loanschedules.loanid=loaninfos.id where loaninfos.id=$id and isLoan=1 and loantrans.branchno=$branch limit 1");
    return view('loanschedules/index')->with('id',$id)->with('clientdetails',$clientdetails);


}

public function viewschedule($id){

    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from  loanschedules where loanid= $id ");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select nopayments, B.id,loanid,format(loanamount,2) as loanamount,format(interest,2) as interest,DATE_FORMAT(scheduledate,'%d-%m-%Y') as date,format(loanamount+interest,2) as total,(SELECT format(if(SUM(A.runningbal)<0,0,SUM(A.runningbal)),2) FROM loanschedules AS A where loanid=$id AND  A.id <= B.id ORDER BY B.id  asc) as runningbal   from loanschedules  as B where loanid=$id limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   echo json_encode($results);



}

public function allloans(){
    return view('allloans/index');
}

public function loansdueindex(){
    return view("loansdue/default");
}
/*
public function viewloandue(){
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1'])){
       
        $today=date("'Y-m-d'");
        $this->dueloans($today);

    }else{
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->dueloans("'$date1'");
    }


}

public function dueloans($where){
    $branch=auth()->user()->branchid;
   // $today=date("'Y/m/d'");
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from ( select count(*) from  customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where loantrans.branchno=$branch and date_add(date,INTERVAL loanrepay month)<$where group by loanid having sum(loan)+sum(interestcredit)>0) d1");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select DATE_FORMAT(date,'%d-%m-%Y')  date,DATE_FORMAT(date_add(date,INTERVAL loanrepay month),'%d-%m-%Y') maturity,acno,loanrepay,name,format(sum(loan),0) as loan,format(loan,0) loancredit,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where branchnumber=$branch and date_add(date,INTERVAL loanrepay month)<$where  and loantrans.branchno=$branch group by loanid having sum(loan)+sum(interestcredit)>0 order by name asc limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
        //Showing The footer and totals 
        $footer =  DB::getPdo()->prepare("select  format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where  loantrans.branchno=$branch  and date_add(date,INTERVAL loanrepay month)<$where ");
        $footer->execute();
        $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
        $results['footer']=$foots;
        echo json_encode($results);
}
}*/
public function viewloandue(){
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1'])){
       
        $today=date("'Y-m-d'");
        $this->dueloans($today,"");

    }else{
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $optional ="and date_add(date,INTERVAL loanrepay month)<".$date1;
        $this->dueloans("'$date1'",$this->optional);
    }


}
public function dueloans($where,$optional){
    $branch=auth()->user()->branchid;
   // $today=date("'Y/m/d'");
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from ( select count(*) from  customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where loantrans.branchno=$branch $optional and date_add(date,INTERVAL loanrepay month)<$where group by loanid having sum(loan)+sum(interestcredit)>0) d1");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select DATE_FORMAT(date,'%d-%m-%Y')  date,DATE_FORMAT(date_add(date,INTERVAL loanrepay month),'%d-%m-%Y') maturity,acno,loanrepay,name,format(sum(loan),0) as loan,format(loan,0) loancredit,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where branchnumber=$branch  $optional  and loantrans.branchno=$branch group by loanid having sum(loan)+sum(interestcredit)>0 order by name asc limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
        //Showing The footer and totals 
        $footer =  DB::getPdo()->prepare("select  format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id inner join loaninfos on loaninfos.id=loantrans.loanid where  loantrans.branchno=$branch $optional and date_add(date,INTERVAL loanrepay month)<$where ");
        $footer->execute();
        $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
        $results['footer']=$foots;
        echo json_encode($results);
}
}