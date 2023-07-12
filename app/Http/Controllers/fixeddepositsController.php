<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\fixeddeposits;
use App\allsavings;
use App\purchaseheaders;
use App\accounttrans;
use App\customers;
use App\fixeddepositconfigs;
 class fixeddepositsController extends Controller{

public function index(){
    return view('fixeddeposits/index');
}
public function view(){
    if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])){
        $today=date("Y-m-d");
        $this->depositview(" and fixdate='$today'");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->depositview("and fixdate<='$date1'");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $this->depositview("and fixdate BETWEEN '$date1' AND '$date2' ");
     }

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
   
if($request['mode']==1){// calculating simple fixed interest
    $objheaderpurchase= new purchaseheaders();
$objheaderpurchase->transdates=date("Y-m-d", strtotime($request['fixdate']));
$objheaderpurchase->branch_id=$bra;
$objheaderpurchase->save();
$Objfixeddeposits=new fixeddeposits();
$month=$request['fixperiod'];
$Objfixeddeposits->client_id=$request['client_id'];
$Objfixeddeposits->fixdate=date("Y-m-d", strtotime($request['fixdate']));
$Objfixeddeposits->fixamount=str_replace( ',', '',$request['fixamount']);
$Objfixeddeposits->fixinterest=$request['fixinterest'];
$Objfixeddeposits->fixperiod=$month;
$Objfixeddeposits->term=$request['term'];
$Objfixeddeposits->branchno=$bra;
$Objfixeddeposits->headerid2=$objheaderpurchase->id;
$Objfixeddeposits->maturitydate=date('Y-m-d',strtotime($request['fixdate'] ."+$month months"));;
//FD=Principal * interestrate* Time /(100*12);
$Objfixeddeposits->maturityinterest=str_replace( ',', '',$request['fixamount'])*$request['fixinterest']*$request['fixperiod']/(100*$request['fixperiod']);//
$Objfixeddeposits->type=$request['mode'];
$Objfixeddeposits->save();

############################ POSTING IN ACCOUNTS #########


/// Accounts table Fixed Deposit
$objaccounttrans= new accounttrans();
$objaccounttrans->accountcode=401;
$objaccounttrans->ttype="C";
$objaccounttrans->amount=str_replace( ',', '',$request['fixamount']);
$objaccounttrans->total=str_replace( ',', '',$request['fixamount']);
$objaccounttrans->purchaseheaderid=$objheaderpurchase->id;
$objaccounttrans->bracid=$bra;
$objaccounttrans->transdate=date("Y-m-d", strtotime($request['fixdate']));
$clientname=customers::where('id','=',$request['client_id'])->get();
foreach($clientname as $nam){
    $objaccounttrans->narration=$nam->name." - Fixed Deposit";
}
$objaccounttrans->save();
// Checking Accounting;
$fixed=DB::select("select * from fixeddepositconfigs where branchno=$bra and id='$request[term]'");
foreach($fixed as $fd){
    $objaccounttrans= new accounttrans();
    $objaccounttrans->accountcode=$fd->checkingac;
    $objaccounttrans->ttype="D";
    $objaccounttrans->amount=str_replace( ',', '',$request['fixamount']);
    $objaccounttrans->total=str_replace( ',', '',$request['fixamount']);
    $objaccounttrans->purchaseheaderid=$objheaderpurchase->id;
    $objaccounttrans->bracid=$bra;
    $objaccounttrans->transdate=date("Y-m-d", strtotime($request['fixdate']));
    $clientname=customers::where('id','=',$request['client_id'])->get();
    foreach($clientname as $nam){
        $objaccounttrans->narration=$nam->name." - Fixed Deposit";
    }
    $objaccounttrans->save(); 
}

// Posting into all savings table 
$Objallsavings=new allsavings();
$Objallsavings->client_no=$request['client_id'];
$Objallsavings->narration="Fixed Deposit";
$Objallsavings->date=date("Y-m-d", strtotime($request['fixdate']));
$Objallsavings->recieptno=$Objfixeddeposits->id;
$Objallsavings->branchno=$bra;
$Objallsavings->headerid=$objheaderpurchase->id;
$Objallsavings->savingpdt5=str_replace( ',', '',$request['fixamount']);
//$Objallsavings->intpdt5=str_replace( ',', '',$request['fixamount'])*$request['fixinterest']*$request['fixperiod']/(100*12);//
$Objallsavings->fixdepositid=$Objfixeddeposits->id;
$Objallsavings->save();
}else if($request['mode']==2){


}
    }catch(\Exception $e){
        DB::rollBack();
        echo "Failed ".$e;
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id,$headerid2){
    DB::beginTransaction();
    $bra=auth()->user()->branchid;
    try{
        $Objfixeddeposits=fixeddeposits::find($id);
        if($request['mode']==1){// calculating simple fixed interest
            $month=$request['fixperiod'];
            $Objfixeddeposits->client_id=$request['client_id'];
            $Objfixeddeposits->fixdate=date("Y-m-d", strtotime($request['fixdate']));
            $Objfixeddeposits->fixamount=str_replace( ',', '',$request['fixamount']);
            $Objfixeddeposits->fixinterest=$request['fixinterest'];
            $Objfixeddeposits->fixperiod=$month;
            $Objfixeddeposits->branchno=$bra;
            $Objfixeddeposits->term=$request['term'];
            $Objfixeddeposits->maturitydate=date('Y-m-d',strtotime($request['fixdate'] ."+$month months"));;
            //FD=Principal * interestrate* Time /(100*12);
            $Objfixeddeposits->maturityinterest=str_replace( ',', '',$request['fixamount'])*$request['fixinterest']*$request['fixperiod']/(100*$request['fixperiod']);//
            $Objfixeddeposits->type=$request['mode'];
            $Objfixeddeposits->save();
            // update  savings table 
            allsavings::where('fixdepositid','=',$id)->update([
                'client_no'=>$request['client_id'],
                'narration'=>'Fixed Deposit',
                'date'=>date('Y-m-d', strtotime($request['fixdate'])),
                'savingpdt5'=>str_replace( ',', '',$request['fixamount']),
            ]);
            /// Accounts table Fixed Deposit
            $clientname=customers::where('id','=',$request['client_id'])->get();
            foreach($clientname as $nam){
            accounttrans::where('purchaseheaderid','=',$headerid2)->where('accountcode','=',401)->update([
                'amount'=>str_replace( ',', '',$request['fixamount']),
                'total'=>str_replace( ',', '',$request['fixamount']),
                'bracid'=>$bra,
                'transdate'=>date("Y-m-d", strtotime($request['fixdate'])),
                'narration'=>$nam->name.'-Fixed Deposit',

            ]);
            }
            $purchase=purchaseheaders::find($headerid2);
            $purchase->transdates=date("Y-m-d", strtotime($request['fixdate']));
            $purchase->save();
            // Checking Accounting;
            
$fixed=DB::select("select * from fixeddepositconfigs where branchno=$bra and id='$request[term]'");
foreach($fixed as $fd){
    $clientname=customers::where('id','=',$request['client_id'])->get();
    foreach($clientname as $nam){
    accounttrans::where('purchaseheaderid','=',$headerid2)->where('accountcode','=',$fd->checkingac)->update([
        'amount'=>str_replace( ',', '',$request['fixamount']),
        'total'=>str_replace( ',', '',$request['fixamount']),
        'bracid'=>$bra,
        'transdate'=>date("Y-m-d", strtotime($request['fixdate'])),
        'narration'=>$nam->name.'-Fixed Deposit',

    ]);
    }
 
}


            }else if($request['mode']==2){
            
            
            }
        }catch(\Exception $e){
            DB::rollBack();
            echo "Failed ".$e;
        }
        DB::commit();
}
 public function destroy($id){
     DB::beginTransaction();
     try{ 

        $Objfixeddeposits=fixeddeposits::find($id);
        $Objfixeddeposits->delete();
        $all=allsavings::where('fixdepositid','=',$id)->get();
        foreach($all as $a){
            allsavings::where('fixdepositid','=',$a->fixdepositid)->delete();
            accounttrans::where('purchaseheaderid','=',$a->headerid)->delete();
        }

     }catch(\Exception $e){
         echo "Failed ".$e;
         DB::rollBack();

     }
     DB::commit();




    }

