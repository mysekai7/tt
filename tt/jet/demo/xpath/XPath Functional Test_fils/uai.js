/*
 * object: user agent identifier
 */
var uai = new function() {

    var ua = navigator.userAgent;

    if (RegExp == undefined) {
        if (ua.indexOf("Opera") >= 0) {
            this.opera = true;
        } else if (ua.indexOf("Netscape") >= 0) {
            this.netscape = true;
        } else if (ua.indexOf("Mozilla/") == 0) {
            this.mozilla = true;
        } else {
            this.unknown = true;
        }

        if (ua.indexOf("Gecko/") >= 0) {
            this.gecko = true;
        }

        if (ua.indexOf("Win") >= 0) {
            this.windows = true;
        } else if (ua.indexOf("Mac") >= 0) {
            this.mac = true;
        } else if (ua.indexOf("Linux") >= 0) {
            this.linux = true;
        } else if (ua.indexOf("BSD") >= 0) {
            this.bsd = true;
        } else if (ua.indexOf("SunOS") >= 0) {
            this.sunos = true;
        }
    }
    else {

        /* for Trident/Tasman */
        /*@cc_on
        @if (@_jscript)
            function jscriptVersion() {
                switch (@_jscript_version) {
                    case 3.0:  return "4.0";
                    case 5.0:  return "5.0";
                    case 5.1:  return "5.01";
                    case 5.5:  return "5.5";
                    case 5.6:
                        if ("XMLHttpRequest" in window) return "7.0";
                        return "6.0";
                    case 5.7:
                        return "7.0";
                    default:   return true;
                }
            }
            if (@_win16 || @_win32 || @_win64) {
                this.windows = true;
                this.trident = jscriptVersion();
            } else if (@_mac || navigator.platform.indexOf("Mac") >= 0) {
                // '@_mac' may be 'NaN' even if the platform is Mac,
                // so we check 'navigator.platform', too.
                this.mac = true;
                this.tasman = jscriptVersion();
            }
            if (/MSIE (\d+\.\d+)b?;/.test(ua)) {
                this.ie = RegExp.$1;
                this['ie' + RegExp.$1.charAt(0)] = true;
            }
        @else @*/

        /* for AppleWebKit */
        if (/AppleWebKit\/(\d+(?:\.\d+)*)/.test(ua)) {
            this.applewebkit = RegExp.$1;
            if (RegExp.$1.charAt(0) == 4) {
                this.applewebkit2 = true;
            }
            else {
                this.applewebkit3 = true;
            }
        }

        /* for Gecko */
        else if (typeof Components == "object" &&
                 (/Gecko\/(\d{8})/.test(ua) ||
                  navigator.product == "Gecko" && /^(\d{8})$/.test(navigator.productSub))) {
            this.gecko = RegExp.$1;
        }

        /*@end @*/

        if (typeof(opera) == "object" && typeof(opera.version) == "function") {
            this.opera = opera.version();
            this['opera' + this.opera[0] + this.opera[2]] = true;
        } else if (typeof opera == "object"
                && (/Opera[\/ ](\d+\.\d+)/.test(ua))) {
            this.opera = RegExp.$1;
        } else if (this.ie) {
        } else if (/Safari\/(\d+(?:\.\d+)*)/.test(ua)) {
            this.safari = RegExp.$1;
        } else if (/NetFront\/(\d+(?:\.\d+)*)/.test(ua)) {
            this.netfront = RegExp.$1;
        } else if (/Konqueror\/(\d+(?:\.\d+)*)/.test(ua)) {
            this.konqueror = RegExp.$1;
        } else if (ua.indexOf("(compatible;") < 0
                && (/^Mozilla\/(\d+\.\d+)/.test(ua))) {
            this.mozilla = RegExp.$1;
            if (/\([^(]*rv:(\d+(?:\.\d+)*).*?\)/.test(ua))
                this.mozillarv = RegExp.$1;
            if (/Firefox\/(\d+(?:\.\d+)*)/.test(ua)) {
                this.firefox = RegExp.$1;
            } else if (/Netscape\d?\/(\d+(?:\.\d+)*)/.test(ua)) {
                this.netscape = RegExp.$1;
            }
        } else {
            this.unknown = true;
        }

        if (ua.indexOf("Win 9x 4.90") >= 0) {
            this.windows = "ME";
        } else if (/Win(?:dows)? ?(NT ?(\d+\.\d+)?|\d+|ME|Vista|XP)/.test(ua)) {
            this.windows = RegExp.$1;
            if (RegExp.$2) {
                this.winnt = RegExp.$2;
            } else switch (RegExp.$1) {
                case "2000":   this.winnt = "5.0";  break;
                case "XP":     this.winnt = "5.1";  break;
                case "Vista":  this.winnt = "6.0";  break;
            }
        } else if (ua.indexOf("Mac") >= 0) {
            this.mac = true;
        } else if (ua.indexOf("Linux") >= 0) {
            this.linux = true;
        } else if (/(\w*BSD)/.test(ua)) {
            this.bsd = RegExp.$1;
        } else if (ua.indexOf("SunOS") >= 0) {
            this.sunos = true;
        }
    }
};


