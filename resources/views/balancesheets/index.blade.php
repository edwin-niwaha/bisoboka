@include('layouts/header')

<table class='easyui-datagrid'  iconCls='fa fa-table' singleSelect='true'  pagination='true' id='gridpaynow' method='get' fitColumns='true' style='width:100%' toolbar='#customerstoolbar'>
    
   
    </table>
    <div id='customerstoolbar'>
        <label>Date</label>
        <input  class='easyui-datebox'  required  id="date1" name="date1"/>To
        <!--<label>Account</label>
        <input class="easyui-combobox" id="account" name="account" data-options="url:'combochartofaccounts',valueField:'accountcode',textField:'accountname',method:'get' "/>
        <label>Branch</label>
        <input class="easyui-combobox" id="branche" name="branche" data-options="url:'combobranches',valueField:'id',textField:'branchname',method:'get' "/>-->&nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="find" name="find"><i class="fa fa-search"></i> Find</a>
         &nbsp;<a href="javascript:void(0)" class="btn btn-primary"
         id="print" name="print"><i class="fa fa-print"></i> Preview</a>
   
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
//Loading Trial balance

         //
         $('#gridpaynow').datagrid({
				title:'Balance Sheet',
				width:'100%',
				rownumbers:true,
				remoteSort:false,
				nowrap:false,
				fitColumns:true,
				url:'balancesheetrpt?end='+finend+'&start='+finstart,
                method:'get',
				columns:[[
					{field:'accounttype',title:'Product ID','hidden':'true',width:100,sortable:true},
					{field:'accountname',title:'AccountName',width:100},
					{field:'accountcode',title:'AccountCode',width:100},
					{field:'Debit',title:'Amount'
				
			},
				
				]],
				groupField:'accounttype',
				view: groupview,
					groupFormatter:function(value, rows){
                        //alert(rows[1].Debit);
                        var data = $('#gridpaynow').datagrid('getData');
                     var start=0;
                     var sum=0;
                var type;
         $.map(rows, function(row){
            sum=sum+parseInt(row['Debit']);
            type=row['type'];
         });
           var nums=rows.length;
          
					return type+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total&nbsp;&nbsp;&nbsp;'+sum;
				}
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

$('#print').click(function(){
    var date3=$('#date1').val();
    var date1=finstart;
    var date2=finend;
    //alert('yes');
   if(date3!=''){
        window.open('/bsheetpdf/'+date1+'/'+date2+'/'+date3,'_newtab');
    }else{
        window.open('/bsheetpdf/'+date1+'/'+date2+'/Bsheet','_newtab');
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
     });   


        </script>