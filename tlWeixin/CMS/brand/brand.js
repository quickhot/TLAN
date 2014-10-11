jQuery("#rowed1").jqGrid({
   	url:'brand.php',
	datatype: "json",
   	colNames:['id','品牌名称'],
   	colModel:[
   	    {name:'id',index:'id', width:"60px",align:"center",editable:false},
   	    {name:'brandName',index:'brandName', width:"400px",align:"left", editable:true,editrules:{required:true}},
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
    //gridview : true,
	caption: "品牌信息管理",
	editurl: "brandEdt.php"
});

//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:true,add:true,del:true},//options
		{reloadAfterSubmit:true,closeAfterEdit:true,url:"brandEdt.php",afterSubmit:afSub}, // edit options
		{reloadAfterSubmit:true,closeAfterAdd:true,afterSubmit:afSub}, // add options
		{reloadAfterSubmit:true,afterSubmit:afSub,beforeSubmit:bfSub}, // del options
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
	if (confirm("删除品牌，将删除所有关于该品牌的数据。\n包括所有陈列，上架，验货，报数等数据。\n您确定要删除该品牌吗？\n强烈不建议删除！") ){
		res[0] = true;
		return res;
	} else {
		window.location.reload();
		return false;
	}
}

