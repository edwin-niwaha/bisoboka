
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
	<script type="text/javascript">
	var rowIndex;
	
	$("#transdates").val(new Date().toISOString().substring(0, 10));
	function saverows(){
		var id=$('#purchaseno').val();
		$('#gridaccounttrans').edatagrid('saveRow');
		$('#gridaccounttrans').datagrid({
			url:'/viewtrans/'+id+'/C',
			method:'get'
		});


	}

	function deleterows(){
        var rows=$('#gridaccounttrans').datagrid('getSelected');
		$.ajax({
			url:'/delxp/'+rows.accounttransid,
			method:'POST',
			data:{'id':rows.id,'_token':$('input[name=_token]').val()},
			success:function(){
				$('#gridaccounttrans').datagrid('reload');
			}


		});
	
		
	}
	function addrows(){
		$('#frmexpenses').form('submit',{
		
                onSubmit: function(){
					/*
					if($('#transdates').val()==""){
						$.messager.alert('Info',"Please Supply in the Date");
						alert('this is the one');
					}*/
                    if($(this).form('validate')){
						
						$('#gridaccounttrans').edatagrid('addRow');
					
						}
					
					
                },
				
		});
		
	}
	$(function(){

				$.ajax({
        async:false,
        url: "maxnumber",
        method:"get",
        dataType:"json",
        success: function(data){
            $.each(data, function(index, element) {
				$('#purchaseno').val(element.id);

            });
        }
    });
	
			$('#gridaccounttrans').edatagrid({
				saveUrl:'/saveotherincomes',
				url:'/viewInc/'+$('#purchaseno').val(),
				method:'get',
				updateUrl:'/incomeedit',
				onClickRow:function(rowIndex){
					if (lastIndex != rowIndex){
						$(this).datagrid('endEdit', lastIndex);
						$(this).datagrid('beginEdit', rowIndex);
					}
					lastIndex = rowIndex;
				},
				
				onBeginEdit:function(rowIndex){
					var editors = $('#gridaccounttrans').datagrid('getEditors', rowIndex);
					var token = $(editors[0].target);
					var _token=$('input[name=_token]').val();
					token.textbox('setValue',_token);
					var pno=$(editors[1].target);
					pno.numberbox('setValue',$('#purchaseno').val());
					var payingaccount=$(editors[3].target);
					payingaccount.numberbox('setValue',$('#payingaccount').val());
					var narration=$(editors[5].target);
					narration.textbox({required:true,});
					var amount=$(editors[6].target);
					amount.textbox({required:true,
						inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})});
					var dat=$(editors[7].target);
					var actualdate=$('#transdates').val();
					dat.textbox('setValue',actualdate);
				
					

				
					
		
				}
			

			});
		});
		
	</script>
<center><h4>Other Incomes </h4></center>
<div style='padding:5px;margin-left:13px;' id='expenses' /><a href='javascript:void(0)' class='btn btn-primary'id='saveexpenses'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a></div>
<form style='width:100%;padding:5px;'  id='frmexpenses' >
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Ref No.</label></div><input type='text' class='form-control' name='id' 
 id='purchaseno'  readonly/></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><div><input  class="easyui-datebox form-control" style="width:100%;height:34px;"  name='transdates' 
 id='transdates' required="true"  data-options="onSelect:validateDate"  /></div>
</div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Mode of Payment</label></div><input  class='form-control easyui-combobox' style="width:100%;height:34px;"  name='mode' 
 id='mode' /></div>
</div>

<div class="col-lg-6">
	<div class="form-group">
		<div><label>Recieving Account</label></div>
	<div><input type="text" class="form-control easyui-combobox" id='payingaccount' name='payingaccount' style="width:100%;height:34px;"/></div>
	</div>
</div>

