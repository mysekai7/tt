<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


//files
$path = './sitemap/';
if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //echo "$file<br />";
            $k = (int)$file;
            $files[$k] = $file;
            ksort($files);
        }
    }
    closedir($handle);
}

$xml_header = <<< END
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n
END;

$xml_footer = '</sitemapindex>';

$xml_body = '';

if(count($files) > 0) {
    foreach($files as $k => $v) {
        $xml_body .= "<sitemap>\n";
        $xml_body .= "\t<loc>http://c.tootoo.com/sitemap/{$v}</loc>\n";
        $xml_body .= "\t<lastmod>2010-01-14</lastmod>\n";
        $xml_body .= "</sitemap>\n";
    }
}

$sitemap_xml = $xml_header.$xml_body.$xml_footer;

file_put_contents('./sitemap/sitemap.xml', $sitemap_xml);

?>
