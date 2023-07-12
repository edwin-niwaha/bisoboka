@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgworkers' toolbar='#workers'><form id='frmworkers'>
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
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>updated_at</label></div><input type='text' class='form-control' name='updated_at' 
 id='updated_at' /></div>
</div>
<div style='padding:5px;' id='workers' /><a href='javascript:void(0)' class='btn btn-primary'id='saveworkers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='workers' iconCls='fa fa-table' singleSelect='true' url='viewworkers' pagination='true' id='gridworkers' method='get' fitColumns='true' style='width:100%' toolbar='#workerstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='workerstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newworkers' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editworkers' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteworkers' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newworkers').click(function(){
       $('#dlgworkers').dialog('open').dialog('setTitle','New workers');
url='/saveworkers';
$('#frmworkers').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editworkers').click(function(){
       
 var row=$('#gridworkers').datagrid('getSelected');
       $('#dlgworkers').dialog('open').dialog('setTitle','Edit workers');

       $('#frmworkers').form('load',row);
       url='/editworkers/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveworkers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgworkers').dialog('close');
  
$('#gridworkers').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteworkers').click(function(){

    var a=$('#gridworkers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridworkers').datagrid('getSelected');
                $.ajax({
                 url:'/destroyworkers/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridworkers').datagrid('reload');
            }

});
}
});

});
</script>