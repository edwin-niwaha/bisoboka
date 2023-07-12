<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\interestmethods;
use App\companynames;
use App\loantrans;
use App\loaninfo;
use App\accounttrans;
use App\purchaseheaders;
use App\savings;
use App\savingdefinations;
use App\allsavings;
use App\savingsproducts;

 class importwithdrawsControllers extends Controller{
	 
	 public function importwithdraws(Request $request){
		$file=$request->file('files');
		$destinationPath="images";
		if($file!=Null){
			$filename=$file->getClientOriginalName();
			//moving it to the folder
			$finalfile=$file->move($destinationPath,$filename);
			$handle = fopen($finalfile, "r");
			while(($data=fgetcsv($handle,1000,","))!==FALSE  ){


			$id=$data[1];
            DB::beginTransaction();
  try{
			// Geting member id
			$branch=auth()->user()->branchid;// Geting branch number 
	   $member=DB::select("select name,id from customers where acno='$id'");
	   if(count($member)>0){
		   if($data[6]=="D"){
       foreach($member as $mem){
               // Obtaining header number from purchaseheaders table 
               $objheaders= new purchaseheaders();
               $objheaders->transdates=date("Y-m-d", strtotime($data[0]));
               $objheaders->save();

           $objallsavings= new allsavings();
           // saving into savings table for clear statements 
           $objsaving1= new savings();
           $objsaving2= new savings();
           $objsaving3= new savings();
           $objsaving4= new savings();
           $objshareamt= new savings();
           $objallsavings->client_no=$mem->id;
           $objallsavings->recieptno=$data[2];
           $objallsavings->branchno=$branch;
           $objallsavings->date=date("Y-m-d", strtotime($data[0]));
           $narration="";
           if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt1' ){
####################### START OF SAVING PDT 1 ######################################################
            $objallsavings->savingpdt1=str_replace( ',', '',$data[4]);
            $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration= $result->productname;
                $objsaving1->narration="Cash Deposit-".$result->productname;
                // Accounts 
                            // inserting into accountrans  savings 1 
             $objaccountrans=new accounttrans;
             $objaccountrans->purchaseheaderid=$objheaders->id;
             $objaccountrans->amount=str_replace( ',', '',$data[4]);
             $objaccountrans->total=str_replace( ',', '',$data[4]);
             $objaccountrans->accountcode=$result->savingac;
             $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
             $objaccountrans->ttype="C";
             $objaccountrans->bracid=auth()->user()->branchid;
             $objaccountrans->cat='savingpdt1';
             $objaccountrans->stockidentify=$data[2];
             $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
             $objaccountrans->save();
              // inserting into accountrans  cash account 
             $objaccountrans=new accounttrans;
             $objaccountrans->purchaseheaderid=$objheaders->id;
             $objaccountrans->amount=str_replace( ',', '',$data[4]);
             $objaccountrans->total=str_replace( ',', '',$data[4]);
             $objaccountrans->accountcode=$result->operatingac;
             $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
             $objaccountrans->ttype="D";
             $objaccountrans->cat='savingpdt1';
             $objaccountrans->stockidentify=$data[2];
             $objaccountrans->bracid=auth()->user()->branchid;
             $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
             $objaccountrans->save();
                         // savings Table 
            $objsaving1->date=date("Y-m-d", strtotime($data[0]));
            $objsaving1->client_no=$mem->id;
            $objsaving1->paydet=$data[2];
            $objsaving1->isCharge=0;
            $objsaving1->branchno=$branch;
            $objsaving1->total=str_replace( ',', '',$data[4]);
            $objsaving1->category='savingpdt1';
            $objsaving1->moneyin=str_replace( ',', '',$data[4]);
            }

            
####################### END OF SAVING PDT 1 ######################################################
           }else{

            $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){  
                $objsaving1->date=date("Y-m-d", strtotime($data[0]));
                $objsaving1->client_no=$mem->id;
                $objsaving1->paydet=$data[2];
                $objsaving1->isCharge=0;
                $objsaving1->total=0;
                $objsaving1->branchno=$branch;
                $objsaving1->category='savingpdt1';
                $objsaving1->moneyin=0;
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt1';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
            $objaccountrans->cat='savingpdt1';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
            $objaccountrans->save();
            }
           }
           if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt2'){
###################### START OF SAVING PDT 2 ####################################################        
            $objallsavings->savingpdt2=str_replace( ',', '',$data[4]);
            $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving2->narration="Cash Deposit-".$result->productname;
                                         // savings Table 
            
            $objsaving2->date=date("Y-m-d", strtotime($data[0]));
            $objsaving2->client_no=$mem->id;
            $objsaving2->paydet=$data[2];
            $objsaving2->isCharge=0;
            $objsaving2->branchno=$branch;
            $objsaving2->total=str_replace( ',', '',$data[4]);;
            $objsaving2->category='savingpdt2';
            $objsaving2->moneyin=str_replace( ',', '',$data[4]);;
                            // Accounts 
                            // inserting into accountrans  savings 1 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$data[4]);
                            $objaccountrans->total=str_replace( ',', '',$data[4]);
                            $objaccountrans->accountcode=$result->savingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->cat='savingpdt2';
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                            $objaccountrans->save();
                             // inserting into accountrans  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$data[4]);
                            $objaccountrans->total=str_replace( ',', '',$data[4]);
                            $objaccountrans->accountcode=$result->operatingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='savingpdt2';
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                            $objaccountrans->save();

########################### END OF SAVING PDT 2 ################################################## 
            }
     
           }else{

            $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){ 
                $objsaving2->date=date("Y-m-d", strtotime($data[0]));
                $objsaving2->client_no=$mem->id;
                $objsaving2->paydet=$data[2];
                $objsaving2->isCharge=0;
                $objsaving2->total=0;
                $objsaving2->branchno=$branch;
                $objsaving2->category='savingpdt2';
                $objsaving2->moneyin=0;  
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt2';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
            $objaccountrans->cat='savingpdt2';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
            $objaccountrans->save();
            } 
           }
           if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt3'){
########################## sTART OF SAVING PDT 3 ##############################################            
            $objallsavings->savingpdt3=str_replace( ',', '',$data[4]);;
            $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving3->narration="Cash Deposit-".$result->productname;
                $objsaving3->date=date("Y-m-d", strtotime($datat[0]));
                $objsaving3->client_no=$mem->id;
                $objsaving3->paydet=$data[2];
                $objsaving3->isCharge=0;
                $objsaving3->branchno=$branch;
                $objsaving3->total=str_replace( ',', '',$data[4]);;
                $objsaving3->category='savingpdt3';
                $objsaving3->moneyin=str_replace( ',', '',$data[4]);;
                                        
 // Accounts 
                            // inserting into accountrans  savings 1 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$data[4]);
                            $objaccountrans->total=str_replace( ',', '',$data[4]);
                            $objaccountrans->accountcode=$result->savingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->cat='savingpdt3';
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                            $objaccountrans->save();
                             // inserting into accountrans  cash account 
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=str_replace( ',', '',$data[4]);
                            $objaccountrans->total=str_replace( ',', '',$data[4]);
                            $objaccountrans->accountcode=$result->operatingac;
                            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                            $objaccountrans->ttype="D";
                            $objaccountrans->cat='savingpdt3';
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                            $objaccountrans->save();
################################## end OF SAVING PDT 3 ##########################################
            }

           }else{
            $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){  
                $objsaving3->date=date("Y-m-d", strtotime($data[0]));
                $objsaving3->client_no=$mem->id;
                $objsaving3->paydet=$data[2];
                $objsaving3->isCharge=0;
                $objsaving3->branchno=$branch;
                $objsaving3->total=0;
                $objsaving3->category='savingpdt3';
                $objsaving3->moneyin=0; 
              
            // Place holders 
            $objaccountrans=new accounttrans;
            $objaccountrans->purchaseheaderid=$objheaders->id;
            $objaccountrans->amount=0;
            $objaccountrans->total=0;
            $objaccountrans->accountcode=$result->savingac;
            $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
            $objaccountrans->ttype="C";
            $objaccountrans->bracid=auth()->user()->branchid;
            $objaccountrans->cat='savingpdt3';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
            $objaccountrans->cat='savingpdt3';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
            $objaccountrans->save();
            } 
           }
           if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt4'){
