@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmodes' toolbar='#modes'><form id='frmmodes'>
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
<div style='padding:5px;' id='modes' /><a href='javascript:void(0)' class='btn btn-primary'id='savemodes'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='modes' iconCls='fa fa-table' singleSelect='true' url='viewmodes' pagination='true' id='gridmodes' method='get' fitColumns='true' style='width:100%' toolbar='#modestoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='modestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmodes' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmodes' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletemodes' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmodes').click(function(){
       $('#dlgmodes').dialog('open').dialog('setTitle','New modes');
url='/savemodes';
$('#frmmodes').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmodes').click(function(){
       
 var row=$('#gridmodes').datagrid('getSelected');
       $('#dlgmodes').dialog('open').dialog('setTitle','Edit modes');

       $('#frmmodes').form('load',row);
       url='/editmodes/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savemodes').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgmodes').dialog('close');
  
$('#gridmodes').datagrid('reload');
});
//Auto generated code for deleting
$('#deletemodes').click(function(){

    var a=$('#gridmodes').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmodes').datagrid('getSelected');
                $.ajax({
                 url:'/destroymodes/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmodes').datagrid('reload');
            }

});
}
});

});
</script>