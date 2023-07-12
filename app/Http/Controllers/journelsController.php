<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\accounttrans;
use App\purchaseheaders;
use App\loaninfo;
use App\chartofaccounts;
use App\savings;
use App\savingdefinations;
use App\allsavings;

 class journelsController extends Controller{

public function index(){

    return view('journels/index');
}

public function journelreports(){
    return view('journelrpts/index1');
}
public function viewjournelreport(){
	
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) ){
       
        $today=date("'Y-m-d'");
		
		$branch=1;//$_GET['branch'];
        $this->view(" and transdates=$today order by purchaseheaderid asc");
    }
    if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])){
        
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $branch=1;//$_GET['branch'];
        $this->view("and transdates  BETWEEN '$date1' AND '$date2' and bracid=$branch order by purchaseheaderid asc");
     
     }
     if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])){
       
        $today=date("'Y-m-d'");
        $branch=1;//$_GET['branch'];
		
        $this->view("and transdates=$today and bracid=$branch order by purchaseheaderid asc ");
     
     }
 

}

public function journelsave(Request $request){
DB::beginTransaction();
try{
   $branch= auth()->user()->branchid;
   $objallsavings= new allsavings();
   // saving into savings table for clear statements 
   $objsaving1= new savings();
   $objsaving2= new savings();
   $objsaving3= new savings();
   $objsaving4= new savings();
   $objshareamt= new savings();
   $objallsavings->client_no=$request['mcode'];
   $objallsavings->recieptno="JV".$request['pno'];//$request['paymentdetails'];
   $objallsavings->branchno=$branch;
   $objallsavings->date=date("Y-m-d", strtotime($request['date']));
   $narration="";
   $member=DB::select("select name from customers where id='$request[mcode]'");
    if(str_replace( ',', '',$request['debit'])!=0){ // IF ITS A DEBIT 
        $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
        if(count($getsavingpdt)>0){
            $objaccounttrans= new accounttrans();
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->memid=$request['mcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->amount=str_replace( ',', '',$request['debit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                
                
                if($t->accounttype==1 || $t->accounttype==2 || $t->accounttype==7){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype=6){
                    $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
                    foreach($getsavingpdt as $getsaving){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit'])*-1; //Debit for negative
                    foreach($member as $mem){ // Geting Member Details
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt1'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving1->client_no=$request['mcode'];
                        $objsaving1->paydet="JV".$request['pno'];
                        $objsaving1->isCharge=0;
                        $objsaving1->branchno=$branch;
                        $objsaving1->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving1->category='savingpdt1';
                        $objsaving1->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt2'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving2->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving2->client_no=$request['mcode'];
                        $objsaving2->paydet="JV";
                        $objsaving2->isCharge=0;
                        $objsaving2->branchno=$branch;
                        $objsaving2->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving2->category='savingpdt2';
                        $objsaving2->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt3'){
                        $objallsavings->savingpdt3=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving3->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving3->client_no=$request['mcode'];
                        $objsaving3->paydet="JV".$request['pno'];;
                        $objsaving3->isCharge=0;
                        $objsaving3->branchno=$branch;
                        $objsaving3->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving3->category='savingpdt3';
                        $objsaving3->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt4'){
                        $objallsavings->savingpdt4=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Witdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving4->client_no=$request['mcode'];
                        $objsaving4->paydet="JV".$request['pno'];;
                        $objsaving4->isCharge=0;
                        $objsaving4->branchno=$branch;
                        $objsaving4->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving4->category='savingpdt1';
                        $objsaving4->moneyout=str_replace( ',', '',$request['debit']);
                    } if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='shares'){
                        $objallsavings->shares=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
            
                    }
                    
                    $objallsavings->narration=$narration." WithDraw";
                    $objallsavings->headerid=$request['pno'];
                    $objallsavings->save();
                               // saving id in savings table
                               $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs1->count()>0){
                                $objsaving1->savingsid=$objallsavings->id; 
                                $objsaving1->save();
                               }
                               $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs2->count()>0){
                                $objsaving2->savingsid=$objallsavings->id; 
                                $objsaving2->save();
                               }
                               $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs3->count()>0){
                                $objsaving3->savingsid=$objallsavings->id; 
                                $objsaving3->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objsaving4->savingsid=$objallsavings->id; 
                                $objsaving4->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objshareamt->savingsid=$objallsavings->id; 
                                $objshareamt->save();
                               }
                }
            }
            }
            //if($request)
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->ttype="D";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
                }

        }else{
            $objaccounttrans= new accounttrans();
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->memid=$request['mcode'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->amount=str_replace( ',', '',$request['debit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                if($t->accounttype==1 || $t->accounttype==2 || $t->accounttype==7){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype=6){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit'])*-1;
                }
            }
            $objaccounttrans->ttype="D";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
        }
    }

    if(str_replace( ',', '',$request['credit'])!=0){ // IF ITS A CREDIT 
        $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
        if(count($getsavingpdt)>0){
 
            $objaccounttrans= new accounttrans();
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->memid=$request['mcode'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->amount=str_replace( ',', '',$request['credit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                
                
                if($t->accounttype==1 || $t->accounttype==2 ){
                    $objaccounttrans->total=str_replace( ',', '',$request['credit'])*-1;
                
                    //$objaccounttrans->total=str_replace( ',', '',$request['credit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype==6){
                    $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
                    foreach($getsavingpdt as $getsaving){
                    $objaccounttrans->total=str_replace( ',', '',$request['credit']); //Debit for negative
                    foreach($member as $mem){ // Geting Member Details
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt1'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving1->client_no=$request['mcode'];
                        $objsaving1->paydet="JV".$request['pno'];;
                        $objsaving1->isCharge=0;
                        $objsaving1->branchno=$branch;
                        $objsaving1->total=str_replace( ',', '',$request['credit']);
                        $objsaving1->category='savingpdt1';
                        $objsaving1->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt2'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving2->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving2->client_no=$request['mcode'];
                        $objsaving2->paydet="JV".$request['pno'];;
                        $objsaving2->isCharge=0;
                        $objsaving2->branchno=$branch;
                        $objsaving2->total=str_replace( ',', '',$request['credit']);
                        $objsaving2->category='savingpdt2';
                        $objsaving2->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt3'){
                        $objallsavings->savingpdt3=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving3->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving3->client_no=$request['mcode'];
                        $objsaving3->paydet="JV".$request['pno'];;
                        $objsaving3->isCharge=0;
                        $objsaving3->branchno=$branch;
                        $objsaving3->total=str_replace( ',', '',$request['credit']);
                        $objsaving3->category='savingpdt3';
                        $objsaving3->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt4'){
                        $objallsavings->savingpdt4=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving4->client_no=$request['mcode'];
                        $objsaving4->paydet="JV".$request['pno'];;
                        $objsaving4->isCharge=0;
                        $objsaving4->branchno=$branch;
                        $objsaving4->total=str_replace( ',', '',$request['credit']);
                        $objsaving4->category='savingpdt1';
                        $objsaving4->moneyin=str_replace( ',', '',$request['credit']);
                    } if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='shares'){
                        $objallsavings->shares=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
            
                    }
                    
                    $objallsavings->narration=$narration." Cash Deposit";
                    $objallsavings->headerid=$request['pno'];
                    $objallsavings->save();
                               // saving id in savings table
                               $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs1->count()>0){
                                $objsaving1->savingsid=$objallsavings->id; 
                                $objsaving1->save();
                               }
                               $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs2->count()>0){
                                $objsaving2->savingsid=$objallsavings->id; 
                                $objsaving2->save();
                               }
                               $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs3->count()>0){
                                $objsaving3->savingsid=$objallsavings->id; 
                                $objsaving3->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objsaving4->savingsid=$objallsavings->id; 
                                $objsaving4->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objshareamt->savingsid=$objallsavings->id; 
                                $objshareamt->save();
                               }
                }
            }
            }
            //if($request)
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->ttype="C";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
                }
        }
        else{
$objaccounttrans= new accounttrans();
$objaccounttrans->accountcode=$request['accountcode'];
$objaccounttrans->narration=$request['description'];
$objaccounttrans->memid=$request['mcode'];
$objaccounttrans->purchaseheaderid=$request['pno'];
$objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccounttrans->amount=str_replace( ',', '',$request['credit']);
$objaccounttrans->ttype="C";

//$objexpensesc->bracid=auth()->user()->branchid;
$objaccounttrans->bracid=auth()->user()->branchid;

// fixing 
$acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
foreach($acount as $t){
    if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype==6){
        $objaccounttrans->total=str_replace( ',', '',$request['credit']);
    }else if($t->accounttype==1 || $t->accounttype==2 ){
        $objaccounttrans->total=str_replace( ',', '',$request['credit'])*-1;
    }
}
$objaccounttrans->save();
        }
    
   } // END OF SEARCHING if its savings or loans 
    ######################################## POSTING IN INDIVIDUAL TABLES ###############################################################
    $lastid=DB::table('accounttrans')->orderBy('id','DESC')->first();
    if($request['credit']!=0){
    echo json_encode(array(
        'description' => $request['description'],
        'credit' => $request['credit'],
        'accountcode' => $request['accountcode'],
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'accounttransid'=>$lastid->id,


    ));
}
if($request['debit']!=0){
    echo json_encode(array(
        'description' => $request['description'],
        'debit' => $request['debit'],
        'accountcode' => $request['accountcode'],
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'accounttransid'=>$lastid->id,


    ));
}


}catch(\Exception $e){
    echo "Failed ".$e;
    DB::rollBack();
}
DB::commit();

}

