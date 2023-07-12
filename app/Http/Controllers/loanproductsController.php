<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\loanproducts;
use App\chartofaccounts;

 class loanproductsController extends Controller{

public function index(){
    return view('loanproducts/index');
}
public function view(){
        $branch=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from loanproducts where branchno=$branch ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select loanproducts.id as id,loanproducts.name as name,loanproducts.accountcode,disbursingac,interest,loanproducts.isActive,if(loanproducts.isActive=1,'Yes','No') as active ,chartofaccounts.accountname as disname  from loanproducts inner join chartofaccounts on loanproducts.disbursingac=chartofaccounts.accountcode where loanproducts.branchno=$branch  group by loanproducts.id limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
        $product=array();
        $branch=auth()->user()->branchid;
        $loanproducts=DB::select("select * from loanproducts where branchno=$branch"); 
        
        foreach($loanproducts as $loan){
        $product[]=$loan->loanpdt;
        }
        $x=0;
        $acode=DB::select("select accountcode from chartofaccounts where accountcode='$request[accountcode]' AND branchno=$branch");

        if(count($acode)>0){
          return ['accountcode'=>true];
          
        }else{
        while($x<=1){
        if(in_array('loanpdt1',$product)==false){
        $this->savingpdts($request['name'],$request['accountcode'],$request['disbursingac'],$request['interest'],'loanpdt1','loanint1',$request['isActive']);
        break;
        }
        else if(in_array('loanpdt2',$product)==false){
        
            $this->savingpdts($request['name'],$request['accountcode'],$request['disbursingac'],$request['interest'],'loanpdt2','loanint2',$request['isActive']);
            break;
            }
            else if(in_array('loanpdt3',$product)==false){
                $this->savingpdts($request['name'],$request['accountcode'],$request['disbursingac'],$request['interest'],'loanpdt3','loanint3',$request['isActive']);
                break;
                }
                else if(in_array('loanpdt4',$product)==false){
                    $this->savingpdts($request['name'],$request['accountcode'],$request['disbursingac'],$request['interest'],'loanpdt4','loanint4',$request['isActive']);
                    break;
                    }
                    else if(in_array('loanpdt5',$product)==false){
                        $this->savingpdts($request['name'],$request['accountcode'],$request['disbursingac'],$request['interest'],'loanpdt5','loanint5',$request['isActive']);
                        break;
                        }else{
                        return ['limit'=>true];
                        }
                        $x++;
        }

    }
}catch(\Exception $e){
    DB::rollBack();
    echo "Failed ".$e;
}
DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    $branch=auth()->user()->branchid;
        $Objloanproducts=loanproducts::find($id);
        chartofaccounts::where('accountcode','=',$Objloanproducts->accountcode)->where('branchno','=',$branch)->update([
       'accountname'=>$request['name'],
        ]);

$Objloanproducts->name=$request['name'];

$Objloanproducts->interest=$request['interest'];
$Objloanproducts->disbursingac=$request['disbursingac'];
$Objloanproducts->isActive=$request['isActive'];
//$Objloanproducts->loanpdt=$request['loanpdt'];
$Objloanproducts->created_at=$request['created_at'];
$Objloanproducts->updated_at=$request['updated_at'];
$Objloanproducts->save();
}
 public function destroy($id){
    $branch=auth()->user()->branchid;
        $Objloanproducts=loanproducts::find($id);
        chartofaccounts::where('accountcode','=',$Objloanproducts->accountcode)->where('branchno','=',$branch)->delete();
        $Objloanproducts->delete();



    }

public function viewcombo(){
    $bra=auth()->user()->branchid;

    return loanproducts::where('branchno','=',$bra)->get();
}

public function savingpdts($name,$accountcode,$disbursingac,$interest,$loanpdt,$intpdt,$isActive){
   
 $Objloanproducts=new loanproducts();
$Objloanproducts->name=$name;
$Objloanproducts->accountcode=$accountcode;
$Objloanproducts->disbursingac=$disbursingac;
$Objloanproducts->interest=$interest;
$Objloanproducts->loanpdt=$loanpdt;
$Objloanproducts->intpdt=$intpdt;
$Objloanproducts->isActive=$isActive;
$Objloanproducts->branchno=auth()->user()->branchid;
$Objloanproducts->save();
$Objcharts= new chartofaccounts();
$Objcharts->accountcode=$accountcode;
$Objcharts->accountname=$name;
$Objcharts->accounttype=1;
$Objcharts->branchno=auth()->user()->branchid;
$Objcharts->isActive=1;
$Objcharts->save();


}
}