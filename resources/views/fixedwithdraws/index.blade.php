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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfixeddeposits' toolbar='#fixeddeposits'><form id='frmfixeddeposits'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input  required style="height:34px;width:100%" type='text' data-options="onSelect:validateDate" class='easyui-datebox form-control' name='paydate' 
 id='fixdate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%"  class='easyui-combobox form-control' data-options="url:'',method:'get',valueField:'id',textField:'name'"  name='client_id' id='client_id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Payment No</label></div><input required style="height:34px;width:100%"    name='paydet' id='payment' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Pay Out Amount</label></div><input readonly style="height:34px;width:100%" type='text' class='easyui-textbox form-control' name='payoutamount' 
 id='fixamount'  /></div>
</div>
<input hidden  id="fixedid" name="fixedid" />
<input hidden  id="amount" name="amount"/>
<input  hidden    id="interest" name="interest"/>

</div>
<div style='padding:5px;' id='fixeddeposits' /><a href='javascript:void(0)' class='btn btn-primary'id='savefixeddeposits'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' showFooter="true" striped='true' rowNumbers='true' title='Fixed Withdraws' iconCls='fa fa-table' singleSelect='true' url='viewfixedwithdraws' pagination='true' id='gridfixeddeposits' method='get' fitColumns='true' style='width:100%' toolbar='#fixeddepositstoolbar'>
<thead><tr>
<th field='paydate' width='70'>Transaction Date</th>
<th field='id' hidden width='100'>id</th>
<th field='headerid' hidden> Header </th>
<th field='name' width='100'>Name</th>
<th field='client_id' hidden width='50'>Nameid</th>
<th field='paydet'  width='50'>Narration</th>
<th field='payoutamount' width='70'>Paid Amount</th>
<th field='fixinterest' hidden  width='50'>Interest</th>


</tr></thead>
</table>
<div id='fixeddepositstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newfixeddeposits'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<!--<a href='javascript:void(0)' class='btn btn-primary' id='editfixeddeposits'  ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>-->
<a href='javascript:void(0)' class='btn btn-primary' id='deletefixeddeposits'  > <i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> &nbsp;
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a></div>

{{csrf_field()}}
<script>
 var url;
 function validateDate(date){
$.getJSON('activeyear',function(data){
if(data==''){
$.messager.alert('Warning','Financial Period Not Set..Please set it and try again','warning');
$('#fixdate').datebox('setValue','');

}else{
	$.each(data, function (index, value) {
		
var start= new Date(value.startperiod).getTime()/1000;
var end =new Date(value.endperiod).getTime()/1000;
var inputdate=date.getTime()/1000;
if(inputdate<start || inputdate>end){
var a=$.messager.alert('Warning','You can not enter a date that is not with in the Active Financial Period '+value.startperiod+ ' AND '+value.endperiod,'warning');
$('#fixdate').datebox('setValue', '');
}

});
}

});

}
 function fixComplete(){
    $.getJSON('isComplete',function(data){
    $.each(data, function (index, value) {
        var countresults=value.count;
        if(countresults>0){
            $.messager.alert('Warning','There is an Incomplete Transaction, Click ok to fix this Issue','warning');  
        }
        }); 
    });  
}
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newfixeddeposits').click(function(){
       $('#dlgfixeddeposits').dialog('open').dialog('setTitle','New Fixed Withdraw');
url='/paycheck';
$('#frmfixeddeposits').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfixeddeposits').click(function(){
       
 var row=$('#gridfixeddeposits').datagrid('getSelected');
       $('#dlgfixeddeposits').dialog('open').dialog('setTitle','Edit Fixed Withdraw');

       $('#frmfixeddeposits').form('load',row);
       url='/paycheckedit/'+row.id+'/'+row.headerid;
       
       
       });
//Auto Generated Code for Saving
$('#savefixeddeposits').click(function(){ 
var id=$('#id').val();
var client_id=$('#client_id').val();
var fixdate=$('#fixdate').val();
var paydate=$('#fixdate').val();
var fixamount=$('#fixamount').val();
var fixinterest=$('#fixinterest').val();
var fixperiod=$('#fixperiod').val();
var maturitydate=$('#maturitydate').val();
var maturityinterest=$('#maturityinterest').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var payment=$('#payment').val();
var term=$('#term').val();
var fixedid=$('#fixedid').val();
var interest=$('#interest').val();
var amount=$('#amount').val();
var mode=$('#mode').val();
if(fixamount<=0){
    $.messager.alert('warning','No Payout can be done at this time','warning');
}else{
$.ajax({
url:url,
method:'POST',
data:{'paydate':paydate,'payment':payment,'interest':interest,'amount':amount,'fixedid':fixedid,'id':id,'term':term,'client_id':client_id,'mode':mode,'fixdate':fixdate,'fixamount':fixamount,'fixinterest':fixinterest,'fixperiod':fixperiod,'maturitydate':maturitydate,'maturityinterest':maturityinterest,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(){
    $('#gridfixeddeposits').datagrid('reload');
}
});
}
  
$('#dlgfixeddeposits').dialog('close');
  

});
//Auto generated code for deleting
$('#deletefixeddeposits').click(function(){

    var a=$('#gridfixeddeposits').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridfixeddeposits').datagrid('getSelected');
                $.ajax({
                 url:'/destroyfixedwithdraws/'+row.id+'/'+row.headerid,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridfixeddeposits').datagrid('reload');
                 }
                });
                
            }

});
}
});

//fixing date
$('#fixdate').datebox({
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
    // date1 
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
    // date2
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
// putting commas
$('#fixamount').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});

$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();   
$('#gridfixeddeposits').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,}

});

});
/* term combobox */
$('#client_id').combobox({
	url:'/customerscombo',
	valueField:'id',
	textField:'name',
    method:'get',
    onSelect:function(rows){
        $.getJSON('/getcheckpay/'+rows.id,function(data){
            if(data.length==0){
                $('#fixamount').textbox('setValue',0);  
            }else{
            $.each(data, function (index, value) {

             $('#fixamount').textbox('setValue',value.paycheck); 
             $('#fixedid').val(value.fid);
             $('#amount').val(value.fixamount);
             $('#interest').val(value.interest);
            });
            }
        });

    }



});

});
</script>