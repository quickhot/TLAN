jQuery("#rowed1").jqGrid({
   	url:'numOff.php',
	datatype: "json",
   	colNames:['id','报数日期','店员id','店员名字','门店名称','代理名称'],
   	colModel:[
   	 {name:'id',index:'id', width:"50px",align:"center",editable:false},
   	 {name:'countOffDate',index:'countOffDate', width:"200px",align:"left", editable:true,editrules:{required:true}},
   	 {name:'staffId',index:'staffId', width:"50px",align:"left", editable:true,hidden:true},
   	 {name:'staffName',index:'staffName', width:"100px",align:"center", editable:false},
   	 {name:'outletName',index:'outletName', width:"200px",align:"center", editable:true},
   	 {name:'agentName',index:'agentName', width:"100px",align:"center", editable:false}
   	],
   	rowNum:50,
   	//mtype: "POST",
   	rowTotal: 50000,
   	rowList:[50,80,100],
   	loadonce:true,
   	pager: '#prowed1',
   	sortname: 'countOffDate',
   	height: "100%",
    viewrecords: true,
    //rownumbers: true,
    sortorder: "DESC",
    //gridview : true,
	caption: "每日报数信息管理",
    grouping: true,
   	groupingView : {
   		groupField : ['countOffDate'],
   		groupColumnShow : [true],
   		groupText : ['<b>{0}</b>'],
   		groupCollapse : false,
		groupOrder: ['asc'],
		groupSummary : [true],
		groupDataSorted : true
   	},
    footerrow: false,
	editurl: "numOffEdt.php",
   	subGrid:true,
   	subGridRowExpanded: function(subgrid_id, row_id) {
		// we pass two parameters
		// subgrid_id is a id of the div tag created whitin a table data
		// the id of this elemenet is a combination of the "sg_" + id of the row
		// the row_id is the id of the row
		// If we wan to pass additinal parameters to the url we can use
		// a method getRowData(row_id) - which returns associative array in type name-value
		// here we can easy construct the flowing
		var subgrid_table_id, pager_id;
		subgrid_table_id = subgrid_id+"_t";
		pager_id = "p_"+subgrid_table_id;
		$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
		jQuery("#"+subgrid_table_id).jqGrid({
			url:"numOffDetail.php?id="+row_id,
			datatype: "json",
			colNames: ['id','品牌名称','产品名称','数量'],
			colModel: [
				{name:"id",index:"id",width:50,key:true},
				{name:"brandName",index:"brandName",align:"center",width:100},
				{name:"productName",index:"productName",width:100,align:"center"},
				{name:"amount",index:"amount",width:70,align:"center",sortable:false}
			],
		   	rowNum:20,
		   	pager: pager_id,
		   	sortname: 'id',
		    sortorder: "asc",
		    height: '100%'
		});
		jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:false,add:false,del:false})
	},
	subGridRowColapsed: function(subgrid_id, row_id) {
		// this function is called before removing the data
		//var subgrid_table_id;
		//subgrid_table_id = subgrid_id+"_t";
		//jQuery("#"+subgrid_table_id).remove();
	}
});

jQuery("#chngroup").change(function(){
	var vl = $(this).val();
	if(vl) {
		if(vl == "clear") {
			jQuery("#rowed1").jqGrid('groupingRemove',true);
		} else {
			jQuery("#rowed1").jqGrid('groupingGroupBy',vl);
		}
	}
});

//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:true,add:true,del:true},//options
		{reloadAfterSubmit:true,closeAfterEdit:true,url:"numOffEdt.php",afterSubmit:afSub}, // edit options
		{reloadAfterSubmit:true,closeAfterAdd:true,afterSubmit:afSub}, // add options
		{reloadAfterSubmit:true,beforeSubmit:bfSub,afterSubmit:afSub}, // del options
		{} // search options
		);
//jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});
jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});

function afSub(response,postdata){
	var jsonStr = response.responseText;
	//alert(jsonStr);
	var res = JSON.parse(jsonStr);
	if (res.success==0) {
		alert('提交失败，请检查数据');
		return true;
	} else {
		alert('提交成功');
		window.location.reload();
		return false;
	}
}

function bfSub(postdata, formid){
	var res = new Array();
	if (confirm("删除门店，将删除所有关于该门店的数据,\n包括该门店下的员工等。\n您确定要删除该门店吗？") ){
		res[0]=true;
		return res;
	} else {
		window.location.reload();
	}
}

