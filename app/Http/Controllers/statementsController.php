<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\stocktrans;
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
 class statementsController extends Controller{

    
public function index(){

    return view('statements/index1');


}
public function sharepdfs(){
    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $ledger =  DB::select("select round(shares/shareprice,0) as noshares,client_no,name, format(sum(shares),0) as shares from allsavings inner join customers on customers.id=allsavings.client_no inner join sharesettings on sharesettings.branchno=allsavings.branchno where  allsavings.branchno=$branch group by client_no having sum(shares)>0");
    $foot =  DB::select("select round(sum(shares/shareprice),0) as noshares, format(sum(shares),0) as shares from allsavings inner join customers on customers.id=allsavings.client_no inner join sharesettings on sharesettings.branchno=allsavings.branchno where  allsavings.branchno=$branch ");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('sharepdfs/default')->with('company',$company)->with('ledger',$ledger)->with('foot',$foot));
   return $pdf->stream();
    //return view ('sharepdfs/default');
}
public function allsavingproducts(){
    $bra=auth()->user()->branchid;
    $results=DB::select("select id,productname,savingpdt,intpdt,isActive,intActive, if(intActive=0,'hidden','') as hidden from savingdefinations where isActive=1 and branchno=$bra");
    $loanresults=DB::select("select * from loanproducts where isActive=1 and branchno=$bra");
    return view('allsavingproducts/index')->with('result',$results)->with('loans',$loanresults);
}
public function viewstatements(){
    if(isset($_GET['client']) &&  isset($_GET['type']) && isset($_GET['page'])&& isset($_GET['rows'])&& empty($_GET['date2']) && empty($_GET['date1']) ){
        $client=$_GET['client'];
        $type=$_GET['type'];
        $bra=auth()->user()->branchid;
$this->viewstockreport("where client_no=$client and category='$type'   ");

}else if(isset($_GET['page']) &&  isset($_GET['type']) && isset($_GET['rows']) && isset($_GET['date1']) && isset($_GET['date2'])&& isset($_GET['client'])){
    $client=$_GET['client'];  
    $date1=date("Y-m-d", strtotime($_GET['date1']));
    $date2=date("Y-m-d", strtotime($_GET['date2']));
    $type=$_GET['type'];
    //$this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND ttype='D' AND credit is Null","sum(if(ttype='D',amount,''))as amount");
    $this->viewstockreport("where  category='$type' AND client_no=$client AND date BETWEEN '$date1' AND '$date2'  ");
 }
}


