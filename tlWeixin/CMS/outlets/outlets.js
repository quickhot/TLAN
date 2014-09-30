jQuery("#rowed1").jqGrid({
   	url:'outlets.php',
	datatype: "json",
   	colNames:['id','门店名称','省份Id','省份','城市Id','城市','区县Id','区县','地址','代理Id','所属代理'],
   	colModel:[
   	 {name:'id',index:'id', width:"60px",align:"center",editable:false},
   	 {name:'outletName',index:'outletName', width:"200px",align:"left", editable:true,editrules:{required:true}},
   	 {name:'provinceId',index:'provinceId', width:"200px",align:"left", editable:false,hidden:true},
   	 {name:'province',index:'province', width:"50px",align:"left", editable:true,
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
   	 {name:'cityId',index:'cityId', width:"200px",align:"left", editable:false,hidden:true},
   	 {name:'city',index:'city', width:"50px",align:"left", editable:true,
   		edittype:'select',editoptions:{value:{'0':'不修改'},
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
     			}
   	 },
   	 {name:'countyId',index:'countyId', width:"200px",align:"left", editable:false,hidden:true},
   	 {name:'county',index:'county', width:"50px",align:"left", editable:true,edittype:'select',editoptions:{value:{'0':'不修改'}},editrules:{required:true}},
   	 {name:'address',index:'address', width:"400px",align:"left", editable:true,editrules:{required:true}},
   	 {name:'agentId',index:'agentId', width:"100px",align:"left", editable:false,hidden:true},
   	 {name:'agentName',index:'agentName', width:"100px",align:"left", editable:true,editrules:{required:true},
   		 edittype:"select",editoptions: {dataUrl: 'listAgent.php'}
   	 },
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
	caption: "门店信息管理",
	editurl: "outletsEdt.php"
});

//jQuery("#rowed1").jqGrid('navGrid',"#prowed1",{edit:true,add:true,del:true});

jQuery("#rowed1").jqGrid('navGrid','#prowed1',{edit:true,add:true,del:true},//options
		{reloadAfterSubmit:true,closeAfterEdit:true,url:"outletsEdt.php",afterSubmit:afSub}, // edit options
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

