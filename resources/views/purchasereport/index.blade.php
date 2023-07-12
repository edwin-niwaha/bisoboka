@include('layouts/header')

<table class='easyui-datagrid' title='Purchase Report' iconCls='fa fa-table' showFooter="true" singleSelect='true' url='' pagination='true' id='gridcustomers'  fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='transdates' width='100'>Date</th>
<th field='companyName' width='100'>Supplier</th>
<th field='name' width='100'>Itemname</th>
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
<input class="easyui-combobox" id="product" name="product" data-options="url:'',valueField:'id',textField:'name',method:'get' "/>
<label>Branch</label>
<input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void()" class="easyui-linkbutton" id="del"><i class="fa fa-trash"></i> Del</a>
 &nbsp;<a href="javascript:void()" class="easyui-linkbutton" id="edit"><i class="fa fa-pencil-square-o"></i> Edit</a>

</div>

{{csrf_field()}}
<!--Dialog for Editing-->

<div class="easyui-dialog" closed='true' id='dlgtrans' style="width:50%;">
    <div style='padding:5px;' id='purchaseheaders' /><!--<a href='javascript:void(0)' class='btn btn-primary'id='savepurchaseheaders'>Save</a>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='btn btn-primary'>Close</a>--></div>
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
    <div><label>Supplier Name</label></div><input  class='form-control'   name='companyName' 
     id='companyName' /></div>
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
	<!--
            <a href="#" class="easyui-linkbutton  "  onclick="editrows()">
                <i class="ace-icon fa fa-plus"></i></a>
            <a href="#" class="easyui-linkbutton " id='save' onclick="saverows()">
                <i class="ace-icon fa fa-floppy-o bigger-160"></i></a>
            <a href="#" class="easyui-linkbutton"  onclick="javascript:$('#tt').edatagrid('cancelRow')">
                <i class="ace-icon fa fa-times"></i>
            </a>
            <a href="#" class="easyui-linkbutton" onclick="deleterows()">
                    <i class="ace-icon fa fa-trash-o bigger-160"></i>
                </a>-->
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
                <th field='buyingrate' editor="numberbox"width='100'>BuyingRate</th>
				<th field='quantity' editor="numberbox" width='80'>Quantity</th>
				<th field='totalamt' editor='numberbox' width='100'>TotalAmt</th>
				<th field='totalpaid' editor='numberbox' width='100'>Totalpaid</th>
                <th field='totaldue' editor="numberbox"  width='100'>Totaldue</th>
                <th field='id'  width='100' hidden='true'>id</th>
                <th field='accountcode' editor="numberbox"  width='100' hidden="true" >Accountcode</th>
                <th field='date' editor="textbox"  width='100'  >Date</th>
           <!-- <th field="status" width="60" align="center" editor="{type:'checkbox',options:{on:'P',off:''}}">Status</th>-->
            <th field="action" width="80" align="center" formatter="formatAction">Action</th>
        </tr>
    </thead>
</table>
    </div>
<script>
    //Start of Javascript
    function formatAction(value,row,index){
    if (row.editing){
        var s = '<a href="#" onclick="saverow(this)">Save</a> ';
        var c = '<a href="#" onclick="cancelrow(this)">Cancel</a>';
        return s+c;
    } else {
        var e = '<a href="#" onclick="editrow(this)">Edit</a> ';
        var d = '<a href="#" onclick="deleterow(this)">Delete</a>';
        return e+d;
    }
}