</form>
</div>
</div>
<table class='easyui-datagrid' idField="accounttransid" rowNumbers='true' title='OtherIncomes' iconCls='fa fa-table' singleSelect='true' url='' pagination='true' id='gridaccounttrans' method='get' fitColumns='true' style='width:100%' toolbar='#expensestoolbar'>
	<thead><tr>
	<th field="_token" editor="textbox"  hidden="true">_token</th>
	<th field="purchaseno" editor="numberbox" hidden="true" >purchaseno</th>
	<th field='id' width='100' hidden='true'>id</th>
	<th field='accountcode' width='100' editor="numberbox" hidden="true" >Accountcode</th>
	<th field='payingaccount' editor="numberbox" hidden='true'>PayingAccount</th>
	<th field='accountname' width="180" data-options="editor:
	{type:'combobox',options:{url:'combochartofaccounts/6',method:'get',valueField:'accountname',textField:'accountname',
onSelect:function(rows){
	var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#gridaccounttrans').datagrid('getEditor',{index:idx,field:'accountcode'});
				$(ed.target).numberbox('setValue',rows.accountcode);


}}}" width='100'>AccountName</th>
	<th field='narration' width='100' editor="textbox">Narration</th>
	<th field='amount' width='100' editor="textbox">Amount</th>
	<th field='ttype' width='100' hidden='true'>ttype</th>
	<th field="date" editor="textbox" hidden="true">Date</th>
	<th field="Aid" hidden editor="textbox"   >Aid</th>
	<th field="accounttransid" hidden >Accountid</th>
	<th field='client' data-options="editor:
				{type:'combobox',options:{url:'/customerscombo',method:'get',valueField:'name',textField:'name',
			onSelect:function(rows){
				var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#gridaccounttrans').datagrid('getEditor',{index:idx,field:'mcode'});
				$(ed.target).textbox('setValue',rows.id);
			
				
				}}}" width='120'>Client Name</th>
				<th field='mcode'  hidden  editor='numberbox'  width='100'>Member Code</th>

	</tr></thead>
	</table>

<div id='expensestoolbar'>
	
	<a href="#" class="easyui-linkbutton  "  onclick="addrows()">
		<i class="ace-icon fa fa-plus"></i></a>
	<!--<a href="#" class="easyui-linkbutton " id='save' onclick="saverows()">
		<i class="ace-icon fa fa-floppy-o bigger-160"></i></a>
	<a href="#" class="easyui-linkbutton"  onclick="javascript:$('#tt').edatagrid('cancelRow')">
		<i class="ace-icon fa fa-times"></i>-->
	</a>
	<a href="#" class="easyui-linkbutton" onclick="deleterows()">
			<i class="ace-icon fa fa-trash-o bigger-160"></i>
		</a>
