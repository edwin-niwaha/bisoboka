@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfixedtypes' toolbar='#fixedtypes'><form id='frmfixedtypes'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>mode</label></div><input type='text' class='form-control' name='mode' 
 id='mode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>name</label></div><input type='text' class='form-control' name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>updated_at</label></div><input type='text' class='form-control' name='updated_at' 
 id='updated_at' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div style='padding:5px;' id='fixedtypes' /><a href='javascript:void(0)' class='btn btn-primary'id='savefixedtypes'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='fixedtypes' iconCls='fa fa-table' singleSelect='true' url='viewfixedtypes' pagination='true' id='gridfixedtypes' method='get' fitColumns='true' style='width:100%' toolbar='#fixedtypestoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='mode' width='100'>mode</th>
<th field='name' width='100'>name</th>
<th field='updated_at' width='100'>updated_at</th>
<th field='created_at' width='100'>created_at</th>
</tr></thead>
</table>
<div id='fixedtypestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newfixedtypes' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editfixedtypes' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletefixedtypes' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newfixedtypes').click(function(){
       $('#dlgfixedtypes').dialog('open').dialog('setTitle','New fixedtypes');
url='/savefixedtypes';
$('#frmfixedtypes').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfixedtypes').click(function(){
       
 var row=$('#gridfixedtypes').datagrid('getSelected');
       $('#dlgfixedtypes').dialog('open').dialog('setTitle','Edit fixedtypes');

       $('#frmfixedtypes').form('load',row);
       url='/editfixedtypes/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savefixedtypes').click(function(){ 
var id=$('#id').val();
var mode=$('#mode').val();
var name=$('#name').val();
var updated_at=$('#updated_at').val();
var created_at=$('#created_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'mode':mode,'name':name,'updated_at':updated_at,'created_at':created_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgfixedtypes').dialog('close');
  
$('#gridfixedtypes').datagrid('reload');
});
//Auto generated code for deleting
$('#deletefixedtypes').click(function(){

    var a=$('#gridfixedtypes').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridfixedtypes').datagrid('getSelected');
                $.ajax({
                 url:'/destroyfixedtypes/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridfixedtypes').datagrid('reload');
            }

});
}
});

});
</script>