<?php 
 namespace App\Http\Controllers;
 date_default_timezone_set("Africa/Nairobi");
use Illuminate\Http\Request;
 use App\memberinfos;
 use App\loantrans;
 use App\loaninfo;
 use App\purchaseheaders;
 use App\accounttrans;
 use Illuminate\Support\Facades\DB;
 class oldloansController extends Controller{

public function index(){
    

    return view('oldloans/index1');
}
public function view(){
   
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) && empty($_GET['branch'])){
       
        $today=date("'Y-m-d'");//, strtotime($_GET['date1']));
       // $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $this->balShit(" BETWEEN '$date1' AND '$date2' ");
        $this->loandisbursement(" and date=$today");
       
     
     }
    
     if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2']) && empty($_GET['branch'])){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
       $this->authloans("and date  BETWEEN '$date1' AND '$date2' ");
     
     }
   
     if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) && !empty($_GET['branch'])){
        $branch=$_GET['branch'];
        $today=date("'Y-m-d'");
        $this->authloans(" and users.branchid=$branch and date=$today");;

     }
     if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['date1']) && !empty($_GET['date2']) && !empty($_GET['branch'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $branch=$_GET['branch'];
        $this->authloans("and date  BETWEEN '$date1' AND '$date2' AND  users.branchid=$branch ");;

     }
    

    
  

   

}
public function save(Request $request){
    $memberid=$request['name'];
    $id=DB::select("select customers.name as client,sum(loan) as loan from loantrans inner join customers on loantrans.memid=customers.id where loantrans.isActive=1 and memid=$memberid");
    foreach($id as $finalid){
        if($finalid->loan!=0 ){
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
$interest=($request['interest']/100)*$request['amount'];
$objloantrans->loancredit=$request['amount'];
$objloantrans->loan=$request['amount'];
$objloantrans->isLoan=1;
$objloantrans->isDisbursement=1;
$objloantrans->user=auth()->user()->name;
//$objloantrans->interestcredit=$interest;

$objloantrans->narration="Loan Balance ";
$objloantrans->date=date("Y-m-d", strtotime($request['date']));
$objloantrans->loanid=$objloaninfo->id;
$objloantrans->memid=$request['name'];
$objloantrans->paydet=$request['paydet'];
$objloantrans->isActive=1;
$h=$request['branch']*$request['repay']*24*21+strtotime($request['date'])-24*21-24*21;
$objloantrans->expecteddate=date("Y-m-d",$h);
$objloantrans->newdate=date("Y-m-d",$h);

$objloantrans->save();

//Saving Interest 
 //Saving into loantrans
 $objloantrans1= new loantrans();
 //$interest=($request['interest']/100)*$request['amount'];
 $objloantrans1->loancredit=$request['interestbal'];
 $objloantrans1->interestcredit=$request['interestbal'];
 $objloantrans1->isLoan=0;
 $objloantrans1->user=auth()->user()->name;
 $objloantrans1->isDisbursement=1;
 $objloantrans1->interestcredit=$request['interestbal'];
 $objloantrans1->narration="Loan Interest Balance";
 $objloantrans1->date=date("Y-m-d", strtotime($request['date']));
 $objloantrans1->loanid=$objloaninfo->id;
 $objloantrans1->memid=$request['name'];
 $objloantrans1->paydet=$request['paydet'];
 $objloantrans1->isActive=1;
 //get expected date
 $h=$request['branch']*$request['repay']*24*21+strtotime($request['date'])-24*21-24*21;
$objloantrans1->expecteddate=date("Y-m-d",$h);
$objloantrans1->newdate=date("Y-m-d",$h);
$newdat=date("Y-m-d",$h);
//updating new date 
DB::statement("update loantrans set newdate='$newdat' where memid=$memberid");
 $objloantrans1->save();

//inserting into purchase headers and transloans
$objpurchaseheaders= new purchaseheaders();
$objpurchaseheaders->id=$objloaninfo->id;
$objpurchaseheaders->isActive=1;
$objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['date']));
$objpurchaseheaders->save();
// inserting into accountrans 
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objloaninfo->id;
$objaccountrans->amount=$request['amount'];
$objaccountrans->accountcode="3500";
$objaccountrans->narration="Loan Balance to ".$finalid->client;
$objaccountrans->ttype="D";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

//inserting into purchase headers and transloans

// inserting into accountrans 
$objaccountrans=new accounttrans;
$objaccountrans->purchaseheaderid=$objloaninfo->id;
$objaccountrans->amount=$request['amount'];
$objaccountrans->accountcode="21";
$objaccountrans->narration="Loan Bal to ".$finalid->client;
$objaccountrans->ttype="C";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

// Loan interest Recivables
$objaccountrans=new accounttrans;
//$interest=($request['interest']/100)*$request['amount'];
$objaccountrans->purchaseheaderid=$objloaninfo->id;
$objaccountrans->amount=$request['interestbal'];;
$objaccountrans->accountcode="5000";
$objaccountrans->narration="Loan Interest Recievable to ".$finalid->client;
$objaccountrans->ttype="D";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();

// Loan interest Income
$objaccountrans=new accounttrans;
//$interest=($request['interest']/100)*$request['amount'];
$objaccountrans->purchaseheaderid=$objloaninfo->id;
$objaccountrans->amount=$request['interestbal'];;
$objaccountrans->accountcode="4000";
$objaccountrans->narration="Loan Interest income to ".$finalid->client;
$objaccountrans->ttype="C";
$objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccountrans->bracid=auth()->user()->branchid;
$objaccountrans->save();




}
    }
}
 
