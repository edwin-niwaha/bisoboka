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
<table class='easyui-datagrid'  striped="true" showFooter='true' rownumbers="true" title='Loan Arrears' iconCls='fa fa-table' singleSelect='true' url='default' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="name" width="100">Name</th>
<th field='loan' width='50'>Loan Balance</th>
<th field='interest' width='50'>Interest</th>
<th field='total' width='50'>Total</th>



</tr></thead>
</table>
<div id='customerstoolbar'>
&nbsp;<a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Print</a>
</div>

{{csrf_field()}}
<script>
$(document).ready(function(){
$('#print').click(function(){
    //var row=$('#gridcustomers').datagrid('getSelected');
 //window.location.href="/loanarrearspdf";
 window.open("loanarrearspdf",'_newtab');

});
});
</script>
