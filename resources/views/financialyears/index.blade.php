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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfinancialyears' toolbar='#financialyears'><form id='frmfinancialyears'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Year ID</label></div><input readonly style="height:34px;width:100%" class='easyui-numberbox form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Start Period</label></div><input  style="height:34px;width:100%"  class='easyui-datebox form-control' name='startperiod' 
 id='startperiod' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Endperiod</label></div><input style="height:34px;width:100%"  class='easyui-datebox form-control' name='endperiod' 
 id='endperiod' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Status</label></div><select  style="height:34px;width:100%" class='easyui-combobox form-control' name='status' 
 id='status'>
<option value=1>Open</option>
<option value=0>Close</option>

<!--<option value=2> Close</option>-->
</select></div>
</div>

<div style='padding:5px;' id='financialyears' /><a href='javascript:void(0)' class='btn btn-primary'id='savefinancialyears'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' rowNumbers="true" striped="true" title='Financial Years' iconCls='fa fa-table' singleSelect='true' url='viewfinancialyears' pagination='true' id='gridfinancialyears' method='get' fitColumns='true' style='width:100%' toolbar='#financialyearstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='startperiod' width='100'>Startperiod</th>
<th field='endperiod' width='100'>Endperiod</th>
<th field='status' width='100'>Status</th>

</tr></thead>
</table>
<div id='financialyearstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newfinancialyears' iconCls='icon-add' > <i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editfinancialyears' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletefinancialyears' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newfinancialyears').click(function(){
       $('#dlgfinancialyears').dialog('open').dialog('setTitle','New financialyears');
url='/savefinancialyears';
$('#frmfinancialyears').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfinancialyears').click(function(){
       
 var row=$('#gridfinancialyears').datagrid('getSelected');
       $('#dlgfinancialyears').dialog('open').dialog('setTitle','Edit financialyears');

       $('#frmfinancialyears').form('load',row);
       url='/editfinancialyears/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savefinancialyears').click(function(){ 
var id=$('#id').val();
var startperiod=$('#startperiod').val();
var endperiod=$('#endperiod').val();
var status=$('#status').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'startperiod':startperiod,'endperiod':endperiod,'status':status,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.year=='closed'){
        $.messager.alert({title:'Warning',icon:'warning',msg:'You cannot Edit a Financial Year which is already closed '});
    }else{
    $('#gridfinancialyears').datagrid('reload');
    }
}
});
$('#dlgfinancialyears').dialog('close'); 

  

});
//Auto generated code for deleting
$('#deletefinancialyears').click(function(){

    var a=$('#gridfinancialyears').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridfinancialyears').datagrid('getSelected');
                $.ajax({
                 url:'/destroyfinancialyears/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                     if(data.year=='closed'){
                        $.messager.alert({title:'Warning',icon:'warning',msg:'You cannot Delete a Financial Year which is already closed '});  
                     }
                 }
                });
                $('#gridfinancialyears').datagrid('reload');
            }

});
}
});
$('#endperiod').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });
$('#startperiod').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });
});
</script>