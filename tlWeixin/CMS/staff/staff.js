jQuery("#rowed1").jqGrid({
   	url:'staff.php',
	datatype: "json",
	openId,staffName,gender,idCard,mobileNo,outletName,address,agentName,province,city,county,staffId,outletId,agentId,countyId
   	colNames:['openId','姓名','性别','证件号码','手机号','门店名称','地址','代理','省份','城市','区/县','员工编号','门店编号','代理编号','区县编号'],
   	colModel:[
   	    {name:'id',index:'id', width:"60px",align:"center", editable:false,hidden:true},
   	    {name:'roomId',index:'roomId', width:"40px",align:"center", editable:true,hidden:true},
   		{name:'alias',index:'alias', width:"150px",align:"center",editable:true,editrules:{required:true}},
   		{name:'gender',index:'gender', width:"60px",align:"center" ,sortable:true,editrules:{required:true},editable:true,edittype:"select",editoptions:{value:"男:男;女:女"}},
   		{name:'mobileNo',index:'mobileNo', width:"150px",align:"center",editable:true,editrules:{required:true,integer:true}},
   		{name:'IDCardNo',index:'IDCardNo', width:"200px", align:"center",editable:true}
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
	caption: "员工信息管理",
	editurl: "staffEdt.php"
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


