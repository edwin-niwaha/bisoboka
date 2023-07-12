
@include('layouts/header')
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
				saveUrl:'stockquantity',


				
				onClickRow:function(rowIndex){
					if (lastIndex != rowIndex){
						$(this).datagrid('endEdit', lastIndex);
						$(this).datagrid('beginEdit', rowIndex);
					}
					lastIndex = rowIndex;
				},
				
				onBeginEdit:function(rowIndex){
					var editors = $('#tt').datagrid('getEditors', rowIndex);
					var number=$(editors[5].target);
					var quantity=$(editors[3].target);
					var _token=$(editors[4].target);
					var to=$(editors[7].target);
					var branch2=$('#to').val();
					to.numberbox('setValue',branch2);
					var from=$(editors[6].target);
					var branch1=$('#from').val();
					from.numberbox('setValue',branch1);
					var pno=$('#purchaseno').val();
					number.numberbox('setValue',pno);
					var token=$('input[name=_token]').val();
					_token.textbox('setValue',token);
					quantity.numberbox({
						required:'true',
					});
					
					
				
				
					
				}
			

			});
		});
	</script>
<center><h4>Funds Transfer </h4></center>
<div style='padding:5px;margin-left:13px;' id='purchaseheaders' /><a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a></div>
<form style='width:100%;padding:5px;'  id='frmpurchaseheaders' name="frmpurchaseheaders">
<div class='col-lg-6'>
<div class='form-group'>
<div><label>TransferID</label></div><input type='text' class='form-control' name='id' 
 id='purchaseno'  readonly/></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><input type='date' class='form-control' name='transdates' 
 id='transdates' required  /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Source Account</label></div><input  class='form-control easyui-combobox' style="width:100%;"  data-options="url:'combochartofaccounts',valueField:'accountcode',textField:'accountname',method:'get',required:'true'" name='sourceaccount' 
 id='sourceaccount' /></div>
</div>
<div class='col-lg-4'>
<div class='form-group'>
<div><label>Destination Account</label></div><input required="true" class='form-control easyui-combobox' style="width:100%;"   data-options="url:'combochartofaccounts',valueField:'accountcode',textField:'accountname',method:'get',required:true" name='destinationaccount' 
 id='destinationaccount' /></div>
</div>
<div class='col-lg-4'>
	<div class='form-group'>
	<div><label>Narration</label></div><input required="true" class=' easyui-textbox form-control' style="width:100%;padding:1px;"  name='remarks' 
	 id='remarks' /></div>
	</div>


</form>


<div style="margin-bottom:10px" id='toolbar'>
	<a href="#" class="easyui-linkbutton "  onclick="addrows()">
		<i class="ace-icon fa fa-plus"></i></a>
	<a href="#" class="easyui-linkbutton " id='save' onclick="javascript:$('#tt').edatagrid('saveRow')">
		<i class="ace-icon fa fa-floppy-o bigger-160"></i></a>
	<a href="#" class="easyui-linkbutton"  onclick="javascript:$('#tt').edatagrid('cancelRow')">
		<i class="ace-icon fa fa-times"></i>
	</a>
	<a href="#" class="easyui-linkbutton" onclick="javascript:$('#tt').edatagrid('destroyRow')">
			<i class="ace-icon fa fa-trash-o bigger-160"></i>
		</a>
</div>

</div></div>
	<!--<table id="tt" pagination="true" rownumbers="true" singleSelect="true" title="stockitem" iconCls="fa fa-table" fitColumns="true" style="width:100%"></table>-->
	<table class='easyui-datagrid' title='stockitem' rownumbers='true' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='tt'  fitColumns='true' style='width:100%'  fitColumns='true' toolbar="#toolbar" >
		<thead>
			<tr>
				<th field='amount' editor="numberbox" required="true" width='100'>Amount</th>
				<th field='description' editor="textbox"width='100' hidden="true" >Description</th>
				<th field='sellingrate' editor='numberbox' hidden="true"  width='100'>SellingRate</th>
				<th field='quantity' editor="numberbox" hidden="true"  width='100'>Quantity</th>
				<th field='_token' editor='numberbox' hidden="true"   width='100'>Hidden</th>
				<th field='purcnumber' editor='numberbox' hidden="true"    width='100'>Purchase</th>
				<th field='sourceaccount' editor='numberbox' hidden="true"    width='100'>SourceAc</th>
				<th field="destinationaccount" editor="numberbox" hidden="true"  width="100">DestinatonAc</th>
			</tr>
		</thead>
	</table>
	


   
{{csrf_field()}}
<script>

 $(document).ready(function(){
//Auto Generated code for New Entry Code

//Auto Generated Code for Saving
$('#savepurchaseheaders').click(function(){ 
var id=$('#id').val();
var transdates=$('#transdates').val();
var remarks=$('#remarks').val();
var destinationaccount=$('#destinationaccount').val();
var sourceaccount=$('#sourceaccount').val();
var branch_id=$('#branch').val();
if(destinationaccount==sourceaccount){
	$.messager.alert('Info','Source and Destination Accounts cannot be the same');
}else{

	$('#frmpurchaseheaders').form('submit',{
		
	onSubmit: function(){
		if($(this).form('validate')==true && $('#transdates').val()!=""){
$.messager.progress({title:'Saving',msg:'Please wait....'});
$.ajax({
url:'/savetransferheaders',
method:'POST',
data:{'id':id,'transdates':transdates,'remarks':remarks,'_token':$('input[name=_token]').val()},
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
}
});

//Auto generated code for deleting

});




</script>

