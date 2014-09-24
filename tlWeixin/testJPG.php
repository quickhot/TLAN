<?php
//获取jpg的信息
$exif = exif_read_data('/newdisk1/weixin.tansuntrade.com/photos/1408611147.jpg', 'ANY_TAG', true);
echo "1408611147.jpg:<br />\n";
if (!($exif===false)) {
	foreach ($exif as $key => $section) {
	    foreach ($section as $name => $val) {
	        echo "$key.$name: $val<br />\n";
	    }
	}
} else {
	echo "No header data found.<br />\n";
}

?>