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
 * kdv 包
 */
Jet().$package("kdv", function(J){
	var $D = J.dom,
		$E = J.event;

		
		
	// Kinvix - a robot by (your name here)
	var Kinvix = new J.Class({extend: J.robot.Robot}, {
		init : function(){
			this.name = "Kinvix";
		},
		
		run : function(){
			while(true) {
				// Replace the next 4 lines with any behavior you would like
				this.ahead(100);
				this.turnGunRight(360);
				this.back(100);
				this.turnGunRight(360);
			}
		},
		
		onScannedRobot : function(e) {
			this.fire(1);
		},
		
		onHitByBullet : function(e) {
			this.turnLeft(90 - e.getBearing());
		}
		
		
	});
	
	kdv.Kinvix = Kinvix;

});













