//$newdate=date_sub($date,$date1);//date('d-m-Y',$date1);
    //echo $newdate;
    $diff=strtotime($date1)-strtotime($date);
    $months=intdiv($diff,(((60*60)*24)*28));
    $done=DB::select("select * from maintenices where isDone=0");
    if(count($done)==0){
    if($months>=1){
    // First date
    $man= new maintenices();
    $man->posteddate=date("Y-m-d", strtotime($date1));
    $man->isDone=1;
    $man->save();
    } 
    
    if($months>1){
        $month=$months-1;
       
           
        }
    for($month;$month>0;$month--){ 
       // Second dates 
    //echo date('d-m-Y',strtotime("-$month months",strtotime($date1)))."<br>";
        
    }
    }