<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\customers;
use App\audits;

 class customersController extends Controller{

public function index(){
    return view('customers/index');
}
public function view(){
$bra=auth()->user()->branchid;
if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['findname'])){
    $memid=$_GET['findname'];
    $results=array();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $offset = ($page-1)*$rows;
    $rs =DB::getPdo();
    $krows = DB::select("select COUNT(*) as count from customers where branchnumber=$bra and customers.id=$memid");
    $results['total']=$krows[0]->count;
    $rst =  DB::getPdo()->prepare("select *,DATE_FORMAT(registrationdate,'%d-%m-%Y') registrationdate,DATE_FORMAT(witnessdate,'%d-%m-%Y') witnessdate,DATE_FORMAT(dob,'%d-%m-%Y')  as dob,DATE_FORMAT(kindob,'%d-%m-%Y') as kindob from customers where branchnumber=$bra and customers.id=$memid order by name,acno asc limit $offset,$rows");
    $rst->execute();

    $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
   $results['rows']=$viewall;
   echo json_encode($results);

}else{
        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from customers where branchnumber=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select *,DATE_FORMAT(registrationdate,'%d-%m-%Y') registrationdate,DATE_FORMAT(witnessdate,'%d-%m-%Y') witnessdate,DATE_FORMAT(dob,'%d-%m-%Y')  as dob,DATE_FORMAT(kindob,'%d-%m-%Y') as kindob from customers where branchnumber=$bra order by acno asc limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);
}

    
}
public function save(Request $request){
    DB::beginTransaction();
    try{
        $acno=$request['acno'];
$checkacno=DB::select("select * from customers where acno='$acno'");
 if(count($checkacno)==0){
    $Objcustomers=new customers();
//$Objcustomers->id=$request['id'];
$Objcustomers->name=$request['name'];
$Objcustomers->sex=$request['sex'];
$Objcustomers->dob=date("Y-m-d", strtotime($request['dob']));
$Objcustomers->address=$request['address'];
$Objcustomers->telephone1=$request['telephone1'];
$Objcustomers->telephone2=$request['telephone2'];
$Objcustomers->email=$request['email'];
$Objcustomers->district=$request['district'];
$Objcustomers->subcounty=$request['subcounty'];
$Objcustomers->parish=$request['parish'];
$Objcustomers->village=$request['village'];
$Objcustomers->religion=$request['religion'];
$Objcustomers->church=$request['church'];
$Objcustomers->education=$request['education'];
$Objcustomers->occupation=$request['occupation'];
$Objcustomers->marital=$request['marital'];
$Objcustomers->nochildren=$request['nochildren'];
$Objcustomers->namechildren=$request['namechildren'];
$Objcustomers->kinname=$request['kinname'];
$Objcustomers->kinsex=$request['kinsex'];
$Objcustomers->kindob=date("Y-m-d", strtotime($request['kindobs']));
$Objcustomers->kinoccupation=$request['kinoccupation'];
$Objcustomers->contactadd=$request['contactadd'];
$Objcustomers->kinemail=$request['kinemail'];
$Objcustomers->kintelephone1=$request['kintelephone1'];
$Objcustomers->kintelephone2=$request['kintelephone2'];
$Objcustomers->witnessname=$request['witnessname'];
$Objcustomers->witnessdate=date("Y-m-d", strtotime($request['witnessdate']));
$Objcustomers->registrationdate=date("Y-m-d", strtotime($request['registrationdate']));
$Objcustomers->acno=$request['acno'];
$Objcustomers->branchnumber=auth()->user()->branchid;
$Objcustomers->isActive=1;
$Objcustomers->created_at=$request['created_at'];
$Objcustomers->updated_at=$request['updated_at'];
$Objcustomers->save();

$objaudits= new audits();
$objaudits->event="Client Entry -".$request['name'];
$objaudits->branchno=auth()->user()->branchid;
$objaudits->username=auth()->user()->name;
$objaudits->save();
 }else{
     return ['checkacno'=>'exists'];
 }
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
//Auto generated code for updating
public function update(Request $request,$id){
    DB::beginTransaction();
    try{
        $Objcustomers=customers::find($id);

//$Objcustomers->id=$request['id'];
$Objcustomers->name=$request['name'];
$Objcustomers->sex=$request['sex'];
$Objcustomers->dob=date("Y-m-d", strtotime($request['dob']));
$Objcustomers->address=$request['address'];
$Objcustomers->telephone1=$request['telephone1'];
$Objcustomers->telephone2=$request['telephone2'];
$Objcustomers->email=$request['email'];
$Objcustomers->district=$request['district'];
$Objcustomers->subcounty=$request['subcounty'];
$Objcustomers->parish=$request['parish'];
$Objcustomers->village=$request['village'];
$Objcustomers->religion=$request['religion'];
$Objcustomers->church=$request['church'];
$Objcustomers->education=$request['education'];
$Objcustomers->occupation=$request['occupation'];
$Objcustomers->marital=$request['marital'];
$Objcustomers->nochildren=$request['nochildren'];
$Objcustomers->namechildren=$request['namechildren'];
$Objcustomers->kinname=$request['kinname'];
$Objcustomers->kinsex=$request['kinsex'];
$Objcustomers->kindob=date("Y-m-d", strtotime($request['kindobs']));
$Objcustomers->kinoccupation=$request['kinoccupation'];
$Objcustomers->contactadd=$request['contactadd'];
$Objcustomers->kinemail=$request['kinemail'];
$Objcustomers->kintelephone1=$request['kintelephone1'];
$Objcustomers->kintelephone2=$request['kintelephone2'];
$Objcustomers->witnessname=$request['witnessname'];
$Objcustomers->witnessdate=date("Y-m-d", strtotime($request['witnessdate']));
$Objcustomers->registrationdate=date("Y-m-d", strtotime($request['registrationdate']));
$Objcustomers->acno=$request['acno'];
$Objcustomers->branchnumber=auth()->user()->branchid;
$Objcustomers->isActive=1;
$Objcustomers->created_at=$request['created_at'];
$Objcustomers->updated_at=$request['updated_at'];
$Objcustomers->save();
    }catch(\Exception $e){
        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
 public function destroy($id){
        $Objcustomers=customers::find($id);
       $client= DB::select("select sum(if(loanint1<0,abs(loanint1),0))+sum(if(loanpdt1<0,abs(loanpdt1),0))+sum(ansub)+sum(memship)+if(savingpdt1>0,sum(savingpdt1),0)+sum(if(shares>0,shares,0)) as total  from allsavings inner join customers on customers.id=allsavings.client_no  where customers.id=$id ");
       foreach($client as $cli){
           if($cli->total>0){
 return ['isdelete'=>'No'];
           }else{
            $Objcustomers->delete();
           }
       }
        



    }

public function viewcombo(){


    $bra=auth()->user()->branchid;

        return  DB::select("select customers.id as id, concat(customers.name,'--',acno) as name  from customers where branchnumber=$bra group by customers.id order by name asc");
}
public function importnames(Request $request){
    DB::beginTransaction();
    try{
    $file=$request->file('files');
    $destinationPath="images";
    if($file!=Null){
        $filename=$file->getClientOriginalName();
        //moving it to the folder
        $finalfile=$file->move($destinationPath,$filename);
        
        $handle = fopen($finalfile, "r");
while(($data=fgetcsv($handle,1000,","))!==FALSE  ){
    $bra=auth()->user()->branchid;
    $customers = new customers();
    $customers->name=$data[0];
    $customers->acno=$data[1];
    $customers->branchnumber=auth()->user()->branchid;
    $customers->save();


}
    }


    }catch(\Exception $e){

        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
public function customerPreview(){

    $branch=auth()->user()->branchid;
    $company=DB::select("select * from companys where id=$branch");
    $customers=DB::select("select name,acno,telephone1,telephone2 from customers where branchnumber=$branch order by acno asc");
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML(view('customerPreviews/index')->with('company',$company)->with('customers',$customers));
   return $pdf->stream();
}
}