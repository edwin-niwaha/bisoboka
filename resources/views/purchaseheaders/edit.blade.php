
@include('layouts/header')

<center><h4>Purchase Stock </h4></center>
<div style='padding:5px;margin-left:13px;' id='purchaseheaders' /><a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a></div>
<form style='width:100%;padding:5px;'  id='frmpurchaseheaders' >

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Date</label></div><div><input  class=" easyui-datebox form-control" style="width:100%;height:34px" name='transdates' 
 id='transdates' required="true" data-options="onSelect:validateDate"  /></div>
</div>
</div>

<div class='col-lg-6'>
<div class='form-group'>
<div><label>Mode of Payment</label></div><input  class='form-control easyui-combobox' style="width:100%;height:34px"  data-options="url:'combomodeofpayments',valueField:'id',textField:'name',method:'get',required:'true'" name='mode' 
 id='mode' /></div>
</div>
<div class='col-lg-6'>
<div class='form-group'>
<div><label>Supplier</label></div><input required="true" class='form-control easyui-combobox' style="width:100%;height:34px"  name='supplier_id' 
 id='supplier_id' /></div>
</div>
<div class="col-lg-6">
	<div class="form-group">
		<div><label>Recieving Branch</label></div>
	<div><input type="text" class="form-control easyui-combobox" id='branch' name='branch' style="width:100%;height:34px"/></div>
	</div>
</div>
<input type='hidden' class='form-control' name='id' 
 id='purchaseno'  readonly/>
 <input  type="hidden" class='easyui-combobox'  data-options="method:'get',required:'true'" name='mode' 
	 id='paccount' />
</form>

<div id='toolbaredit'>
	
	<a href="#" class="easyui-linkbutton  "  onclick="addrows()">
		<i class="ace-icon fa fa-plus"></i></a>
	<a href="#" class="easyui-linkbutton " id='save' onclick="saverows()">
		<i class="ace-icon fa fa-floppy-o bigger-160"></i></a>
	<a href="#" class="easyui-linkbutton"  onclick="javascript:$('#tt').edatagrid('cancelRow')">
		<i class="ace-icon fa fa-times"></i>
	</a>
	<a href="#" class="easyui-linkbutton" onclick="deleterows()">
			<i class="ace-icon fa fa-trash-o bigger-160"></i>
		</a>
</div>
</div></div>

	<table class="easyui-datagrid" toolbar="#toolbaredit" title='stockitem' rownumbers='true' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='tt'  fitColumns='true' style='width:100%'  fitColumns='true' showFooter="true">
		<thead>
			<tr>
				<th field='stockid' data-options="editor:
				{type:'combobox',options:{id:'product',url:'',method:'get',valueField:'name',textField:'name',
			onSelect:function(rows){
				var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#tt').datagrid('getEditor',{index:idx,field:'buyingrate'});
				var ed1=$('#tt').datagrid('getEditor',{index:idx,field:'productid'});
				$(ed.target).numberbox('setValue',rows.buyingrate);
				$(ed1.target).numberbox('setValue',rows.id);
			

   


			}}}" width='400'>ItemName</th>
			<th field='buyingrate' editor="numberbox"width='100'>BuyingRate</th>
				<th field='quantity' editor="numberbox" width='100'>Quantity</th>
				
				<th field='totalamt' editor='numberbox' width='100'>TotalAmt</th>
				<th field='totalpaid' editor='numberbox'   width='100'>Totalpaid</th>
				<th field='totaldue' editor='numberbox'  width='100'>Totaldue</th>
				<th field='_token' editor='numberbox' hidden="true" width='100'>Hidden</th>
				<th field='purcnumber' editor='numberbox' hidden="true"  width='100'>Purchase</th>
				<th field="branch_id" editor="numberbox" hidden="true" >Branch</th>
				<th field="id" hidden="true"> Id</th>
				<th field="productid" editor="numberbox" hidden="true">productID</th>
				<th field="paccount" editor="numberbox" hidden="true" >Paccount</th>
				<th field="date" editor="textbox" hidden="true"  >Date</th>
			</tr>
		</thead>
	</table>
	




<!--Suppliers Dialog-->
<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgsuppliers' toolbar='#suppliers'><form id='frmsuppliers'>
	
	<div class='col-lg-6'>
	<div class='form-group'>
	<div><label>companyName</label></div><input type='text' class='form-control' name='companyName' 
	 id='companyName' /></div>
	</div>
	<div class='col-lg-6'>
	<div class='form-group'>
	<div><label>contactPerson</label></div><input type='text' class='form-control' name='contactPerson' 
	 id='contactPerson' /></div>
	</div>
	<div class='col-lg-6'>
	<div class='form-group'>
	<div><label>tel</label></div><input type='text' class='form-control' name='tel' 
	 id='tel' /></div>
	</div>
	<div class='col-lg-6'>
	<div class='form-group'>
	<div><label>email</label></div><input type='text' class='form-control' name='email' 
	 id='email' /></div>
	</div>
	
	</form>
	<div style='padding:5px;' id='suppliers' /><a href='javascript:void(0)' class='btn btn-primary'id='savesuppliers'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
	
	</div></div>
	<!--End of Suppliers Dialog-->
	<!-- Branch Dialog-->
	<div class='easyui-dialog' style='width:50%;padding:5px;' closed='true' id='dlgbranches' toolbar='#branches'><form id='frmbranches'>
	
		<div class='col-lg-6'>
		<div class='form-group'>
		<div><label>branchname</label></div><input type='text' class='form-control' name='branchname' 
		 id='branchname' /></div>
		</div>
		<div class='col-lg-6'>
		<div class='form-group'>
		<div><label>contactPerson</label></div><input type='text' class='form-control' name='contactPerson' 
		 id='contactPerson' /></div>
		</div>
		<div class='col-lg-6'>
		<div class='form-group'>
		<div><label>Tel</label></div><input type='text' class='form-control' name='Tel' 
		 id='Tel' /></div>
		</div>
		<div class='col-lg-6'>
		<div class='form-group'>
		<div><label>Address</label></div><input type='text' class='form-control' name='Address' 
		 id='Address' /></div>
		</div>
		
	</form>
		<div style='padding:5px;' id='branches' /><a href='javascript:void(0)' class='btn btn-primary'id='savebranches'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>
		</div></div>
		<!--End of Dialog Branches-->
   
{{csrf_field()}}
<script type="text/javascript" src="assets/customjs/stockpurchases.js"></script>

