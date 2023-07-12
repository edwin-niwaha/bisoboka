@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgcategories' toolbar='#categories'><form id='frmcategories'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
</div>

<div style='padding:5px;' id='categories' /><a href='javascript:void(0)' class='btn btn-primary'id='savecategories'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='categories' iconCls='fa fa-table' singleSelect='true' url='viewcategories' pagination='true' id='gridcategories' method='get' fitColumns='true' style='width:100%' toolbar='#categoriestoolbar'>
<thead><tr>
<th field='id' width='100'>CatID</th>
<th field='name' width='100'>name</th>
<th field='isActive' width='100'>isActive</th>

</tr></thead>
</table>
<div id='categoriestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newcategories' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editcategories' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletecategories' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newcategories').click(function(){
       $('#dlgcategories').dialog('open').dialog('setTitle','New categories');
url='/savecategories';
$('#frmcategories').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editcategories').click(function(){
       
 var row=$('#gridcategories').datagrid('getSelected');
       $('#dlgcategories').dialog('open').dialog('setTitle','Edit categories');

       $('#frmcategories').form('load',row);
       url='/editcategories/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savecategories').click(function(){ 
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
  
$('#dlgcategories').dialog('close');
  
$('#gridcategories').datagrid('reload');
});
//Auto generated code for deleting
$('#deletecategories').click(function(){

    var a=$('#gridcategories').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridcategories').datagrid('getSelected');
                $.ajax({
                 url:'/destroycategories/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridcategories').datagrid('reload');
            }

});
}
});

});
</script>