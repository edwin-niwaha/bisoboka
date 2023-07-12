@include('layouts/header')
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsuppliers' toolbar='#suppliers'><form id='frmsuppliers'>

<!--<div class='col-lg-6'>
<div class='form-group'>
<div><label>Firstname</label></div><input type='text' class='form-control' name='firstname' 
 id='firstname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Secondname</label></div><input type='text' class='form-control' name='secondname' 
 id='secondname' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Address</label></div><input type='text' class='form-control' name='address' 
 id='address' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Tel</label></div><input type='text' class='form-control' name='tel' 
 id='tel' /></div>
</div>-->
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input  class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input type='date' class='form-control' name='date' 
 id='date' /></div>
</div>
<div class='col-lg-6'>
    <div class='form-group'>
    <div><label>Loan Amount</label></div><input type="text" class='form-control' id='amount'name='amount' /> 
  
    </div>
    
    </div>
    <div class='col-lg-6'>
<div class='form-group'>
<div><label>Loan Interest</label></div><input type='text' class='form-control' name='interest' 
 id='interest' /></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Repay Period</label></div><input type='text' class='form-control' name='repay' 
 id='repay' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Mode</label></div><select  class='form-control' name='branch' 
 id='branch' >
 <option value="7">Weeks</option>
 <option value="30">Months</option>
 <option value="365">Years</option>
 </select></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Collateral</label></div><input type='text' class='form-control' name='security' 
 id='security' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Guaranter</label></div><input type='text' class='form-control' name='guaranter' 
 id='guaranter' /></div>
</div>
<div style='padding:5px;' id='suppliers' /><a href='javascript:void(0)' class='btn btn-primary'id='savesuppliers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='Loans' iconCls='fa fa-table' singleSelect='true' url='viewsuppliers' pagination='true' id='gridsuppliers' method='get' fitColumns='true'  rownumbers='true' style='width:100%' toolbar='#supplierstoolbar'>
<thead><tr>
<th field='id' width='50' hidden="true">SupplierId</th>
<th field='date' width='90'>Date</th>
<th field='name' width='50'>Name</th>
<th field='loancredit' width='50'>LoanAmount</th>
<th field='loaninterest' width='90'>InterestRate</th>
<th field='loanrepay' width='90'>RepayPeriod</th>
<th field='collateral' width='40'>security</th>
<th field='guanter' width='40'>Guaranter</th>

</tr></thead>
</table>
<div id='supplierstoolbar'>
 <a href='javascript:void(0)' class='easyui-linkbutton' id='newsuppliers' iconCls='icon-add' >New</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='editsuppliers' iconCls='icon-edit' > Edit</a>
<a href='javascript:void(0)' class='easyui-linkbutton' id='deletesuppliers' iconCls='icon-remove' > Delete</a> </div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsuppliers').click(function(){
       $('#dlgsuppliers').dialog('open').dialog('setTitle','New Loan Disbursement');
     
url='/savesuppliers';
$('#frmsuppliers').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editsuppliers').click(function(){
       
 var row=$('#gridsuppliers').datagrid('getSelected');
       $('#dlgsuppliers').dialog('open');
       $('#frmsuppliers').form('load',row);
       url='/editsuppliers/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#savesuppliers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var tel=$('#name').val();
var branch=$('#branch').val();
var amount=$('#amount').val();
//var address=$('#address').val();
var security=$('#security').val();
var interest=$('#interest').val();
var date=$('#date').val();
var repay=$('#repay').val();
var gauranter=$('#guaranter').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'branch':branch,'security':security,'interest':interest,'date':date,'repay':repay,'gauranter':gauranter,'created_at':created_at,'updated_at':updated_at,'amount':amount,'_token':$('input[name=_token]').val()},
success:function(data){
    $('#gridsuppliers').datagrid('reload');
}
});

  
$('#dlgsuppliers').dialog('close');
});
//Auto generated code for deleting
$('#deletesuppliers').click(function(){

    var a=$('#gridsuppliers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsuppliers').datagrid('getSelected');
                $.ajax({
                 url:'/destroysuppliers/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridsuppliers').datagrid('reload');
            }

});
}
});

});
</script>