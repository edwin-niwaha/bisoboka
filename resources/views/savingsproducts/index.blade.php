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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsavingsproducts' toolbar='#savingsproducts'><form id='frmsavingsproducts'>
<!--
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>-->
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
<div><label>Minmum Balance</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='minbal' 
 id='minbal' /></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Withdraw Charge</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='charge' 
 id='charge' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Checking Account</label></div><input  class='form-control easyui-combobox' style="width:100%;height:34px;"  data-options="url:'combochartofaccounts/1',valueField:'accountcode',textField:'accountname',method:'get',required:'true'" name='account' 
 id='account' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest Rate</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='interest' 
 id='interest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest Method</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='intmethod' 
 id='intmethod' >
 <option value="1">Simple </option>
 <option value="0">Compound </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date when interest should be calculated</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='datecomp' 
 id='datecomp' >
 <option value="01">1st </option>
 <option value="02">2nd </option>
 <option value="03">3rd </option>
 <option value="04">4th </option>
 <option value="05">5th </option>
 <option value="06">6th </option>
 <option value="07">7th </option>
 <option value="08">8th </option>
 <option value="09">9th </option>
 <option value="10">10th </option>
 <option value="11">11th </option>
 <option value="12">12th </option>
 <option value="13">13th </option>
 <option value="14">14th </option>
 <option value="15">15th </option>
 <option value="16">16th </option>
 <option value="17">17th </option>
 <option value="18">18th </option>
 <option value="19">19th </option>
 <option value="20">20th </option>
 <option value="21">21st </option>
 <option value="22">22nd </option>
 <option value="23">23rd </option>
 <option value="24">24th </option>
 <option value="25">25th </option>
 <option value="26">26th </option>
 <option value="27">27th </option>
 <option value="28">28th </option>
 <option value="29">29th </option>
 <option value="30">30th </option>
 <option value="31">31st </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest Posting Frequency</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='freq' 
 id='freq' >
 <option value="1">Every 1 Month </option>
 <option value="2">Every 2 Months </option>
 <option value="3">Every 3 Months </option>
 <option value="4">Every 4 Months </option>
 <option value="6">Every 6 Months </option>
 <option value="12">Every 12 Months </option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Number of days money should be on an account</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='nodays' 
 id='nodays' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><select  style="height:34px;width:100%" required class='easyui-combobox form-control' name='isActive' 
 id='isActive' >
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
<div style='padding:5px;' id='savingsproducts' /><a href='javascript:void(0)' class='btn btn-primary'id='savesavingsproducts'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' rowNumbers="true" title='Savings Products' iconCls='fa fa-table' singleSelect='true' url='viewsavingsproducts' pagination='true' id='gridsavingsproducts' method='get' fitColumns='true' style='width:100%' toolbar='#savingsproductstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='100'>Name</th>
<th field='accountcode' width='100'>Account code</th>
<th field='minbal' width='100'>Minmum Bal</th>
<th field='charge' width='100'>Withdraw Charge</th>
<th field='accountname' width='100'>Recieving AC</th>
<th field='account' hidden width='100'>Recieving AC</th>
<th field='interest' width='100'>Interest</th>
<th field='freq' width='100'>Freq (Months)</th>
<th field='intmethod' hidden width='100'>Int Method</th>
<th field='intmeth' width='100'>Int Method</th>
<th field='nodays' width='100'>Days on account</th>
<th field='datecomp' width='100'>Day of Post</th>
<th field='isActive' hidden width='100'>isActive</th>
<th field='active' width='100'>isActive</th>

</tr></thead>
</table>
<div id='savingsproductstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newsavingsproducts' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editsavingsproducts' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletesavingsproducts' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsavingsproducts').click(function(){
       $('#dlgsavingsproducts').dialog('open').dialog('setTitle','New savingsproducts');
url='/savesavingsproducts';
$('#frmsavingsproducts').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsavingsproducts').click(function(){
       
 var row=$('#gridsavingsproducts').datagrid('getSelected');
       $('#dlgsavingsproducts').dialog('open').dialog('setTitle','Edit savingsproducts');

       $('#frmsavingsproducts').form('load',row);
       url='/editsavingsproducts/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesavingsproducts').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var accountcode=$('#accountcode').val();
var minbal=$('#minbal').val();
var interest=$('#interest').val();
var charge=$('#charge').val();
var isActive=$('#isActive').val();
var account=$('#account').val();
var intmethod=$('#intmethod').val();
var datecomp=$('#datecomp').val();;
var freq=$('#freq').val();
var nodays=$('#nodays').val();
var updated_at=$('#updated_at').val();
var created_at=$('#created_at').val();
$.ajax({
url:url,
method:'POST',
data:{'nodays':nodays,'freq':freq,'intmethod':intmethod,'datecomp':datecomp,'id':id,'name':name,'account':account,'accountcode':accountcode,'minbal':minbal,'interest':interest,'charge':charge,'isActive':isActive,'updated_at':updated_at,'created_at':created_at,'_token':$('input[name=_token]').val()},
success:function(row){
    if(row.exists==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Account Code already exists,Please select another Account code '});
    }

    if(row.limit==true){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Record Not Saved, You can only save 4 Savings Products '});
    }
        $('#gridsavingsproducts').datagrid('reload');
    
}
});
  
$('#dlgsavingsproducts').dialog('close');
  

});
//Auto generated code for deleting
$('#deletesavingsproducts').click(function(){

    var a=$('#gridsavingsproducts').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsavingsproducts').datagrid('getSelected');
                $.ajax({
                 url:'/destroysavingsproducts/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridsavingsproducts').datagrid('reload');
                 }
                });
               
            }

});
}
});

});
</script>