@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmemberships' toolbar='#memberships'><form id='frmmemberships'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>raccount</label></div><input type='text' class='form-control' name='raccount' 
 id='raccount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
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
<div style='padding:5px;' id='memberships' /><a href='javascript:void(0)' class='btn btn-primary'id='savememberships'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='memberships' iconCls='fa fa-table' singleSelect='true' url='viewmemberships' pagination='true' id='gridmemberships' method='get' fitColumns='true' style='width:100%' toolbar='#membershipstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='raccount' width='100'>raccount</th>
<th field='isActive' width='100'>isActive</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='membershipstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmemberships' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmemberships' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletememberships' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmemberships').click(function(){
       $('#dlgmemberships').dialog('open').dialog('setTitle','New memberships');
url='/savememberships';
$('#frmmemberships').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmemberships').click(function(){
       
 var row=$('#gridmemberships').datagrid('getSelected');
       $('#dlgmemberships').dialog('open').dialog('setTitle','Edit memberships');

       $('#frmmemberships').form('load',row);
       url='/editmemberships/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savememberships').click(function(){ 
var id=$('#id').val();
var raccount=$('#raccount').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'raccount':raccount,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgmemberships').dialog('close');
  
$('#gridmemberships').datagrid('reload');
});
//Auto generated code for deleting
$('#deletememberships').click(function(){

    var a=$('#gridmemberships').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmemberships').datagrid('getSelected');
                $.ajax({
                 url:'/destroymemberships/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmemberships').datagrid('reload');
            }

});
}
});

});
</script>