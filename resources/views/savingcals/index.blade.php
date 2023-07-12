@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsavingcals' toolbar='#savingcals'><form id='frmsavingcals'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>savingpdt</label></div><input type='text' class='form-control' name='savingpdt' 
 id='savingpdt' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>begining</label></div><input type='text' class='form-control' name='begining' 
 id='begining' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>next</label></div><input type='text' class='form-control' name='next' 
 id='next' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>nodays</label></div><input type='text' class='form-control' name='nodays' 
 id='nodays' /></div>
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
<div style='padding:5px;' id='savingcals' /><a href='javascript:void(0)' class='btn btn-primary'id='savesavingcals'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='savingcals' iconCls='fa fa-table' singleSelect='true' url='viewsavingcals' pagination='true' id='gridsavingcals' method='get' fitColumns='true' style='width:100%' toolbar='#savingcalstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='savingpdt' width='100'>savingpdt</th>
<th field='begining' width='100'>begining</th>
<th field='next' width='100'>next</th>
<th field='nodays' width='100'>nodays</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='savingcalstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newsavingcals' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editsavingcals' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletesavingcals' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsavingcals').click(function(){
       $('#dlgsavingcals').dialog('open').dialog('setTitle','New savingcals');
url='/savesavingcals';
$('#frmsavingcals').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsavingcals').click(function(){
       
 var row=$('#gridsavingcals').datagrid('getSelected');
       $('#dlgsavingcals').dialog('open').dialog('setTitle','Edit savingcals');

       $('#frmsavingcals').form('load',row);
       url='/editsavingcals/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesavingcals').click(function(){ 
var id=$('#id').val();
var savingpdt=$('#savingpdt').val();
var begining=$('#begining').val();
var next=$('#next').val();
var nodays=$('#nodays').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'savingpdt':savingpdt,'begining':begining,'next':next,'nodays':nodays,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgsavingcals').dialog('close');
  
$('#gridsavingcals').datagrid('reload');
});
//Auto generated code for deleting
$('#deletesavingcals').click(function(){

    var a=$('#gridsavingcals').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsavingcals').datagrid('getSelected');
                $.ajax({
                 url:'/destroysavingcals/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridsavingcals').datagrid('reload');
            }

});
}
});

});
</script>