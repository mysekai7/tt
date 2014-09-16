function SLink(el) {
    this.base = 'data/city';
    this.el = el;
    this.ro = null;
}
SLink.prototype = {
	//核心原理（异步请求）
    request:function(url, callback) {
        if(window.XMLHttpRequest){
            this.ro = new XMLHttpRequest();
        } else if(window.ActiveXObject){
            this.ro = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var ro = this.ro;
        this.ro.open("GET", url, true);
        this.ro.onreadystatechange= function(){
            if (ro.readyState == 4)
            {
                callback(ro);
            }
        }
        this.ro.send('');
    },
    init:function() {
        var self = this;
        for(var i=0;i<this.el.length-1;i++) {
            var el = (typeof this.el[i][0] == 'string')?document.getElementById(this.el[i][0]):this.el[i][0];
            el.onchange = function(event) {
                self.onChange(event);
            }
        }
        var url = this.base + '/0.js';
        self.request(url, function(o) {
            var data = self._parse(o.responseText);
            self._fill(self.el[0], data);
        });
    },
    onChange:function(event) {
        var self = this;
        var e  = window.event || event;
        var el = e.srcElement ? e.srcElement : e.target;
        //alert([target.value, this._next(target.id)]);
        var url = this.base + '/'+el.value+'.js';
        var ntag = this._next(el.id);
        self.request(url, function(o) {
            var data = self._parse(o.responseText);

            self._fill(ntag, data);
        });
        this._fire(ntag[0]);
    },
    _fill:function(eli, data) {
        var el = (typeof eli[0] == 'string')?document.getElementById(eli[0]):eli[0];
        el.options.length = 0;
        if(data.length<=0)
		{
			var option = document.createElement("OPTION"); 
			var j;
			option.text = '选择...';
			option.value = '';
			el.options[0] = option;
		}
		else
		{
			var option = document.createElement("OPTION"); 
			var j;
			option.text = '选择...';
			option.value = '';
			el.options[0] = option;
			for(var i=0; i<data.length; i++)
			{
				option = document.createElement("OPTION"); 
				option.text = data[i][1];
				option.value = data[i][0];
				if(typeof eli[1] != 'undefined' && eli[1]==data[i][0])
					option.selected = true;
				j = i+1;
				el.options[j] = option;
			}
		}
        this._fire(el);
    },
    _fire:function(el) {
        el = (typeof el == 'string')?document.getElementById(el):el;
        if(document.createEvent)
        {
            var evObj = document.createEvent('Events');
            evObj.initEvent( 'change', true, false );
            el.dispatchEvent(evObj);
        }
        else if( document.createEventObject )
        {
            el.fireEvent('onchange');
        }
    },
    /*
     * 根据实际需要定制解析方法
     */
    _parse:function(data) {
        if(data.indexOf('@')==-1 && data.indexOf('^')==-1)
            return [];

        var d = data.split('@');
        var nd = [];
        for(var i=0; i<d.length;i++) {
            nd[nd.length] = d[i].split('^');
        }
        return nd;
    },
    _next:function(name) {
        for(var i=0;i<this.el.length;i++) {
            if(this.el[i][0] == name && typeof this.el[i+1][0]!= 'undefined')
            return this.el[i+1];
        }
        return null;
    }
}