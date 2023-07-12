@include('layouts/header')

<table class='easyui-datagrid' showFooter='true' rownumbers="true" title='Ledger' iconCls='fa fa-table' singleSelect='true' url='default' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='weeks' width='150'># of Weeks </th>
<th field='name' width='90'>Name</th>
<th field='loan' width='30'>Loan Taken</th>
<th field='loanrepay' width='90'>Loan Period</th>



</tr></thead>
</table>
<div id='customerstoolbar'>
&nbsp;<a href="javascript:void()" class="easyui-linkbutton" id="print"><i class="fa fa-print"></i> Print</a>
</div>

{{csrf_field()}}
<script>

</script>