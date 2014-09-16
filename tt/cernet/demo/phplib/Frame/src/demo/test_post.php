<?

$i=1;
if( $_POST )
{
	foreach( $_POST as $aPost )
	{
		echo($i." post:".$aPost."<br>");
		$i++;
	}
}
else
{
	echo("result:error");
}

?>