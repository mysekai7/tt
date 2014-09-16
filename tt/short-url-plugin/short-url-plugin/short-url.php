<?php
/*
Plugin Name: Shorturl
Plugin URI: http://www.harleyquine.com/php-scripts/short-url-plugin/
Description: Lets you add short URL's so you can keep track of outgoing links, downloads or simply mask URL's.
Version: 2.7.1
Author: Harley
Author URI: http://www.harleyquine.com
*/

/*  Copyright 2009 Lisa-Marie Welsh  (email : harley@harleyquine.com) */
function kd_rewriterules($current){
   $setting_values = get_option('short-url-plugin');
   if($setting_values[1] == 1){
      $shortlink = $setting_values[2];
      $shortlink = str_replace("$1", "([0-9]+)", $shortlink);
      $shortlink = str_replace("$2", "(.*)", $shortlink);
      $therule = "\nRewriteRule ^" . $shortlink . "$ ";
      if($setting_values[0] == "home"){ $therule .= "u.php?$1|$2\n"; } else { $therule .= "wp-content/plugins/short-url-plugin/u.php?$1|$2\n"; }
      $broken = explode("\n", $current);
         foreach ($broken as $value) {
            if(strpos($value, "RewriteBase") !== false){ $value .= $therule; }
         else { $value .= "\n"; }
         $rules .= $value;
         }
   }
   if($setting_values[1] != 1){ $rules = $current; }
   return $rules;
}

function shorturl_showlink($linkid){
$setting_values = get_option('short-url-plugin');
if($setting_values[1] == 1){
   $shortlink = $setting_values[2];
   $shortlink = str_replace("$1", $linkid, $shortlink);
   return get_bloginfo('url') . "/" . $shortlink;
   }
else {
   if($setting_values[0] == "home"){ return get_bloginfo('url') . "/u.php?" . $linkid; }
   if($setting_values[0] == "plugin"){ return get_bloginfo('url') . "/wp-content/plugins/short-url-plugin/u.php?" . $linkid; }
}
}

function kd_admin_menu_su() {
   $setting_values = get_option('short-url-plugin');
   add_filter('mod_rewrite_rules', 'kd_rewriterules');
   add_submenu_page('plugins.php', 'Configure ShortURL', 'ShortURL', 10, 'short-url', 'kd_admin_options_su');
   }
   
function kd_admin_options_su(){ 
   global $table_prefix, $wpdb, $user_ID, $wp_rewrite;
   $shorturl_per_page = 10;
   $setting_values = get_option('short-url-plugin');
   
   $table_name = $table_prefix . "short_url";
   
   if($wpdb->get_var("show tables like '$table_name'") != $table_name){
   
   $sql = "CREATE TABLE ".$table_name." (
   link_id int(11) NOT NULL auto_increment,
   link_url text NOT NULL,
   link_desc text NOT NULL,
   link_count int(11) NOT NULL default '0',
   PRIMARY KEY  (`link_id`)
   );";
   
   require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
   dbDelta($sql);
   }

   if(empty($setting_values[0])){
      $setting_values[0] = "plugin";
      $setting_values[1] = 0;
      $setting_values[0] = "u/$1";
      update_option("short-url-plugin", $setting_values);
   }

   if(isset($_POST['action'])) {
      $action = $_POST['action'];

if($action == "create"){
   $add_url = $wpdb->escape($_POST['form_url']);
   $add_desc = $wpdb->escape($_POST['form_desc']);
   if($add_url == "http://" || (!$add_url)){ $ERR = $ERR . "<br>You must enter a URL to redirect to!"; }
   if(!$ERR){
      $wpdb->query("INSERT INTO $table_name (link_url,link_desc) VALUES ('$add_url','$add_desc')");
         $new_url = get_option("siteurl") . "/u/" . mysql_insert_id();
         $MES = $MES . "<br>The redirect URL has been added. Your new Short URL is: " . $new_url;
         }
      }

if($action == "edit"){
   $edit_id = $wpdb->escape($_POST['id']);
   $edit_url = $wpdb->escape($_POST['form_url']);
   $edit_desc = $wpdb->escape($_POST['form_desc']);
   if($edit_url == "http://" || (!$edit_url)){ $ERR = $ERR . "<br>You must enter a URL to redirect to!"; }
   if(!$ERR){
      $wpdb->query("UPDATE $table_name SET link_url='$edit_url',link_desc='$edit_desc' WHERE link_id = $edit_id");
         $MES = $MES . "<br>The redirect URL has been modified.";
         }
      }

   
if($action == "delete"){
   $delete_id = $_POST['id']; 
   $wpdb->query("DELETE FROM $table_name WHERE link_id = '$delete_id'");
   $MES = $MES . "<br>Redirect deleted!";
   }  
   
if($action == "clearall"){
        $wpdb->query("UPDATE $table_name SET link_count='0' WHERE link_count > 0");
   $MES = $MES . "<br>Counts have been reset!";
   }

if($action == "updatesettings"){

$setting_values[0] = $wpdb->escape($_POST['urltouphp']);
$setting_values[1] = $wpdb->escape($_POST['enginefriendly']);
if($setting_values[1] != 1){ $setting_values[1] = 0; }
$setting_values[2] = $wpdb->escape($_POST['linkurl']);
if(empty($setting_values[2])){ $setting_values[2] = "u/$1"; }
update_option("short-url-plugin", $setting_values);

$wp_rewrite->flush_rules();
   }
}
   ?>
   <div class=wrap>
   <form method="post">
      <h2>Short URL Admin</h2>
