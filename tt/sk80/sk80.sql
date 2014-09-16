-- phpMyAdmin SQL Dump
-- version 2.11.1-rc1
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Mar 01, 2010, 11:05 AM
-- 伺服器版本: 5.1.42
-- PHP 版本: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 資料庫: `sk80`
--

-- --------------------------------------------------------

--
-- 資料表格式： `sk_category`
--

CREATE TABLE IF NOT EXISTS `sk_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `slug` char(30) NOT NULL DEFAULT '',
  `path` char(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- 列出以下資料庫的數據： `sk_category`
--

INSERT INTO `sk_category` (`id`, `pid`, `name`, `slug`, `path`, `count`) VALUES
(20, 0, '图书', 'books', '0,20', 0),
(17, 0, '计算机', 'computer', '0,17', 3);

-- --------------------------------------------------------

--
-- 資料表格式： `sk_content`
--

CREATE TABLE IF NOT EXISTS `sk_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL DEFAULT '',
  `slug` char(255) NOT NULL DEFAULT '',
  `keywords` char(255) NOT NULL DEFAULT '',
  `description` text,
  `type` char(5) NOT NULL DEFAULT 'post',
  `status` char(10) NOT NULL DEFAULT 'publish',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cid` smallint(3) unsigned NOT NULL DEFAULT '0',
  `created_uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `updated_uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_date` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_date` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0',
  `is_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- 列出以下資料庫的數據： `sk_content`
--

INSERT INTO `sk_content` (`id`, `title`, `slug`, `keywords`, `description`, `type`, `status`, `position`, `cid`, `created_uid`, `updated_uid`, `created_date`, `updated_date`, `comment_count`, `is_comment`, `password`) VALUES
(39, 'Flash wmode参数详解', 'flash wmode', '', '', 'post', 'publish', 0, 17, 1, 1, 1267364575, 1267368544, 0, 1, ''),
(40, 'MySQL Show命令的使用', 'mysql show', '', '', 'post', 'publish', 0, 17, 1, 1, 1267364615, 1267368529, 0, 1, ''),
(41, 'nginx rewrite 参数和例子nginx rewrite 参数和例子', '', '', '', 'post', 'publish', 0, 17, 1, 1, 1267374315, 1267374319, 0, 1, '');

-- --------------------------------------------------------

--
-- 資料表格式： `sk_content_part`
--

