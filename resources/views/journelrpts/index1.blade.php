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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgcompanynames' toolbar='#companynames'>
<form id='frmcompanynames'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input  style="height:34px;width:100%" required class='easyui-datebox form-control' name='date' 
 id='date' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Reciept No</label></div><input   required style="height:34px;width:100%" class='easyui-textbox form-control' name='paydet' 
 id='paydet' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Amount</label></div><input   required style="height:34px;width:100%" class='easyui-numberbox form-control' name='amount' 
 id='amount' /></div>
</div>
<input type="hidden" id="memid" name="memid"/>
<input type="hidden"  id="lnid"   name="lnid"/>
</form>
<div style='padding:5px;' id='companynames' /><a href='javascript:void(0)' class='btn btn-primary'id='savecompanynames'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table   showFooter="true" striped="true" rowNumbers="true" class='easyui-datagrid' title='Journel Report' iconCls='fa fa-table' singleSelect='true' url='viewjournel' pagination='true' id='gridcompanynames' method='get' fitColumns='true' style='width:100%' toolbar='#companynamestoolbar'>
<thead><tr>

<th field='transdates' width='100'>Transcation Date</th>
<th field='id'  width='100' >ID</th>
<th field='accountname' width='100'>Account Name</th>
<th field='narration' width='250'>Description</th>
<th field='debit' width='100'>Debit</th>
<th field='credit' width='100'>Credit</th>
<!--<th field='interestcredit' width='100'>Interest</th>
<th field='loancredit' width='100'>Total</th>
<th field='memid' hidden="true" width='100'>Memember</th>-->

</tr></thead>
</table>
<div id='companynamestoolbar'>
 <!--<a href='javascript:void(0)' class='easyui-linkbutton' id='newcompanynames' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editcompanynames' iconCls='icon-edit' > Edit</a>-->
<a href='javascript:void(0)' class='btn btn-primary' id='deletecompanynames' iconCls='icon-remove' > Delete</a>
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newcompanynames').click(function(){
       $('#dlgcompanynames').dialog('open').dialog('setTitle','New Loan Repayments');
url='/savecompanynames';
$('#frmcompanynames').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editcompanynames').click(function(){
    /*   
 var row=$('#gridcompanynames').datagrid('getSelected');
       $('#dlgcompanynames').dialog('open').dialog('setTitle','Edit Loan Repayments');

       $('#frmcompanynames').form('load',row);
       url='/editcompanynames/'+row.loanid;
       
       */
       });
//Auto Generated Code for Saving
$('#savecompanynames').click(function(){ 
    
var date=$('#date').val();
var name=$('#name').val();
var amount=$('#amount').val();
var paymentdetails=$('#paydet').val();
var created_at="";
var updatated_at="";
var memid=$('#memid').val();
var lnid=$('#lnid').val();
$('#frmcompanynames').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
                        $.ajax({
url:url,
method:'POST',
data:{'date':date,'memid':memid,'lnid':lnid,'name':name,'amount':amount,'paymentdetails':paymentdetails,'created_at':created_at,'updatated_at':updatated_at,'_token':$('input[name=_token]').val()},
success:function(data){

    $('#gridcompanynames').datagrid('reload');
}
});
  
$('#dlgcompanynames').dialog('close'); 
                    }
                }
            });

});
//Auto generated code for deleting
$('#deletecompanynames').click(function(){

    var a=$('#gridcompanynames').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a Record to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridcompanynames').datagrid('getSelected');
                $.ajax({
                 url:'/destroyjournel/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    $('#gridcompanynames').datagrid('reload');

                 }
                });
                
            }

});
}
});
$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
   // var product=$('#product').val();
    var branch=$('#branche').val();
$('#gridcompanynames').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,branch:branch}

});
});
});
</script>