################################# START OF SAVING PDT 4 #########################################
            $objallsavings->savingpdt4=str_replace( ',', '',$data[4]);;
            $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           
            foreach($rs as $result){
                $narration.=" & ". $result->productname;
                $objsaving4->narration="Cash Deposit-".$result->productname;
                $objsaving4->date=date("Y-m-d", strtotime($data[0]));
                $objsaving4->client_no=$mem->id;
                $objsaving4->paydet=$data[2];
                $objsaving4->isCharge=0;
                $objsaving4->branchno=$branch;
                $objsaving4->total=str_replace( ',', '',$data[4]);;
                $objsaving4->category='savingpdt4';
                $objsaving4->moneyin=str_replace( ',', '',$data[4]);;
                                // Accounts 
                                // inserting into accountrans  savings 1 
                                $objaccountrans=new accounttrans;
                                $objaccountrans->purchaseheaderid=$objheaders->id;
                                $objaccountrans->amount=str_replace( ',', '',$data[4]);
                                $objaccountrans->total=str_replace( ',', '',$data[4]);
                                $objaccountrans->accountcode=$result->savingac;
                                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                $objaccountrans->ttype="C";
                                $objaccountrans->cat='savingpdt4';
                                $objaccountrans->bracid=auth()->user()->branchid;
                                $objaccountrans->stockidentify=$data[2];
                                $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                                $objaccountrans->save();
                                 // inserting into accountrans  cash account 
                                $objaccountrans=new accounttrans;
                                $objaccountrans->purchaseheaderid=$objheaders->id;
                                $objaccountrans->amount=str_replace( ',', '',$data[4]);
                                $objaccountrans->total=str_replace( ',', '',$data[4]);
                                $objaccountrans->accountcode=$result->operatingac;
                                $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                $objaccountrans->ttype="D";
                                $objaccountrans->cat='savingpdt4';
                                $objaccountrans->stockidentify=$data[2];
                                $objaccountrans->bracid=auth()->user()->branchid;
                                $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                                $objaccountrans->save();
             
    ############################# END OF SAVING PDT 4 ######################################################
            }
                        

           }
           else{
 
            $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
            foreach($rs as $result){ 
                $objsaving4->date=date("Y-m-d", strtotime($data[0]));
                $objsaving4->client_no=$mem->id;
                $objsaving4->paydet=$data[2];
                $objsaving4->isCharge=0;
                $objsaving4->total=0;
                $objsaving4->branchno=$branch;
                $objsaving4->category='savingpdt4';
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
            $objaccountrans->cat='savingpdt4';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
            $objaccountrans->cat='savingpdt4';
            $objaccountrans->stockidentify=$data[2];
            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
            $objaccountrans->save();
            } 
           }
