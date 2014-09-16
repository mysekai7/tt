/**
 * JET (Javascript Extension Tools)
 * Copyright (c) 2009, KDV.cn, All rights reserved.
 * Code licensed under the BSD License:
 * http://developer.kdv.cn/jet/license.txt
 *
 * @fileOverview Jet!
 * @version	1.0
 * @author	Kinvix(<a href="mailto:Kinvix@gmail.com">Kinvix@gmail.com</a>)
 * @description
 *
 */


/**
 * @description
 * Package: jet.ui
 *
 * Need package:
 * jet.core.js
 *
 */


/**
 * ui模块包
 */
Jet().$package(function(J){
	/**
	 * ui 名字空间
	 *
	 * @namespace
	 * @name ui
	 */
	J.ui = J.ui || {};
});


/**
 * 拖拽模块
 */
Jet().$package(function (J) {
    var $D = J.dom,
		$E = J.event;

    var ieSelectFix = function (e) {
        e.preventDefault();
        //return false;
    };

	var _clientWidth=false,
		_clientHeight=false,
		_width=false,
		_height=false;
    /**
	 * 拖拽类
	 *
	 * @memberOf ui
	 * @Class
	 *
	 * @param {Element} apperceiveEl 监听拖拽动作的元素
	 * @param {Element} effectEl 展现拖拽结果的元素
	 * @param {Object} option 其他选项，如:isLimited,leftMargin...
	 * @returns
	 *
	 *
	 */
    J.ui.Drag = new J.Class({
        init: function (apperceiveEl, effectEl, option) {
            var context = this;
            var curDragElementX, curDragElementY, dragStartX, dragStartY;
            this.apperceiveEl = apperceiveEl;
            option = option || {};
            this.isLimited = option.isLimited || false;
            if (this.isLimited) {
                this._leftMargin = option.leftMargin || 0;
                this._topMargin = option.topMargin || 0;
                this._rightMargin = option.rightMargin || 0;
                this._bottomMargin = option.bottomMargin || 0;
            }


            if (effectEl === false) {
                this.effectEl = false;
            } else {
                this.effectEl = effectEl || apperceiveEl;
            }




            this.dragStart = function (e) {
                e.preventDefault();
                e.stopPropagation();

				//缓存高宽
				_clientWidth = $D.getClientWidth();
				_clientHeight = $D.getClientHeight();
				_width = parseInt($D.getStyle(effectEl, "width"));
				_height = parseInt($D.getStyle(effectEl, "height"));

				if(J.browser.ie)
				{
					curDragElementX = parseInt($D.getStyle(context.apperceiveEl, "left")+10) || 0;
					curDragElementY = parseInt($D.getStyle(context.apperceiveEl, "top")+10) || 0;
				}
				else{
					curDragElementX = parseInt($D.getStyle(context.effectEl, "left")) || 0;
					curDragElementY = parseInt($D.getStyle(context.effectEl, "top")) || 0;
				}
                dragStartX = e.pageX;
                dragStartY = e.pageY;
                $E.on(document, "mousemove", context.dragMove);
                $E.on(document, "mouseup", context.dragStop);
                if (J.browser.ie) {
                    $E.on(document.body, "selectstart", ieSelectFix);
                }

                $E.notifyObservers(context, "start", { x: curDragElementX, y: curDragElementY });
            };

            this.dragMove = function (e) {
                var x = curDragElementX + (e.pageX - dragStartX);
                var y = curDragElementY + (e.pageY - dragStartY);
                var isMoved = false;


                if (context.isLimited) {
                    var tempX = _clientWidth - _width - context._rightMargin;
                    if (x > tempX) {
                        x = tempX;
                    }
                    tempX = context._leftMargin;
                    if (x < tempX) {
                        x = tempX;
                    }

                }
                if (context._oldX !== x) {
                    context._oldX = x;
                    if (context.effectEl) {
                        context.effectEl.style.left = x + "px";
                    }
                    isMoved = true;
                }

                //J.out("context._topMargin: "+context._topMargin);
                if (context.isLimited) {
                    var tempY = _clientHeight - _height - context._bottomMargin;
                    if (y > tempY) {
                        y = tempY;
                    }
                    tempY = context._topMargin;
                    if (y < tempY) {
                        y = tempY;
                    }

                }

                if (context._oldY !== y) {
                    context._oldY = y;
                    if (context.effectEl) {
                        context.effectEl.style.top = y + "px";
                    }
                    isMoved = true;
                }


                if (isMoved) {
                    $E.notifyObservers(context, "move", { x: x, y: y });
                }

            };

            this.dragStop = function (e) {
                $E.notifyObservers(context, "end", { x: context._oldX, y: context._oldY });
				_clientWidth = false;
				_clientHeight = false;
				_width = false;
				_height = false;
                $E.off(document, "mousemove", context.dragMove);
                $E.off(document, "mouseup", context.dragStop);
                if (J.browser.ie) {
                    $E.off(document.body, "selectstart", ieSelectFix);
                }
                J.out("end")
            };

            $E.on(this.apperceiveEl, "mousedown", this.dragStart);
        },
        lock: function () {
            $E.off(this.apperceiveEl, "mousedown", this.dragStart);
        },
        unlock: function () {
            $E.on(this.apperceiveEl, "mousedown", this.dragStart);
        },
        show: function () {
            $D.show(this.apperceiveEl);
        },
        hide: function () {
            $D.hide(this.apperceiveEl);
        }
    });



});






