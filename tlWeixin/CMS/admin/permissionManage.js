var adminId = $("#adminId").val();

jQuery("#rowed1").jqGrid({
   	url:'pInfo.php?adminId='+adminId,
	datatype: "json",
   	colNames:['id','name','parName','adminId'],
   	colModel:[
   	    {name:'id',index:'id', width:"140px",align:"center", editable:false,hidden:false},
   		{name:'name',index:'name', width:"300px",align:"center",editable:true},
   		{name:'parName',index:'parName', width:"140px",align:"center",editable:true},
   		{name:'adminId',index:'adminId', width:"300px",align:"center",editable:true,hidden:false}
   	],
   	rowNum:600,
   	//mtype: "POST",
   	rowTotal: 50000,
   	rowList:[20,50,100],
   	loadonce:true,
   	//pager: '#prowed1',
   	sortname: 'id',
   	height: "100%",
    viewrecords: true,
    //rownumbers: true,
    sortorder: "asc",
    //gridview : true,
	caption: "分配权限",
	multiselect: true,
	grouping: true,
   	groupingView : {
   		groupField : ['parName'],
   		groupColumnShow : [false],
   		groupText : ['<b>{0}</b>'],
   		groupCollapse : false,
		groupOrder: ['asc'],
		//groupSummary : [false],
		groupDataSorted : false
   	},
   	gridComplete:function () {
   		var rowIds = jQuery("#rowed1").jqGrid('getDataIDs').toString();
   		var result;
   		var box = rowIds.split(",");
   		for (var i = 0; i < box.length; i++) {
   			result = $("#rowed1").jqGrid('getCell',box[i],"adminId");
   			//alert(result);
   			if(result == adminId){
   				$("#rowed1").jqGrid('setSelection',box[i]);
   				//$("#"+box[i].id).attr("Checked", true);
   			}
   		}
   	}
});

$("#saveButton").click(function(){
	var saveParam;
	saveParam = jQuery("#rowed1").jqGrid('getGridParam','selarrrow');
	//alert(saveParam.length);
	if(saveParam.length!=0){
	window.location.href="permissionSave.php?saveParam="+saveParam+"&adminId="+adminId;
	} else alert("至少需要选一个权限");
});



