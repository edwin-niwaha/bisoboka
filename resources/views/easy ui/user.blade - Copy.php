@include('layouts/layout')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"
               integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
               crossorigin="anonymous">
</script>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/color.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/demo/demo.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
{{csrf_field()}}
<script>
 $(document).ready(function(event){


});  


$(document).ready(function(){
    $("#btns").click(function(event){
       var text=$("#name").val();
     
       $.post('/list',{'text1':text,'_token':$('input[name=_token]').val()},function(data){
console.log(data);

       });
    });

$("#del").click(function(){
var a=$('#datagrid').datagrid('getSelected');
//console.log(a.id);
var url='/list/'+a.id;
console.log(url);
$.post(url,{'_method':'delete','_token':$('input[name=_token]').val()},function(data){

console.log(data);
});

});




});      
</script>
<body>
    <table  class="easyui-datagrid" pagination="true" method="get" singleSelect="true" url="users" id="datagrid" fitColumns="true" style="width:750px;" toolbar="#toolbar">
    <thead>
    <tr>
    <th field="created_at" width="50">Name</th>
    <th field="updated_at" width="50">Color</th>
    <th field="id" width="50">Engine Number</th>
    <th field="stockname" width="50">No of Seats</th>
    <th field="doors" width="50">No of Doors</th>
    </tr>
    
    </thead>
    </table>
    <div class="container">
        <form >
<div class="form-group">

    <input type="text" class="form-control" id="name"/>
</div>
<div class="form-group">

        <input type="button" class="btn btn-primary" value="press" id="btns"/>
        <input type="button" class="btn btn-primary" value="Delete" id="del"/>
    </div>
</form>
 <body>
    <p>Click on this paragraph.</p>
    <ul class="easyui-tree">
        <li><span>Accounts</span></li>
    <ul class="easyui-tree">
        <li><span>Payment</span></li>
        <li><span>Reciept</span></li>
    </ul>
    <li><span>Utilites</span></li>
    <ul>
        <li><span>change password</span></li>
    </ul>
</ul>
   </body>
</html>