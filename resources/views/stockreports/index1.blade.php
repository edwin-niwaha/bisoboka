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

<table class='easyui-datagrid' striped="true" showFooter='true' rownumbers="true" title='Ledger' iconCls='fa fa-table' singleSelect='true' url='viewdailystock' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='date' width='50'>Transcation Date</th>
<th field='name' width='90'>Name</th>
<th field='narration' width='250'>Particular</th>
<th field='loan' width='90'>Loan</th>
<th field='interestcredit' width='90'>Interest</th>
<th field='total' width='90'>Total</th>
<th  hidden="true" field='loancredit' width='90'>Ledger Balance</th>
<th field='runningbal' width='90'>Ledger Balance</th>
<th field='expecteddate' width='90'>Maturity Date</th>
<th field="loanid" hidden width="90">Loanid</th>


</tr></thead>
</table>
<div id='customerstoolbar'>
 <label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>
<label>Name</label>
<input class="easyui-combobox" style="width:20%" id="product" name="product" data-options="url:'customerscombo',valueField:'id',textField:'name',method:'get' "/>
&nbsp;
<label>Product</label>
<input class="easyui-combobox"  id="type" name="type" data-options="url:'comboloanproducts',valueField:'id',textField:'name',method:'get' "/>
&nbsp; <a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>

 <a href="javascript:void(0)" class="btn btn-primary"
 id="schedule" name="schedule"><i class="fa fa-calendar"></i> Loan Schedule</a>
</div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
    $('#gridcustomers').datagrid({
    rowStyler:function (index, row) {
			if (row.stockavailabe<row.limitlevel) {
				return 'background-color:rgb(111, 179, 224);';//rgb(209, 91, 71)
			}
		}
});
   
// End Loan Schedule

// Searching
$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    var product=$('#product').val();
    var type=$('#type').val();
   
$('#gridcustomers').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,product:product,type:type}

});

});
$('#schedule').click(function(){
  var a=$('#gridcustomers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Warning','Please select the loan to view its schedule');
        
    }else
    {
    var product=$('#product').val();
  window.open('/loanschedule/'+a.loanid);
    }
})
});
</script>