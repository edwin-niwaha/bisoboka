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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgchartofaccounts' toolbar='#chartofaccounts'><form id='frmchartofaccounts'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Account Code</label></div><input style="height:34px;width:100%" required class='easyui-numberbox form-control' name='accountcode' 
 id='accountcode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Account Name</label></div><input style="height:34px;width:100%" required class='easyui-textbox form-control' name='accountname' 
 id='accountname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Account Type </label></div><input style="height:34px;width:100%" required type='text' data-options="url:'comboaccounttypes',method:'get',valueField:'id',textField:'accounttype'" class='easyui-combobox form-control' name='accounttype' 
 id='accounttype' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Main Account</label></div><input style="height:34px;width:100%"  class='easyui-combobox form-control' data-options="url:'combochartofaccounts',method:'get',valueField:'accountcode',textField:'accountname'" name='mainaccount' 
 id='mainaccount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Openingbal</label></div><input  style="height:34px;width:100%"  class='easyui-textbox form-control' name='openingbal' 
 id='openingbal' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Asof</label></div><input  style="height:34px;width:100%"  class='easyui-datebox form-control' name='asof' 
 id='asof' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Cash Account</label></div>
<input id="accept" type="checkbox" name="accept" value="true">
</div>
</div>
<!--<div class='col-lg-6'>
<div class='form-group'>
<div><label>isActive</label></div><input type='text' class='form-control' name='isActive' 
 id='isActive' /></div>
</div>
<input id="typeid" name="typeid" hidden="true">
<div class='col-lg-6'>
<div class='form-group'>
<div><label>isDefault</label></div><input type='text' class='form-control' name='isDefault' 
 id='isDefault' /></div>
</div>-->

<div style='padding:5px;' id='chartofaccounts' /><a href='javascript:void(0)' class='btn btn-primary'id='savechartofaccounts'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' striped="true" title='Chartofaccounts' iconCls='fa fa-table' singleSelect='true' url='viewchartofaccounts' pagination='true' id='gridchartofaccounts' method='get' fitColumns='true' style='width:100%'rowNumbers='true' toolbar='#chartofaccountstoolbar'>
<thead><tr>
<th field='id'  hidden="true"  width='100'>id</th>
<th field='accountname' width='200'>Account Name</th>
<th field='accountcode' width='120'>Account Code</th>
<th field='accounttype' hidden width='120'>Account Type</th>
<th field='names'  width='120'>Account Type</th>
<th field='mainaccount' width='120'>Main Account</th>
<th field='openingbal' width='100'>Opening Bal</th>
<th field='asof' width='100'>Asof</th>
<th field='accept' hidden  width='100'>isCash</th>
<th field='isActive' hidden width='100'>isActive</th>
<th field='isDefault' hidden width='100'>isDefault</th>

</tr></thead>
</table>
<div id='chartofaccountstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newchartofaccounts' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editchartofaccounts' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletechartofaccounts' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>
<a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Preview</a> 
<input  type="file"  style="float:right;    border: 1px solid #ccc;
    display: inline-block;
    padding: 5px 10px;
    cursor: pointer;" id="files" />
 <a href='javascript:void(0)' style="float:right;" class='btn btn-primary' id='import'  ><i class="fa fa-upload" aria-hidden="true"></i>
 Import</a></div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){

    $('#import').click(function(){
var file_data =$('#files').prop('files')[0]; //$('#file')[0].files;
var form_data = new FormData();
form_data.append('files', file_data);
form_data.append('_token', $('input[name=_token]').val());
$.messager.progress({title:'Importing',msg:"Importing ...."});
//alert(file_data);
$.ajax({
    dataType:'Text',
    cache:false,
url:'/importchartofaccounts',
data:form_data,
contentType:false,
processData:false,
method:'post',
success:function(data){
    $('#gridchartofaccounts').datagrid('reload');
    $.messager.progress('close');
}
});
});
    $('#asof').datebox({
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
    $('#openingbal').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
//Auto Generated code for New Entry Code
    
   $('#newchartofaccounts').click(function(){
       $('#dlgchartofaccounts').dialog('open').dialog('setTitle','New chartofaccounts');
url='/savechartofaccounts';
$('#frmchartofaccounts').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editchartofaccounts').click(function(){
       
 var row=$('#gridchartofaccounts').datagrid('getSelected');
       $('#dlgchartofaccounts').dialog('open').dialog('setTitle','Edit chartofaccounts');

       $('#frmchartofaccounts').form('load',row);
       url='/editchartofaccounts/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savechartofaccounts').click(function(){ 
var id=$('#id').val();
var accountcode=$('#accountcode').val();
var accountname=$('#accountname').val();
var accounttype=$('#accounttype').val();
var mainaccount=$('#mainaccount').val();
var openingbal=$('#openingbal').val();
var asof=$('#asof').val();
var isActive=$('#isActive').val();
var isDefault=$('#isDefault').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var accounttip=$('#typeid').val();
$('#frmchartofaccounts').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
$.ajax({
url:url,
method:'POST',
data:{'id':id,'accountip':accounttip,'accountcode':accountcode,'accountname':accountname,'accounttype':accounttype,'mainaccount':mainaccount,'openingbal':openingbal,'asof':asof,'isActive':isActive,'isDefault':isDefault,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.isExist=='yes'){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Account code already Exists, Please change and try again... '});
    }else{
        $('#gridchartofaccounts').datagrid('reload');
    }
    
}
});
  
$('#dlgchartofaccounts').dialog('close');
}
                }
            });
  

});
$('#gridchartofaccounts').datagrid({
        pageSize:50,
        pageList:[10,20,30,40,50],


    });
//Auto generated code for deleting
$('#deletechartofaccounts').click(function(){

    var a=$('#gridchartofaccounts').datagrid('getSelected');
    
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
            
                var row=$('#gridchartofaccounts').datagrid('getSelected');
                $.ajax({
                 url:'/destroychartofaccounts/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    if(data.isdelete=='No'){
        $.messager.alert({title:'Warning',icon:'warning',msg:'Operation Failed,This account has data associated with it. '});
    }else{
        $('#gridchartofaccounts').datagrid('reload');
    }
                   
                 }
                });
                
            }

});
    }
});
$('#print').click(function(){
    id=$('#lnid').val();
 window.open("/chartofaccountpreview/",'_newtab');

});
});
</script>