public function viewcombo(){


    return fixeddeposits::all();
}
public function fixeddepositconfigs(){
    return view('fixeddepositconfigs/index');
}
public function computefixedDeposit(){
    DB::beginTransaction();
    try{
    $bra=auth()->user()->branchid;
    $FD=DB::select("select fixeddeposits.id as id,name, client_id,fixdate,fixamount,fixinterest,fixperiod,maturitydate,maturityinterest from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where customers.isActive=1 and isComplete=0 and isPay=0");
    foreach($FD as $deposits){
        $today=strtotime("now");
        $objpurchaseheaders= new purchaseheaders();
        $objpurchaseheaders->transdates=date("Y-m-d",strtotime($deposits->maturitydate));
        $objpurchaseheaders->branch_id=$bra;
        $objpurchaseheaders->save();
        if($today>=strtotime($deposits->maturitydate)){

            // Account trans 
            // Savings Interest Expense
            $account= new accounttrans();
            $account->accountcode="701";
            $account->narration=$deposits->name." Savings Interest Expense ";
            $account->amount=$deposits->maturityinterest;
            $account->total=$deposits->maturityinterest;
            $account->ttype="D";
            $account->purchaseheaderid=$objpurchaseheaders->id;
            $account->transdate=date("Y-m-d",strtotime($deposits->maturitydate));
            $account->bracid=$bra;
            $account->save();
                        // Savings Interest Payable
                        $account= new accounttrans();
                        $account->accountcode="404";
                        $account->narration=$deposits->name." Savings Interest Payable";
                        $account->amount=$deposits->maturityinterest;
                        $account->total=$deposits->maturityinterest;
                        $account->ttype="C";
                        $account->purchaseheaderid=$objpurchaseheaders->id;
                        $account->transdate=date("Y-m-d",strtotime($deposits->maturitydate));
                        $account->bracid=$bra;
                        $account->save();
$Objallsavings= new allsavings();
$Objallsavings->client_no=$deposits->client_id;
$Objallsavings->narration="Fixed Deposit-Interest";
$Objallsavings->date=date("Y-m-d",strtotime($deposits->maturitydate));
$Objallsavings->recieptno=$objpurchaseheaders->id;
$Objallsavings->branchno=$bra;
$Objallsavings->headerid=$objpurchaseheaders->id;
//$Objallsavings->savingpdt5=str_replace( ',', '',$request['fixamount']);
$Objallsavings->intpdt5=$deposits->maturityinterest;
//$Objallsavings->fixdepositid=$Objfixeddeposits->id;
$Objallsavings->save();          
DB::update("update fixeddeposits set isComplete=1 where id=$deposits->id");
            
        }
    }

}catch(\Exception $e){
    echo "Failed ".$e;
    DB::rollBack();
} 
DB::commit(); 
}
public function fixedwithdraws(){
    return view('fixedwithdraws/index');
}
public function getcheckpay($id){
$chk=DB::select("select id from fixeddeposits where client_id=$id and isPay=1 order by id desc");
if(count($chk)>0){
return ['paycheck'=>'0','interest'=>'0','fixamount'=>'0'];
}else{

  return  DB::select("select fixeddeposits.id as fid,format(if(isComplete=1,fixamount+maturityinterest,fixamount),0) as paycheck, format(if(isComplete=1,maturityinterest,0),0) as interest,fixamount from fixeddeposits where client_id=$id");
}

}
public function paycheck(Request $request){
    $bra=auth()->user()->branchid;
    DB::beginTransaction();
    try{
        $objpuchase= new purchaseheaders();
        $objpuchase->transdates=date("Y-m-d", strtotime($request['fixdate']));
        $objpuchase->branch_id=$bra;
        $objpuchase->save();

        // Cash Account
        $fixed=DB::select("select checkingac,fixeddeposits.term from fixeddepositconfigs inner join fixeddeposits on fixeddeposits.term=fixeddepositconfigs.id where fixeddeposits.branchno=$bra  and fixeddeposits.id='$request[fixedid]'");
        foreach($fixed as $fd){
            $objaccounttrans= new accounttrans();
            $objaccounttrans->accountcode=$fd->checkingac;
            $objaccounttrans->ttype="C";
            $objaccounttrans->amount=str_replace( ',', '',$request['fixamount']);
            $objaccounttrans->total=str_replace( ',', '',$request['fixamount'])*-1;
            $objaccounttrans->purchaseheaderid=$objpuchase->id;
            $objaccounttrans->bracid=$bra;
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['fixdate']));
            $clientname=customers::where('id','=',$request['client_id'])->get();
            foreach($clientname as $nam){
                $objaccounttrans->narration=$nam->name." -Fixed Deposit WithDraw";
            }
            $objaccounttrans->save(); 
            ################## DEBITING FIXED DEPOSIT
            $objaccounttrans= new accounttrans();
            $objaccounttrans->accountcode=401;
            $objaccounttrans->ttype="D";
            $objaccounttrans->amount=str_replace( ',', '',$request['amount']);
            $objaccounttrans->total=str_replace( ',', '',$request['amount'])*-1;
            $objaccounttrans->purchaseheaderid=$objpuchase->id;
            $objaccounttrans->bracid=$bra;
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['fixdate']));
            $clientname=customers::where('id','=',$request['client_id'])->get();
            foreach($clientname as $nam){
                $objaccounttrans->narration=$nam->name." -Fixed Deposit WithDraw";
            }
            $objaccounttrans->save();

            ################### Reducing on Savings Interest Payble ###########
            if(str_replace( ',', '',$request['interest'])>0){
                $objaccounttrans= new accounttrans();
                $objaccounttrans->accountcode=404;
                $objaccounttrans->ttype="D";
                $objaccounttrans->amount=str_replace( ',', '',$request['interest']);
                $objaccounttrans->total=str_replace( ',', '',$request['interest'])*-1;
                $objaccounttrans->purchaseheaderid=$objpuchase->id;
                $objaccounttrans->bracid=$bra;
                $objaccounttrans->transdate=date("Y-m-d", strtotime($request['fixdate']));
                $clientname=customers::where('id','=',$request['client_id'])->get();
                foreach($clientname as $nam){
                    $objaccounttrans->narration=$nam->name." -Fixed Deposit WithDraw";
                }
                $objaccounttrans->save();
            }

            DB::update("update fixeddeposits set isPay=1 where id='$request[fixedid]'");
            // Affecting personal statments 
