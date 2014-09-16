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
 * robot 包
 */
Jet().$package(function(J){
	J.robot = J.robot || {};
	
});
/**
 * Robot 类
 */
Jet().$package(function(J){
	var $D = J.dom,
		$E = J.event;

	var Robot = new J.Class({
		init : function(){
			this.type = "robot";
			J.out("I'm " + this.type + "!");
			
		},
		
		doNothing : function(){
			
		},
		
		/**
		 * robot 前进
		 * 
		 * @memberOf Robot.ptototype
		 * @param {Number} distance 向前走的距离，单位：px
		 * @return {Number} 返回...
		 */
		ahead : function(distance){
			
		},
		
		
		/**
		 * robot 后退
		 * 
		 * @memberOf Robot.ptototype
		 * @param {Number} distance 向前走的距离，单位：px
		 * @return {Number} 返回...
		 */
		back : function(distance){
			
		},
		
		/**
		 * robot 开火
		 * 
		 * @memberOf Robot.ptototype
		 * @param {Number} power 火力
		 * @return {Number} 返回...
		 */
		fire : function(power){
			
		},
		
		
		/**
		 * 返回机器人目前的能源
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回机器人目前的能源
		 */
		getEnergy : function(){
			
		},
		
		/**
		 * 返回枪目前的热量
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回枪目前的热量
		 */
		getGunHeat : function(){
			
		},
		
		/**
		 * 返回目前的速度
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回目前的速度
		 */
		getVelocity : function(){
			
		},
		
		
		/**
		 * 返回枪目前的热量
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回枪目前的热量
		 */
		getHeading : function(){
			
		},
		
		turnLeft : function(degrees){
		},
		
		turnRight : function(degrees){
		},
		
		turnRadarLeft : function(degrees){
		},
		
		turnRadarRight : function(degrees){
		},
		
		turnGunLeft : function(degrees){
		},
		
		turnGunRight : function(degrees){
		},
		
		
		getName : function(){			
		},
		
		/**
		 * 返回多少对手还留在本场战争
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回多少对手还留在本场战争
		 */
		getOthers : function(){			
		},
		
		/**
		 * 返回机器人X位置。 （0,0）是在战场左下方
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回机器人X位置。 （0,0）是在战场左下方
		 */
		getX : function(){			
		},
		
		/**
		 * 返回机器人Y位置。 （0,0）是在战场左下方
		 * 
		 * @memberOf Robot.ptototype
		 * @return {Number} 返回机器人X位置。 （0,0）是在战场左下方
		 */
		getY : function(){			
		},
		
		
		run : function(){			
		},
		scan : function(){			
		},
		
		
		stop : function(){			
		},
		
		setBodyColor : function(color){			
		},
		
		about : function(){
			return "Robot by Kinvix"
		}
	
	});
		
	J.robot.Robot = Robot;

});













