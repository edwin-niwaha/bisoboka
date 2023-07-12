@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgloanrepayments' toolbar='#loanrepayments'><form id='frmloanrepayments'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>id</label></div><input type='text' class='form-control' name='id' 
 id='id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>loanid</label></div><input type='text' class='form-control' name='loanid' 
 id='loanid' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>loanamount</label></div><input type='text' class='form-control' name='loanamount' 
 id='loanamount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>loanrunbal</label></div><input type='text' class='form-control' name='loanrunbal' 
 id='loanrunbal' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>interest</label></div><input type='text' class='form-control' name='interest' 
 id='interest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>intrunbal</label></div><input type='text' class='form-control' name='intrunbal' 
 id='intrunbal' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>runningbal</label></div><input type='text' class='form-control' name='runningbal' 
 id='runningbal' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>scheduledate</label></div><input type='text' class='form-control' name='scheduledate' 
 id='scheduledate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>payvalue</label></div><input type='text' class='form-control' name='payvalue' 
 id='payvalue' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>nopayments</label></div><input type='text' class='form-control' name='nopayments' 
 id='nopayments' /></div>
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
<div style='padding:5px;' id='loanrepayments' /><a href='javascript:void(0)' class='btn btn-primary'id='saveloanrepayments'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='loanrepayments' iconCls='fa fa-table' singleSelect='true' url='viewloanrepayments' pagination='true' id='gridloanrepayments' method='get' fitColumns='true' style='width:100%' toolbar='#loanrepaymentstoolbar'>
<thead><tr>
<th field='id' width='100'>id</th>
<th field='loanid' width='100'>loanid</th>
<th field='loanamount' width='100'>loanamount</th>
<th field='loanrunbal' width='100'>loanrunbal</th>
<th field='interest' width='100'>interest</th>
<th field='intrunbal' width='100'>intrunbal</th>
<th field='runningbal' width='100'>runningbal</th>
<th field='scheduledate' width='100'>scheduledate</th>
<th field='payvalue' width='100'>payvalue</th>
<th field='nopayments' width='100'>nopayments</th>
<th field='created_at' width='100'>created_at</th>
<th field='updated_at' width='100'>updated_at</th>
</tr></thead>
</table>
<div id='loanrepaymentstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newloanrepayments' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editloanrepayments' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deleteloanrepayments' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newloanrepayments').click(function(){
       $('#dlgloanrepayments').dialog('open').dialog('setTitle','New loanrepayments');
url='/saveloanrepayments';
$('#frmloanrepayments').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editloanrepayments').click(function(){
       
 var row=$('#gridloanrepayments').datagrid('getSelected');
       $('#dlgloanrepayments').dialog('open').dialog('setTitle','Edit loanrepayments');

       $('#frmloanrepayments').form('load',row);
       url='/editloanrepayments/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveloanrepayments').click(function(){ 
var id=$('#id').val();
var loanid=$('#loanid').val();
var loanamount=$('#loanamount').val();
var loanrunbal=$('#loanrunbal').val();
var interest=$('#interest').val();
var intrunbal=$('#intrunbal').val();
var runningbal=$('#runningbal').val();
var scheduledate=$('#scheduledate').val();
var payvalue=$('#payvalue').val();
var nopayments=$('#nopayments').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'loanid':loanid,'loanamount':loanamount,'loanrunbal':loanrunbal,'interest':interest,'intrunbal':intrunbal,'runningbal':runningbal,'scheduledate':scheduledate,'payvalue':payvalue,'nopayments':nopayments,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgloanrepayments').dialog('close');
  
$('#gridloanrepayments').datagrid('reload');
});
//Auto generated code for deleting
$('#deleteloanrepayments').click(function(){

    var a=$('#gridloanrepayments').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridloanrepayments').datagrid('getSelected');
                $.ajax({
                 url:'/destroyloanrepayments/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridloanrepayments').datagrid('reload');
            }

});
}
});

});
</script>