$.extend($.fn.datagrid.defaults.editors, {
    numberspinner: {
        init: function(container, options){
            var input = $('<input type="text">').appendTo(container);
            return input.numberspinner(options);
        },
        destroy: function(target){
            $(target).numberspinner('destroy');
        },
        getValue: function(target){
            return $(target).numberspinner('getValue');
        },
        setValue: function(target, value){
            $(target).numberspinner('setValue',value);
        },
        resize: function(target, width){
            $(target).numberspinner('resize',width);
        }
    }
});
$(function(){
    $('#tt').datagrid({
        onBeforeEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:true}})
        },
        onAfterEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:false}})
        },
        onCancelEdit:function(index,row){
            $(this).datagrid('updateRow', {index:index,row:{editing:false}})
        }
    });
});
function getRowIndex(target){
    var tr = $(target).closest('tr.datagrid-row');
    return parseInt(tr.attr('datagrid-row-index'));
}
function editrow(target){
    $('#tt').datagrid('beginEdit', getRowIndex(target));
}
function deleterow(target){
    var row=$('#tt').datagrid('getSelected');
    if(row==null){

        $.messager.alert("Message","Please select a record to delete");
    }else{
    $.messager.confirm('Confirm','Are you sure?',function(r){
        if (r){
           
            $('#tt').datagrid('deleteRow', getRowIndex(target));
            $.ajax({
                url:'deletesales',
                method:'post',
                data:{'id':row.id,'_token':$('input[name=_token]').val()},



            });
           
            
        }
    });
}
}
function saverow(target){
    var $row=$(target).closest('tr');
    var rows = $('#tt').datagrid('getRows');
$.each(rows, function(i, row) {
  $('#tt').datagrid('endEdit', i);
  
  var url = row.isNewRecord ? 'test.php?savetest=true' : 'updatepurchase';
  $.ajax(url, {
      type:'POST',
      dataType: 'json',
      data:row
  });
});
}
function cancelrow(target){
    $('#tt').datagrid('cancelEdit', getRowIndex(target));
}
$('#tt').edatagrid({
    onClickRow:function(rowIndex){
        if (lastIndex != rowIndex){
            $(this).datagrid('endEdit', lastIndex);
            $(this).datagrid('beginEdit', rowIndex);
        }
        lastIndex = rowIndex;
    },
    
    onBeginEdit:function(rowIndex){
        var editors = $('#tt').datagrid('getEditors', rowIndex);
        var token = $(editors[0].target);
       token.textbox('setValue',$('input[name=_token]').val());
       var quantity=$(editors[3].target);
      var totalamt=$(editors[4].target);
       var selling= $(editors[2].target);
       var accountcode= $(editors[7].target);
       var date= $(editors[8].target);
       date.textbox('setValue',$('#transdate').val());
      
       if(isNaN(parseInt($('#accountname').val()))){
          
        accountcode.numberbox('setValue',$('#accountcode').val());  
       }else{
       accountcode.numberbox('setValue',$('#accountname').val());
      
       }
       selling.numberbox({
           onChange:function(){
               var qty=quantity.numberbox('getValue');
               var sale=selling.numberbox('getValue');
               totalamt.numberbox('setValue',qty*sale);


           }

       });
       quantity.numberbox({
           onChange:function(){
            var qty=quantity.numberbox('getValue');
            var sale=selling.numberbox('getValue');
            totalamt.numberbox('setValue',qty*sale);

           }
       });
       var totalpaid=$(editors[5].target);
       var totaldue=$(editors[6].target);
       totalpaid.numberbox({
           onChange:function(){
               totaldue.numberbox('setValue',totalamt.numberbox('getValue')-totalpaid.numberbox('getValue'));


           }
       })


        
    }

    });



    //End of dialog javascript
 var url;
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

    $('#gridcustomers').datagrid({
method:'get',
url:'viewdailypurchase?end='+finend+'&start='+finstart,

});
//Auto Generated code for New Entry Code
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

}
});

   $('#newcustomers').click(function(){
       $('#dlgcustomers').dialog('open').dialog('setTitle','New customers');
url='/savecustomers';
$('#frmcustomers').form('clear');
});

       //Auto Generated code for Edit Code
       $('#edit').click(function(){
 
 var row=$('#gridcustomers').datagrid('getSelected');
 $('#dlgtrans').dialog('open').dialog('setTitle','Edit Purchase Transcations');
  url="/viewstock/"+row.purchaseheaderid;
  edittranscations();
 $('#edittrans').form('load',row);  

 

 });
//Auto Generated Code for Saving
$('#savecustomers').click(function(){ 
var id=$('#id').val();
var name=$('#name').val();
var phone=$('#phone').val();
var address=$('#address').val();
var tel=$('#tel').val();
var email=$('#email').val();
var isActive=$('#isActive').val();
var created_at=$('#created_at').val();
var updated_at=$('#updated_at').val();
$.ajax({
url:url,
method:'POST',
data:{'id':id,'name':name,'phone':phone,'address':address,'tel':tel,'email':email,'isActive':isActive,'created_at':created_at,'updated_at':updated_at,'_token':$('input[name=_token]').val()}
});
  
$('#dlgcustomers').dialog('close');
  
$('#gridcustomers').datagrid('reload');
});
//Auto generated code for deleting
$('#del').click(function(){

var a=$('#gridcustomers').datagrid('getSelected');
if(a==null){
    $.messager.alert('Delete','Please select a row to delete');
    
}else{
    $.messager.confirm('Confirm','Are you sure you want to Delete this Record',function(r){
        if(r){
            var row=$('#gridcustomers').datagrid('getSelected');
            $.ajax({
             url:'/destroysalesrecord/'+row.purchaseheaderid,
             method:'POST',
             data:{'id':row.purchaseheaderid,'_token':$('input[name=_token]').val()}
            });
            $('#gridcustomers').datagrid('reload');
        }

});
}
});
// Searching
function edittranscations(){

$('#tt').datagrid({
url:url,
onSuccess:function(){

    $('#tt').datagrid('reload');
}


});

    
}


$('#find').click(function(){
    var date1=$('#date1').val();
    var date2=$('#date2').val();
    var product=$('#product').val();
    var branch=$('#branche').val();
$('#gridcustomers').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2,product:product,branch:branch}

});

});
});
</script>