################################# Shares ###############################################
if(str_replace( ',', '',$data[4])>0 && $data[5]=='shares'){
    ################################# START OF SHARES #########################################
                $objallsavings->shares=str_replace( ',', '',$data[4]);;
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
               
                foreach($rs as $result){
                    $narration.=" & ". $result->productname;
                    $objshareamt->narration="Cash Deposit-".$result->productname;
                    $objshareamt->date=date("Y-m-d", strtotime($data[0]));
                    $objshareamt->client_no=$mem->id;
                    $objshareamt->paydet=$data[2];
                    $objshareamt->isCharge=0;
                    $objshareamt->branchno=$branch;
                    $objshareamt->total=str_replace( ',', '',$data[4]);;
                    $objshareamt->category='shares';
                    $objshareamt->moneyin=str_replace( ',', '',$data[4]);;

                                    // Accounts 
                                    // inserting into accountrans  savings 1 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                                    $objaccountrans->total=str_replace( ',', '',$data[4]);
                                    $objaccountrans->accountcode=$result->savingac;
                                    $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                    $objaccountrans->ttype="C";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->stockidentify=$data[2];
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                                    $objaccountrans->save();
                                     // inserting into accountrans  cash account 
                                    $objaccountrans=new accounttrans;
                                    $objaccountrans->purchaseheaderid=$objheaders->id;
                                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                                    $objaccountrans->total=str_replace( ',', '',$data[4]);
                                    $objaccountrans->accountcode=$result->operatingac;
                                    $objaccountrans->narration=$mem->name." -Cash Deposits ".$result->productname;
                                    $objaccountrans->ttype="D";
                                    $objaccountrans->cat='shares';
                                    $objaccountrans->stockidentify=$data[2];
                                    $objaccountrans->bracid=auth()->user()->branchid;
                                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                                    $objaccountrans->save();
                 
        ############################# Shares ######################################################
                }
                            
    
               }
               else{
     
                $rs=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
                foreach($rs as $result){ 
                    $objsaving4->date=date("Y-m-d", strtotime($data[0]));
                    $objsaving4->client_no=$mem->id;
                    $objsaving4->paydet=$data[2];
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
                $objaccountrans->stockidentify=$data[2];
                $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
                $objaccountrans->stockidentify=$data[2];
                $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                $objaccountrans->save();
                } 
               }
           $objallsavings->narration=$narration." Deposit";
           $objallsavings->headerid=$objheaders->id;
           $objallsavings->save();
           // saving id in savings table
           $rs1=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs1->count()>0){
            $objsaving1->savingsid=$objallsavings->id; 
            $objsaving1->save();
           }
           $rs2=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs2->count()>0){
            $objsaving2->savingsid=$objallsavings->id; 
            $objsaving2->save();
           }
           $rs3=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs3->count()>0){
            $objsaving3->savingsid=$objallsavings->id; 
            $objsaving3->save();
           }
           $rs4=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs4->count()>0){
            $objsaving4->savingsid=$objallsavings->id; 
            $objsaving4->save();
           }
           $rs4=savingdefinations::where('savingpdt','=','shares')->where('isActive','=',1)->where('branchno','=',$branch)->get();
           if($rs4->count()>0){
            $objshareamt->savingsid=$objallsavings->id; 
            $objshareamt->save();
           }

           


	   }
	}else if($data[6]=="W"){
        $ac=$data[1];
		$member=DB::select("select name,id,acno from customers where acno=$ac");
foreach($member as $mem){
       // Obtaining header number from purchaseheaders table 
       $objheaders= new purchaseheaders();
       $objheaders->transdates=date("Y-m-d", strtotime($data[0]));
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
   $objallsavings->client_no=$mem->id;
   $objallsavings->branchno=$branch;
   $objallsavings->recieptno=$data[2];
   $objallsavings->date=date("Y-m-d", strtotime($data[0]));
   $narration="";
   if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt1'){
####################### START OF SAVING PDT 1 ######################################################
$rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt1=str_replace( ',', '',$data[4])*-1; 
                    
            }
        }
        $narration= $result->productname;
        $objsaving1->narration="Cash Withdraw-".$result->productname; 
        $objsaving1->branchno=$branch;
        $objsaving11->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving11->branchno=$branch;
        // Accounts 
                    // inserting into accountrans  savings 1 
     $objaccountrans=new accounttrans;
     $objaccountrans->purchaseheaderid=$objheaders->id;
     $objaccountrans->amount=str_replace( ',', '',$data[4]);
     $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
     $objaccountrans->accountcode=$result->savingac;
     $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
     $objaccountrans->ttype="D";
     $objaccountrans->bracid=auth()->user()->branchid;
     $objaccountrans->cat='savingpdt1';
     $objaccountrans->stockidentify=$data[2];
     $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
     $objaccountrans->save();
      // inserting into accountrans  cash account 
     $objaccountrans=new accounttrans;
     $objaccountrans->purchaseheaderid=$objheaders->id;
     $objaccountrans->amount=str_replace( ',', '',$data[4]);
     $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
     $objaccountrans->accountcode=$result->operatingac;
     $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
     $objaccountrans->ttype="C";
     $objaccountrans->cat='savingpdt1';
     $objaccountrans->stockidentify=$data[2];
     $objaccountrans->bracid=auth()->user()->branchid;
     $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
     $objaccountrans->save();
                 // savings Table 
    $objsaving1->date=date("Y-m-d", strtotime($data[0]));
    $objsaving1->client_no=$mem->id;
    $objsaving1->paydet=$data[2];
    $objsaving1->isCharge=0;
    $objsaving1->branchno=$branch;
    $objsaving1->total=str_replace( ',', '',$data[4])*-1;
    $objsaving1->category='savingpdt1';
    $objsaving1->moneyout=str_replace( ',', '',$data[4]);
    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
    // Savings Withdraw Accounts 
    if($savingrs->count()>0){
        foreach($savingrs as $with){
        $objallsavings->savingpdt1=(str_replace( ',', '',$data[4])+$with->charge)*-1;
                         // savings2 Table 
    $objsaving11->date=date("Y-m-d", strtotime($data[0]));
    $objsaving11->client_no=$mem->id;
    $objsaving11->paydet=$data[2];
    $objsaving11->isCharge=1;
    $objsaving11->branchno=$branch;
    $objsaving11->total=$with->charge*-1;
    $objsaving11->category='savingpdt1';
    $objsaving11->moneyout=$with->charge;
        $objaccountrans=new accounttrans;
        $objaccountrans->purchaseheaderid=$objheaders->id;
        $objaccountrans->amount=$with->charge;
        $objaccountrans->total=$with->charge;
        $objaccountrans->accountcode="6003";
        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
        $objaccountrans->ttype="C";
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->cat='savingpdt1';
        $objaccountrans->stockidentify=$data[2];
        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
        $objaccountrans->stockidentify=$data[2];
        $objaccountrans->bracid=auth()->user()->branchid;
        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
        $objaccountrans->save();
        }
    }
    }

    