CREATE TABLE IF NOT EXISTS `sk_content_part` (
  `id` int(10) unsigned NOT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `sk_content_part`
--

INSERT INTO `sk_content_part` (`id`, `content`) VALUES
(39, '<p>当wmode属性设置不是window的时候，在Firefox下我们就会发现Flash里的文本输入框无法输入中文，这个问题暂无解决方案。顺带记录wmode各个参数的详细含义。</p><p>wmode属性详细说明<br /><strong>Window模式</strong><br />默认情况下的显示模式，在这种模式下flash player有自己的窗口句柄，这就意味着flash影片是存在于Windows中的一个显示实例，并且是在浏览器核心显示窗口之上的，所以flash只是貌似显示在浏览器中，但这也是flash最快最有效率的渲染模式。由于他是独立于浏览器的HTML渲染表面，这就导致默认显示方式下flash总是会遮住位置与他重合的所有DHTML层。</p><p>但是大多数苹果电脑浏览器会允许DHTML层显示在flash之上，但当flash影片播放时会出现比较诡异的现象，比如DHTML层像被 flash刮掉一块一样显示异常。</p><p><strong>Opaque模式</strong><br />这是一种无窗口模式，在这种情况下flash player没有自己的窗口句柄，这就需要浏览器需要告诉flash player在浏览器的渲染表面绘制的时间和位置。这时flash影片就不会在高于浏览器HTML渲染表面而是与其他元素一样在同一个页面上,因此你就可以使用z-index值来控制DHTML元素是遮盖flash或者被遮盖。</p><p><strong>Transparent模式</strong><br />透明模式，在这种模式下flash player会将stage的背景色alpha值将为0并且只会绘制stage上真实可见的对象，同样你也可以使用z-index来控制flash影片的深度值，但是与Opaque模式不同的是这样做会降低flash影片的回放效果，而且在9.0.115之前的flash player版本设置wmode=”opaque”或”transparent”会导致全屏模式失效。</p><p><strong>说明</strong><br />在做web开发中可能会遇到Flash遮挡页面中元素的情况，无论怎么设置Flash容器和层的深度(z-index)也无济于事，现有的解决方案是在插入flash的embed或object标签中加入”wmode”属性并设置为wmode=“transparent”或”opaque”来解决。</p>'),
(40, '<p>show tables或show tables from database_name;<br />解释：显示当前数据库中所有表的名称</p><p>show databases;<br />解释：显示mysql中所有数据库的名称</p><p>show processlist;<br />解释：显示系统中正在运行的所有进程，也就是当前正在执行的查询。大多数用户可以查看<br />他们自己的进程，但是如果他们拥有process权限，就可以查看所有人的进程，包括密码。</p><p>show table status;<br />解释：显示当前使用或者指定的database中的每个表的信息。信息包括表类型和表的最新更新时间</p><p></p><p>show columns from table_name from database_name; 或show columns from database_name.table_name;<br />解释：显示表中列名称</p><p>show grants for user_name@localhost;<br />解释：显示一个用户的权限，显示结果类似于grant 命令</p><p>show index from table_name;<br />解释：显示表的索引</p><p>show status;<br />解释：显示一些系统特定资源的信息，例如，正在运行的线程数量</p><p>show variables;<br />解释：显示系统变量的名称和值</p><p>show privileges;<br />解释：显示服务器所支持的不同权限</p><p>show create database database_name;<br />解释：显示create database 语句是否能够创建指定的数据库</p><p>show create table table_name;<br />解释：显示create database 语句是否能够创建指定的数据库</p><p>show engies;<br />解释：显示安装以后可用的存储引擎和默认引擎。</p><p>show innodb status;<br />解释：显示innoDB存储引擎的状态</p><p>show logs;<br />解释：显示BDB存储引擎的日志</p><p>show warnings;<br />解释：显示最后一个执行的语句所产生的错误、警告和通知</p><p>show errors;<br />解释：只显示最后一个执行语句所产生的错误</p>'),
(41, '<p>推荐参考地址：<br />Mailing list ARChives 官方讨论区<br /><a href="http://marc.info/?l=nginx">http://marc.info/?l=nginx</a></p><p>Nginx 常见应用技术指南[Nginx Tips]<br /><a href="http://bbs.linuxtone.org/thread-1685-1-1.html">http://bbs.linuxtone.org/thread-1685-1-1.html</a></p><p><strong>本日志内容来自互联网和平日使用经验，整理一下方便日后参考。</strong><br /></p><p><strong>正则表达式匹配，其中：</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">* ~ 为区分大小写匹配</li><li>* ~* 为不区分大小写匹配</li><li>* !~和!~*分别为区分大小写不匹配及不区分大小写不匹配</li></ol></div><p><strong>文件及目录匹配，其中：</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">* -f和!-f用来判断是否存在文件</li><li>* -d和!-d用来判断是否存在目录</li><li>* -e和!-e用来判断是否存在文件或目录</li><li>* -x和!-x用来判断文件是否可执行</li></ol></div><p><strong>flag标记有：</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">* last 相当于Apache里的[L]标记，表示完成rewrite</li><li>* break 终止匹配, 不再匹配后面的规则</li><li>* redirect 返回302临时重定向 地址栏会显示跳转后的地址</li><li>* permanent 返回301永久重定向 地址栏会显示跳转后的地址</li></ol></div><p><strong>一些可用的全局变量有，可以用做条件判断(待补全)</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">$args</li><li>$content_length</li><li>$content_type</li><li>$document_root</li><li>$document_uri</li><li>$host</li><li>$http_user_agent</li><li>$http_cookie</li><li>$limit_rate</li><li>$request_body_file</li><li>$request_method</li><li>$remote_addr</li><li>$remote_port</li><li>$remote_user</li><li>$request_filename</li><li>$request_uri</li><li>$query_string</li><li>$scheme</li><li>$server_protocol</li><li>$server_addr</li><li>$server_name</li><li>$server_port</li><li>$uri</li></ol></div><p><strong>结合QeePHP的例子</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if (!-d $request_filename) {</li><li>rewrite ^/([a-z-A-Z]+)/([a-z-A-Z]+)/?(.*)$ /index.php?namespace=user&amp;controller=$1&amp;action=$2&amp;$3 last;</li><li>rewrite ^/([a-z-A-Z]+)/?$ /index.php?namespace=user&amp;controller=$1 last;</li><li>break;</li></ol></div><p><strong>多目录转成参数</strong><br />abc.domian.com/sort/2 =&gt; abc.domian.com/index.php?act=sort&amp;name=abc&amp;id=2</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if ($host ~* (.*)\\.domain\\.com) { </li><li>set $sub_name $1;&nbsp; &nbsp; </li><li>rewrite ^/sort\\/(\\d+)\\/?$ /index.php?act=sort&amp;cid=$sub_name&amp;id=$1 last; </li><li>}</li></ol></div><p><strong>目录对换</strong><br />/123456/xxxx -&gt; /xxxx?id=123456</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/(\\d+)/(.+)/ /$2?id=$1 last;</li></ol></div><p><strong>例如下面设定nginx在用户使用ie的使用重定向到/nginx-ie目录下：</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if ($http_user_agent ~ MSIE) {</li><li>rewrite ^(.*)$ /nginx-ie/$1 break;</li><li>}</li></ol></div><p><strong>目录自动加“/”</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if (-d $request_filename){</li><li>rewrite ^/(.*)([^/])$ http://$host/$1$2/ permanent;</li><li>}</li></ol></div><p><strong>禁止htaccess</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~/\\.ht {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;deny all;</li><li>&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>禁止多个目录</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~ ^/(cron|templates)/ {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;deny all;</li><li>		 break;</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>禁止以/data开头的文件</strong><br />可以禁止/data/下多级目录下.log.txt等请求;</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~ ^/data {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;deny all;</li><li>&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>禁止单个目录</strong><br />不能禁止.log.txt能请求</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location /searchword/cron/ {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;deny all;</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>禁止单个文件</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~ /data/sql/data.sql {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;deny all;</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>给favicon.ico和robots.txt设置过期时间;</strong><br />这里为favicon.ico为99天,robots.txt为7天并不记录404错误日志</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~(favicon.ico) {</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; log_not_found off;</li><li>		 expires 99d;</li><li>		 break;</li><li>	&nbsp; &nbsp; &nbsp;}</li><li>&nbsp;</li><li>	&nbsp; &nbsp; &nbsp;location ~(robots.txt) {</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; log_not_found off;</li><li>		 expires 7d;</li><li>		 break;</li><li>&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>设定某个文件的过期时间;这里为600秒，并不记录访问日志</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ^~ /html/scripts/loadhead_1.js {</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; access_log&nbsp; &nbsp;off;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; root /opt/lampp/htdocs/web;</li><li>		 expires 600;</li><li>		 break;</li><li>&nbsp;&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>文件反盗链并设置过期时间</strong><br />这里的return 412 为自定义的http状态码，默认为403，方便找出正确的盗链的请求<br />“rewrite ^/ http://leech.c1gstudio.com/leech.gif;”显示一张防盗链图片<br />“access_log   off;”不记录访问日志，减轻压力<br />“expires 3d”所有文件3天的浏览器缓存</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~* ^.+\\.(jpg|jpeg|gif|png|swf|rar|zip|css|js)$ {</li><li>		valid_referers none blocked *.c1gstudio.com *.c1gstudio.net localhost 208.97.167.194;</li><li>if ($invalid_referer) {</li><li>		&nbsp; &nbsp; rewrite ^/ http://leech.c1gstudio.com/leech.gif;</li><li>		&nbsp; &nbsp; return 412;</li><li>&nbsp; &nbsp; break;</li><li>		}</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; access_log&nbsp; &nbsp;off;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; root /opt/lampp/htdocs/web;</li><li>		 expires 3d;</li><li> break;</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>只充许固定ip访问网站，并加上密码</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">root&nbsp; /opt/htdocs/www;</li><li>		allow&nbsp; &nbsp;208.97.167.194;</li><li>		allow&nbsp; &nbsp;222.33.1.2;</li><li>		allow&nbsp; &nbsp;231.152.49.4;</li><li>		deny&nbsp; &nbsp; all;</li><li>		auth_basic "C1G_ADMIN";</li><li>auth_basic_user_file htpasswd;</li></ol></div><p><strong>将多级目录下的文件转成一个文件，增强seo效果</strong><br />/job-123-456-789.html 指向/job/123/456/789.html</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/job-([0-9]+)-([0-9]+)-([0-9]+)\\.html$ /job/$1/$2/jobshow_$3.html last;</li></ol></div><p><strong>将根目录下某个文件夹指向2级目录</strong><br />如/<strong>shanghai</strong>job/ 指向 /area/<strong>shanghai</strong>/<br />如果你将last改成permanent，那么浏览器地址栏显是/location/shanghai/</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/([0-9a-z]+)job/(.*)$ /area/$1/$2 last;</li></ol></div><p>上面例子有个问题是访问/shanghai 时将不会匹配</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/([0-9a-z]+)job$ /area/$1/ last;</li><li>rewrite ^/([0-9a-z]+)job/(.*)$ /area/$1/$2 last;</li></ol></div><p>这样/shanghai 也可以访问了，但页面中的相对链接无法使用，<br />如./list_1.html真实地址是/area/shanghia/list_1.html会变成/list_1.html,导至无法访问。</p><p>那我加上自动跳转也是不行咯<br />(-d $request_filename)它有个条件是必需为真实目录，而我的rewrite不是的，所以没有效果</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if (-d $request_filename){</li><li>rewrite ^/(.*)([^/])$ http://$host/$1$2/ permanent;</li><li>}</li></ol></div><p>知道原因后就好办了，让我手动跳转吧</p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/([0-9a-z]+)job$ /$1job/ permanent;</li><li>rewrite ^/([0-9a-z]+)job/(.*)$ /area/$1/$2 last;</li></ol></div><p><strong>文件和目录不存在的时候重定向：</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if (!-e $request_filename) {</li><li>proxy_pass http://127.0.0.1;</li><li>}</li></ol></div><p><strong>域名跳转</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">server</li><li>&nbsp;&nbsp; &nbsp; {</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; listen&nbsp; &nbsp; &nbsp; &nbsp;80;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; server_name&nbsp; jump.c1gstudio.com;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; index index.html index.htm index.php;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; root&nbsp; /opt/lampp/htdocs/www;	</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; rewrite ^/ http://www.c1gstudio.com/;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; access_log&nbsp; off;</li><li>&nbsp;&nbsp; &nbsp; }</li></ol></div><p><strong>多域名转向</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">server_name&nbsp; www.c1gstudio.com www.c1gstudio.net;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; index index.html index.htm index.php;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; root&nbsp; /opt/lampp/htdocs;</li><li>	if ($host ~ "c1gstudio\\.net") {</li><li>rewrite ^(.*) http://www.c1gstudio.com$1 permanent;</li><li>	}</li></ol></div><p><strong>三级域名跳转</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">if ($http_host ~* "^(.*)\\.i\\.c1gstudio\\.com$") {</li><li>rewrite ^(.*) http://top.yingjiesheng.com$1;</li><li>		break;</li><li>}</li></ol></div><p><strong>域名镜向</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">server</li><li>&nbsp;&nbsp; &nbsp; {</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; listen&nbsp; &nbsp; &nbsp; &nbsp;80;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; server_name&nbsp; mirror.c1gstudio.com;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; index index.html index.htm index.php;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; root&nbsp; /opt/lampp/htdocs/www;	</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; rewrite ^/(.*) http://www.c1gstudio.com/$1 last;</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; access_log&nbsp; off;</li><li>&nbsp;&nbsp; &nbsp; }</li></ol></div><p><strong>某个子目录作镜向</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ^~ /zhaopinhui {</li><li>		&nbsp; rewrite ^.+ http://zph.c1gstudio.com/ last;</li><li>		&nbsp; break;</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>discuz ucenter home (uchome) rewrite</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^/(space|network)-(.+)\\.html$ /$1.php?rewrite=$2 last;</li><li>		rewrite ^/(space|network)\\.html$ /$1.php last;</li><li>		rewrite ^/([0-9]+)$ /space.php?uid=$1 last;</li></ol></div><p><strong>discuz 7 rewrite</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">rewrite ^(.*)/archiver/((fid|tid)-[\\w\\-]+\\.html)$ $1/archiver/index.php?$2 last;</li><li>		rewrite ^(.*)/forum-([0-9]+)-([0-9]+)\\.html$ $1/forumdisplay.php?fid=$2&amp;page=$3 last;</li><li>		rewrite ^(.*)/thread-([0-9]+)-([0-9]+)-([0-9]+)\\.html$ $1/viewthread.php?tid=$2&amp;extra=page\\%3D$4&amp;page=$3 last;</li><li>rewrite ^(.*)/profile-(username|uid)-(.+)\\.html$ $1/viewpro.php?$2=$3 last;</li><li>		rewrite ^(.*)/space-(username|uid)-(.+)\\.html$ $1/space.php?$2=$3 last;</li><li>		rewrite ^(.*)/tag-(.+)\\.html$ $1/tag.php?name=$2 last;</li></ol></div><p><strong>给discuz某版块单独配置域名</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">server_name&nbsp; bbs.c1gstudio.com news.c1gstudio.com;</li><li>&nbsp;</li><li>&nbsp; &nbsp; &nbsp;location = / {</li><li>	&nbsp; &nbsp; &nbsp; &nbsp; if ($http_host ~ news\\.c1gstudio.com$) {</li><li>		&nbsp; rewrite ^.+ http://news.c1gstudio.com/forum-831-1.html last;</li><li>		&nbsp; break;</li><li>}</li><li>	&nbsp; &nbsp; &nbsp;}</li></ol></div><p><strong>discuz ucenter 头像 rewrite 优化</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ^~ /ucenter {</li><li>		&nbsp; &nbsp; &nbsp;location ~ .*\\.php?$</li><li>		&nbsp; &nbsp; &nbsp;{</li><li>			&nbsp; #fastcgi_pass&nbsp; unix:/tmp/php-cgi.sock;</li><li>			&nbsp; fastcgi_pass&nbsp; 127.0.0.1:9000;</li><li>&nbsp; fastcgi_index index.php;</li><li>			&nbsp; include fcgi.conf;&nbsp; &nbsp; &nbsp; </li><li>&nbsp; &nbsp; &nbsp;}</li><li>&nbsp;</li><li>		&nbsp; &nbsp; &nbsp;location /ucenter/data/avatar {</li><li>log_not_found off;</li><li>			access_log&nbsp; &nbsp;off;</li><li>			location ~ /(.*)_big\\.jpg$ {</li><li>			&nbsp; &nbsp; error_page 404 /ucenter/images/noavatar_big.gif;</li><li>			}</li><li>			location ~ /(.*)_middle\\.jpg$ {</li><li>			&nbsp; &nbsp; error_page 404 /ucenter/images/noavatar_middle.gif;</li><li>			}</li><li>			location ~ /(.*)_small\\.jpg$ {</li><li>			&nbsp; &nbsp; error_page 404 /ucenter/images/noavatar_small.gif;</li><li>			}</li><li>			 expires 300;</li><li>			 break;</li><li>		&nbsp; &nbsp; &nbsp;}</li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</li></ol></div><p><strong>jspace rewrite</strong></p><div class="hl-surround"><ol class="hl-main ln-show" title="Double click to hide line number."><li class="hl-firstline">location ~ .*\\.php?$ </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;#fastcgi_pass&nbsp; unix:/tmp/php-cgi.sock; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;fastcgi_pass&nbsp; 127.0.0.1:9000; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;fastcgi_index index.php; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;include fcgi.conf;&nbsp; &nbsp; &nbsp; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; } </li><li>&nbsp; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; location ~* ^/index.php/ </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { </li><li>&nbsp;&nbsp; &nbsp;rewrite ^/index.php/(.*) /index.php?$1 break; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;fastcgi_pass&nbsp; 127.0.0.1:9000; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;fastcgi_index index.php; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;include fcgi.conf; </li><li>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; }</li></ol></div>');

-- --------------------------------------------------------

--
-- 資料表格式： `sk_content_tag`
--

CREATE TABLE IF NOT EXISTS `sk_content_tag` (
  `content_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`content_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `sk_content_tag`
--

INSERT INTO `sk_content_tag` (`content_id`, `tag_id`) VALUES
(39, 68),
(39, 69),
(40, 70),
(40, 71),
(41, 72),
(41, 73);

-- --------------------------------------------------------

--
-- 資料表格式： `sk_tag`
--

CREATE TABLE IF NOT EXISTS `sk_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `hit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- 列出以下資料庫的數據： `sk_tag`
--

INSERT INTO `sk_tag` (`id`, `name`, `count`, `hit`) VALUES
(64, '迪拜', 0, 0),
(65, '媳妇', 0, 0),
(66, '旅游', 0, 0),
(67, '感慨', 0, 0),
(68, 'FireFox', 1, 0),
(69, 'wmode', 1, 0),
(70, 'MySQL', 1, 0),
(71, '分析', 1, 0),
(72, 'nginx', 1, 0),
(73, 'rewrite', 1, 0);

-- --------------------------------------------------------

--
-- 資料表格式： `sk_user`
--

CREATE TABLE IF NOT EXISTS `sk_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(40) NOT NULL DEFAULT '',
  `password` char(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 列出以下資料庫的數據： `sk_user`
--

INSERT INTO `sk_user` (`id`, `username`, `password`) VALUES
(1, 'chaobj', '111111'),
(2, 'chaobj001', '111111'),
(3, 'chaobj002', '111111');
