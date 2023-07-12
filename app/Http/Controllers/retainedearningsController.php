<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\retainedearnings;

 class retainedearningsController extends Controller{

public function index(){
    return view('retainedearnings/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from retainedearnings ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from retainedearnings limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objretainedearnings=new retainedearnings();
$Objretainedearnings->id=$request['id'];
$Objretainedearnings->amount=$request['amount'];
$Objretainedearnings->year=$request['year'];
$Objretainedearnings->created_at=$request['created_at'];
$Objretainedearnings->updated_at=$request['updated_at'];
$Objretainedearnings->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objretainedearnings=retainedearnings::find($id);

$Objretainedearnings->id=$request['id'];
$Objretainedearnings->amount=$request['amount'];
$Objretainedearnings->year=$request['year'];
$Objretainedearnings->created_at=$request['created_at'];
$Objretainedearnings->updated_at=$request['updated_at'];
$Objretainedearnings->save();
}
 public function destroy($id){
        $Objretainedearnings=retainedearnings::find($id);
        $Objretainedearnings->delete();



    }

public function viewcombo(){


    return retainedearnings::all();
}
public function incomestatepdf($date1,$date2,$day1,$day2){
    $date1=date("Y-m-d", strtotime($date1));
    $date2=date("Y-m-d", strtotime($date2));
    $thedate='';
    $asof='';
    if($day1=="Income" && $day2=="Income"){
        
        $asof= "As of ".date("d-M-Y");

    }
    else if($day1!=''  && $day2=='Income'){
        $date=date("Y-m-d", strtotime($day1)); 
        $thedate=" and transdate <='$date'";
        $asof="As of ".$day1;
    }
    else if($day1!='' && $day2!=''){
        $date1=date("Y-m-d", strtotime($day1));
        $date2=date("Y-m-d", strtotime($day2));
        $thedate=" and transdate between '$date1' and '$date2'";
        $asof="From ".$day1." To ".$day2;

    }

    $branch=auth()->user()->branchid;
    $totalincome=DB::select("select if(amount is Null,0,format(sum(amount),0)) as amount,ttype,accountname,bracid,accountcode from incomepdfs where bracid=$branch and transdate between '$date1' AND '$date2' $thedate  ");
    $income=DB::select("select format(sum(amount),0) as amount,ttype,accountname,bracid,accountcode from incomepdfs where bracid=$branch and transdate between '$date1' AND '$date2' $thedate group by accountcode");
    $totalexpense=DB::select("select  if(amount is Null,0,format(sum(amount),0)) as amount,ttype,accountname,bracid,accountcode from expensepdfs where bracid=$branch and transdate between '$date1' AND '$date2' $thedate");
    $expense=DB::select("select format(sum(amount),0) as amount,ttype,accountname,bracid,accountcode from expensepdfs where bracid=$branch and transdate between '$date1' AND '$date2' $thedate group by accountcode");
    $company=DB::select("select * from companys where id=$branch");
    $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML(view('incomestatepdfs/index')->with('asof',$asof)->with('company',$company)->with('income',$income)->with('totalincome',$totalincome)->with('totalexpense',$totalexpense)->with('expense',$expense));
    return $pdf->stream();
    
}
}