/**
 * Resize 模块
 */
Jet().$package(function (J) {
    J.ui = J.ui || {};
    var $D = J.dom,
		$E = J.event;

    var id = 0;
    var handleNames = {
        t: "t",
        r: "r",
        b: "b",
        l: "l",
        rt: "rt",
        rb: "rb",
        lb: "lb",
        lt: "lt"
    };


    /**
    * resize类
    *
    * @memberOf ui
    * @Class
    *
    * @param {Element} apperceiveEl 监听resize动作的元素
    * @param {Element} effectEl 展现resize结果的元素
    * @param {Object} option 其他选项，如:dragProxy,size,minWidth...
    * @returns
    *
    *
    */
    J.ui.Resize = new J.Class({
        init: function (apperceiveEl, effectEl, option) {
            var context = this;
            option = option || {};

            this.apperceiveEl = apperceiveEl;
            if (effectEl === false) {
                this.effectEl = false;
            } else {
                this.effectEl = effectEl || apperceiveEl;
            }

            this.size = option.size || 5;
            this.minWidth = option.minWidth || 0;
            this.minHeight = option.minHeight || 0;
            this._dragProxy = option.dragProxy;

            this._left = this.getLeft();
            this._top = this.getTop();
            this._width = this.getWidth();
            this._height = this.getHeight();

            this.id = this.getId();

            var styles = {
                t: "cursor:n-resize; z-index:1; left:0; top:-5px; width:100%; height:5px;",
                r: "cursor:e-resize; z-index:1; right:-5px; top:0; width:5px; height:100%;",
                b: "cursor:s-resize; z-index:1; left:0; bottom:-5px; width:100%; height:5px;",
                l: "cursor:w-resize; z-index:1; left:-5px; top:0; width:5px; height:100%;",
                rt: "cursor:ne-resize; z-index:2; right:-5px; top:-5px; width:5px; height:5px;",
                rb: "cursor:se-resize; z-index:2; right:-5px; bottom:-5px; width:5px; height:5px;",
                lt: "cursor:nw-resize; z-index:2; left:-5px; top:-5px; width:5px; height:5px;",
                lb: "cursor:sw-resize; z-index:2; left:-5px; bottom:-5px; width:5px; height:5px;"
            };

            this._onMousedown = function () {
                $E.notifyObservers(context, "mousedown", { width: context._width, height: context._height });
            };
            this._onDragEnd = function () {
                J.out("this._width： " + context._width);
			    J.out("this._height： " + context._height);
                $E.notifyObservers(context, "end", {
                    x: context.getLeft(),
                    y: context.getTop(),
                    width: context.getWidth(),
                    height: context.getHeight()
                });
            };

            for (var p in handleNames) {
                var tempEl = $D.node("div", {
                    "id": "window_" + this.id + "_resize_" + handleNames[p]
                });

                this.apperceiveEl.appendChild(tempEl);
                $D.setCssText(tempEl, "position:absolute; overflow:hidden; background:url(" + J.path + "assets/images/transparent.gif);" + styles[p]);
                if (this._dragProxy) {
                    //$E.on(tempEl, "mousedown", this._onMousedown);
                } else {

                }

                this["_dragController_" + handleNames[p]] = new J.ui.Drag(tempEl, false);

            }



            // 左侧
            this._onDragLeftStart = function (xy) {
                $E.notifyObservers(context, "mousedown", { width: context._width, height: context._height });
                context._startLeft = context._left = context.getLeft();
                context._startWidth = context._width = context.getWidth();
            };
            this._onDragLeft = function (xy) {
                var w = context._startWidth - xy.x;
                var x = context._startLeft + xy.x;
                if (w < context.minWidth) {
                    w = context.minWidth;
                    x = context._startLeft + (context._startWidth - w);
                }
                context.setLeft(x);
                context.setWidth(w);
                $E.notifyObservers(context, "resize", { width: context._width, height: context._height });

            };

            // 上侧
            this._onDragTopStart = function (xy) {
                $E.notifyObservers(context, "mousedown", { width: context._width, height: context._height });
                context._startTop = context._top = context.getTop();
                context._startHeight = context._height = context.getHeight();
            };
            this._onDragTop = function (xy) {
                var h = context._startHeight - xy.y;
                var y = context._startTop + xy.y;
                if (h < context.minHeight) {
                    h = context.minHeight;
                    y = context._startTop + (context._startHeight - h);
                }
                context.setTop(y);
                context.setHeight(h);
                $E.notifyObservers(context, "resize", { width: context._width, height: context._height });
            };



            // 右侧
            this._onDragRightStart = function (xy) {
                 $E.notifyObservers(context, "mousedown", { width: context._width, height: context._height });
				context._startWidth = context._width = context.getWidth();
                 //context._startWidth = ;
                //context._startWidth = context._width = context.startSize.width; // context.startWidth;
            };
            this._onDragRight = function (xy) {
                var w = context._startWidth + xy.x;
                if (w < context.minWidth) {
                    w = context.minWidth;
                }
                context.setWidth(w);
                $E.notifyObservers(context, "resize", { width: context._width, height: context._height });
            };


            // 下侧
            this._onDragBottomStart = function (xy) {
                $E.notifyObservers(context, "mousedown", { width: context._width, height: context._height });
                context._startHeight = context._height = context.getHeight();
            };
            this._onDragBottom = function (xy) {
                var h = context._startHeight + xy.y;
                if (h < context.minHeight) {
                    h = context.minHeight;
                }
                context.setHeight(h);
                $E.notifyObservers(context, "resize", { width: context._width, height: context._height });
            };

            // 左上
            this._onDragLeftTopStart = function (xy) {
                context._onDragLeftStart(xy);
                context._onDragTopStart(xy);
            };
            this._onDragLeftTop = function (xy) {
                context._onDragLeft(xy);
                context._onDragTop(xy);
            };

            // 左下
            this._onDragLeftBottomStart = function (xy) {
                context._onDragLeftStart(xy);
                context._onDragBottomStart(xy);
            };
            this._onDragLeftBottom = function (xy) {
                context._onDragLeft(xy);
                context._onDragBottom(xy);
            };


            // 右下
            this._onDragRightBottomStart = function (xy) {
                context._onDragRightStart(xy);
                context._onDragBottomStart(xy);
            };
            this._onDragRightBottom = function (xy) {
                context._onDragRight(xy);
                context._onDragBottom(xy);
            };

            // 右上
            this._onDragRightTopStart = function (xy) {
                context._onDragRightStart(xy);
                context._onDragTopStart(xy);
            };
            this._onDragRightTop = function (xy) {
                context._onDragRight(xy);
                context._onDragTop(xy);
            };



            $E.addObserver(this["_dragController_" + handleNames.t], "start", this._onDragTopStart);
            $E.addObserver(this["_dragController_" + handleNames.t], "move", this._onDragTop);
            $E.addObserver(this["_dragController_" + handleNames.t], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.r], "start", this._onDragRightStart);
            $E.addObserver(this["_dragController_" + handleNames.r], "move", this._onDragRight);
            $E.addObserver(this["_dragController_" + handleNames.r], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.b], "start", this._onDragBottomStart);
            $E.addObserver(this["_dragController_" + handleNames.b], "move", this._onDragBottom);
            $E.addObserver(this["_dragController_" + handleNames.b], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.l], "start", this._onDragLeftStart);
            $E.addObserver(this["_dragController_" + handleNames.l], "move", this._onDragLeft);
            $E.addObserver(this["_dragController_" + handleNames.l], "end", this._onDragEnd);



            $E.addObserver(this["_dragController_" + handleNames.rb], "start", this._onDragRightBottomStart);
            $E.addObserver(this["_dragController_" + handleNames.rb], "move", this._onDragRightBottom);
            $E.addObserver(this["_dragController_" + handleNames.rb], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.rt], "start", this._onDragRightTopStart);
            $E.addObserver(this["_dragController_" + handleNames.rt], "move", this._onDragRightTop);
            $E.addObserver(this["_dragController_" + handleNames.rt], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.lt], "start", this._onDragLeftTopStart);
            $E.addObserver(this["_dragController_" + handleNames.lt], "move", this._onDragLeftTop);
            $E.addObserver(this["_dragController_" + handleNames.lt], "end", this._onDragEnd);

            $E.addObserver(this["_dragController_" + handleNames.lb], "start", this._onDragLeftBottomStart);
            $E.addObserver(this["_dragController_" + handleNames.lb], "move", this._onDragLeftBottom);
            $E.addObserver(this["_dragController_" + handleNames.lb], "end", this._onDragEnd);
        },

        setWidth: function (w) {
            $D.setStyle(this.effectEl, "width", w + "px");
            this._width = w;
        },
        setHeight: function (h) {
            $D.setStyle(this.effectEl, "height", h + "px");
            this._height = h;
        },

        setLeft: function (x) {
            $D.setStyle(this.effectEl, "left", x + "px");
            this._left = x;
        },
        setTop: function (y) {
            $D.setStyle(this.effectEl, "top", y + "px");
            this._top = y;
        },


        getWidth: function () {
            return parseInt($D.getStyle(this.effectEl, "width"));
        },
        getHeight: function () {
            return parseInt($D.getStyle(this.effectEl, "height"));
        },

        getLeft: function () {
            return parseInt($D.getStyle(this.effectEl, "left"));
        },
        getTop: function () {
            return parseInt($D.getStyle(this.effectEl, "top"));
        },
        getId: function () {
            return id++;
        },

        lock: function () {
            for (var p in handleNames) {
                this["_dragController_" + handleNames[p]].lock();
            }
        },
        unlock: function () {
            for (var p in handleNames) {
                this["_dragController_" + handleNames[p]].unlock();
            }
        },
        show: function () {
            for (var p in handleNames) {
                this["_dragController_" + handleNames[p]].show();
            }
        },
        hide: function () {
            for (var p in handleNames) {
                this["_dragController_" + handleNames[p]].hide();
            }
        }
    });



});

