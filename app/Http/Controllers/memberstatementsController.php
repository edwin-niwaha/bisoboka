<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\branches;

 class memberstatementsController extends Controller{

public function memberstatement($product){

    $pdf = \App::make('dompdf.wrapper');
    $name=DB::select("select * from customers where id=$product");
    $results=DB::select("select DATE_FORMAT(date,'%d-%m-%Y') as date,if(expecteddate='0000-00-00','',DATE_FORMAT(expecteddate,'%d-%m-%Y')) as expecteddate,format(loan,0) as loan,format(interestcredit,0) as interestcredit,narration, name, format(loancredit,0) as loancredit from loantrans inner join customers on loantrans.memid=customers.id and loantrans.isActive=1  where memid=$product order by loantrans.id,date"); 

    $pdf->loadHTML(view('memberstatements/index')->with('results',$results)->with('name',$name));
    return $pdf->stream();

  

}
}