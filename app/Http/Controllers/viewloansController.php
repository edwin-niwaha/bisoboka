<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;


 class viewloansController extends Controller{

    public function viewloans(){

           
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2'])){
       
        $today=date("'Y-m-d'");//, strtotime($_GET['date1']));
       // $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $this->balShit(" BETWEEN '$date1' AND '$date2' ");
        $this->loans(" and date BETWEEN '2018-10-10'  AND $today");
       
     
     }
    
     if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->loans("and date  BETWEEN '$date1' AND '$date2' ");
     
     }

    }
   public function loans($where){

    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $bra=auth()->user()->branchid;
    $admin=auth()->user()->isAdmin;
   // if($admin==0){
    $krows = DB::select("select COUNT(*) as count  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where isLoan=1 and isDisbursement=1 and loantrans.isActive=1 and loantrans.branchno=$bra $where");
    $results["total"]=$krows[0]->count;
    
    $sth =  DB::getPdo()->prepare("select loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,paydet,customers.name,concat(loaninterest,' ','%' )as loaninterest,concat(loanrepay,' ','month (s)') as loanrepay,collateral,FORMAT(loancredit,0) as loancredit,guanter,headerid from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where loantrans.branchno=$bra and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
    $sth->execute();
       $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
    $results["rows"]=$dogs;
  
                 //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(loancredit),0) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id   where   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 and branchno=$bra $where limit $offset,$rows");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);

   }


}