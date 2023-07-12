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
<table class='easyui-datagrid'  striped="true" showFooter='true' rownumbers="true" title='Inactive Clients' iconCls='fa fa-table' singleSelect='true' url='/threemonths' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="name" width="100">Name</th>
<th field='acno' width='50'>Account Number</th>
<th field='savingpdt1' width='50'>Last Saved Amount</th>
<th field='date' width='50'>Last  Date</th>



</tr></thead>
</table>
<div id='customerstoolbar'>
<!--&nbsp;<a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Print</a>-->
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
