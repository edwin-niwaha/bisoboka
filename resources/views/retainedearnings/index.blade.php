@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgretainedearnings' toolbar='#retainedearnings'><form id='frmretainedearnings'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>amount</label></div><input type='text' class='form-control' name='amount' 
 id='amount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>year</label></div><input type='text' class='form-control' name='year' 
 id='year' /></div>
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
<div style='padding:5px;' id='retainedearnings' /><a href='javascript:void(0)' class='btn btn-primary'id='saveretainedearnings'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='retainedearnings' iconCls='fa fa-table' singleSelect='true' url='viewretainedearnings' pagination='true' id='gridretainedearnings' method='get' fitColumns='true' style='width:100%' toolbar='#retainedearningstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='amount' width='100'>amount</th>
<th field='year' width='100'>year</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='retainedearningstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newretainedearnings' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editretainedearnings' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteretainedearnings' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newretainedearnings').click(function(){
       $('#dlgretainedearnings').dialog('open').dialog('setTitle','New retainedearnings');
url='/saveretainedearnings';
$('#frmretainedearnings').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editretainedearnings').click(function(){
       
 var row=$('#gridretainedearnings').datagrid('getSelected');
       $('#dlgretainedearnings').dialog('open').dialog('setTitle','Edit retainedearnings');

       $('#frmretainedearnings').form('load',row);
       url='/editretainedearnings/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveretainedearnings').click(function(){ 
var id=$('#id').val();
var amount=$('#amount').val();
var year=$('#year').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'amount':amount,'year':year,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgretainedearnings').dialog('close');
  
$('#gridretainedearnings').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteretainedearnings').click(function(){

    var a=$('#gridretainedearnings').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridretainedearnings').datagrid('getSelected');
                $.ajax({
                 url:'/destroyretainedearnings/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridretainedearnings').datagrid('reload');
            }

});
}
});

});
</script>