<?php  ?>

   <?php
$blogurl = get_bloginfo('url');
if($setting_values[0] == "home"){ $MES = "According to your settings u.php is in your home directory. Click <a href='" . $blogurl . "/u.php'>here</a> to test (blank page means its working :)"; }
if($setting_values[0] == "plugin"){ $MES = "According to your settings u.php is in your plugin directory. Click <a href='" . $blogurl . "/wp-content/plugins/short-url-plugin/u.php'>here</a> to test (blank page means its working :)"; }

if($ERR){ echo "<p>" . $ERR . "</p>"; }
if($MES){ echo "<p>" . $MES . "</p>"; } ?>

      <p>Short URL allows you to create shorter URL's and keeps track of how many
times a link has been clicked. It's useful for managing downloads, keeping track
of outbound links and for masking URL's. Clicking the Clear All Clicks button
will reset the count for each entry. Visit the <a href="http://www.harleyquine.com/php-scripts/short-url-plugin/">plugin page</a> for more information about this plugin. If you're having trouble setting this plugin up or are getting a 404 error you can contact <a href="http://www.harleyquine.com/support">Harley</a> for help.</p>


<h2>Current Redirects</h2>
<table class="widefat">
   <thead>
   <tr>
   <th scope="col">Short URL (The URL to use)</th>
   <th scope="col">Real URL (Where it redirects to)</th>
   <th scope="col">Notes</th>
   <th scope="col">Amount of Clicks</th>
   <th scope="col">Manage</th>
   </tr>
      </thead>
   <tbody id="the-list">
<?php

if ( isset( $_GET['apage'] ) )
   $page = abs( (int) $_GET['apage'] );
else
   $page = 1;

$start = $offset = ( $page - 1 ) * $shorturl_per_page;


$total = $wpdb->query("SELECT * FROM $table_name");

   $rowdata = $wpdb->get_results("SELECT * FROM $table_name LIMIT $offset, $shorturl_per_page");

   $page_links = paginate_links( array(
   'base' => add_query_arg( 'apage', '%#%' ),
   'format' => '',
   'prev_text' => __('&laquo;'),
   'next_text' => __('&raquo;'),
   'total' => ceil($total / $shorturl_per_page),
   'current' => $page
));

