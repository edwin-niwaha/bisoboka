
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
<center >

<table  style="border:10px; width:80%; font-size:15px;" >

@foreach($clientdetails as $cdetails)
<tr>
<th width="100px">Loan Applicant</th> <td width="100px">{{$cdetails->name}}</td>
<th width="100px"> Loan Issue Date</th> <td width="100px">{{$cdetails->date}}</td>
<th width="100px"> Loan Interest % </th> <td width="100px">{{$cdetails->loaninterest}}</td>
</tr>

<tr>
<th width="100px">Loan Amount</th> <td>{{$cdetails->loan}}</td>
<th width="100px">Interest Amount</th> <td>{{$cdetails->interest}}</td>
<th width="100px">Repay Period</th> <td>{{$cdetails->loanrepay}} {{$cdetails->period}} (s) </td>
</tr>


@endforeach
 </table>
 </center>

<input type="hidden" id="lnid"  value={{$id}} />

<table class='easyui-datagrid' striped="true" showFooter='true' rownumbers="true" title='Loan Schedule' iconCls='fa fa-table' singleSelect='true' url='' pagination='true' id='loanschedule' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='nopayments' width='50'> No of Payments</th>
<th field='date' width='50'> Date</th>
<th field='name' hidden width='90'>Name</th>
<th field='loanamount' width='90'>Princple</th>
<th field='interest' width='90'>Interest</th>
<th field='total' width='90'>Total Due</th>
<th  hidden="true" field='loancredit' width='90'>Ledger Balance</th>
<th field='runningbal' width='90'>Ledger Balance</th>
<th field="loanid" hidden width="90">Loanid</th>


</tr></thead>
</table>
<div id='customerstoolbar'>
<a href="javascript:void()" class="easyui-linkbutton" id="print"><i class="fa fa-print"></i> Print</a>

</div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
     id=$('#lnid').val();
$('#loanschedule').datagrid({
url:'/viewloanschedule/'+id,

});
//printing
$('#print').click(function(){
    id=$('#lnid').val();
 window.open("/loanschedulepdf/"+id,'_newtab');

});
});
</script>