@include('layouts/header')
<div class="easyui-dialog" closed='true' id='dlgpaynow' style="width:40%;">
<div style='padding:5px;' id='purchaseheaders' /><a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a></div>
<form style='width:100%;padding:5px;'  id='frmpaynow' toolbar='#purchaseheaders'>
    <div class='col-lg-6'>
        <div class='form-group'>
        <div><label>Transcation Id</label></div><input type='text' class='form-control' name='purchaseheaderid' 
         id='purchaseheaderid'  readonly/></div>
        </div>
        <div class='col-lg-6'>
        <div class='form-group'>
        <div><label>Date</label></div><input type='date' class='form-control' name='transdates' 
         id='transdates' required  /></div>
        </div>
        <div class='col-lg-6'>
        <div class='form-group'>
        <div><label>Supply Name</label></div><input  class='form-control'   name='companyName' 
         id='companyName' /></div>
        </div>
        <div class='col-lg-6'>
        <div class='form-group'>
        <div><label>Balance</label></div><input required="true" class='form-control'  name='totaldue' 
         id='totaldue' /></div>
        </div><!--
        <div class='col-lg-6'>
                <div class='form-group'>
                <div><label>Stockid</label></div><input required="true" class='form-control'  name='stockid' 
                 id='stockid' /></div>
                </div>
                <div class='col-lg-6'>
                        <div class='form-group'>
                        <div><label>Custid</label></div><input required="true" class='form-control'  name='customerid' 
                         id='customerid' /></div>
                        </div>-->
                        <div class='col-lg-6'>
                            <div class='form-group'>
                            <div><label>Stockname</label></div><input required="true" class='form-control'  name='itemcode' 
                             id='itemcode' /></div>
                            </div>
                           <!-- <div class='col-lg-4'>
                                <div class='form-group'>
                                <div><label>Recieving Account</label></div><input  class='form-control easyui-combobox' style="width:100%;height: 34px;"  data-options="url:'combochartofaccounts/1',valueField:'accountcode',textField:'accountname',method:'get',required:'true'" name='mode' 
                                 id='raccount' /></div>
                                </div>-->
                                <input required="true"  type="hidden" class='form-control'  name='stockid' 
                 id='stockid' />
        
</form>
</div>
<table class='easyui-datagrid' title='Pending Purchase ' iconCls='fa fa-table' singleSelect='true' showFooter="true" url='' pagination='true' id='gridpaynow' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
    <thead><tr>
    <th field='transdates' width='100'>Duedate</th>
    <th field='companyName' width='150'>Supplier</th>
    <th field='itemcode' width='100'>Itemname</th>
    <th field='quantity' width='70'>Qty</th>
    <th field='totalamt' width=100>TotalAmt</th>
    <th field='totalpaid' width=100>Payment</th>
    <th field='totaldue' width='100'>Balance</th>
    </tr></thead>
    </table>
   {{csrf_field()}}
    <div id='customerstoolbar'>
        <label>Date</label>
        <input  class='easyui-datebox'  required  id="date1" name="date1"/>To
        <input  class='easyui-datebox' id="date2" name="date2"  required/>
        <label>Product</label>
        <input class="easyui-combobox" id="product" name="product" data-options="url:'stockscombo',valueField:'id',textField:'name',method:'get' "/>
        <label>Branch</label>
        <input class="easyui-combobox" id="branche" name="branches" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton"
         id="find" name="find"><i class="fa fa-search"></i> Find</a>
     <a href='javascript:void(0)' class='easyui-linkbutton' id='paynow' iconCls='icon-add' >PayNow</a>
    <!--<input  class='easyui-datebox' id='editcustomers' required />To
    <input  class='easyui-datebox' id='deletecustomers'  required/>
    <a href="javascript:void(0)" class="easyui-linkbutton" ><i class="fa fa-search"></i> Find</a>-->
    </div>
    <script>
        var finend=null;
        var finstart=null;
     $(document).ready(function(){

             
    $.ajax({
        async:false,
        url: "activeyear",
        method:"get",
        dataType:"json",
        success: function(data){
            $.each(data, function(index, element) {
            finend=element.endperiod;
            finstart=element.startperiod;

            });
        }
    });

//Loading Financial Period

$('#gridpaynow').datagrid({
method:'get',
url:'pendingreport?end='+finend+'&start='+finstart,

});
       //Seting default Branch

           $('#branche').combobox({
url:'combobranches',
method:'get',
valueField:'id',
textField:'branchname',
onLoadSuccess:function(){
var data=$(this).combobox('getData');
for (var i = 0;i<data.length;i++ ) {
					if(data[i].isDefault==1){
						$('#branche').combobox('select', data[i].id);
                        $('#product').combobox('reload','/stockscombo/'+data[i].id);
					
					}
}

},
onSelect:function(record){

    $('#product').combobox('reload','/stockscombo/'+record.id);
}
});
   $('#paynow').click(function(){
    $('#dlgpaynow').dialog('open');
    var row=$('#gridpaynow').datagrid('getSelected');
    $('#frmpaynow').form('load',row);


   });

  

$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    var product=$('#product').val();
    var branch=$('#branche').val();

$('#gridpaynow').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,product:product,branch:branch}

});

});


$('#savepurchaseheaders').click(function(){
var transdates=$('#transdates').val();
var amount=$('#totaldue').val();
var headno=$('#purchaseheaderid').val();
var stockid=$('#stockid').val();
var customerid=$('#companyName').val();
var itemcode=$('#itemcode').val();
$.ajax({
    data:{'dates':transdates,'itemcode':itemcode,'amount':amount,'stockid':stockid,'headno':headno,'customerid':customerid,'_token':$('input[name=_token]').val()},
    url:'savebalance2',
   method:'post',
   success:function(){
    $('#dlgpaynow').dialog('close');
       $('#gridpaynow').datagrid('reload');
   }


});

});
     });   







        </script>