####################### END OF SAVING PDT 1 ######################################################
   }else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt1')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){  
        $objsaving1->date=date("Y-m-d", strtotime($data[0]));
        $objsaving1->client_no=$mem->id;
        $objsaving1->paydet=$data[2];
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
    $objaccountrans->cat='savingpdt1';
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
    $objaccountrans->save();
    }
   }
   if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt2'){
###################### START OF SAVING PDT 2 ####################################################        
    $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $objsaving2->narration="Cash Withdraw-".$result->productname;
        $objsaving2->branchno=$branch;
        $objsaving22->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving22->branchno=$branch;
                                 // savings Table 
    $objsaving2->date=date("Y-m-d", strtotime($data[0]));
    $objsaving2->client_no=$mem->id;
    $objsaving2->paydet=$data[2];
    $objsaving2->isCharge=0;
    $objsaving2->branchno=$branch;
    $objsaving2->total=str_replace( ',', '',$data[4])*-1;
    $objsaving2->category='savingpdt2';
    $objsaving2->moneyout=str_replace( ',', '',$data[4]);;
    //
    $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
    foreach($isCharge as $char){
        if($char->charge==0){
            $objallsavings->savingpdt2=str_replace( ',', '',$data[4])*-1; 
                
        }
    }
                    // Accounts 
                    // inserting into accountrans  savings 1 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                    $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                    $objaccountrans->accountcode=$result->savingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="D";
                    $objaccountrans->cat='savingpdt2';
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->stockidentify=$data[2];
                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                    $objaccountrans->save();
                     // inserting into accountrans  cash account 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                    $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                    $objaccountrans->accountcode=$result->operatingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="C";
                    $objaccountrans->cat='savingpdt2';
                    $objaccountrans->stockidentify=$data[2];
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                    $objaccountrans->save();

                    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                    // Savings Withdraw Accounts 
                    if($savingrs->count()>0){
                        foreach($savingrs as $with){
                            $objallsavings->savingpdt2=(str_replace( ',', '',$data[4])+$with->charge)*-1;
                            // savings2 Table 
                          $objsaving22->date=date("Y-m-d", strtotime($data[0]));
                          $objsaving22->client_no=$mem->id;
                          $objsaving22->paydet=$data[2];
                          $objsaving22->isCharge=1;
                          $objsaving22->branchno=$branch;
                          $objsaving22->total=$with->charge*-1;
                          $objsaving22->category='savingpdt2';
                          $objsaving22->moneyout=$with->charge;    
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge;
                        $objaccountrans->accountcode="6003";
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->cat='savingpdt2';
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                        $objaccountrans->save();
                        }
                    }

