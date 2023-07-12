<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use App\memberinfos;
 use App\loantrans;
 use App\loaninfo;
 use Illuminate\Support\Facades\DB;
 class TrailsController extends Controller{


public function view(){
   
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2'])){
       
        $today=date("'Y-m-d'");//, strtotime($_GET['date1']));
       // $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $this->balShit(" BETWEEN '$date1' AND '$date2' ");
        $this->loandisbursement(" and date=$today");
       
     
     }
     if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->loandisbursement("and date  BETWEEN '$date1' AND '$date2' ");
     
     }

   

}
public function save(Request $request){
    $memberid=$request['name'];
    $id=DB::select("select sum(loan) as loan from loantrans where memid=$memberid");
    foreach($id as $finalid){
        if($finalid->loan!=0){
            return ['results'=>'true'];
        }else{
           //loan info
$objloaninfo= new loaninfo();
$objloaninfo->loanrepay=$request['repay'];
$objloaninfo->period=$request['repay'];
$objloaninfo->loaninterest=$request['interest'];
$objloaninfo->mode=$request['branch'];
$objloaninfo->collateral=$request['security'];
$objloaninfo->guanter=$request['gauranter'];
$objloaninfo->memeberid=$request['name'];
$objloaninfo->save();
 //Saving into loantrans
$objloantrans= new loantrans();
$objloantrans->loancredit=$request['amount'];
$objloantrans->loan=$request['amount'];
$objloantrans->isLoan=1;
$objloantrans->isDisbursement=1;
$objloantrans->narration="Loan Disbursement";
$objloantrans->date=date("Y-m-d", strtotime($request['date']));
$objloantrans->loanid=$objloaninfo->id;
$objloantrans->memid=$request['name'];
$objloantrans->paydet=$request['paydet'];
$objloantrans->isActive=1;

$objloantrans->save();
//Saving Interest 
$objloantrans1= new loantrans();
$objloantrans1->narration="Loan Interest Charged";
$interest=($request['interest']/100)*$request['amount'];
$objloantrans1->loancredit=$interest;
$objloantrans1->interestcredit=$interest;
$objloantrans1->date=date("Y-m-d", strtotime($request['date']));
$objloantrans1->loanid=$objloaninfo->id;
$objloantrans1->isLoan=0;
$objloantrans1->memid=$request['name'];
$objloantrans1->paydet=$request['paydet'];
$objloantrans1->isActive=1;
$objloantrans1->save();

}
    }
}
 
//Auto generated code for updating
public function update(Request $request,$id2){
   // $memberid=$request['name'];
   DB::delete("update  loantrans set isActive=0,status='Edited' where loanid=$id2");
  // DB::delete("delete from loaninfos  where id=$id2");
    $id=DB::select("select sum(loan) as loan,memid from loantrans where loanid=$id2");
    foreach($id as $finalid){
        if($finalid->loan!=0 && $finalid->memid!=$request['memid']){
            return ['results'=>'true'];
        }else{
           //loan info
$objloaninfo= new loaninfo();
$objloaninfo->loanrepay=$request['repay'];
$objloaninfo->period=$request['repay'];
$objloaninfo->loaninterest=$request['interest'];
$objloaninfo->mode=$request['branch'];
$objloaninfo->collateral=$request['security'];
$objloaninfo->guanter=$request['gauranter'];
$objloaninfo->memeberid=$request['memid'];
$objloaninfo->save();
 //Saving into loantrans
$objloantrans= new loantrans();
$objloantrans->loancredit=$request['amount'];
$objloantrans->loan=$request['amount'];
$objloantrans->isLoan=1;
$objloantrans->isDisbursement=1;
$objloantrans->narration="Loan Disbursement";
$objloantrans->date=date("Y-m-d", strtotime($request['date']));
$objloantrans->loanid=$objloaninfo->id;
$objloantrans->memid=$request['memid'];
$objloantrans->paydet=$request['paydet'];
$objloantrans->isActive=1;


$objloantrans->save();
//Saving Interest 
$objloantrans1= new loantrans();
$objloantrans1->narration="Loan Interest Charged";
$interest=($request['interest']/100)*$request['amount'];
$objloantrans1->loancredit=$interest;
$objloantrans1->interestcredit=$interest;
$objloantrans1->date=date("Y-m-d", strtotime($request['date']));
$objloantrans1->loanid=$objloaninfo->id;
$objloantrans1->isLoan=0;
$objloantrans1->memid=$request['memid'];
$objloantrans1->paydet=$request['paydet'];
$objloantrans1->isActive=1;
$objloantrans1->save();

}
    }
    }

    
 


 public function destroy($id){
        $Objsuppliers=suppliers::find($id);
        $Objsuppliers->delete();



    }
    public function viewcombo(){

        return suppliers::all();

    }

    public function loandisbursement($where){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where isLoan=1 and isDisbursement=1 and loantrans.isActive=0 $where");
        $results["total"]=$krows[0]->count;
        $sth =  DB::getPdo()->prepare("select user,status,narration,loanid,date,memid,paydet,name,loaninterest,loanrepay,collateral,abs(loancredit) as loancredit,guanter from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where  loantrans.isActive=0 $where limit $offset,$rows");
        $sth->execute();
    
        $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
        $results["rows"]=$dogs;
       echo json_encode($results);
    }
}
