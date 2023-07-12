<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use App\items;

 class itemsController extends Controller{

public function index(){
    

    return view('items/index');
}
public function view(){

    return items::all();
}
public function save(Request $request){
    $Objitems=new items();
$Objitems->id=$request['id'];
$Objitems->itemname=$request['itemname'];
$Objitems->barcodename=$request['barcodename'];
$Objitems->groups=$request['groups'];
$Objitems->category=$request['category'];
$Objitems->uom=$request['uom'];
$Objitems->mfgdate=$request['mfgdate'];
$Objitems->expirydate=$request['expirydate'];
$Objitems->rate=$request['rate'];
$Objitems->vat=$request['vat'];
$Objitems->openingbal=$request['openingbal'];
$Objitems->mvm=$request['mvm'];
$Objitems->isActive=$request['isActive'];
$Objitems->created_at=$request['created_at'];
$Objitems->updated_at=$request['updated_at'];
$Objitems->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objitems=items::find($id);

$Objitems->id=$request['id'];
$Objitems->itemname=$request['itemname'];
$Objitems->barcodename=$request['barcodename'];
$Objitems->groups=$request['groups'];
$Objitems->category=$request['category'];
$Objitems->uom=$request['uom'];
$Objitems->mfgdate=$request['mfgdate'];
$Objitems->expirydate=$request['expirydate'];
$Objitems->rate=$request['rate'];
$Objitems->vat=$request['vat'];
$Objitems->openingbal=$request['openingbal'];
$Objitems->mvm=$request['mvm'];
$Objitems->isActive=$request['isActive'];
$Objitems->created_at=$request['created_at'];
$Objitems->updated_at=$request['updated_at'];
$Objitems->save();
}
 public function destroy($id){
        $Objitems=items::find($id);
        $Objitems->delete();



    }
}