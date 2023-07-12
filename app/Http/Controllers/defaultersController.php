<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\stocktrans;
use App\defaulters;

 class defaultersController extends Controller{
    
public function index(){

    return view('defaulters/default');


}
public function defaulters(){


    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $branch=auth()->user()->branchid;
    $admin=auth()->user()->isAdmin;
 
    $krows = DB::select("select  COUNT(*) as count from (select name as name  from  customers inner join loantrans on loantrans.memid=customers.id where branchnumber=$branch group by loanid having sum(loan)+sum(interestcredit)>0) as f");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select name,format(sum(loan),0) as loan,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id where branchnumber=$branch group by loanid having sum(loan)+sum(interestcredit)>0 limit $offset,$rows");
    
    $rst->execute();

   $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
    //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(loan),0) as loan,format(sum(interestcredit),0) as interest, format(sum(loan)+sum(interestcredit),0) as total from customers inner join loantrans on loantrans.memid=customers.id where branchnumber=$branch having sum(loan)+sum(interestcredit)>0");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);

    
}

 }

 