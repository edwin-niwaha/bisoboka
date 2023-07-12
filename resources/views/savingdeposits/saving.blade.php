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

<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgcompanynames' toolbar='#companynames'>
<form id='frmcompanynames'>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input  style="height:34px;width:100%" data-options="onSelect:validateDate" required class='easyui-datebox form-control' name='date' 
 id='date' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Name</label></div><input style="height:34px;width:100%" required class='easyui-combobox form-control' data-options="url:'customerscombo',method:'get',valueField:'id',textField:'name'" name='name' 
 id='name' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Reciept No</label></div><input   required style="height:34px;width:100%" class='easyui-textbox form-control' name='paydet' 
 id='paydet' /></div>
</div>
@foreach($results as $result)
<div class='col-lg-6'>
<div class='form-group'>
<div><label>{{$result->productname}}</label></div><input value="0"   required style="height:34px;width:100%" class='easyui-textbox form-control' name="{{$result->savingpdt}}"
 id="{{$result->savingpdt}}" /></div>
</div>
@endforeach
<input type="hidden" id="memid" name="memid"/>
<input type="hidden"  id="lnid"   name="lnid"/>
<input type="hidden" id="clino" name="clino"/>
<input type="hidden" id="id" name="id"/>
<input type="hidden" id="headerid" name="headerid"/>
</form>
<div style='padding:5px;' id='companynames' /><a href='javascript:void(0)' class='btn btn-primary'id='savecompanynames'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
</div></div>
<table   showFooter="true" striped="true" rowNumbers="true" class='easyui-datagrid' title='Savings Deposits' iconCls='fa fa-table' singleSelect='true' url='viewdeposits' pagination='true' id='gridcompanynames' method='get' fitColumns='true' style='width:100%' toolbar='#companynamestoolbar'>
<thead><tr>
<th field='header' hidden="true" width='100' >id</th>
<th field='date' width='100'>Transcation Date</th>
<th field='name' width='150'>Name</th>
<th field='paydet' width='100'>Payment Details</th>
<th field='narration' width='250'>Particular</th>
<th field='savings' width='100'>Savings</th>
<th field='savingpdt1' hidden width='100'>Savingpdt1</th>
<th field='savingpdt2' hidden width='100'>Savingpdt2</th>
<th field='memid' hidden="true" width='100'>Memember</th>
<th field='clino' hidden width="100">Client Number </th>
<th field='id' hidden  width="100">id savings </th>
<th field="headerid" hidden width="100" > Header</th>
</tr></thead>
</table>
<div id='companynamestoolbar'>
 <a href='javascript:void(0)' class='btn btn-primary' id='newcompanynames' iconCls='icon-add' ><i class="fa fa-plus-circle" aria-hidden="true"></i> New</a>
<a href='javascript:void(0)' class='btn btn-primary' id='editcompanynames' iconCls='icon-edit' ><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
<a href='javascript:void(0)' class='btn btn-primary' id='deletecompanynames' iconCls='icon-remove' ><i class="fa fa-minus-circle" aria-hidden="true"></i> Delete</a>
<label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
<!--<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 <input  type="file"  style="float:right;    border: 1px solid #ccc;
    display: inline-block;
    padding: 5px 10px;
    cursor: pointer;" id="files" />
 <a href='javascript:void(0)' style="float:right;" class='btn btn-primary' id='import'  ><i class="fa fa-upload" aria-hidden="true"></i>
 Import</a>
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
   // $('#savingpdt1').val(0);
   // $('#shares').val(0);
  
   
   $('#newcompanynames').click(function(){
       fixComplete();
       $('#dlgcompanynames').dialog('open').dialog('setTitle','New Savings Deposits');
url='/savings';
$('#frmcompanynames').form('clear');
$('#shares').textbox('setValue', '0');
$('#savingpdt1').textbox('setValue', '0');
});

$('#import').click(function(){
var file_data =$('#files').prop('files')[0]; //$('#file')[0].files;
var form_data = new FormData();
form_data.append('files', file_data);
form_data.append('_token', $('input[name=_token]').val());
$.messager.progress({title:'Importing',msg:"Importing ...."});
//alert(file_data);
$.ajax({
    dataType:'Text',
    cache:false,
url:'/importsavings',
data:form_data,
contentType:false,
processData:false,
method:'post',
success:function(data){

    $('#gridcompanynames').datagrid('reload');
    $.messager.progress('close');
}
});
});
       //Auto Generated code for Edit Code
 $('#editcompanynames').click(function(){
       
 var row=$('#gridcompanynames').datagrid('getSelected');
       $('#dlgcompanynames').dialog('open').dialog('setTitle','Edit Savings Deposits');

       $('#frmcompanynames').form('load',row);
       
      url='/editsavings/'+row.header;
       
       
       });
//Auto Generated Code for Saving
$('#savecompanynames').click(function(){ 
    
var date=$('#date').val();
var name=$('#name').val();
var amount=$('#amount').val();
var paymentdetails=$('#paydet').val();
var created_at="";
var updatated_at="";
var memid=$('#memid').val();
var lnid=$('#lnid').val();
var id=$('#id').val();
var headerid=$('#headerid').val();
var savingpdt1=$('#savingpdt1').val();
var savingpdt2=$('#savingpdt2').val();
var savingpdt3=$('#savingpdt3').val();
var savingpdt4=$('#savingpdt4').val();
var savingpdt5=$('#savingpdt5').val();
var shares=$('#shares').val();
var clino=$('#clino').val();
$('#frmcompanynames').form('submit',{
				
				onSubmit:function(){
					if($(this).form('validate')==true){
                        $.ajax({
url:url,
method:'POST',
data:{'shares':shares,'headerid':headerid,'id':id,'clino':clino,'savingpdt1':savingpdt1,'savingpdt2':savingpdt2,'savingpdt3':savingpdt3,'savingpdt4':savingpdt4,'savingpdt5':savingpdt5,'date':date,'memid':memid,'lnid':lnid,'name':name,'amount':amount,'paymentdetails':paymentdetails,'created_at':created_at,'updatated_at':updatated_at,'_token':$('input[name=_token]').val()},
success:function(data){
    $.messager.progress('close');
	  $.messager.show({title:'Saving',msg:'Transcation succesfully Saved'});
    $('#gridcompanynames').datagrid('reload');
}
});
  
$('#dlgcompanynames').dialog('close'); 
                    }
                }
            });

});
//Auto generated code for deleting
$('#deletecompanynames').click(function(){

    var a=$('#gridcompanynames').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridcompanynames').datagrid('getSelected');
                $.ajax({
                 url:'/deletesavings/'+row.headerid,
                 method:'POST',
                 data:{'id':row.header,'_token':$('input[name=_token]').val()},
                 success:function(data){
                    $('#gridcompanynames').datagrid('reload');

                 }
                });
                
            }

});
}
});
$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
   // var product=$('#product').val();
    var branch=$('#branche').val();
$('#gridcompanynames').datagrid({
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
    $('#savingpdt4').textbox({
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
$('#savingpdt3').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
});
$('#savingpdt2').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
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

});

</script>