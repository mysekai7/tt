/**
 * [Javascript core part]: sound 扩展
 */

var swfsound;
Jet().$package(function(J){
	var $D = J.dom,
		$E = J.event;

	//J.sound = J.sound || {};



	/*! SWFSound v1.1 <http://code.google.com/p/swfsound/>
		Copyright (c) 2009 Frank Baumgartner, www.b-nm.at
		This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>

		*** Requires SWFObject 2.1 or higher ! ***
		*** Requires Flash Player 8.0 or higher! ***

		History:
		===================================
		xx.01.2009 	- v1.0 - Initial release
		14.03.2009 	- v1.1 - Added "pause" feature
		30.03.2009  - v1.2 - Added some fine-tuning by Ben Long (shift.insert@gmail.com)
		07.06.2009	- v1.3 - Removed Array keyword, changed visibility to top left pixel corner
												 (both reported by pellicierraphael@neuf.fr)
	*/
	/**
	 * @namespace sound 名字空间
	 * @name sound
	 */
	swfsound = function()
	{
		// Public API
		return {

			pauseStatus: [],

			embedSWF: function( path ){
				if ( path == undefined ) {
					path = "./swf/swfsound.swf";
				}

				var flashvars = false;

				var attributes = {
						id : 'swfSound_Flash'
				};

				var params = {
		      		menu : 'false',
		       	   	wmode : 'transparent',
		       	   	swLiveConnect : 'true',
		       	   	allowScriptAccess : 'always'
				};

				// document.write( '<div id="swfSound_Flash_div" style="position:absolute; left:0; top:0;"></div>' );
		        var d = document, div;
		            div = d.createElement('div');
		            div.id = "swfSound_Flash_div";
		            div.style.position = "absolute";
		            div.style.left = 0;
		            div.style.top = 0;
		            d.getElementsByTagName('body')[0].appendChild(div);

				// make sure the flash movie is visible, otherwise the onload is not fired!
				var def = "#swfSound_Flash { left:0; position:absolute; top: 0; }";
				var ss1 = document.createElement('style');
				ss1.setAttribute("type", "text/css");
				if (ss1.styleSheet)
				{
						// IE
				    ss1.styleSheet.cssText = def;
				}
				else
				{
					  // W3C
				    var tt1 = document.createTextNode(def);
				    ss1.appendChild(tt1);
				}
				var hh1 = document.getElementsByTagName('head')[0];
				hh1.appendChild(ss1);

				try
				{
					 J.swfobject.embedSWF( path, 'swfSound_Flash_div', '1', '1', '8.0.0', './expressInstall.swf', flashvars, params, attributes);
		       	}
		       	catch ( e )
		       	{
		       		alert( 'Seems like you are missing swfobject! - Please include the swfobject javascript into your HTML!' );
		      	}
			},


			/*
					loadSound( mp3URL, streamingMode )
					=========================================
					mp3URL: String ... relative or absolute URL path to a sound
					streamingMode: Boolean ... true streams the mp3 progressively and automatically starts playback,
																		false just loads the sound for later event based triggering with "startSound"
					onLoadCallbackFunctionName ... STRING of the JavaScript function that should be called when the sound is fully loaded
					onID3CallbackFunctionName ... STRING of the JavaScript function that should be claled when MP3 ID3 information is available

					return value: id of loaded sound
			*/
			/**
			 * 加载声音文件的方法
			 *
			 * @memberOf sound
			 *
			 * @param {String} mp3URL sound文件的路径，目前支持mp3
			 * @param {Bool} streamingMode 是否采用流模式
			 * @returns
			 *
			 */
			loadSound: function( mp3URL, streamingMode, onLoadCallbackFunctionName, onID3CallbackFunctionName )
			{
					if ( streamingMode == undefined ) streamingMode = false;
					if ( onLoadCallbackFunctionName == undefined ) onLoadCallbackFunctionName = null;
					if ( onID3CallbackFunctionName == undefined ) onID3CallbackFunctionName = null;

					var obj = document.getElementById( 'swfSound_Flash' );

					return obj.loadSound( mp3URL, streamingMode, onLoadCallbackFunctionName, onID3CallbackFunctionName );
			},


			/*
					startSound( id_Sound, offsetSecondsFloat )
					=========================================
					mp3URL: String ... relative or absolute URL path to a sound
					offsetSecondsFloat: Number ... offset in seconds (float values possible)
					loopCount ... number of loops the sound should be played
					onSoundCompleteCallbackFunctionName ... the name of the function (as String!) that should be called when the sound playback has been completed
			*/
			/**
			 * 开始播放的声音的方法
			 *
			 * @memberOf sound
			 *
			 * @param {Object} id_sound 要播放声音的对象
			 * @returns
			 *
			 */
			startSound: function( id_sound, offsetSecondsFloat, loopCount, onSoundCompleteCallbackFunctionName )
			{
					if ( offsetSecondsFloat == undefined ) offsetSecondsFloat = 0.0;
					if ( onSoundCompleteCallbackFunctionName == undefined ) onSoundCompleteCallbackFunctionName = null;
					if ( loopCount == undefined ) loopCount = 1;

					var obj = document.getElementById( 'swfSound_Flash' );

					obj.startSound( id_sound, offsetSecondsFloat, loopCount, onSoundCompleteCallbackFunctionName );

					return true;
			},

			/**
			 * 停止播放的声音的方法
			 *
			 * @memberOf sound
			 *
			 * @param {Object} id_sound 声音对象
			 * @returns
			 *
			 */
			stopSound: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					obj.stopSound( id_sound );

					return true;
			},


			/*
					Pause Sound:
					==================================
					Added in v1.1 - pause/play toggle feature
			*/
			/**
			 * 暂停播放的声音的方法
			 *
			 * @memberOf sound
			 *
			 * @param {Object} id_sound 声音对象
			 * @returns
			 *
			 */
			pauseSound: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					var pstatus = swfsound.pauseStatus[id_sound];

					if ( pstatus == true )
					{
							swfsound.startSound( id_sound, swfsound.getPosition(id_sound)/1000 );
							swfsound.pauseStatus[id_sound] = false;
					}
					else
					{
							swfsound.stopSound( id_sound );
							swfsound.pauseStatus[id_sound] = true;
					}
					return swfsound.pauseStatus[id_sound];
			},


			/*
					Set Volume:
					==================================
					valid values: 0 (= silent) ... 100 (= maximum volume)
			*/
			/**
			 * 设置音量
			 *
			 * @memberOf sound
			 *
			 * @param {Object} id_sound 声音对象
			 * @param {Number} newVolume 声音大小
			 * @returns
			 *
			 */
			setVolume: function( id_sound, newVolume )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					obj.setVolume( id_sound, newVolume );

					return true;
			},

			/**
			 * 获取音量
			 *
			 * @memberOf sound
			 *
			 * @param {Object} id_sound 声音对象
			 * @returns
			 *
			 */
			getVolume: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getVolume( id_sound );
			},


			/*
					Returns the duration of a sound, in milliseconds
			*/
			getDuration: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getDuration( id_sound );
			},


			/*
					Returns the current playback position of a sound, in milliseconds
			*/
			getPosition: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getPosition( id_sound );
			},


			/*
					Returns the current ID3 object
			*/
			getID3: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getID3( id_sound );
			},


			/*
					Set left/right panning:
					==================================
					-100 	= left
					0 		= center
					+100 	= right
			*/
			setPan: function( id_sound, newPan )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					obj.setPan( id_sound, newPan );

					return true;
			},


			getPan: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getPan( id_sound );
			},


			/*
					Returns the number of bytes of a sound that have already been loaded
			*/
			getBytesLoaded: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getBytesLoaded( id_sound );
			},


			/*
					Returns the total number of bytes of a sound file
			*/
			getBytesTotal: function( id_sound )
			{
					var obj = document.getElementById( 'swfSound_Flash' );
					return obj.getBytesTotal( id_sound );
			}

		};

	}();

	//swfsound.embedSWF( './swfsound.swf' );

	J.sound = swfsound;

});
