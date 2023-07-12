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

<table class='easyui-datagrid' striped="true" showFooter="true" title='Loans Disbursed' iconCls='fa fa-table' singleSelect='true' url='/viewloans' pagination='true' id='gridsuppliers' method='get' fitColumns='true'  rownumbers='true' style='width:100%' toolbar='#supplierstoolbar'>
<thead><tr>

<th field='date' width='40'>Date</th>
<th field='name' width='70'>Name</th>
<!--<th field='paydet' width='40'>LoanSheet Details</th>-->
<th field='loaninterest' width='40'>InterestRate</th>
<th field='loanrepay' width='40'>RepayPeriod</th>
<th field='loa' hidden width='40'>Mode</th>
<!--<th field='collateral' width='40'>security</th>
<th field='guanter' width='40'>Guaranter</th>-->
<th field='loancredit' width='50'>LoanAmount</th>
<th field='loanid'  hidden="true" width='10'>Loanid</th>
<th field='memid' hidden="true" width='10'>Loanid</th>
<th field="headerid" hidden="true" >Headerid </th>

</tr></thead>
</table>
<div id='supplierstoolbar'>

 
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>&nbsp;
 <a href="javascript:void()" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Preview</a></div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newsuppliers').click(function(){
       $('#dlgsuppliers').dialog('open').dialog('setTitle','New Loan Disbursement');
     
url='/savesuppliers';
$('#loans').form('clear');
});
// Prininting

$('#print').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();

    if( date1=="" && date2==""){
        window.open("/allloanspdf/day/day",'new_tab');
    }else{
      
        window.open("/allloanspdf/"+date1+"/"+date2,'new_tab');
      

    }


});

       //Auto Generated code for Edit Code
 $('#editsuppliers').click(function(){
       
 var row=$('#gridsuppliers').datagrid('getSelected');

       $('#dlgsuppliers').dialog('open');
       $('#loans').form('load',row);
       url='/editsuppliers/'+row.loanid+'/'+row.headerid;
     
       
       
       });

//Auto Generated Code for Saving
$('#savesuppliers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var tel=$('#name').val();
var branch=$('#branch').val();
var amount=$('#amount').val();
//var address=$('#address').val();
var security=$('#security').val();
var interest=$('#interest').val();
var date=$('#date').val();
var repay=$('#repay').val();
var memid=$('#memid').val();
var gauranter=$('#guaranter').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var paydet=$('#paydet').val();
$('#loans').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
                        $.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'paydet':paydet,'memid':memid,'branch':branch,'security':security,'interest':interest,'date':date,'repay':repay,'gauranter':gauranter,'created_at':created_at,'updated_at':updated_at,'amount':amount,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.results=="true"){
    $.messager.alert("Info","This Person has an exiting Loan. Loan Cannot be Disbursed ");
    }else{
        $('#gridsuppliers').datagrid('reload');
    }
}
});
$('#dlgsuppliers').dialog('close');
                    }
                }
});

});


//Auto generated code for deleting
$('#deletesuppliers').click(function(){

    var a=$('#gridsuppliers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridsuppliers').datagrid('getSelected');
                $.ajax({
                 url:'/destroysuppliers/'+row.loanid+'/'+row.headerid,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridsuppliers').datagrid('reload');
                 }
                });
                
            }

});
}
});
//Querying for the loans

$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
   // var product=$('#product').val();
    var branch=$('#branche').val();
    
$('#gridsuppliers').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,branch:branch}

});

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
    $('#date2').datebox({
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