<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
 class getLoanbal 
{
	public function getInterestRBal($loanId,$interestBal,$creditinterest){
    $loandet2=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where loanid=$loanId  order by loanrepayments.id ");
    foreach($loandet2 as $loandet){
       // return $loandet->intrunbal. "the last";
        if($interestBal==$loandet->intrunbal){ //&& $loandet->intrunbal>$interestBal
            //if($creditinterest==0){
               // return 0;
                //break;
            //}else{
            return true;// ($loandet->intrunbal-$interestBal);
            break; 
           // }
           }
           if($interestBal!=$loandet->intrunbal && $loandet->intrunbal>$interestBal){
               return ($loandet->intrunbal-$interestBal);
           }

}
}
public function getLoanRBal($loanId,$loanBal){
    $loandet2=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where loanid=$loanId  order by loanrepayments.id ");
    foreach($loandet2 as $loandet){
        if($loanBal==$loandet->loanrunbal){ //&& $loandet->intrunbal>$interestBal
            return $loandet->loanrunbal;
            break; 
           }
           if($loanBal!=$loandet->loanrunbal && $loandet->loanrunbal>$loanBal){
               return ($loandet->loanrunbal);
           }
}
}
public function getintRBal($loanId,$loanBal){
    $loandet2=DB::select("select intrunbal,loanrunbal from loanrepayments inner join loaninfos  on loanrepayments.loanid=loaninfos.id where loanid=$loanId  order by loanrepayments.id ");
    foreach($loandet2 as $loandet){
        if($loanBal==$loandet->intrunbal){ //&& $loandet->intrunbal>$interestBal
            return $loandet->intrunbal;
            break; 
           }
           if($loanBal!=$loandet->intrunbal && $loandet->intrunbal>$loanBal){
               return ($loandet->intrunbal);
           }
}
}
public function getPosition($loanbal,$loanrun,$loanid,$type){
   if($type=="loan"){
    if($loanbal==$loanrun){
        return ($this->getLoanRBal($loanid,$loanbal)+1);
    }else{
        return $this->getLoanRBal($loanid,$loanbal);
    }
} if ($type=="interest"){
    if($loanbal==$loanrun){
        return ($this->getintRBal($loanid,$loanbal)+1);
    }else{
        return $this->getintRBal($loanid,$loanbal);
    }  
}
}
}