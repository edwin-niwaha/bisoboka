<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\chartofaccounts;
use App\accounttrans;
use App\purchaseheaders;
use App\retainedearnings;
 class chartofaccountsController extends Controller{

public function index(){
    return view('chartofaccounts/index');
}
public function view(){
        $bra=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id where branchno=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select accept, chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.accountname as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$bra order by chartofaccounts.accountcode asc limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
    $bra=auth()->user()->branchid;
    $Objchartofaccounts=new chartofaccounts();
    $checkchart=chartofaccounts::where('accountcode','=',$request['accountcode'])->where('branchno','=',$bra)->get();
    if(count($checkchart)>0){
        return ['isExist'=>'yes'];
    }else{
$Objchartofaccounts->id=$request['id'];
$Objchartofaccounts->accountcode=$request['accountcode'];
$Objchartofaccounts->accountname=$request['accountname'];
$Objchartofaccounts->accounttype=$request['accounttype'];
$Objchartofaccounts->mainaccount=$request['mainaccount'];
$Objchartofaccounts->openingbal=str_replace( ',', '',$request['openingbal']);
if($request['asof']==''){
    $Objchartofaccounts->asof=null;   
}else{
$Objchartofaccounts->asof=date("Y-m-d", strtotime($request['asof']));
}
$Objchartofaccounts->branchno=$bra;
$Objchartofaccounts->isActive=$request['isActive'];
$Objchartofaccounts->isDefault=$request['isDefault'];
$Objchartofaccounts->created_at=$request['created_at'];
$Objchartofaccounts->updated_at=$request['updated_at'];
$Objchartofaccounts->save();
//purchase header 
$objpurchase= new purchaseheaders();
if($request['asof']==''){
    $objpurchase->transdates=null;  
}else{
$objpurchase->transdates=date("Y-m-d", strtotime($request['asof']));
}
$objpurchase->branch_id=$bra;
$objpurchase->save();
$account1= new accounttrans();
$account1->accountcode=$request['accountcode'];
$account1->amount=str_replace( ',', '',$request['openingbal']);
$account1->total=str_replace( ',', '',$request['openingbal']);
if($request['accounttype']==1 || $request['accounttype']==2){
    $account1->ttype="D";
}else if($request['accounttype']==3 || $request['accounttype']==4 || $request['accounttype=']==5){
    $account1->ttype="C";
}

$account1->purchaseheaderid=$objpurchase->id;
$account1->bracid=$bra;
$account1->cat='isFirst';
if($request['asof']==''){
    $account1->transdate=null;
}else{
$account1->transdate=date("Y-m-d", strtotime($request['asof']));
}
$account1->narration="Brought Forward Figures";
$account1->save();
}
    }catch(\Exception $e){
        echo "Faied to save ".$e;
        DB::rollBack();
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
        $isFirst=DB::select("select * from retainedearnings where isFirst=1 AND branchid=$bra");
$num=count($isFirst);
//if($num==1){
$Objchartofaccounts=chartofaccounts::find($id);
$Objchartofaccounts->accountcode=$request['accountcode'];
$Objchartofaccounts->accountname=$request['accountname'];
$Objchartofaccounts->accounttype=$request['accounttype'];
$Objchartofaccounts->mainaccount=$request['mainaccount'];
$Objchartofaccounts->openingbal=str_replace( ',', '',$request['openingbal']);;
if($request['asof']==''){
    $Objchartofaccounts->asof=null;   
}else{
$Objchartofaccounts->asof=date("Y-m-d", strtotime($request['asof']));
}
$Objchartofaccounts->isActive=$request['isActive'];
$Objchartofaccounts->isDefault=$request['isDefault'];
$Objchartofaccounts->save();

accounttrans::where('accountcode','=',$request['accountcode'])->where('bracid','=',$bra)->where('cat','=','isFirst')->update([
    'narration'=>'Brought Foward Figures',
    'amount'=>str_replace( ',', '',$request['openingbal']),
    'total'=>str_replace( ',', '',$request['openingbal']),
    'transdate'=>date("Y-m-d", strtotime($request['asof'])),
    ]);
    $ac=accounttrans::where('accountcode','=',$request['accountcode'])->where('bracid','=',$bra)->where('cat','=','isFirst')->get();
    foreach($ac as $c){
        purchaseheaders::where('id',$c->purchaseheaderid)->where('branch_id','=',$bra)->update(['transdates'=>date("Y-m-d", strtotime($request['asof']))

]);
    }
// accountrans

if($request['accountcode']==9000){
    retainedearnings::where('branchid','=',$bra)->where('isFirst','=',1)->update([
        'amount'=>str_replace( ',', '',$request['openingbal']),
        ]);
}


//}else{

//}
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
    
}
 public function destroy($id){
     
        $Objchartofaccounts=chartofaccounts::find($id);
       $chart= DB::select("select sum(amount) as amount from accounttrans where accountcode=$Objchartofaccounts->accountcode");
        foreach($chart as $chat){
            if($chat->amount>0){
        return ['isdelete'=>'No'];
            }else{
            $Objchartofaccounts->delete();
            }
        }
        
       



    }

public function viewcombo($id){
    $bra=auth()->user()->branchid;

    return chartofaccounts::where('accounttype',$id)->where('branchno','=',$bra)->get();
}
public function viewcombo1(){
    $bra=auth()->user()->branchid;
    return chartofaccounts::where('branchno','=',$bra)->get();
}
public function inventorysettings(){
    return view ('inventorysettings/index1');

}
public function chartofaccountpreview(){
    $branch=auth()->user()->branchid;
    $cassets=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=1 order by accountcode asc");
    $fassets=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=2 order by accountcode asc");
    $cliabilities=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=3 order by accountcode asc");
    $ltliabilities=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=4 order by accountcode asc");
    $equity=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=5 order by accountcode asc");
    $income=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=6 order by accountcode asc");
    $expense=DB::select("select  chartofaccounts.id as id,accountname,format(openingbal,0) openingbal,DATE_FORMAT(asof,'%d-%m-%Y') asof,accountcode,accounttypes.id as typeid,chartofaccounts.accounttype, chartofaccounts.mainaccount as parent,accounttypes.accounttype as names from chartofaccounts inner join accounttypes on chartofaccounts.accounttype=accounttypes.id  where branchno=$branch and chartofaccounts.accounttype=7 order by accountcode asc");
    //$income=DB::select("select format(amount,0) as amount,ttype,accountname,bracid,accountcode from incomepdfs where bracid=$branch");
    //$totalexpense=DB::select("select format(sum(amount),0) as amount,ttype,accountname,bracid,accountcode from expensepdfs where bracid=$branch");
    //$expense=DB::select("select format(amount,0) as amount,ttype,accountname,bracid,accountcode from expensepdfs where bracid=$branch");
    $company=DB::select("select * from companys where id=$branch");
    $pdf = \App::make('dompdf.wrapper');
    $da=date('d-m-Y');
    //DB::statement("set @CumulativeSum := 0;");
    //$ledger =  DB::select("select format((@CumulativeSum := @CumulativeSum + total),0) as runningbal,DATE_FORMAT(date,'%d-%m-%Y') date,format(moneyin,0) as moneyin, format(moneyout,0) as moneyout,paydet,narration from savings where client_no=$id and category='$pdt'");
    $pdf->loadHTML(view('chartofaccountspdfs/index')->with('company',$company)->with('cassets',$cassets)->with('fassets',$fassets)->with('cliabilities',$cliabilities)->with('ltliabilities',$ltliabilities)->with('equity',$equity)->with('income',$income)->with('expense',$expense));
    return $pdf->stream();
}
public function importchartofaccount(Request $request){
    DB::beginTransaction();
    try{
        $bra=auth()->user()->branchid;
        $file=$request->file('files');
		$destinationPath="images";
		if($file!=Null){
			$filename=$file->getClientOriginalName();
			//moving it to the folder
			$finalfile=$file->move($destinationPath,$filename);
			$handle = fopen($finalfile, "r");
			while(($data=fgetcsv($handle,1000,","))!==FALSE  ){
                $check=chartofaccounts::where('accountcode','=',$data[1])->where('branchno','=',$bra)->get();
                if(count($check)>0){

                }else{
                    $chart= new chartofaccounts();
                    $chart->accountname=$data[0];
                    $chart->accountcode=$data[1];
                    $chart->accounttype=$data[2];
                    $chart->mainaccount=$data[3];
                    $chart->branchno=$bra;
                    $chart->openingbal=str_replace( ',', '',$data[4]);;
                    if($data[5]==''){
                        $chart->asof=null;

                    }else{
                    $chart->asof=date("Y-m-d", strtotime($data[5]));
                    }
                    $chart->save();
                    //purchase header 
$objpurchase= new purchaseheaders();
if($request['asof']==''){
    $objpurchase->transdates=null;  
}else{
$objpurchase->transdates=date("Y-m-d", strtotime($request['asof']));
}
$objpurchase->branch_id=$bra;
$objpurchase->save();
$account1= new accounttrans();
$account1->accountcode=$data[1];
$account1->amount=str_replace( ',', '',$data[4]);
$account1->total=str_replace( ',', '',$data[4]);
if($data[2]==1 || $data[2]==2){
    $account1->ttype="D";
}else if($data[2]==3 || $data[2]==4 || $data[2]==5){
    $account1->ttype="C";
}

$account1->purchaseheaderid=$objpurchase->id;
$account1->bracid=$bra;
$account1->cat='isFirst';
if($data[5]==''){
    $account1->transdate=null;
}else{
$account1->transdate=date("Y-m-d", strtotime($data[5]));
}
$account1->narration="Brought Forward Figures";
$account1->save();
                }
            }
        }


    }catch(\Exception $e){
        echo "Failed to import ".$e;
        DB::rollBack();
    }
    DB::commit();

}
}