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
<table class='easyui-datagrid' striped="true" showFooter='true' rownumbers="true" title='Defaulters' iconCls='fa fa-table' singleSelect='true' url='/viewsharesdefaulters' pagination='true' id='gridcustomers' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
<thead><tr>
<th field='name'   width='120'>Name</th>
<th   field='acno' width='90'>Account No</th>
<th field='paid'  width='90'>Paid</th>
<th field='bal'  width='90'>Balance</th>
</tr></thead>
</table>
<div id='customerstoolbar'>
<!-- <label>Date</label>
<input  class='easyui-datebox'  required  id="date1" name="date1"/>To
<input  class='easyui-datebox' id="date2" name="date2"  required/>-->
<label>Name</label>
<select class="easyui-combobox form-control" style="width:20%"  id="client" >
<option value='memship'> MemeberShip Fees</option>
<option value='shares'> Shares </option>
</select>
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
 id="find" name="find"><i class="fa fa-search"></i> Find</a>
 &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="preview" name="preview"><i class="fa fa-print"></i> Preview</a>
</div>

{{csrf_field()}}
<script>
 var url;
 $(document).ready(function(){
     $('#client').combobox('setValue','');
    $('#gridcustomers').datagrid({
    rowStyler:function (index, row) {
			if (row.stockavailabe<row.limitlevel) {
				return 'background-color:rgb(111, 179, 224);';//rgb(209, 91, 71)
			}
		}
});
   
$('#preview').click(function(){
    var client=$('#client').val();

    if(client==''){
    window.open('/memberPreview/memship/','_newtab'); 
    }else if(client=='memship'){
        window.open('/memberPreview/memship/','_newtab');  
    }
    else if(client=='shares'){
        window.open('/memberPreview/shares/','_newtab');  
    }

});
   


// Searching
$('#find').click(function(){
   // var date1=$('#date1').val();
    //var date2=$('#date2').val();
    var client=$('#client').val();
   
$('#gridcustomers').datagrid({
method:'get',
queryParams:{client:client}

});

});

$('#gridcustomers').datagrid({
        pageSize:50,
        pageList:[10,20,30,40,50],


    });
});
</script>