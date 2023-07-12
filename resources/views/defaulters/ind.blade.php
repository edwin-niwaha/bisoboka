@include('layouts/header')

<table class='easyui-datagrid' showFooter='true' rownumbers="true" title='Ledger' iconCls='fa fa-table' singleSelect='true' url='default' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='weeks' width='150'># of Weeks </th>
<th field='name' width='90'>Name</th>
<th field='loan' width='30'>Loan Taken</th>
<th field='loanrepay' width='90'>Loan Period</th>



</tr></thead>
</table>
<div id='customerstoolbar'><!--
 <label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>
<label>Name</label>
<input class="easyui-combobox" id="product" name="product" data-options="url:'customerscombo',valueField:'id',textField:'name',method:'get' "/>
&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>-->
</div>

{{csrf_field()}}
<script>

</script>