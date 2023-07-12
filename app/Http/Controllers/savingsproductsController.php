<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\savingsproducts;
use App\savingdefinations;
use App\chartofaccounts;
use App\savingcals;

 class savingsproductsController extends Controller{

public function index(){
    return view('savingsproducts/index');
}
public function view(){
        $branch= auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from savingsproducts where branchno=$branch ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select intmethod,sitonaccount as nodays,  if(intmethod=1,'Simple','Compound') as intmeth,freq,dayofpost as datecomp,savingsproducts.id as id,if(savingsproducts.isActive=1,'Yes','No') as active,accountname ,operatingac as account,savingsproducts.name as name,savingsproducts.accountcode as accountcode,minbal,savingsproducts.interest as interest,charge,savingsproducts.isActive as isActive from savingsproducts inner join savingdefinations on savingdefinations.savingac=savingsproducts.accountcode inner join chartofaccounts on savingdefinations.operatingac=chartofaccounts.accountcode where savingsproducts.branchno=$branch group by savingsproducts.id limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $branch= auth()->user()->branchid;
    $acode=DB::select("select accountcode from chartofaccounts where accountcode='$request[accountcode]' AND branchno=$branch");
    if(count($acode)>0){
return ['exists'=>'true'];
    }else{
        $product=array();
        $savingsproducts=DB::select("select * from savingdefinations where branchno=$branch"); 
        
        foreach($savingsproducts as $savings){
        $product[]=$savings->savingpdt;
        }
        $x=0;
        while($x<=1){
            if(in_array('savingpdt1',$product)==false){
            $this->savings($request['name'],$request['accountcode'],$request['minbal'],$request['interest'],$request['charge'],$request['isActive'],"savingpdt1","intpdt1",$request['account'],$request['intmethod'],$request['freq'],$request['datecomp'],$request['nodays']);
            break;
            }
            else if(in_array('savingpdt2',$product)==false){
            
                $this->savings($request['name'],$request['accountcode'],$request['minbal'],$request['interest'],$request['charge'],$request['isActive'],"savingpdt2","intpdt2",$request['account'],$request['intmethod'],$request['freq'],$request['datecomp'],$request['nodays']);
                break;
                }
                else if(in_array('savingpdt3',$product)==false){
                    $this->savings($request['name'],$request['accountcode'],$request['minbal'],$request['interest'],$request['charge'],$request['isActive'],"savingpdt3","intpdt3",$request['account'],$request['intmethod'],$request['freq'],$request['datecomp'],$request['nodays']);
                    break;
                    }
                    else if(in_array('savingpdt4',$product)==false){
                        $this->savings($request['name'],$request['accountcode'],$request['minbal'],$request['interest'],$request['charge'],$request['isActive'],"savingpdt4","intpdt4",$request['account'],$request['intmethod'],$request['freq'],$request['datecomp'],$request['nodays']);
                        break;
                        }else{
                            return ['limit'=>true];
                            }
                            $x++;
            }

    
}
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objsavingsproducts=savingsproducts::find($id);
$Objsavingsproducts->name=$request['name'];
//$Objsavingsproducts->accountcode=$request['accountcode'];
$Objsavingsproducts->minbal=$request['minbal'];
$Objsavingsproducts->interest=$request['interest'];
$Objsavingsproducts->charge=$request['charge'];
$Objsavingsproducts->isActive=$request['isActive'];
$Objsavingsproducts->intmethod=$request['intmethod'];
$Objsavingsproducts->freq=$request['freq'];
$Objsavingsproducts->dayofpost=$request['datecomp'];
$Objsavingsproducts->sitonaccount=$request['nodays'];
savingdefinations::where('savingac','=',$Objsavingsproducts->accountcode)->update([
'productname'=>$request['name']

]);
chartofaccounts::where('accountcode','=',$Objsavingsproducts->accountcode)->update([
    'accountname'=>$request['name']
    
    ]);
savingcals::where('savingpdtac','=',$Objsavingsproducts->accountcode)->update([
    'nodays'=>$request['nodays'],
    'frekq'=>$request['freq']
]);
$Objsavingsproducts->save();
}
 public function destroy($id){
        $Objsavingsproducts=savingsproducts::find($id);
        chartofaccounts::where('accountcode','=',$Objsavingsproducts->accountcode)->delete();
        savingdefinations::where('savingac','=',$Objsavingsproducts->accountcode)->delete();
        $Objsavingsproducts->delete();
        savingcals::where('savingpdtac','=',$Objsavingsproducts->accountcode)->delete();



    }

public function viewcombo(){
    $bra=auth()->user()->branchid;

    return savingsproducts::where('branchno','=',$bra)->get();
}
public function savings($name,$accountcode,$minbal,$interest,$charge,$isActive,$savingpdt,$intpdt,$account,$method,$freq,$datecomp,$nodays){
    $Objsavingsproducts=new savingsproducts();
    $branch= auth()->user()->branchid;
   // $Objsavingsproducts->id=$request['id'];
    $Objsavingsproducts->name=$name;
    $Objsavingsproducts->accountcode=$accountcode;
    $Objsavingsproducts->minbal=$minbal;
    $Objsavingsproducts->interest=$interest;
    $Objsavingsproducts->charge=$charge;
    $Objsavingsproducts->isActive=$isActive;
    $Objsavingsproducts->intmethod=$method;
    $Objsavingsproducts->freq=$freq;
    $Objsavingsproducts->branchno=$branch;
    $Objsavingsproducts->dayofpost=$datecomp;
    $Objsavingsproducts->sitonaccount=$nodays;
    $Objsavingsproducts->save();
    // posting in status table 
    $objsavingsdefinations= new savingdefinations();
    $objsavingsdefinations->productname=$name;
    $objsavingsdefinations->interest="Interest";
    $objsavingsdefinations->savingpdt=$savingpdt;;
    $objsavingsdefinations->intpdt=$intpdt;
    $objsavingsdefinations->isActive=1;
    $objsavingsdefinations->branchno=$branch;
    $objsavingsdefinations->savingac=$accountcode;
    $objsavingsdefinations->operatingac=$account;
    $objsavingsdefinations->intActive=1;
    $objsavingsdefinations->save();
    // Saving products Last  
    // chart of accounts
    $objchart= new chartofaccounts();
    $objchart->accountcode=$accountcode;
    $objchart->accountname=$name;
    $objchart->accounttype=3;
    $objchart->branchno=$branch;
    $objchart->save();
    //Savings Cals
    $curryear=date("Y"); 
    $currmonth=date("m");
    $nextdate=$curryear."-".$currmonth."-".$datecomp;
    $begindate=date("Y-m-d",strtotime(date("Y-m-d",strtotime($nextdate."-$nodays days"))." $freq months"));

    $objcals= new savingcals();
    $objcals->savingpdt=$savingpdt;
    $objcals->next=$nextdate;
    $objcals->begining=$begindate;
    $objcals->nodays=$nodays;
    $objcals->frekq=$freq;
    $objcals->savingpdtac=$accountcode;
    $objcals->branchno=$branch;
    $objcals->save();
}
}