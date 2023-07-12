<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CodeGeneratorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        
     return view('CodeGenerator/index');

            
       // }
    }

    public function buildcode(Request $request){
     $tablename=$request['table'];
     $tablecolumns=DB::select("Describe ".$tablename);
     //create view as the table selected 
     if(!is_dir($tablename)){
mkdir('../resources/views/'.$tablename);
        $file=fopen('../resources/views/'.$tablename.'/'."index.blade.php","w");
         $content="@include('layouts/header')";
         fwrite($file,$content);
//writing models
$model=fopen('../app/'.$tablename.'.php',"w");
$modelContent="<?php\nnamespace App;\nuse Illuminate\Database\Eloquent\Model;\n class ".$tablename." extends Model\n{\n}";
fwrite($model,$modelContent);

// writing routes
//Index route
$route=fopen('../routes/web.php',"a");
$routeContent="\n// Auto generated Routes for table $tablename 
Route::get('/".$tablename."','".$tablename."Controller@index');
Route::get('/view".$tablename."','".$tablename."Controller@view');
Route::post('/save".$tablename."','".$tablename."Controller@save');
Route::post('/edit".$tablename."/{id}','".$tablename."Controller@update');
Route::post('/destroy".$tablename."/{id}','".$tablename."Controller@destroy');
Route::get('/combo".$tablename."','".$tablename."Controller@viewcombo');";            //"'.$tablename."Controller@view');";
fwrite($route,$routeContent);


//Writing Controllers
 
$controller=fopen('../app/Http/Controllers/'.$tablename.'Controller.php',"w");
$controllerContent="<?php \n namespace App\Http\Controllers;\nuse Illuminate\Http\Request;\n use Illuminate\Support\Facades\DB;\nuse App\\".$tablename.";
\n class ".$tablename."Controller extends Controller{
\npublic function index(){
    return view('".$tablename."/index');\n}\npublic function view(){\n
        "."$"."results=array();
        "."$"."page = isset("."$"."_GET['page']) ? intval("."$"."_GET['page']) : 1;
        "."$"."rows = isset("."$"."_GET['rows']) ? intval("."$"."_GET['rows']) : 10;
        "."$"."offset = ("."$"."page-1)*"."$"."rows;
        "."$"."rs =DB::getPdo();
        "."$"."krows = DB::select('select COUNT(*) as count from $tablename ');
        "."$"."results['total']="."$"."krows[0]->count;
        "."$"."rst =  DB::getPdo()->prepare(\"select * from $tablename limit "."$"."offset,"."$"."rows\");
        "."$"."rst->execute();
    
        "."$"."viewall = "."$"."rst->fetchAll(\PDO::FETCH_OBJ);
       "."$"."results['rows']="."$"."viewall;
       echo json_encode("."$"."results);

    \n}\npublic function save(Request $"."request){
    "."$"."Obj$tablename=new $tablename();";
$objts="";
    foreach($tablecolumns as $tblcols){
    $objts.="\n"."$"."Obj$tablename->".head($tblcols)."=$"."request['".head($tblcols)."'];";//\nObj$tablename->'save()'";

    }
    $objts.="\n"."$"."Obj$tablename->"."save();\n}\n//Auto generated code for updating\npublic function update(Request "."$"."request,"."$"."id){
        "."$"."Obj$tablename=$tablename::find("."$"."id);
";
foreach($tablecolumns as $tblcols){
    $objts.="\n"."$"."Obj$tablename->".head($tblcols)."=$"."request['".head($tblcols)."'];";//\nObj$tablename->'save()'";

    }

    $objts.="\n"."$"."Obj$tablename->"."save();\n}\n public function destroy("."$"."id){
        "."$"."Obj$tablename=$tablename::find("."$"."id);
        "."$"."Obj$tablename->"."delete();



    }\n
public function viewcombo(){


    return $tablename::all();
}
}";
$controllerContent.=$objts;
    //fwrite($controllerContent,"}\n}");

fwrite($controller,$controllerContent);

// writing html to the index papge
$appendIndex=fopen('../resources/views/'.$tablename.'/'."index.blade.php","a");
$dlghead="\n<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlg".$tablename."' toolbar='#".$tablename."'><form id='frm".$tablename."'>";
fwrite($appendIndex,$dlghead);
foreach($tablecolumns as $tblcolums){
 $indexContent="\n<div class='col-lg-6'>\n<div class='form-group'>\n<div><label>".head($tblcolums)."</label></div><input type='text' class='form-control' name='".head($tblcolums)."' 
 id='".head($tblcolums)."' /></div>\n</div>";

 fwrite($appendIndex,$indexContent);
} 
$dialogfoot="\n<div style='padding:5px;' id='".$tablename."' /><a href='javascript:void(0)' class='btn btn-primary'id='save".$tablename."'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>\n</div></div>";
fwrite($appendIndex,$dialogfoot);
//dialog toolbar
//writing data-grid
$datagridhead="\n<table class='easyui-datagrid' title='$tablename' iconCls='fa fa-table' singleSelect='true' url='view".$tablename."' pagination='true' id='grid".$tablename."' method='get' fitColumns='true' style='width:100%' toolbar='#".$tablename."toolbar'>
<thead><tr>";
fwrite($appendIndex,$datagridhead);

//fetching table column names 
foreach($tablecolumns as $columns){
$datagridContent="\n<th field='".head($columns)."' width='100'>".head($columns)."</th>";
fwrite($appendIndex,$datagridContent);
}
$datagridfoot="\n</tr></thead>\n</table>\n<div id='".$tablename."toolbar'>\n <a href='javascript:void(0)' class='easyui-linkbutton' id='new".$tablename."' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='edit".$tablename."' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='delete".$tablename."' iconCls='icon-remove' > Delete</a> </div>
";
$javascip="\n{{csrf_field()}}\n<script>\n var url;\n $(document).ready(function(){\n//Auto Generated code for New Entry Code
    
   $('#new".$tablename."').click(function(){
       $('#dlg".$tablename."').dialog('open').dialog('setTitle','New $tablename');\nurl='/save$tablename';\n"."$"."('#frm".$tablename."').form('clear');\n});\n
       //Auto Generated code for Edit Code\n $('#edit".$tablename."').click(function(){
       \n var row=$('#grid".$tablename."').datagrid('getSelected');
       $('#dlg".$tablename."').dialog('open').dialog('setTitle','Edit $tablename');

       $('#frm".$tablename."').form('load',row);
       url='/edit$tablename/'+row.id;
       
       
       });\n//Auto Generated Code for Saving\n$('#save".$tablename."').click(function(){ ";  


       
      // $closeonclick=" \n});";
      // fwrite($appendIndex,$closeonclick);

 fwrite($appendIndex,$datagridfoot);
