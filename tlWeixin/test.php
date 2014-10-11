<?php
echo "<pre>";
var_dump($_SERVER);
echo "</pre>";

?>
<input type="button" value="goBack" onclick="WeixinJSBridge.call('closeWindow');" />