@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgprocurements' toolbar='#procurements'><form id='frmprocurements'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>role</label></div><input type='text' class='form-control' name='role' 
 id='role' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>updated_at</label></div><input type='text' class='form-control' name='updated_at' 
 id='updated_at' /></div>
</div>
<div style='padding:5px;' id='procurements' /><a href='javascript:void(0)' class='btn btn-primary'id='saveprocurements'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' singleSelect='true' url='viewprocurements' pagination='true' id='gridprocurements' method='get' fitColumns='true' style='width:100%' toolbar='#procurementstoolbar'>
<thead><tr>
<th field='id' width='50'>id</th>
<th field='name' width='50'>name</th>
<th field='role' width='50'>role</th>
<th field='created_at' width='50'>created_at</th>
<th field='updated_at' width='50'>updated_at</th>
</tr></thead>
</table>
<div id='procurementstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newprocurements' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editprocurements' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteprocurements' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newprocurements').click(function(){
       $('#dlgprocurements').dialog('open');
url='/saveprocurements';
$('#frmprocurements').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editprocurements').click(function(){
       
 var row=$('#gridprocurements').datagrid('getSelected');
       $('#dlgprocurements').dialog('open');
       $('#frmprocurements').form('load',row);
       url='/editprocurements/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveprocurements').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var role=$('#role').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'role':role,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
$('#gridprocurements').datagrid('reload');
  
$('#dlgprocurements').dialog('close');
});
//Auto generated code for deleting
$('#deleteprocurements').click(function(){

    var a=$('#gridprocurements').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridprocurements').datagrid('getSelected');
                $.ajax({
                 url:'/destroyprocurements/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridprocurements').datagrid('reload');
            }

});
}
});

});
</script>