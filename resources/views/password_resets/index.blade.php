@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgpassword_resets' toolbar='#password_resets'><form id='frmpassword_resets'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>email</label></div><input type='text' class='form-control' name='email' 
 id='email' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>token</label></div><input type='text' class='form-control' name='token' 
 id='token' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div style='padding:5px;' id='password_resets' /><a href='javascript:void(0)' class='btn btn-primary'id='savepassword_resets'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' singleSelect='true' url='viewpassword_resets' pagination='true' id='gridpassword_resets' method='get' fitColumns='true' style='width:100%' toolbar='#password_resetstoolbar'>
<thead><tr>
<th field='email' width='50'>email</th>
<th field='token' width='50'>token</th>
<th field='created_at' width='50'>created_at</th>
</tr></thead>
</table>
<div id='password_resetstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newpassword_resets' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editpassword_resets' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletepassword_resets' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newpassword_resets').click(function(){
       $('#dlgpassword_resets').dialog('open');
url='/savepassword_resets';
$('#frmpassword_resets').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editpassword_resets').click(function(){
       
 var row=$('#gridpassword_resets').datagrid('getSelected');
       $('#dlgpassword_resets').dialog('open');
       $('#frmpassword_resets').form('load',row);
       url='/editpassword_resets/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savepassword_resets').click(function(){ 
var email=$('#email').val();
var token=$('#token').val();
var created_at=$('#created_at').val();
$.ajax({
url:url,
method:'POST',
data:{'email':email,'token':token,'created_at':created_at,'_token':$('input[name=_token]').val()}
});
$('#gridpassword_resets').datagrid('reload');
  
$('#dlgpassword_resets').dialog('close');
});
//Auto generated code for deleting
$('#deletepassword_resets').click(function(){

    var a=$('#gridpassword_resets').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridpassword_resets').datagrid('getSelected');
                $.ajax({
                 url:'/destroypassword_resets/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridpassword_resets').datagrid('reload');
            }

});
}
});

});
</script>