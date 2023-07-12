<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\companys;
use App\users;
use App\chartofaccounts;
use App\savingdefinations;
use App\retainedearnings;
use App\purchaseheaders;
use App\accounttrans;

 class companysController extends Controller{

public function index(){
    return view('companys/index');
}
public function view(){

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $bra=auth()->user()->branchid;
        $krows = DB::select("select COUNT(*) as count from companys where id=$bra ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select * from companys where id=$bra limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);

    
}
public function save(Request $request){
    $Objcompanys=new companys();
$Objcompanys->id=$request['id'];
$Objcompanys->name=$request['name'];
$Objcompanys->location=$request['location'];
$Objcompanys->boxno=$request['boxno'];
$Objcompanys->phone=$request['phone'];
$Objcompanys->email=$request['email'];
$Objcompanys->created_at=$request['created_at'];
$Objcompanys->updated_at=$request['updated_at'];
$Objcompanys->save();
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objcompanys=companys::find($id);
        $file=$request->file('logo');
        $destinationPath="images";
        if($file!=Null){
            $filename=$file->getClientOriginalName();
            //moving it to the folder
            $file->move($destinationPath,$filename);
            $Objcompanys->logo=$filename;
    
        }
//$Objcompanys->id=$request['id'];
$Objcompanys->name=$request['name'];
$Objcompanys->location=$request['location'];
$Objcompanys->boxno=$request['boxno'];
$Objcompanys->phone=$request['phone'];
$Objcompanys->email=$request['email'];
$Objcompanys->created_at=$request['created_at'];
$Objcompanys->updated_at=$request['updated_at'];
$Objcompanys->save();
}
 public function destroy($id){
        $Objcompanys=companys::find($id);
        $Objcompanys->delete();



    }

public function viewcombo(){


    return companys::all();
}

public function createcompany(Request $request){
    $validator=\Validator::make($request->all(),[
        'name'=>'required',
        'location'=>'required',
        'phone'=>'required',
        'email'=>'required',
        'phone'=>'required'
    ]);
    if($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput();
    }
    $this->validate($request, [
        
        'email'   => 'required|email',
        
       // 'password'  => 'min:6|required_with:password_confirmation|same:confirm',
        'password' => 'required|confirmed|min:6'
       ]);
       DB::beginTransaction();
       try{

$Objcompanys=new companys();
$Objcompanys->name=$request['name'];
$Objcompanys->location=$request['location'];
$Objcompanys->boxno=$request['boxnumber'];
$Objcompanys->phone=$request['phone'];
$Objcompanys->email=$request['email'];
$Objcompanys->save();

$objusers= new users();
$objusers->name=$request['contactname'];
$objusers->email=$request['email'];
$objusers->password=bcrypt($request->password);
$objusers->branchid=$Objcompanys->id;
$objusers->branchname=$request['name'];
$objusers->admin=1;
$objusers->save();

$chart= new chartofaccounts();
$chart->accountcode="21";
$chart->accountname="Cash at Hand";
$chart->accounttype=1;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save();
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="21";
$account->ttype="D";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save(); 

$chart= new chartofaccounts();
$chart->accountcode="7100";
$chart->accountname="Capital";
$chart->accounttype=5;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="7100";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

########## MEMBERSHIP FEES ###############
$chart= new chartofaccounts();
$chart->accountcode="604";
$chart->accountname="Membership Fees";
$chart->accounttype=6;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="604";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();
#####################

########## ANNUAL SUB  ###############
$chart= new chartofaccounts();
$chart->accountcode="603";
$chart->accountname="Annual Subscription";
$chart->accounttype=6;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="603";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();
#####################

$chart= new chartofaccounts();
$chart->accountcode="3700";
$chart->accountname="Petty Cash";
$chart->accounttype=1;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="3700";
$account->ttype="D";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

// Savings Interest Expense 
$chart= new chartofaccounts();
$chart->accountcode="701";
$chart->accountname="Savings Interest Expenses";
$chart->accounttype=7;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="701";
$account->ttype="D";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();
// Savings Interest Liablity
$chart= new chartofaccounts();
$chart->accountcode="4300";
$chart->accountname="Savings Interest Payable";
$chart->accounttype=3;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="4300";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

$chart= new chartofaccounts();
$chart->accountcode="4000";
$chart->accountname="Loan Interest Income";
$chart->accounttype=6;
$chart->branchno=$Objcompanys->id;
$chart->isActive=6;
$chart->save();
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="4000";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

$chart= new chartofaccounts();
$chart->accountcode="5000";
$chart->accountname="Loan Interest Recievable";
$chart->accounttype=1;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save();
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="5000";
$account->ttype="D";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

$chart= new chartofaccounts();
$chart->accountcode="6003";
$chart->accountname="Withdraw Fees";
$chart->accounttype=6;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save(); 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="6003";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

$chart= new chartofaccounts();
$chart->accountcode="7000";
$chart->accountname="Shares";
$chart->accounttype=5;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save();
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="7000";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();
// Retained Earning
$chart= new chartofaccounts();
$chart->accountcode="9000";
$chart->accountname="Retained Earning";
$chart->accounttype=5;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save();
// Fixed Deposits
$chart= new chartofaccounts();
$chart->accountcode="8000";
$chart->accountname="Fixed Deposits";
$chart->accounttype=3;
$chart->branchno=$Objcompanys->id;
$chart->isActive=1;
$chart->save();
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="8000";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();

//Definations
$Objdefinations= new savingdefinations();
$Objdefinations->productname="Share Amt";
$Objdefinations->interest="Interest"; 
$Objdefinations->savingpdt="shares";
$Objdefinations->operatingac="21";
$Objdefinations->savingac="7000";
$Objdefinations->isActive=0;
$Objdefinations->intActive=0;
$Objdefinations->branchno=$Objcompanys->id;
// Fixed deposits
$Objdefinations->save();
$Objdefinations= new savingdefinations();
$Objdefinations->productname="Fixed Deposits";
$Objdefinations->interest="Interest"; 
$Objdefinations->savingpdt="savingpdt5";
$Objdefinations->intpdt="intpdt5";
$Objdefinations->savingac="8000";
$Objdefinations->isActive=0;
$Objdefinations->intActive=0;
$Objdefinations->branchno=$Objcompanys->id;
$Objdefinations->save();
// Annual Subscription
$Objdefinations= new savingdefinations();
$Objdefinations->productname="Annual Sub";
$Objdefinations->interest=""; 
$Objdefinations->savingpdt="ansub";
$Objdefinations->savingac="603";
$Objdefinations->isActive=0;
$Objdefinations->intActive=0;
$Objdefinations->branchno=$Objcompanys->id;
$Objdefinations->save();
// Membership fees
$Objdefinations= new savingdefinations();
$Objdefinations->productname="Membership";
$Objdefinations->interest=""; 
$Objdefinations->savingpdt="memship";
$Objdefinations->savingac="604";
$Objdefinations->isActive=0;
$Objdefinations->intActive=0;
$Objdefinations->branchno=$Objcompanys->id;
$Objdefinations->save();
// Retained 
// posting to purchase headers;
$objpurchase= new purchaseheaders();
$objpurchase->inter="2";
$objpurchase->branch_id=$Objcompanys->id;
$objpurchase->save();
$account= new accounttrans();
$account->accountcode="9000";
$account->ttype="C";
$account->purchaseheaderid=$objpurchase->id;
$account->bracid=$Objcompanys->id;
$account->cat="isFirst";
$account->save();




       }catch(\Exception $e){
           DB::rollBack();
           echo "Failed ".$e;
       }
       DB::commit();
       return redirect('login')->with('status', 'Company Created Successfully, Please Login !!!');
}
}