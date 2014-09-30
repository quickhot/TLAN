jQuery("#rowed1").jqGrid({
   	url:'staff.php',
	datatype: "json",
   	colNames:['openId','姓名','性别','证件号码','手机号','代理','门店名称','地址','省份','城市','区/县','员工编号','门店编号','代理编号','区县编号','员工状态'],
   	colModel:[
   	    {name:'openId',index:'openId', width:"60px",align:"center", editable:false,hidden:true},
   	    {name:'staffName',index:'staffName', width:"60px",align:"center", editable:true,hidden:false,editrules:{required:true}},
   		{name:'gender',index:'gender', width:"50px",align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{value:"0:男;1:女"}},
   		{name:'idCard',index:'idCard', width:"200px",align:"left" ,editrules:{required:true},editable:true},
   		{name:'mobileNo',index:'mobileNo', width:"100px",align:"left",editable:true,editrules:{required:true,integer:true}},
   		{name:'agentName',index:'agentName', width:"150px", align:"center",editable:true,
   			edittype:"select",
   			editoptions: {
                dataUrl: 'listAgent.php',
                dataEvents: [
                   {  type: 'change',
                      fn: function(e) {
                         //alert(this.value);
                         var str="";
                         $.ajax({
                        	 url:'listOutlets.php?agentId='+this.value,
                        	 async:false,
                        	 cache:false,
                        	 dataType:"text",
                        	 data:{
                        		 actiontype:this.value
                        	 },
                        	 success: function(text){
                        		 str=text;
                        	 }
                         }
                        		);
                         var agentList=$("select#outletName");
                         agentList.empty();
                         agentList.append(str);
                         //alert(str);
                      }
                   }
                ]
   			}
   		},
   		{name:'outletName',index:'outletName', width:"150px", align:"center",editable:true,edittype:'select',editoptions:{value:{'0':'不修改'}},editrules:{required:true}},
   		{name:'address',index:'address', width:"200px", align:"center",editable:true,hidden:true},
   		{name:'province',index:'province', width:"50px", align:"center",editable:false,
   			edittype:"select",
   			editoptions: {
                dataUrl: 'listProvince.php',
                dataEvents: [
                   {  type: 'change',
                      fn: function(e) {
                         //alert(this.value);
                         var str="";
                         $.ajax({
                        	 url:'listCity.php?provinceId='+this.value,
                        	 async:false,
                        	 cache:false,
                        	 dataType:"text",
                        	 data:{
                        		 actiontype:this.value
                        	 },
                        	 success: function(text){
                        		 str=text;
                        	 }
                         }
                        		);
                         var cityList=$("select#city");
                         cityList.empty();
                         var countyList=$("select#county");
                         countyList.empty();
                         countyList.append("<option value=\"0\">请选择·市·</option>");
                         cityList.append(str);

                         //alert(str);
                      }
                   }
                ]
   			}
   		},
   		{name:'city',index:'city', width:"50px", align:"center",editable:false,edittype:'select',editoptions:{value:{'0':'不修改'},
   			dataEvents: [
     	                   {  type: 'change',
     	                      fn: function(e) {
     	                         //alert(this.value);
     	                         var str="";
     	                         $.ajax({
     	                        	 url:'listCounty.php?cityId='+this.value,
     	                        	 async:false,
     	                        	 cache:false,
     	                        	 dataType:"text",
     	                        	 data:{
     	                        		 actiontype:this.value
     	                        	 },
     	                        	 success: function(text){
     	                        		 if(text==''){text="<option value=\"0\">不修改</option>";};
     	                        		 str=text;
     	                        	 }
     	                         }
     	                        		);
     	                         var countyList=$("select#county");
     	                         countyList.empty();
     	                         countyList.append(str);
     	                         //alert(str);
     	                      }
     	                   }
     	                ]
     			}},
   		{name:'county',index:'county', width:"50px", align:"center",editable:false,edittype:'select',editoptions:{value:{'0':'不修改'}},editrules:{required:true}},
   		{name:'staffId',index:'staffId', width:"200px", align:"center",editable:true,hidden:true},
   		{name:'outletId',index:'outletId', width:"200px", align:"center",editable:true,hidden:true},
   		{name:'agentId',index:'agentId', width:"200px", align:"center",editable:false,hidden:true},
   		{name:'countyId',index:'countyId', width:"200px", align:"center",editable:false,hidden:true},
   		{name:'active',index:'active', width:"50", align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{value:"0:冻结;1:可用"}}
   	],
   	rowNum:20,
   	//mtype: "POST",
   	rowTotal: 50000,
   	rowList:[20,50,100],
   	loadonce:true,
   	pager: '#prowed1',
   	sortname: 'staffId',
   	height: "100%",
    viewrecords: true,
    //rownumbers: true,
    sortorder: "ASC",
    //gridview : true,
	caption: "员工信息管理",
	editurl: "staffEdt.php"
});


//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:true,add:true,del:true},//options
		{reloadAfterSubmit:true,closeAfterEdit:true,url:"staffEdt.php",afterSubmit:afSub}, // edit options
		{reloadAfterSubmit:true,closeAfterAdd:true,afterSubmit:afSub}, // add options
		{reloadAfterSubmit:true,afterSubmit:afSub}, // del options
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
	//alert(res.hello);
	//alert(postdata.staffId);
}