/**
 * tab模块
 */
Jet().$package(function(J){
	var $ = J.dom.id,
		$D = J.dom,
		$E = J.event;


	/**
	 * Tab类
	 *
	 * @memberOf ui
	 *
	 * @param {Element} triggers tab head元素
	 * @param {Element} sheets tab body元素
	 * @param {Object} config 其他选项，如:isLimited,leftMargin...
	 * @returns
	 *
	 *
	 */
	J.ui.Tab = function(triggers,sheets,config){
		this.tabs = [];             //tab的集合
		this.currentTab = null;     //当前tab
		this.config = {
			defaultIndex : 0,       //默认的tab索引
			triggerEvent : 'click', //默认的触发事件
			slideEnabled : false,   //是否自动切换
			slideInterval : 5*1000,   //切换时间间隔
			slideDelay : 300,       //鼠标离开tab继续切换的延时
			autoInit : true,        //是否自动初始化
			onShow:function(){ }    //默认的onShow事件处理函数
		};

		this.setConfig(config);

		if(triggers && sheets) {
			this.addRange(triggers,sheets);
			if(this.config.autoInit){
				this.init();
			}
		}
	};

	J.ui.Tab.prototype = {
		/**
		 * 设置config
		 * @param {object} config 配置项如{'slideEnabled':true,'defaultIndex':0,'autoInit':false}
		 */
		setConfig:function(config){
			if(!config) return;
			for(var i in config){
				this.config[i] = config[i];
			}
		},
		/**
		 * 增加tab
		 * @param tab={trigger:aaaa, sheet:bbbb}
		 * @param {string|HTMLElement} trigger
		 * @param {string|HTMLElement} sheet
		 * */
		add:function(tab){
			if(!tab) return;

			if(tab.trigger){
				this.tabs.push(tab);
				tab.trigger.style.display = 'block';
			}
		},

		/**
		 * 增加tab数组
		 * @param {array} triggers HTMLElement数组
		 * @param {array} sheets HTMLElement数组
		 * */
		addRange:function(triggers, sheets){
			if(!triggers||!sheets) return;
			if(triggers.length && sheets.length && triggers.length == sheets.length){
				for(var i = 0, len = triggers.length; i < len; i++){
					this.add({trigger:triggers[i],sheet:sheets[i]});
				}
			}
		},

		/**
		 * 设置tab为当前tab并显示
		 * @param {object} tab  tab对象 {trigger:HTMLElement,sheet:HTMLElement}
		 * */
		select:function(tab){
			if(!tab || (!!this.currentTab && tab.trigger == this.currentTab.trigger)) return;
			if(this.currentTab){
				$D.removeClass(this.currentTab.trigger, 'current');
				if(this.currentTab.sheet){
					this.currentTab.sheet.style.display = 'none';
				}

			}
			this.currentTab = tab;
			this.show();
		},

		/**
		 * 设置tab为隐藏的
		 * @param {object} tab  tab对象 {trigger:HTMLElement,sheet:HTMLElement}
		 * */
		remove:function(tab){
			if(!tab) return;


			if(tab.trigger){
				$D.removeClass(tab.trigger, 'current');
				tab.trigger.style.display = 'none';
			}
			if(tab.sheet){
				tab.sheet.style.display = 'none';
			}

			var index=this.indexOf(tab);
			this.tabs.splice(index,index);

			if(tab.trigger == this.currentTab.trigger){
				if(index==0){
					//this.currentTab=this.tabs[(index+1)];
					this.select(this.tabs[(index+1)]);
				}else{
					//this.currentTab=this.tabs[(index-1)];
					this.select(this.tabs[(index-1)]);
				}
			}
		},
		/**
		 * 显示当前被选中的tab
		 * */
		show:function(){

			if(this.currentTab.trigger){
				this.currentTab.trigger.style.display = 'block';
			}
			$D.addClass(this.currentTab.trigger, 'current');
			if(this.currentTab.sheet){
				this.currentTab.sheet.style.display = 'block';
			}
			$E.notifyObservers(this, "show", this.currentTab);

		},
		/**
		 * 自动切换
		 * */
		slide:function(){
			var	config = this.config,
				_this = this,
				intervalId,
				delayId;
			J.array.forEach(this.tabs, function(tab, index, tabs){
				$E.on(tab.trigger, 'mouseover' , clear);
				$E.on(tab.sheet, 'mouseover' , clear);

				$E.on(tab.trigger, 'mouseout' , delay);
				$E.on(tab.sheet, 'mouseout' , delay);
			});
			start();
			function start() {
				var i = _this.indexOf(_this.currentTab);
				if( i == -1 ) return;
				intervalId = window.setInterval(function(){
					var tab = _this.tabs[ ++i % _this.tabs.length ];
					if(tab){
						_this.select(tab);
					}
				},config['slideInterval']);
			}
			function clear() {
				window.clearTimeout(delayId);
				window.clearInterval(intervalId);
			}
			function delay() {
				delayId = window.setTimeout(start,config['slideDelay']);
			}
		},
		/**
		 * 获取tab在tabs数组中的索引
		 * @param {object} tab  tab对象 {trigger:HTMLElement,sheet:HTMLElement}
		 * */
		indexOf:function(tab){
			for(var i = 0, len = this.tabs.length; i < len; i++){
				if(tab.trigger == this.tabs[i].trigger)
					return i;
			}
			return -1;
		},
		/**
		 * 初始化函数
		 * */
		init:function(){
			var config = this.config,
				_this = this;

			J.array.forEach(this.tabs, function(tab, index, tabs){
				$E.on(tab.trigger,config['triggerEvent'], function(){
					_this.select.call(_this,tab);
				});
				if(tab.sheet){
					tab.sheet.style.display = 'none';
				}
			});

			this.select(this.tabs[config['defaultIndex']]);
			if(config['slideEnabled']){
				this.slide();
			}
		}
	};

});






