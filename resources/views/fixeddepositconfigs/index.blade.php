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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfixeddepositconfigs' toolbar='#fixeddepositconfigs'><form id='frmfixeddepositconfigs'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Term</label></div><input style="height:34px;width:100%" required  class='easyui-textbox form-control' name='term' 
 id='term' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest %</label></div><input style="height:34px;width:100%" required   class='easyui-numberbox form-control' name='interest' 
 id='interest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Period</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='period' 
 id='period' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Checking Account</label></div><input style="height:34px;width:100%"  class='easyui-combobox form-control' data-options="url:'combochartofaccounts/1',valueField:'accountcode',textField:'accountname',method:'get',required:'true'" name='checkingac' 
 id='checkingac' /></div>
</div>

</div>
<div style='padding:5px;' id='fixeddepositconfigs' /><a href='javascript:void(0)' class='btn btn-primary'id='savefixeddepositconfigs'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid'  striped='true' rowNumbers='true' title='Fixed Deposit Configs' iconCls='fa fa-table' singleSelect='true' url='viewfixeddepositconfigs' pagination='true' id='gridfixeddepositconfigs' method='get' fitColumns='true' style='width:100%' toolbar='#fixeddepositconfigstoolbar'>
<thead><tr>
<th field='id' hidden  width='100'>id</th>
<th field='term' width='100'>Term</th>
<th field='interestrate' width='70'>Interest %</th>
<th field='period' width='70'>Period</th>
<th field='checkingac' hidden width='100'>Checkingac</th>
<th field="accountname" width="100"> Checking Account </th>
</tr></thead>
</table>
<div id='fixeddepositconfigstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newfixeddepositconfigs'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editfixeddepositconfigs'  ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletefixeddepositconfigs'  ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newfixeddepositconfigs').click(function(){
       $('#dlgfixeddepositconfigs').dialog('open').dialog('setTitle','New Fixed Deposit Setting');
url='/savefixeddepositconfigs';
$('#frmfixeddepositconfigs').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfixeddepositconfigs').click(function(){
       
 var row=$('#gridfixeddepositconfigs').datagrid('getSelected');
       $('#dlgfixeddepositconfigs').dialog('open').dialog('setTitle','Edit Fixed Deposit Setting');

       $('#frmfixeddepositconfigs').form('load',row);
       url='/editfixeddepositconfigs/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savefixeddepositconfigs').click(function(){ 
var id=$('#id').val();
var term=$('#term').val();
var period=$('#period').val();
var checkingac=$('#checkingac').val();
var interest=$('#interest').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'term':term,'interest':interest,'period':period,'checkingac':checkingac,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    $('#gridfixeddepositconfigs').datagrid('reload');
}
});
  
$('#dlgfixeddepositconfigs').dialog('close');
  

});
//Auto generated code for deleting
$('#deletefixeddepositconfigs').click(function(){

    var a=$('#gridfixeddepositconfigs').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridfixeddepositconfigs').datagrid('getSelected');
                $.ajax({
                 url:'/destroyfixeddepositconfigs/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    $('#gridfixeddepositconfigs').datagrid('reload');
                 }
                });
                
            }

});
}
});

});
</script>