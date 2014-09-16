<%@ Page Language="C#" AutoEventWireup="true" %>
<%@ Import namespace="System" %>
<%@ Import namespace="System.Collections" %>
<%@ Import namespace="System.Configuration" %>
<%@ Import namespace="System.Data" %>
<%@ Import namespace="System.Web" %>
<%@ Import namespace="System.Web.Security" %>
<%@ Import namespace="System.Web.UI" %>
<%@ Import namespace="System.Web.UI.HtmlControls" %>
<%@ Import namespace="System.Web.UI.WebControls" %>
<%@ Import namespace="System.Web.UI.WebControls.WebParts" %>

<script runat="server">
/*
 * upload demo for c# .net 2.0
 * 
 * @requires xhEditor
 * @author Jediwolf<jediwolf@gmail.com>
 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
 * 
 * @Version: 0.1.2 build 100225
 * 
 * 注：本程序仅为演示用，请您根据自己需求进行相应修改，或者重开发。
 * 
 */

protected void Page_Load(object sender, EventArgs e)
{
    Response.Charset = "UTF-8";
    Response.Write(upLoadFile("filedata"));
}

string upLoadFile(string inputname) 
{
    string immediate;
    string attachdir;
    int dirtype;
    int maxattachsize;
    string upext;
	int msgtype;

    immediate = Request.QueryString["immediate"];
    attachdir = "upload";     // 上传文件保存路径，结尾不要带/
    dirtype = 1;              // 1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
    maxattachsize = 2097152;  // 最大上传大小，默认是2M
    upext = "txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid"; // 上传扩展名
	msgtype = 2;		//返回上传参数的格式：1，只返回url，2，返回参数数组

    string err, msg, upfile;
    err = "";
    msg = "''";

    HttpFileCollection filecollection = Request.Files;

    // 只接收指定文件域的上传，如果需要同时接收多个文件，请通过循环方式接收
    HttpPostedFile postedfile = filecollection.Get(inputname);

    if (postedfile == null)
    {
        err = "无数据提交";
    }
    else
    {
        if (postedfile.ContentLength > maxattachsize)
        {
            err = "文件大小超过" + maxattachsize + "字节";
        }
        else
        {
            string attach_dir, attach_subdir, filename, extension, target, tmpfile;

            // 取上载文件后缀名
            extension = GetFileExt(postedfile.FileName);

            if (("," + upext + ",").IndexOf("," + extension + ",") < 0)
            {
                err = "上传文件扩展名必需为：" + upext;
            }
            else
            {
                switch (dirtype)
                {
                    case 2:
                        attach_subdir = "month_" + DateTime.Now.ToString("yyMM");
                        break;
                    case 3:
                        attach_subdir = "ext_" + extension;
                        break;
                    default:
                        attach_subdir = "day_" + DateTime.Now.ToString("yyMMdd");
                        break;
                }
                attach_dir = attachdir + "/" + attach_subdir + "/";

                // 生成随机文件名
                Random random = new Random(DateTime.Now.Millisecond);
                filename = DateTime.Now.ToString("yyyyMMddhhmmss") + random.Next(10000) + "." + extension;

                target = attach_dir + filename;
                try
                {
                    CreateFolder(Server.MapPath(attach_dir));
                    postedfile.SaveAs(Server.MapPath(target));
                    
                }
                catch (Exception ex)
                {
                    err = ex.Message.ToString();
                }

                // 立即模式判断
                if (immediate == "1") target = "!" + target;
				target=jsonString(target);
				if(msgtype==1)msg = "'"+target+"'";
				else msg = "{url:'"+target+"',localname:'"+postedfile.FileName+"',id:'1'}";
            }
        }
        
    }

    postedfile = null;
    filecollection = null;

    return "{err:'" + jsonString(err) + "',msg:" + msg + "}";
}


string jsonString(string str) 
{
    str = str.Replace("\\", "\\\\");
    str = str.Replace("/", "\\/");
    str = str.Replace("'", "\\'");
    return str;
}


string GetFileExt(string FullPath) 
{
    if (FullPath != "")
    {
        return FullPath.Substring(FullPath.LastIndexOf('.') + 1).ToLower();
    }
    else
    {
        return "";
    }
}

void CreateFolder(string FolderPath)
{
    if (!System.IO.Directory.Exists(FolderPath))
    {
        System.IO.Directory.CreateDirectory(FolderPath);
    }
}
</script>