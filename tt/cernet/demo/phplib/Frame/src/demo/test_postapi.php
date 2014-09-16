<?

//postÊý¾Ý²âÊÔ
//by indraw
//2005/02/22

	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include_once("PostHttp.class.php");

	$postData['test1'] = "hehe";
	$postData['test2'] = "haha";
	$postURL = "http://localhost/base/lib/post/test_post.php";
	
	$http = new PostHttp();
	$http->clearFields();
	foreach ($postData as $key => $val)
	{
		$http->addField($key, $val);
	}
	$http->postPage($postURL);

	//print($http->getHeaders());

	//$http->getPage($postURL);

	$strPostResult = $http->getContent();
	echo("<hr>");
	echo($strPostResult);


?>