########################### END OF SAVING PDT 2 ################################################## 
    }

   }else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt2')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){ 
        $objsaving2->date=date("Y-m-d", strtotime($data[0]));
        $objsaving2->client_no=$mem->id;
        $objsaving2->paydet=$data[2];
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
    $objaccountrans->save();
    } 
   }
   if(str_replace( ',', '',$data[4])>0  && $data[5]=='savingpdt3'){
########################## sTART OF SAVING PDT 3 ##############################################            
    $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt3=str_replace( ',', '',$data[4])*-1; 
                    
            }
        }
        $objsaving33->narration="Cash Withdraw Fees-".$result->productname;
        $narration.=" & ". $result->productname;
        $objsaving3->narration="Cash Withdraw-".$result->productname;
        $objsaving3->date=date("Y-m-d", strtotime($data[0]));
        $objsaving3->client_no=$mem->id;
        $objsaving3->paydet=$data[2];
        $objsaving3->isCharge=0;
        $objsaving3->branchno=$branch;
        $objsaving3->total=str_replace( ',', '',$data[4])*-1;;
        $objsaving3->category='savingpdt3';
        $objsaving3->moneyout=str_replace( ',', '',$data[4]);;
                                
// Accounts 
                    // inserting into accountrans  savings 1 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                    $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                    $objaccountrans->accountcode=$result->savingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="D";
                    $objaccountrans->cat='savingpdt3';
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->stockidentify=$data[2];
                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                    $objaccountrans->save();
                     // inserting into accountrans  cash account 
                    $objaccountrans=new accounttrans;
                    $objaccountrans->purchaseheaderid=$objheaders->id;
                    $objaccountrans->amount=str_replace( ',', '',$data[4]);
                    $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                    $objaccountrans->accountcode=$result->operatingac;
                    $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                    $objaccountrans->ttype="C";
                    $objaccountrans->cat='savingpdt3';
                    $objaccountrans->stockidentify=$data[2];
                    $objaccountrans->bracid=auth()->user()->branchid;
                    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                    $objaccountrans->save();
                    $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                    // Savings Withdraw Accounts 
                    if($savingrs->count()>0){
                        foreach($savingrs as $with){
                                    $objallsavings->savingpdt3=(str_replace( ',', '',$data[4])+$with->charge)*-1;
                         // savings2 Table 
                  $objsaving33->date=date("Y-m-d", strtotime($data[0]));
                  $objsaving33->client_no=$mem->id;
                  $objsaving33->paydet=$data[2];
                  $objsaving33->isCharge=1;
                  $objsaving33->branchno=$branch;
                  $objsaving33->total=$with->charge*-1;
                  $objsaving33->category='savingpdt3';
                  $objsaving33->moneyout=$with->charge;
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=$with->charge;
                        $objaccountrans->total=$with->charge;
                        $objaccountrans->accountcode="6003";
                        $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->cat='savingpdt3';
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                        $objaccountrans->save();
                        }
                    }
