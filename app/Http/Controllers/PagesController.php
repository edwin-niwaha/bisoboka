<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function index(){
        $interest="this is so fun";
        return view('pages/index')->with('a',$interest);
    }

    public function services()
    {
        $data=array('title'=>'books','services'=>['Php','Javascript','mysql','Html','css']);
        return view('pages/services')->with($data);
    }
    public function about(){
     $a="this is true laravel";
        return view('pages/about')->with('ex',$a);
    }
}
