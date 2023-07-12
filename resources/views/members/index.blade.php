@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgmembers' toolbar='#members'><form id='frmmembers'>
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
<div><label>lastname</label></div><input type='text' class='form-control' name='lastname' 
 id='lastname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>created_at</label></div><input type='text' class='form-control' name='created_at' 
 id='created_at' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>updatated_at</label></div><input type='text' class='form-control' name='updatated_at' 
 id='updatated_at' /></div>
</div>
<div style='padding:5px;' id='members' /><a href='javascript:void(0)' class='btn btn-primary'id='savemembers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='members' iconCls='fa fa-table' singleSelect='true' url='viewmembers' pagination='true' id='gridmembers' method='get' fitColumns='true' style='width:100%' toolbar='#memberstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='firstname' width='100'>firstname</th>
<th field='lastname' width='100'>lastname</th>
<th field='created_at' width='100'>created_at</th>
<th field='updatated_at' width='100'>updatated_at</th>
</tr></thead>
</table>
<div id='memberstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newmembers' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editmembers' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletemembers' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newmembers').click(function(){
       $('#dlgmembers').dialog('open').dialog('setTitle','New members');
url='/savemembers';
$('#frmmembers').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editmembers').click(function(){
       
 var row=$('#gridmembers').datagrid('getSelected');
       $('#dlgmembers').dialog('open').dialog('setTitle','Edit members');

       $('#frmmembers').form('load',row);
       url='/editmembers/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savemembers').click(function(){ 
var id=$('#id').val();
var firstname=$('#firstname').val();
var lastname=$('#lastname').val();
var created_at=$('#created_at').val();
var updatated_at=$('#updatated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'firstname':firstname,'lastname':lastname,'created_at':created_at,'updatated_at':updatated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgmembers').dialog('close');
  
$('#gridmembers').datagrid('reload');
});
//Auto generated code for deleting
$('#deletemembers').click(function(){

    var a=$('#gridmembers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridmembers').datagrid('getSelected');
                $.ajax({
                 url:'/destroymembers/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridmembers').datagrid('reload');
            }

});
}
});

});
</script>