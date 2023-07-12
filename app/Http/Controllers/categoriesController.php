<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\categories;

 class categoriesController extends Controller{

public function index(){
    return view('categories/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from categories ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from categories limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objcategories=new categories();
$Objcategories->id=$request['id'];
$Objcategories->name=$request['name'];
$Objcategories->isActive=$request['isActive'];
$Objcategories->created_at=$request['created_at'];
$Objcategories->updated_at=$request['updated_at'];
$Objcategories->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objcategories=categories::find($id);

$Objcategories->id=$request['id'];
$Objcategories->name=$request['name'];
$Objcategories->isActive=$request['isActive'];
$Objcategories->created_at=$request['created_at'];
$Objcategories->updated_at=$request['updated_at'];
$Objcategories->save();
}
 public function destroy($id){
        $Objcategories=categories::find($id);
        $Objcategories->delete();



    }

    public function viewcombo(){

   return categories::all();

    }
}