public function viewsummary(){
    $branch=auth()->user()->branchid;
    $results=DB::select("select id,productname,savingpdt,intpdt,isActive,intActive, if(intActive=0,'hidden','') as hidden from savingdefinations where isActive=1 and savingac!=603 and savingac!=604 and branchno=$branch");
    return view('summarystats/default')->with('result',$results);
}
public function viewstockreport($where){


   $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("SELECT COUNT(*) as count ,moneyin,moneyout,(SELECT format(SUM(A.total),2) FROM savings AS A $where AND A.id <= B.id ORDER BY B.id  asc ) AS totalbal  
    FROM savings AS B inner join customers on customers.id=B.client_no $where having(abs(sum(moneyin)+sum(moneyout)))   ORDER BY B.id asc ");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("SELECT B.id ,format(B.moneyin,2)as moneyin,paydet,format(B.moneyout,2)as moneyout,B.date,B.client_no,B.narration,customers.name ,(SELECT format(SUM(A.total),2) FROM savings AS A $where AND A.id <= B.id ORDER BY B.id  asc ) AS totalbal  
    FROM savings AS B inner join customers on customers.id=B.client_no $where having abs(moneyin+moneyout)>0  ORDER BY B.id asc  limit  $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select COUNT(*) as count  from savings inner join customers on savings.client_no=customers.id inner join users on users.branchid=customers.branchnumber  $where  limit  $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
   
}


public function summary(){
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $branch=auth()->user()->branchid;
    $admin=auth()->user()->isAdmin;
 
    $krows = DB::select("select  COUNT(*) as count from (select allsavings.id  from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch  group by client_no) as f");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select name,client_no,format(sum(savingpdt1)+sum(intpdt1),0)as savingpdt1,format(sum(savingpdt2)+sum(intpdt2),0)as savingpdt2,format(sum(savingpdt3)+sum(intpdt3),0)as savingpdt3,format(sum(savingpdt4)+sum(intpdt4),0)as savingpdt4,format(sum(savingpdt5)+sum(intpdt5),0)as savingpdt5,format(sum(shares),0)as shares from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch  group by client_no order by name asc  limit $offset,$rows");
 
    $rst->execute();

   $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
    //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select client_no,format(sum(savingpdt1)+sum(intpdt1),0)as savingpdt1,format(sum(savingpdt2)+sum(intpdt2),0)as savingpdt2,format(sum(savingpdt3)+sum(intpdt3),0)as savingpdt3,format(sum(savingpdt4)+sum(intpdt4),0)as savingpdt4,format(sum(savingpdt5)+sum(intpdt5),0)as savingpdt5,format(sum(shares),0)as shares from allsavings inner join customers on customers.id=allsavings.client_no where branchno=$branch");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);

}
public function allsavingproductsrpt(){
    if(isset($_GET['client']) && isset($_GET['page'])&& isset($_GET['rows'])&& empty($_GET['date2']) && empty($_GET['date1']) ){
        $client=$_GET['client'];
$this->allsavings1("where client_no=$client  ");

}else if(isset($_GET['page'])&& isset($_GET['rows']) && isset($_GET['date1']) && isset($_GET['date2'])&& isset($_GET['client'])){
    $client=$_GET['client'];  
    $date1=date("Y-m-d", strtotime($_GET['date1']));
    $date2=date("Y-m-d", strtotime($_GET['date2']));
    //$this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND ttype='D' AND credit is Null","sum(if(ttype='D',amount,''))as amount");
    //$this->viewstockreport("where client_no=$client AND date BETWEEN '$date1' AND '$date2' ");
 } 
}
public function allsavings1($where){
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count  from allsavings $where ");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select id, DATE_FORMAT(date,'%d-%m-%Y') date,narration,recieptno,format(savingpdt1,0)as savingpdt1,format(intpdt1,0)as intpdt1,format(savingpdt2,0)as savingpdt2,format(intpdt2,0)as intpdt2,format(savingpdt3,0)as savingpdt3,format(intpdt3,0)as intpdt3,format(savingpdt4,0)as savingpdt4,format(intpdt4,0)as intpdt4,format(savingpdt5,0)as savingpdt5,format(intpdt5,0)as intpdt5,
    format(loanpdt1,0)as loanpdt1,format(loanint1,0)as loanint1,format(loanpdt2,0)as loanpdt2,format(loanint2,0)as loanint2,format(loanpdt3,0)as loanpdt3,format(loanint3,0)as loanint3,format(loanpdt4,0)as loanpdt4,format(loanint4,0)as loanint4,format(loanpdt5,0)as loanpdt5,format(loanint5,0)as loanint5,format(shares,0)as shares,format(ansub,0) as ansub, format(memship,0) as memship from allsavings $where limit  $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   //Showing The footer and totals 
 $footer =  DB::getPdo()->prepare("select format(sum(savingpdt1),0)as savingpdt1,format(sum(intpdt1),0)as intpdt1,format(sum(savingpdt2),0)as savingpdt2,format(sum(intpdt2),0)as intpdt2,format(sum(savingpdt3),0)as savingpdt3,format(sum(intpdt3),0)as intpdt3,format(sum(savingpdt4),0)as savingpdt4,format(sum(intpdt4),0)as intpdt4,format(sum(savingpdt5),0)as savingpdt5,format(sum(intpdt5),0)as intpdt5,
  format(sum(loanpdt1),0)as loanpdt1,format(sum(loanint1),0)as loanint1,format(sum(loanpdt2),0)as loanpdt2,format(sum(loanint2),0)as loanint2,format(sum(loanpdt3),0)as loanpdt3,format(sum(loanint3),0)as loanint3,format(sum(loanpdt4),0)as loanpdt4,format(sum(loanint4),0)as loanint4,format(sum(loanpdt5),0)as loanpdt5,format(sum(loanint5),0)as loanint5,format(sum(shares),0)as shares,format(sum(ansub),0) as ansub, format(sum(memship),0) as memship from allsavings $where");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results); 
}
public function sharesindex(){
    return view('shares/index');
}
public function shares(){
    $results=array();
    $branch=auth()->user()->branchid;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select  COUNT(*) as count from (select allsavings.id from allsavings inner join customers on customers.id=allsavings.client_no where shares>0 and allsavings.branchno=$branch group by client_no) as f");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select round(sum(shares)/shareprice,0) as noshares,client_no,name, format(sum(shares),0) as shares from allsavings inner join customers on customers.id=allsavings.client_no inner join sharesettings on sharesettings.branchno=allsavings.branchno where  allsavings.branchno=$branch group by client_no having sum(shares)>0 limit  $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select round(sum(shares/shareprice),0) as noshares, format(sum(shares),0) as shares from allsavings inner join customers on customers.id=allsavings.client_no inner join sharesettings on sharesettings.branchno=allsavings.branchno where  allsavings.branchno=$branch ");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results); 
}
public function example(){

  $savingcal=DB::select("select id, savingpdt,nodays,frekq,begining,next from savingcals");
  foreach($savingcal as $cal){
      $currentdate=strtotime(date("d-m-Y"));
      $month=strtotime($cal->next."$cal->frekq months");
      $begin=strtotime($cal->begining);
      $x=$cal->frekq;
      if($cal->savingpdt=='savingpdt1'){
     while($currentdate>=$month){
       $dat=$month;
       $month=strtotime($cal->next."$x months");
       if($cal->nodays==0){
       $this->postinterest($cal->savingpdt,'intpdt1',false,date('Y-m-d',$begin),date('Y-m-d',$month));
       }else{
        $this->postinterest($cal->savingpdt,'intpdt1',true,date('Y-m-d',$begin),date('Y-m-d',$month));   
       }
       //echo date('Y-m-d',$month)."<br><br>";
       $month=date('Y-m-d',$month); 
       $month=strtotime($month."$cal->frekq months"); 
       $dat=date('Y-m-d',strtotime(date('Y-m-d',$month)."-$cal->frekq months"));
       $calculatedbegin= date('Y-m-d',strtotime(date('Y-m-d',strtotime($dat."-$cal->nodays days"))."$cal->frekq months"));
       DB::update("update savingcals set next='$dat',begining='$calculatedbegin' where savingpdt='savingpdt1'");
       $x++;

      }
    }
    if($cal->savingpdt=='savingpdt2'){
        while($currentdate>=$month){
          $dat=$month;
          $month=strtotime($cal->next."$x months");
          if($cal->nodays==0){
            $this->postinterest($cal->savingpdt,'intpdt2',false,date('Y-m-d',$begin),date('Y-m-d',$month));
            }else{
             $this->postinterest($cal->savingpdt,'intpdt2',true,date('Y-m-d',$begin),date('Y-m-d',$month));   
            }
          //echo date('Y-m-d',$month)."<br><br>";
          $month=date('Y-m-d',$month); 
          $month=strtotime($month."$cal->frekq months"); 
          $dat=date('Y-m-d',strtotime(date('Y-m-d',$month)."-$cal->frekq months"));
          $calculatedbegin= date('Y-m-d',strtotime(date('Y-m-d',strtotime($dat."-$cal->nodays days"))."$cal->frekq months"));
          DB::update("update savingcals set next='$dat',begining='$calculatedbegin' where savingpdt='savingpdt2'");
          $x++;
   
         }
       }
       if($cal->savingpdt=='savingpdt3'){
        while($currentdate>=$month){
          $dat=$month;
          $month=strtotime($cal->next."$x months");
          if($cal->nodays==0){
            $this->postinterest($cal->savingpdt,'intpdt3',false,date('Y-m-d',$begin),date('Y-m-d',$month));
            }else{
             $this->postinterest($cal->savingpdt,'intpdt3',true,date('Y-m-d',$begin),date('Y-m-d',$month));   
            }
          //echo date('Y-m-d',$month)."<br><br>";
          $month=date('Y-m-d',$month); 
          $month=strtotime($month."$cal->frekq months"); 
          $dat=date('Y-m-d',strtotime(date('Y-m-d',$month)."-$cal->frekq months"));
          $calculatedbegin= date('Y-m-d',strtotime(date('Y-m-d',strtotime($dat."-$cal->nodays days"))."$cal->frekq months"));
          DB::update("update savingcals set next='$dat',begining='$calculatedbegin' where savingpdt='savingpdt3'");
          $x++;
   
         }
       }
       if($cal->savingpdt=='savingpdt4'){
        while($currentdate>=$month){
          $dat=$month;
          $month=strtotime($cal->next."$x months");
          if($cal->nodays==0){
            $this->postinterest($cal->savingpdt,'intpdt4',false,date('Y-m-d',$begin),date('Y-m-d',$month));
            }else{
             $this->postinterest($cal->savingpdt,'intpdt4',true,date('Y-m-d',$begin),date('Y-m-d',$month));   
            }
          //echo date('Y-m-d',$month)."<br><br>";
          $month=date('Y-m-d',$month); 
          $month=strtotime($month."$cal->frekq months"); 
          $dat=date('Y-m-d',strtotime(date('Y-m-d',$month)."-$cal->frekq months"));
          $calculatedbegin= date('Y-m-d',strtotime(date('Y-m-d',strtotime($dat."-$cal->nodays days"))."$cal->frekq months"));
          DB::update("update savingcals set next='$dat',begining='$calculatedbegin' where savingpdt='savingpdt4'");
          $x++;
   
         }
       }

  }


}
public function postinterest($savingpdt,$intpdt,$isexclude,$prevmonth,$postmonth){
    $pdtdefs=DB::select("select freq,savingsproducts.interest as interest,intmethod,dayofpost from savingsproducts inner join savingdefinations on savingdefinations.savingac=savingsproducts.accountcode where savingsproducts.isActive=1 and savingpdt='$savingpdt'");
    foreach($pdtdefs as $def){
    if($isexclude==true){
        $where=" where date <='$prevmonth'";
    }
    else{
       $where="";
    }
    $pdts=DB::select("select client_no,sum($savingpdt) as savings from allsavings $where   group by client_no"); 
     if($def->interest>0){
    foreach($pdts as $pdt){
        // P*R*T
            $calculatedinterest=$pdt->savings*($def->interest/100)*$def->freq;
            $objallsavings= new allsavings();
            $objallsavings->date=$postmonth;
            //$objallsavings->recieptno=$postmonth;
            $objallsavings->client_no=$pdt->client_no;
            $objallsavings->$intpdt=$calculatedinterest;
            $objallsavings->narration="Auto interest computation";
            $objallsavings->save();
            

        }
    }

        
    

    }
}
public function computeloans(){
    
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

}

public function computeinterestRBal($balance,$loanid,$date,$loancat,$branchid){
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
    
    

}
public function incrementrepaymentsch($interest,$loanid,$loanbalance){
    
        $amount=$this->getLoanRBal($loanid,$this->getPosition($loanbalance,$this->getLoanRBal($loanid,$loanbalance),$loanid,"loan"));
        $getid=DB::select("select id from loanrepayments where loanrunbal=$amount");
        foreach($getid as $gid){
            //if($loanbalance>0){
            DB::update("update loanrepayments set intrunbal=intrunbal+$interest where loanid=$loanid and id>=$gid->id"); 
            //}      
    }
    
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
 }

 
 