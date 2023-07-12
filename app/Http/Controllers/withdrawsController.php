<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\companynames;
use App\loantrans;
use App\loaninfo;
use App\accounttrans;
use App\purchaseheaders;
use App\savings;
use App\savingdefinations;
use App\allsavings;
use App\savingsproducts;
use App\audits;

 class withdrawsController extends Controller{

public function withdrawsindex(){
    $branch=auth()->user()->branchid;
    $minbal=DB::select("SELECT minbal+charge as minbal,savingpdt FROM `savingsproducts` inner join savingdefinations on savingsproducts.accountcode=savingdefinations.savingac");
    $results=savingdefinations::where('isActive','=',1)->where('branchno','=',$branch)->where('productname','!=','Fixed Deposits')->where('savingac','!=',604)->where('savingac','!=',603)->get();
    return view('savingwithdraws/withdraw')->with('results',$results)->with('minbal',$minbal);
}
public function view(){
    if(isset($_GET['page'])&& isset($_GET['rows']) && empty($_GET['date1']) && empty($_GET['date2']) && empty($_GET['reciept'])){
       
        $today=date("'Y-m-d'");
		
$this->savingview(" and savings.date=$today", "=$today",'','');
    }
    if(isset($_GET['page'])&& isset($_GET['rows'])  && !empty($_GET['date1']) && !empty($_GET['date2']) && empty($_GET['reciept'])){
       
        $date1=date("Y-m-d", strtotime($_GET['date1']));
        $date2=date("Y-m-d", strtotime($_GET['date2']));
      //  $branch=$_GET['branch'];
        $this->savingview("and savings.date  BETWEEN '$date1' AND '$date2'  and savings.narration!='Auto computed Interest' "," BETWEEN '$date1' AND '$date2'","" ,"");
     
     }
     if(isset($_GET['page'])&& isset($_GET['rows'])  && empty($_GET['date1']) && empty($_GET['date2']) && !empty($_GET['branch'])){
       
        $today=date("'Y-m-d'");
        $branch=$_GET['branch'];
        $this->authsavingview("and savings.date=$today and users.branchid=$branch and savings.narration!='Auto computed Interest'");
     
     }
     else if(isset($_GET['page'])&& isset($_GET['rows']) && !empty($_GET['reciept'])){
         $reciept=$_GET['reciept'];
        $this->savingview(" "," ","and recieptno=$reciept","and paydet=$reciept" );   
        //echo "marks";
     } 
 

    
}
public function savewithdraws(Request $request){
    $id=$request['name'];
    $audit="";
    DB::beginTransaction();
try{
    $recp=DB::select("select recieptno from allsavings where recieptno='$request[paymentdetails]' ");
    if(count($recp)>0){
    return ['isExists'=>'yes'];
    }else{
    $branch=auth()->user()->branchid;
    $Name="";
    // Geting member id
$member=DB::select("select name from customers where id=$id");
foreach($member as $mem){
       // Obtaining header number from purchaseheaders table 
       $objheaders= new purchaseheaders();
       $objheaders->transdates=date("Y-m-d", strtotime($request['date']));
       $objheaders->save();

   $objallsavings= new allsavings();
   // saving into savings table for clear statements 
   $objsaving1= new savings();
   $objsaving11= new savings();
   $objsaving2= new savings();
   $objsaving22= new savings();
   $objsaving3= new savings();
   $objsaving33= new savings();
   $objsaving4= new savings();
   $objsaving44= new savings();
   $objsaving5= new savings();
   $objsaving55= new savings();
   $objshareamt= new savings();
   $objallsavings->client_no=$request['name'];
   $objallsavings->branchno=$branch;
   $objallsavings->recieptno=$request['paymentdetails'];
   $objallsavings->date=date("Y-m-d", strtotime($request['date']));
   $narration="";
   if(str_replace( ',', '',$request['savingpdt1'])>0){
####################### START OF SAVING PDT 1 ######################################################
$rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt1'])*-1; 
                    
            }
        }
        $narration= $result->productname;
        $objsaving1->narration="Cash Withdraw-".$result->productname; 
        $objsaving1->branchno=$branch;
        $objsaving11->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving11->branchno=$branch;
        $audit=$mem->name." ".$result->productname. "With draw of $request[savingpdt1] Reciept No: ".$request['paymentdetails'];
        // Accounts 
                    // inserting into accountrans  savings 1 
     $objaccountrans=new accounttrans;
     $objaccountrans->purchaseheaderid=$objheaders->id;
     $objaccountrans->amount=str_replace( ',', '',$request['savingpdt1']);
     $objaccountrans->total=str_replace( ',', '',$request['savingpdt1'])*-1;
     $objaccountrans->accountcode=$result->savingac;
     $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
     $objaccountrans->ttype="D";
     $objaccountrans->bracid=auth()->user()->branchid;
     $objaccountrans->cat='savingpdt1';
     $objaccountrans->stockidentify=$request['paymentdetails'];
     $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
     $objaccountrans->save();
      // inserting into accountrans  cash account 
     $objaccountrans=new accounttrans;
     $objaccountrans->purchaseheaderid=$objheaders->id;
     $objaccountrans->amount=str_replace( ',', '',$request['savingpdt1']);
     $objaccountrans->total=str_replace( ',', '',$request['savingpdt1'])*-1;
     $objaccountrans->accountcode=$result->operatingac;
     $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
     $objaccountrans->ttype="C";
     $objaccountrans->cat='savingpdt1';
     $objaccountrans->stockidentify=$request['paymentdetails'];
     $objaccountrans->bracid=auth()->user()->branchid;
     $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
     $objaccountrans->save();
                 // savings Table 
    $objsaving1->date=date("Y-m-d", strtotime($request['date']));
    $objsaving1->client_no=$request['name'];
    $objsaving1->paydet=$request['paymentdetails'];
    $objsaving1->isCharge=0;
    $objsaving1->branchno=$branch;
    $objsaving1->total=str_replace( ',', '',$request['savingpdt1'])*-1;
    $objsaving1->category='savingpdt1';
    $objsaving1->moneyout=str_replace( ',', '',$request['savingpdt1']);
    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
    // Savings Withdraw Accounts 
    if($savingrs->count()>0){
        foreach($savingrs as $with){
        $objallsavings->savingpdt1=(str_replace( ',', '',$request['savingpdt1'])+$with->charge)*-1;
                         // savings2 Table 
    $objsaving11->date=date("Y-m-d", strtotime($request['date']));
    $objsaving11->client_no=$request['name'];
    $objsaving11->paydet=$request['paymentdetails'];
    $objsaving11->isCharge=1;
    $objsaving11->branchno=$branch;
    $objsaving11->total=$with->charge*-1;
    $objsaving11->category='savingpdt1';
    $objsaving11->moneyout=$with->charge;
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$objheaders->id;
        $objaccountrans->amount=$with->charge;
        $objaccountrans->total=$with->charge;
        $objaccountrans->accountcode="602";
        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
        $objaccountrans->ttype="C";
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->cat='savingpdt1';
        $objaccountrans->stockidentify=$request['paymentdetails'];
        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
        $objaccountrans->save();
         // inserting into accountrans  cash account 
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$objheaders->id;
        $objaccountrans->amount=$with->charge;
        $objaccountrans->total=$with->charge*-1;
        $objaccountrans->accountcode=$result->savingac;
        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
        $objaccountrans->ttype="D";
        $objaccountrans->credit=1;
        $objaccountrans->cat='savingpdt1';
        $objaccountrans->stockidentify=$request['paymentdetails'];
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
        $objaccountrans->save();
        
    }
}
    }

    
####################### END OF SAVING PDT 1 ######################################################
   }else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){  
        $objsaving1->date=date("Y-m-d", strtotime($request['date']));
        $objsaving1->client_no=$request['name'];
        $objsaving1->paydet=$request['paymentdetails'];
        $objsaving1->isCharge=0;
        $objsaving1->branchno=$branch;
        $objsaving1->total=0;
        $objsaving1->category='savingpdt1';
        $objsaving1->moneyout=0;
    // Place holders 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->savingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt1';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    // second place holder 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->operatingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="C";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt1';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();

// incase with charges are incluse 30-09-2020
$savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
// Savings Withdraw Accounts 
if($savingrs->count()>0){
    foreach($savingrs as $with){
    $objallsavings->savingpdt1=0;
                     // savings2 Table 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode="602";
    $objaccountrans->narration="";
    $objaccountrans->ttype="C";
    $objaccountrans->credit=1;
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt1';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
     // inserting into accountrans  cash account 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->savingac;
    $objaccountrans->narration="";
    $objaccountrans->ttype="D";
    $objaccountrans->credit=1;
    $objaccountrans->cat='savingpdt1';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    }
}
    }
   }
   if(str_replace( ',', '',$request['savingpdt2'])>0){
###################### START OF SAVING PDT 2 ####################################################        
    $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $objsaving2->narration="Cash Withdraw-".$result->productname;
        $objsaving2->branchno=$branch;
        $objsaving22->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving22->branchno=$branch;
                                 // savings Table 
    $objsaving2->date=date("Y-m-d", strtotime($request['date']));
    $objsaving2->client_no=$request['name'];
    $objsaving2->paydet=$request['paymentdetails'];
    $objsaving2->isCharge=0;
    $objsaving2->branchno=$branch;
    $objsaving2->total=str_replace( ',', '',$request['savingpdt2'])*-1;
    $objsaving2->category='savingpdt2';
    $objsaving2->moneyout=str_replace( ',', '',$request['savingpdt2']);;
    //
    $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
    foreach($isCharge as $char){
        if($char->charge==0){
            $objallsavings->savingpdt2=str_replace( ',', '',$request['savingpdt2'])*-1; 
                
        }
    }
                    // Accounts 
                    // inserting into accountrans  savings 1 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$request['savingpdt2']);
                    $objaccountrans->total=str_replace( ',', '',$request['savingpdt2'])*-1;
                    $objaccountrans->accountcode=$result->savingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="D";
                    $objaccountrans->cat='savingpdt2';
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->stockidentify=$request['paymentdetails'];
                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                    $objaccountrans->save();
                     // inserting into accountrans  cash account 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$request['savingpdt2']);
                    $objaccountrans->total=str_replace( ',', '',$request['savingpdt2'])*-1;
                    $objaccountrans->accountcode=$result->operatingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="C";
                    $objaccountrans->cat='savingpdt2';
                    $objaccountrans->stockidentify=$request['paymentdetails'];
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                    $objaccountrans->save();

                    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                    // Savings Withdraw Accounts 
                    if($savingrs->count()>0){
                        foreach($savingrs as $with){
                            $objallsavings->savingpdt2=(str_replace( ',', '',$request['savingpdt2'])+$with->charge)*-1;
                            // savings2 Table 
                          $objsaving22->date=date("Y-m-d", strtotime($request['date']));
                          $objsaving22->client_no=$request['name'];
                          $objsaving22->paydet=$request['paymentdetails'];
                          $objsaving22->isCharge=1;
                          $objsaving22->branchno=$branch;
                          $objsaving22->total=$with->charge*-1;
                          $objsaving22->category='savingpdt2';
                          $objsaving22->moneyout=$with->charge;    
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge;
                        $objaccountrans->accountcode="602";
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->cat='savingpdt2';
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                         // inserting into accountrans  cash account 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge*-1;
                        $objaccountrans->credit=1;
                        $objaccountrans->accountcode=$result->savingac;
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="D";
                        $objaccountrans->cat='savingpdt2';
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                        }
                    }

########################### END OF SAVING PDT 2 ################################################## 
    }

   }else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){ 
        $objsaving2->date=date("Y-m-d", strtotime($request['date']));
        $objsaving2->client_no=$request['name'];
        $objsaving2->paydet=$request['paymentdetails'];
        $objsaving2->isCharge=0;
        $objsaving2->total=0;
        $objsaving2->branchno=$branch;
        $objsaving2->category='savingpdt2';
        $objsaving2->moneyout=0;  
    // Place holders 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->savingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt2';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    // second place holder 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->operatingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt2';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    } 
   }
   if(str_replace( ',', '',$request['savingpdt3'])>0){
########################## sTART OF SAVING PDT 3 ##############################################            
    $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt3=str_replace( ',', '',$request['savingpdt3'])*-1; 
                    
            }
        }
        $objsaving33->narration="Cash Withdraw Fees-".$result->productname;
        $narration.=" & ". $result->productname;
        $objsaving3->narration="Cash Withdraw-".$result->productname;
        $objsaving3->date=date("Y-m-d", strtotime($request['date']));
        $objsaving3->client_no=$request['name'];
        $objsaving3->paydet=$request['paymentdetails'];
        $objsaving3->isCharge=0;
        $objsaving3->branchno=$branch;
        $objsaving3->total=str_replace( ',', '',$request['savingpdt3'])*-1;;
        $objsaving3->category='savingpdt3';
        $objsaving3->moneyout=str_replace( ',', '',$request['savingpdt3']);;
                                
