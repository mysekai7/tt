<?php
if(file_exists('../../../wp-config.php')){ require('../../../wp-config.php'); } else { require('wp-config.php'); }

global $table_prefix, $wpdb;

$get_id = $_SERVER['QUERY_STRING'];
$pieces = explode("|", $get_id);
$get_id = $pieces[0];
$variables = $pieces[1];

   
   $table_name = $table_prefix . "short_url";

if($get_id){
   $site_redirect = $wpdb->get_var("SELECT link_url FROM $table_name WHERE link_id='$get_id'");
   if($site_redirect){
      $wpdb->query("UPDATE $table_name SET link_count = link_count + 1 WHERE link_id='$get_id'");
      $site_redirect = str_replace("$2", $variables, $site_redirect);
      header("Location: $site_redirect");
      exit;
      }
   echo "<h2>Short URL Not Found</h2>";
   echo "<p>Sorry but the URL you entered was not found to redirect anywhere. Please check the link and try again.</p>";
   }
