function playbksound()
		{
			//var agent = navigator.userAgent.toLowerCase();
			//if (agent.indexOf('iphone') > -1)
			//{
				var audiocontainer = document.getElementById('audiocontainer');
				audiocontainer.innerHTML = '<audio id="bgsound" loop="loop" autoplay="autoplay"></audio>';
			
				var audio = document.getElementById('bgsound');
				if (audio == null)
				{
					alert('audio 对象为空')
				}
				audio.src = gSound;
				audio.play();
			/*}
			else
			{
				var audiocontainer = document.getElementById('audiocontainer');
				audiocontainer.innerHTML = '<audio id="bgsound" src="' + gSound + '" autoplay="autoplay" loop="loop"></audio>';
			}*/
		}	
		
	(function(){
		var onBridgeReady = function () {	
		
		playbksound();			
		
		
	// 发送给好友;
		WeixinJSBridge.on('menu:share:appmessage', function(argv){
			WeixinJSBridge.invoke('sendAppMessage',{
				'img_url' : imgUrl,
				'img_width' : '640',
				'img_height' : '640',
				'link' : link,
				'desc' : desc,
				'title' : title
				}, function(res) {});
		});
	// 分享到朋友圈;
		WeixinJSBridge.on('menu:share:timeline', function(argv){
			WeixinJSBridge.invoke('shareTimeline',{
			'img_url' : imgUrl,
			'img_width' : '640',
			'img_height' : '640',
			'link' : link,
			'desc' : desc,
			'title' : desc
			}, function(res) {
			});
		});
	};
	
	if(document.addEventListener){
	document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if(document.attachEvent){
	document.attachEvent('WeixinJSBridgeReady' , onBridgeReady);
	document.attachEvent('onWeixinJSBridgeReady' , onBridgeReady);
	}
	})();