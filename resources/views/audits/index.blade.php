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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgaudits' toolbar='#audits'><form id='frmaudits'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>tdate</label></div><input type='text' class='form-control' name='tdate' 
 id='tdate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>event</label></div><input type='text' class='form-control' name='event' 
 id='event' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>branchno</label></div><input type='text' class='form-control' name='branchno' 
 id='branchno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>username</label></div><input type='text' class='form-control' name='username' 
 id='username' /></div>
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
<div style='padding:5px;' id='audits' /><a href='javascript:void(0)' class='btn btn-primary'id='saveaudits'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' striped='true' title='Audit Logs' rowNumbers='true' iconCls='fa fa-table' singleSelect='true' url='viewaudits' pagination='true' id='gridaudits' method='get' fitColumns='true' style='width:100%' toolbar='#auditstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='created_at' width='50'>Transaction Date</th>
<th field='event' width='200'>Event</th>
<th field='branchno' hidden  width='100'>branchno</th>
<th field='username' width='50'>Username</th>

</tr></thead>
</table>
<div id='auditstoolbar'>
 <!--<a href='javascript:void(0)' class='easyui-linkbutton' id='newaudits' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editaudits' iconCls='icon-edit' > Edit</a>-->
<a href='javascript:void(0)' class='btn btn-primary' id='deleteaudits' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>
<label&nbsp;>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;
<label>Username</label>
<input style="height:34px;width:30%" required class='easyui-combobox form-control' data-options="url:'usernamecombo',method:'get',valueField:'name',textField:'name'" name='username' 
 id='username' />

<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newaudits').click(function(){
       $('#dlgaudits').dialog('open').dialog('setTitle','New audits');
url='/saveaudits';
$('#frmaudits').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editaudits').click(function(){
       
 var row=$('#gridaudits').datagrid('getSelected');
       $('#dlgaudits').dialog('open').dialog('setTitle','Edit audits');

       $('#frmaudits').form('load',row);
       url='/editaudits/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveaudits').click(function(){ 
var id=$('#id').val();
var tdate=$('#tdate').val();
var event=$('#event').val();
var branchno=$('#branchno').val();
var username=$('#username').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'tdate':tdate,'event':event,'branchno':branchno,'username':username,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgaudits').dialog('close');
  
$('#gridaudits').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteaudits').click(function(){

    var a=$('#gridaudits').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridaudits').datagrid('getSelected');
                $.ajax({
                 url:'/destroyaudits/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridaudits').datagrid('reload');
                 }
                });
                
            }

});
}
});

$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    var username=$('#username').val();
$('#gridaudits').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,username:username}

});
});

});
</script>