// Accounts 
                    // inserting into accountrans  savings 1 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$request['savingpdt3']);
                    $objaccountrans->total=str_replace( ',', '',$request['savingpdt3'])*-1;
                    $objaccountrans->accountcode=$result->savingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="D";
                    $objaccountrans->cat='savingpdt3';
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->stockidentify=$request['paymentdetails'];
                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                    $objaccountrans->save();
                     // inserting into accountrans  cash account 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$request['savingpdt3']);
                    $objaccountrans->total=str_replace( ',', '',$request['savingpdt3'])*-1;
                    $objaccountrans->accountcode=$result->operatingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="C";
                    $objaccountrans->cat='savingpdt3';
                    $objaccountrans->stockidentify=$request['paymentdetails'];
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                    $objaccountrans->save();
                    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                    // Savings Withdraw Accounts 
                    if($savingrs->count()>0){
                        foreach($savingrs as $with){
                                    $objallsavings->savingpdt3=(str_replace( ',', '',$request['savingpdt3'])+$with->charge)*-1;
                         // savings2 Table 
                  $objsaving33->date=date("Y-m-d", strtotime($request['date']));
                  $objsaving33->client_no=$request['name'];
                  $objsaving33->paydet=$request['paymentdetails'];
                  $objsaving33->isCharge=1;
                  $objsaving33->branchno=$branch;
                  $objsaving33->total=$with->charge*-1;
                  $objsaving33->category='savingpdt3';
                  $objsaving33->moneyout=$with->charge;
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge;
                        $objaccountrans->accountcode="602";
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->cat='savingpdt3';
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                         // inserting into accountrans  cash account 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge*-1;
                        $objaccountrans->credit=1;
                        $objaccountrans->accountcode=$result->savingac;
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="D";
                        $objaccountrans->cat='savingpdt3';
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                        }
                    }
