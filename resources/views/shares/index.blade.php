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
<table class='easyui-datagrid'  striped="true" showFooter='true' rownumbers="true" title='Shares Report' iconCls='fa fa-table' singleSelect='true' url='viewshares' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="name" width="100">Name</th>
<th field="noshares" width="40">No of shares </th>
<th field='shares' width='50'>Share Amount</th>




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
    window.open("/sharepdfs/",'_newtab');

});
});
</script>