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
 * Package: jet.fx
 * 
 * Need package:
 * jet.core.js
 * 
 */
 
 
 /**
 * fx
 */
Jet().$package(function(J){
	J.fx = J.fx || {};
	
});
/**
 * tween模块
 */
Jet().$package(function(J){
	var $D = J.dom,
		$E = J.event,
		$T = J.fx.tween;
	/*
	http://www.cnblogs.com/cloudgamer/archive/2009/01/06/Tween.html
	
	Linear：无缓动效果；
	Quadratic：二次方的缓动（t^2）；
	Cubic：三次方的缓动（t^3）；
	Quartic：四次方的缓动（t^4）；
	Quintic：五次方的缓动（t^5）；
	Sinusoidal：正弦曲线的缓动（sin(t)）；
	Exponential：指数曲线的缓动（2^t）；
	Circular：圆形曲线的缓动（sqrt(1-t^2)）；
	Elastic：指数衰减的正弦曲线缓动；
	Back：超过范围的三次方缓动（(s+1)*t^3 - s*t^2）；
	Bounce：指数衰减的反弹缓动。


	每个效果都分三个缓动方式（方法），分别是：
	easeIn：从0开始加速的缓动；
	easeOut：减速到0的缓动；
	easeInOut：前半段从0开始加速，后半段减速到0的缓动。
	其中Linear是无缓动效果，没有以上效果。



	t: current time（当前时间）；
	b: beginning value（初始值）；
	c: change in value（变化量）；
	d: duration（持续时间）。

	*/
	var tween = {
		// linear：无缓动效果；
		linear: function(t,b,c,d){ return c*t/d + b; },
		
		// quadratic：二次方的缓动（t^2）；
		quadratic: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t + b;
			},
			easeOut: function(t,b,c,d){
				return -c *(t/=d)*(t-2) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t + b;
				return -c/2 * ((--t)*(t-2) - 1) + b;
			}
		},
		
		// cubic：三次方的缓动（t^3）；
		cubic: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return c*((t=t/d-1)*t*t + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t + b;
				return c/2*((t-=2)*t*t + 2) + b;
			}
		},
		
		// quartic：四次方的缓动（t^4）；
		quartic: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return -c * ((t=t/d-1)*t*t*t - 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
				return -c/2 * ((t-=2)*t*t*t - 2) + b;
			}
		},
		
		// quintic：五次方的缓动（t^5）；
		quintic: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return c*((t=t/d-1)*t*t*t*t + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
				return c/2*((t-=2)*t*t*t*t + 2) + b;
			}
		},
		
		// sinusoidal：正弦曲线的缓动（sin(t)）；
		sinusoidal: {
			easeIn: function(t,b,c,d){
				return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
			},
			easeOut: function(t,b,c,d){
				return c * Math.sin(t/d * (Math.PI/2)) + b;
			},
			easeInOut: function(t,b,c,d){
				return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
			}
		},
		
		// exponential：指数曲线的缓动（2^t）；
		exponential: {
			easeIn: function(t,b,c,d){
				return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
			},
			easeOut: function(t,b,c,d){
				return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if (t==0) return b;
				if (t==d) return b+c;
				if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
				return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
			}
		},
		
		// circular：圆形曲线的缓动（sqrt(1-t^2)）；
		circular: {
			easeIn: function(t,b,c,d){
				return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
			},
			easeOut: function(t,b,c,d){
				return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
				return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
			}
		},
		
		// elastic：指数衰减的正弦曲线缓动；
		elastic: {
			easeIn: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
			},
			easeOut: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				return (a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b);
			},
			easeInOut: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
				return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
			}
		},
		
		// back：超过范围的三次方缓动（(s+1)*t^3 - s*t^2）；
		back: {
			easeIn: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158;
				return c*(t/=d)*t*((s+1)*t - s) + b;
			},
			easeOut: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158;
				return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
			},
			easeInOut: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158; 
				if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
				return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
			}
		},
		
		// bounce：指数衰减的反弹缓动。
		bounce: {
			easeIn: function(t,b,c,d){
				return c - tween.bounce.easeOut(d-t, 0, c, d) + b;
			},
			easeOut: function(t,b,c,d){
				if ((t/=d) < (1/2.75)) {
					return c*(7.5625*t*t) + b;
				} else if (t < (2/2.75)) {
					return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
				} else if (t < (2.5/2.75)) {
					return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
				} else {
					return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
				}
			},
			easeInOut: function(t,b,c,d){
				if (t < d/2) return tween.bounce.easeIn(t*2, 0, c, d) * .5 + b;
				else return tween.bounce.easeOut(t*2-d, 0, c, d) * .5 + c*.5 + b;
			}
		}
	}
	
	
	var Animation = new J.Class({
		init : function(el, style, begin, end, fx, total){

			var fx = fx,
				total = total || 20,
				context = this,
				value;


			var _run = this._run = function(){
				
				if(context.current < total){
					context.current++;
					/*
					current:当前比率
					begin:0%时输出的实际值
					end:100%时输出的实际值
					total:总比率
					*/
					if(begin > end){
						value = begin - Math.ceil(fx(context.current, 0, (begin - end), total));
					}else{
						value = begin + Math.ceil(fx(context.current, 0, (end - begin), total));
					}
					
					$D.setStyle(el, style, value + "px");
					context._timer = setTimeout(_run, 30);
				}else{
					//$D.setStyle(el, style, end + "px");
					$E.notifyObservers(context, "finish");
				}
			};
		
			
			
		},

		start : function(){
			clearTimeout(this._timer);
			this.current = 0;
			//$D.setStyle(this.el, this.style, this.begin + "px");
			this._run();
		}
	});
	
	
	J.fx.Animation = Animation;
	J.fx.tween = tween;
});