</div>


   
{{csrf_field()}}
<script>
function validateDate(date){
$.getJSON('activeyear',function(data){
if(data==''){
$.messager.alert('Warning','Financial Period Not Set..Please set it and try again','warning');
$('#transdates').datebox('setValue','');

}else{
	$.each(data, function (index, value) {
		
var start= new Date(value.startperiod).getTime()/1000;
var end =new Date(value.endperiod).getTime()/1000;
var inputdate=date.getTime()/1000;
if(inputdate<start || inputdate>end){
var a=$.messager.alert('Warning','You can not enter a date that is not with in the Active Financial Period '+value.startperiod+ ' AND '+value.endperiod,'warning');
$('#transdates').datebox('setValue', '');
}

});
}

});

}

 $(document).ready(function(){
//Auto Generated code for New Entry Code

$('#payingaccount').combobox({
url:'combochartofaccounts/1',
method:'get',
valueField:'accountcode',
required:'true',
textField:'accountname',
icons:[{
            iconCls:'icon-add',handler:function(){

				$('#dlgbranches').dialog('open').dialog('setTitle','New Branch');
     
				url='/savebranches';
	          $('#frmbranches').form('clear');
			  $('#savebranches').click(function(){ 
var id=$('#id').val();
var branchname=$('#branchname').val();
var contactPerson=$('#contactPerson').val();
var Tel=$('#Tel').val();
var Address=$('#Address').val();
var isActive=$('#isActive').val();
var isDefault=$('#isDefault').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'branchname':branchname,'contactPerson':contactPerson,'Tel':Tel,'Address':Address,'isActive':isActive,'isDefault':isDefault,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
;
$('#dlgbranches').dialog('close');
			$('#branch').combobox('reload','combobranches')
			});
			;
			
}
}],
onLoadSuccess:function(){
	var data = $(this).combobox("getData");
	
                 for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#branch').combobox('select', data[i].id);
					
					}
               
                }
				var tr=$(this).closest('tr.datagrid-row');
var idx=parseInt(tr.attr('datagrid-row-index'));

var ed=$('#gridaccounttrans').datagrid('getEditor',{index:idx,field:'stockid'});
//$(ed.target).combobox('select','go');

//$(ed.target).combobox('select','go');
				
} ,
onSelect:function(results){
$.ajax({
	url:"/updatebranch1",
	method:'post',
	data:{'pno':$('#purchaseno').val(),'payingaccount':results.accountcode,'_token':$('input[name=_token]').val()}
	


})


}
});
$('#mode').combobox({
url:'combomodeofpayments',
method:'get',
valueField:'id',
required:'true',
textField:'name',
onLoadSuccess:function(){
	var data = $(this).combobox("getData");
	
                 for (var i = 0;i<data.length;i++ ) {
					if(data[i].id==1){
						$('#mode').combobox('select', data[i].id);
					
					}
               
                }
}

});
$('#supplier_id').combobox({
url:'supplierscombo',
method:'get',
valueField:'id',
required:'true',
textField:'companyName',
icons:[{
            iconCls:'icon-add',handler:function(){
			
				$('#dlgsuppliers').dialog('open').dialog('setTitle','New Suppliers');
     
	              
	          $('#frmsuppliers').form('clear');
			 $('#savesuppliers').click(function(){ 
				url='/savesuppliers';
var id=$('#id').val();
var companyName=$('#companyName').val();
var contactPerson=$('#contactPerson').val();
var tel=$('#tel').val();
var email=$('#email').val();
var isDefault=$('#isDefault').val();
var address1=$('#address1').val();
var address2=$('#address2').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'companyName':companyName,'contactPerson':contactPerson,'tel':tel,'email':email,'address1':address1,'address2':address2,'created_at':created_at,'updated_at':updated_at,'isDefault':isDefault,'_token':$('input[name=_token]').val()}
});
$('#dlgsuppliers').dialog('close');
			$('#supplier_id').combobox('reload','supplierscombo');
			});
			;
			
}
}],
onLoadSuccess:function(){
	var data = $(this).combobox("getData");
	
                 for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#supplier_id').combobox('select', data[i].id);
					
					}
               
                }
} 
});

//Auto Generated Code for Saving
$('#saveexpenses').click(function(){ 
var id=$('#id').val();
var transdates=$('#transdates').val();
var mode=$('#mode').val();
var payingaccount=$('#payingaccount').val();
$('#frmexpenses').form('submit',{
	onSubmit: function(){
                    if($(this).form('validate')==true){
						$.messager.progress({title:'Saving',msg:"Please wait...."});


$.ajax({
url:'/savepurchaseheaders',
method:'POST',
data:{'id':id,'transdates':transdates,'mode':mode,'payingaccount':payingaccount,'_token':$('input[name=_token]').val()},
success:function(){
	$.ajax({
        async:false,
        url: "maxnumber",
        method:"get",
        dataType:"json",
        success: function(data){
            $.each(data, function(index, element) {
				$('#purchaseno').val(element.id);
	  var emtpy="";
	  $('#transdates').val(emtpy);
	  var id=$('#purchaseno').val();
		$('#gridaccounttrans').datagrid({
			
			url:'/viewtrans/'+id+'/C',
			method:'get'
		});
	 
		
	  $.messager.progress('close');
	  $.messager.show({title:'Info',msg:'Transcation succesfully Saved'});

            });
        }
    });

}
});

					
					}/*else{
						if($('#transdates').val()==""){
							$.messager.alert('Info','Please Supply in the Date');
						}
					}*/
                }
	});


//get purchase number after saving
 

});
//Auto generated code for deleting
$('#deletepurchaseheaders').click(function(){

    var a=$('#gridpurchaseheaders').datagrid('getSelected');
    if(a==null){
        $.messager.alert('Delete','Please select a row to delete');
        
    }else{
        $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
            if(r){
                var row=$('#gridpurchaseheaders').datagrid('getSelected');
                $.ajax({
                 url:'/destroypurchaseheaders/'+row.id,
                 method:'POST',
                 data:{'id':row.id,'_token':$('input[name=_token]').val()}
                });
                $('#gridpurchaseheaders').datagrid('reload');
            }

});
}
});

$('#transdates').datebox({
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

