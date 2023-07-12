@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgaccounttypes' toolbar='#accounttypes'><form id='frmaccounttypes'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>accounttype</label></div><input type='text' class='form-control' name='accounttype' 
 id='accounttype' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
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
<div style='padding:5px;' id='accounttypes' /><a href='javascript:void(0)' class='btn btn-primary'id='saveaccounttypes'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='accounttypes' iconCls='fa fa-table' singleSelect='true' url='viewaccounttypes' pagination='true' id='gridaccounttypes' method='get' fitColumns='true' style='width:100%' toolbar='#accounttypestoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='accounttype' width='100'>accounttype</th>
<th field='isActive' width='100'>isActive</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='accounttypestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newaccounttypes' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editaccounttypes' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteaccounttypes' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newaccounttypes').click(function(){
       $('#dlgaccounttypes').dialog('open').dialog('setTitle','New accounttypes');
url='/saveaccounttypes';
$('#frmaccounttypes').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editaccounttypes').click(function(){
       
 var row=$('#gridaccounttypes').datagrid('getSelected');
       $('#dlgaccounttypes').dialog('open').dialog('setTitle','Edit accounttypes');

       $('#frmaccounttypes').form('load',row);
       url='/editaccounttypes/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveaccounttypes').click(function(){ 
var id=$('#id').val();
var accounttype=$('#accounttype').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'accounttype':accounttype,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgaccounttypes').dialog('close');
  
$('#gridaccounttypes').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteaccounttypes').click(function(){

    var a=$('#gridaccounttypes').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridaccounttypes').datagrid('getSelected');
                $.ajax({
                 url:'/destroyaccounttypes/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridaccounttypes').datagrid('reload');
            }

});
}
});

});
</script>