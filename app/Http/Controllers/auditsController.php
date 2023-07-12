<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\audits;

 class auditsController extends Controller{

public function index(){
    return view('audits/index');
}
public function view(){
    if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])  && empty($_GET['username']) ){
      
        $this->viewaudits("");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2'])  && empty($_GET['username']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->viewaudits(" where created_at <='$date1'");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])  && empty($_GET['username']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $this->viewaudits("where created_at BETWEEN '$date1' AND '$date2' ");

     }
    else if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2'])  && !empty($_GET['username']) ){
      $name=$_GET['username'];
        $this->viewaudits("where username='$name' ");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2'])  && !empty($_GET['username']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $name=$_GET['username'];
        $this->viewaudits(" where created_at <='$date1' and username='$name' ");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2'])  && !empty($_GET['username']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $name=$_GET['username'];
        $this->viewaudits("where created_at BETWEEN '$date1' AND '$date2' and username='$name' ");

     }


    
}
public function viewaudits($where){
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from audits  $where ");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select id, event,username,DATE_FORMAT(created_at,'%d-%m-%Y  %H:%i:%s' ) as created_at from audits  $where limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   echo json_encode($results);
}
public function save(Request $request){
    $Objaudits=new audits();
$Objaudits->id=$request['id'];
$Objaudits->tdate=$request['tdate'];
$Objaudits->event=$request['event'];
$Objaudits->branchno=$request['branchno'];
$Objaudits->username=$request['username'];
$Objaudits->created_at=$request['created_at'];
$Objaudits->updated_at=$request['updated_at'];
$Objaudits->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objaudits=audits::find($id);

$Objaudits->id=$request['id'];
$Objaudits->tdate=$request['tdate'];
$Objaudits->event=$request['event'];
$Objaudits->branchno=$request['branchno'];
$Objaudits->username=$request['username'];
$Objaudits->created_at=$request['created_at'];
$Objaudits->updated_at=$request['updated_at'];
$Objaudits->save();
}
 public function destroy($id){
        $Objaudits=audits::find($id);
        $Objaudits->delete();



    }

public function viewcombo(){


    return audits::all();
}
public function usernamecombo(){
    return DB::select("select name from users where id!=1");
}
}