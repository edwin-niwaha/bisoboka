@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsubcategories' toolbar='#subcategories'><form id='frmsubcategories'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>subname</label></div><input type='text' class='form-control' name='subname' 
 id='subname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
</div>

<div style='padding:5px;' id='subcategories' /><a href='javascript:void(0)' class='btn btn-primary'id='savesubcategories'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='subcategories' iconCls='fa fa-table' singleSelect='true' url='viewsubcategories' pagination='true' id='gridsubcategories' method='get' fitColumns='true' style='width:100%' toolbar='#subcategoriestoolbar'>
<thead><tr>
<th field='id' width='100'>Sub-CatID</th>
<th field='subname' width='100'>Sub-Category</th>
<th field='isActive' width='100'>isActive</th>

</tr></thead>
</table>
<div id='subcategoriestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newsubcategories' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editsubcategories' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletesubcategories' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsubcategories').click(function(){
       $('#dlgsubcategories').dialog('open').dialog('setTitle','New subcategories');
url='/savesubcategories';
$('#frmsubcategories').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsubcategories').click(function(){
       
 var row=$('#gridsubcategories').datagrid('getSelected');
       $('#dlgsubcategories').dialog('open').dialog('setTitle','Edit subcategories');

       $('#frmsubcategories').form('load',row);
       url='/editsubcategories/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesubcategories').click(function(){ 
var id=$('#id').val();
var subname=$('#subname').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'subname':subname,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgsubcategories').dialog('close');
  
$('#gridsubcategories').datagrid('reload');
});
//Auto generated code for deleting
$('#deletesubcategories').click(function(){

    var a=$('#gridsubcategories').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsubcategories').datagrid('getSelected');
                $.ajax({
                 url:'/destroysubcategories/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridsubcategories').datagrid('reload');
            }

});
}
});

});
</script>