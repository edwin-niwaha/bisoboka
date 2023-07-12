<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\sharesettings;

 class sharesettingsController extends Controller{

public function index(){
    return view('sharesettings/index');
}
public function view(){
    $bra=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from sharesettings where branchno=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from sharesettings where branchno=$bra limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objsharesettings=new sharesettings();
$Objsharesettings->id=$request['id'];
$Objsharesettings->shareprice=$request['shareprice'];
$Objsharesettings->branchno=$request['branchno'];
$Objsharesettings->created_at=$request['created_at'];
$Objsharesettings->updated_at=$request['updated_at'];
$Objsharesettings->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objsharesettings=sharesettings::find($id);

//$Objsharesettings->id=$request['id'];
$Objsharesettings->shareprice=$request['shareprice'];
$Objsharesettings->branchno=auth()->user()->branchid;
$Objsharesettings->save();
}
 public function destroy($id){
        $Objsharesettings=sharesettings::find($id);
        $Objsharesettings->delete();



    }

public function viewcombo(){


    return sharesettings::all();
}
}