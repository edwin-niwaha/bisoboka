@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmodeofpayments' toolbar='#modeofpayments'><form id='frmmodeofpayments'>
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
<div class='col-lg-6'>
    <div class='form-group'>
    <div><label>isDefault</label></div><input type='text' class='form-control' name='isDefault' 
     id='isDefault' /></div>
    </div>
<div style='padding:5px;' id='modeofpayments' /><a href='javascript:void(0)' class='btn btn-primary'id='savemodeofpayments'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='modeofpayments' iconCls='fa fa-table' singleSelect='true' url='viewmodeofpayments' pagination='true' id='gridmodeofpayments' method='get' fitColumns='true' style='width:100%' toolbar='#modeofpaymentstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='isActive' width='100'>isActive</th>
<th field='isDefault' width='100'>isDefault</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='modeofpaymentstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmodeofpayments' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmodeofpayments' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletemodeofpayments' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmodeofpayments').click(function(){
       $('#dlgmodeofpayments').dialog('open').dialog('setTitle','New modeofpayments');
url='/savemodeofpayments';
$('#frmmodeofpayments').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmodeofpayments').click(function(){
       
 var row=$('#gridmodeofpayments').datagrid('getSelected');
       $('#dlgmodeofpayments').dialog('open').dialog('setTitle','Edit modeofpayments');

       $('#frmmodeofpayments').form('load',row);
       url='/editmodeofpayments/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savemodeofpayments').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var isDefault=$('#isDefault').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'isDefault':isDefault,'_token':$('input[name=_token]').val()}
});
  
$('#dlgmodeofpayments').dialog('close');
  
$('#gridmodeofpayments').datagrid('reload');
});
//Auto generated code for deleting
$('#deletemodeofpayments').click(function(){

    var a=$('#gridmodeofpayments').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmodeofpayments').datagrid('getSelected');
                $.ajax({
                 url:'/destroymodeofpayments/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmodeofpayments').datagrid('reload');
            }

});
}
});

});
</script>