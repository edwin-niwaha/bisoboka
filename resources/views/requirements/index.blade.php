@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgrequirements' toolbar='#requirements'><form id='frmrequirements'>
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
<div><label>module_id</label></div><input  class='easyui-combobox form-control' data-options="url:'combomodules',textField:'name',valueField:'id',method:'get'"style="width:100%;height:33px;" name='module_id' 
 id='module_id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Urls</label></div><input type='text' class='form-control' name='Urls' 
 id='Urls' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><select class='form-control' id='isActive'name='isActive' > 
    <option value=1>Yes</option>
    <option value="0" selected>No</option> 
</select></div>
</div>

<div style='padding:5px;' id='requirements' /><a href='javascript:void(0)' class='btn btn-primary'id='saverequirements'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='requirements' iconCls='fa fa-table' singleSelect='true' url='viewrequirements' pagination='true' id='gridrequirements' method='get' fitColumns='true' style='width:100%' toolbar='#requirementstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='module_id' width='100'>module_id</th>
<th field='Urls' width='100'>Urls</th>
<th field='isActive' width='100'>isActive</th>

</tr></thead>
</table>
<div id='requirementstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newrequirements' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editrequirements' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleterequirements' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newrequirements').click(function(){
       $('#dlgrequirements').dialog('open').dialog('setTitle','New requirements');
url='/saverequirements';
$('#frmrequirements').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editrequirements').click(function(){
       
 var row=$('#gridrequirements').datagrid('getSelected');
       $('#dlgrequirements').dialog('open').dialog('setTitle','Edit requirements');

       $('#frmrequirements').form('load',row);
       url='/editrequirements/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saverequirements').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var module_id=$('#module_id').val();
var Urls=$('#Urls').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var isActive=$('#isActive').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'module_id':module_id,'Urls':Urls,'created_at':created_at,'updated_at':updated_at,'isActive':isActive,'_token':$('input[name=_token]').val()}
});
  
$('#dlgrequirements').dialog('close');
  
$('#gridrequirements').datagrid('reload');
});
//Auto generated code for deleting
$('#deleterequirements').click(function(){

    var a=$('#gridrequirements').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else if(a.id==4){
        $.messager.alert('Info','This Module cannot be deleted please');
        
        }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridrequirements').datagrid('getSelected');
                $.ajax({
                 url:'/destroyrequirements/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridrequirements').datagrid('reload');
            }

});
}
});

});
</script>