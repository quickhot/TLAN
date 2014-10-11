jQuery("#rowed1").jqGrid({
   	url:'list.php',
	datatype: "json",
   	colNames:['id','上架日期','上架时间','上架商品','商品编号','整体货架照片','货品近景照片','价格签','员工id','员工姓名','门店名称','代理名称'],
   	colModel:[
   	    {name:'id',index:'id', width:"60px",align:"center",editable:false},
   	    {name:'listDate',index:'listDate', width:"80px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'listTime',index:'listTime', width:"60px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'productName',index:'productName', width:"60px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'productCode',index:'productCode', width:"80px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'listFarPic',index:'listFarPic', width:"90px",align:"left", editable:false,editrules:{required:true},formatter:pictures},
   	    {name:'listNearPic',index:'listNearPic', width:"90px",align:"left", editable:false,editrules:{required:true},formatter:pictures},
   	    {name:'barCodePic',index:'barCodePic', width:"90px",align:"left", editable:false,editrules:{required:true},formatter:pictures},
   	    {name:'staffId',index:'staffId', width:"200px",align:"left", editable:false,hidden:true},
   	    {name:'staffName',index:'staffName', width:"60px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'outletName',index:'outletName', width:"80px",align:"left", editable:false,editrules:{required:true}},
   	    {name:'agentName',index:'agentName', width:"80px",align:"left", editable:false,editrules:{required:true}}
   	],
   	rowNum:20,
   	//mtype: "POST",
   	rowTotal: 50000,
   	rowList:[20,50,100],
   	loadonce:true,
   	pager: '#prowed1',
   	sortname: 'id',
   	height: "100%",
    viewrecords: true,
    //rownumbers: true,
    sortorder: "asc",
    grouping: true,
   	groupingView : {
   		groupField : ['listDate'],
   		groupColumnShow : [true],
   		groupText : ['<b>{0}</b>'],
   		groupCollapse : true,
		groupOrder: ['desc'],
		groupSummary : [false],
		groupDataSorted : true
   	},
    footerrow: false,
	caption: "代理信息管理",
	editurl: "agentEdt.php"
});

//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:false,add:false,del:false},//options
		{reloadAfterSubmit:true,closeAfterEdit:true,url:"agentEdt.php",afterSubmit:afSub}, // edit options
		{reloadAfterSubmit:true,closeAfterAdd:true,afterSubmit:afSub}, // add options
		{reloadAfterSubmit:true,afterSubmit:afSub,beforeSubmit:bfSub}, // del options
		{} // search options
		);
//jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});
jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});

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
	if (confirm("您确定要删除该上架商品信息吗？") ){
		res[0] = true;
		return res;
	} else {
		window.location.reload();
		return false;
	}
}

function pictures(cellvalue, options, rowdata)
{
    if (cellvalue !="")
    	//TODO: CHANGE THE HOST
        return '<a href="' + 'http://weixin.jtshmall.com/photos/'+cellvalue+'"><img style="width: 90px; height: 160px;" src="' + 'http://weixin.jtshmall.com/photos/'+cellvalue+'"/></a>';
    else
        return '<a>图片为空</a>';
}