################################## end OF SAVING PDT 3 ##########################################
    }

   }else{
    $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){  
        $objsaving3->date=date("Y-m-d", strtotime($request['date']));
        $objsaving3->client_no=$request['name'];
        $objsaving3->paydet=$request['paymentdetails'];
        $objsaving3->isCharge=0;
        $objsaving3->total=0;
        $objsaving3->branchno=$branch;
        $objsaving3->category='savingpdt3';
        $objsaving3->moneyout=0; 
      
    // Place holders 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->savingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt3';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    // second place holder 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->operatingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt3';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    } 
   }
   if(str_replace( ',', '',$request['savingpdt4'])>0){
################################# START OF SAVING PDT 4 #########################################
    //$objallsavings->savingpdt4=str_replace( ',', '',$request['savingpdt4'])*-1;;
    $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt4=str_replace( ',', '',$request['savingpdt4'])*-1; 
                    
            }
        }
        $narration.=" & ". $result->productname;
        $objsaving44->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving44->branchno=$branch;
        $objsaving4->branchno=$branch;
        $objsaving4->narration="Cash Withdraw-".$result->productname;
        $objsaving4->date=date("Y-m-d", strtotime($request['date']));
        $objsaving4->client_no=$request['name'];
        $objsaving4->paydet=$request['paymentdetails'];
        $objsaving4->isCharge=0;
        $objsaving4->total=str_replace( ',', '',$request['savingpdt4'])*-1;;
        $objsaving4->category='savingpdt4';
        $objsaving4->moneyout=str_replace( ',', '',$request['savingpdt4']);;
                        // Accounts 
                        // inserting into accountrans  savings 1 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=str_replace( ',', '',$request['savingpdt4']);
                        $objaccountrans->total=str_replace( ',', '',$request['savingpdt4'])*-1;
                        $objaccountrans->accountcode=$result->savingac;
                        $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                        $objaccountrans->ttype="D";
                        $objaccountrans->cat='savingpdt4';
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                         // inserting into accountrans  cash account 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=str_replace( ',', '',$request['savingpdt4']);
                        $objaccountrans->total=str_replace( ',', '',$request['savingpdt4'])*-1;
                        $objaccountrans->accountcode=$result->operatingac;
                        $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->cat='savingpdt4';
                        $objaccountrans->stockidentify=$request['paymentdetails'];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                        $objaccountrans->save();
                        $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                        // Savings Withdraw Accounts 
                        if($savingrs->count()>0){
                            foreach($savingrs as $with){
                                $objallsavings->savingpdt4=(str_replace( ',', '',$request['savingpdt4'])+$with->charge)*-1;
                                $objsaving44->date=date("Y-m-d", strtotime($request['date']));
                                $objsaving44->client_no=$request['name'];
                                $objsaving44->paydet=$request['paymentdetails'];
                                $objsaving44->isCharge=1;
                                $objsaving44->total=$with->charge*-1;
                                $objsaving44->category='savingpdt4';
                                $objsaving44->moneyout=$with->charge;
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$with->charge;
                            $objaccountrans->total=$with->charge;
                            $objaccountrans->accountcode="602";
                            $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->cat='savingpdt4';
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
                             // inserting into accountrans  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$with->charge;
                            $objaccountrans->total=$with->charge*-1;
                            $objaccountrans->credit=1;
                            $objaccountrans->accountcode=$result->savingac;
                            $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='savingpdt4';
                            $objaccountrans->stockidentify=$request['paymentdetails'];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                            $objaccountrans->save();
                            }
                        }
     
