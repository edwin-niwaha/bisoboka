<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\subcategories;

 class subcategoriesController extends Controller{

public function index(){
    return view('subcategories/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from subcategories ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from subcategories limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objsubcategories=new subcategories();
$Objsubcategories->id=$request['id'];
$Objsubcategories->subname=$request['subname'];
$Objsubcategories->isActive=$request['isActive'];
$Objsubcategories->created_at=$request['created_at'];
$Objsubcategories->updated_at=$request['updated_at'];
$Objsubcategories->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objsubcategories=subcategories::find($id);

$Objsubcategories->id=$request['id'];
$Objsubcategories->subname=$request['subname'];
$Objsubcategories->isActive=$request['isActive'];
$Objsubcategories->created_at=$request['created_at'];
$Objsubcategories->updated_at=$request['updated_at'];
$Objsubcategories->save();
}
 public function destroy($id){
        $Objsubcategories=subcategories::find($id);
        $Objsubcategories->delete();



    }
    public function viewcombo(){

        return subcategories::all();
    }
}