/**
 * MaskLayer模块
 */
Jet().$package(function(J){
	var $ = J.dom.id,
		$D = J.dom,
		$E = J.event;

	/**
	 * MaskLayer 遮罩层类
	 *
	 * @memberOf ui
	 * @Class
	 *
	 * @param {Object} option 其他选项，如:zIndex,appendTo...
	 * @returns
	 *
	 *
	 */
	J.ui.MaskLayer = new J.Class({

		/**
		 * 初始化函数
		 * */
		init:function(option){
			var context = this;
			option.zIndex = !J.isUndefined(option.zIndex) ? option.zIndex : 9000000;
			option.appendTo = option.appendTo || $D.getDocument();

			this.container = $D.node("div", {
				"class" : "maskLayer"
			});
			this.container.innerHTML = '\
					<div class="maskBackground"></div>\
					<div id="maskLayerBody"></div>\
				'
			this.setZIndex(option.zIndex);
			option.appendTo.appendChild(this.container);


			var observer = {
				onMaskLayerClick : function(){
					$E.notifyObservers(context, "click", context);
				}
			};

			$E.on(this.container, "click", observer.onMaskLayerClick);

			this.body = $D.id("maskLayerBody");
		},

		append : function(el){
			this.body.appendChild(el);
		},

		show : function(){
			$D.show(this.container);
			$E.notifyObservers(this, "show");
			this._isShow = true;
		},
		hide : function(){
			$D.hide(this.container);
			$E.notifyObservers(this, "hide");
			this._isShow = false;
		},
		isShow : function(){
			return this._isShow;
		},
		toggleShow : function(){
			if(this.isShow()){
				this.hide();
			}else{
				this.show();
			}
		},
		getZIndex : function(){
			return this._zIndex;
		},

		setZIndex : function(zIndex){
			$D.setStyle(this.container, "zIndex", zIndex);
			this._zIndex = zIndex;
		},

		setTopZIndex : function(){
			this.setZIndex(qqweb.layout.getTopZIndex());
		},
		fadeIn : function(){
			this.show();
		},

		fadeOut : function(){
			this.hide();
		},

		// 关于
		about : function(){

		}
	});

});