############################# END OF SAVING PDT 4 ######################################################
    }
                

   }
   else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){ 
        $objsaving4->date=date("Y-m-d", strtotime($request['date']));
        $objsaving4->client_no=$request['name'];
        $objsaving4->paydet=$request['paymentdetails'];
        $objsaving4->isCharge=0;
        $objsaving4->branchno=$branch;
        $objsaving4->total=0;
        $objsaving4->category='savingpdt4';
        $objsaving4->moneyout=0; 
        $objsaving44->date=date("Y-m-d", strtotime($request['date']));
        $objsaving44->client_no=$request['name'];
        $objsaving44->paydet=$request['paymentdetails'];
        $objsaving44->isCharge=0;
        $objsaving44->total=0;
        $objsaving44->category='savingpdt4';
        $objsaving44->moneyout=0; 
    // Place holders 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->savingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt4';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    // second place holder 
    $objaccountrans=new accounttrans;
    $objaccountrans->purchaseheaderid=$objheaders->id;
    $objaccountrans->amount=0;
    $objaccountrans->total=0;
    $objaccountrans->accountcode=$result->operatingac;
    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
    $objaccountrans->ttype="D";
    $objaccountrans->bracid=auth()->user()->branchid;
    $objaccountrans->cat='savingpdt4';
    $objaccountrans->stockidentify=$request['paymentdetails'];
    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
    $objaccountrans->save();
    } 
   }
   if(str_replace( ',', '',$request['shares'])>0){
    ################################# START OF SHARES #########################################
                $objallsavings->shares=str_replace( ',', '',$request['shares'])*-1;
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
               
                foreach($rs as $result){
                    $narration.=" & ". $result->productname;
                    $objshareamt->narration="Cash Withdraw-".$result->productname;
                    $objshareamt->date=date("Y-m-d", strtotime($request['date']));
                    $objshareamt->client_no=$request['name'];
                    $objshareamt->paydet=$request['paymentdetails'];
                    $objshareamt->isCharge=0;
                    $objshareamt->branchno=$branch;
                    $objshareamt->total=str_replace( ',', '',$request['shares'])*-1;
                    $objshareamt->category='shares';
                    $objshareamt->moneyout=str_replace( ',', '',$request['shares']);;

                                    // Accounts 
                                    // inserting into accountrans  savings 1 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->total=str_replace( ',', '',$request['shares'])*-1;
                                    $objaccountrans->accountcode=$result->savingac;
                                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                                    $objaccountrans->ttype="D";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->stockidentify=$request['paymentdetails'];
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                    $objaccountrans->save();
                                     // inserting into accountrans  cash account 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$request['shares']);
                                    $objaccountrans->total=str_replace( ',', '',$request['shares'])*-1;
                                    $objaccountrans->accountcode=$result->operatingac;
                                    $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                    $objaccountrans->ttype="C";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->stockidentify=$request['paymentdetails'];
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                                    $objaccountrans->save();
                 
        ############################# Shares ######################################################
                }
                            
    
               }
               else{
     
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                foreach($rs as $result){ 
                    $objsaving4->date=date("Y-m-d", strtotime($request['date']));
                    $objsaving4->client_no=$request['name'];
                    $objsaving4->paydet=$request['paymentdetails'];
                    $objsaving4->isCharge=0;
                    $objsaving4->total=0;
                    $objsaving4->branchno=$branch;
                    $objsaving4->category='shares';
                    $objsaving4->moneyin=0;
                // Place holders 
                $objaccountrans=new accounttrans;
                $objaccountrans->purchaseheaderid=$objheaders->id;
                $objaccountrans->amount=0;
                $objaccountrans->total=0;
                $objaccountrans->accountcode=$result->savingac;
                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                $objaccountrans->ttype="C";
                $objaccountrans->bracid=auth()->user()->branchid;
                $objaccountrans->cat='shares';
                $objaccountrans->stockidentify=$request['paymentdetails'];
                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                $objaccountrans->save();
                // second place holder 
                $objaccountrans=new accounttrans;
                $objaccountrans->purchaseheaderid=$objheaders->id;
                $objaccountrans->amount=0;
                $objaccountrans->total=0;
                $objaccountrans->accountcode=$result->operatingac;
                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                $objaccountrans->ttype="C";
                $objaccountrans->bracid=auth()->user()->branchid;
                $objaccountrans->cat='shares';
                $objaccountrans->stockidentify=$request['paymentdetails'];
                $objaccountrans->transdate=date("Y-m-d", strtotime($request['date']));
                $objaccountrans->save();

                
            }
            }
   if($request['savingpdt5']>0){
    $objallsavings->savingpdt5=$request['savingpdt5'];

    $rs=savingdefinations::where('savingpdt','=','savingpdt5')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $objsaving->narration="Cash Withdraw-".$result->productname;
    }
                
    $objsaving5->date=date("Y-m-d", strtotime($request['date']));
    $objsaving5->client_no=$request['name'];
    $objsaving5->paydet=$request['paymentdetails'];
    $objsaving5->isCharge=0;
    $objsaving5->branchno=$branch;
    $objsaving5->total=$request['savingpdt5']*-1;
    $objsaving5->category='savingpdt5';
    $objsaving5->moneyout=$request['savingpdt5'];

   }
   $objallsavings->narration=$narration." Withdraw";
   $objallsavings->headerid=$objheaders->id;
   $objallsavings->save();
   // saving id in savings table
   $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs1->count()>0){
    $objsaving1->savingsid=$objallsavings->id; 
    $objsaving1->save();
    $objsaving11->savingsid=$objallsavings->id; 
    $objsaving11->save();
   }
   $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs2->count()>0){
    $objsaving2->savingsid=$objallsavings->id; 
    $objsaving2->save();
    $objsaving22->savingsid=$objallsavings->id; 
    $objsaving22->save();
   }
   $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs3->count()>0){
    $objsaving3->savingsid=$objallsavings->id; 
    $objsaving3->save();
    $objsaving33->savingsid=$objallsavings->id; 
    $objsaving33->save();
   }
   $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs4->count()>0){
    $objsaving4->savingsid=$objallsavings->id; 
    $objsaving4->save();
    $objsaving44->savingsid=$objallsavings->id; 
    $objsaving44->save();
   }
   $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   if($rs4->count()>0){
    $objshareamt->savingsid=$objallsavings->id; 
    $objshareamt->save();
   }

   $objaudits= new audits();
   $objaudits->event=$audit;
   $objaudits->branchno=auth()->user()->branchid;
   $objaudits->username=auth()->user()->name;
   $objaudits->save();  


}
    }
}catch(\Exception $e){
DB::rollback();
echo "Failed ".$e;
}
DB::commit();



}

