#######################################################################
# 
# xhEditor 自述文件
#
#######################################################################

使用方法

1. 下载xhEditor最新版本。
   下载地址：http://code.google.com/p/xheditor/downloads/list

2. 解压zip文件，将其中的xheditor.js以及xheditor_emot、xheditor_plugins和xheditor_skin三个文件夹上传到网站相应目录

3. 在相应html文件的</head>之前添加
<script type="text/javascript" src="http://static.xxx.com/js/jquery.js"></script>
<script type="text/javascript" src="http://static.xxx.com/js/xheditor.js"></script>

4. 
方法1：在textarea上添加属性： class="xheditor"(设置为xheditor-mini和xheditor-simple，分别默认加载迷你和简单工具栏)
方法2：在textarea上添加属性： xheditor="{skin:'default'}"，此方法支持除插件之外的所有初始化参数方法
方法3：在您的页面初始JS代码里加上： $('#elm1').xheditor(true);
$('#elm1').xheditor(true)；
例如：
$({
$('#elm1').xheditor(true)；
});
相应的隐藏编辑器的代码为
$('#elm1').xheditor(false)；


更多帮助信息，请查看在线帮助：http://code.google.com/p/xheditor/wiki/Help
或者参考demos文件夹中的演示页面