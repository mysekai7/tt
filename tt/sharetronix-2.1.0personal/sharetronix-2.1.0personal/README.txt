Welcome to Sharetronix Opensource
  -------------------------
  Sharetronix Opensource is a multimedia microblogging platform. It helps
  people in a community, company, or group to exchange short messages over
  the Web. Find more information in http://sharetronix.com/sharetronix
  -------------------------

License
  -------------------------
  Please check out the license.txt file. By installing Sharetronix, you
  agree to all the conditions of the license and also to the Sharetronix
  Terms of Use: http://sharetronix.com/sharetronix/license
  -------------------------

INSTALLATION
  -------------------------
  To install Sharetronix Opensource on your webserver, upload the contents
  of the "upload/" folder to the preferred location on your webserver
  (wherever you want to install Sharetronix) with your favorite FTP client.
  Open with your browser the "install" location in this folder and follow
  the steps in the installation wizard.
  -------------------------

UPGRADE
  -------------------------
  To upgrade Sharetronix Opensource from a previous version, just follow
  the Installation steps. Replace the old installation files with the
  contents of the "upload/" folder and run the installation wizard. But
  first - don't forget to backup your old installation (database and files)
  - it's important!
  -------------------------

System Requirements
  -------------------------
  - Apache Web Server
  - MySQL version 5.0 or higher
  - PHP version 5.2 or higher
  -------------------------

Official website
  -------------------------
  http://sharetronix.com
  http://blogtronixmicro.com
  http://blogtronix.com
  -------------------------

FACEBOOK CONNECT
  -------------------------
  To activate Facebook Connect integration for your Sharetronix site, first
  you have to register a Facebook application and get its API key:
  1. Complete the Sharetronix installation/upgrade script
  2. Go to FB and join the Developers group: http://developers.facebook.com/
  3. Create new application: http://facebook.com/developers/createapp.php 
  4. Go to the application and click "Edit Settings"
  5. From the "Web Site" tab fill the fields "Site URL" and "Site Domain"
  6. Place the API Key in ./system/conf_main.php in $C->FACEBOOK_API_KEY
  -------------------------
  
TWITTER CONNECT
  -------------------------
  To activate Twitter OAuth Login for your Sharetronix site, first you have
  to register a Twitter application and get its Consumer KEY and SECRET:
  1. Complete the Sharetronix installation/upgrade script
  2. Go to the Twitter New Application form: http://twitter.com/apps/new
  3. For "Application Type" choose "Browser"
  4. For "Callback URL" enter http://your-sharetronix-url/twitter-connect
  5. For "Default Access type" choose "Read & Write"
  6. Select che "Use Twitter for login" checkbox
  7. Submit the form, get the "Consumer key" and "Consumer secret" and then
     place them in ./system/conf_main.php - in $C->TWITTER_CONSUMER_KEY and
     $C->TWITTER_CONSUMER_SECRET
  -------------------------
  
YAHOO: Inviting contacts from Yahoo
  -------------------------
  To activate the Yahoo page in the Invitation center, first you have to 
  register a Yahoo application and get its Consumey KEY and SECRET:
  1. Complete the Sharetronix installation/upgrade script
  2. Go to the Yahoo New App form: https://developer.apps.yahoo.com/projects
  3. For Type of applcation choose "Create apps that use Yahoo! OAuth APIs"
  4. On the next step for "Kind of Application" choose "Web-based"
  5. For "Application Domain" fill your sharetronix site url
  6. For "Access Scopes" choose "This app requires access to private user data"
  7. From the menus choose "Read Full" for the "Yahoo! Contacts" section
  8. Submit the form, get the "Consumer key" and "Consumer secret" and then
     place them in ./system/conf_main.php - in $C->YAHOO_CONSUMER_KEY and
     $C->YAHOO_CONSUMER_SECRET
  -------------------------