//Auto generated code for updating
public function update(Request $request,$id2){
    $id=$request['clino'];
    DB::beginTransaction();
    $branch=auth()->user()->branchid;
try{
    // Geting member id
$member=DB::select("select name from customers where id=$id");
foreach($member as $mem){
    purchaseheaders::where('id','=',$request['headerid'])->update([
     'transdates'=>date("Y-m-d", strtotime($request['date'])),
    ]);
   $objallsavings= allsavings::find($request['id']);
   $objallsavings->client_no=$id;
   $objallsavings->recieptno=$request['paymentdetails'];
   $objallsavings->date=date("Y-m-d", strtotime($request['date']));
   $narration="";
   if(str_replace( ',', '',$request['savingpdt1'])>0){
####################### START OF SAVING PDT 1 ######################################################
  $withdrawcharge=0;
    //$objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt1']);
    $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            $withdrawcharge=$char->charge;
            if($char->charge==0){
                $objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt1'])*-1; 
                    
            }
        }
        $narration= $result->productname;
        // Accounts 
          accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt1')->update([
              'amount'=>str_replace( ',', '',$request['savingpdt1']),
              'total'=>str_replace( ',', '',$request['savingpdt1'])*-1,
              'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
              'stockidentify'=>$request['paymentdetails'],
              'transdate'=>date("Y-m-d", strtotime($request['date']))

          ]) ;        
      // inserting into accountrans  cash account 
      accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt1')->whereNull('credit')->update([
        'amount'=>str_replace( ',', '',$request['savingpdt1']),
        'total'=>str_replace( ',', '',$request['savingpdt1'])*-1,
        'ttype'=>'C',
        'narration'=>$mem->name." -Cash withdraw ".$result->productname,
        'stockidentify'=>$request['paymentdetails'],
        'transdate'=>date("Y-m-d", strtotime($request['date']))

    ]) ;
        // savings Table 
        savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt1')->where('isCharge',0)->update([
            'date'=>date('Y-m-d', strtotime($request['date'])),
            'client_no'=>$id,
            'paydet'=>$request['paymentdetails'],
            'total'=>str_replace( ',', '',$request['savingpdt1'])*-1,
            'moneyout'=>str_replace( ',', '',$request['savingpdt1']),
            'narration'=>'Cash withdraw-'.$result->productname,
    
            ]); 
    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
    // Savings Withdraw Accounts 
    if($savingrs->count()>0){
        foreach($savingrs as $with){
            $objallsavings->savingpdt1=(str_replace( ',', '',$request['savingpdt1'])+$with->charge)*-1;
                    // withdrawal account  
          accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt1')->update([
            'amount'=>str_replace( ',', '',$with->charge),
            'total'=>str_replace( ',', '',$with->charge),
            'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
            'stockidentify'=>$request['paymentdetails'],
            'transdate'=>date("Y-m-d", strtotime($request['date']))

        ]) ;        
    // inserting into accountrans  cash account 
    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt1')->where('credit','=',1)->update([
      'amount'=>str_replace( ',', '',$with->charge),
      'total'=>str_replace( ',', '',$with->charge)*-1,
      'narration'=>$mem->name." -Cash withdraw ".$result->productname,
      'stockidentify'=>$request['paymentdetails'],
      'transdate'=>date("Y-m-d", strtotime($request['date']))

  ]) ;
      // savings table two 
     $chkquick= savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt1')->where('isCharge','=',1)->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$with->charge)*-1,
        'moneyout'=>str_replace( ',', '',$with->charge),
        'narration'=>'Cash withdraw Fees-'.$result->productname,
        'isCharge'=>1,

        ]);
if($chkquick==0){
    savings::where('savingsid','=',$request['id'])->whereNull('category')->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$with->charge)*-1,
        'moneyout'=>str_replace( ',', '',$with->charge),
        'narration'=>'Cash withdraw Fees-'.$result->productname,
        'branchno'=>$branch,
        'category'=>'savingpdt1',
        'isCharge'=>1,

        ]);
}
        }
    }
    }

    
####################### END OF SAVING PDT 1 ######################################################
   }else{
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt1')->update([
        'date'=>'',
        'client_no'=>'',
        'paydet'=>'',
        'total'=>0,
        'moneyout'=>0,

        ]);

        $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
             // Accounts 
          accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt1')->update([
                            'amount'=>0,
                            'total'=>0,
                            'stockidentify'=>$request['paymentdetails'],
                            'transdate'=>date("Y-m-d", strtotime($request['date']))
      
                        ]) ;        
         // inserting into accountrans  cash account 
             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt1')->update([
                      'amount'=>0,
                      'total'=>0,
                      'stockidentify'=>$request['paymentdetails'],
                      'transdate'=>date("Y-m-d", strtotime($request['date']))
      
                  ]) ;
                           // Withdraw fees 
             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt1')->update([
                'amount'=>0,
                'total'=>0,
                'stockidentify'=>$request['paymentdetails'],
                'transdate'=>date("Y-m-d", strtotime($request['date']))

            ]) ;
             }
       $objallsavings->savingpdt1=0;
   }
   if(str_replace( ',', '',$request['savingpdt2'])>0){
###################### START OF SAVING PDT 2 ####################################################        
    //$objallsavings->savingpdt2=str_replace( ',', '',$request['savingpdt2']);
    $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt2=str_replace( ',', '',$request['savingpdt2'])*-1; 
                    
            }
        }

    }
                 // savings Table 
    
                 savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt2')->update([
                    'date'=>date('Y-m-d', strtotime($request['date'])),
                    'client_no'=>$id,
                    'paydet'=>$request['paymentdetails'],
                    'total'=>str_replace( ',', '',$request['savingpdt2'])*-1,
                    'moneyout'=>str_replace( ',', '',$request['savingpdt2']),
                    'narration'=>'Cash Withdraw-'.$result->productname,
                    ]); 
                    // Accounts 
        // Accounts 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt2')->update([
            'amount'=>str_replace( ',', '',$request['savingpdt2']),
            'total'=>str_replace( ',', '',$request['savingpdt2'])*-1,
            'narration'=>$mem->name." -Cash withdraw ".$result->productname,
            'stockidentify'=>$request['paymentdetails'],
            'transdate'=>date("Y-m-d", strtotime($request['date']))

        ]) ;        
    // inserting into accountrans  cash account // Prob
    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt2')->where('credit','=',0)->update([
      'amount'=>str_replace( ',', '',$request['savingpdt2']),
      'total'=>str_replace( ',', '',$request['savingpdt2'])*-1,
      'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
      'stockidentify'=>$request['paymentdetails'],
      'transdate'=>date("Y-m-d", strtotime($request['date']))

  ]) ;
  ###########withdraws ##
  $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
  // Savings Withdraw Accounts 
  if($savingrs->count()>0){
      foreach($savingrs as $with){
                  // withdrawal account
        $objallsavings->savingpdt2=(str_replace( ',', '',$request['savingpdt2'])+$with->charge)*-1; 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt2')->update([
          'amount'=>str_replace( ',', '',$with->charge),
          'total'=>str_replace( ',', '',$with->charge),
          'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
          'stockidentify'=>$request['paymentdetails'],
          'transdate'=>date("Y-m-d", strtotime($request['date']))

      ]) ;        
  // inserting into accountrans  cash account 
  accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt2')->where('credit','=',1)->update([
    'amount'=>str_replace( ',', '',$with->charge),
    'total'=>str_replace( ',', '',$with->charge)*-1,
    'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
    'stockidentify'=>$request['paymentdetails'],
    'transdate'=>date("Y-m-d", strtotime($request['date']))

]) ;
      // savings table two 
      savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt2')->where('isCharge','=',1)->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$with->charge)*-1,
        'moneyout'=>str_replace( ',', '',$with->charge),
        'narration'=>'Cash withdraw Fees-'.$result->productname,

        ]);
      }
  }

