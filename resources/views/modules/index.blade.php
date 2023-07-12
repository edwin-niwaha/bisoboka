@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmodules' toolbar='#modules'><form id='frmmodules'>
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
<div><label>isActive</label></div>
<select class='form-control' id='isActive'name='isActive' > 
    <option value=1>Yes</option>
    <option value=0>No</option> 
</select></div>
</div>

<div style='padding:5px;' id='modules' /><a href='javascript:void(0)' class='btn btn-primary'id='savemodules'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' singleSelect='true' url='viewmodules' pagination='true' id='gridmodules' method='get' fitColumns='true' style='width:100%' toolbar='#modulestoolbar'>
<thead><tr>
<th field='id' width='50'>id</th>
<th field='name' width='50'>name</th>
<th field='isActive' width='50'>isActive</th>
</tr></thead>
</table>
<div id='modulestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmodules' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmodules' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletemodules' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmodules').click(function(){
       $('#dlgmodules').dialog('open');
url='/savemodules';
$('#frmmodules').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmodules').click(function(){
       
 var row=$('#gridmodules').datagrid('getSelected');
 if(row.id==7){
$.messager.alert('Info','This Module cannot be Edited');
 }else{
       $('#dlgmodules').dialog('open');
       $('#frmmodules').form('load',row);
       url='/editmodules/'+row.id;
 }
       
       });
//Auto Generated Code for Saving
$('#savemodules').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var isActive=$('#isActive').val();

$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
$('#gridmodules').datagrid('reload');
  
$('#dlgmodules').dialog('close');
});
//Auto generated code for deleting
$('#deletemodules').click(function(){

    var a=$('#gridmodules').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else if (a.id==7){
        $.messager.alert('Info','This Module cannot be Deleted');
    }
        
        else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmodules').datagrid('getSelected');
                $.ajax({
                 url:'/destroymodules/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmodules').datagrid('reload');
            }

});
}
});

});
</script>