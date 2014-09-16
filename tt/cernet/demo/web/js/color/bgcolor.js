/**
* 表格背景色控制脚本
*
*/
function JSBgColor()
{
	this._tbls = new Array();
	this.config = {hc:'#CCFFCC',cc:'#FFCC99',fc:'#D5D5D5',nc: '#E5E5E5'}
}

JSBgColor.prototype.append = function(tbl,skip)
{
	var item = new Array(tbl,skip||[]);
	this._tbls.push(item);
}

JSBgColor.prototype._sc = function(rw,c)
{
	rw.style.backgroundColor=c;
	var cls = rw.getElementsByTagName('td');
	for(var j=0;j<cls.length;j++)
	{
		var cl = cls[j];
		cl.style.backgroundColor=c;
	}
}

JSBgColor.prototype._skip = function(a,i,ai)
{
	for(var j=0;j<a.length;j++)
	{
		if(a[j] == (i+1) || ((a[j]+ai)==i))return true;
	}
	return false;
}

JSBgColor.prototype.create = function(ibg)
{
	var self = this;
	for(var i=0;i<this._tbls.length;i++)
	{
		var tbl = document.getElementById(this._tbls[i][0]);
		var rws = tbl.getElementsByTagName('tr');
		for(var j=0;j<rws.length;j++)
		{
			if(this._skip(this._tbls[i][1],j,rws.length))continue;
			var rw = rws[j];
			//初始化行
			if(ibg == true){
				if(!trf){
					this._sc(rw,self.config.fc);var trf=true;
				}else{
					this._sc(rw,self.config.nc);var trf=false;
				}
			}
			//注册事件
			rw.onmouseover = function(event){
				if(typeof this.ic=='undefined')this.ic = this.style.backgroundColor;
				self._sc(this,self.config.hc);
			};
			rw.onmouseout = function(event){
				if(!this.rcd) self._sc(this,this.ic);
				if(this.rcd) self._sc(this,self.config.cc);
			};
			rw.onclick = function(event){
				this.rcd = this.rcd?false:true;
			};
		}
	}
}