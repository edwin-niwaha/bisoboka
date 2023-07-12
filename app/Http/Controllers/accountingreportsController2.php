<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


 class accountingreportsController extends Controller{

    public function index(){
        return view('ledgers/index');
    }

    public function ledger(){
      // $account=$_GET['account']; 
       $start=date("Y-m-d", strtotime($_GET['start']));
   $today=date("'Y/m/d'");
   $endFin=date("Y-m-d", strtotime($_GET['end']));

   if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && empty($_GET['date1']) && empty($_GET['date2']) && isset($_GET['account']) && isset($_GET['branch']))  {
       $account=$_GET['account'];
       $branch=$_GET['branch'];
       //echo "yes";
    $this->report("where purchaseheaders.isActive=1 and bracid=$branch and  transdates = $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttrans.accountcode=$account ","format(sum(if(ttype='D',amount,'')),0)as amount,format(sum(if(ttype='D',amount,'')),0) as debits,format(sum(if(ttype='C',amount,'')),0) as credits ");
       
}      
if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && isset($_GET['date1']) && isset($_GET['date2']) && isset($_GET['account']) && isset($_GET['branch']))  {
    $account=$_GET['account'];
    $branch=$_GET['branch'];
    $date1=date("Y-m-d", strtotime($_GET['date1']));
    $date2=date("Y-m-d", strtotime($_GET['date2']));
 $this->report("where purchaseheaders.isActive=1 and bracid=$branch and  transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttrans.accountcode=$account ","format(sum(if(ttype='D',amount,'')),0)as amount,format(sum(if(ttype='D',amount,'')),0) as debits,format(sum(if(ttype='C',amount,'')),0) as credits ");
    
}



    }
    
    public function totalexpensesindex(){
        return view('totalexpenses/index');
    }
   public function totalexpenses(){
   $start=date("Y-m-d", strtotime($_GET['start']));
   $today=date("'Y/m/d'");
   $endFin=date("Y-m-d", strtotime($_GET['end']));
   if(auth()->user()->isAdmin==1){
   if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2']) && !empty($_GET['branch'])){
      
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $date2=date("Y-m-d", strtotime($_GET['date2']));
      $branch=$_GET['branch'];
      $this->report("where  purchaseheaders.isActive=1 AND transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND ttype='D' AND bracid=$branch AND credit is Null","format(sum(if(ttype='D',amount,'')),0)as amount");
  
   }else{
   //if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['branch'])){
    $branch=$_GET['branch'];
    $this->report("where  purchaseheaders.isActive=1 AND  bracid=$branch AND  transdates= $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null ","format(sum(if(ttype='D',amount,'')),0)as amount");

   }

} else{
    $branch=auth()->user()->branchid;
  $this->report("where purchaseheaders.isActive=1 and bracid=$branch AND transdates= $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null ","format(sum(if(ttype='D',amount,'')),0)as amount");
        
}
  
   }
   public function indextotalincome(){
       return view('totalincomes/index');
   }
   public function totalincome(){
    
    $start=date("Y-m-d", strtotime($_GET['start']));
   $today=date("'Y/m/d'");
   $endFin=date("Y-m-d", strtotime($_GET['end']));
   if(auth()->user()->isAdmin==1){
   if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2'])&& !empty($_GET['branch'])){
      
      $date1=date("Y-m-d", strtotime($_GET['date1']));
      $date2=date("Y-m-d", strtotime($_GET['date2']));
      $branch=$_GET['branch'];
      $this->report("where purchaseheaders.isActive=1 AND bracid=$branch AND  transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null","format(sum(if(ttype='C',amount,'')),0)as amount");
  
   }else{
       $branch=$_GET['branch'];
    $this->report("where bracid=$branch AND purchaseheaders.isActive=1 AND transdates=$today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null ","format(sum(if(ttype='C',amount,'')),0)as amount");  
   }
}else{
    $branch=auth()->user()->branchid;
  $this->report("where purchaseheaders.isActive=1 and bracid=$branch  AND  transdates=$today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=6 AND ttype='C' AND credit is Null ","format(sum(if(ttype='C',amount,'')),0)as amount");
        
}
    
   }

   public function indextrialbalance(){
    return view('trialbalances/trial');
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
    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2']) && !empty($_GET['branch'])){
        $branch=$_GET['branch'];
       $date1=date("Y-m-d", strtotime($_GET['date1']));
       $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->viewtrial("where bracid=$branch AND  transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' and purchaseheaders.isActive=1");
    $this->Strialbalance();
    }if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && empty($_GET['date1']) && empty($_GET['date2']) && !empty($_GET['branch'])){
        $branch=$_GET['branch'];
        
   $this->viewtrial("where  bracid=$branch AND transdates BETWEEN '$start' AND $today AND transdates BETWEEN '$start' AND '$endFin' and purchaseheaders.isActive=1 ");
        $this->Strialbalance();
   
 }
}

 public function incomestatement(){
    $start=date("Y-m-d", strtotime($_GET['start']));
    $today=date("'Y/m/d'");
    $endFin=date("Y-m-d", strtotime($_GET['end']));
    if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['start']) && !empty($_GET['end']) && !empty($_GET['date1']) && !empty($_GET['date2']) && !empty($_GET['branch'])){
       
       $date1=date("Y-m-d", strtotime($_GET['date1']));
       $date2=date("Y-m-d", strtotime($_GET['date2']));
       $branch=$_GET['branch'];

      $this->income("where bracid=$branch AND  transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' ANd credit is Null  OR credit =''  ");
   
    }else{
        $branch=$_GET['branch']; 
       
   $this->income("where bracid=$branch AND  transdates=$today AND transdates BETWEEN '$start' AND '$endFin' AND credit is Null  OR credit ='' ");
         
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
    public function report($where,$total){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype $where");
        $results['total']=$krows[0]->count;
        //$rst =  DB::getPdo()->prepare("select purchaseheaders.id ,transdates,accountcode,narration,if(ttype='D',amount,'')as debits,if(ttype='C',amount,'')as credits,amount from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id $where limit $offset,$rows");
        //$rst =  DB::getPdo()->prepare("select code,name, purchaseheaders.id ,DATE_FORMAT(transdates,'%d-%m-%Y') as transdates,chartofaccounts.accountname,accounttrans.accountcode,narration,format(if(ttype='D',amount,''),0)as debits,format(if(ttype='C',amount,''),0)as credits,format(amount,0) as amount from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype inner join editexpenses on accounttrans.purchaseheaderid=editexpenses.id $where limit $offset,$rows");
        $rst =  DB::getPdo()->prepare("select code,name, purchaseheaders.id ,DATE_FORMAT(transdates,'%d-%m-%Y') as transdates,chartofaccounts.accountname,B.accountcode,narration,format(if(ttype='D',amount,''),0)as debits,format(if(ttype='C',amount,''),0)as credits,format(amount,0) as amount,(SELECT format(SUM(A.total),2) FROM accounttrans AS A where  A.id <= B.id ORDER BY B.id  asc )as runningbal from accounttrans as B INNER join purchaseheaders on B.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=B.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype inner join editexpenses on B.purchaseheaderid=editexpenses.id $where limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
         // Showing Footer
   $footer =  DB::getPdo()->prepare("select $total from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype $where limit $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
 }

 public function viewtrial($where){
     DB::statement('drop view trials');
     DB::statement("create view trials as select chartofaccounts.accountname,chartofaccounts.accountcode,Sum(If(ttype='D',amount,-amount))as Debit from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid $where group by accounttrans.accountcode,chartofaccounts.accountname");
 }

 public function Strialbalance(){
    $count="select COUNT(*) as count,accountname,accountcode from trials ";
    $sql="select accountname,accountcode,format(if(Debit<0,0,Debit),0) as Debits ,format(if(Debit>0,0,abs(Debit)),0) as Credits from trials  ";
    $footer="select format(sum(if(Debit<0,0,Debit)),0) as Debits ,format(sum(if(Debit>0,0,abs(Debit))),0) as Credits from trials  ";
    $this->trialandsheet($sql,$count,$footer);  
 }
public function balShit($where){
    $footer="select * from incomestatement";// Not so usefull but the bal sheet cannot work without it
DB::statement("drop view Bsheet");
  DB::statement("create view Bsheet as select ttype,chartofaccounts.accountname,chartofaccounts.accounttype,chartofaccounts.accountcode,accounttypes.accounttype as type,If(ttype='D',amount,-amount)as Debit,transdates from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on chartofaccounts.accounttype=accounttypes.id inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid where transdates $where  and  chartofaccounts.accounttype=1 OR chartofaccounts.accounttype=2 OR chartofaccounts.accounttype=3 OR chartofaccounts.accounttype=4 OR chartofaccounts.accounttype=5");
  $sql="select accountname,accounttype,accountcode,accounttype as type,abs(Sum(If(ttype='K',Debit,-Debit)))as Debit from Bsheet  where accounttype=1 OR accounttype=2 OR accounttype=3 OR accounttype=4 OR accounttype=5 group by accountcode,accountname,accounttype,type";
        $count="select COUNT(*) as count,accountname,accounttype,accountcode,accounttype as type,abs(Sum(If(ttype='K',Debit,-Debit)))as Debit from Bsheet  where accounttype=1 OR accounttype=2 OR accounttype=3 OR accounttype=4 OR accounttype=5 group by accountcode,accountname,accounttype,type";
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
    DB::statement("drop view incstat");
    DB::statement("create view incstat as select ttype,bracid,amount,credit,chartofaccounts.accountname,chartofaccounts.accounttype,chartofaccounts.accountcode,accounttypes.accounttype as type,transdates from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on chartofaccounts.accounttype=accounttypes.id inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid  where chartofaccounts.accounttype=6  OR chartofaccounts.accounttype=7 ");
    DB::statement("drop view totalincome");
    DB::statement("create view totalincome as select * from incstat $where");
    $sql="select accountname,accounttype,accountcode,type,abs(Sum(If(ttype='D',amount,-amount)))as Debit from totalincome  group by accountcode,accountname,accounttype order by accounttype";
    $count="select COUNT(*) as count, accountname,accounttype,accountcode,type,abs(Sum(If(ttype='D',amount,-amount)))as Debit from totalincome  group by accountcode,accountname,accounttype order by accounttype DESC";
    $footer="select format(sum(if(accounttype=6,amount,0))-sum(if(accounttype=7,amount,0)),0) as accountname from totalincome";
    $this->trialandsheet($sql,$count,$footer);
}
public function viewexp($id){
    return DB::select("select accounttrans.purchaseheaderid as Aid,accounttrans.id as accounttransid,accounttrans.accountcode,accountname,narration,amount from accounttrans inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode where purchaseheaderid=$id and ttype='D' and accounttype=7 ");
    }
 }