@include('layouts/header')

<table class='easyui-datagrid' showFooter='true' rownumbers="true" title='Defaulters' iconCls='fa fa-table' singleSelect='true' url='default' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="weeks" width="50"># of Weeks</th>
<th field='name' width='90'>Name</th>
<th field='loanrepay' width='30'>Repay Period</th>
<th field='loanbal' width='30'>Loan Balance</th>
<th field='interest' width='30'>Interest</th>
<th field='total' width='30'>Total</th>



</tr></thead>
</table>
<div id='customerstoolbar'>
<a href="javascript:void()" class="easyui-linkbutton" id="print"><i class="fa fa-print"></i> Print</a>
</div>

{{csrf_field()}}
<script>

</script>