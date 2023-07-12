@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlginterestmethods' toolbar='#interestmethods'><form id='frminterestmethods'>
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
<div style='padding:5px;' id='interestmethods' /><a href='javascript:void(0)' class='btn btn-primary'id='saveinterestmethods'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='interestmethods' iconCls='fa fa-table' singleSelect='true' url='viewinterestmethods' pagination='true' id='gridinterestmethods' method='get' fitColumns='true' style='width:100%' toolbar='#interestmethodstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='interestmethodstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newinterestmethods' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editinterestmethods' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteinterestmethods' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newinterestmethods').click(function(){
       $('#dlginterestmethods').dialog('open').dialog('setTitle','New interestmethods');
url='/saveinterestmethods';
$('#frminterestmethods').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editinterestmethods').click(function(){
       
 var row=$('#gridinterestmethods').datagrid('getSelected');
       $('#dlginterestmethods').dialog('open').dialog('setTitle','Edit interestmethods');

       $('#frminterestmethods').form('load',row);
       url='/editinterestmethods/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveinterestmethods').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlginterestmethods').dialog('close');
  
$('#gridinterestmethods').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteinterestmethods').click(function(){

    var a=$('#gridinterestmethods').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridinterestmethods').datagrid('getSelected');
                $.ajax({
                 url:'/destroyinterestmethods/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridinterestmethods').datagrid('reload');
            }

});
}
});

});
</script>