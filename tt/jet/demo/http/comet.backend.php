<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
flush();

?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Comet php backend</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

	<script type="text/javascript">
		// load the comet object
		var f = window.parent._parent.callback;
	</script>

	<?php

		while(1) {
			echo '<script type="text/javascript">f('.time().');</script>';
			ob_flush();
			flush(); // used to send the echoed data to the client
			sleep(3); // a little break to unload the server CPU
		}

	?>

</body>
</html>