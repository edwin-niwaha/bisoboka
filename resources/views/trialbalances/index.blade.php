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
<table   class='easyui-datagrid' striped='true' title='Trial Balance'  rowNumbers='true' iconCls='fa fa-table' singleSelect='true' showFooter="true"  pagination='true' url='' id='gridpaynow' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
    <thead><tr>
    <th field='accountname' width='100'>Account Name</th>
    <th field='accountcode' width='150'>Account Code</th>
    <th field='Debits' width='100'>Debit</th>
    <th field='Credits' width='70'>Credit</th>
    

    </tr></thead>
    </table>
    <div id='customerstoolbar'>
        <label>Date</label>
        <input  class='easyui-datebox'  required  id="date1" name="date1"/>To
        <input  class='easyui-datebox' id="date2" name="date2"  required/>&nbsp;
       <!-- <input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->&nbsp;
&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="find" name="find"><i class="fa fa-search"></i> Find</a>
         &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="preview" name="preview"><i class="fa fa-print"></i> Preview</a>

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
    $('#gridpaynow').datagrid({
        pageSize:150,
        pageList:[30,60,90,120,150],


    });
//Loading Trial balance

$('#gridpaynow').datagrid({
method:'get',
url:'trialbalancerpt?end='+finend+'&start='+finstart,

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
						//$('#branche').combobox('select', data[i].id);
                      //  $('#product').combobox('reload','/stockscombo/'+data[i].id);
					
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
 
$('#gridpaynow').datagrid({
method:'get',
queryParams:{date1:date1,date2:date2}

});

});
$('#preview').click(function(){
    var date1=finstart;
    var date2=finend;  
     var day1= $('#date1').val();
     var day2=$('#date2').val();
     if(day1=='' && day2==''){
         day1='Tbal';
         day2='Tbal';
        window.open("/trialbalancepdf/"+date1+"/"+date2+"/"+day1+"/"+day2,'_newtab');
     }else if (day1!='' && day2==''){
        window.open("/trialbalancepdf/"+date1+"/"+date2+"/"+day1+"/Tbal",'_newtab');
     }else if(day1!='' && day2!=''){
        window.open("/trialbalancepdf/"+date1+"/"+date2+"/"+day1+"/"+day2,'_newtab'); 
     }

});


$('#date1').datebox({
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
    $('#date2').datebox({
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