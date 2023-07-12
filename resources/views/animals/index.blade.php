@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlganimals' toolbar='#animals'><form id='frmanimals'>
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
<div><label>breed</label></div><input type='text' class='form-control' name='breed' 
 id='breed' /></div>
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
<div style='padding:5px;' id='animals' /><a href='javascript:void(0)' class='btn btn-primary'id='saveanimals'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' singleSelect='true' url='viewanimals' pagination='true' id='gridanimals' method='get' fitColumns='true' style='width:100%' toolbar='#animalstoolbar'>
<thead><tr>
<th field='id' width='50'>id</th>
<th field='name' width='50'>name</th>
<th field='breed' width='50'>breed</th>
<th field='created_at' width='50'>created_at</th>
<th field='updated_at' width='50'>updated_at</th>
</tr></thead>
</table>
<div id='animalstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newanimals' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editanimals' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteanimals' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newanimals').click(function(){
       $('#dlganimals').dialog('open');
url='/saveanimals';
$('#frmanimals').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editanimals').click(function(){
       
 var row=$('#gridanimals').datagrid('getSelected');
       $('#dlganimals').dialog('open');
       $('#frmanimals').form('load',row);
       url='/editanimals/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveanimals').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var breed=$('#breed').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'breed':breed,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
$('#gridanimals').datagrid('reload');
  
$('#dlganimals').dialog('close');
});
//Auto generated code for deleting
$('#deleteanimals').click(function(){

    var a=$('#gridanimals').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridanimals').datagrid('getSelected');
                $.ajax({
                 url:'/destroyanimals/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridanimals').datagrid('reload');
            }

});
}
});

});
</script>