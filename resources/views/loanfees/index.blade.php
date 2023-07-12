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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgloanfees' toolbar='#loanfees'><form id='frmloanfees'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input  style="height:34px;width:100%" required class='easyui-textbox form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Account Code</label></div><input  style="height:34px;width:100%" required class='easyui-textbox form-control' name='code' 
 id='code' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>is Percent of Princple ?</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='isPercent' 
 id='isPercent' >
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Amount / Percentage</label></div><input  style="height:34px;width:100%" required class='easyui-textbox form-control' name='amount' 
 id='amount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Deduct from Princple ?</label></div><select style="height:34px;width:100%" required class='easyui-combobox form-control' name='isDeduct' 
 id='isDeduct'>
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Deduct from Savings ?</label></div><select style="height:34px;width:100%" required class='easyui-combobox form-control' name='isSavings' 
 id='isSavings'>
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>is Active ? </label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='isActive' 
 id='isActive' >
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Savings Product</label></div>
<input class="easyui-combobox" id="savgpdt" style="height:34px;width:100%" name="savgpdt" data-options="url:'combosavingsproducts',valueField:'accountcode',textField:'name',method:'get' "/>
</div>
</div><!--
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
<div style='padding:5px;' id='loanfees' /><a href='javascript:void(0)' class='btn btn-primary'id='saveloanfees'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='Loan Application Charges' rowNumbers="true" iconCls='fa fa-table' singleSelect='true' url='viewloanfees' pagination='true' id='gridloanfees' method='get' fitColumns='true' style='width:100%' toolbar='#loanfeestoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='100'>Name</th>
<th field='code' width='100'>Account Code</th>
<th field='isPercent' hidden width='100'>isPercent</th>
<th field='percent' width='100'>isPercent</th>
<th field='amount' width='100'>Amount/Percentage</th>
<th field='isDeduct' hidden width='100'>isDeduct</th>
<th field='deduct' width='100'>Deduct From Prinple</th>
<th field='isSavings' hidden  width='100'>Deduct From Savings</th>
<th field='saving'  width='100'>Deduct From Savings</th>
<th field='isActive' hidden width='100'>isActive</th>
<th field="savgpdt" hidden> Savingpdt </th>
<th field='active' width='100'>isActive</th>
<th field='created_at' hidden width='100'>created_at</th>
<th field='updated_at' hidden width='100'>updated_at</th>
</tr></thead>
</table>
<div id='loanfeestoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newloanfees' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editloanfees' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deleteloanfees' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newloanfees').click(function(){
       $('#dlgloanfees').dialog('open').dialog('setTitle','New loanfees');
url='/saveloanfees';
$('#frmloanfees').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editloanfees').click(function(){
       
 var row=$('#gridloanfees').datagrid('getSelected');
       $('#dlgloanfees').dialog('open').dialog('setTitle','Edit loanfees');

       $('#frmloanfees').form('load',row);
       url='/editloanfees/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveloanfees').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var code=$('#code').val();
var isPercent=$('#isPercent').val();
var amount=$('#amount').val();
var isDeduct=$('#isDeduct').val();
var isSavings=$('#isSavings').val();
var savgpdt=$('#savgpdt').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'savgpdt':savgpdt,'isSavings':isSavings,'id':id,'name':name,'code':code,'isPercent':isPercent,'amount':amount,'isDeduct':isDeduct,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.chart==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Account Code already exists,Please select another Account code '});
    }

    if(data.limit==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Record Not Saved, You can only save 3 loan application charges '});
    }
 
      
$('#gridloanfees').datagrid('reload');
}
});
  
$('#dlgloanfees').dialog('close');

});
//Auto generated code for deleting
$('#deleteloanfees').click(function(){

    var a=$('#gridloanfees').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','This record may have some data associated with it,Are you sure you want to Delete it ?',function(r){
            if(r){
                var row=$('#gridloanfees').datagrid('getSelected');
                $.ajax({
                 url:'/destroyloanfees/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridloanfees').datagrid('reload');
                 }
                });
                
            }

});
}
});

});
</script>