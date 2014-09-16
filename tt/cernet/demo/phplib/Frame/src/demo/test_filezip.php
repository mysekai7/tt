<?

//压缩操作类测试
//by indraw
//2004/11/4

require('FileZip.class.php') ;
//---------------------------------------------------------

$test = new zip_file("test.zip");
$test->set_options(array('basedir'=>"./",'overwrite'=>1,'level'=>1,'type'=>zip));
$test->add_files("FileCsv.class.php");						//要压缩的文件目录
//$test->exclude_files("d/*.swf");			//跳过的文件
//$test->store_files("d/*.txt");				//只保存，不压缩
$test->create_archive();

echo "成功压缩：test.zip";

//---------------------------------------------------------
/*
$test = new gzip_file("test.gzip");
$test->set_options(array('overwrite'=>1));
$test->extract_files();

echo "成功展开：test.gzip";

*/
//---------------------------------------------------------
?>