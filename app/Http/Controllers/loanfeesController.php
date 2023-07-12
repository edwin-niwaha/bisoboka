<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\loanfees;
use App\chartofaccounts;

 class loanfeesController extends Controller{

public function index(){
    return view('loanfees/index');
}
public function view(){
       $branch= auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from loanfees where branchno=$branch ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select id,name,code,savingac savgpdt,isPercent,amount,isDeduct,isActive,isSavings,if(isSavings=1,'Yes','No') as saving,if(isActive=1,'Yes','No') as active,if(isDeduct=1,'Yes','No') as deduct,if(isPercent=1,'Yes','No') as percent from loanfees where branchno=$branch limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
        $branch=auth()->user()->branchid;
        $loanfee1=DB::select("select * from loanfees where feevar='loanfee1' AND branchno=$branch");
        $loanfee2=DB::select("select * from loanfees where feevar='loanfee2' AND branchno=$branch ");
        $loanfee3=DB::select("select * from loanfees where feevar='loanfee3' AND branchno=$branch ");
        $chartofaccounts=DB::select("select accountcode from chartofaccounts where accountcode='$request[code]'");
        if(count($chartofaccounts)==0){
        if(count($loanfee1)==0 && count($loanfee2)==0 && count($loanfee3)==0 || count($loanfee2)==0 &&count($loanfee3)==1 &&count($loanfee1)==0 || count($loanfee2)==1 &&count($loanfee3)==1 &&count($loanfee1)==0 || count($loanfee2)==0 &&count($loanfee3)==1 &&count($loanfee1)==0 ){
          $this->savefees("loanfee1",$request['name'],$request['code'],$request['isPercent'],$request['amount'],$request['feevar'],$request['isDeduct'],$request['isSavings'],$request['savgpdt'],$request['isActive']);
        }
        else if(count($loanfee2)==0 && count($loanfee1)==1 && count($loanfee3)==0 || count($loanfee2)==0 &&count($loanfee3)==1 &&count($loanfee1)==1 ){
            $this->savefees("loanfee2",$request['name'],$request['code'],$request['isPercent'],$request['amount'],$request['feevar'],$request['isDeduct'],$request['isSavings'],$request['savgpdt'],$request['isActive']);
          }
          else if(count($loanfee2)==1 && count($loanfee1)==1 && count($loanfee3)==0 || count($loanfee2)==0 && count($loanfee1)==1 && count($loanfee3)==0  || count($loanfee2)==1 && count($loanfee1)==0 && count($loanfee3)==0){
            $this->savefees("loanfee3",$request['name'],$request['code'],$request['isPercent'],$request['amount'],$request['feevar'],$request['isDeduct'],$request['isSavings'],$request['savgpdt'],$request['isActive']);
          }else{
              return ['limit'=>true];
          }
        }else{
            return['chart'=>true];
        }


        


}catch(\Exception $e){
    DB::rollBack();
    echo "Failed ".$e;
}
DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    DB::beginTransaction();
    try{
        $Objloanfees=loanfees::find($id);
        chartofaccounts::where('accountcode','=',$Objloanfees->code)->update([
            'accountname'=>$request['name'],
             ]);
//$Objloanfees->id=$request['id'];
$Objloanfees->name=$request['name'];
//$Objloanfees->code=$request['code'];
$Objloanfees->isPercent=$request['isPercent'];
$Objloanfees->amount=$request['amount'];
$Objloanfees->isSavings=$request['isSavings'];
$Objloanfees->savingac=$request['savgpdt'];
$Objloanfees->isDeduct=$request['isDeduct'];
$Objloanfees->isActive=$request['isActive'];
$Objloanfees->created_at=$request['created_at'];
$Objloanfees->updated_at=$request['updated_at'];
$Objloanfees->save();
    }catch(\Exception $e){
        DB::rollBack();
        echo "Failed ".$e;
    }
    DB::commit();
}
 public function destroy($id){
     DB::beginTransaction();
try{    
        
        $Objloanfees=loanfees::find($id);
        chartofaccounts::where('accountcode','=',$Objloanfees->code)->delete();
        $Objloanfees->delete();
    }catch(\Exception $e){
        DB::rollBack();
        echo "Failed ".$e;
    }
    DB::commit();


    }

public function viewcombo(){


    return loanfees::all();
}
public function savefees($loanfee,$name,$code,$isPercent,$amount,$feevar,$isDeduct,$isSavings,$savgpdt,$isActive){
    $Objloanfees=new loanfees();
    $Objloanfees->name=$name;
    $Objloanfees->code=$code;
    $Objloanfees->isPercent=$isPercent;
    $Objloanfees->amount=$amount;
    $Objloanfees->feevar=$loanfee;
    $Objloanfees->isDeduct=$isDeduct;
    $Objloanfees->isSavings=$isSavings;
    $Objloanfees->isActive=$isActive;
    $Objloanfees->savingac=$savgpdt;
    $Objloanfees->branchno=auth()->user()->branchid;
    $Objloanfees->save();
    $objchart= new chartofaccounts();
    $objchart->accountname=$name;
    $objchart->accountcode=$code;
    $objchart->branchno=auth()->user()->branchid;
    $objchart->accounttype=6;
    $objchart->save();
}
}