Twitter Feed Import

  -------------------------
  To enable the Twitter Feed Import feature you need to go to Settings -> RSS fees and in the text field Feed Url enter the rss feed address from your 
  profile in Twitter (something like http://twitter.com/user_timeline/1231331.rss  or http://twitter.com/friends_timeline/123123231.rss  The system 
  will automatically recognize that this Twitter RSS and all the data coming from it will display in the Twitter tab in Sharetronix.
  
  If you are upgrading your Sharetronix version most probably your users have already included their RSS feeds and Twitter feeds, however the Twitter 
  Feed Import feature in Sharetronix works only with new feeds (feeds that will come after your upgrade). If you want to include older feeds you need 
  to write a dB query to find all feeds that were coming from Twitter before the upgrade and display them in the Twitter filter. Run the following 
  query in your dB:
  
  'UPDATE users_rssfeeds SET is_twitter="1" WHERE feed_url LIKE "http://twitter.com/%"'
  
Mail send problems
  --------------------------
  
  If you have problems with the emails distribution and one of these two problems:
  - send emails with empty body
  - send emails with incomplete text in the body
 
  You can resolve this issue by changing a few items in the file ./system/helpers/func_main.php
  Go to line 126 and delete all lines that contain the phrase (DELETE_THIS_LINE):
  
  ----------------------------------------------
  /*  (DELETE_THIS_LINE) This is a fix for everybody with mail issues (2 types)
      (DELETE_THIS_LINE) 1. Your script send mails with blank body
      (DELETE_THIS_LINE) 2. Your script send mails with missing text in the mail body
               
      (DELETE_THIS_LINE) To activate the fix delete all the lines which contains (DELETE_THIS_LINE).
 
	do_send_mail($email, $subject, $message_txt, $from);
	return;
 
   (DELETE_THIS_LINE)*/
  ----------------------------------------------        
  
  Once you have deleted the lines you should have only the following code left:
 
  ----------------------------------------------
   do_send_mail($email, $subject, $message_txt, $from);
   return;
  ----------------------------------------------
 
  Note: please backup the file ./system/helpers/func_main.php before applying any changes


  -------------------------

Using Google reCaptcha

  -------------------------
  
  To activate the google reCaptcha on your sharetronix community follow the steps below:
  1. Go to https://www.google.com/recaptcha/admin/create 
  2. Create your private and public keys.
  3. Open the ./conf_main.php file
  4. Enter the value for the private key at $C->GOOGLE_CAPTCHA_PRIVATE_KEY 
  5. Enter the value for the public key at $C->GOOGLE_CAPTCHA_PUBLIC_KEY
  
  -------------------------	
  
Send Post Directly to Twitter

  -------------------------
  To give the ability for your users to Send Post Directly to Twitter from your community follow these steps:
  1. If you have a twitter application and it's TWITTER_CONSUMER_SECRET and TWITTER_CONSUMER_KEY are set up in the conf_main.php file
  (as described in the TWITTER CONNECT section in this README.TXT file) then your users are ready to integrate thir twitter profiles in
  your community. Send Direct Post to Twitter and TWITTER CONNECT could use same TWITTER_CONSUMER_SECRET and TWITTER_CONSUMER_KEY.
  
  2. If you do not have TWITTER CONNECT integration, create a twitter application as described for the TWITTER CONNECT integration in this README.TXT file 
  and you are ready. Send Direct Post to Twitter and TWITTER CONNECT could use same TWITTER_CONSUMER_SECRET and TWITTER_CONSUMER_KEY.
  
  -------------------------	
  
Send Post Directly to Facebook

  -------------------------
  To give the ability for your users to Send Post Directly to Facebook from your community follow these steps:
  1. If you already have a facebook application for your community, then in your conf_main.php file you will have the APP KEY of your application.
  2. Go to http://developers.facebook.com/ and copy and paste your application's KEY and SECRET to $C->FACEBOOK_API_ID and $C->FACEBOOK_API_SECRET in
  your conf_main.php file. 
  3. You are ready, facebook connect and send post to facebook options could use same facebook application
  4. If you do not have a facebook application, then follow the steps for FACEBOOK CONNECT in this README.TXT file and after that paste your application's 
  KEY and SECRET in conf_main.php.
  
  -------------------------
  
One click install
  
  -------------------------
  Softaculous 	- http://www.softaculous.com/softwares/microblogs/Sharetronix
  AMPPS		- http://www.ampps.com/apps/php/microblogs/Sharetronix