########################### END OF SAVING PDT 2 ##################################################      
   }else{
    // Delete if amount is zero
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt2')->update([
        'date'=>'',
        'client_no'=>'',
        'paydet'=>'',
        'total'=>0,
        'moneyout'=>0,

        ]);

          // Accounts 
          $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('branchno','=',$branch)->get();
          foreach($rs as $result){
         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt2')->update([
                                'amount'=>0,
                                'total'=>0,
                                'stockidentify'=>$request['paymentdetails'],
                                'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                            ]) ;        
             // inserting into accountrans  cash account 
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt2')->update([
                          'amount'=>0,
                          'total'=>0,
                          'stockidentify'=>$request['paymentdetails'],
                          'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                      ]) ;
                                                 // Withdraw fees 
             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt2')->update([
                'amount'=>0,
                'total'=>0,
                'stockidentify'=>$request['paymentdetails'],
                'transdate'=>date("Y-m-d", strtotime($request['date']))

            ]) ;
    $objallsavings->savingpdt2=0;
}
}
   if(str_replace( ',', '',$request['savingpdt3'])>0){
########################## sTART OF SAVING PDT 3 ##############################################            
    $objallsavings->savingpdt3=str_replace( ',', '',$request['savingpdt3']);
    $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt3=str_replace( ',', '',$request['savingpdt3'])*-1; 
                    
            }
        }

    }
                
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt3')->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$request['savingpdt3'])*-1,
        'moneyout'=>str_replace( ',', '',$request['savingpdt3']),
        'narration'=>'Cash Withdraw-'.$result->productname,
        ]); 
                   // Accounts 
        // Accounts 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt3')->update([
            'amount'=>str_replace( ',', '',$request['savingpdt3']),
            'total'=>str_replace( ',', '',$request['savingpdt3'])*-1,
            'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
            'stockidentify'=>$request['paymentdetails'],
            'transdate'=>date("Y-m-d", strtotime($request['date']))

        ]) ;        
    // inserting into accountrans  cash account 
    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt3')->where('credit','=',0)->update([
      'amount'=>str_replace( ',', '',$request['savingpdt3']),
      'total'=>str_replace( ',', '',$request['savingpdt3'])*-1,
      'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
      'stockidentify'=>$request['paymentdetails'],
      'transdate'=>date("Y-m-d", strtotime($request['date']))

  ]) ;
  $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
  // Savings Withdraw Accounts 
  if($savingrs->count()>0){
      foreach($savingrs as $with){
        $objallsavings->savingpdt3=(str_replace( ',', '',$request['savingpdt3'])+$with->charge)*-1;
                  // withdrawal account  
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt3')->update([
          'amount'=>str_replace( ',', '',$with->charge),
          'total'=>str_replace( ',', '',$with->charge),
          'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
          'stockidentify'=>$request['paymentdetails'],
          'transdate'=>date("Y-m-d", strtotime($request['date']))

      ]) ;        
  // inserting into accountrans  cash account 
  accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt3')->where('credit','=',1)->update([
    'amount'=>str_replace( ',', '',$with->charge),
    'total'=>str_replace( ',', '',$with->charge)*-1,
    'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
    'stockidentify'=>$request['paymentdetails'],
    'transdate'=>date("Y-m-d", strtotime($request['date']))

]) ;
      // savings table two 
      savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt3')->where('isCharge','=',1)->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$with->charge)*-1,
        'moneyout'=>str_replace( ',', '',$with->charge),
        'narration'=>'Cash withdraw Fees-'.$result->productname,

        ]);
      }
  }
