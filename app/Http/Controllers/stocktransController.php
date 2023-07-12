<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\stocktrans;
use App\accounttrans;
use App\stockbals;
use App\purchaseheaders;
use App\allsavings;

 class stocktransController extends Controller{

public function index(){
    return view('stocktrans/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select('select COUNT(*) as count from stocktrans ');
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from stocktrans limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $stockidentify=0;
    $Objstocktrans=new stocktrans();
$Objstocktrans->id=$request['id'];
//$Objstocktrans->purchaseheaderid=$request['purchaseheaderid'];
//$Objstocktrans->transdate=$request['transdate'];
$Objstocktrans->transdate=date("Y-m-d", strtotime($request['date']));
$Objstocktrans->purchaseheaderid=$request['purcnumber'];
$Objstocktrans->stockname=$request['productid'];
$Objstocktrans->totalamt=$request['totalamt'];
$Objstocktrans->quantity=$request['quantity'];
$Objstocktrans->type="I";
$Objstocktrans->totalpaid=$request['totalpaid'];
$Objstocktrans->totaldue=$request['totaldue'];
$Objstocktrans->buyingrate=$request['buyingrate'];
$Objstocktrans->created_at=$request['created_at'];
$Objstocktrans->updated_at=$request['updated_at'];
$Objstocktrans->save();
$stockidentify=$Objstocktrans->id;
//Inserting into stockbals
if($request['totaldue']>0){
    $stockbals= new stockbals();
    $stockbals->headno=$request['purcnumber'];
    $stockbals->balance=$request['totaldue'];
    $stockbals->save();
    }
// updating stock table
$qty=$request['quantity'];
$name=$request['stockid'];
$branch_id=$request['branch_id'];
DB::update("update stocks set openingstock=openingstock+$qty where name='$name' AND branch_id=$branch_id");

// Accounting 
$Objaccounttrans= new accounttrans();
$Objaccounttrans->accountcode="1113";//Inventory
$Objaccounttrans->narration="Purchase of ".$request['stockid'];
$Objaccounttrans->amount=$request['totalpaid'];
$Objaccounttrans->ttype="D";
$Objaccounttrans->purchaseheaderid=$request['purcnumber'];
$Objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccounttrans->stockidentify=$stockidentify;
$Objaccounttrans->save();
//Debiting remember its a double entry
$Objaccounttrans1= new accounttrans();
$Objaccounttrans1->accountcode=$request['paccount'];//Fixed Account for sale of goods
$Objaccounttrans1->narration="Purchase of ".$request['stockid'];
$Objaccounttrans1->amount=$request['totalpaid'];
$Objaccounttrans1->ttype="C";
$Objaccounttrans1->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccounttrans1->stockidentify=$stockidentify;
$Objaccounttrans1->purchaseheaderid=$request['purcnumber'];
$Objaccounttrans1->save();



//Accounts Recievable
if($request['totaldue']>0){
    $Objaccounttrans2= new accounttrans();
    $Objaccounttrans2->accountcode="1112";//Fixed Account Accounts Payable
    $Objaccounttrans2->narration="Purchase of ".$request['stockid']." on Credit";
    $Objaccounttrans2->amount=$request['totaldue'];
    $Objaccounttrans2->ttype="C";
    $Objaccounttrans2->transdate=date("Y-m-d", strtotime($request['date']));
    $Objaccounttrans2->purchaseheaderid=$request['purcnumber'];
    //$Objaccounttrans2->stockidentify=$stockidentify;
    
    $Objaccounttrans2->save();

    $Objaccounttrans3= new accounttrans();
    $Objaccounttrans3->accountcode="1113";//Inventory Account
    $Objaccounttrans3->narration="Purchase of ".$request['productid']." on Credit";
    $Objaccounttrans3->amount=$request['totaldue'];
    $Objaccounttrans3->ttype="D";
    $Objaccounttrans3->purchaseheaderid=$request['purcnumber'];
    $Objaccounttrans3->credit=1;
    $Objaccounttrans3->transdate=date("Y-m-d", strtotime($request['date']));
    $Objaccounttrans3->save();

}




}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objstocktrans=stocktrans::find($id);

$Objstocktrans->id=$request['id'];
$Objstocktrans->purchaseheaderid=$request['purchaseheaderid'];
$Objstocktrans->transdate=$request['transdate'];
$Objstocktrans->stockid=$request['stockid'];
$Objstocktrans->quantity=$request['quantity'];
$Objstocktrans->totalpaid=$request['totalpaid'];
$Objstocktrans->totaldue=$request['totaldue'];
$Objstocktrans->sellingrate=$request['sellingrate'];
$Objstocktrans->created_at=$request['created_at'];
$Objstocktrans->updated_at=$request['updated_at'];
$Objstocktrans->save();
}
 public function destorys($id){
     
        $Objstocktrans=stocktrans::where('id','=',$id)->delete();
    
        $Objpurchaseheader=purchaseheaders::where('id','=',$id)->delete();
       
        $Objaccounttrans=accounttrans::where('purchaseheaderid','=',$id)->delete();
      



    }

public function viewcombo(){


    return stocktrans::all();
}

public function savesales(Request $request){
    $Objstocktrans=new stocktrans();
    $Objstocktrans->id=$request['id'];
    $stockidentify=0;
//$Objstocktrans->purchaseheaderid=$request['purchaseheaderid'];
$Objstocktrans->transdate=date("Y-m-d", strtotime($request['date']));

$Objstocktrans->purchaseheaderid=$request['purcnumber'];
$Objstocktrans->stockname=$request['productid'];
$Objstocktrans->totalamt=$request['totalamt'];
$Objstocktrans->quantity=$request['quantity'];
$Objstocktrans->type="O";
$Objstocktrans->totalpaid=$request['totalpaid'];
$Objstocktrans->totaldue=$request['totaldue'];
$Objstocktrans->sellingrate=$request['sellingrate'];
$Objstocktrans->created_at=$request['created_at'];
$Objstocktrans->updated_at=$request['updated_at'];
$Objstocktrans->save();
$stockidentify=$Objstocktrans->id;
//Inserting into stockbals
if($request['totaldue']>0){
    $stockbals= new stockbals();
    $stockbals->headno=$request['purcnumber'];
    $stockbals->balance=$request['totaldue'];
    $stockbals->save();
    }
// updating stock table
$qty=$request['quantity'];
$name=$request['stockid'];
$branch_id=$request['branch_id'];
DB::update("update stocks set openingstock=openingstock-$qty where name='$name' AND branch_id=$branch_id");
// Accounting 
$Objaccounttrans= new accounttrans();
$Objaccounttrans->accountcode="1000";//Fixed Account for sale of goods
$Objaccounttrans->narration="Sale of ".$request['stockid'];
$Objaccounttrans->amount=$request['totalpaid'];
$Objaccounttrans->ttype="C";
$Objaccounttrans->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccounttrans->purchaseheaderid=$request['purcnumber'];
$Objaccounttrans->stockidentify=$stockidentify;
$Objaccounttrans->save();
//Debiting remember its a double entry
$Objaccounttrans1= new accounttrans();
$Objaccounttrans1->accountcode=$request['raccount'];//cash account
$Objaccounttrans1->narration="Sale of ".$request['stockid'];
$Objaccounttrans1->amount=$request['totalpaid'];
$Objaccounttrans1->ttype="D";
$Objaccounttrans1->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccounttrans1->purchaseheaderid=$request['purcnumber'];
$Objaccounttrans1->stockidentify=$stockidentify;
$Objaccounttrans1->save();

// Inserting into inventory
$Objaccountinventory= new accounttrans();
$Objaccountinventory->accountcode="1113";//Fixed Account Inventory
$Objaccountinventory->narration="Sale of ".$request['stockid'] ;
$Objaccountinventory->amount=$request['buyingrate']*$request['quantity'];
$Objaccountinventory->ttype="C";
$Objaccountinventory->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccountinventory->purchaseheaderid=$request['purcnumber'];
$Objaccountinventory->save();
// Cost of Goods inserting // Remember its an expense
$Objaccountinventory= new accounttrans();
$Objaccountinventory->accountcode="1001";//Cost of Goods
$Objaccountinventory->narration="Sale of ".$request['stockid'] ;
$Objaccountinventory->amount=$request['buyingrate']*$request['quantity'];
$Objaccountinventory->ttype="D";
$Objaccountinventory->transdate=date("Y-m-d", strtotime($request['date']));
$Objaccountinventory->purchaseheaderid=$request['purcnumber'];
$Objaccountinventory->save();
//Accounts Recievable
if($request['totaldue']>0){
    $Objaccounttrans2= new accounttrans();
    $Objaccounttrans2->accountcode="1111";//Fixed Account Accounts Recievables
    $Objaccounttrans2->narration="Sale of ".$request['stockid']." on Credit";
    $Objaccounttrans2->amount=$request['totaldue'];
    $Objaccounttrans2->ttype="D";
    $Objaccounttrans2->transdate=date("Y-m-d", strtotime($request['date']));
    $Objaccounttrans2->purchaseheaderid=$request['purcnumber'];
    $Objaccounttrans2->stockidentify=$stockidentify;
    $Objaccounttrans2->save();

    $Objaccounttrans3= new accounttrans();
    $Objaccounttrans3->accountcode="1000";//Fixed Account Sale of Goods
    $Objaccounttrans3->narration="Sale of ".$request['stockid']." on Credit";
    $Objaccounttrans3->amount=$request['totaldue'];
    $Objaccounttrans3->ttype="C";
    $Objaccounttrans3->transdate=date("Y-m-d", strtotime($request['date']));
    $Objaccounttrans3->credit=1;
    $Objaccounttrans3->purchaseheaderid=$request['purcnumber'];
    $Objaccounttrans3->stockidentify=$stockidentify;
    $Objaccounttrans3->save();

}


}
public function viewstock($id){
    //return stocktrans::where('purchaseheaderid',$id)->get();
    return DB::select("select stocktrans.id,name,stocks.buyingrate as buying,purchaseheaderid,transdate,stockname,quantity,totalamt,totalpaid,totaldue,stocktrans.sellingrate,stocktrans.buyingrate from stocktrans
    inner join stocks on stocktrans.stockname=stocks.id where purchaseheaderid=$id");
   // return DB::select("select stockname,chartofaccounts.accountname,totalpaid,accounttrans.accountcode,stocktrans.purchaseheaderid from stocktrans inner join accounttrans on accounttrans.purchaseheaderid=stocktrans.purchaseheaderid inner join   purchaseheaders on purchaseheaders.id=accounttrans.purchaseheaderid inner join chartofaccounts on accounttrans.accountcode=chartofaccounts.accountcode  where stocktrans.purchaseheaderid=$id AND ttype='D' group by stocktrans.id");
}

public function stocktransfer(Request $request){
    $objstocktrans1= new stocktrans();
 
    // Removing form the first branch
    $objstocktrans1->stockname=$this->getstockid($request['from'],$request['stockid']);
    $objstocktrans1->quantity=$request['quantity'];
    $objstocktrans1->purchaseheaderid=$request['purcnumber']; 
    $objstocktrans1->type="OT";
    $objstocktrans1->save();

    //saving to the second branch
    $objstocktrans2= new stocktrans();
    $objstocktrans2->stockname=$this->getstockid($request['to'],$request['stockid']);
    $objstocktrans2->quantity=$request['quantity'];
    $objstocktrans2->purchaseheaderid=$request['purcnumber']+1; 
    $objstocktrans2->type="IT";
    $objstocktrans2->save();

    //$headerno=$request['purcnumber'];
}
public function getstockid($branch,$stockcode){
    $stockID=0;
    $results= DB::select("select id from stocks where branch_id=$branch AND stockcode='$stockcode'");
    foreach($results as $rs){
     $stockID=$rs->id;
    }
    return $stockID;


}
public function updaterow(Request $request){
$stocktrans= stocktrans::find($request['id']);
$stocktrans->quantity=$request['quantity'];
$stocktrans->totalamt=$request['totalamt'];
$stocktrans->totalpaid=$request['totalpaid'];
$stocktrans->totaldue=$request['totaldue'];
$stocktrans->sellingrate=$request['sellingrate'];
$stocktrans->transdate=date("Y-m-d", strtotime($request['date']));
$stocktrans->save();
//Updating sales of goods 
$accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('ttype','=','C')->update(['amount'=>$request['totalpaid'],'transdate'=>date("Y-m-d", strtotime($request['date']))]
);
//Updating Cash Account
$accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('ttype','=','D')->update(['amount'=>$request['totalpaid'],'accountcode'=>$request['accountcode'],'transdate'=>date("Y-m-d", strtotime($request['date']))]
);
if($request['totaldue']>0){
    $accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('accountcode','=',1111)->update(['amount'=>$request['totaldue']]);

}
//updating date
$purchaseheadersob=purchaseheaders::find($request['id']);
$purchaseheadersob->transdates=date("Y-m-d", strtotime($request['date']));
$purchaseheadersob->update();
//updating stocks


}

public function updatepurchase(Request $request){
    $stocktrans= stocktrans::find($request['id']);
    $stocktrans->quantity=$request['quantity'];
    $stocktrans->totalamt=$request['totalamt'];
    $stocktrans->totalpaid=$request['totalpaid'];
    $stocktrans->totaldue=$request['totaldue'];
    $stocktrans->buyingrate=$request['buyingrate'];
    $stocktrans->transdate=date("Y-m-d", strtotime($request['date']));
    $stocktrans->save();
    //Updating sales of goods 
    $accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('ttype','=','D')->update(['amount'=>$request['totalpaid'],'transdate'=>date("Y-m-d", strtotime($request['date']))]
    );
    //Updating Cash Account
    $accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('ttype','=','C')->update(['amount'=>$request['totalpaid'],'accountcode'=>$request['accountcode'],'transdate'=>date("Y-m-d", strtotime($request['date']))]
    );
    if($request['totaldue']>0){
        $accounttrans=accounttrans::where('stockidentify','=',$request['id'])->where('accountcode','=',1111)->update(['amount'=>$request['totaldue']]);
    
    }
    
    
    }

public function deletesales(Request $request){
    // Deleting from stocktable
    $id=$request['id'];
    $stocktransobj=stocktrans::find($id);
    $stocktransobj->delete();
    // Deleting from accountrans table
    $accountransobj=accounttrans::where('stockidentify',$id)->delete();



}
public function delxp($id){
    DB::beginTransaction();
    try{
    
    
        $del=accounttrans::where('id','=',$id)->get();
    foreach($del as $d){
        allsavings::where('headerid','=',$d->purchaseheaderid)->delete();
        
    }
    accounttrans::where('id','=',$id)->delete();
       
   accounttrans::where('id','=',$id+1)->delete();

    }catch(\Exception $e){
        DB::rollBack();
        echo "Failed ".$e;
    }
   DB::commit();
}


}