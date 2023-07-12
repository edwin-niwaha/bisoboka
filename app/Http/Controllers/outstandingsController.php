<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\stocks;

 class outstandingsController extends Controller{

public function index(){

    return view('outstandings/index');
}

public function outstanding(){

return DB::select('select * from purchaseheaders inner join stocktrans on purchaseheaders.id=stocktrans.purchaseheaderid inner join customers on customers.id=purchaseheaders.customer_id where totaldue !=0');

}


 }

 