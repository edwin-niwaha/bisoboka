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
<div class='easyui-dialog'  style='width:50%;padding:5px;' closed='true' id='dlgsuppliers' toolbar='#suppliers'>
<form id='loans'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input  required class='easyui-combobox form-control' style="height:34px;width:100%;" data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='name' 
 id='name' /></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input required class='easyui-datebox form-control' data-options="onSelect:validateDate" style="height:34px;width:100%;" name='date' 
 id='date' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Loan Category</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control'  name='loancat' 
 id='loancat' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Loan Purpose</label></div><input required class='easyui-textbox form-control'  style="height:34px;width:100%;" name='paydet' 
 id='paydet' /></div>
</div>
<div class='col-lg-6'>
    <div class='form-group'>
    <div><label>Loan Amount</label></div><input required class='easyui-textbox form-control'  style="height:34px;width:100%;" id='amount' name='loancredit' /> 
  
    </div>
    
    </div>
    <div class='col-lg-6'>
<div class='form-group'>
<div><label>Interest Method</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'combointerestmethods',method:'get',valueField:'id',textField:'name'" name='method' 
 id='method' />
 </div>
</div>
    <div class='col-lg-6'>
<div class='form-group'>
<div><label>Loan Interest %</label></div><input required  class='easyui-numberbox form-control'  style="height:34px;width:100%;" name='interestrate' 
 id='interestrate' /></div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Repay Period</label></div><input required class='easyui-numberbox form-control'  style="height:34px;width:50%;" name='loanrepay' 
 id='repay' /><input style="height:34px;width:50%" required class='easyui-combobox form-control' data-options="url:'combomodes',method:'get',valueField:'name',textField:'name'" name='mode' 
 id='mode' />
 </div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Collateral</label></div><input required class='easyui-textbox form-control'  style="height:34px;width:100%;" name='collateral' 
 id='security' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Guarantor</label></div><input required  class='easyui-combobox form-control'  style="height:34px;width:100%;" name='guanter' 
 id='guaranter' /></div>
 
</div>
<center> Loan Fees</center>
@foreach($loanfees as $fees)
<div class='col-lg-6'>
<div class='form-group'>
<div><label>{{$fees->name}}</label></div><input required class='easyui-textbox form-control'  style="height:34px;width:100%;" name="{{$fees->feevar}}"
 id="{{$fees->feevar}}" /></div>
</div>
@endforeach
<hr>
<input type="hidden" id="memid" name="memid"/>
</div>
</form>
@foreach($loanfees as $fees)
<input type="hidden"  id="{{$fees->feevar}}1"  value="{{$fees->isPercent}}"/>
<input type="hidden"  id="{{$fees->feevar}}2"  value="{{$fees->amount}}"/>
@endforeach
<div style='padding:5px;' id='suppliers' /><a href='javascript:void(0)' class='btn btn-primary'id='savesuppliers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table class='easyui-datagrid' striped="true" showFooter="true" title='Loan Disbursement' iconCls='fa fa-table' singleSelect='true' url='viewsuppliers' pagination='true' id='gridsuppliers' method='get' fitColumns='true'  rownumbers='true' style='width:100%' toolbar='#supplierstoolbar'>
<thead><tr>

<th field='date' width='40'>Date</th>
<th field='name' width='100'>Name</th>
<th field='paydet' width='70'>Loan Purpose</th>
<th field='loaninterest' width='40'>InterestRate</th>
<th field='loanrepay' width='40'>RepayPeriod</th>
<th field='loa' hidden width='40'>Mode</th>
<th field='collateral' width='40'>security</th>
<th field='guanter' width='100'>Guarantor</th>
<th field='loancredit' width='50'>LoanAmount</th>
<th field='loanid'  hidden="true" width='10'>Loanid</th>
<th field='memid' hidden="true" width='10'>Loanid</th>
<th field="headerid" hidden="true"  >Headerid </th>

</tr></thead>
</table>
<div id='supplierstoolbar'>

 <a href='javascript:void(0)' class='btn btn-primary' id='newsuppliers'  ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editsuppliers' iconCls='icon-edit' > <i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletesuppliers' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a> 
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="loansch" name="find"><i class="fa fa-calendar"></i> Loan Schedule</a>
 </div>

{{csrf_field()}}
<script>
 var url;
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
   $('#newsuppliers').click(function(){
       fixComplete();
       $('#dlgsuppliers').dialog('open').dialog('setTitle','New Loan Disbursement');
           
url='/savesuppliers';
$('#loans').form('clear');
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
var mode=$('#mode').val();
var security=$('#security').val();
var interest=$('#interestrate').val();
var date=$('#date').val();
var repay=$('#repay').val();
var memid=$('#memid').val();
var gauranter=$('#guaranter').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
var paydet=$('#paydet').val();
var method=$('#method').val();
var loancat=$('#loancat').val();
var loanrepay=$('#loanrepay').val();
var loanfee1=$('#loanfee1').val();
var loanfee2=$('#loanfee2').val();
var loanfee3=$('#loanfee3').val();

$('#loans').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
                        $.ajax({
url:url,
method:'POST',
data:{'loanfee1':loanfee1,'loanfee2':loanfee2,'loanfee3':loanfee3,'method':method,'loancat':loancat,'loanrepay':loanrepay,'id':id,'name':name,'paydet':paydet,'memid':memid,'branch':branch,'security':security,'interest':interest,'date':date,'mode':mode,'repay':repay,'gauranter':gauranter,'created_at':created_at,'updated_at':updated_at,'amount':amount,'_token':$('input[name=_token]').val()},
success:function(data){
    if(data.results=="true"){
    $.messager.alert("Info","This Person has an exiting Loan. Loan Cannot be Disbursed ");
    }else{
        
        $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
      $('#gridsuppliers').datagrid('reload');
    }
}
});
$('#dlgsuppliers').dialog('close');
                    }
                }
});

});
// Loading data from loan categories 
$('#loancat').combobox({
	url:'/comboloanproducts',
	valueField:'id',
	textField:'name',
    'method':'get',
    onSelect:function(data){
       //interest.numberbox('setValue',data.interest);
       //console.log(data.interest);
       $('#interestrate').numberbox('setValue',data.interest);
    }


});
$('#guaranter').combobox({
	url:'/customerscombo',
	valueField:'name',
	textField:'name',
    multiple:'true',
    'method':'get',



});



// End of load data from loan categories


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
$('#loansch').click(function(){
    var a=$('#gridsuppliers').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Info','Please select a Recored to View');
        
    }else{
    window.open('/loanschedule/'+a.loanid);
    }

});
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
    // setting up percentage amount

        $('#amount').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
              var cleanamount=$(this).val().split(",").join("");
              if($('#loanfee11').val()==1){
                  var percentage=($('#loanfee12').val()/100)*cleanamount;
                  $('#loanfee1').textbox('setValue',percentage);
              }else{
                $('#loanfee1').textbox('setValue',$('#loanfee12').val()); 
              }
              if($('#loanfee21').val()==1){
                  var percentage=($('#loanfee22').val()/100)*cleanamount;
                  $('#loanfee2').textbox('setValue',percentage);
              }else{
                $('#loanfee2').textbox('setValue',$('#loanfee22').val());
              }
              if($('#loanfee31').val()==1){
                  var percentage=($('#loanfee32').val()/100)*cleanamount;
                  $('#loanfee3').textbox('setValue',percentage);
              }else{
                $('#loanfee3').textbox('setValue',$('#loanfee32').val());
              }
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#loanfee1').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#loanfee2').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#loanfee3').textbox({
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