<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\purchaseheaders;
use App\stocktrans;
use App\loaninfo;
use App\accounttrans;
use App\allsavings;

 class purchaseheadersController extends Controller{

public function index(){
    return view('purchaseheaders/edit');
}

public function salesindex(){

    return view('purchasesales/index');
}
public function isComplete(){
$isComp=DB::select("SELECT count(amount) as count,purchaseheaderid FROM accounttrans WHERE purchaseheaderid NOT IN (SELECT id 
     FROM purchaseheaders)");
          foreach($isComp as $comp){
            accounttrans::where('purchaseheaderid','=',$comp->purchaseheaderid)->delete();
            allsavings::where('headerid','=',$comp->purchaseheaderid)->delete();
           //echo $comp->purchaseheaderi;
        }
     return $isComp;

     

}

public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from purchaseheaders ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from purchaseheaders limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
   /* $objloaninfo= new loaninfo();
    $objloaninfo->isExpense=1;
    $objloaninfo->save();*/


    $Objpurchaseheaders=new purchaseheaders();
//$Objpurchaseheaders->id=$objloaninfo->id;
$Objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
$Objpurchaseheaders->mode=$request['mode'];
$Objpurchaseheaders->supplier_id=$request['supplier_id'];
$Objpurchaseheaders->branch_id=$request['branch_id'];
$Objpurchaseheaders->isActive=1;

$Objpurchaseheaders->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objpurchaseheaders=purchaseheaders::find($id);

$Objpurchaseheaders->id=$request['id'];
$Objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
$Objpurchaseheaders->mode=$request['mode'];
$Objpurchaseheaders->supplier_id=$request['supplier_id'];
$Objpurchaseheaders->customer_id=$request['customer_id'];
$Objpurchaseheaders->branch_id=$request['branch_id'];
$Objpurchaseheaders->isActive=$request['isActive'];
$Objpurchaseheaders->created_at=$request['created_at'];
$Objpurchaseheaders->updated_at=$request['updated_at'];
$Objpurchaseheaders->save();
}
 public function destroy($id){
        $Objpurchaseheaders=purchaseheaders::find($id);
        $Objpurchaseheaders->delete();



    }

public function viewcombo(){


    return purchaseheaders::all();
}

public function maximum(){

  
            return DB::select("SELECT  AUTO_INCREMENT as  id from information_schema.TABLES WHERE TABLE_SCHEMA='bisoboka' AND TABLE_NAME='purchaseheaders'");
           /// return DB::select('select if(max(id) is null,1,max(id)) as id from purchaseheaders');



 
}
public function savesales(Request $request){
    $Objpurchaseheaders= new purchaseheaders();
    $Objpurchaseheaders->id=$request['id'];
    $Objpurchaseheaders->branch_id=$request['branch_id'];
$Objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
$Objpurchaseheaders->mode=$request['mode'];
$Objpurchaseheaders->customer_id=$request['customer_id'];
$Objpurchaseheaders->isActive=$request['isActive'];

$Objpurchaseheaders->save();



}

public function savetransfers(Request $request){
    //post from branch one
 $Objpurchaseheaders= new purchaseheaders();
 $Objpurchaseheaders->id=$request['id'];
 $Objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
 $Objpurchaseheaders->remarks=$request['remarks'];
 $Objpurchaseheaders->save();
 //post from branch two
 $Objpurchaseheaders= new purchaseheaders();
 $Objpurchaseheaders->id=$request['id'];
 $Objpurchaseheaders->transdates=date("Y-m-d", strtotime($request['transdates']));
 $Objpurchaseheaders->branch_id=$request['to'];
 $Objpurchaseheaders->remarks=$request['remarks'];
 $Objpurchaseheaders->save();

}
public function savetransfertrans(Request $request){
$Objstocktrans= new stocktrans();
$Objstocktrans->stockname=$request['stockid'];
$Objstocktrans->quantity=$request['quantity'];
$Objstocktrans->type="IT";


}
}