################################## end OF SAVING PDT 3 ##########################################
    }

   }else{
    $rs=savingdefinations::where('savingpdt','=','savingpdt3')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){  
        $objsaving3->date=date("Y-m-d", strtotime($data[0]));
        $objsaving3->client_no=$mem->id;
        $objsaving3->paydet=$data[2];
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
    $objaccountrans->save();
    } 
   }
   if(str_replace( ',', '',$data[4])>0 && $data[5]=='savingpdt4'){
################################# START OF SAVING PDT 4 #########################################
    //$objallsavings->savingpdt4=str_replace( ',', '',$data[4])*-1;;
    $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
   
    foreach($rs as $result){
        $isCharge=savingsproducts::where('accountcode','=',$result->savingac)->where('isActive','=',1)->get();
        foreach($isCharge as $char){
            if($char->charge==0){
                $objallsavings->savingpdt4=str_replace( ',', '',$data[4])*-1; 
                    
            }
        }
        $narration.=" & ". $result->productname;
        $objsaving44->narration="Cash Withdraw Fees-".$result->productname;
        $objsaving44->branchno=$branch;
        $objsaving4->branchno=$branch;
        $objsaving4->narration="Cash Withdraw-".$result->productname;
        $objsaving4->date=date("Y-m-d", strtotime($data[0]));
        $objsaving4->client_no=$mem->id;
        $objsaving4->paydet=$data[2];
        $objsaving4->isCharge=0;
        $objsaving4->total=str_replace( ',', '',$data[4])*-1;;
        $objsaving4->category='savingpdt4';
        $objsaving4->moneyout=str_replace( ',', '',$data[4]);;
                        // Accounts 
                        // inserting into accountrans  savings 1 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=str_replace( ',', '',$data[4]);
                        $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                        $objaccountrans->accountcode=$result->savingac;
                        $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                        $objaccountrans->ttype="D";
                        $objaccountrans->cat='savingpdt4';
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                        $objaccountrans->save();
                         // inserting into accountrans  cash account 
                        $objaccountrans=new accounttrans;
                        $objaccountrans->purchaseheaderid=$objheaders->id;
                        $objaccountrans->amount=str_replace( ',', '',$data[4]);
                        $objaccountrans->total=str_replace( ',', '',$data[4])*-1;
                        $objaccountrans->accountcode=$result->operatingac;
                        $objaccountrans->narration=$mem->name." -Cash Withdraw ".$result->productname;
                        $objaccountrans->ttype="C";
                        $objaccountrans->cat='savingpdt4';
                        $objaccountrans->stockidentify=$data[2];
                        $objaccountrans->bracid=auth()->user()->branchid;
                        $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                        $objaccountrans->save();
                        $savingrs=savingsproducts::where('accountcode','=',$result->savingac)->where('charge','>',0)->get();
                        // Savings Withdraw Accounts 
                        if($savingrs->count()>0){
                            foreach($savingrs as $with){
                                $objallsavings->savingpdt4=(str_replace( ',', '',$data[4])+$with->charge)*-1;
                                $objsaving44->date=date("Y-m-d", strtotime($data[0]));
                                $objsaving44->client_no=$mem->id;
                                $objsaving44->paydet=$data[2];
                                $objsaving44->isCharge=1;
                                $objsaving44->total=$with->charge*-1;
                                $objsaving44->category='savingpdt4';
                                $objsaving44->moneyout=$with->charge;
                            $objaccountrans=new accounttrans;
                            $objaccountrans->purchaseheaderid=$objheaders->id;
                            $objaccountrans->amount=$with->charge;
                            $objaccountrans->total=$with->charge;
                            $objaccountrans->accountcode="6003";
                            $objaccountrans->narration=$mem->name." -Withdraw Fees ".$result->productname;
                            $objaccountrans->ttype="C";
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->cat='savingpdt4';
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
                            $objaccountrans->stockidentify=$data[2];
                            $objaccountrans->bracid=auth()->user()->branchid;
                            $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
                            $objaccountrans->save();
                            }
                        }
     
############################# END OF SAVING PDT 4 ######################################################
    }
                

   }
   else{

    $rs=savingdefinations::where('savingpdt','=','savingpdt4')->where('isActive','=',1)->where('branchno','=',$branch)->get();
    foreach($rs as $result){ 
        $objsaving4->date=date("Y-m-d", strtotime($data[0]));
        $objsaving4->client_no=$mem->id;
        $objsaving4->paydet=$data[2];
        $objsaving4->isCharge=0;
        $objsaving4->branchno=$branch;
        $objsaving4->total=0;
        $objsaving4->category='savingpdt4';
        $objsaving4->moneyout=0; 
        $objsaving44->date=date("Y-m-d", strtotime($data[0]));
        $objsaving44->client_no=$mem->id;
        $objsaving44->paydet=$data[2];
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
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
    $objaccountrans->stockidentify=$data[2];
    $objaccountrans->transdate=date("Y-m-d", strtotime($data[0]));
    $objaccountrans->save();
    } 
   }
   if($data[4]>0 && $data[5]=="savingpdt5"){
    $objallsavings->savingpdt5=$data[4];

    $rs=savingdefinations::where('savingpdt','=','savingpdt5')->where('branchno','=',$branch)->get();
    foreach($rs as $result){
        $narration.=" & ". $result->productname;
        $objsaving->narration="Cash Withdraw-".$result->productname;
    }
                
    $objsaving5->date=date("Y-m-d", strtotime($data[0]));
    $objsaving5->client_no=$mem->id;
    $objsaving5->paydet=$data[2];
    $objsaving5->isCharge=0;
    $objsaving5->branchno=$branch;
    $objsaving5->total=$data[4]*-1;
    $objsaving5->category='savingpdt5';
    $objsaving5->moneyout=$data[4];

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

   


}





	}
	}else{
		echo "No";
	}
 }catch(\Exception $e){
    DB::rollback();
      echo "Failed ".$e;
  }
  DB::commit();
	
		}
	} 
		 
	 }


}