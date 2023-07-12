@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgstuff' toolbar='#stuff'><form id='frmstuff'>
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
<div style='padding:5px;' id='stuff' /><a href='javascript:void(0)' class='btn btn-primary'id='savestuff'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='stuff' iconCls='fa fa-table' singleSelect='true' url='viewstuff' pagination='true' id='gridstuff' method='get' fitColumns='true' style='width:100%' toolbar='#stufftoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='name' width='100'>name</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='stufftoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newstuff' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editstuff' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletestuff' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newstuff').click(function(){
       $('#dlgstuff').dialog('open').dialog('setTitle','New stuff');
url='/savestuff';
$('#frmstuff').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editstuff').click(function(){
       
 var row=$('#gridstuff').datagrid('getSelected');
       $('#dlgstuff').dialog('open').dialog('setTitle','Edit stuff');

       $('#frmstuff').form('load',row);
       url='/editstuff/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savestuff').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgstuff').dialog('close');
  
$('#gridstuff').datagrid('reload');
});
//Auto generated code for deleting
$('#deletestuff').click(function(){

    var a=$('#gridstuff').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridstuff').datagrid('getSelected');
                $.ajax({
                 url:'/destroystuff/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridstuff').datagrid('reload');
            }

});
}
});

});
</script>