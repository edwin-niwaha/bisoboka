<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\stocks;
use App\stocktrans;
use App\purchaseheaders;

 class stocksController extends Controller{
    
public $stkcode=0;
public $branchcode=0;
public function index(){
    return view('stocks/index');
}
public function view(){
    //if(isset($_GET['branch_id'])){
       // $branch=$_GET['branch_id'];
       // $this->stocks("Where branch_id=$branch","branch_id=$branch");
    //}else{
$this->stocks("Where isDefault=1","isDefault=1");
   // }
    
}
public function save(Request $request){
    
    $branch=DB::select('select id from branches');
    $max=DB::select('select count(id) as id from stocks');
    $number=0;
    foreach($max as $k){
$number=$k->id;


    }
    foreach($branch as $r){

      // if($r->id==$request['branch_id']){
           //continue;
        $Objstocks=new stocks();
        $Objstocks->id=$request['id'];
        $Objstocks->name=$request['name'];
        $Objstocks->description=$request['description'];
        $Objstocks->category=$request['category'];
        $Objstocks->subcategory=$request['subcategory'];
        $Objstocks->stockcode=substr($request['name'], 0, 3)."-".$number;
        $this->stkcode=substr($request['name'], 0, 3)."-".$number;
        $Objstocks->branch_id=$r->id;
        if($r->id==$request['branch_id']){
        $Objstocks->openingstock=$request['openingstock'];
        $this->branchcode=$request['branch_id'];

        }
       // $Objstocks->openingstock=$request['openingstock'];
        $Objstocks->limitlevel=$request['limitlevel'];
        $Objstocks->buyingrate=$request['buyingrate'];
        $Objstocks->sellingrate=$request['sellingrate'];
        $Objstocks->unitofmeasure=$request['unitofmeasure'];
        $Objstocks->isActive=$request['isActive'];
        $Objstocks->created_at=$request['created_at'];
        $Objstocks->updated_at=$request['updated_at'];
        $Objstocks->save();

     //}
    }
    


// saving in stocktrans
$Objstocktrans= new stocktrans();
$itmcode=0;
$itemcode=DB::select("select id from stocks where stockcode='$this->stkcode' AND branch_id=$this->branchcode");
foreach($itemcode as $code){
    $this->itmcode=$code->id;
}
$Objstocktrans->quantity=$request['openingstock'];
$Objstocktrans->type="I";
$Objstocktrans->stockname= $this->itmcode;
if($request['purchaseno']==0){
    $Objstocktrans->purchaseheaderid=1;   
}else{
$Objstocktrans->purchaseheaderid=$request['purchaseno'];
}
$Objstocktrans->save();
// Savings in purchaseheaders
$today=date("Y-m-d"); 
$Objpurchaseheader= new purchaseheaders();
$Objpurchaseheader->transdates=$today;
$Objpurchaseheader->branch_id=$request['branch_id'];
$Objpurchaseheader->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objstocks=stocks::find($id);

$Objstocks->id=$request['id'];
$Objstocks->branch_id=$request['branch_id'];
$Objstocks->name=$request['name'];
$Objstocks->description=$request['description'];
$Objstocks->category=$request['category'];
$Objstocks->subcategory=$request['subcategory'];
$Objstocks->openingstock=$request['openingstock'];
$Objstocks->limitlevel=$request['limitlevel'];
$Objstocks->buyingrate=$request['buyingrate'];
$Objstocks->sellingrate=$request['sellingrate'];
$Objstocks->unitofmeasure=$request['unitofmeasure'];
$Objstocks->isActive=$request['isActive'];
$Objstocks->created_at=$request['created_at'];
$Objstocks->updated_at=$request['updated_at'];
$Objstocks->save();
}
 public function destroy($id){
        $Objstocks=stocks::find($id);
        $Objstocks->delete();



    }

    public function viewcombo($id){

        return stocks::where('branch_id',$id)->get();
    }

    public function bybranch(){

        return stocks::all();
    }
    public function viewstocks(){

        return DB::select('select stockcode,name,description,buyingrate,sellingrate from stocks group by stockcode,name,description,buyingrate,sellingrate');
    }
 
    public function stocks($stocks,$limit){
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from stocks inner join branches on stocks.branch_id=branches.id $stocks");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select date,narration,firstname,loandebit from loantrans inner join memberinfos on loantrans.memid=memberinfos.id limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);
}

//importing stock items 
public function importstock(Request $request){

    $branch=DB::select('select id from branches');
    $max=DB::select('select count(id) as id from stocks');
    $number=0;
    foreach($max as $k){
$number=$k->id;


    }
    //Capturing the image
$file=$request->file('files');
$f=fopen('write.txt','w');
fwrite($f,$file=$request->file('files'));
$destinationPath="stockuploads";
$filename=$file->getClientOriginalName();
//moving it to the folder
$finalfile=$file->move($destinationPath,$filename);
$handle = fopen($finalfile, "r");
$a=0;

while(($data=fgetcsv($handle,1000,","))!==FALSE  ){
    
    foreach($branch as $r){
        $purchaseobj= new purchaseheaders();
        $purchaseobj->transdates=date("'Y/m/d'");
        $purchaseobj->branch_id=$data[4];
        $purchaseobj->save();

        // if($r->id==$request['branch_id']){
             //continue;
          $Objstocks=new stocks();
         // $Objstocks->id=$request['id'];
          $Objstocks->name=$data[0];// name
          $Objstocks->description=$data[1];// Description
          $Objstocks->category=$data[2];//category
          $Objstocks->subcategory=$data[3];//subcateory
          $Objstocks->stockcode=substr($data[0], 0, 3)."-".$a;
          $this->stkcode=substr($data[0], 0, 3)."-".$a;
          $Objstocks->branch_id=$r->id;
          if($r->id==$data[4]){
          $Objstocks->openingstock=$data[5];//Opening stock
          $this->branchcode=$data[4];//branch_Id
  
          }
         // $Objstocks->openingstock=$request['openingstock'];
          $Objstocks->limitlevel=$data[6];//Limt Level
          $Objstocks->buyingrate=$data[7];//Buyingrate
          $Objstocks->sellingrate=$data[8];//selling rate
          $Objstocks->unitofmeasure=$data[9];//unit of measure
          $Objstocks->isActive=$data[10];//IsActive
          //$Objstocks->created_at=$request['created_at'];
          //$Objstocks->updated_at=$request['updated_at'];
          $Objstocks->save();
  
       //}
       //Saving into stocktrans;
       $stocktranobj= new stocktrans();
       if($r->id==$data[4]){
       $stocktranobj->stockname=$Objstocks->id;
       $stocktranobj->quantity=$data[5];
       $stocktranobj->type="I";
       $stocktranobj->purchaseheaderid=$purchaseobj->id;
       $stocktranobj->save();
       
       }
       
      }
      $a++;
}



}
}