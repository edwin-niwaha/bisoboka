@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgaccounttrans' toolbar='#accounttrans'><form id='frmaccounttrans'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>accountcode</label></div><input type='text' class='form-control' name='accountcode' 
 id='accountcode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>narration</label></div><input type='text' class='form-control' name='narration' 
 id='narration' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>amount</label></div><input type='text' class='form-control' name='amount' 
 id='amount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>ttype</label></div><input type='text' class='form-control' name='ttype' 
 id='ttype' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>purchaseheaderid</label></div><input type='text' class='form-control' name='purchaseheaderid' 
 id='purchaseheaderid' /></div>
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
<div style='padding:5px;' id='accounttrans' /><a href='javascript:void(0)' class='btn btn-primary'id='saveaccounttrans'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='accounttrans' iconCls='fa fa-table' singleSelect='true' url='viewaccounttrans' pagination='true' id='gridaccounttrans' method='get' fitColumns='true' style='width:100%' toolbar='#accounttranstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='accountcode' width='100'>accountcode</th>
<th field='narration' width='100'>narration</th>
<th field='amount' width='100'>amount</th>
<th field='ttype' width='100'>ttype</th>
<th field='purchaseheaderid' width='100'>purchaseheaderid</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='accounttranstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newaccounttrans' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editaccounttrans' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteaccounttrans' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newaccounttrans').click(function(){
       $('#dlgaccounttrans').dialog('open').dialog('setTitle','New accounttrans');
url='/saveaccounttrans';
$('#frmaccounttrans').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editaccounttrans').click(function(){
       
 var row=$('#gridaccounttrans').datagrid('getSelected');
       $('#dlgaccounttrans').dialog('open').dialog('setTitle','Edit accounttrans');

       $('#frmaccounttrans').form('load',row);
       url='/editaccounttrans/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveaccounttrans').click(function(){ 
var id=$('#id').val();
var accountcode=$('#accountcode').val();
var narration=$('#narration').val();
var amount=$('#amount').val();
var ttype=$('#ttype').val();
var purchaseheaderid=$('#purchaseheaderid').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'accountcode':accountcode,'narration':narration,'amount':amount,'ttype':ttype,'purchaseheaderid':purchaseheaderid,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgaccounttrans').dialog('close');
  
$('#gridaccounttrans').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteaccounttrans').click(function(){

    var a=$('#gridaccounttrans').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridaccounttrans').datagrid('getSelected');
                $.ajax({
                 url:'/destroyaccounttrans/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridaccounttrans').datagrid('reload');
            }

});
}
});

});
</script>