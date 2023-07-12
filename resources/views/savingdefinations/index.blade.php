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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsavingdefinations' toolbar='#savingdefinations'><form id='frmsavingdefinations'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Product Name</label></div><input readonly  style="height:34px;width:100%" required class='easyui-textbox form-control' name='productname' 
 id='productname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><select style="height:34px;width:100%"  class='easyui-combobox form-control' name='isActive' 
 id='isActive' >
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select>
 </select></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest</label></div><input  readonly style="height:34px;width:100%" required class='easyui-textbox form-control' name='interest' 
 id='interest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Display </label></div><select class='easyui-combobox form-control' name='intActive' 
 id='intActive' style="height:34px;width:100%">
 <option value="1">Yes </option>
 <option value="0">No </option>
 </select></div>
</div>
</div>
<div style='padding:5px;' id='savingdefinations' /><a href='javascript:void(0)' class='btn btn-primary'id='savesavingdefinations'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' rowNumbers="true" title='savingdefinations' iconCls='fa fa-table' singleSelect='true' url='viewsavingdefinations' pagination='true' id='gridsavingdefinations' method='get' fitColumns='true' style='width:100%' toolbar='#savingdefinationstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='productname' width='100'>Product Name</th>
<th field='active' width='100'>isActive</th>
<th field='isActive' hidden width='100'>isActive</th>
<th field='interest' width='100'>Interest</th>
<th field='intActive' hidden  width='100'>Display</th>
<th field='iactive'  width='100'>Display</th>
</tr></thead>
</table>
<div id='savingdefinationstoolbar'>
 <!--<a href='javascript:void(0)' class='easyui-linkbutton' id='newsavingdefinations' iconCls='icon-add' >New</a>-->
<a href='javascript:void(0)' class='btn btn-primary' id='editsavingdefinations' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<!--<a href='javascript:void(0)' class='easyui-linkbutton' id='deletesavingdefinations' iconCls='icon-remove' > Delete</a> --></div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsavingdefinations').click(function(){
       $('#dlgsavingdefinations').dialog('open').dialog('setTitle','New savingdefinations');
url='/savesavingdefinations';
$('#frmsavingdefinations').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsavingdefinations').click(function(){
       
 var row=$('#gridsavingdefinations').datagrid('getSelected');
       $('#dlgsavingdefinations').dialog('open').dialog('setTitle','Edit savingdefinations');

       $('#frmsavingdefinations').form('load',row);
       url='/editsavingdefinations/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesavingdefinations').click(function(){ 
var id=$('#id').val();

var isActive=$('#isActive').val();
var intActive=$('#intActive').val();

$.ajax({
url:url,
method:'POST',
data:{'id':id,'isActive':isActive,'intActive':intActive,'_token':$('input[name=_token]').val()},
success:function(){
    $('#gridsavingdefinations').datagrid('reload');
}
});
  
$('#dlgsavingdefinations').dialog('close');
  

});
//Auto generated code for deleting
$('#deletesavingdefinations').click(function(){

    var a=$('#gridsavingdefinations').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsavingdefinations').datagrid('getSelected');
                $.ajax({
                 url:'/destroysavingdefinations/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridsavingdefinations').datagrid('reload');
                 }
                });
               
            }

});
}
});

});
</script>