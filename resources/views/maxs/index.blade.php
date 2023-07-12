@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmaxs' toolbar='#maxs'><form id='frmmaxs'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>firstname</label></div><input type='text' class='form-control' name='firstname' 
 id='firstname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>secondname</label></div><input type='text' class='form-control' name='secondname' 
 id='secondname' /></div>
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
<div style='padding:5px;' id='maxs' /><a href='javascript:void(0)' class='btn btn-primary'id='savemaxs'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='maxs' iconCls='fa fa-table' singleSelect='true' url='viewmaxs' pagination='true' id='gridmaxs' method='get' fitColumns='true' style='width:100%' toolbar='#maxstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='firstname' width='100'>firstname</th>
<th field='secondname' width='100'>secondname</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='maxstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmaxs' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmaxs' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletemaxs' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmaxs').click(function(){
       $('#dlgmaxs').dialog('open').dialog('setTitle','New maxs');
url='/savemaxs';
$('#frmmaxs').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmaxs').click(function(){
       
 var row=$('#gridmaxs').datagrid('getSelected');
       $('#dlgmaxs').dialog('open').dialog('setTitle','Edit maxs');

       $('#frmmaxs').form('load',row);
       url='/editmaxs/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savemaxs').click(function(){ 
var id=$('#id').val();
var firstname=$('#firstname').val();
var secondname=$('#secondname').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'firstname':firstname,'secondname':secondname,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgmaxs').dialog('close');
  
$('#gridmaxs').datagrid('reload');
});
//Auto generated code for deleting
$('#deletemaxs').click(function(){

    var a=$('#gridmaxs').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmaxs').datagrid('getSelected');
                $.ajax({
                 url:'/destroymaxs/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmaxs').datagrid('reload');
            }

});
}
});

});
</script>