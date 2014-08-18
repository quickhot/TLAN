function DivAlert(messageDiv) {
	this.messageDIV = messageDiv;
	// 创建提示框底层
	this.bottomDIV = document.createElement("div");
	// 获取body中间点
	var x = document.body.clientWidth / 2, y = document.body.clientHeight / 2;
	// 配置样式
	this.bottomDIV.style.opacity = "0.50";
	this.bottomDIV.style.filter = "Alpha(opacity=50);";
	this.bottomDIV.style.backgroundColor = "#CCCCCC";
	this.bottomDIV.style.height = document.body.scrollHeight + "px";
	this.bottomDIV.style.width = "100%";
	this.bottomDIV.style.marginTop = "0px";
	this.bottomDIV.style.marginLeft = "0px";
	this.bottomDIV.style.position = "absolute";
	this.bottomDIV.style.top = "0px";
	this.bottomDIV.style.left = "0px";
	this.bottomDIV.style.zIndex = 100;
	// 显示提示框
	this.show = function() {
		// 显示提示框底层
		document.body.appendChild(this.bottomDIV);
		// 显示messageDIV
		document.body.appendChild(this.messageDIV);
		// 把messageDIV定位到body中间
		this.messageDIV.style.position = "absolute";
		x = x - this.messageDIV.clientWidth / 2;
		y = y - this.messageDIV.clientHeight / 2;
		this.messageDIV.style.top = y + "px";
		this.messageDIV.style.left = x + "px";
		this.messageDIV.style.zIndex = 101;
	}
	// 移除提示框
	this.remove = function() {
		document.body.removeChild(this.bottomDIV);
		document.body.removeChild(this.messageDIV);
	}
}
// 测试DivAlert对象
var dc;
function alertShow(message) {
	// 创建提示框内容部分
	var d = document.createElement("div");
	d.style.width = "220px";
	d.style.height = "150px";
	d.style.backgroundColor = "#AA00CC";
	d.style.padding = "10px";
	// 向提示框内容部分画需要显示的信息
	d.innerHTML = message+"<br/><br/><input type=\"button\" style=\"color:#cc4044\" value=\"关闭\" onclick=\"closeAlert()\"/>"
	// 实例化提示框
	dc = new DivAlert(d);
	// 显示提示框
	dc.show();
}
// 提示框里的Button按钮点击事件
function closeAlert() {
	// 移除对话框
	dc.remove();
}