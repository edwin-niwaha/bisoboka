<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\accounttrans;
use App\savingdefinations;
use App\allsavings;
use App\audits;

 class accounttransController extends Controller{

public function index(){
    return view('accounttrans/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from accounttrans ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from accounttrans limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objaccounttrans=new accounttrans();
$Objaccounttrans->id=$request['id'];
$Objaccounttrans->accountcode=$request['accountcode'];
$Objaccounttrans->narration=$request['narration'];
$Objaccounttrans->amount=$request['amount'];
$Objaccounttrans->ttype=$request['ttype'];
$Objaccounttrans->purchaseheaderid=$request['purchaseheaderid'];
$Objaccounttrans->created_at=$request['created_at'];
$Objaccounttrans->updated_at=$request['updated_at'];
$Objaccounttrans->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objaccounttrans=accounttrans::find($id);

$Objaccounttrans->id=$request['id'];
$Objaccounttrans->accountcode=$request['accountcode'];
$Objaccounttrans->narration=$request['narration'];
$Objaccounttrans->amount=$request['amount'];
$Objaccounttrans->ttype=$request['ttype'];
$Objaccounttrans->purchaseheaderid=$request['purchaseheaderid'];
$Objaccounttrans->created_at=$request['created_at'];
$Objaccounttrans->updated_at=$request['updated_at'];
$Objaccounttrans->save();
}
 public function destroy($id){
        $Objaccounttrans=accounttrans::find($id);
        $Objaccounttrans->delete();



    }

public function viewcombo(){


    return accounttrans::all();
}

public function viewexpenses(){

    return view('expenses/expenses');
}

public function saveexpenses(Request $request){
    DB::beginTransaction();
    try{
    $objexpenses= new accounttrans();
    $objexpensesc= new accounttrans();
    $objexpenses->accountcode=$request['accountcode'];
    $objexpenses->narration=$request['narration'];
    $objexpenses->amount=str_replace( ',', '',$request['amount']);
    $objexpenses->total=str_replace( ',', '',$request['amount']);
    $objexpenses->ttype="D";
    $objexpenses->transdate=date("Y-m-d", strtotime($request['date']));
    $objexpenses->purchaseheaderid=$request['purchaseno'];
    $objexpenses->bracid=auth()->user()->branchid;
    $objexpenses->save();
    $objexpensesc->purchaseheaderid=$request['purchaseno'];
    $objexpensesc->amount=str_replace( ',', '',$request['amount']);
    $objexpensesc->bracid=auth()->user()->branchid;
    $objexpensesc->total=str_replace( ',', '',$request['amount'])*-1;
    $objexpensesc->narration=$request['narration'];
    $objexpensesc->accountcode=$request['payingaccount'];
    $objexpensesc->transdate=date("Y-m-d", strtotime($request['date']));
    $objexpensesc->ttype="C";
    $objexpensesc->save();
    $objaudits= new audits();
$objaudits->event=$request['narration']." ".$request['amount']." (Expense) ";
$objaudits->branchno=auth()->user()->branchid;
$objaudits->username=auth()->user()->name;
$objaudits->save();
$lastid=DB::table('accounttrans')->orderBy('id','DESC')->first();
    echo json_encode(array(
        'narration' => $request['narration'],
        'amount' => $request['amount'],
        'accountcode' => $request['accountcode'],
        'Aid'=>$request['purchaseno'],
        'accounttransid'=>$lastid->id-1,
    ));
}catch(\Exception $e){
    DB::rollback();
    echo "Failed ".$e;
}

DB::commit();
}
public function saveotherincomes(Request $request){
    DB::beginTransaction();
    try{
    $objexpenses= new accounttrans();
    $objexpensesc= new accounttrans();
    
    //Determining if anual sub or membership fees
    $codeexits= savingdefinations::where('savingac','=',$request['accountcode'])->whereNull('operatingac')->get();
    if($codeexits->count()>0){
        $objexpenses= new accounttrans();
        $objexpenses->accountcode=$request['accountcode'];
        $objexpenses->narration=$request['narration'].'-'.$request['client'];
        $objexpenses->amount=str_replace( ',', '',$request['amount']);
        $objexpenses->total=str_replace( ',', '',$request['amount']);
        $objexpenses->ttype="C";
        $objexpenses->memid=$request['mcode'];
        $objexpenses->bracid=auth()->user()->branchid;
        $objexpenses->transdate=date("Y-m-d", strtotime($request['date']));
        $objexpenses->purchaseheaderid=$request['purchaseno'];
        $objexpenses->save();
        foreach($codeexits as $exists){
            if($exists->savingpdt=='ansub'){
              $allsavingsobj= new allsavings();
              $allsavingsobj->date=date("Y-m-d", strtotime($request['date']));
              $allsavingsobj->client_no=$request['mcode'];
              $allsavingsobj->narration=$request['narration'].'-'.$request['client'];
              $allsavingsobj->branchno=auth()->user()->branchid;
              $allsavingsobj->headerid=$request['purchaseno'];
              $allsavingsobj->ansub=str_replace( ',', '',$request['amount']);
              $allsavingsobj->save();

            }
            if($exists->savingpdt=='memship'){
                $allsavingsobj= new allsavings();
                $allsavingsobj->date=date("Y-m-d", strtotime($request['date']));
                $allsavingsobj->client_no=$request['mcode'];
                $allsavingsobj->narration=$request['narration'].'-'.$request['client'];
                $allsavingsobj->branchno=auth()->user()->branchid;
                $allsavingsobj->headerid=$request['purchaseno'];
                $allsavingsobj->memship=str_replace( ',', '',$request['amount']);
                $allsavingsobj->save();
  
              }
        }  
    }else{
    $objexpenses->accountcode=$request['accountcode'];
    $objexpenses->narration=$request['narration'].'-'.$request['client'];
    $objexpenses->amount=str_replace( ',', '',$request['amount']);
    $objexpenses->total=str_replace( ',', '',$request['amount']);
    $objexpenses->ttype="C";
    $objexpenses->memid=$request['mcode'];
    $objexpenses->bracid=auth()->user()->branchid;
    $objexpenses->transdate=date("Y-m-d", strtotime($request['date']));
    $objexpenses->purchaseheaderid=$request['purchaseno'];
    $objexpenses->save();
    }
    $objexpensesc->purchaseheaderid=$request['purchaseno'];
    $objexpensesc->amount=str_replace( ',', '',$request['amount']);
    $objexpensesc->total=str_replace( ',', '',$request['amount']);
    $objexpensesc->bracid=auth()->user()->branchid;
    $objexpensesc->narration=$request['narration'].'-'.$request['client'];
    $objexpensesc->accountcode=$request['payingaccount'];
    $objexpensesc->memid=$request['mcode'];
    $objexpensesc->transdate=date("Y-m-d", strtotime($request['date']));
    $objexpensesc->ttype="D";
    $objexpensesc->save();
    $objaudits= new audits();
    $objaudits->event=$request['narration']." ".$request['amount']." (Other Incomes )";
    $objaudits->branchno=auth()->user()->branchid;
    $objaudits->username=auth()->user()->name;
    $objaudits->save();
    $lastid=DB::table('accounttrans')->orderBy('id','DESC')->first();
    echo json_encode(array(
        'narration' => $request['narration'],
        'amount' => $request['amount'],
        'accountcode' => $request['accountcode'],
        'Aid'=>$request['purchaseno'],
        'accounttransid'=>$lastid->id-1,
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'payingaccount'=>$request['payingaccount'],
        'purchaseno'=>$request['purchaseno'], 

    ));
    }catch(\Exception $e){
        echo "Failed ".$e;
    }
DB::commit();

}
public function viewotherincomes(){
return view('otherincomes/index');

}
public function viewtrans($id,$ttype){
    return DB::select("select accounttrans.accountcode,narration,ttype,amount,accountname from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode where purchaseheaderid=$id AND ttype='$ttype'");


}
public function updatebranch(Request $request){
    $payingaccount=$request['payingaccount'];
    $pno=$request['pno'];
    DB::update("update accounttrans set accountcode= $payingaccount  where purchaseheaderid=$pno AND ttype='C'");

}
public function updatebranch1(Request $request){
    $payingaccount=$request['payingaccount'];
    $pno=$request['pno'];
    DB::update("update accounttrans set accountcode= $payingaccount  where purchaseheaderid=$pno AND ttype='D'");

}

public function editexpenses($id){

    return DB::select("select accounttrans.id,accounttrans.accountcode,chartofaccounts.accountname,narration,amount from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype where accounttypes.id=7 and accounttrans.purchaseheaderid=$id");
}

public function viewincomes($id){
    return DB::select("select accounttrans.id,accounttrans.accountcode,chartofaccounts.accountname,narration,amount from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on accounttypes.id=chartofaccounts.accounttype where accounttypes.id=6 and accounttrans.purchaseheaderid=$id");
}

public function expenseedit(Request $request ){
    $id=$request['accounttransid'];
    DB::beginTransaction();
    try{
        //Expense Account
    accounttrans::where('id','=',$id)->where('ttype','=','D')->update(['amount'=>str_replace( ',', '',$request['amount']),'total'=>str_replace( ',', '',$request['amount']),'narration'=>$request['narration'],'accountcode'=>$request['accountcode']]);//->update(['narration'=>$request['naration']]);
    // Crediting Cash Account
    accounttrans::where('id','=',$id+1)->where('ttype','=','C')->update(['amount'=>str_replace( ',', '',$request['amount']),'total'=>str_replace( ',', '',$request['amount'])*-1,'narration'=>$request['narration'],'accountcode'=>$request['payingaccount']]);//->update(['narration'=>$request['naration']]);
    }catch(\Exception $e){
        DB::rollback();
        echo "Failed ".$e;
    }
    DB::commit();


}
public function incomeedit(Request $request ){
    $id=$request['accounttransid'];
    DB::beginTransaction();
    try{
        //Expense Account
    accounttrans::where('id','=',$id+1)->where('ttype','=','D')->update(['memid'=>$request['mcode'],'amount'=>str_replace( ',', '',$request['amount']),'total'=>str_replace( ',', '',$request['amount']),'narration'=>$request['narration'],'accountcode'=>$request['payingaccount']]);//->update(['narration'=>$request['naration']]);
    // Crediting Cash Account
    $codeexits= savingdefinations::where('savingac','=',$request['accountcode'])->whereNull('operatingac')->get();
    if($codeexits->count()>0){
        accounttrans::where('id','=',$id)->where('ttype','=','C')->update([ 'memid'=>$request['mcode'],'amount'=>str_replace( ',', '',$request['amount']),'total'=>str_replace( ',', '',$request['amount']),'narration'=>$request['narration'],'accountcode'=>$request['accountcode']]);
        foreach($codeexits as $exists){
            if($exists->savingpdt=='ansub'){
              allsavings::where('headerid','=',$request['purchaseno'])->update([
                  'client_no'=>$request['mcode'],
                  'narration'=>$request['narration'].'-'.$request['client'],
                  'ansub'=>str_replace( ',', '',$request['amount']),
                  'branchno'=>auth()->user()->branchid,

              ]);

            }
            if($exists->savingpdt=='memship'){
                allsavings::where('headerid','=',$request['purchaseno'])->update([
                    'client_no'=>$request['mcode'],
                    'narration'=>$request['narration'].'-'.$request['client'],
                    'memship'=>str_replace( ',', '',$request['amount']),
                    'branchno'=>auth()->user()->branchid,

                    
  
                ]);
  
              }
        }  
    }else{
        allsavings::where('headerid','=',$request['purchaseno'])->update([
            'ansub'=>0,
            'memship'=>0,
            'client_no'=>0,
        ]);
    accounttrans::where('id','=',$id)->where('ttype','=','C')->update(['memid'=>$request['mcode'],'amount'=>$request['amount'],'total'=>$request['amount'],'narration'=>$request['narration'],'accountcode'=>$request['accountcode']]);//->update(['narration'=>$request['naration']]);
    }
    $lastid=DB::table('accounttrans')->orderBy('id','DESC')->first();
    echo json_encode(array(
        'narration' => $request['narration'],
        'amount' => $request['amount'],
        'accountcode' => $request['accountcode'],
        'Aid'=>$request['purchaseno'],
        'accounttransid'=>$lastid->id-1,
        'client'=>$request['client'],
        'mcode'=>$request['mcode'],
        'date'=>$request['date'],
        'payingaccount'=>$request['payingaccount'],
        'purchaseno'=>$request['purchaseno'], 

    ));
    }catch(\Exception $e){
        DB::rollback();
        echo "Failed ".$e;
    }
    DB::commit();


}
public function help(){
    return 2;
}
}