<?php
/**
 * 检查是否是一个序列化数据.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}

/**
 * 检查是否是一个字符串序列化数据
 *
 * @since 2.0.5
 *
 * @param mixed $data Serialized data
 * @return bool False if not a serialized string, true if it is.
 */
function is_serialized_string( $data ) {
	// if it isn't a string, it isn't a serialized string
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( preg_match( '/^s:[0-9]+:.*;$/s', $data ) ) // this should fetch all serialized strings
		return true;
	return false;
}

/**
 * http状态转换成说明
 *
 * @since 2.3.0
 *
 * @param int $code HTTP status code.
 * @return string Empty string if not found, or description if found.
 */
function get_status_header_desc( $code ) {
	global $wp_header_to_desc;

	$code = absint( $code );

	if ( !isset( $wp_header_to_desc ) ) {
		$wp_header_to_desc = array(
			100 => 'Continue',
			101 => 'Switching Protocols',

			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',

			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',

			400 => 'Bad Request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',

			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);
	}

	if ( isset( $wp_header_to_desc[$code] ) )
		return $wp_header_to_desc[$code];
	else
		return '';
}

/**
 * 发送一个让页面不缓存的http头信息
 *
 * Different browsers support different nocache headers, so several headers must
 * be sent so that all of them get the point that no caching should occur.
 *
 * @since 2.0.0
 */
function nocache_headers() {
	// why are these @-silenced when other header calls aren't?
	@header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
	@header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	@header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	@header( 'Pragma: no-cache' );
}

/**
 * 让javascript缓存10天的http头信息
 *
 * @since 2.1.0
 */
function cache_javascript_headers() {
	$expiresOffset = 864000; // 10 days
	header( "Content-Type: text/javascript; charset=" . get_bloginfo( 'charset' ) );
	header( "Vary: Accept-Encoding" ); // Handle proxies
	header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $expiresOffset ) . " GMT" );
}

/**
 * 递归创建一个完整的目录(整个目录中的子目录不存在都创建)
 *
 * Will attempt to set permissions on folders.
 *
 * @since 2.0.1
 *
 * @param string $target Full path to attempt to create.
 * @return bool Whether the path was created or not. True if path already exists.
 */
function wp_mkdir_p( $target ) {
	// from php.net/mkdir user contributed notes
	$target = str_replace( '//', '/', $target );
	if ( file_exists( $target ) )
		return @is_dir( $target );

	// Attempting to create the directory may clutter up our display.
	if ( @mkdir( $target ) ) {
		$stat = @stat( dirname( $target ) );
		$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
		@chmod( $target, $dir_perms );
		return true;
	} elseif ( is_dir( dirname( $target ) ) ) {
			return false;
	}

	// If the above failed, attempt to create the parent node, then try again.
	if ( ( $target != '/' ) && ( wp_mkdir_p( dirname( $target ) ) ) )
		return wp_mkdir_p( $target );

	return false;
}

/**
 * 检查是否是个绝对路径 ('/foo/bar', 'c:\windows').
 *
 * @since 2.5.0
 *
 * @param string $path File path
 * @return bool True if path is absolute, false is not absolute.
 */
function path_is_absolute( $path ) {
	// this is definitive if true but fails if $path does not exist or contains a symbolic link
	if ( realpath($path) == $path )
		return true;

	if ( strlen($path) == 0 || $path{0} == '.' )
		return false;

	// windows allows absolute paths like this
	if ( preg_match('#^[a-zA-Z]:\\\\#', $path) )
		return true;

	// a path starting with / or \ is absolute; anything else is relative
	return (bool) preg_match('#^[/\\\\]#', $path);
}

/**
 * 根据扩展名获得文件类型
 *
 * @package WordPress
 * @since 2.5.0
 * @uses apply_filters() Calls 'ext2type' hook on default supported types.
 *
 * @param string $ext The extension to search.
 * @return string|null The file type, example: audio, video, document, spreadsheet, etc. Null if not found.
 */
function wp_ext2type( $ext ) {
	$ext2type = apply_filters('ext2type', array(
		'audio' => array('aac','ac3','aif','aiff','mp1','mp2','mp3','m3a','m4a','m4b','ogg','ram','wav','wma'),
		'video' => array('asf','avi','divx','dv','mov','mpg','mpeg','mp4','mpv','ogm','qt','rm','vob','wmv'),
		'document' => array('doc','docx','pages','odt','rtf','pdf'),
		'spreadsheet' => array('xls','xlsx','numbers','ods'),
		'interactive' => array('ppt','pptx','key','odp','swf'),
		'text' => array('txt'),
		'archive' => array('tar','bz2','gz','cab','dmg','rar','sea','sit','sqx','zip'),
		'code' => array('css','html','php','js'),
	));
	foreach ( $ext2type as $type => $exts )
		if ( in_array($ext, $exts) )
			return $type;
}

/**
 * 转换成正整数
 *
 * @since 2.5.0
 *
 * @param mixed $maybeint Data you wish to have convered to an absolute integer
 * @return int An absolute integer
 */
function absint( $maybeint ) {
	return abs( intval( $maybeint ) );
}
?>