if ( $page_links ) : ?>
<div class="tablenav">
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
   number_format_i18n( $start + 1 ),
   number_format_i18n( min( $page * $shorturl_per_page, $total ) ),
   number_format_i18n( $total ),
   $page_links
); echo $page_links_text; ?></div></div>
<?php endif;

   foreach ($rowdata as $row) {
   $is_editing = $_POST['edit_id'];
   if($is_editing){
      if($is_editing == $row->link_id){ $EDIT = 1; $EDIT_ID = $row->link_id; $EDIT_URL = $row->link_url; $EDIT_DESC = $row->link_desc; }
      }
?>
   <tr class='<?php echo $class; ?>'>

   <th scope="row"><a href="<? echo shorturl_showlink($row->link_id); ?>" target="_blank"><? echo shorturl_showlink($row->link_id); ?></a></th>
   <td><? echo $row->link_url; ?></td>
   <td><? echo $row->link_desc; ?></td>
   <td><? echo $row->link_count; ?></td>
   <td><form method="post" name="delete"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<? echo $row->link_id; ?>"><input type="submit" value="Delete"></form><form method="post" name="edit"><input type="hidden" name="edit_id" value="<? echo $row->link_id; ?>"><input type="submit" value="Edit"></form></td>

</tr>

<? } ?>
   </tbody>
</table>
<h2><? if($EDIT == 1){ echo "Edit"; } else { echo "Create"; } ?> Redirect</h2>
<form method="post" name="createform">
<? if($EDIT == 1){ ?>
<p><span class="required">URL Destination</span><br><input type="text" name="form_url" value="<?php echo $EDIT_URL; ?>" maxlength="255" size="25"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?php echo $EDIT_ID; ?>"><input type="text" name="form_desc" value="<?php echo $EDIT_DESC; ?>" maxlength="255" size="25"><input type="submit" name="submit" value="Edit"></p>
<? } else { ?>
    <p><span class="required">URL Destination</span><br><input type="text" name="form_url" value="http://" maxlength="255" size="25"><input type="hidden" name="action" value="create"><input type="text" name="form_desc" value="Notes" maxlength="255" size="25"><input type="submit" name="submit" value="Create"></p>
<? } ?>
</form>
<h2 class="copyright">Clear All Clicks</h2>
<form method="post" name="clear_all_clicks">
    <p><input type="hidden" name="action" value="clearall"><input type="submit" name="submit" value="Clear All Clicks"></p>
</form>

<h2 class="copyright">Settings</h2>
<form method="post" name="shorturlpluginsettings">
    <p><input type="hidden" name="action" value="updatesettings">
    <table class="form-table">

   <tr valign="top"><th scope="row">Where is u.php?</th>
   <td><select name="urltouphp" size="1">
   <option <?php if($setting_values[0]=="home") echo "selected "; ?>value="home">Home Directory</option>
   <option <?php if($setting_values[0]=="plugin") echo "selected "; ?>value="plugin">Plugin Directory</option>
   </select>
<br />You can either put the u.php in the same folder as this plugin (i.e <?php echo get_bloginfo('url') . "/wp-content/plugins/short-url-plugin/u.php"; ?>) or in the home directory (i.e <?php echo get_bloginfo('url') . "/u.php"; ?>) of your blog. The home directory is recommended.</td>
   </tr>
   <tr valign="top"><th scope="row">Use htaccess to make search engine friendly links?</th>
   <td><input name="enginefriendly" type="checkbox" id="enginefriendly" value="1" <?php if($setting_values[1]=="1") echo "checked=\"checked \""; ?>/><br />If you'd like your links in the form http://www.yoursite.com/u/24 instead of http://www.yoursite.com/u.php?24 then tick this option.</td>
   </tr>
   <tr valign="top"><th scope="row">Link URL</th>
   <td><input type="text" name="linkurl" value="<?php echo $setting_values[2]; ?>"><br />Please enter the form of link you'd like in your htaccess. Default is /u/$1. You could change this to something like /link/$1 or /urltracker/$1. $1 is the ShortURL number. To pass variables to your links use $2 in this link and also in the links you create. An example can be found on the <a href="http://www.harleyquine.com/php-scripts/short-url-plugin/">plugin page</a>.</td>
   </tr>
   </table>

    <input type="submit" name="submit" value="Update Settings"></p>
</form>

   </div>
   <div class="wrap">
   <p><b>Usage:</b></p>
   <p>Set your options and check that they're right and off you go. The plugin should automatically update your permalink/htaccess structure if you chose the search engine friendly links. For advanced usage including how to pass variables to your redirects check out the <a href="http://www.harleyquine.com/php-scripts/short-url-plugin/">plugin page</a>.</p>
<?php
}
add_action('admin_menu', 'kd_admin_menu_su');


