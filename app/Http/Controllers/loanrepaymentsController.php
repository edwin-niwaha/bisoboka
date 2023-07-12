<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\loanrepayments;

 class loanrepaymentsController extends Controller{

public function index(){
    return view('loanrepayments/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from loanrepayments ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from loanrepayments limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objloanrepayments=new loanrepayments();
$Objloanrepayments->id=$request['id'];
$Objloanrepayments->loanid=$request['loanid'];
$Objloanrepayments->loanamount=$request['loanamount'];
$Objloanrepayments->loanrunbal=$request['loanrunbal'];
$Objloanrepayments->interest=$request['interest'];
$Objloanrepayments->intrunbal=$request['intrunbal'];
$Objloanrepayments->runningbal=$request['runningbal'];
$Objloanrepayments->scheduledate=$request['scheduledate'];
$Objloanrepayments->payvalue=$request['payvalue'];
$Objloanrepayments->nopayments=$request['nopayments'];
$Objloanrepayments->created_at=$request['created_at'];
$Objloanrepayments->updated_at=$request['updated_at'];
$Objloanrepayments->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objloanrepayments=loanrepayments::find($id);

$Objloanrepayments->id=$request['id'];
$Objloanrepayments->loanid=$request['loanid'];
$Objloanrepayments->loanamount=$request['loanamount'];
$Objloanrepayments->loanrunbal=$request['loanrunbal'];
$Objloanrepayments->interest=$request['interest'];
$Objloanrepayments->intrunbal=$request['intrunbal'];
$Objloanrepayments->runningbal=$request['runningbal'];
$Objloanrepayments->scheduledate=$request['scheduledate'];
$Objloanrepayments->payvalue=$request['payvalue'];
$Objloanrepayments->nopayments=$request['nopayments'];
$Objloanrepayments->created_at=$request['created_at'];
$Objloanrepayments->updated_at=$request['updated_at'];
$Objloanrepayments->save();
}
 public function destroy($id){
        $Objloanrepayments=loanrepayments::find($id);
        $Objloanrepayments->delete();



    }

public function viewcombo(){


    return loanrepayments::all();
}
}