//Auto generated code for updating
public function update(Request $request,$id2){
    $memberid=$request['name'];
  // DB::select("update  loantrans set isActive=0,status='Edited' where loanid=$id2");
  // DB::select("update  purchaseheaders  set isActive=0  where id=$id2");
  // DB::delete("delete from loaninfos  where id=$id2");
    $id=DB::select("select customers.name as client,sum(loan) as loan,branchnumber from loantrans inner join customers on loantrans.memid=customers.id where loantrans.isActive=1 and loanid=$id2");
    foreach($id as $finalid){
     
           //loan info
           $objloaninfo=  loaninfo::find($id2);
$objloaninfo->loanrepay=$request['repay'];
$objloaninfo->period=$request['repay'];
$objloaninfo->loaninterest=$request['interest'];
$objloaninfo->mode=$request['branch'];
$objloaninfo->collateral=$request['security'];
$objloaninfo->guanter=$request['gauranter'];
$objloaninfo->memeberid=$request['memid'];
$objloaninfo->save();

 //Saving into loantrans
 //$interest=($request['interest']/100)*$request['amount'];
 $h=$request['branch']*$request['repay']*24*21+strtotime($request['date'])-24*21-24*21;
$objloantrans=  loantrans::where('loanid','=',$id2)->where('narration','=','Loan Balance ')->update(

    ['loancredit'=>$request['amount'],
    'loan'=>$request['amount'],
    'isLoan'=>1,
    'isDisbursement'=>1,
    'loanid'=>$objloaninfo->id,
    'memid'=>$request['memid'],
    'paydet'=>$request['paydet'],
    'isActive'=>1,
    'interestcredit'=>0,
    


    ]);

    //Saving Interest 
    $objloantrans1=  loantrans::where('loanid','=',$id2)->where('narration','=','Loan Interest Balance')->update(

        ['loancredit'=>$request['interestbal'],
        'loan'=>0,
        'isLoan'=>0,
        'isDisbursement'=>1,
        'loanid'=>$objloaninfo->id,
        'memid'=>$request['memid'],
        'paydet'=>$request['paydet'],
        'isActive'=>1,
        'interestcredit'=>$request['interestbal'],
       
    
    
        ]);

        //inserting into purchase headers and transloans
        $purchaseheaders=  purchaseheaders::where('id','=',$id2)->update(

            ['id'=>$id2,
            'isActive'=>1,
            ]);

//inserting into purchase headers and transloans
$accounttrans=  accounttrans::where('purchaseheaderid','=',$id2)->where('ttype','=','D')->where('accountcode',
'=','3500')->where('bracid','=',$finalid->branchnumber)->update(

    ['amount'=>$request['amount'],
    
    
    ]);

    // Cash Account
    accounttrans::where('purchaseheaderid','=',$id2)->where('ttype','=','C')->where('accountcode',
    '=','21')->where('bracid','=',$finalid->branchnumber)->update(
    
        ['amount'=>$request['amount'],
        
        
        ]);
       // Loan interest Recivables

       
accounttrans::where('purchaseheaderid','=',$id2)->where('ttype','=','D')->where('accountcode',
'=','5000')->where('bracid','=',$finalid->branchnumber)->update(

    ['amount'=>$request['interestbal'],
    
    
    ]);

    // Loan interest Income
    accounttrans::where('purchaseheaderid','=',$id2)->where('ttype','=','C')->where('accountcode',
'=','4000')->where('bracid','=',$finalid->branchnumber)->update(

    ['amount'=>$request['interestbal'],
    
    
    ]);


    }
    }

    
 


 public function destroy($id){
$user=auth()->user()->name;
    DB::update("update  loantrans set isActive=0,status='Deleted',user='$user' where loanid=$id");  
    DB::update("update  purchaseheaders set isActive=0  where id=$id"); 



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
        $bra=auth()->user()->branchid;
        $admin=auth()->user()->isAdmin;
       // if($admin==0){
        $krows = DB::select("select COUNT(*) as count  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id where isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where");
        $results["total"]=$krows[0]->count;
        
        $sth =  DB::getPdo()->prepare("select loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,paydet,customers.name,loaninterest,loanrepay,collateral,loancredit,guanter from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where users.branchid=$bra and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
        $sth->execute();
           $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
        $results["rows"]=$dogs;
      
                     //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select sum(loancredit) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where users.branchid=$bra and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
    }
    public function authloans($where){
        $admin=auth()->user()->isAdmin;
        if($admin==1){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $bra=auth()->user()->branchid;
        $admin=auth()->user()->isAdmin;
       // if($admin==0){
        $krows = DB::select("select COUNT(*) as count from  customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where isLoan=1 and isDisbursement=1 and loantrans.isActive=1  $where");
        $results["total"]=$krows[0]->count;
        
        $sth =  DB::getPdo()->prepare("select loanid,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,paydet,customers.name,loaninterest,loanrepay,collateral,loancredit,guanter from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where   isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
        $sth->execute();
           $dogs = $sth->fetchAll(\PDO::FETCH_OBJ);
        $results["rows"]=$dogs;
              //Showing The footer and totals 
              $footer =  DB::getPdo()->prepare("select sum(loancredit) as loancredit  from customers inner join loaninfos on loaninfos.memeberid=customers.id inner join loantrans on loantrans.loanid=loaninfos.id inner join users on users.branchid=customers.branchnumber  where users.branchid=$bra and  isLoan=1 and isDisbursement=1 and loantrans.isActive=1 $where limit $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);







        }

    }
}
