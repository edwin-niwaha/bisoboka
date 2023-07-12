@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlguoms' toolbar='#uoms'><form id='frmuoms'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Primary Unit of Measure </label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Secondary Unit of Measure </label></div><input type='text' class='form-control' name='secondaryuom' 
 id='secondaryuom' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
</div>

<div style='padding:5px;' id='uoms' /><a href='javascript:void(0)' class='btn btn-primary'id='saveuoms'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='uoms' iconCls='fa fa-table' singleSelect='true' url='viewuoms' pagination='true' id='griduoms' method='get' fitColumns='true' style='width:100%' toolbar='#uomstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>Unit of Measure</th>
<th field='isActive' width='100'>isActive</th>

</tr></thead>
</table>
<div id='uomstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newuoms' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='edituoms' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteuoms' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newuoms').click(function(){
       $('#dlguoms').dialog('open').dialog('setTitle','New uoms');
url='/saveuoms';
$('#frmuoms').form('clear');
});

       //Auto Generated code for Edit Code
 $('#edituoms').click(function(){
       
 var row=$('#griduoms').datagrid('getSelected');
       $('#dlguoms').dialog('open').dialog('setTitle','Edit uoms');

       $('#frmuoms').form('load',row);
       url='/edituoms/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveuoms').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlguoms').dialog('close');
  
$('#griduoms').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteuoms').click(function(){

    var a=$('#griduoms').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#griduoms').datagrid('getSelected');
                $.ajax({
                 url:'/destroyuoms/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#griduoms').datagrid('reload');
            }

});
}
});

});
</script>