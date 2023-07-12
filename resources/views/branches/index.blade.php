@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgbranches' toolbar='#branches'><form id='frmbranches'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>branchname</label></div><input type='text' class='form-control' name='branchname' 
 id='branchname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>contactPerson</label></div><input type='text' class='form-control' name='contactPerson' 
 id='contactPerson' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Tel</label></div><input type='text' class='form-control' name='Tel' 
 id='Tel' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Address</label></div><input type='text' class='form-control' name='Address' 
 id='Address' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isDefault</label></div><input type='text' class='form-control' name='isDefault' 
 id='isDefault' /></div>
</div>

<div style='padding:5px;' id='branches' /><a href='javascript:void(0)' class='btn btn-primary'id='savebranches'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='branches'  rownumbers="true" iconCls='fa fa-table' singleSelect='true' url='viewbranches' pagination='true' id='gridbranches' method='get' fitColumns='true' style='width:100%' toolbar='#branchestoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='branchname' width='100'>branchname</th>
<th field='contactPerson' width='100'>contactPerson</th>
<th field='Tel' width='100'>Tel</th>
<th field='Address' width='100'>Address</th>
<th field='isActive' width='100'>isActive</th>
<th field='isDefault' width='100'>isDefault</th>

</tr></thead>
</table>
<div id='branchestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newbranches' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editbranches' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletebranches' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newbranches').click(function(){
       $('#dlgbranches').dialog('open').dialog('setTitle','New branches');
url='/savebranches';
$('#frmbranches').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editbranches').click(function(){
       
 var row=$('#gridbranches').datagrid('getSelected');
       $('#dlgbranches').dialog('open').dialog('setTitle','Edit branches');

       $('#frmbranches').form('load',row);
       url='/editbranches/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savebranches').click(function(){ 
var id=$('#id').val();
var branchname=$('#branchname').val();
var contactPerson=$('#contactPerson').val();
var Tel=$('#Tel').val();
var Address=$('#Address').val();
var isActive=$('#isActive').val();
var isDefault=$('#isDefault').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'branchname':branchname,'contactPerson':contactPerson,'Tel':Tel,'Address':Address,'isActive':isActive,'isDefault':isDefault,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(){
    $('#gridbranches').datagrid('reload');
}
});
  
$('#dlgbranches').dialog('close');
  

});
//Auto generated code for deleting
$('#deletebranches').click(function(){

    var a=$('#gridbranches').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridbranches').datagrid('getSelected');
                $.ajax({
                 url:'/destroybranches/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridbranches').datagrid('reload');
                 }
                });
                
            }

});
}
});

});
</script>