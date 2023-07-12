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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgfixeddeposits' toolbar='#fixeddeposits'>

<form id='frmfixeddeposits'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input required style="height:34px;width:100%" type='text' data-options="onSelect:validateDate" class='easyui-datebox form-control' name='fixdate' 
 id='fixdate' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input required  style="height:34px;width:100%"  class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'"  name='client_id' id='client_id' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Term</label></div><input required   style="height:34px;width:100%"  class='easyui-combobox form-control'  name='term' id='term' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Amount</label></div><input  required  style="height:34px;width:100%" type='text' class='easyui-textbox form-control' name='fixamount' 
 id='fixamount' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest Rate</label></div><input required style="height:34px;width:100%"  type='text' class='easyui-numberbox form-control' name='fixinterest' 
 id='fixinterest' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Period (months)</label></div><input required  style="height:34px;width:100%"  class='easyui-numberbox form-control' name='fixperiod' 
 id='fixperiod' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Mode</label></div><input required  style="height:34px;width:100%" type='text' class='easyui-combobox form-control' name='mode' 
 id='mode' data-options="url:'combofixedtypes',method:'get',valueField:'id',textField:'name'" /></div>
</div>
</div>
<div style='padding:5px;' id='fixeddeposits' /><a href='javascript:void(0)' class='btn btn-primary'id='savefixeddeposits'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' showFooter="true" striped='true' rowNumbers='true' title='Fixed Deposits' iconCls='fa fa-table' singleSelect='true' url='viewfixeddeposits' pagination='true' id='gridfixeddeposits' method='get' fitColumns='true' style='width:100%' toolbar='#fixeddepositstoolbar'>
<thead><tr>
<th field='id' hidden width='100'>id</th>
<th field='name' width='100'>Name</th>
<th field='client_id' hidden width='50'>Nameid</th>
<th field='fixdate' width='70'>Fix Date</th>

<th field='fixinterest' width='50'>Interest (%)</th>
<th field='fixperiod' width='50'>Period (Months)</th>
<th field='maturitydate' width='70'>Maturity Date</th>
<th field='fixamount' width='70'>Amount</th>
<th field='headerid2' hidden width='70'>Header</th>
<th field='maturityinterest' width='70'>Maturity Interest</th>
<th field="mode" hidden width="100">Type</th>

</tr></thead>
</table>
<div id='fixeddepositstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newfixeddeposits'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editfixeddeposits'  ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletefixeddeposits'  > <i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> &nbsp;
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="print" name="print"><i class="fa fa-print"></i> Print</a></div>

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
       $('#dlgfixeddeposits').dialog('open').dialog('setTitle','New Fixed Deposits');
url='/savefixeddeposits';
$('#frmfixeddeposits').form('clear');
});

       //Auto Generated code for Edit Code
 $('#editfixeddeposits').click(function(){
       
 var row=$('#gridfixeddeposits').datagrid('getSelected');
       $('#dlgfixeddeposits').dialog('open').dialog('setTitle','Edit fixeddeposits');

       $('#frmfixeddeposits').form('load',row);
       url='/editfixeddeposits/'+row.id+'/'+row.headerid2;
       
       
       });
//Auto Generated Code for Saving
$('#savefixeddeposits').click(function(){ 
var id=$('#id').val();
var client_id=$('#client_id').val();
var fixdate=$('#fixdate').val();
var fixamount=$('#fixamount').val();
var fixinterest=$('#fixinterest').val();
var fixperiod=$('#fixperiod').val();
var maturitydate=$('#maturitydate').val();
var maturityinterest=$('#maturityinterest').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var term=$('#term').val();
var mode=$('#mode').val();
$('#frmfixeddeposits').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
                        
$.ajax({
url:url,
method:'POST',
data:{'id':id,'term':term,'client_id':client_id,'mode':mode,'fixdate':fixdate,'fixamount':fixamount,'fixinterest':fixinterest,'fixperiod':fixperiod,'maturitydate':maturitydate,'maturityinterest':maturityinterest,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()},
success:function(){
    $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
    $('#gridfixeddeposits').datagrid('reload');
}
});
$('#dlgfixeddeposits').dialog('close');
                    }
                }
});
  

  

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
                 url:'/destroyfixeddeposits/'+row.id,
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
$('#term').combobox({
	url:'/combofixeddepositconfigs',
	valueField:'id',
	textField:'term',
    method:'get',
    onSelect:function(rows){
    $('#fixinterest').numberbox('setValue',rows.interestrate);
    $('#fixperiod').numberbox('setValue',rows.period);
    }



});

$('#print').click(function(){
    row=$('#gridfixeddeposits').datagrid('getSelected');
 window.open("/fixedcertificate/"+row.id,'_newtab');

});

});
</script>