$Objallsavings= new allsavings();
$Objallsavings->client_no=$request['client_id'];
$Objallsavings->narration="Fixed Deposit Payout";
$Objallsavings->date=date("Y-m-d", strtotime($request['fixdate']));
$Objallsavings->recieptno=$request['payment'];
$Objallsavings->branchno=$bra;
$Objallsavings->headerid=$objpuchase->id;
$Objallsavings->savingpdt5=str_replace( ',', '',$request['amount'])*-1;
if(str_replace( ',', '',$request['interest'])>0){
$Objallsavings->intpdt5=str_replace( ',', '',$request['interest'])*-1;
}

//$Objallsavings->fixdepositid=$Objfixeddeposits->id;
$Objallsavings->save(); 
        }
                    // Posting to Fixed deposits table 
                    $fp=  fixeddeposits::find($request['fixedid']);
                    $fp->paydate=date("Y-m-d", strtotime($request['paydate']));
                    $fp->payoutamount=str_replace( ',', '',$request['fixamount']);
                    $fp->paydet=$request['payment'];
                    $fp->isPay=1;
                    $fp->headerid=$objpuchase->id;
                    $fp->save();

    }catch(\Exception $e){
     echo "Failed ".$e;
     DB::rollBack();   
    }
    DB::commit();
    
}
public function viewfixedwithdraws(){
    if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])){
        $today=date("Y-m-d");
        $this->FDwithdraws("paydate='$today'");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->FDwithdraws("paydate<='$date1'");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $this->FDwithdraws("paydate BETWEEN '$date1' AND '$date2' ");
     }

 
}
public function destroyfixedwithdraws($id,$headerid){
    DB::beginTransaction();
    try{
    $fxd=fixeddeposits::find($id);
    $fxd->isPay=0;
    $fxd->payoutamount=0;
    $fxd->paydet='';
    $fxd->paydate='';
    $fxd->save();
    accounttrans::where('purchaseheaderid','=',$headerid)->delete();
    purchaseheaders::where('id','=',$headerid)->delete();
    allsavings::where('headerid','=',$headerid)->delete();

    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();

}
public function FDwithdraws($where){
    $bra=auth()->user()->branchid;
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count,headerid,fixeddeposits.id id, client_id,name,payout,format(payoutamount,0) payoutamount,paydet, DATE_FORMAT(paydate,'%d-%m-%Y') paydate from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where isPay=1 and branchno=$bra and $where");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select headerid,fixeddeposits.id id, client_id,name,payout,format(payoutamount,0) payoutamount,paydet, DATE_FORMAT(paydate,'%d-%m-%Y') paydate from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where isPay=1 and branchno=$bra and $where limit $offset,$rows");
    $rst->execute();
    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
    $results['rows']=$viewall;
     //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(payoutamount),0) payoutamount from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where isPay=1 and branchno=$bra and $where");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);
}
public function paycheckedit(Request $request,$id,$headerid){
    
    
    $bra=auth()->user()->branchid;
    DB::beginTransaction();
    try{
        $objpuchase=  purchaseheaders::find($headerid);
        $objpuchase->transdates=date("Y-m-d", strtotime($request['fixdate']));
        $objpuchase->branch_id=$bra;
        $objpuchase->save();

        // Cash Account
        $fixed=DB::select("select checkingac,fixeddeposits.term from fixeddepositconfigs inner join fixeddeposits on fixeddeposits.term=fixeddepositconfigs.id where fixeddeposits.branchno=$bra  and fixeddeposits.id='$request[fixedid]'");
        foreach($fixed as $fd){
            $clientname=customers::where('id','=',$request['client_id'])->get();
            foreach($clientname as $nam){
            accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','C')->update(
                [
                    'accountcode'=>$fd->checkingac,
                    'amount'=>str_replace( ',', '',$request['fixamount']),
                    'total'=>str_replace( ',', '',$request['fixamount'])*-1,
                    'bracid'=>$bra,
                    'transdate'=>date("Y-m-d", strtotime($request['fixdate'])),
                    'narration'=>$nam->name.' -Fixed Deposit WithDraw',

                ]
                );
            }
            ################## DEBITING FIXED DEPOSIT
            $clientname=customers::where('id','=',$request['client_id'])->get();
            foreach($clientname as $nam){
            accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode','=',401)->update(
                [
                    'amount'=>str_replace( ',', '',$request['amount']),
                    'total'=>str_replace( ',', '',$request['amount'])*-1,
                    'bracid'=>$bra,
                    'transdate'=>date("Y-m-d", strtotime($request['fixdate'])),
                    'narration'=>$nam->name.' -Fixed Deposit WithDraw',

                ]
                );
            }
            ################### Reducing on Savings Interest Payble ###########
            if(str_replace( ',', '',$request['interest'])>0){
                $clientname=customers::where('id','=',$request['client_id'])->get();
                foreach($clientname as $nam){
                accounttrans::where('purchaseheaderid','=',$headerid)->where('ttype','=','D')->where('accountcode','=',404)->update(
                    [
                        'amount'=>str_replace( ',', '',$request['interest']),
                        'total'=>str_replace( ',', '',$request['interest'])*-1,
                        'bracid'=>$bra,
                        'transdate'=>date("Y-m-d", strtotime($request['fixdate'])),
                        'narration'=>$nam->name.' -Fixed Deposit WithDraw',
    
                    ]
                    );
                }

            }

            DB::update("update fixeddeposits set isPay=1 where id='$request[fixedid]'");
            // Affecting personal statments 
            if(str_replace( ',', '',$request['interest'])>0){
                $totalinterest=str_replace( ',', '',$request['interest'])*-1;
            }else{
                $totalinterest=0; 
            }

            allsavings::where('headerid','=',$headerid)->update([
                'client_no'=>$request['client_id'],
                'narration'=>'Fixed Deposit Payout',
                'date'=>date("Y-m-d", strtotime($request['fixdate'])),
                'recieptno'=>$request['payment'],
                'branchno'=>$bra,
                'savingpdt5'=>str_replace( ',', '',$request['amount'])*-1,
                'intpdt5'=>$totalinterest,

            ]);

        }
                    // Posting to Fixed deposits table 
                    $fp=  fixeddeposits::find($request['fixedid']);
                    $fp->paydate=date("Y-m-d", strtotime($request['paydate']));
                    $fp->payoutamount=str_replace( ',', '',$request['fixamount']);
                    $fp->paydet=$request['payment'];
                    $fp->isPay=1;
                    //$fp->headerid=$objpuchase->id;
                    $fp->save();

    }catch(\Exception $e){
     echo "Failed ".$e;
     DB::rollBack();   
    }
    DB::commit();
    
}
public function depositview($where){
    $bra=auth()->user()->branchid;
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select count(*) as count from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where branchno=$bra $where");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select fixeddeposits.id as id,headerid,headerid2,term, client_id,name,DATE_FORMAT(fixdate,'%d-%m-%Y') as fixdate,format(fixamount,0) as fixamount,fixinterest,fixperiod,type,DATE_FORMAT(maturitydate,'%d-%m-%Y') as maturitydate,format(maturityinterest,0)as maturityinterest,type as mode from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where fixeddeposits.branchno=$bra  $where limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
    $results['rows']=$viewall;
     //Showing The footer and totals 
$footer =  DB::getPdo()->prepare("select format(sum(fixamount),0) as fixamount,format(sum(maturityinterest),0)as maturityinterest from fixeddeposits inner join customers on customers.id=fixeddeposits.client_id where fixeddeposits.branchno=$bra  $where");
$footer->execute();
$foots=$footer->fetchAll(\PDO::FETCH_OBJ);
$results['footer']=$foots;
echo json_encode($results);
}
public function fixedcertificate($id){
    $branch=auth()->user()->branchid;
    $name=DB::select("select name from customers inner join fixeddeposits on customers.id=fixeddeposits.client_id where fixeddeposits.id=$id");
    $company=DB::select("select * from companys where id=$branch");
    $dep=DB::select("select *,format(fixamount,0) as fixamount,DATE_FORMAT(maturitydate,'%d-%m-%Y') as maturitydate,DATE_FORMAT(fixdate,'%d-%m-%Y') as fixdate from fixeddeposits where id=$id");
    $deposit=DB::select("select *,format(fixamount+maturityinterest,0) as payable,format(fixamount,0) as fixamount,DATE_FORMAT(maturitydate,'%d-%m-%Y') as maturitydate,format(maturityinterest,0) as maturityinterest from fixeddeposits where id=$id");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('fixedcertificatepdfs/index')->with('company',$company)->with('dep',$dep)->with('deposit',$deposit)->with('name',$name));
   return $pdf->stream();

}
}