<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\savingcals;

 class savingcalsController extends Controller{

public function index(){
    return view('savingcals/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from savingcals ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from savingcals limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objsavingcals=new savingcals();
$Objsavingcals->id=$request['id'];
$Objsavingcals->savingpdt=$request['savingpdt'];
$Objsavingcals->begining=$request['begining'];
$Objsavingcals->next=$request['next'];
$Objsavingcals->nodays=$request['nodays'];
$Objsavingcals->created_at=$request['created_at'];
$Objsavingcals->updated_at=$request['updated_at'];
$Objsavingcals->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objsavingcals=savingcals::find($id);

$Objsavingcals->id=$request['id'];
$Objsavingcals->savingpdt=$request['savingpdt'];
$Objsavingcals->begining=$request['begining'];
$Objsavingcals->next=$request['next'];
$Objsavingcals->nodays=$request['nodays'];
$Objsavingcals->created_at=$request['created_at'];
$Objsavingcals->updated_at=$request['updated_at'];
$Objsavingcals->save();
}
 public function destroy($id){
        $Objsavingcals=savingcals::find($id);
        $Objsavingcals->delete();



    }

public function viewcombo(){


    return savingcals::all();
}
}