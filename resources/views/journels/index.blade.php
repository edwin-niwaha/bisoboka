
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
	var Purch;
	function addrows(){
		$('#frmpurchaseheaders').form('submit',{
		
                onSubmit: function(){
					if($('#transdates').val()==""){
						$.messager.alert('Info',"Please Supply in the Date");
					}
                    else if($(this).form('validate')){
						$('#tt').edatagrid('addRow');
					
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

			$('#tt').edatagrid({
				saveUrl:'journelsave',
				updateUrl:'editjournel',
				url:'/viewjournelfooters/'+$('#purchaseno').val(),
				method:'get',


				
				onClickRow:function(rowIndex){
					if (lastIndex != rowIndex){
						$(this).datagrid('endEdit', lastIndex);
						$(this).datagrid('beginEdit', rowIndex);
					}
					lastIndex = rowIndex;
				},
				
				onBeginEdit:function(rowIndex){
					var editors = $('#tt').datagrid('getEditors', rowIndex);
					var pno=$(editors[9].target);
					var _token=$(editors[10].target);
					pno.numberbox('setValue',$('#purchaseno').val());
					var token=$('input[name=_token]').val();
					_token.textbox('setValue',token);
					var description=$(editors[3].target);
					description.textbox({
						required:true,
					});
					var debit=$(editors[4].target);
					var credit=$(editors[5].target);
					var datez=$(editors[8].target);
					datez.textbox('setValue',$('#transdates').val());
					debit.textbox({
						inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
					});
					credit.textbox({
						inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
            if(e.which >= 37 && e.which <= 40) return;
            $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
			
		}
	})
					});
				
				
					
				
				
					
				}
			

			});
		});
	</script>
<center><h4>Journel Entries </h4></center>
<div style='padding:5px;margin-left:13px;' id='purchaseheaders' /><a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a></div>
<form style='width:100%;padding:5px;'  id='frmpurchaseheaders' name="frmpurchaseheaders">
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Journel Id</label></div><input type='text' class='form-control' name='id' 
 id='purchaseno'  readonly/></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input  style="width:100%;height:34px;" data-options="onSelect:validateDate" class='easyui-datebox form-control' name='transdates' 
 id='transdates' required  /></div>
</div>



</form>


<div style="margin-bottom:10px" id='toolbar'>
	<a href="#" class="easyui-linkbutton "  onclick="addrows()">
		<i class="ace-icon fa fa-plus"></i></a>

		

	<a href="#" class="easyui-linkbutton" onclick="javascript:$('#tt').edatagrid('destroyRow')">
			<i class="ace-icon fa fa-trash-o bigger-160"></i>
		</a>
</div>

</div></div>
	<!--<table id="tt" pagination="true" rownumbers="true" singleSelect="true" title="stockitem" iconCls="fa fa-table" fitColumns="true" style="width:100%"></table>-->
	<table class='easyui-datagrid' rownumbers="true" title='Journel Entries' idField="accounttransid" rownumbers='true' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='tt'  fitColumns='true' style='width:100%'  fitColumns='true' toolbar="#toolbar" >
		<thead>
			<tr>
				<th field='stockid' data-options="editor:
				{type:'combobox',options:{url:'combochartofaccounts',required:true,method:'get',valueField:'accountname',textField:'accountname',
			onSelect:function(rows){
				var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#tt').datagrid('getEditor',{index:idx,field:'accountcode'});
				$(ed.target).textbox('setValue',rows.accountcode);
				
				}}}" width='250'>AccountName</th>
				<th field="accountcode" editor="numberbox" width="100">AccountCode</th>
				<th field='branch' hidden data-options="editor:
				{type:'combobox',options:{url:'combobranches',method:'get',valueField:'id',textField:'branchname'}}"   width="100">Branch</th>
				<th field='description' required="true" editor="textbox" width="400">Description</th>
				<th field='debit' editor="textbox"width='100'>Debit</th>
				<th field='credit' editor='textbox' width='100'>Credit</th>
				<th field='client' data-options="editor:
				{type:'combobox',options:{url:'/customerscombo',method:'get',valueField:'name',textField:'name',
			onSelect:function(rows){
				var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#tt').datagrid('getEditor',{index:idx,field:'mcode'});
				$(ed.target).textbox('setValue',rows.id);
			
				
				}}}" width='180'>Member / Client</th>
				<th field='mcode'  editor='numberbox' hidden width='100'>Member Code</th>
				<th field='date' hidden editor='textbox' width='100'>Date</th>
				<th field='pno' editor='numberbox' hidden   width='100'>Number</th>
				<th field='_token' editor='textbox' hidden  width='100'>Token</th>
				<th field='accounttransid' editor='numberbox' hidden   width='100'>Number</th>

			
			</tr>
		</thead>
	</table>
	


   
{{csrf_field()}}
<script>
function validateDate(date){
$.getJSON('activeyear',function(data){
if(data==''){
$.messager.alert('Warning','Financial Period Not Set..Please set it and try again','warning');
$('#trandates').datebox('setValue','');

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

//Auto Generated Code for Saving
$('#savepurchaseheaders').click(function(){ 
var id=$('#id').val();
var transdates=$('#transdates').val();

if(validatedata()==true){


	$('#frmpurchaseheaders').form('submit',{
		
	onSubmit: function(){
		if($(this).form('validate')==true && $('#transdates').val()!=""){
$.messager.progress({title:'Saving',msg:'Please wait....'});
$.ajax({
url:'/journelheader',
method:'POST',
data:{'id':id,'transdates':transdates,'_token':$('input[name=_token]').val()},
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
	  //$('#transdates').val(emtpy);
	  var id=$('#purchaseno').val();
		$('#tt').datagrid({
			
			url:'/viewstock/'+id,
			method:'get'
		});
	 
		
	  $.messager.progress('close');
	  $.messager.show({title:'Info',msg:'Transcation succesfully Saved'});

            });
        }
    });
}
});
}else{

	if($('#transdates').val()==""){
							$.messager.alert('Info','Please Supply in the Date');
						}
}

}
});
}else{
	$.messager.alert('Warning','Credits must be equal to Debits Please','warning');
}
// Defining function to determine if total credits are equal to debits
function validatedata(){
	var rows = $('#tt').datagrid('getRows');
	var row = $('#tt').datagrid('getRows');
	var sumcredit=0;
	var sumdebit=0;
for(var i=0; i<rows.length; i++){
  //console.log(rows[i]['credit']);
 // sumcredit=sumcredit+parseInt(rows[i]['credit']); 
 if(rows[i]['credit']!=""){
	sumcredit=sumcredit+parseInt(rows[i]['credit']);
 }
 if(rows[i]['debit']!=""){
	sumdebit=sumdebit+parseInt(rows[i]['debit']);
 }
}
if(sumcredit==sumdebit){
return true;
}
else{
	return false;
}


}
});

//Auto generated code for deleting
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

