@include('layouts/header')
<style>
.datagrid-row-alt{
    background-color: #d9f2e7;

}
.datagrid-row-selected {
  background: #009900;
  color: white;
}
</style>
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgloanproducts' toolbar='#loanproducts'><form id='frmloanproducts'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Account code</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='accountcode' 
 id='accountcode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='interest' 
 id='interest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Disbursed By</label></div><input  style="height:34px;width:100%" class='easyui-combobox form-control' name='disbursingac' 
 id='disbursingac' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='isActive' 
 id='isActive' >
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<!--
<div class='col-lg-6'>
<div class='form-group'>
<div><label>loanpdt</label></div><input type='text' class='form-control' name='loanpdt' 
 id='loanpdt' /></div>
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
</div>-->
<div style='padding:5px;' id='loanproducts' /><a href='javascript:void(0)' class='btn btn-primary'id='saveloanproducts'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' rowNumbers="true" title='Loan Products' iconCls='fa fa-table' singleSelect='true' url='viewloanproducts' pagination='true' id='gridloanproducts' method='get' fitColumns='true' style='width:100%' toolbar='#loanproductstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='100'>Name</th>
<th field='accountcode' width='100'>Account code</th>
<th field='disbursingac' hidden width='100'>Disbursingname</th>
<th field='disname' width='100'>Disbursing Account</th>
<th field='interest' width='100'>Interest</th>
<th field='active'  width='100'>IsActive</th>
<th field='isActive' hidden width='100'>IsActive</th>
<th field='loanpdt' hidden width='100'>Loanpdt</th>
<th field='created_at' hidden width='100'>created_at</th>
<th field='updated_at' hidden  width='100'>updated_at</th>
</tr></thead>
</table>
<div id='loanproductstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newloanproducts' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editloanproducts' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deleteloanproducts' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newloanproducts').click(function(){
       $('#dlgloanproducts').dialog('open').dialog('setTitle','New loanproducts');
url='/saveloanproducts';
$('#frmloanproducts').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editloanproducts').click(function(){
       
 var row=$('#gridloanproducts').datagrid('getSelected');
       $('#dlgloanproducts').dialog('open').dialog('setTitle','Edit loanproducts');

       $('#frmloanproducts').form('load',row);
       url='/editloanproducts/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveloanproducts').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var accountcode=$('#accountcode').val();
var interest=$('#interest').val();
var loanpdt=$('#loanpdt').val();
var disbursingac=$('#disbursingac').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'isActive':isActive,'disbursingac':disbursingac,'id':id,'name':name,'accountcode':accountcode,'interest':interest,'loanpdt':loanpdt,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.accountcode==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Account Code already exists,Please select another Account code '});
    }
    if(data.limit==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Record Not Saved, You can only save 5 Loan Products '});
    }
    $('#gridloanproducts').datagrid('reload');
}
});
  
$('#dlgloanproducts').dialog('close');
  

});
$('#disbursingac').combobox({
url:'combochartofaccounts/1',
method:'get',
valueField:'accountcode',
required:'true',
textField:'accountname'});
//Auto generated code for deleting
$('#deleteloanproducts').click(function(){

    var a=$('#gridloanproducts').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridloanproducts').datagrid('getSelected');
                $.ajax({
                 url:'/destroyloanproducts/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridloanproducts').datagrid('reload');
                 }
                });
               
            }

});
}
});

});
</script>