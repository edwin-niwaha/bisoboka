@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfixednotes' toolbar='#fixednotes'><form id='frmfixednotes'>
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
<div><label>branchno</label></div><input type='text' class='form-control' name='branchno' 
 id='branchno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>date</label></div><input type='text' class='form-control' name='date' 
 id='date' /></div>
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
<div class='col-lg-6'>
<div class='form-group'>
<div><label>done</label></div><input type='text' class='form-control' name='done' 
 id='done' /></div>
</div>
<div style='padding:5px;' id='fixednotes' /><a href='javascript:void(0)' class='btn btn-primary'id='savefixednotes'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='fixednotes' iconCls='fa fa-table' singleSelect='true' url='viewfixednotes' pagination='true' id='gridfixednotes' method='get' fitColumns='true' style='width:100%' toolbar='#fixednotestoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='branchno' width='100'>branchno</th>
<th field='date' width='100'>date</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
<th field='done' width='100'>done</th>
</tr></thead>
</table>
<div id='fixednotestoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newfixednotes' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editfixednotes' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletefixednotes' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newfixednotes').click(function(){
       $('#dlgfixednotes').dialog('open').dialog('setTitle','New fixednotes');
url='/savefixednotes';
$('#frmfixednotes').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfixednotes').click(function(){
       
 var row=$('#gridfixednotes').datagrid('getSelected');
       $('#dlgfixednotes').dialog('open').dialog('setTitle','Edit fixednotes');

       $('#frmfixednotes').form('load',row);
       url='/editfixednotes/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savefixednotes').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var branchno=$('#branchno').val();
var date=$('#date').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var done=$('#done').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'branchno':branchno,'date':date,'created_at':created_at,'updated_at':updated_at,'done':done,'_token':$('input[name=_token]').val()}
});
  
$('#dlgfixednotes').dialog('close');
  
$('#gridfixednotes').datagrid('reload');
});
//Auto generated code for deleting
$('#deletefixednotes').click(function(){

    var a=$('#gridfixednotes').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridfixednotes').datagrid('getSelected');
                $.ajax({
                 url:'/destroyfixednotes/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridfixednotes').datagrid('reload');
            }

});
}
});

});
</script>