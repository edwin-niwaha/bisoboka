@include('layouts/header')
<style>
.datagrid-row-alt{
    background-color: #d9f2e7;

}
.datagrid-row-selected {
  background: #009900;
  color: white;
}
</style>
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsharesettings' toolbar='#sharesettings'><form id='frmsharesettings'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Share Price</label></div><input  required class='easyui-numberbox form-control' name='shareprice' 
 id='shareprice' /></div>
</div>

<div style='padding:5px;' id='sharesettings' /><a href='javascript:void(0)' class='btn btn-primary'id='savesharesettings'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid'  striped='true' title='Share settings' rowNumbers="true" iconCls='fa fa-table' singleSelect='true' url='viewsharesettings' pagination='true' id='gridsharesettings' method='get' fitColumns='true' style='width:100%' toolbar='#sharesettingstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='shareprice' width='100'>Share Price</th>
</tr></thead>
</table>
<div id='sharesettingstoolbar'>

<a href='javascript:void(0)' class='btn btn-primary' id='editsharesettings' iconCls='icon-edit' > Edit</a>
 </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsharesettings').click(function(){
       $('#dlgsharesettings').dialog('open').dialog('setTitle','New sharesettings');
url='/savesharesettings';
$('#frmsharesettings').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsharesettings').click(function(){
       
 var row=$('#gridsharesettings').datagrid('getSelected');
       $('#dlgsharesettings').dialog('open').dialog('setTitle','Edit sharesettings');

       $('#frmsharesettings').form('load',row);
       url='/editsharesettings/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesharesettings').click(function(){ 
var id=$('#id').val();
var shareprice=$('#shareprice').val();
var branchno=$('#branchno').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$('#frmsharesettings').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
$.ajax({
url:url,
method:'POST',
data:{'id':id,'shareprice':shareprice,'branchno':branchno,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(){
    $('#gridsharesettings').datagrid('reload');
    $('#dlgsharesettings').dialog('close');
}
});
                    }
                }

});
  

});
//Auto generated code for deleting
$('#deletesharesettings').click(function(){

    var a=$('#gridsharesettings').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsharesettings').datagrid('getSelected');
                $.ajax({
                 url:'/destroysharesettings/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridsharesettings').datagrid('reload');
            }

});
}
});

});
</script>