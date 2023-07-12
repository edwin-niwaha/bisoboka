<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\savingdefinations;

 class savingdefinationsController extends Controller{

public function index(){
    return view('savingdefinations/index');
}
public function view(){
       $branch= auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from savingdefinations  where branchno=$branch");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select productname,id,interest,isActive,intActive, if(isActive=1,'Yes','No') as active,if(intActive=1,'Yes','No') as iactive from savingdefinations where branchno=$branch limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
    $Objsavingdefinations=new savingdefinations();
$Objsavingdefinations->id=$request['id'];
$Objsavingdefinations->intActive=$request['intActive'];
$Objsavingdefinations->isActive=$request['isActive'];

$Objsavingdefinations->save();
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
        $Objsavingdefinations=savingdefinations::find($id);
        $Objsavingdefinations->intActive=$request['intActive'];
$Objsavingdefinations->isActive=$request['isActive'];

$Objsavingdefinations->save();
}catch(\Exception $e){
    DB::rollBack();
    echo "Failed ".$e;
}
DB::commit();
}
 public function destroy($id){
     DB::beginTransaction();
     try{
        $Objsavingdefinations=savingdefinations::find($id);
        $Objsavingdefinations->delete();
    }catch(\Exception $e){
        DB::rollBack();
        echo "Failed ".$e;
    }
    DB::commit();


    }

public function viewcombo(){

    $branch=auth()->user()->branchid;
    return DB::select("select savingpdt,productname from savingdefinations where savingac!=301 and savingac!=401 and savingac!=603 and savingac!=604 and isActive=1 and branchno=$branch");
}
}