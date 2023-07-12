<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\fixeddepositconfigs;

 class fixeddepositconfigsController extends Controller{

public function index(){
    return view('fixeddepositconfigs/index');
}
public function view(){
    $bra=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from fixeddepositconfigs where branchno=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select *, interestrate as interest,accountname, fixeddepositconfigs.id as id from fixeddepositconfigs inner join chartofaccounts on chartofaccounts.accountcode=fixeddepositconfigs.checkingac  where fixeddepositconfigs.branchno=$bra limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $bra=auth()->user()->branchid;
    $Objfixeddepositconfigs=new fixeddepositconfigs();
$Objfixeddepositconfigs->id=$request['id'];
$Objfixeddepositconfigs->term=$request['term'];
$Objfixeddepositconfigs->branchno=$bra;
$Objfixeddepositconfigs->interestrate=$request['interest'];
$Objfixeddepositconfigs->period=$request['period'];
$Objfixeddepositconfigs->checkingac=$request['checkingac'];
$Objfixeddepositconfigs->created_at=$request['created_at'];
$Objfixeddepositconfigs->updated_at=$request['updated_at'];
$Objfixeddepositconfigs->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
    $bra=auth()->user()->branchid;
        $Objfixeddepositconfigs=fixeddepositconfigs::find($id);
        $Objfixeddepositconfigs->interestrate=$request['interest']; 
        $Objfixeddepositconfigs->branchno=$bra; 
//$Objfixeddepositconfigs->id=$request['id'];
$Objfixeddepositconfigs->term=$request['term'];
$Objfixeddepositconfigs->period=$request['period'];
$Objfixeddepositconfigs->checkingac=$request['checkingac'];
$Objfixeddepositconfigs->created_at=$request['created_at'];
$Objfixeddepositconfigs->updated_at=$request['updated_at'];
$Objfixeddepositconfigs->save();
}
 public function destroy($id){
        $Objfixeddepositconfigs=fixeddepositconfigs::find($id);
        $Objfixeddepositconfigs->delete();



    }

public function viewcombo(){

    $bra=auth()->user()->branchid;
  
    return DB::select("select * from fixeddepositconfigs where branchno=$bra");
}
}