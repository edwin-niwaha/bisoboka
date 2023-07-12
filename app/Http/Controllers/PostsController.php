<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\posts;

 class postsController extends Controller{

public function index(){
    return view('posts/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from posts ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from posts limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objposts=new posts();
$Objposts->id=$request['id'];
$Objposts->created_at=$request['created_at'];
$Objposts->updated_at=$request['updated_at'];
$Objposts->Title=$request['Title'];
$Objposts->Posts=$request['Posts'];
$Objposts->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objposts=posts::find($id);

$Objposts->id=$request['id'];
$Objposts->created_at=$request['created_at'];
$Objposts->updated_at=$request['updated_at'];
$Objposts->Title=$request['Title'];
$Objposts->Posts=$request['Posts'];
$Objposts->save();
}
 public function destroy($id){
        $Objposts=posts::find($id);
        $Objposts->delete();



    }

public function viewcombo(){


    return posts::all();
}
}