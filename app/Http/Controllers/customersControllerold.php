<?php 
 namespace App\Http\Controllers;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
use App\customers;

 class customersController extends Controller{

public function index(){
    return view('customers/customers');
}
public function view(){
    $bra=auth()->user()->branchid;
    $admin=auth()->user()->isAdmin;

        $results=array();
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $offset = ($page-1)*$rows;
        $rs =DB::getPdo();
        
            $krows = DB::select("select COUNT(*) as count from customers where branchnumber=$bra");   

        $results['total']=$krows[0]->count;
            $rst =  DB::getPdo()->prepare("select customers.isActive as isActive,acno,if(customers.isActive=1,'Yes','No') as act,customers.id,nextofkin,identityno,nextofkinconc,rshipkin,placeofwork,natureofwork,customers.name,phone,image,address,tel,customers.email FROM `customers` where customers.branchnumber=$bra order by name asc  limit $offset,$rows"); 
            $rst->execute();

        
        
    
        $viewall = $rst->fetchAll(\PDO::FETCH_OBJ);
       $results['rows']=$viewall;
       echo json_encode($results);
       

    
}
public function save(Request $request){
    $name=$request['name'];
   //$results=DB::select("select name from customers where name='$name'");
  // foreach($results as $result){
      
      // if($result->name!='h'){
          try{
    
    $Objcustomers= new customers();
    $file=$request->file('image');
    $destinationPath="images";
    if($file!=Null){
        $filename=$file->getClientOriginalName();
        //moving it to the folder
        $file->move($destinationPath,$filename);
        $Objcustomers->image=$filename;

    }
    $Objcustomers->nextofkin=$request['nextofkin'];
    $Objcustomers->identityno=$request['identityno'];
    $Objcustomers->nextofkinconc=$request['nextofkinconc'];
    $Objcustomers->rshipkin=$request['rshipkin'];
    $Objcustomers->placeofwork=$request['placeofwork'];
    $Objcustomers->natureofwork=$request['natureofwork'];
    $Objcustomers->name=$request['name'];
    $Objcustomers->acno=$request['acno'];
    $Objcustomers->address=$request['address'];
    $Objcustomers->phone=$request['phone'];
    $Objcustomers->isActive=$request['isActive'];
    $Objcustomers->branchnumber=auth()->user()->branchid;


    
$Objcustomers->save();
          }catch(\Exception $e){
              echo "Failure".$e;
          }
   
//}/*else{
   //return ['exist'=>'true']; 
//}
//*/
//}
}
//Auto generated code for updating
public function update(Request $request,$id){
        $Objcustomers=customers::find($id);
        $file=$request->file('image');
    $destinationPath="images";
    if($file!=Null){
        $filename=$file->getClientOriginalName();
        //moving it to the folder
        $file->move($destinationPath,$filename);
        $Objcustomers->image=$filename;

    }
    $Objcustomers->nextofkin=$request['nextofkin'];
    $Objcustomers->identityno=$request['identityno'];
    $Objcustomers->nextofkinconc=$request['nextofkinconc'];
    $Objcustomers->rshipkin=$request['rshipkin'];
    $Objcustomers->placeofwork=$request['placeofwork'];
    $Objcustomers->natureofwork=$request['natureofwork'];
    $Objcustomers->name=$request['name'];
    $Objcustomers->acno=$request['acno'];
    $Objcustomers->address=$request['address'];
    $Objcustomers->phone=$request['phone'];
    $Objcustomers->isActive=$request['isActive'];
    $Objcustomers->branchnumber=auth()->user()->branchid;


    
$Objcustomers->save();


}
 public function destroy($id){
        //if(auth()->user()->isAdmin==1){
        $Objcustomers=customers::find($id);
        $Objcustomers->delete();
     //}else{
        // return ['deleted'=>'no'];
     //}



    }

public function viewcombo(){
    
    $bra=auth()->user()->branchid;

        return  DB::select("select customers.id,customers.name,phone,address,tel,customers.email,branchnumber,branchid from customers inner join users on customers.branchnumber=users.branchid where branchid=$bra group by customers.id order by name asc");
    
  
  //  DB::select("select * from customers inner join users on customers.branchnumber=users.branchid ");
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
    $customers->phone=$data[1];
    $customers->address=$data[3];
    //$customers->tel=$data[3];
    //$customers->email=$data[4];
    $customers->branchnumber=$bra;
    $customers->identityno=$data[2];
    $customers->placeofwork=$data[5];
    $customers->natureofwork=$data[4];
    $customers->rshipkin=$data[8];
    $customers->nextofkin=$data[6];
    $customers->nextofkinconc=$data[7];
    $customers->acno=$data[9];
    $customers->isActive=1;
    $customers->save();


}
    }


    }catch(\Exception $e){

        echo "Failed ".$e;
        DB::rollBack();
    }
    DB::commit();
}
}