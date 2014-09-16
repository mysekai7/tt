/**
* check which browser is used
*
* return: string
*/
function checkBrowser()
{
	var agentArr = ['msie','firefox'];
	var agent = navigator.userAgent;
	for(var i=0;i<agentArr.length;i++) {
		var reg = new RegExp(agentArr[i]);
		if(reg.test(agent.toLowerCase())) return agentArr[i];
	}
	return false;
}
/**
* a sniffer to check if some browser is used
*
* return: boolean
*/
function isBrowser(type)
{
	var reg = new RegExp(type.toLowerCase());
	var agent = navigator.userAgent;
	return reg.test(agent.toLowerCase());
}

