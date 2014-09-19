jQuery("#rowed1").jqGrid({
   	url:'agent.php',
	datatype: "json",
   	colNames:['id','代理名称','代理联系地址'],
   	colModel:[
   	    {name:'id',index:'id', width:"60px",align:"center",editable:false},
   	    {name:'agentName',index:'agentName', width:"200px",align:"left", editable:true,editrules:{required:true}},
   	    {name:'address',index:'address', width:"400px",align:"left", editable:true,editrules:{required:true}}
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
	caption: "代理信息管理",
	editurl: "agentEdt.php"
});

//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:true,add:true,del:true},//options
		{reloadAfterSubmit:true,closeAfterEdit:true}, // edit options
		{reloadAfterSubmit:true,closeAfterAdd:true}, // add options
		{reloadAfterSubmit:true}, // del options
		{} // search options
		);
//jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});
jQuery("#rowed1").jqGrid('filterToolbar',{searchOnEnter : false});