fwrite($appendIndex,$javascip);
    foreach($tablecolumns as $tbcols){
    $getvalues="\nvar ".head($tbcols)."=$('#".head($tbcols)."').val();";
    fwrite($appendIndex,$getvalues);
    }
   
$ajaxdata="{";
foreach($tablecolumns as $ajxdta){
$ajaxdata.="'".head($ajxdta)."':".head($ajxdta).",";
}
$ajaxdata.="'_token':$('input[name=_token]').val()}\n});
  \n"."$"."('#dlg".$tablename."').dialog('close');
  \n"."$"."('#grid".$tablename."').datagrid('reload');";
    $ajaxcontents="\n$.ajax({
url:url,
method:'POST',";
$data="\ndata:".$ajaxdata;



   
   $onready="\n});\n</script>";
fwrite($appendIndex,$ajaxcontents);
fwrite($appendIndex,$data);
fwrite($appendIndex,"\n});\n//Auto generated code for deleting\n$('#delete$tablename').click(function(){\n
    var a=$('#grid$tablename').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#grid$tablename').datagrid('getSelected');
                $.ajax({
                 url:'/destroy$tablename/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#grid$tablename').datagrid('reload');
            }

});\n}\n});
");
// End of on save dialog button

// creating save Route Url

fwrite($appendIndex,$onready);

 
       

  //
//});   </script>";




        }
     

    }
    public function gettblnames(){

        $names=array();
        $tables = DB::select("show tables");
        foreach($tables as $tbl){
         
        array_push($names,head($tbl));
       
        }
        echo json_encode($names);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

  
}
