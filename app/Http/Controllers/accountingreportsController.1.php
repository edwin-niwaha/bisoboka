<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


 class accountingreportsController extends Controller{

    public function index(){
        return view('ledgers/index');
    }

    public function ledger(){
       $account=$_GET['account']; 
       $start=date("Y-m-d", strtotime($_GET['start']));
   $today=date("'Y/m/d'");
   $endFin=date("Y-m-d", strtotime($_GET['end']));
   if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2']) &&isset($_GET['account'])){   
    $date1=date("Y-m-d", strtotime($_GET['date1']));
    $date2=date("Y-m-d", strtotime($_GET['date2']));
    $this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttrans.accountcode=$account AND amount>0","format(sum(if(ttype='D',amount,'')),0)as amount,format(sum(if(ttype='D',amount,'')),0) as debits,format(sum(if(ttype='C',amount,'')),0) as credits "," ");

   }
   else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && empty($_GET['date2']) &&isset($_GET['account'])){
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $this->report("where transdates <='$date1' AND transdates BETWEEN '$start' AND '$endFin' AND accounttrans.accountcode=$account AND amount>0 ","sum(if(ttype='D',amount,''))as amount,format(sum(if(ttype='D',amount,'')),0) as debits,format(sum(if(ttype='C',amount,'')),0) as credits "," "); 
      
   }else{
    $this->report("where transdates = $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttrans.accountcode=$account AND amount>0 ","sum(if(ttype='D',amount,''))as amount,format(sum(if(ttype='D',amount,'')),0) as debits,format(sum(if(ttype='C',amount,'')),0) as credits "," ");
        
}

    }
    
    public function totalexpensesindex(){
        return view('totalexpenses/index');
    }
   public function totalexpense(){
      $start=date("Y-m-d", strtotime($_GET['start']));
      $today=date("'Y-m-d'");
      $endFin=date("Y-m-d", strtotime($_GET['end']));
   
       if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && empty($_GET['date1']) && empty($_GET['date2'])){
         $this->report("where transdates= $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null and amount>0 ","format(sum(if(ttype='D',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
              
       }
       else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && empty($_GET['date2'])){
         $date1=date("Y-m-d", strtotime($_GET['date1']));
         $this->report("where transdates<= '$date1' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null and amount>0 ","format(sum(if(ttype='D',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
       }
       else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])){
         $date1=date("Y-m-d", strtotime($_GET['date1']));
         $date2=date("Y-m-d", strtotime($_GET['date2']));
         
         $this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null and amount>0 ","format(sum(if(ttype='D',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
       }
   }
   public function indextotalincome(){
       return view('totalincomes/index');
   }
   public function totalincome(){
    $start=date("Y-m-d", strtotime($_GET['start']));
   $today=date("'Y-m-d'");
   $endFin=date("Y-m-d", strtotime($_GET['end']));

    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && empty($_GET['date1']) && empty($_GET['date2'])){
      $this->report("where transdates=$today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null and amount>0 ","format(sum(if(ttype='C',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
           
    }
    else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && empty($_GET['date2'])){
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $this->report("where transdates<='$date1' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null and amount>0 ","format(sum(if(ttype='C',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
    }
    else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])){
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $date2=date("Y-m-d", strtotime($_GET['date2']));
      
      $this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null and amount>0 ","format(sum(if(ttype='C',amount,'')),0)as amount","inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype");
    }
    
   }

   public function indextrialbalance(){
    return view('trialbalances/index');
}
public function balancesheet(){
    $start=date("Y-m-d", strtotime($_GET['start']));
    $today=date("'Y/m/d'");
    $endFin=date("Y-m-d", strtotime($_GET['end']));
    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
       $date1=date("Y-m-d", strtotime($_GET['date1']));
       $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->balShit(" BETWEEN '$date1' AND '$date2' ");
    
    }else{
   $this->balShit(" <= $today");
        
   
 }
  
}
public function indexbalancesheet(){
    return view('balancesheets/index');
}
public function viewincomestats(){
   return view('incomestats/index');  
}
 public function trialbalance(){
    $start=date("Y-m-d", strtotime($_GET['start']));
    $today=date("'Y/m/d'");
    $endFin=date("Y-m-d", strtotime($_GET['end']));
    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
       $date1=date("Y-m-d", strtotime($_GET['date1']));
       $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->viewtrial("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin'");
    $this->Strialbalance();
    }else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && empty($_GET['date1']) && empty($_GET['date2'])){
   $this->viewtrial("where transdates BETWEEN '$start' AND $today AND transdates BETWEEN '$start' AND '$endFin' ");
       $this->Strialbalance();
      
   
 }else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && empty($_GET['date2'])){
   $date1=date("Y-m-d", strtotime($_GET['date1']));
   $this->viewtrial("where transdates BETWEEN '$start' AND '$date1' AND transdates BETWEEN '$start' AND '$endFin' ");
        $this->Strialbalance();

   
 }
}

 public function incomestatement(){
    $start=date("Y-m-d", strtotime($_GET['start']));
    $today=date("'Y/m/d'");
    $endFin=date("Y-m-d", strtotime($_GET['end']));
    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
       $date1=date("Y-m-d", strtotime($_GET['date1']));
       $date2=date("Y-m-d", strtotime($_GET['date2']));
      $this->income("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' ANd credit is Null  OR credit =''  ");
   
    }else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && empty($_GET['date2'])){
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $this->income("where transdates<='$date1' AND transdates BETWEEN '$start' AND '$endFin' AND credit is Null  OR credit ='' ");  
     
    }else{
   $this->income("where transdates<=$today AND transdates BETWEEN '$start' AND '$endFin' AND credit is Null  OR credit ='' ");
         
 }
     

 }


 public function trialandsheet($sql,$count,$footer){
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select($count);
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare($sql." limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   // Showing Footer
   $footer =  DB::getPdo()->prepare($footer ." limit $offset,$rows ");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);

 }
    public function report($where,$total,$join){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $branch=auth()->user()->branchid;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from (select accounttrans.id from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype  $where and bracid=$branch  group by accounttrans.id) as f ");
        $results['total']=$krows[0]->count;
        DB::statement("set @CumulativeSum := 0;");
        $rst =  DB::getPdo()->prepare("select format((@CumulativeSum := @CumulativeSum + total),0) as runningbal, purchaseheaders.id ,DATE_FORMAT(transdates,'%d-%m-%Y') transdates,chartofaccounts.accountname,accounttrans.accountcode,narration,format(if(ttype='D',amount,''),0)as debits,format(if(ttype='C',amount,''),0)as credits,format(amount,0) as amount from (select * from chartofaccounts where branchno=$branch)as chartofaccounts inner join accounttrans on chartofaccounts.accountcode=accounttrans.accountcode inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join accounttypes on chartofaccounts.accounttype=accounttypes.id   $where and bracid=$branch  order by transdates asc limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
         // Showing Footer
   $footer =  DB::getPdo()->prepare("select $total from (select * from chartofaccounts where branchno=$branch)as chartofaccounts inner join accounttrans on chartofaccounts.accountcode=accounttrans.accountcode inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join accounttypes on chartofaccounts.accounttype=accounttypes.id    $where  and bracid=$branch ");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
 }

 public function viewtrial($where){
   $branch=auth()->user()->branchid;
     DB::statement('drop view trial');
     DB::statement("create view trial as select  accounttrans.accountcode,Sum(If(ttype='D',amount,-amount))as Debit from purchaseheaders inner join accounttrans on purchaseheaders.id=accounttrans.purchaseheaderid
     AND accounttrans.bracid=$branch $where  group by accounttrans.accountcode  ");
 }

 public function Strialbalance(){
    $count="select COUNT(*) as count,accountname from trial inner join chartofaccounts on chartofaccounts.accountcode=trial.accountcode group by  chartofaccounts.accountcode";
    $sql="select accountname,chartofaccounts.accountcode,format(if(Debit<0,0,Debit),0) as Debits ,format(if(Debit>0,0,abs(Debit)),0) as Credits from trial inner join chartofaccounts on chartofaccounts.accountcode=trial.accountcode group by chartofaccounts.accountcode  ";
    $footer="select format(sum(if(Debit<0,0,Debit)),0) as Debits ,format(sum(if(Debit>0,0,abs(Debit))),0) as Credits from trial   ";
    $this->trialandsheet($sql,$count,$footer);  
 }
public function balShit($where){
   $branch=auth()->user()->branchid;
   $footer="select * from incomestatement";// Not so usefull but the bal sheet cannot work without it
   DB::statement("drop view Bsheet");
     DB::statement("create view Bsheet as select accountcode,bracid,ttype,If(ttype='D',amount,-amount)as Debit,transdates from accounttrans inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid where transdates $where and bracid=$branch");
     $sql="SELECT chartofaccounts.accounttype as type, accounttypes.accounttype,bsheet.accountcode,accountname,abs(sum(Debit)) as Debit FROM `bsheet` inner join chartofaccounts on chartofaccounts.accountcode=bsheet.accountcode inner join  accounttypes on accounttypes.id=chartofaccounts.accounttype where branchno=$branch AND chartofaccounts.accounttype=1 OR chartofaccounts.accounttype=2 OR chartofaccounts.accounttype=3 OR chartofaccounts.accounttype=4 OR chartofaccounts.accounttype=5 group by bsheet.accountcode,accountname,chartofaccounts.accounttype";
           $count="SELECT COUNT(*) as count FROM `bsheet` inner join chartofaccounts on chartofaccounts.accountcode=bsheet.accountcode inner join  accounttypes on accounttypes.id=chartofaccounts.accounttype where branchno=$branch AND chartofaccounts.accounttype=1 OR chartofaccounts.accounttype=2 OR chartofaccounts.accounttype=3 OR chartofaccounts.accounttype=4 OR chartofaccounts.accounttype=5 group by bsheet.accountcode,accountname,chartofaccounts.accounttype";
        $this->trialandsheet($sql,$count,$footer);

}

public function IncS($where){
    $footer="select * from incomestatement";// Not so usefull but the bal sheet cannot work without it
DB::statement("drop view Incs");
  DB::statement("create view Incs as select ttype,chartofaccounts.accountname,chartofaccounts.accounttype,chartofaccounts.accountcode,accounttypes.accounttype as type,If(ttype='D',amount,-amount)as Debit,transdate from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on chartofaccounts.accounttype=accounttypes.id where transdate $where  and  chartofaccounts.accounttype=1 OR chartofaccounts.accounttype=2 OR chartofaccounts.accounttype=3 OR chartofaccounts.accounttype=4 OR chartofaccounts.accounttype=5");
  $sql="select accountname,accounttype,accountcode,accounttype as type,abs(Sum(If(ttype='K',Debit,-Debit)))as Debit from Bsheet  where accounttype=1 OR accounttype=2 OR accounttype=3 OR accounttype=4 OR accounttype=5 group by accountcode,accountname,accounttype,type";
        $count="select COUNT(*) as count,accountname,accounttype,accountcode,accounttype as type,abs(Sum(If(ttype='K',Debit,-Debit)))as Debit from Bsheet  where accounttype=1 OR accounttype=2 OR accounttype=3 OR accounttype=4 OR accounttype=5 group by accountcode,accountname,accounttype,type";
     $this->trialandsheet($sql,$count,$footer);

}

public function income($where){
   $branch=auth()->user()->branchid;
    DB::statement("drop view incstat");
    DB::statement("create view incstat as select ttype,amount,credit,chartofaccounts.accountname,chartofaccounts.accounttype,chartofaccounts.accountcode,accounttypes.accounttype as type,transdates from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on chartofaccounts.accounttype=accounttypes.id inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid  where branchno=$branch  and  chartofaccounts.accounttype=6  OR chartofaccounts.accounttype=7   ");
    DB::statement("drop view totalincome");
    DB::statement("create view totalincome as select * from incstat $where");
    $sql="select accountname,accounttype,accountcode,type,abs(Sum(If(ttype='D',amount,-amount)))as Debit from totalincome  group by accountcode,accountname,accounttype order by accounttype";
    $count="select COUNT(*) as count, accountname,accounttype,accountcode,type,abs(Sum(If(ttype='D',amount,-amount)))as Debit from totalincome  group by accountcode,accountname,accounttype order by accounttype DESC";
    $footer="select format(sum(if(accounttype=6,amount,0))-sum(if(accounttype=7,amount,0)),0) as accountname from totalincome";
    $this->trialandsheet($sql,$count,$footer);
}

public function viewexp($id){
return DB::select("select accounttrans.purchaseheaderid as Aid,accounttrans.id as accounttransid,accounttrans.accountcode,accountname,narration,format(amount,0) as amount from accounttrans inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode where purchaseheaderid=$id and ttype='D' and accounttype=7 ");
}
public function viewInc($id){
   return DB::select("select accounttrans.purchaseheaderid as Aid,accounttrans.id as accounttransid,accounttrans.accountcode,accountname,narration,format(amount,0) as amount from accounttrans inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode where purchaseheaderid=$id and ttype='C' and accounttype=6 ");
   }
 }