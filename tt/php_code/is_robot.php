<?php
function isrobot() {
	$kw_spiders = 'Bot|Crawl|Spider|Slurp|sohu|Twiceler|lycos|robozilla|Google|baidu|msn|yahoo|sogou';
	$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
	if(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return 1;
	} elseif(preg_match("/($kw_browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
		return 0;
	} else {
		return 0;
	}
}

echo $_SERVER['HTTP_USER_AGENT'];

?>