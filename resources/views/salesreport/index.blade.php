@include('layouts/header')

<table class='easyui-datagrid' title='Sales Report' showFooter="true" iconCls='fa fa-table' singleSelect='true'  pagination='true' id='gridcustomers' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='purchaseheaderid' hidden width='100'>NO</th>
<th field='transdate' width='100'>Date</th>
<th field='name' width='100'>Customer</th>
<th field='itemcode' width='100'>Itemname</th>
<th field='quantity' width='100'>Qty</th>
<th field='totalamt' width='100'>TotalAmt</th>
<th field='totalpaid' width='100'>Payment</th>
<th field='totaldue' width='100'>Bal</th>
</tr></thead>
</table>
<div id='customerstoolbar'>
 <label>Date</label>

<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>
<label>Product</label>
<input class="easyui-combobox" id="product" name="product" data-options="method:'get',url:'',valueField:'id',textField:'name'"/>
<label>Branch</label>
<input class="easyui-combobox" id="branche" name="branch" />&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void()" class="easyui-linkbutton" id="del"><i class="fa fa-trash"></i> Del</a>
 &nbsp;<a href="javascript:void()" class="easyui-linkbutton" id="edit"><i class="fa fa-pencil-square-o"></i> Edit</a>
</div>
<!--Dialog for Editing-->

<div class="easyui-dialog" closed='true' id='dlgtrans' style="width:50%;">
    <div style='padding:5px;' id='purchaseheaders' >
    <form style='width:100%;padding:5px;'  id='edittrans' toolbar='#purchaseheaders'>
  
    <div class='col-lg-6'>
    <div class='form-group'>
    <div><label>Date</label></div><input  class='easyui-datebox form-control' style="width:100%;height:34px;" name='transdate' 
     id='transdate' required  /></div>
    </div>
    <div class='col-lg-6'>
        <div class='form-group'>
        <div><label>Transcation No</label></div><input  class='form-control' readonly  name='purchaseheaderid' 
         id='purchaseheaderid' /></div>
        </div>
    <div class='col-lg-6'>
    <div class='form-group'>
    <div><label>Customer Name</label></div><input  class='form-control'   name='name' 
     id='name' /></div>
    </div>
 <!-- Account Code-->
 <input  type="hidden"   name='accountcode' 
     id='accountcode'  />
     <!--End of Account code-->
    
        
            <div class='col-lg-6'>
                    <div class='form-group'>
                    <div><label>Destination Account</label></div>
                    <input  style="height:34px;width:100%"class="easyui-combobox" id="accountname" name="accountname" data-options="url:'combochartofaccounts/1',valueField:'accountcode',textField:'accountname',method:'get' "/>
                </div>
                    </div>
                   
                       
    </form>
    <div id='toolbaredit'>

        </div>
        <table id="tt" class="easyui-datagrid" toolbar="#toolbaredit"  rownumbers='true' iconCls='fa fa-table' singleSelect='true'  pagination='true' id='tt'  fitColumns='true' style='width:100%'  fitColumns='true' showFooter="true"
        url="viewstock/26" method="get" title="Editable DataGrid" iconCls="icon-edit"
        singleSelect="true" idField="itemid" fitColumns="true">
    <thead>
        <tr> <th field='_token' editor="textbox" hidden="true" width='100'>_token</th>
                <th field='name' data-options="editor:
				{type:'combobox',options:{id:'product',url:'stockscombo/1',method:'get',valueField:'name',textField:'name',
			onSelect:function(rows){
				var tr=$(this).closest('tr.datagrid-row');
				var idx=parseInt(tr.attr('datagrid-row-index'));
				var ed=$('#tt').datagrid('getEditor',{index:idx,field:'buyingrate'});
				var ed1=$('#tt').datagrid('getEditor',{index:idx,field:'productid'});
				$(ed.target).numberbox('setValue',rows.buyingrate);
				$(ed1.target).numberbox('setValue',rows.id);
			

   


			}}}" width='150'>PdtName</th>
                <th field='sellingrate' editor="numberbox"width='100'>SellingRate</th>
				<th field='quantity' editor="numberbox" width='80'>Quantity</th>
				<th field='totalamt' editor='numberbox' width='100'>TotalAmt</th>
				<th field='totalpaid' editor='numberbox' width='100'>Totalpaid</th>
                <th field='totaldue' editor="numberbox"  width='100'>Totaldue</th>
                <th field='id'  width='100' hidden='true'>id</th>
                <th field='accountcode' editor="numberbox"  width='100' hidden="true" >Accountcode</th>
                <th field='date' editor="textbox"  width='100' hidden="true" >Date</th>
            <th field="action" width="80" align="center" formatter="formatAction">Action</th>
        </tr>
    </thead>
</table>
    </div>
{{csrf_field()}}
<script type="text/javascript" src="assets/customjs/salesreport.js"></script>