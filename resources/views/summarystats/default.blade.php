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
<table class='easyui-datagrid'  striped="true" showFooter='true' rownumbers="true" title='Savings Summary Report' iconCls='fa fa-table' singleSelect='true' url='viewsummary' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="name" width="120">Name</th>
<!-- Start  Dynamic Savings -->
@foreach($result as $results)
<th field="{{$results->savingpdt}}" width='90'>{{$results->productname}}</th>

<!--<th field="{{$results->intpdt}}"  {{$results->hidden}} width='90'>Interest</th>-->

@endforeach





</tr></thead>
</table>
<div id='customerstoolbar'>
&nbsp;<a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Preview</a>
</div>

{{csrf_field()}}
<script>
$(document).ready(function(){
$('#print').click(function(){
    //var row=$('#gridcustomers').datagrid('getSelected');
 window.open('/summarypdf','new_tab');

});
});
</script>