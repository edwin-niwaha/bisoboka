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
<table class='easyui-datagrid'  striped="true" showFooter='true' rownumbers="true" title='Loan Dues' iconCls='fa fa-table' singleSelect='true' url='viewloandue' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field="date" width="50">Issue Date</th>
<th field="maturity" width="50">Maturity</th>
<th field="name" width="80">Name</th>
<th field="acno" width="50">Client No</th>
<th field='loan' width='50'>Loan Balance</th>
<th field='interest' width='50'>Interest</th>
<th field='total' width='50'>Total Amount</th>




</tr></thead>
</table>
<div id='customerstoolbar'>
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>&nbsp;
<!--<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;-->
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;
 &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="print" name="print"><i class="fa fa-print"></i> Preview</a>
 &nbsp;
</div>

{{csrf_field()}}
<script>
$(document).ready(function(){
  $('#find').click(function(){
    var date1=$('#date1').val();  
$('#gridcustomers').datagrid({
method:'get',
queryParams:{date1:date1}

});

});

$('#print').click(function(){
var date1=$('#date1').val();
if(date1!=''){
    window.open("/loanduepdf/"+date1,'_newtab');
}else{
    window.open("/loanduepdf/today",'_newtab');
}

});

$('#date1').datebox({
        formatter : function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        },
        parser : function(s){

            if (!s) return new Date();
            var ss = s.split('-');
            var y = parseInt(ss[2],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[0],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d)
            } else {
                return new Date();
            }
        }

    });

});
</script>