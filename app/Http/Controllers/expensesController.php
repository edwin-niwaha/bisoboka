<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\stocktrans;
use App\accounttrans;
use App\stockbals;
use App\purchaseheaders;

 class expensesController extends Controller{

    public function totalexpensesindex(){
        return view('totalexpenses/expenses');
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
         $this->report("where  purchaseheaders.isActive=1 AND  bracid=$branch AND  transdates= $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND B.accountcode!=1001 AND ttype='D' AND credit is Null ","format(sum(if(ttype='D',amount,'')),0)as amount");
     
        }
     
     } else{
         $branch=auth()->user()->branchid;
       $this->report("where purchaseheaders.isActive=1 and bracid=$branch AND transdates= $today AND transdates BETWEEN '$start' AND '$endFin' AND accounttypes.id=7 AND accounttrans.accountcode!=1001 AND ttype='D' AND credit is Null ","format(sum(if(ttype='D',amount,'')),0)as amount");
             
     }
       
        }
    
        public function report($where,$total){
            $results=array();
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
            $offset = ($page-1)*$rows;
            $rs =DB::getPdo();
            $krows = DB::select("select COUNT(*) as count from accounttrans INNER join purchaseheaders on accounttrans.purchaseheaderid=purchaseheaders.id inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype ");
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


}