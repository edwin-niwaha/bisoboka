<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\sharetransfers;
use App\allsavings;
use App\sharesettings;
use App\audits;

 class sharetransfersController extends Controller{

public function index(){
    return view('sharetransfers/index');
}

public function view(){

       if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2']) ){
        $today=date("'Y-m-d'");
      //  $date1=date("Y-m-d", strtotime($_GET['date1']));
       // $date2=date("Y-m-d", strtotime($_GET['date2']));
       // $branch=$_GET['branch'];
        $this->sharetransferview("date=$today");
     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && empty($_GET['date2']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $this->sharetransferview("date <='$date1'");

     }
     else if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2']) ){
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
        $this->sharetransferview("date BETWEEN '$date1' AND '$date2' ");

     }
    
}
public function save(Request $request){
    $bra=auth()->user()->branchid;
    DB::beginTransaction();
    try{
$shared=DB::select("select * from sharesettings where branchno=$bra");
foreach($shared as $shd){
    //echo $shd->shareprice;
   $tshare= DB::select("select sum(shares) as tshares from allsavings where client_no=$request[sharesfrom]");
   foreach($tshare as $sshare){
     if($shd->shareprice*$request['shareno']<$sshare->tshares){
        $Objsharetransfers=new sharetransfers();
        $Objsharetransfers->sharesfrom=$request['sharesfrom'];
        $Objsharetransfers->sharesto=$request['sharesto'];
        $Objsharetransfers->shareno=$request['shareno'];
        $Objsharetransfers->branchno=auth()->user()->branchid;
        $Objsharetransfers->date=date("Y-m-d", strtotime($request['tdate']));
        $Objsharetransfers->save();
        
        $objallsavingsfrom= new allsavings();
        $objallsavingsfrom->date=date("Y-m-d", strtotime($request['tdate']));
        $objallsavingsfrom->client_no=$request['sharesfrom'];
        $objallsavingsfrom->narration="Share Transfer to ".$this->sharename("$request[sharesto]");
        $objallsavingsfrom->branchno=$bra;
        $objallsavingsfrom->recieptno=date("Y-m-d", strtotime($request['tdate']));
        $objallsavingsfrom->shares=$request['shareno']*$shd->shareprice*-1;
        $objallsavingsfrom->sharetransferid=$Objsharetransfers->id;
        $objallsavingsfrom->save();
        // to
        $objallsavingsto= new allsavings();
        $objallsavingsto->date=date("Y-m-d", strtotime($request['tdate']));
        $objallsavingsto->client_no=$request['sharesto'];
        $objallsavingsto->narration="Share Transfer from ".$this->sharename("$request[sharesfrom]");
        $objallsavingsto->branchno=$bra;
        $objallsavingsto->recieptno=date("Y-m-d", strtotime($request['tdate']));
        $objallsavingsto->shares=$request['shareno']*$shd->shareprice;
        $objallsavingsto->sharetransferid=$Objsharetransfers->id;
        $objallsavingsto->save(); 
        
        $objaudits= new audits();
        $objaudits->event="Share Transfer of $request[shareno] from ".$this->sharename("$request[sharesfrom]")." to ".$this->sharename("$request[sharesto]");
        $objaudits->branchno=auth()->user()->branchid;
        $objaudits->username=auth()->user()->name;
        $objaudits->save();
     }else{

return['shares'=>'notenough'];
     }


   }

}
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    $bra=auth()->user()->branchid;
    DB::beginTransaction();
    try{
$shared=DB::select("select * from sharesettings where branchno=$bra");
foreach($shared as $shd){
    //echo $shd->shareprice;
   $tshare= DB::select("select sum(shares) as tshares from allsavings where client_no=$request[sharesfrom]");
   foreach($tshare as $sshare){
     if($shd->shareprice*$request['shareno']>$sshare->tshares){
         return['shares'=>'notenough'];
     }else{

    $Objsharetransfers= sharetransfers::find($id);
$Objsharetransfers->sharesfrom=$request['sharesfrom'];
$Objsharetransfers->sharesto=$request['sharesto'];
$Objsharetransfers->shareno=$request['shareno'];
$Objsharetransfers->branchno=auth()->user()->branchid;
$Objsharetransfers->date=date("Y-m-d", strtotime($request['tdate']));
$Objsharetransfers->save();
allsavings::where('sharetransferid','=',$id)->where('client_no','=',$request['sharesfrom'])->update([
'date'=>date("Y-m-d", strtotime($request['tdate'])),
'shares'=>$request['shareno']*$shd->shareprice*-1,

]);

// to
allsavings::where('sharetransferid','=',$id)->where('client_no','=',$request['sharesto'])->update([
    'date'=>date("Y-m-d", strtotime($request['tdate'])),
    'shares'=>$request['shareno']*$shd->shareprice,
    
    ]);

     }


   }

}
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
 public function destroy($id){
     DB::beginTransaction();
     try{
        $Objsharetransfers=sharetransfers::find($id);
        $Objsharetransfers->delete();
        allsavings::where('sharetransferid','=',$id)->delete();
     }catch(\Exception $e){
         echo "Failed ".$e;
         DB::rollBack();
     }
     DB::commit();

    }

public function viewcombo(){


    return sharetransfers::all();
}
public function sharename($id){
    $t=DB::select("select name from customers where id=$id");
    foreach($t as $d){
        return $d->name;
    }
}
public function sharetransferview($where){
    $bra=auth()->user()->branchid;
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("SELECT COUNT(*) as count  FROM `sharetranferviews`inner join customers on customers.id=sharetranferviews.sharesto where branchno=$bra and $where  ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("SELECT sharesfromview,shareno,sharetranferviews.id,DATE_FORMAT(date,'%d-%m-%Y') as date,branchno,sharesfrom,sharesto,customers.name as sharestoview  FROM `sharetranferviews`inner join customers on customers.id=sharetranferviews.sharesto where branchno=$bra and $where  limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);
}
}