public function journelheader(Request $request){
      // $objloaninfo= new loaninfo();
    //$objloaninfo->isExpense=2; //2 is only for Journal entries 
    //$objloaninfo->save();
    DB::beginTransaction();
    try{
    $objpurchaseheaders= new purchaseheaders();
    $objpurchaseheaders->id=$request['pno'];
    $objpurchaseheaders->isActive=1;
    $objpurchaseheaders->mode=2; // for journal only
    $objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
    $objpurchaseheaders->save();
    }
catch(\Exception $e){
    echo "Failed ".$e;
    DB::rollBack();
}
DB::commit();



}

public function view($where){


    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from accounttrans inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode where mode=2 and purchaseheaders.isActive=1 $where");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select format(if(ttype='c',amount,0),0) as credit,chartofaccounts.accountcode,accountname,purchaseheaderid as id, format(if(ttype='D',amount,0),0) as debit,DATE_FORMAT(transdates,'%d-%m-%Y') as transdates,narration from accounttrans inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode where mode=2 and purchaseheaders.isActive=1 $where limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   echo json_encode($results);


}

public function destroy(Request $request,$id){

    $del=purchaseheaders::find($id);
    $del->delete();
    accounttrans::where('purchaseheaderid','=',$id)->delete();


}
public function editjournel(Request $request){
    DB::beginTransaction();
try{
   $branch= auth()->user()->branchid;
   $savingid=0;
   $orsavings=0;
   $svg=allsavings::where('headerid','=',$request['pno'])->where('branchno','=',$branch)->get();
   foreach($svg as $g){
      $savingid =$g->id;

   }
   $savingout=savings::where('savingsid','=',$savingid)->where('branchno','=',$branch)->get();
   foreach($savingout as $out){
       $orsavings=$out->id;

   }
   $objallsavings=  allsavings::find($savingid);
   // saving into savings table for clear statements 
   $objsaving1=  savings::find($orsavings);
   $objsaving2= savings::find($orsavings);
   $objsaving3= savings::find($orsavings);
   $objsaving4= savings::find($orsavings);
   $objshareamt=  savings::find($orsavings);
   $objallsavings->client_no=$request['mcode'];
   $objallsavings->recieptno="JV".$request['pno'];//$request['paymentdetails'];
   $objallsavings->branchno=$branch;
   $objallsavings->date=date("Y-m-d", strtotime($request['date']));
   $narration="";
   $member=DB::select("select name from customers where id='$request[mcode]'");
    if(str_replace( ',', '',$request['debit'])!=0){ // IF ITS A DEBIT 
        $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
        if(count($getsavingpdt)>0){
            $objaccounttrans= accounttrans::find($request['accounttransid']);
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->amount=str_replace( ',', '',$request['debit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                
                
                if($t->accounttype==1 || $t->accounttype==2 || $t->accounttype==7){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype=6){
                    $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
                    foreach($getsavingpdt as $getsaving){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit'])*-1; //Debit for negative
                    foreach($member as $mem){ // Geting Member Details
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt1'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving1->client_no=$request['mcode'];
                        $objsaving1->paydet="JV".$request['pno'];
                        $objsaving1->isCharge=0;
                        $objsaving1->branchno=$branch;
                        $objsaving1->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving1->category='savingpdt1';
                        $objsaving1->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt2'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving2->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving2->client_no=$request['mcode'];
                        $objsaving2->paydet="JV";
                        $objsaving2->isCharge=0;
                        $objsaving2->branchno=$branch;
                        $objsaving2->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving2->category='savingpdt2';
                        $objsaving2->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt3'){
                        $objallsavings->savingpdt3=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving3->narration="Cash Withdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving3->client_no=$request['mcode'];
                        $objsaving3->paydet="JV".$request['pno'];;
                        $objsaving3->isCharge=0;
                        $objsaving3->branchno=$branch;
                        $objsaving3->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving3->category='savingpdt3';
                        $objsaving3->moneyout=str_replace( ',', '',$request['debit']);
                    }
                    if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='savingpdt4'){
                        $objallsavings->savingpdt4=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Witdraw-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving4->client_no=$request['mcode'];
                        $objsaving4->paydet="JV".$request['pno'];;
                        $objsaving4->isCharge=0;
                        $objsaving4->branchno=$branch;
                        $objsaving4->total=str_replace( ',', '',$request['debit'])*-1;
                        $objsaving4->category='savingpdt1';
                        $objsaving4->moneyout=str_replace( ',', '',$request['debit']);
                    } if(str_replace( ',', '',$request['debit'])>0 && $getsaving->savingpdt=='shares'){
                        $objallsavings->shares=str_replace( ',', '',$request['debit'])*-1;
                        $narration= $getsaving->productname;
            
                    }
                    
                    $objallsavings->narration=$narration." WithDraw";
                    $objallsavings->headerid=$request['pno'];
                    $objallsavings->save();
                               // saving id in savings table
                               $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs1->count()>0){
                                $objsaving1->savingsid=$objallsavings->id; 
                                $objsaving1->save();
                               }
                               $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs2->count()>0){
                                $objsaving2->savingsid=$objallsavings->id; 
                                $objsaving2->save();
                               }
                               $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs3->count()>0){
                                $objsaving3->savingsid=$objallsavings->id; 
                                $objsaving3->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objsaving4->savingsid=$objallsavings->id; 
                                $objsaving4->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objshareamt->savingsid=$objallsavings->id; 
                                $objshareamt->save();
                               }
                }
            }
            }
            //if($request)
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->ttype="D";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
                }

        }else{
            // if account previous used is not found, please remove all the data 
           /* $prevaccount=allsaving::where('headerid','=',$request['pno'])->get();
            foreach($prevaccount as $pre){
                savings::where('savingsid','=',$pre->id)->update([
                    'branchno'=>'0'
                ]);
            }
            allsavings::where('headerid','=',$request['pno'])->update([
                'brancho'=>'0'

            ]);*/
            

            $objaccounttrans= accounttrans::find($request['accounttransid']);
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->amount=str_replace( ',', '',$request['debit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                if($t->accounttype==1 || $t->accounttype==2 || $t->accounttype==7){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype=6){
                    $objaccounttrans->total=str_replace( ',', '',$request['debit'])*-1;
                }
            }
            $objaccounttrans->ttype="D";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
        }
    }

    if(str_replace( ',', '',$request['credit'])!=0){ // IF ITS A CREDIT 
        $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
        if(count($getsavingpdt)>0){
 
            $objaccounttrans=  accounttrans::find($request['accounttransid']);
            $objaccounttrans->accountcode=$request['accountcode'];
            $objaccounttrans->narration=$request['description'];
            $objaccounttrans->purchaseheaderid=$request['pno'];
            $objaccounttrans->amount=str_replace( ',', '',$request['credit']);
            $acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
            foreach($acount as $t){
                
                
                if($t->accounttype==1 || $t->accounttype==2 ){
                    $objaccounttrans->total=str_replace( ',', '',$request['credit'])*-1;
                
                    //$objaccounttrans->total=str_replace( ',', '',$request['credit']);// Debit for positive
                    // checking if its a savings account 
            
                }else if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype==6){
                    $getsavingpdt=savingdefinations::where('savingac','=',$request['accountcode'])->where('isActive','=',1)->where('branchno','=',$branch)->get();
                    foreach($getsavingpdt as $getsaving){
                    $objaccounttrans->total=str_replace( ',', '',$request['credit']); //Debit for negative
                    foreach($member as $mem){ // Geting Member Details
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt1'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving1->client_no=$request['mcode'];
                        $objsaving1->paydet="JV".$request['pno'];;
                        $objsaving1->isCharge=0;
                        $objsaving1->branchno=$branch;
                        $objsaving1->total=str_replace( ',', '',$request['credit']);
                        $objsaving1->category='savingpdt1';
                        $objsaving1->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt2'){
                        $objallsavings->savingpdt1=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving2->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving2->client_no=$request['mcode'];
                        $objsaving2->paydet="JV".$request['pno'];;
                        $objsaving2->isCharge=0;
                        $objsaving2->branchno=$branch;
                        $objsaving2->total=str_replace( ',', '',$request['credit']);
                        $objsaving2->category='savingpdt2';
                        $objsaving2->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt3'){
                        $objallsavings->savingpdt3=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving3->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving3->client_no=$request['mcode'];
                        $objsaving3->paydet="JV".$request['pno'];;
                        $objsaving3->isCharge=0;
                        $objsaving3->branchno=$branch;
                        $objsaving3->total=str_replace( ',', '',$request['credit']);
                        $objsaving3->category='savingpdt3';
                        $objsaving3->moneyin=str_replace( ',', '',$request['credit']);
                    }
                    if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='savingpdt4'){
                        $objallsavings->savingpdt4=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
                        $objsaving1->narration="Cash Deposit-".$getsaving->productname;
                        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
                        $objsaving4->client_no=$request['mcode'];
                        $objsaving4->paydet="JV".$request['pno'];;
                        $objsaving4->isCharge=0;
                        $objsaving4->branchno=$branch;
                        $objsaving4->total=str_replace( ',', '',$request['credit']);
                        $objsaving4->category='savingpdt1';
                        $objsaving4->moneyin=str_replace( ',', '',$request['credit']);
                    } if(str_replace( ',', '',$request['credit'])>0 && $getsaving->savingpdt=='shares'){
                        $objallsavings->shares=str_replace( ',', '',$request['credit']);
                        $narration= $getsaving->productname;
            
                    }
                    
                    $objallsavings->narration=$narration." Cash Deposit";
                    $objallsavings->headerid=$request['pno'];
                    $objallsavings->save();
                               // saving id in savings table
                               $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs1->count()>0){
                                $objsaving1->savingsid=$objallsavings->id; 
                                $objsaving1->save();
                               }
                               $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs2->count()>0){
                                $objsaving2->savingsid=$objallsavings->id; 
                                $objsaving2->save();
                               }
                               $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs3->count()>0){
                                $objsaving3->savingsid=$objallsavings->id; 
                                $objsaving3->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objsaving4->savingsid=$objallsavings->id; 
                                $objsaving4->save();
                               }
                               $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                               if($rs4->count()>0){
                                $objshareamt->savingsid=$objallsavings->id; 
                                $objshareamt->save();
                               }
                }
            }
            }
            //if($request)
            $objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
            $objaccounttrans->ttype="C";
            $objaccounttrans->bracid=auth()->user()->branchid;
            $objaccounttrans->save();
                }
        }
        else{
$objaccounttrans=  accounttrans::find($request['accounttransid']);
$objaccounttrans->accountcode=$request['accountcode'];
$objaccounttrans->narration=$request['description'];
$objaccounttrans->purchaseheaderid=$request['pno'];
$objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
$objaccounttrans->amount=str_replace( ',', '',$request['credit']);
$objaccounttrans->ttype="C";

//$objexpensesc->bracid=auth()->user()->branchid;
$objaccounttrans->bracid=auth()->user()->branchid;

// fixing 
$acount=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$branch)->get();
foreach($acount as $t){
    if($t->accounttype==3 || $t->accounttype==4 || $t->accounttype==5 || $t->accounttype==6){
        $objaccounttrans->total=str_replace( ',', '',$request['credit']);
    }else if($t->accounttype==1 || $t->accounttype==2 ){
        $objaccounttrans->total=str_replace( ',', '',$request['credit'])*-1;
    }
}
$objaccounttrans->save();
        }
    
   } // END OF SEARCHING if its savings or loans 
    ######################################## POSTING IN INDIVIDUAL TABLES ###############################################################
    $lastid=DB::table('accounttrans')->orderBy('id','DESC')->first();
    if($request['credit']!=0){
    echo json_encode(array(
        'description' => $request['description'],
        'credit' => $request['credit'],
        'accountcode' => $request['accountcode'],
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'accounttransid'=>$lastid->id,


    ));
}
if($request['debit']!=0){
    echo json_encode(array(
        'description' => $request['description'],
        'debit' => $request['debit'],
        'accountcode' => $request['accountcode'],
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'accounttransid'=>$lastid->id,


    ));
}


}catch(\Exception $e){
    echo "Failed ".$e;
    DB::rollBack();
}
DB::commit();


}
public function viewjournelfooters($id){
    $results=DB::select("select customers.id as mcode, accounttrans.id as accounttransid,accountname as stockid,narration as description,accounttrans.accountcode,if(ttype='D',format(amount,0),'') as debit,if(ttype='C',format(amount,0),'') as credit,name as client from accounttrans inner join chartofaccounts on chartofaccounts.accountcode=accounttrans.accountcode left join customers on customers.id=accounttrans.memid where purchaseheaderid=$id");
    return $results;
    
}
 }

 