################################## end OF SAVING PDT 3 ##########################################
   }else{
    // Delete if amount is zero
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt3')->update([
        'date'=>'',
        'client_no'=>'',
        'paydet'=>'',
        'total'=>0,
        'moneyout'=>0,

        ]);

        // Accounts 
        $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
         accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt3')->update([
                                'amount'=>0,
                                'total'=>0,
                                'stockidentify'=>$request['paymentdetails'],
                                'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                            ]) ;        
             // inserting into accountrans  cash account 
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt3')->update([
                          'amount'=>0,
                          'total'=>0,
                          'stockidentify'=>$request['paymentdetails'],
                          'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                      ]) ;
                                                 // Withdraw fees 
             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt3')->update([
                'amount'=>0,
                'total'=>0,
                'stockidentify'=>$request['paymentdetails'],
                'transdate'=>date("Y-m-d", strtotime($request['date']))

            ]) ;
    $objallsavings->savingpdt3=0;
}
}
   if(str_replace( ',', '',$request['savingpdt4'])>0){
################################# START OF SAVING PDT 4 #########################################
    //$objallsavings->savingpdt4=str_replace( ',', '',$request['savingpdt4']);
    $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('branchno','=',$branch)->get();
   
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt1=str_replace( ',', '',$request['savingpdt4'])*-1; 
                    
            }
        }
    }
                
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt4')->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$request['savingpdt4'])*-1,
        'moneyout'=>str_replace( ',', '',$request['savingpdt4']),
        'narration'=>'Cash Withdraw-'.$result->productname,
        ]); 
                    // Accounts 
        // Accounts 
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt4')->update([
            'amount'=>str_replace( ',', '',$request['savingpdt4']),
            'total'=>str_replace( ',', '',$request['savingpdt4'])*-1,
            'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
            'stockidentify'=>$request['paymentdetails'],
            'transdate'=>date("Y-m-d", strtotime($request['date']))

        ]) ;        
    // inserting into accountrans  cash account 
    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt4')->where('credit','=',0)->update([
      'amount'=>str_replace( ',', '',$request['savingpdt4']),
      'total'=>str_replace( ',', '',$request['savingpdt4'])*-1,
      'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
      'stockidentify'=>$request['paymentdetails'],
      'transdate'=>date("Y-m-d", strtotime($request['date']))

  ]) ;
  $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
  // Savings Withdraw Accounts 
  if($savingrs->count()>0){
      foreach($savingrs as $with){
        $objallsavings->savingpdt4=(str_replace( ',', '',$request['savingpdt4'])+$with->charge)*-1;
                  // withdrawal account  
        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt4')->update([
          'amount'=>str_replace( ',', '',$with->charge),
          'total'=>str_replace( ',', '',$with->charge),
          'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
          'stockidentify'=>$request['paymentdetails'],
          'transdate'=>date("Y-m-d", strtotime($request['date']))

      ]) ;        
  // inserting into accountrans  cash account 
  accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt4')->where('credit','=',1)->update([
    'amount'=>str_replace( ',', '',$with->charge),
    'total'=>str_replace( ',', '',$with->charge)*-1,
    'narration'=>$mem->name." -Cash Withdraw ".$result->productname,
    'stockidentify'=>$request['paymentdetails'],
    'transdate'=>date("Y-m-d", strtotime($request['date']))

]) ;
      // savings table two 
      savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt4')->where('isCharge','=',1)->update([
        'date'=>date('Y-m-d', strtotime($request['date'])),
        'client_no'=>$id,
        'paydet'=>$request['paymentdetails'],
        'total'=>str_replace( ',', '',$with->charge)*-1,
        'moneyout'=>str_replace( ',', '',$with->charge),
        'narration'=>'Cash withdraw Fees-'.$result->productname,

        ]);
      }
  }
############################# END OF SAVING PDT 4 ######################################################
   }else{
    // Delete if amount is zero
    savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt4')->update([
        'date'=>'',
        'client_no'=>'',
        'paydet'=>'',
        'total'=>0,
        'moneyout'=>0,

        ]);

        // Accounts 
        $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('branchno','=',$branch)->get();
        foreach($rs as $result){
                             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','savingpdt4')->update([
                                'amount'=>0,
                                'total'=>0,
                                'stockidentify'=>$request['paymentdetails'],
                                'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                            ]) ;        
             // inserting into accountrans  cash account 
                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','savingpdt4')->update([
                          'amount'=>0,
                          'total'=>0,
                          'stockidentify'=>$request['paymentdetails'],
                          'transdate'=>date("Y-m-d", strtotime($request['date']))
          
                      ]) ;
                                                 // Withdraw fees 
             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=','602')->where('cat','=','savingpdt4')->update([
                'amount'=>0,
                'total'=>0,
                'stockidentify'=>$request['paymentdetails'],
                'transdate'=>date("Y-m-d", strtotime($request['date']))

            ]) ;
    $objallsavings->savingpdt4=0;
}
}
    // Shares
    if(str_replace( ',', '',$request['shares'])>0){
        ################################# SHARES  #########################################
                    $objallsavings->shares=str_replace( ',', '',$request['shares'])*-1;
                    $rs=savingdefinations::where('savingpdt','=','shares')->where('branchno','=',$branch)->get();
                   
                    foreach($rs as $result){
                        $narration.=" & ". $result->productname;
                    }
                                
                    $chksave=savings::where('savingsid','=',$request['id'])->where('category','=','shares')->update([
                        'date'=>date('Y-m-d', strtotime($request['date'])),
                        'client_no'=>$id,
                        'paydet'=>$request['paymentdetails'],
                        'total'=>str_replace( ',', '',$request['shares'])*-1,
                        'moneyout'=>str_replace( ',', '',$request['shares']),
                        'narration'=>'Cash Deposit-'.$result->productname,
                        ]);
                        if($chksave==0){
                        savings::where('savingsid','=',$request['id'])->whereNull('category')->update([
                            'date'=>date('Y-m-d', strtotime($request['date'])),
                            'client_no'=>$id,
                            'paydet'=>$request['paymentdetails'],
                            'total'=>str_replace( ',', '',$request['shares'])*-1,
                            'moneyout'=>str_replace( ',', '',$request['shares']),
                            'narration'=>'Cash Deposit-'.$result->productname,
                            'branchno'=>$branch,
                            'category'=>'shares',
                            ]); 
                       }
                                    // Accounts 
                        // Accounts 
                        accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','shares')->update([
                            'amount'=>str_replace( ',', '',$request['shares']),
                            'total'=>str_replace( ',', '',$request['shares'])*-1,
                            'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                            'stockidentify'=>$request['paymentdetails'],
                            'transdate'=>date("Y-m-d", strtotime($request['date'])),
                            'ttype'=>'D',
        
                        ]) ;        
                    // inserting into accountrans  cash account 
                    accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','shares')->update([
                      'amount'=>str_replace( ',', '',$request['shares']),
                      'total'=>str_replace( ',', '',$request['shares'])*-1,
                      'ttype'=>'C',
                      'narration'=>$mem->name." -Cash Deposits ".$result->productname,
                      'stockidentify'=>$request['paymentdetails'],
                      'transdate'=>date("Y-m-d", strtotime($request['date']))
        
                  ]) ;
                 
        ############################# SHAREs ######################################################
                   }else{
                    // Delete if amount is zero
                    savings::where('savingsid','=',$request['id'])->where('category','=','shares')->update([
                        'date'=>'',
                        'client_no'=>'',
                        'paydet'=>'',
                        'total'=>0,
                        'moneyin'=>0,
           
                        ]);
                        // Accounts 
                        $rs=savingdefinations::where('savingpdt','=','shares')->where('branchno','=',$branch)->get();
                        foreach($rs as $result){
                                             accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->savingac)->where('cat','=','shares')->update([
                                                'amount'=>0,
                                                'total'=>0,
                                                'stockidentify'=>$request['paymentdetails'],
                                                'transdate'=>date("Y-m-d", strtotime($request['date']))
                          
                                            ]) ;        
                             // inserting into accountrans  cash account 
                                 accounttrans::where('purchaseheaderid','=',$request['headerid'])->where('accountcode','=',$result->operatingac)->where('cat','=','shares')->update([
                                          'amount'=>0,
                                          'total'=>0,
                                          'stockidentify'=>$request['paymentdetails'],
                                          'transdate'=>date("Y-m-d", strtotime($request['date']))
                          
                                      ]) ;
                    $objallsavings->shares=0;
                }
   if($request['savingpdt5']>0){
    $objallsavings->savingpdt5=$request['savingpdt5'];

    $rs=savingdefinations::where('savingpdt','=','savingpdt5')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
    }
     savings::where('savingsid','=',$request['id'])->where('category','=','savingpdt5')->update([
     'date'=>date('Y-m-d', strtotime($request['date'])),
     'client_no'=>$id,
     'paydet'=>$request['paymentdetails'],
     'total'=>str_replace( ',', '',$request['savingpdt5']),
     'moneyout'=>str_replace( ',', '',$request['savingpdt5']),

     ]);         

   }
}
   $objallsavings->narration=$narration." Withdraw";
   $objallsavings->save();



}

}catch(\Exception $e){
DB::rollback();
echo "Failed ".$e;
}
DB::commit();




    }
    public function destroy(Request $request,$id){
        $user=auth()->user()->name;
        DB::beginTransaction();
        try{
            $deleteitems=DB::select("select id from allsavings where headerid=$id");
            foreach($deleteitems as $items){
            DB::delete("delete from allsavings where headerid=$id");
            DB::delete("delete from purchaseheaders where id=$id");
            DB::delete("delete from savings where savingsid=$items->id");
            DB::delete("delete from accounttrans where purchaseheaderid=$id");
     
            }
        }catch(\Exception $e){
           DB::rollback();
           echo "Failed".$e;
       }
       DB::commit();
    
    
    
        }   

