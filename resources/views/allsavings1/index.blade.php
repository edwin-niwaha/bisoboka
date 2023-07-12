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
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgallsavings' toolbar='#allsavings'><form id='frmallsavings'>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input  style="height:34px;width:100%" data-options="onSelect:validateDate,fixComplete" required class='easyui-datebox form-control' name='date' 
 id='date' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Reciept no</label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='recieptno' 
 id='recieptno' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label> Ordinary Savings &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bal :</b><b id="savingpd1"></b></label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='savingpdt1' 
 id='savingpdt1' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Shares&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bal :</b><b id="shar"></b></label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='shares' 
 id='shares' /></div>
 </div>
 <div class='col-lg-6'>
<div class='form-group'>
<div><label>Loan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bal :</b><b id="loanpd1"></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Int :</b><b id="loanin1"></b></label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='loanpdt1' 
 id='loanpdt1' /></div>
 </div>
 <div class='col-lg-6'>
<div class='form-group'>
<div><label>Membership</label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='memship' 
 id='memship' /></div>
 </div>
 <div class='col-lg-6'>
<div class='form-group'>
<div><label>Annual Sub</label></div><input required style="width:100%;height:34px" type='text' class='easyui-textbox form-control' name='ansub' 
 id='ansub' /></div>
 </div>
 <input  name="headerid" type="hidden"  id="headerid"/>
</div>
<div style='padding:5px;' id='allsavings' /><a href='javascript:void(0)' class='btn btn-primary'id='saveallsavings'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' title='Periodic Deposits'  showFooter='true' striped='true' rowNumbers='true' iconCls='fa fa-table' singleSelect='true' url='viewallsavings' pagination='true' id='gridallsavings' method='get' fitColumns='true' style='width:100%' toolbar='#allsavingstoolbar'>
<thead><tr>
<th field='id' hidden width='80'>id</th>
<th field='date' width='70'>Date</th>
<th field='name' width='150'>Name</th>
<th field='recieptno' width='50'>Reciept No</th>
<th field='savingpdt13' hidden width='100'>Narration</th>
<th field='client_no' hidden width='100'>client_no</th>
<th field='headerid' hidden  width='100'>headerid</th>
<th field='savingpdt1' width='80'>Savings</th>
<th field='shares' width='80'>Shares</th>
<th field='loanpdt1' hidden width='80'>Loan</th>
<th field='loan'width='80'>Loan</th>
<th field='loanint1' width='80'>Interest</th>
<th field='memship' width='80'>Membership</th>
<th field='ansub' width='80'>Anual Sub</th>
<th field='total' width='80'>Total</th>
</tr></thead>
</table>
<div id='allsavingstoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newallsavings'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editallsavings'  ><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deleteallsavings' > <i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
  </div>

{{csrf_field()}}
<script>
  var url;
 
 function validateDate(date){
 $.getJSON('activeyear',function(data){
 if(data==''){
 $.messager.alert('Warning','Financial Period Not Set..Please set it and try again','warning');
 $('#date').datebox('setValue','');
 
 }else{
     $.each(data, function (index, value) {
         
 var start= new Date(value.startperiod).getTime()/1000;
 var end =new Date(value.endperiod).getTime()/1000;
 var inputdate=date.getTime()/1000;
 if(inputdate<start || inputdate>end){
 var a=$.messager.alert('Warning','You can not enter a date that is not with in the Active Financial Period '+value.startperiod+ ' AND '+value.endperiod,'warning');
 $('#date').datebox('setValue', '');
 }
 
 });
 }
 
 });
 
 }
 $(document).ready(function(){
//Auto Generated code for New Entry Code
    
   $('#newallsavings').click(function(){
       $('#dlgallsavings').dialog('open').dialog('setTitle','New Periodic Deposits');
url='/saveallsavings';
$('#date').datebox('setValue','');
$('#recieptno').textbox('setValue','');
$('#name').combobox('setValue','');
$('#savingpdt1').textbox('setValue',0);
$('#shares').textbox('setValue',0);
$('#loanpdt1').textbox('setValue',0);
$('#ansub').textbox('setValue',0);
$('#memship').textbox('setValue',0);
//$('#frmallsavings').form('clear');
});
$('#name').combobox({
url:'/customerscombo',
method:'get',
valueField:'id',
textField:'name',
onChange:function(){
    $.getJSON('checkBalAll/'+$(this).combobox('getValue'),function(data){
    $.each(data, function (index, value) {
        $('#savingpd1').text(value.savingpd1);
        $('#loanpd1').text(value.loanpd1);
        $('#loanin1').text(value.loanin1);
        $('#shar').text(value.shares);
       

        }); 
    });
}


});
       //Auto Generated code for Edit Code
 $('#editallsavings').click(function(){
       
 var row=$('#gridallsavings').datagrid('getSelected');
       $('#dlgallsavings').dialog('open').dialog('setTitle','Edit Periodic Deposits');

       $('#frmallsavings').form('load',row);
       url='/editallsavings/'+row.id;
       
       
       });
//Auto Generated Code for Saving
$('#saveallsavings').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var date=$('#date').val();
var paymentdetails=$('#recieptno').val();
var savingpdt1=$('#savingpdt1').val();
var shares=$('#shares').val();
var loanpdt1=$('#loanpdt1').val();
var ansub=$('#ansub').val();
var memship=$('#memship').val();
var headerid=$('#headerid').val();

$.ajax({
url:url,
method:'POST',
data:{'headerid':headerid,'name':name,'date':date,'memship':memship,'ansub':ansub,'savingpdt1':savingpdt1,'shares':shares,'loanpdt1':loanpdt1,'paymentdetails':paymentdetails,'_token':$('input[name=_token]').val()},
success:function(){
    $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
    $('#gridallsavings').datagrid('reload');
}
});
  
$('#dlgallsavings').dialog('close');
  

});
//Auto generated code for deleting
$('#deleteallsavings').click(function(){

    var a=$('#gridallsavings').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridallsavings').datagrid('getSelected');
                $.ajax({
                 url:'/destroyallsavings/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()},
                 success:function(){
                    $('#gridallsavings').datagrid('reload');
                 }
                });
                
            }

});
}
});
$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();

$('#gridallsavings').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2}

});
});
$('#date').datebox({
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
    $('#savingpdt1').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#loanpdt1').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#ansub').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#memship').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#shares').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
});

</script>