<?php
function getRootPath() {
	$sRealPath = realpath('./');

	$sSelfPath = $_SERVER['PHP_SELF'];
	$sSelfPath = substr( $sSelfPath, 0, strrpos( $sSelfPath, '/' ));

	return substr($sRealPath, 0, strlen($sRealPath) - strlen($sSelfPath));
}
?>