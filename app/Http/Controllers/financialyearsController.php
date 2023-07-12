<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\financialyears;
use App\purchaseheaders;
use App\accounttrans;
use App\retainedearnings;

 class financialyearsController extends Controller{

public function index(){
    return view('financialyears/index');
}
public function view(){
    $bra=auth()->user()->branchid;

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from financialyears where branchid=$bra");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select id, DATE_FORMAT(startperiod,'%d-%m-%Y') startperiod, DATE_FORMAT(endperiod,'%d-%m-%Y')endperiod,if(status=1,'Active','Not Active') as status from financialyears where branchid=$bra limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
        $isFirst=DB::select("select * from retainedearnings where isFirst=1 AND branchid=$bra");
$num=count($isFirst);
if($num==0){
$Objfinancialyears=new financialyears();
//$Objfinancialyears->id=$request['id'];
$Objfinancialyears->startperiod=date("Y-m-d", strtotime($request['startperiod']));;
$Objfinancialyears->endperiod=date("Y-m-d", strtotime($request['endperiod']));;
$Objfinancialyears->branchid=$bra;
$Objfinancialyears->status=$request['status'];
$Objfinancialyears->created_at=$request['created_at'];
$Objfinancialyears->updated_at=$request['updated_at'];
$Objfinancialyears->save();
$retainedearnings= new retainedearnings();
$retainedearnings->acode=308;
$retainedearnings->yearStart=date("Y-m-d", strtotime($request['startperiod']));;
$retainedearnings->yearEnd=date("Y-m-d", strtotime($request['endperiod']));;
$retainedearnings->branchid=$bra;
$retainedearnings->isFirst=1;
$retainedearnings->yearid=$Objfinancialyears->id;
$retainedearnings->save();

}else{
    if($request['status']==1){
        DB::update("update financialyears set status=0 where branchid=$bra");
    }

$close=DB::select("select * from financialyears where branchid=$bra order by id desc limit 1") ;
foreach($close as $clo){
$Expenses=DB::select("select ttype,amount,credit,chartofaccounts.accountname,chartofaccounts.accounttype,chartofaccounts.accountcode,accounttypes.accounttype as type,transdates from accounttrans inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode inner join accounttypes on chartofaccounts.accounttype=accounttypes.id inner join purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid where branchno=23 and chartofaccounts.accounttype=6 OR chartofaccounts.accounttype=7"); 
$nexty=DB::select("select accounttrans.accountcode,Sum(If(ttype='D',amount,-amount))as Debit from purchaseheaders inner join accounttrans on purchaseheaders.id=accounttrans.purchaseheaderid inner join closeyears on closeyears.accountcode=accounttrans.accountcode
AND accounttrans.bracid=$bra where transdates BETWEEN '$clo->startperiod' AND '$clo->endperiod'  AND closeyears.bracid=$bra AND closeyears.accounttype!=6 AND closeyears.accounttype!=7 group by accounttrans.accountcode");

$yearexpense=DB::select("select sum(if(accounttype=6,amount,0))-sum(if(accounttype=7,amount,0)) as totalincome from yearexpenses where transdate between '$clo->startperiod' AND '$clo->endperiod' and bracid=$bra ");
$retained=DB::select("select * from retainedearnings where  branchid=$bra order by id desc limit 1");
//setting the previous year to complete 
DB::update("update retainedearnings set isComplete=1 where yearid=$clo->id");
$objpurchase= new purchaseheaders();
$objpurchase->transdates=date("Y-m-d", strtotime($request['startperiod']));;
$objpurchase->branch_id=$bra;
$objpurchase->save();
foreach($retained as $retain){

foreach($yearexpense as $exp){
$account1= new accounttrans();
$account1->accountcode=308;
$income=$exp->totalincome;
$account1->amount=abs($income);
$account1->total=$income;
$account1->ttype="C";
$account1->purchaseheaderid=$objpurchase->id;
$account1->bracid=$bra;
$account1->transdate=date("Y-m-d", strtotime($request['startperiod']));;
$account1->narration="Brought Forward Figures";
$account1->save();
// Creation of new year
$Objfinancialyears=new financialyears();
$Objfinancialyears->startperiod=date("Y-m-d", strtotime($request['startperiod']));;
$Objfinancialyears->endperiod=date("Y-m-d", strtotime($request['endperiod']));;
$Objfinancialyears->branchid=$bra;
$Objfinancialyears->status=$request['status'];
$Objfinancialyears->created_at=$request['created_at'];
$Objfinancialyears->updated_at=$request['updated_at'];
$Objfinancialyears->save();
//end of creation of new year
$retainedearnings= new retainedearnings();
$retainedearnings->acode=308;
$retainedearnings->yearStart=date("Y-m-d", strtotime($request['startperiod']));;
$retainedearnings->yearEnd=date("Y-m-d", strtotime($request['endperiod']));;
$retainedearnings->branchid=$bra;
$retainedearnings->amount=($exp->totalincome+$retain->amount);
$retainedearnings->isFirst=0;
//$retainedearnings->isComplete=1;
$retainedearnings->yearid=$Objfinancialyears->id;
$retainedearnings->save();
 }
}
foreach($nexty as $next){
    if($next->Debit<0){
        $type="C";
    }else{
        $type="D";
    }

$account= new accounttrans();
$account->accountcode=$next->accountcode;
$account->amount=abs($next->Debit);
$account->total=abs($next->Debit);
$account->ttype=$type;
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$bra;
$account->transdate=date("Y-m-d", strtotime($request['startperiod']));
$account->narration="Brought Forward Figures";
$account->save();
}

}
}
    }catch(\Exception $e){
        echo "Failed :".$e;
        DB::rollBack();
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
        $startperiod=date("Y-m-d", strtotime($request['startperiod']));
        $endperiod=date("Y-m-d", strtotime($request['endperiod']));
        $edityear=DB::select("select * from retainedearnings where yearid=$id AND branchid=$bra AND isComplete=0");
        $countyear=DB::select("select *,count(*) as count from retainedearnings where yearid=$id And branchid=$bra AND isComplete=1  and yearStart='$startperiod' AND yearEnd='$endperiod' ");
        
        foreach($countyear as $year){
        if($year->count<=0 && count($edityear)==0){
            return ['year'=>'closed']; 
        }else {
            DB::update("update financialyears set status=0  where branchid=$bra");
            $Objfinancialyears=financialyears::find($id);
            $Objfinancialyears->startperiod=date("Y-m-d", strtotime($request['startperiod']));
            $Objfinancialyears->endperiod=date("Y-m-d", strtotime($request['endperiod']));
            $Objfinancialyears->branchid=$bra;
            $Objfinancialyears->status=$request['status'];
            $Objfinancialyears->save();
            #### update the retained earning #######
            retainedearnings::where('branchid','=',$bra)->where('yearid','=',$id)->update([
                'yearStart'=>date("Y-m-d", strtotime($request['startperiod'])),
                'yearEnd'=>date("Y-m-d", strtotime($request['endperiod']))
            
            ]); 
        }
    }
        
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
DB::commit();
}
 public function destroy($id){
    $bra=auth()->user()->branchid;
    $closedyear=DB::select("select * from retainedearnings where yearid=$id And branchid=$bra AND isComplete=1");
    if(count($closedyear)>0){
        return ['year'=>'closed'];
    }else{
        $Objfinancialyears=financialyears::find($id);
        $Objfinancialyears->delete();
    }



    }

public function viewcombo(){


    return financialyears::all();
}

public function activeyear(){
    $bra=auth()->user()->branchid;
    return DB::select("select endperiod,startperiod,id from financialyears where status=1 and branchid=$bra ");
}
}