public function viewcombo(){


    return companynames::all();
}

public function savingview($where,$alldate,$rec,$pay){
    $results=array();
    $bra=auth()->user()->branchid;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count from savings inner join customers on customers.id=savings.client_no inner join allsavings on allsavings.id=savings.savingsid $where  and moneyout>0 and isCharge!=1 and allsavings.branchno=$bra $rec ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select format(if(category='shares',moneyout,0),0) as shares, headerid, allsavings.id as id,purchase_headerid as header,DATE_FORMAT(savings.date,'%d-%m-%Y') as date,paydet,customers.name as name,customers.id as clino,savings.narration,format(moneyout,2) as savings,format(abs(savingpdt1),0) as savingpdt1,format(abs(savingpdt2),0) as savingpdt2,format(abs(savingpdt3),0) as savingpdt3,format(abs(savingpdt4),0) as savingpdt4,format(abs(savingpdt5),0) as savingpdt5  from savings inner join customers on customers.id=savings.client_no inner join allsavings on allsavings.id=savings.savingsid $where  and moneyout>0 and isCharge!=1 and allsavings.branchno=$bra and isFee!=1  $rec limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
        //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select format(sum(if(isCharge=0,moneyout,0)),2) as savings from savings where branchno=$bra and date $alldate and isFee!=1 $pay");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
}

public function authsavingview($where){
    $admin=auth()->user()->isAdmin;
        if($admin==1){
    $results=array();
    $bra=auth()->user()->branchid;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        $krows = DB::select("select COUNT(*) as count  from loantrans inner join customers on loantrans.memid=customers.id inner join users on users.branchid=customers.branchnumber where isSavingDep=0  and narration!='Auto computed interest' and loantrans.isActive=1  $where ");
        $results['total']=$krows[0]->count;
        $rst =  DB::getPdo()->prepare("select loantrans.id as lnid,DATE_FORMAT(date,'%d-%m-%Y') as date,memid,loanid,customers.name,paydet,narration,format(abs(loan),0) as loan,format(abs(savings),0) as savings from loantrans inner join customers on loantrans.memid=customers.id inner join users on users.branchid=customers.branchnumber where isSavingDep=0  and narration!='Auto computed interest' and loantrans.isActive=1  $where  limit $offset,$rows");
        $rst->execute();
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
        //Showing The footer and totals 
   $footer =  DB::getPdo()->prepare("select format(sum(abs(savings)),0) as savings from loantrans inner join customers on loantrans.memid=customers.id inner join users on users.branchid=customers.branchnumber where isSavingDep=0  and narration!='Auto computed interest' loantrans.isActive=1   $where  $and limit  $offset,$rows");
   $footer->execute();
   $foots=$footer->fetchAll(\PDO::FETCH_OBJ);
   $results['footer']=$foots;
   echo json_encode($results);
        }
}
public function checkBal($clientno){

    return DB::select("select format(sum(savingpdt1),0) as savingpd1, format(sum(savingpdt2),0) as savingpd2,format(sum(savingpdt3),0) as savingpd3,format(sum(savingpdt4),0) as savingpd4,format(sum(savingpdt5),0) as savingpd5,format(sum(shares),0) as shares1 from allsavings where  client_no=$clientno ");
}
public function checkBalLn($clientno){

    return DB::select("select format(sum(loanpdt1),0) as loanpd1,format(sum(loanint1),0) as loanin1 from allsavings where  client_no=$clientno ");
}
public function checkBalAll($clientno){

    return DB::select("select format(sum(savingpdt1),0) as savingpd1,format(sum(shares),0)as shares, format(sum(loanpdt1),0) as loanpd1,format(sum(loanint1),0) as loanin1 from allsavings where  client_no=$clientno  ");
}
}