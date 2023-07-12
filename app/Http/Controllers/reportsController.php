<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\stocktrans;

 class reportsController extends Controller{
    
public function stockreport(){

    return view('stockreports/index1');


}
public function viewdailystock(){
    if(isset($_GET['product']) && isset($_GET['type']) && isset($_GET['page'])&& isset($_GET['rows'])&& empty($_GET['date2']) && empty($_GET['date1']) ){
        $product=$_GET['product'];
        $type=$_GET['type'];
$this->viewstockreport("where memid=$product  ");

}else if(isset($_GET['page'])&& isset($_GET['type']) && isset($_GET['rows']) && isset($_GET['date1']) && isset($_GET['date2'])&& isset($_GET['product'])){
    $product=$_GET['product']; 
    $type=$_GET['type']; 
    $date1=date("Y-m-d", strtotime($_GET['date1']));
    $date2=date("Y-m-d", strtotime($_GET['date2']));
    //$this->report("where transdates BETWEEN '$date1' AND '$date2' AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND ttype='D' AND credit is Null","sum(if(ttype='D',amount,''))as amount");
    $this->viewstockreport("where memid=$product AND date BETWEEN '$date1' AND '$date2'  ");
 }
}


public function viewstockreport($where){


   $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from loantrans inner join customers on loantrans.memid=customers.id and loantrans.isActive=1  $where order by loanid");
    $results['total']=$krows[0]->count;
    //$rst =  DB::getPdo()->prepare("select DATE_FORMAT(date,'%d-%m-%Y') as date,if(expecteddate='0000-00-00','',DATE_FORMAT(expecteddate,'%d-%m-%Y')) as expecteddate,format(loan,0)as loan,format(interestcredit,0) as interestcredit,narration, name, format(loancredit,0) as loancredit from loantrans inner join customers on loantrans.memid=customers.id and loantrans.isActive=1  $where order by date  limit  $offset,$rows");
    $rst =  DB::getPdo()->prepare("select loanid, DATE_FORMAT(date,'%d-%m-%Y') as date,if(expecteddate='0000-00-00','',DATE_FORMAT(expecteddate,'%d-%m-%Y')) as expecteddate,format(loan,0)as loan,format(interestcredit,0) as interestcredit,narration,format(abs(loan+interestcredit),0) as total, name, format(loancredit,0) as loancredit,(SELECT format(SUM(A.loancredit),2) FROM loantrans AS A $where AND  A.id <= B.id ORDER BY B.id  asc )as runningbal  from loantrans As B inner join customers on B.memid=customers.id $where order by B.id asc  limit  $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select format(sum(loancredit),0) as loancredit, format(sum(loan),0) as loan ,format(sum(interestcredit),0) as interestcredit  from loantrans inner join customers on loantrans.memid=customers.id and loantrans.isActive=1  $where order by loanid   limit  $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
}


 }

 