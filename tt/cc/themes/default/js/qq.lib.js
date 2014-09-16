if (typeof(QZFL) == "undefined" || !QZFL) {
    var QZFL;
    if (typeof(QZONE) == "object") {
        QZFL = QZONE;
    } else {
        window.QZONE = QZFL = {};
    }
    QZFL.version = "2.0.5.0";
    QZFL._qzfl = true;
}
QZFL.emptyFn = function() {};
QZFL.userAgent = (function() {
    var t,
    vie,
    vff,
    vopera,
    vsf,
    vawk,
    wintype,
    winver,
    mactype,
    vair,
    vchrome,
    isBeta = false,
    discerned = false,
    _ua = navigator.userAgent,
    mainRE = /(?:MSIE.(\d+\.\d+))|(?:(?:Firefox|GranParadiso|Iceweasel|Minefield).(\d+\.\d+))|(?:Opera.(\d+\.\d+))|(?:AppleWebKit.(\d+(?:\.\d+)?))/,
    osRE = /(Windows.*?;)|(Mac OS X.*?;)/,
    winRE = /Windows.+?(\d+\.\d+)/,
    airRE = /AdobeAIR/,
    chromeRE = /Chrome.(\d+\.\d+)/,
    safariRE = /Version\/(\d+(?:\.\d+)?)/,
    agent = mainRE.exec(_ua),
    os = osRE.exec(_ua);
    if (agent) {
        vie = agent[1] ? parseFloat(agent[1], 10) : NaN;
        vff = agent[2] ? parseFloat(agent[2], 10) : NaN;
        vopera = agent[3] ? parseFloat(agent[3], 10) : NaN;
        vawk = agent[4] ? parseFloat(agent[4], 10) : NaN;
        vair = vsf = vchrome = NaN;
        if (vawk) {
            if (t = airRE.exec(_ua)) {
                vair = 1;
            } else if (t = safariRE.exec(_ua)) {
                if (t.length > 1) {
                    vsf = parseFloat(t[1], 10);
                } else {
                    vsf = 1.0;
                }
            } else if (t = chromeRE.exec(_ua)) {
                if (t.length > 1) {
                    vchrome = parseFloat(t[1], 10);
                } else {
                    vchrome = 1.0;
                }
            }
        }
    } else {
        vie = vff = vopera = vsf = vawk = vair = vchrome = NaN;
        if (typeof ActiveXObject == "function") {
            vie = (/Trident\/4\.0/i.test(navigator.appVersion)) ? 8: 8 - (typeof XDomainRequest == "object" ? 0: 1) - (typeof XMLHttpRequest == "object" ? 0: 1);
        } else if (/AppleWebKit\/\d+\.\d+/i.test(navigator.appVersion)) {
            vawk = parseFloat(navigator.appVersion.replace(/^[\s\S]*?AppleWebKit.(\d+\.\d+)[\s\S]*$/i, "$1"));
            if (typeof openDatabase == "function") {
                vsf = parseFloat(navigator.appVersion.replace(/^[\s\S]*?Version.(\d+\.\d+)[\s\S]*$/i, "$1"));
            }
            if (typeof MessageEvent == "function") {
                vchrome = parseFloat(navigator.appVersion.replace(/^[\s\S]*?chrome.(\d+\.\d+)[\s\S]*$/i, "$1"));
            }
            if (/AdobeAIR/i.test(navigator.appVersion)) {
                vair = 1;
            }
        } else if (typeof document.getBoxObjectFor == "function") {
            vff = parseFloat(navigator.userAgent.replace(/^[\s\S]*?Firefox\/(\d+\.\d+)[\s\S]*$/i, "$1"));
        } else if (typeof opera == "object") {
            vopera = parseFloat(navigator.appVersion.replace(/^(\d+\.\d+)[\s\S]*$/i, "$1"));
        } else {
            vie = 6;
        }
    }
    if (vie) {
        if (vie > 7 && window.navigator && window.navigator.appMinorVersion && window.navigator.appMinorVersion.toLowerCase().indexOf("beta") > -1) {
            isBeta = true;
        }
    }
    if (os) {
        wintype = !!os[1];
        mactype = !!os[2];
        if (wintype) {
            if (t = winRE.exec(_ua)) {
                if (t.length > 0) {
                    winver = parseFloat(t[1], 10);
                }
            }
        }
    } else {
        wintype = mactype = false;
        winver = NaN;
    }
    function adjustBehaviors() {
        if (!adjustBehaviors.adjusted && vie && vie < 7) {
            try {
                document.execCommand('BackgroundImageCache', false, true);
            } catch(ignored) {}
            adjustBehaviors.adjusted = true;
        }
    }
    return {
        beta: isBeta,
        firefox: vff,
        ie: vie,
        opera: vopera,
        air: vair,
        safari: vsf,
        safariV: vsf,
        webkit: vawk,
        windows: winver ? winver: wintype,
        macs: mactype,
        chrome: vchrome,
        adjustBehaviors: adjustBehaviors
    };
})();
QZFL.object = {
    map: function(object, scope, tf) {
        scope = scope || window;
        QZFL.object.each(object,
        function(value, key) {
            if (typeof(tf) == "string") {
                if (typeof(value == tf)) {
                    scope[key] = value;
                }
            } else {
                scope[key] = value;
            }
        });
    },
    extend: function(object, extendModule) {
        var _t = typeof object;
        if (_t != "object" && _t != "function") {
            return;
        }
        QZFL.object.each(extendModule,
        function(value, key) {
            object[key] = value;
        });
        extendModule = null;
    },
    each: function(object, fn) {
        if (typeof object != "object" || typeof fn != "function") {
            return false;
        }
        var i = 0,
        k,
        _fn = fn;
        if (Object.prototype.toString.call(object) === "[object Array]") {
            if ( !! object.forEach) {
                object.forEach(fn);
            } else {
                var len = object.length
                while (i < len) {
                    _fn(object[i], i, object); ++i;
                }
            }
        } else {
            for (k in object) {
                _fn(object[k], k, object);
            }
        }
        return true;
    },
    getType: function(object) {
        var _t;
        return ((_t = typeof(object)) == "object" ? object == null && "null" || Object.prototype.toString.call(object).slice(8, -1) : _t).toLowerCase();
    }
};
QZFL.console = {
    print: function(msg, type) {
        if (window.console) {
            console.log((type == 4 ? (new Date() + ":") : "") + msg);
        }
    }
}
QZFL.report = {
    receive: QZFL.emptyFn,
    addRule: QZFL.emptyFn
};
QZFL.runTime = {
    isDebugMode: false,
    error: function() {},
    warn: function() {}
}
QZFL.object.each(["widget", "string", "util"],
function(value) {
    QZFL[value] = {};
});
QZFL.namespace = QZFL.object;
window.ua = window.ua || QZFL.userAgent;
QZFL.config = {
    debugLevel: 0,
    defaultDataCharacterSet: "utf-8",
    DCCookieDomain: "qq.com",
    domainPrefix: "qq.com",
    gbEncoderPath: "http://imgcache.qq.com/ac/club/qqvip/toolpages/",
    FSHelperPage: "http://imgcache.qq.com/ac/club/qqvip/toolpages/fp_gbk.html",
    defaultShareObject: "http://imgcache.qq.com/ac/club/qqvip/toolpages/getset.swf"
};
QZFL.css = {
    getClassRegEx: function(className) {
        var re = QZFL.css.classNameCache[className];
        if (!re) {
            re = new RegExp('(?:^|\\s+)' + className + '(?:\\s+|$)');
            QZFL.css.classNameCache[className] = re;
        }
        return re;
    },
    convertHexColor: function(color) {
        color = /^#/.test(color) ? color.substr(1) : color;
        var reColor = new RegExp("\\w{2}", "ig");
        color = color.match(reColor);
        if (!color || color.length < 3) {
            return [0, 0, 0]
        }
        var r = parseInt(color[0], 16);
        var g = parseInt(color[1], 16);
        var b = parseInt(color[2], 16);
        return [r, g, b];
    },
    styleSheets: {},
    getStyleSheetById: function(id) {
        try {
            return QZFL.dom.get(id).sheet || document.styleSheets[id];
        } catch(e) {
            return null
        }
    },
    getRulesBySheet: function(sheetId) {
        var ss = QZFL.css.getStyleSheetById(sheetId);
        if (ss) {
            try {
                return ss.cssRules || ss.rules;
            } catch(e) {
                return null
            }
        } else {
            return null
        }
    },
    getRuleBySelector: function(sheetId, selector) {
        var _ss = this.getStyleSheetById(sheetId);
        if (!_ss.cacheSelector) {
            _ss.cacheSelector = {}
        };
        if (_ss) {
            var _rs = _ss.cssRules || _ss.rules;
            var re = new RegExp('^' + selector + '$', "i");
            var _cs = _ss.cacheSelector[selector];
            if (_cs && re.test(_rs[_cs].selectorText)) {
                return _rs[_cs];
            } else {
                for (var i = 0; i < _rs.length; i++) {
                    if (re.test(_rs[i].selectorText)) {
                        _ss.cacheSelector[selector] = i;
                        return _rs[i];
                    }
                }
                return null;
            }
        } else {
            return null;
        }
    },
    insertCSSLink: function(url, id) {
        var dom = document,
        cssLink = dom.createElement("link");
        if (id) {
            cssLink.id = id;
        }
        cssLink.rel = "stylesheet";
        cssLink.rev = "stylesheet";
        cssLink.type = "text/css";
        cssLink.media = "screen";
        cssLink.href = url;
        dom.getElementsByTagName("head")[0].appendChild(cssLink);
        return cssLink.sheet || cssLink;
    },
    insertStyleSheet: function(sheetId) {
        var ss = document.createElement("style");
        ss.id = sheetId;
        document.getElementsByTagName("head")[0].appendChild(ss);
        return ss.sheet || ss;
    },
    removeStyleSheet: function(id) {
        var _ss = this.getStyleSheetById(id);
        if (_ss) {
            var own = _ss.owningElement || _ss.ownerNode;
            QZFL.dom.removeElement(own);
        }
    },
    hasClassName: function(elem, cname) {
        return (elem && cname) ? new RegExp('\\b' + cname + '\\b').test(elem.className) : false;
    },
    swapClassName: function(elements, class1, class2) {
        function _swap(el, c1, c2) {
            if (QZFL.css.hasClassName(el, c1)) {
                el.className = el.className.replace(c1, c2);
            } else if (QZFL.css.hasClassName(el, c2)) {
                el.className = el.className.replace(c2, c1);
            }
        }
        if (elements.constructor != Array) {
            elements = [elements];
        }
        for (var i = 0, len = elements.length; i < len; i++) {
            _swap(elements[i], class1, class2);
        }
    },
    replaceClassName: function(elements, sourceClass, targetClass) {
        function _replace(el, c1, c2) {
            if (QZFL.css.hasClassName(el, c1)) {
                el.className = el.className.replace(c1, c2);
            }
        }
        if (elements.constructor != Array) {
            elements = [elements];
        }
        for (var i = 0, len = elements.length; i < len; i++) {
            _replace(elements[i], sourceClass, targetClass);
        }
    },
    addClassName: function(elem, cname) {
        if (elem && cname) {
            if (elem.className) {
                if (QZFL.css.hasClassName(elem, cname)) {
                    return false;
                } else {
                    elem.className += ' ' + cname;
                    return true;
                }
            } else {
                elem.className = cname;
                return true;
            }
        } else {
            return false;
        }
    },
    removeClassName: function(elem, cname) {
        if (elem && cname && elem.className) {
            var old = elem.className;
            elem.className = (elem.className.replace(new RegExp('\\b' + cname + '\\b'), ''));
            return elem.className != old;
        } else {
            return false;
        }
    },
    toggleClassName: function(elem, cname) {
        var r = QZFL.css;
        if (r.hasClassName(elem, cname)) {
            r.removeClassName(elem, cname);
        } else {
            r.addClassName(elem, cname);
        }
    }
}
QZFL.css.classNameCache = {};
QZFL.dom = {
    getById: function(id) {
        return document.getElementById(id);
    },
    getByName: function(name, tagName) {
        if (!tagName) return document.getElementsByName(name);
        var arr = [];
        var e = document.getElementsByTagName(tagName);
        for (var i = 0; i < e.length; ++i) {
            if ( !! e[i].getAttribute("name") && (e[i].getAttribute("name").toLowerCase() == name.toLowerCase())) {
                arr.push(e[i]);
            }
        }
        return arr;
    },
    get: function(e) {
        if (e && ((e.tagName || e.item) || e.nodeType == 9)) {
            return e;
        }
        return this.getById(e);
    },
    getNode: function(e) {
        if (e && (e.nodeType || e.item)) {
            return e;
        }
        if (typeof e === 'string') {
            return this.getById(e);
        }
        return null;
    },
    removeElement: function(el) {
        if (typeof(el) == "string") {
            el = QZFL.dom.getById(el);
        }
        if (!el) {
            return;
        }
        if (el.removeNode) {
            el.removeNode(true);
        } else {
            if (el.childNodes.length > 0) {
                for (var ii = el.childNodes.length - 1; ii >= 0; ii--) {
                    QZFL.dom.removeElement(el.childNodes[ii]);
                }
            }
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }
        el = null;
        return null;
    },
    searchElementByClassName: function(el, className) {
        el = this.get(el);
        if (!el) {
            return null
        }
        var re = QZFL.css.getClassRegEx(className);
        while (el) {
            if (re.test(el.className)) {
                return el;
            }
            el = el.parentNode;
        }
        return null;
    },
    getElementsByClassName: function(className, tag, root) {
        tag = tag || '*';
        root = (root) ? this.get(root) : null || document;
        if (!root) {
            return [];
        }
        var nodes = [],
        elements = root.getElementsByTagName(tag),
        re = QZFL.css.getClassRegEx(className);
        for (var i = 0, len = elements.length; i < len; ++i) {
            if (re.test(elements[i].className)) {
                nodes[nodes.length] = elements[i];
            }
        }
        return nodes;
    },
    isAncestor: function(node1, node2) {
        if (!node1 || !node2) {
            return false;
        }
        if (node1.contains && node2.nodeType && !QZFL.userAgent.Safari) {
            return node1.contains(node2) && node1 != node2;
        }
        if (node1.compareDocumentPosition && node2.nodeType) {
            return !! (node1.compareDocumentPosition(node2) & 16);
        } else if (node2.nodeType) {
            return !! this.getAncestorBy(node2,
            function(el) {
                return el == node1;
            });
        }
        return false;
    },
    getAncestorBy: function(node, method) {
        while (node = node.parentNode) {
            if (node && node.nodeType == 1 && (!method || method(node))) {
                return node;
            }
        }
        return null;
    },
    getFirstChild: function(node) {
        node = this.getNode(node);
        if (!node) {
            return null;
        }
        var child = !!node.firstChild && node.firstChild.nodeType == 1 ? node.firstChild: null;
        return child || this.getNextSibling(node.firstChild);
    },
    getNextSibling: function(node) {
        node = this.getNode(node);
        if (!node) {
            return null;
        }
        while (node) {
            node = node.nextSibling;
            if ( !! node && node.nodeType == 1) {
                return node;
            }
        }
        return null;
    },
    getPreviousSibling: function(node) {
        node = this.getNode(node);
        if (!node) {
            return null;
        }
        while (node) {
            node = node.previousSibling;
            if ( !! node && node.nodeType == 1) {
                return node;
            }
        }
        return null;
    },
    swapNode: function(node1, node2) {
        if (node1.swapNode) {
            node1.swapNode(node2);
        } else {
            var parent = node2.parentNode;
            var next = node2.nextSibling;
            if (next == node1) {
                parent.insertBefore(node1, node2);
            } else if (node2 == node1.nextSibling) {
                parent.insertBefore(node2, node1);
            } else {
                node1.parentNode.replaceChild(node2, node1);
                parent.insertBefore(node1, next);
            }
        }
    },
    createElementIn: function(tagName, el, insertFirst, attributes) {
        tagName = tagName || "div";
        el = this.get(el) || document.body;
        var _doc = el.ownerDocument;
        var _e = _doc.createElement(tagName);
        if (attributes) {
            for (var k in attributes) {
                if (/class/.test(k)) {
                    _e.className = attributes[k];
                } else if (/style/.test(k)) {
                    _e.style.cssText = attributes[k];
                } else {
                    _e[k] = attributes[k];
                }
            }
        }
        if (insertFirst) {
            el.insertBefore(_e, el.firstChild);
        } else {
            el.appendChild(_e);
        }
        return _e;
    },
    getStyle: function(el, property) {
        el = this.get(el);
        if (!el || el.nodeType == 9) {
            return null;
        }
        var w3cMode = document.defaultView && document.defaultView.getComputedStyle;
        var computed = !w3cMode ? null: document.defaultView.getComputedStyle(el, '');
        var value = "";
        switch (property) {
        case "float":
            property = w3cMode ? "cssFloat": "styleFloat";
            break;
        case "opacity":
            if (!w3cMode) {
                var val = 100;
                try {
                    val = el.filters['DXImageTransform.Microsoft.Alpha'].opacity;
                } catch(e) {
                    try {
                        val = el.filters('alpha').opacity;
                    } catch(e) {}
                }
                return val / 100;
            } else {
                return parseFloat((computed || el.style)[property]);
            }
            break;
        case "backgroundPositionX":
            if (w3cMode) {
                property = "backgroundPosition";
                return ((computed || el.style)[property]).split(" ")[0];
            }
            break;
        case "backgroundPositionY":
            if (w3cMode) {
                property = "backgroundPosition";
                return ((computed || el.style)[property]).split(" ")[1];
            }
            break;
        }
        if (w3cMode) {
            return (computed || el.style)[property];
        } else {
            return (el.currentStyle[property] || el.style[property]);
        }
    },
    setStyle: function(el, property, value) {
        el = this.get(el);
        if (!el || el.nodeType == 9) {
            return false;
        }
        var w3cMode = document.defaultView && document.defaultView.getComputedStyle;
        switch (property) {
        case "float":
            property = w3cMode ? "cssFloat": "styleFloat";
            el.style[property] = value;
            return true;
            break;
        case "opacity":
            if (!w3cMode) {
                if (value >= 1) {
                    el.style.filter = "";
                    return;
                }
                el.style.filter = 'alpha(opacity=' + (value * 100) + ')';
                return true;
            } else {
                el.style[property] = value;
                return true;
            }
            break;
        case "backgroundPositionX":
            if (w3cMode) {
                var _y = QZFL.dom.getStyle(el, "backgroundPositionY");
                el.style["backgroundPosition"] = value + " " + (_y || "top");
            } else {
                el.style[property] = value;
            }
            break;
        case "backgroundPositionY":
            if (w3cMode) {
                var _x = QZFL.dom.getStyle(el, "backgroundPositionX");
                el.style["backgroundPosition"] = (_x || "left") + " " + value;
            } else {
                el.style[property] = value;
            }
            break;
        default:
            if (typeof el.style[property] == "undefined") {
                return false
            }
            el.style[property] = value;
            return true;
        }
    },
    createNamedElement: function(type, name, doc) {
        doc = doc || document;
        var element;
        try {
            element = doc.createElement('<' + type + ' name="' + name + '">');
        } catch(ignore) {}
        if (!element || !element.name) {
            element = doc.createElement(type);
            element.name = name;
        }
        return element;
    },
    getPosition: function(el) {
        var xy = QZFL.dom.getXY(el),
        size = QZFL.dom.getSize(el);
        return {
            "top": xy[1],
            "left": xy[0],
            "width": size[0],
            "height": size[1]
        };
    },
    setPosition: function(el, pos) {
        QZFL.dom.setXY(el, pos['left'], pos['top']);
        QZFL.dom.setSize(el, pos['width'], pos['height']);
    },
    getXY: function(el, doc) {
        var _t = 0,
        _l = 0;
        doc = doc || document;
        if (el) {
            if (doc.documentElement.getBoundingClientRect && el.getBoundingClientRect) {
                var box = el.getBoundingClientRect(),
                oDoc = el.ownerDocument,
                _fix = QZFL.userAgent.ie ? 2: 0;
                _t = box.top - _fix + QZFL.dom.getScrollTop(oDoc);
                _l = box.left - _fix + QZFL.dom.getScrollLeft(oDoc);
            } else {
                while (el.offsetParent) {
                    _t += el.offsetTop;
                    _l += el.offsetLeft;
                    el = el.offsetParent;
                }
            }
        }
        return [_l, _t];
    },
    getSize: function(el) {
        var _fix = [0, 0];
        QZFL.object.each(["Left", "Right", "Top", "Bottom"],
        function(v) {
            _fix[v == "Left" || v == "Right" ? 0: 1] += (parseInt(QZFL.dom.getStyle(el, "border" + v + "Width"), 10) || 0) + (parseInt(QZFL.dom.getStyle(el, "padding" + v), 10) || 0);
        });
        var _w = el.offsetWidth - _fix[0];
        var _h = el.offsetHeight - _fix[1];
        return [_w, _h];
    },
    setXY: function(el, x, y) {
        el = this.get(el);
        var _ml = parseInt(this.getStyle(el, "marginLeft")) || 0;
        var _mt = parseInt(this.getStyle(el, "marginTop")) || 0;
        this.setStyle(el, "left", parseInt(x) - _ml + "px");
        this.setStyle(el, "top", parseInt(y) - _mt + "px");
    },
    getScrollLeft: function(doc) {
        doc = doc || document;
        return Math.max(doc.documentElement.scrollLeft, doc.body.scrollLeft);
    },
    getScrollTop: function(doc) {
        doc = doc || document;
        return Math.max(doc.documentElement.scrollTop, doc.body.scrollTop);
    },
    getScrollHeight: function(doc) {
        doc = doc || document;
        return Math.max(doc.documentElement.scrollHeight, doc.body.scrollHeight);
    },
    getScrollWidth: function(doc) {
        doc = doc || document;
        return Math.max(doc.documentElement.scrollWidth, doc.body.scrollWidth);
    },
    setScrollLeft: function(value, doc) {
        doc = doc || document;
        doc[doc.compatMode == "CSS1Compat" && !QZFL.userAgent.safari ? "documentElement": "body"].scrollLeft = value;
    },
    setScrollTop: function(value, doc) {
        doc = doc || document;
        doc[doc.compatMode == "CSS1Compat" && !QZFL.userAgent.safari ? "documentElement": "body"].scrollTop = value;
    },
    getClientHeight: function(doc) {
        doc = doc || document;
        return doc.compatMode == "CSS1Compat" ? doc.documentElement.clientHeight: doc.body.clientHeight;
    },
    getClientWidth: function(doc) {
        doc = doc || document;
        return doc.compatMode == "CSS1Compat" ? doc.documentElement.clientWidth: doc.body.clientWidth;
    },
    setSize: function(el, width, height) {
        el = this.get(el);
        var _wFix = /\d+([a-z%]+)/i.exec(width);
        _wFix = _wFix ? _wFix[1] : "";
        var _hFix = /\d+([a-z%]+)/i.exec(height);
        _hFix = _hFix ? _hFix[1] : "";
        this.setStyle(el, "width", (typeof width != "number" || width < 0 || /auto/i.test(width)) ? "auto": (parseInt(width) + (_wFix || "px")));
        this.setStyle(el, "height", (typeof height != "number" || height < 0 || /auto/i.test(height)) ? "auto": (parseInt(height) + (_hFix || "px")));
    },
    getDocumentWindow: function(doc) {
        _doc = doc || document;
        return _doc.parentWindow || _doc.defaultView;
    },
    getElementsByTagNameNS: function(node, ns, tgn) {
        var res = [];
        if (node) {
            if (node.getElementsByTagNameNS) {
                return node.getElementsByTagName(ns + ":" + tgn);
            } else if (node.getElementsByTagName) {
                var n = document.namespaces;
                if (n.length > 0) {
                    var l = node.getElementsByTagName(tgn);
                    for (var i = 0, len = l.length; i < len; ++i) {
                        if (l[i].scopeName == ns) {
                            res.push(l[i]);
                        }
                    }
                }
            }
        }
        return res;
    },
    collection2Array: function(coll) {
        if (isArray(coll)) {
            return coll;
        } else {
            var r = [];
            for (var i = 0, len = coll.length; i < len; ++i) {
                r.push(coll[i]);
            }
        }
        return r;
    },
    getElementByTagNameBubble: function(a, tn) {
        if (!isNode(a)) {
            return null;
        }
        tn += "";
        var maxLv = 31;
        while (a && a.tagName && (a.tagName.toLowerCase() != tn.toLowerCase())) {
            if (a == document.body || (--maxLv) < 0) {
                return null;
            }
            a = a.parentNode;
        }
        return a;
    }
};
var _CN = QZFL.dom.createNamedElement,
$ = QZFL.dom.getById,
removeNode = QZFL.dom.removeElement;
QZFL.event = {
    KEYS: {
        BACKSPACE: 8,
        TAB: 9,
        RETURN: 13,
        ESC: 27,
        SPACE: 32,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40,
        DELETE: 46
    },
    extendType: /(click|mousedown|mouseover|mouseout|mouseup|mousemove|scroll|contextmenu|resize)/i,
    _eventListDictionary: {},
    _fnSeqUID: 0,
    _objSeqUID: 0,
    addEvent: function(obj, eventType, fn, argArray) {
        var cfn = fn,
        res = false,
        l;
        if (!obj) {
            return res;
        }
        if (!obj.eventsListUID) {
            obj.eventsListUID = "e" + (++QZFL.event._objSeqUID);
            QZFL.event._eventListDictionary[obj.eventsListUID] = {};
        }
        l = QZFL.event._eventListDictionary[obj.eventsListUID];
        if (!fn.__elUID) {
            fn.__elUID = "e" + (++QZFL.event._fnSeqUID) + obj.eventsListUID;
        }
        if (!l[eventType]) {
            l[eventType] = {};
        }
        if (typeof(l[eventType][fn.__elUID]) == 'function') {
            return false;
        }
        if (QZFL.event.extendType.test(eventType)) {
            argArray = argArray || [];
            cfn = function(e) {
                return fn.apply(null, ([QZFL.event.getEvent(e)]).concat(argArray));
            };
        }
        if (obj.addEventListener) {
            obj.addEventListener(eventType, cfn, false);
            res = true;
        } else if (obj.attachEvent) {
            res = obj.attachEvent("on" + eventType, cfn);
        } else {
            res = false;
        }
        if (res) {
            l[eventType][fn.__elUID] = cfn;
        }
        return res;
    },
    removeEvent: function(obj, eventType, fn) {
        var cfn = fn,
        res = false,
        l;
        if (!obj) {
            return res;
        }
        if (!cfn) {
            return QZFL.event.purgeEvent(obj, eventType);
        }
        if (!obj.eventsListUID) {
            obj.eventsListUID = "e" + (++QZFL.event._objSeqUID);
            QZFL.event._eventListDictionary[obj.eventsListUID] = {};
        }
        l = QZFL.event._eventListDictionary[obj.eventsListUID];
        if (!fn.__elUID) {
            fn.__elUID = "e" + (++QZFL.event._fnSeqUID) + obj.eventsListUID;
        }
        if (!l[eventType]) {
            l[eventType] = {};
        }
        if (QZFL.event.extendType.test(eventType) && l[eventType] && l[eventType][fn.__elUID]) {
            cfn = l[eventType][fn.__elUID];
        }
        if (obj.removeEventListener) {
            obj.removeEventListener(eventType, cfn, false);
            res = true;
        } else if (obj.detachEvent) {
            obj.detachEvent("on" + eventType, cfn);
            res = true;
        } else {
            return false;
        }
        if (res && l[eventType]) {
            delete l[eventType][fn.__elUID];
        }
        return res;
    },
    purgeEvent: function(obj, type) {
        var l;
        if (obj.eventsListUID && (l = QZFL.event._eventListDictionary[obj.eventsListUID]) && l[type]) {
            for (var k in l[type]) {
                if (obj.removeEventListener) {
                    obj.removeEventListener(type, l[type][k], false);
                } else if (obj.detachEvent) {
                    obj.detachEvent('on' + type, l[type][k]);
                }
            }
        }
        if (obj['on' + type]) {
            obj['on' + type] = null;
        }
        if (l) {
            l[type] = null;
            delete l[type];
        }
        return true;
    },
    getEvent: function(evt) {
        evt = evt || window.event;
        if (!evt && !QZFL.userAgent.ie) {
            var c = this.getEvent.caller,
            cnt = 1;
            while (c) {
                evt = c.arguments[0];
                if (evt && Event == evt.constructor) {
                    break;
                } else if (cnt > 32) {
                    break;
                }
                c = c.caller;
                cnt++;
            }
        }
        return evt;
    },
    getButton: function(evt) {
        var e = QZFL.event.getEvent(evt);
        if (!e) {
            return - 1
        }
        if (QZFL.userAgent.ie) {
            return e.button - Math.ceil(e.button / 2);
        } else {
            return e.button;
        }
    },
    getTarget: function(evt) {
        var e = QZFL.event.getEvent(evt);
        if (e) {
            return e.target || e.srcElement;
        } else {
            return null;
        }
    },
    getCurrentTarget: function(evt) {
        var e = QZFL.event.getEvent(evt);
        if (e) {
            return e.currentTarget || document.activeElement;
        } else {
            return null;
        }
    },
    cancelBubble: function(evt) {
        evt = QZFL.event.getEvent(evt);
        if (!evt) {
            return false
        }
        if (evt.stopPropagation) {
            evt.stopPropagation();
        } else {
            if (!evt.cancelBubble) {
                evt.cancelBubble = true;
            }
        }
    },
    preventDefault: function(evt) {
        evt = QZFL.event.getEvent(evt);
        if (!evt) {
            return false
        }
        if (evt.preventDefault) {
            evt.preventDefault();
        } else {
            evt.returnValue = false;
        }
    },
    mouseX: function(evt) {
        evt = QZFL.event.getEvent(evt);
        return evt.pageX || (evt.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft));
    },
    mouseY: function(evt) {
        evt = QZFL.event.getEvent(evt);
        return evt.pageY || (evt.clientY + (document.documentElement.scrollTop || document.body.scrollTop));
    },
    getRelatedTarget: function(ev) {
        ev = QZFL.event.getEvent(ev);
        var t = ev.relatedTarget;
        if (!t) {
            if (ev.type == "mouseout") {
                t = ev.toElement;
            } else if (ev.type == "mouseover") {
                t = ev.fromElement;
            } else {}
        }
        return t;
    },
    bind: function(obj, method) {
        var args = Array.prototype.slice.call(arguments, 2);
        return function() {
            var _obj = obj || this;
            var _args = args.concat(Array.prototype.slice.call(arguments, 0));
            if (typeof(method) == "string") {
                if (_obj[method]) {
                    return _obj[method].apply(_obj, _args);
                }
            } else {
                return method.apply(_obj, _args);
            }
        }
    }
};
QZFL.event.on = QZFL.event.addEvent;
window.addEvent = QZFL.event.addEvent;
window.removeEvent = QZFL.event.removeEvent;
window.getEvent = QZFL.event.getEvent;
QZFL.selector = {
    query: function(selector, context) {
        context = context || document;
        var _s = QZFL.selector.engine(selector, context);
        return _s;
    }
};; (function() {
    var chunker = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]*['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?/g,
    done = 0,
    toString = Object.prototype.toString,
    hasDuplicate = false;
    var Sizzle = function(selector, context, results, seed) {
        results = results || [];
        var origContext = context = context || document;
        if (context.nodeType !== 1 && context.nodeType !== 9) {
            return [];
        }
        if (!selector || typeof selector !== "string") {
            return results;
        }
        var parts = [],
        m,
        set,
        checkSet,
        check,
        mode,
        extra,
        prune = true,
        contextXML = isXML(context);
        chunker.lastIndex = 0;
        while ((m = chunker.exec(selector)) !== null) {
            parts.push(m[1]);
            if (m[2]) {
                extra = RegExp.rightContext;
                break;
            }
        }
        if (parts.length > 1 && origPOS.exec(selector)) {
            if (parts.length === 2 && Expr.relative[parts[0]]) {
                set = posProcess(parts[0] + parts[1], context);
            } else {
                set = Expr.relative[parts[0]] ? [context] : Sizzle(parts.shift(), context);
                while (parts.length) {
                    selector = parts.shift();
                    if (Expr.relative[selector]) selector += parts.shift();
                    set = posProcess(selector, set);
                }
            }
        } else {
            if (!seed && parts.length > 1 && context.nodeType === 9 && !contextXML && Expr.match.ID.test(parts[0]) && !Expr.match.ID.test(parts[parts.length - 1])) {
                var ret = Sizzle.find(parts.shift(), context, contextXML);
                context = ret.expr ? Sizzle.filter(ret.expr, ret.set)[0] : ret.set[0];
            }
            if (context) {
                var ret = seed ? {
                    expr: parts.pop(),
                    set: makeArray(seed)
                }: Sizzle.find(parts.pop(), parts.length === 1 && (parts[0] === "~" || parts[0] === "+") && context.parentNode ? context.parentNode: context, contextXML);
                set = ret.expr ? Sizzle.filter(ret.expr, ret.set) : ret.set;
                if (parts.length > 0) {
                    checkSet = makeArray(set);
                } else {
                    prune = false;
                }
                while (parts.length) {
                    var cur = parts.pop(),
                    pop = cur;
                    if (!Expr.relative[cur]) {
                        cur = "";
                    } else {
                        pop = parts.pop();
                    }
                    if (pop == null) {
                        pop = context;
                    }
                    Expr.relative[cur](checkSet, pop, contextXML);
                }
            } else {
                checkSet = parts = [];
            }
        }
        if (!checkSet) {
            checkSet = set;
        }
        if (!checkSet) {
            throw "Syntax error, unrecognized expression: " + (cur || selector);
        }
        if (toString.call(checkSet) === "[object Array]") {
            if (!prune) {
                results.push.apply(results, checkSet);
            } else if (context && context.nodeType === 1) {
                for (var i = 0; checkSet[i] != null; i++) {
                    if (checkSet[i] && (checkSet[i] === true || checkSet[i].nodeType === 1 && contains(context, checkSet[i]))) {
                        results.push(set[i]);
                    }
                }
            } else {
                for (var i = 0; checkSet[i] != null; i++) {
                    if (checkSet[i] && checkSet[i].nodeType === 1) {
                        results.push(set[i]);
                    }
                }
            }
        } else {
            makeArray(checkSet, results);
        }
        if (extra) {
            Sizzle(extra, origContext, results, seed);
            Sizzle.uniqueSort(results);
        }
        return results;
    };
    Sizzle.uniqueSort = function(results) {
        if (sortOrder) {
            hasDuplicate = false;
            results.sort(sortOrder);
            if (hasDuplicate) {
                for (var i = 1; i < results.length; i++) {
                    if (results[i] === results[i - 1]) {
                        results.splice(i--, 1);
                    }
                }
            }
        }
    };
    Sizzle.matches = function(expr, set) {
        return Sizzle(expr, null, null, set);
    };
    Sizzle.find = function(expr, context, isXML) {
        var set,
        match;
        if (!expr) {
            return [];
        }
        for (var i = 0, l = Expr.order.length; i < l; i++) {
            var type = Expr.order[i],
            match;
            if ((match = Expr.match[type].exec(expr))) {
                var left = RegExp.leftContext;
                if (left.substr(left.length - 1) !== "\\") {
                    match[1] = (match[1] || "").replace(/\\/g, "");
                    set = Expr.find[type](match, context, isXML);
                    if (set != null) {
                        expr = expr.replace(Expr.match[type], "");
                        break;
                    }
                }
            }
        }
        if (!set) {
            set = context.getElementsByTagName("*");
        }
        return {
            set: set,
            expr: expr
        };
    };
    Sizzle.filter = function(expr, set, inplace, not) {
        var old = expr,
        result = [],
        curLoop = set,
        match,
        anyFound,
        isXMLFilter = set && set[0] && isXML(set[0]);
        while (expr && set.length) {
            for (var type in Expr.filter) {
                if ((match = Expr.match[type].exec(expr)) != null) {
                    var filter = Expr.filter[type],
                    found,
                    item;
                    anyFound = false;
                    if (curLoop == result) {
                        result = [];
                    }
                    if (Expr.preFilter[type]) {
                        match = Expr.preFilter[type](match, curLoop, inplace, result, not, isXMLFilter);
                        if (!match) {
                            anyFound = found = true;
                        } else if (match === true) {
                            continue;
                        }
                    }
                    if (match) {
                        for (var i = 0; (item = curLoop[i]) != null; i++) {
                            if (item) {
                                found = filter(item, match, i, curLoop);
                                var pass = not ^ !!found;
                                if (inplace && found != null) {
                                    if (pass) {
                                        anyFound = true;
                                    } else {
                                        curLoop[i] = false;
                                    }
                                } else if (pass) {
                                    result.push(item);
                                    anyFound = true;
                                }
                            }
                        }
                    }
                    if (found !== undefined) {
                        if (!inplace) {
                            curLoop = result;
                        }
                        expr = expr.replace(Expr.match[type], "");
                        if (!anyFound) {
                            return [];
                        }
                        break;
                    }
                }
            }
            if (expr == old) {
                if (anyFound == null) {
                    throw "Syntax error, unrecognized expression: " + expr;
                } else {
                    break;
                }
            }
            old = expr;
        }
        return curLoop;
    };
    var Expr = Sizzle.selectors = {
        order: ["ID", "NAME", "TAG"],
        match: {
            ID: /#((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
            CLASS: /\.((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
            NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF_-]|\\.)+)['"]*\]/,
            ATTR: /\[\s*((?:[\w\u00c0-\uFFFF_-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/,
            TAG: /^((?:[\w\u00c0-\uFFFF\*_-]|\\.)+)/,
            CHILD: /:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/,
            POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/,
            PSEUDO: /:((?:[\w\u00c0-\uFFFF_-]|\\.)+)(?:\((['"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/
        },
        attrMap: {
            "class": "className",
            "for": "htmlFor"
        },
        attrHandle: {
            href: function(elem) {
                return elem.getAttribute("href");
            }
        },
        relative: {
            "+": function(checkSet, part, isXML) {
                var isPartStr = typeof part === "string",
                isTag = isPartStr && !/\W/.test(part),
                isPartStrNotTag = isPartStr && !isTag;
                if (isTag && !isXML) {
                    part = part.toUpperCase();
                }
                for (var i = 0, l = checkSet.length, elem; i < l; i++) {
                    if ((elem = checkSet[i])) {
                        while ((elem = elem.previousSibling) && elem.nodeType !== 1) {}
                        checkSet[i] = isPartStrNotTag || elem && elem.nodeName === part ? elem || false: elem === part;
                    }
                }
                if (isPartStrNotTag) {
                    Sizzle.filter(part, checkSet, true);
                }
            },
            ">": function(checkSet, part, isXML) {
                var isPartStr = typeof part === "string";
                if (isPartStr && !/\W/.test(part)) {
                    part = isXML ? part: part.toUpperCase();
                    for (var i = 0, l = checkSet.length; i < l; i++) {
                        var elem = checkSet[i];
                        if (elem) {
                            var parent = elem.parentNode;
                            checkSet[i] = parent.nodeName === part ? parent: false;
                        }
                    }
                } else {
                    for (var i = 0, l = checkSet.length; i < l; i++) {
                        var elem = checkSet[i];
                        if (elem) {
                            checkSet[i] = isPartStr ? elem.parentNode: elem.parentNode === part;
                        }
                    }
                    if (isPartStr) {
                        Sizzle.filter(part, checkSet, true);
                    }
                }
            },
            "": function(checkSet, part, isXML) {
                var doneName = done++,
                checkFn = dirCheck;
                if (!/\W/.test(part)) {
                    var nodeCheck = part = isXML ? part: part.toUpperCase();
                    checkFn = dirNodeCheck;
                }
                checkFn("parentNode", part, doneName, checkSet, nodeCheck, isXML);
            },
            "~": function(checkSet, part, isXML) {
                var doneName = done++,
                checkFn = dirCheck;
                if (typeof part === "string" && !/\W/.test(part)) {
                    var nodeCheck = part = isXML ? part: part.toUpperCase();
                    checkFn = dirNodeCheck;
                }
                checkFn("previousSibling", part, doneName, checkSet, nodeCheck, isXML);
            }
        },
        find: {
            ID: function(match, context, isXML) {
                if (typeof context.getElementById !== "undefined" && !isXML) {
                    var m = context.getElementById(match[1]);
                    return m ? [m] : [];
                }
            },
            NAME: function(match, context, isXML) {
                if (typeof context.getElementsByName !== "undefined") {
                    var ret = [],
                    results = context.getElementsByName(match[1]);
                    for (var i = 0, l = results.length; i < l; i++) {
                        if (results[i].getAttribute("name") === match[1]) {
                            ret.push(results[i]);
                        }
                    }
                    return ret.length === 0 ? null: ret;
                }
            },
            TAG: function(match, context) {
                return context.getElementsByTagName(match[1]);
            }
        },
        preFilter: {
            CLASS: function(match, curLoop, inplace, result, not, isXML) {
                match = " " + match[1].replace(/\\/g, "") + " ";
                if (isXML) {
                    return match;
                }
                for (var i = 0, elem; (elem = curLoop[i]) != null; i++) {
                    if (elem) {
                        if (not ^ (elem.className && (" " + elem.className + " ").indexOf(match) >= 0)) {
                            if (!inplace) result.push(elem);
                        } else if (inplace) {
                            curLoop[i] = false;
                        }
                    }
                }
                return false;
            },
            ID: function(match) {
                return match[1].replace(/\\/g, "");
            },
            TAG: function(match, curLoop) {
                for (var i = 0; curLoop[i] === false; i++) {}
                return curLoop[i] && isXML(curLoop[i]) ? match[1] : match[1].toUpperCase();
            },
            CHILD: function(match) {
                if (match[1] == "nth") {
                    var test = /(-?)(\d*)n((?:\+|-)?\d*)/.exec(match[2] == "even" && "2n" || match[2] == "odd" && "2n+1" || !/\D/.test(match[2]) && "0n+" + match[2] || match[2]);
                    match[2] = (test[1] + (test[2] || 1)) - 0;
                    match[3] = test[3] - 0;
                }
                match[0] = done++;
                return match;
            },
            ATTR: function(match, curLoop, inplace, result, not, isXML) {
                var name = match[1].replace(/\\/g, "");
                if (!isXML && Expr.attrMap[name]) {
                    match[1] = Expr.attrMap[name];
                }
                if (match[2] === "~=") {
                    match[4] = " " + match[4] + " ";
                }
                return match;
            },
            PSEUDO: function(match, curLoop, inplace, result, not) {
                if (match[1] === "not") {
                    if (chunker.exec(match[3]).length > 1 || /^\w/.test(match[3])) {
                        match[3] = Sizzle(match[3], null, null, curLoop);
                    } else {
                        var ret = Sizzle.filter(match[3], curLoop, inplace, true ^ not);
                        if (!inplace) {
                            result.push.apply(result, ret);
                        }
                        return false;
                    }
                } else if (Expr.match.POS.test(match[0]) || Expr.match.CHILD.test(match[0])) {
                    return true;
                }
                return match;
            },
            POS: function(match) {
                match.unshift(true);
                return match;
            }
        },
        filters: {
            enabled: function(elem) {
                return elem.disabled === false && elem.type !== "hidden";
            },
            disabled: function(elem) {
                return elem.disabled === true;
            },
            checked: function(elem) {
                return elem.checked === true;
            },
            selected: function(elem) {
                elem.parentNode.selectedIndex;
                return elem.selected === true;
            },
            parent: function(elem) {
                return !! elem.firstChild;
            },
            empty: function(elem) {
                return ! elem.firstChild;
            },
            has: function(elem, i, match) {
                return !! Sizzle(match[3], elem).length;
            },
            header: function(elem) {
                return /h\d/i.test(elem.nodeName);
            },
            text: function(elem) {
                return "text" === elem.type;
            },
            radio: function(elem) {
                return "radio" === elem.type;
            },
            checkbox: function(elem) {
                return "checkbox" === elem.type;
            },
            file: function(elem) {
                return "file" === elem.type;
            },
            password: function(elem) {
                return "password" === elem.type;
            },
            submit: function(elem) {
                return "submit" === elem.type;
            },
            image: function(elem) {
                return "image" === elem.type;
            },
            reset: function(elem) {
                return "reset" === elem.type;
            },
            button: function(elem) {
                return "button" === elem.type || elem.nodeName.toUpperCase() === "BUTTON";
            },
            input: function(elem) {
                return /input|select|textarea|button/i.test(elem.nodeName);
            }
        },
        setFilters: {
            first: function(elem, i) {
                return i === 0;
            },
            last: function(elem, i, match, array) {
                return i === array.length - 1;
            },
            even: function(elem, i) {
                return i % 2 === 0;
            },
            odd: function(elem, i) {
                return i % 2 === 1;
            },
            lt: function(elem, i, match) {
                return i < match[3] - 0;
            },
            gt: function(elem, i, match) {
                return i > match[3] - 0;
            },
            nth: function(elem, i, match) {
                return match[3] - 0 == i;
            },
            eq: function(elem, i, match) {
                return match[3] - 0 == i;
            }
        },
        filter: {
            PSEUDO: function(elem, match, i, array) {
                var name = match[1],
                filter = Expr.filters[name];
                if (filter) {
                    return filter(elem, i, match, array);
                } else if (name === "contains") {
                    return (elem.textContent || elem.innerText || "").indexOf(match[3]) >= 0;
                } else if (name === "not") {
                    var not = match[3];
                    for (i = 0, l = not.length; i < l; i++) {
                        if (not[i] === elem) {
                            return false;
                        }
                    }
                    return true;
                }
            },
            CHILD: function(elem, match) {
                var type = match[1],
                node = elem;
                switch (type) {
                case 'only':
                case 'first':
                    while ((node = node.previousSibling)) {
                        if (node.nodeType === 1) return false;
                    }
                    if (type == 'first') return true;
                    node = elem;
                case 'last':
                    while ((node = node.nextSibling)) {
                        if (node.nodeType === 1) return false;
                    }
                    return true;
                case 'nth':
                    var first = match[2],
                    last = match[3];
                    if (first == 1 && last == 0) {
                        return true;
                    }
                    var doneName = match[0],
                    parent = elem.parentNode;
                    if (parent && (parent.sizcache !== doneName || !elem.nodeIndex)) {
                        var count = 0;
                        for (node = parent.firstChild; node; node = node.nextSibling) {
                            if (node.nodeType === 1) {
                                node.nodeIndex = ++count;
                            }
                        }
                        parent.sizcache = doneName;
                    }
                    var diff = elem.nodeIndex - last;
                    if (first == 0) {
                        return diff == 0;
                    } else {
                        return (diff % first == 0 && diff / first >= 0);
                    }
                }
            },
            ID: function(elem, match) {
                return elem.nodeType === 1 && elem.getAttribute("id") === match;
            },
            TAG: function(elem, match) {
                return (match === "*" && elem.nodeType === 1) || elem.nodeName === match;
            },
            CLASS: function(elem, match) {
                return (" " + (elem.className || elem.getAttribute("class")) + " ").indexOf(match) > -1;
            },
            ATTR: function(elem, match) {
                var name = match[1],
                result = Expr.attrHandle[name] ? Expr.attrHandle[name](elem) : elem[name] != null ? elem[name] : elem.getAttribute(name),
                value = result + "",
                type = match[2],
                check = match[4];
                return result == null ? type === "!=": type === "=" ? value === check: type === "*=" ? value.indexOf(check) >= 0: type === "~=" ? (" " + value + " ").indexOf(check) >= 0: !check ? value && result !== false: type === "!=" ? value != check: type === "^=" ? value.indexOf(check) === 0: type === "$=" ? value.substr(value.length - check.length) === check: type === "|=" ? value === check || value.substr(0, check.length + 1) === check + "-": false;
            },
            POS: function(elem, match, i, array) {
                var name = match[2],
                filter = Expr.setFilters[name];
                if (filter) {
                    return filter(elem, i, match, array);
                }
            }
        }
    };
    var origPOS = Expr.match.POS;
    for (var type in Expr.match) {
        Expr.match[type] = new RegExp(Expr.match[type].source + /(?![^\[]*\])(?![^\(]*\))/.source);
    }
    var makeArray = function(array, results) {
        array = Array.prototype.slice.call(array);
        if (results) {
            results.push.apply(results, array);
            return results;
        }
        return array;
    };
    try {
        Array.prototype.slice.call(document.documentElement.childNodes);
    } catch(e) {
        makeArray = function(array, results) {
            var ret = results || [];
            if (toString.call(array) === "[object Array]") {
                Array.prototype.push.apply(ret, array);
            } else {
                if (typeof array.length === "number") {
                    for (var i = 0, l = array.length; i < l; i++) {
                        ret.push(array[i]);
                    }
                } else {
                    for (var i = 0; array[i]; i++) {
                        ret.push(array[i]);
                    }
                }
            }
            return ret;
        };
    }
    var sortOrder;
    if (document.documentElement.compareDocumentPosition) {
        sortOrder = function(a, b) {
            var ret = a.compareDocumentPosition(b) & 4 ? -1: a === b ? 0: 1;
            if (ret === 0) {
                hasDuplicate = true;
            }
            return ret;
        };
    } else if ("sourceIndex" in document.documentElement) {
        sortOrder = function(a, b) {
            var ret = a.sourceIndex - b.sourceIndex;
            if (ret === 0) {
                hasDuplicate = true;
            }
            return ret;
        };
    } else if (document.createRange) {
        sortOrder = function(a, b) {
            var aRange = a.ownerDocument.createRange(),
            bRange = b.ownerDocument.createRange();
            aRange.selectNode(a);
            aRange.collapse(true);
            bRange.selectNode(b);
            bRange.collapse(true);
            var ret = aRange.compareBoundaryPoints(Range.START_TO_END, bRange);
            if (ret === 0) {
                hasDuplicate = true;
            }
            return ret;
        };
    } (function() {
        var form = document.createElement("div"),
        id = "script" + (new Date).getTime();
        form.innerHTML = "<a name='" + id + "'/>";
        var root = document.documentElement;
        root.insertBefore(form, root.firstChild);
        if ( !! document.getElementById(id)) {
            Expr.find.ID = function(match, context, isXML) {
                if (typeof context.getElementById !== "undefined" && !isXML) {
                    var m = context.getElementById(match[1]);
                    return m ? m.id === match[1] || typeof m.getAttributeNode !== "undefined" && m.getAttributeNode("id").nodeValue === match[1] ? [m] : undefined: [];
                }
            };
            Expr.filter.ID = function(elem, match) {
                var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");
                return elem.nodeType === 1 && node && node.nodeValue === match;
            };
        }
        root.removeChild(form);
        root = form = null;
    })(); (function() {
        var div = document.createElement("div");
        div.appendChild(document.createComment(""));
        if (div.getElementsByTagName("*").length > 0) {
            Expr.find.TAG = function(match, context) {
                var results = context.getElementsByTagName(match[1]);
                if (match[1] === "*") {
                    var tmp = [];
                    for (var i = 0; results[i]; i++) {
                        if (results[i].nodeType === 1) {
                            tmp.push(results[i]);
                        }
                    }
                    results = tmp;
                }
                return results;
            };
        }
        div.innerHTML = "<a href='#'></a>";
        if (div.firstChild && typeof div.firstChild.getAttribute !== "undefined" && div.firstChild.getAttribute("href") !== "#") {
            Expr.attrHandle.href = function(elem) {
                return elem.getAttribute("href", 2);
            };
        }
        div = null;
    })();
    if (document.querySelectorAll)(function() {
        var oldSizzle = Sizzle,
        div = document.createElement("div");
        div.innerHTML = "<p class='TEST'></p>";
        if (div.querySelectorAll && div.querySelectorAll(".TEST").length === 0) {
            return;
        }
        Sizzle = function(query, context, extra, seed) {
            context = context || document;
            if (!seed && context.nodeType === 9 && !isXML(context)) {
                try {
                    return makeArray(context.querySelectorAll(query), extra);
                } catch(e) {}
            }
            return oldSizzle(query, context, extra, seed);
        };
        for (var prop in oldSizzle) {
            Sizzle[prop] = oldSizzle[prop];
        }
        div = null;
    })();
    if (document.getElementsByClassName && document.documentElement.getElementsByClassName)(function() {
        var div = document.createElement("div");
        div.innerHTML = "<div class='test e'></div><div class='test'></div>";
        if (div.getElementsByClassName("e").length === 0) return;
        div.lastChild.className = "e";
        if (div.getElementsByClassName("e").length === 1) return;
        Expr.order.splice(1, 0, "CLASS");
        Expr.find.CLASS = function(match, context, isXML) {
            if (typeof context.getElementsByClassName !== "undefined" && !isXML) {
                return context.getElementsByClassName(match[1]);
            }
        };
        div = null;
    })();
    function dirNodeCheck(dir, cur, doneName, checkSet, nodeCheck, isXML) {
        var sibDir = dir == "previousSibling" && !isXML;
        for (var i = 0, l = checkSet.length; i < l; i++) {
            var elem = checkSet[i];
            if (elem) {
                if (sibDir && elem.nodeType === 1) {
                    elem.sizcache = doneName;
                    elem.sizset = i;
                }
                elem = elem[dir];
                var match = false;
                while (elem) {
                    if (elem.sizcache === doneName) {
                        match = checkSet[elem.sizset];
                        break;
                    }
                    if (elem.nodeType === 1 && !isXML) {
                        elem.sizcache = doneName;
                        elem.sizset = i;
                    }
                    if (elem.nodeName === cur) {
                        match = elem;
                        break;
                    }
                    elem = elem[dir];
                }
                checkSet[i] = match;
            }
        }
    }
    function dirCheck(dir, cur, doneName, checkSet, nodeCheck, isXML) {
        var sibDir = dir == "previousSibling" && !isXML;
        for (var i = 0, l = checkSet.length; i < l; i++) {
            var elem = checkSet[i];
            if (elem) {
                if (sibDir && elem.nodeType === 1) {
                    elem.sizcache = doneName;
                    elem.sizset = i;
                }
                elem = elem[dir];
                var match = false;
                while (elem) {
                    if (elem.sizcache === doneName) {
                        match = checkSet[elem.sizset];
                        break;
                    }
                    if (elem.nodeType === 1) {
                        if (!isXML) {
                            elem.sizcache = doneName;
                            elem.sizset = i;
                        }
                        if (typeof cur !== "string") {
                            if (elem === cur) {
                                match = true;
                                break;
                            }
                        } else if (Sizzle.filter(cur, [elem]).length > 0) {
                            match = elem;
                            break;
                        }
                    }
                    elem = elem[dir];
                }
                checkSet[i] = match;
            }
        }
    }
    var contains = document.compareDocumentPosition ?
    function(a, b) {
        return a.compareDocumentPosition(b) & 16;
    }: function(a, b) {
        return a !== b && (a.contains ? a.contains(b) : true);
    };
    var isXML = function(elem) {
        return elem.nodeType === 9 && elem.documentElement.nodeName !== "HTML" || !!elem.ownerDocument && elem.ownerDocument.documentElement.nodeName !== "HTML";
    };
    var posProcess = function(selector, context) {
        var tmpSet = [],
        later = "",
        match,
        root = context.nodeType ? [context] : context;
        while ((match = Expr.match.PSEUDO.exec(selector))) {
            later += match[0];
            selector = selector.replace(Expr.match.PSEUDO, "");
        }
        selector = Expr.relative[selector] ? selector + "*": selector;
        for (var i = 0, l = root.length; i < l; i++) {
            Sizzle(selector, root[i], tmpSet);
        }
        return Sizzle.filter(later, tmpSet);
    };
    QZFL.selector.engine = Sizzle;
})();; (function() {
    var _el = null;
    QZFL.element = {
        get: function(selector, context) {
            if (selector.nodeType) {
                selector = [selector];
            }
            return new _el(selector, context);
        },
        extend: function(object) {
            QZFL.namespace.extend(_el, object);
        },
        extendFn: function(object) {
            QZFL.namespace.extend(_el.prototype, object);
        },
        getVersion: function() {
            return _el.version;
        }
    }
    QZFL.ElementHandler = function(selector, context) {
        this.elements = null;
        this._isElementHandler = true;
        this._init(selector, context);
    }
    _el = QZFL.ElementHandler;
    _el.prototype = {
        _init: function(selector, context) {
            if (typeof(selector) == "string") {
                this.elements = QZFL.selector.query(selector, context);
            } else {
                this.elements = selector;
            }
        },
        findElements: function(selector) {
            var _pushstack = [];
            this.each(function(el) {
                var _s = QZFL.selector.query(selector, el);
                if (_s.length > 0) {
                    _pushstack = _pushstack.concat(_s);
                }
            });
            return _pushstack;
        },
        find: function(selector) {
            return QZFL.element.get(this.findElements(selector));
        },
        each: function(fn) {
            QZFL.object.each(this.elements, fn);
        },
        concat: function(elements) {
            return QZFL.element.get(this.elements.concat( !! elements._isElementHandler ? elements.elements: elements));
        },
        get: function(index) {
            return QZFL.element.get(this.elements[index]);
        }
    }
    window.$e = QZFL.element.get;
})();
QZFL.element.extend({
    version: "1.0"
});
QZFL.ElementHandler.v QZFL.element.extendFn({
    bind: function(evtType, fn) {
        this.each(function(el) {
            QZFL.event.addEvent(el, evtType, fn);
        });
    },
    unBind: function(evtType, fn) {
        this.each(function(el) {
            QZFL.event.removeEvent(el, evtType, fn);
        });
    },
    onClick: function(fn) {
        this.bind("click", fn);
    },
    onHover: function(fn) {}
});
QZFL.element.extendFn({
    setHtml: function(value) {
        this.setAttr("innerHTML", value);
    },
    getHtml: function(index) {
        var _e = this.elements[index || 0];
        return !! _e ? _e.innerHTML: null;
    },
    setVal: function(value) {
        if (QZFL.object.getType(value) == "array") {
            var _v = "\x00" + value.join("\x00") + "\x00";
            this.each(function(el) {
                if (/radio|checkbox/.test(el.type)) {
                    el.checked = el.nodeType && ("\x00" + _v.indexOf(el.value.toString() + "\x00") > -1 || _v.indexOf("\x00" + el.name.toString() + "\x00") > -1);
                } else if (el.tagName == "SELECT") {
                    QZFL.object.each(el.options,
                    function(e) {
                        e.selected = e.nodeType == 1 && ("\x00" + _v.indexOf(e.value.toString() + "\x00") > -1 || _v.indexOf("\x00" + e.text.toString() + "\x00") > -1);
                    });
                } else {
                    el.value = value;
                }
            })
        } else {
            this.setAttr("value", value);
        }
    },
    getVal: function(index) {
        var _e = this.elements[index || 0],
        _v;
        if (_e) {
            if (_e.tagName == "SELECT") {
                _v = [];
                if (_e.selectedIndex < 0) {
                    return null;
                }
                if (_e.type == "select-one") {
                    _v.push(_e.value);
                } else {
                    QZFL.object.each(_e.options,
                    function(e) {
                        if (e.nodeType == 1 && e.selected) {
                            _v.push(e.value);
                        }
                    });
                }
            } else {
                _v = _e.value;
            }
        } else {
            return null
        }
        return _v;
    },
    addClass: function(className) {
        this.each(function(el) {
            QZFL.css.addClassName(el, className);
        })
    },
    removeClass: function(className) {
        this.each(function(el) {
            QZFL.css.removeClassName(el, className);
        })
    },
    toggleClass: function(className) {
        this.each(function(el) {
            QZFL.css.toggleClassName(el, className);
        })
    },
    getSize: function(index) {
        var _e = this.elements[index || 0];
        return !! _e ? QZFL.dom.getSize(_e) : null;
    },
    getXY: function(index) {
        var _e = this.elements[index || 0];
        return !! _e ? QZFL.dom.getXY(_e) : null;
    },
    setSize: function(width, height) {
        this.each(function(el) {
            QZFL.dom.setSize(el, width, height);
        })
    },
    setXY: function(X, Y) {
        this.each(function(el) {
            QZFL.dom.setXY(el, X, Y);
        })
    },
    hide: function() {
        this.each(function(el) {
            QZFL.dom.setStyle(el, "display", "none");
        })
    },
    show: function() {
        this.each(function(el) {
            QZFL.dom.setStyle(el, "display", "");
        })
    },
    getStyle: function(key, index) {
        var _e = this.elements[index || 0];
        return !! _e ? QZFL.dom.getStyle(_e, key) : null;
    },
    setStyle: function(key, value) {
        this.each(function(el) {
            QZFL.dom.setStyle(el, key, value);
        })
    },
    setAttr: function(key, value) {
        this.each(function(el) {
            el[key] = value;
        })
    },
    getAttr: function(key, index) {
        var _e = this.elements[index || 0];
        return !! _e ? _e[key] : null;
    }
});
QZFL.element.extendFn({
    getPrev: function() {
        var _arr = [];
        this.each(function(el) {
            var _e = QZFL.dom.getPreviousSibling(el);
            _arr.push(_e);
        });
        return QZFL.element.get(_arr);
    },
    getNext: function() {
        var _arr = [];
        this.each(function(el) {
            var _e = QZFL.dom.getNextSibling(el);
            _arr.push(_e);
        });
        return QZFL.element.get(_arr);
    },
    getChildren: function() {
        var _arr = [];
        this.each(function(el) {
            var node = QZFL.dom.getFirstChild(el);
            while (node) {
                if ( !! node && node.nodeType == 1) {
                    _arr.push(node);
                }
                node = node.nextSibling;
            }
        });
        return QZFL.element.get(_arr);
    },
    getParent: function() {
        var _arr = [];
        this.each(function(el) {
            var _e = el.parentNode;
            _arr.push(_e);
        });
        return QZFL.element.get(_arr);
    }
});
QZFL.element.extendFn({
    create: function(tagName, attributes) {
        var _arr = [];
        this.each(function(el) {
            _arr.push(QZFL.dom.createElementIn(tagName, el, false, attributes));
        });
        return QZFL.element.get(_arr);
    },
    appendTo: function(el) {
        var el = (el.elements && el.elements[0]) || QZFL.dom.get(el);
        this.each(function(element) {
            el.appendChild(element)
        });
    },
    insertAfter: function(el) {
        var el = (el.elements && el.elements[0]) || QZFL.dom.get(el),
        _ns = el.nextSibling,
        _p = el.parentNode;
        this.each(function(element) {
            _p[!_ns ? "appendChild": "insertBefore"](element, _ns);
        });
    },
    insertBefore: function(el) {
        var el = (el.elements && el.elements[0]) || QZFL.dom.get(el),
        _p = el.parentNode;
        this.each(function(element) {
            _p.insertBefore(this, element)
        });
    },
    remove: function() {
        this.each(function(el) {
            QZFL.dom.removeElement(el);
        })
    }
});
QZFL.queue = (function() {
    var _o = QZFL.object;
    var _queue = {};
    var _Queue = function(key, queue) {
        if (this instanceof arguments.callee) {
            this._qz_queuekey = key;
            return this;
        }
        if (_o.getType(queue = queue || []) == "array") {
            _queue[key] = queue;
        }
        return new _Queue(key);
    };
    var _extend = {
        push: function(key, fn) {
            fn = this._qz_queuekey ? key: fn;
            _queue[this._qz_queuekey || key].push(fn);
        },
        shift: function(key) {
            var _q = _queue[this._qz_queuekey || key];
            if (_q) {
                return QZFL.queue._exec(_q.shift());
            }
        },
        getLen: function(key) {
            return _queue[this._qz_queuekey || key].length;
        },
        run: function(key) {
            var _q = _queue[this._qz_queuekey || key];
            if (_q) {
                _o.each(_queue[this._qz_queuekey || key], QZFL.queue._exec);
            }
        },
        _exec: function(value, key, source) {
            if (!value || _o.getType(value) != "function") {
                if (_o.getType(key) == "number") {
                    source[key] = null;
                }
                return false;
            }
            try {
                return value();
            } catch(e) {
                QZFL.console.print("QZFL Queue Got An Error: [" + e.name + "]  " + e.message, 1)
            }
        }
    };
    _o.extend(_Queue.prototype, _extend);
    _o.extend(_Queue, _extend);
    return _Queue;
})();
QZFL.util = {
    buildUri: function(s) {
        return QZFL.util.URI(s);
    },
    URI: function(s) {
        if (!QZFL.object.getType(s) == "string") {
            return null;
        }
        if (s.indexOf("://") < 1) {
            s = location.protocol + "//" + location.host + (s.indexOf("/") == 0 ? "": location.pathname.substr(0, location.pathname.lastIndexOf("/") + 1)) + s;
        }
        var depart = s.split("://");
        if (QZFL.object.getType(depart) == "array" && depart.length > 1 && (/^[a-zA-Z]+$/).test(depart[0])) {
            this.protocol = depart[0].toLowerCase();
            var h = depart[1].split("/");
            if (QZFL.object.getType(h) == "array") {
                this.host = h[0];
                this.pathname = "/" + h.slice(1).join("/").replace(/(\?|\#).+/i, "");
                this.href = s;
                var se = depart[1].lastIndexOf("?"),
                ha = depart[1].lastIndexOf("#");
                this.search = (se >= 0) ? depart[1].substring(se) : "";
                this.hash = (ha >= 0) ? depart[1].substring(ha) : "";
                if (this.search.length > 0 && this.hash.length > 0) {
                    if (ha < se) {
                        this.search = "";
                    } else {
                        this.search = depart[1].substring(se, ha);
                    }
                }
                return this;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
QZFL.XHR = function(actionURL, cname, method, data, isAsync, nocache) {
    if (!cname) {
        cname = "_xhrInstence_" + (QZFL.XHR.counter + 1);
    }
    var prot;
    if (QZFL.XHR.instance[cname] instanceof QZFL.XHR) {
        prot = QZFL.XHR.instance[cname];
    } else {
        prot = (QZFL.XHR.instance[cname] = this);
        QZFL.XHR.counter++;
    }
    prot._name = cname;
    prot._nc = !!nocache;
    prot._method = (typeof(method) != "string" || method.toUpperCase() != "GET") ? "POST": (method = "GET");
    prot._isAsync = (!(isAsync === false)) ? true: isAsync;
    prot._uri = actionURL;
    prot._data = (typeof(data) == "object" || typeof(data) == 'string') ? data: {};
    prot._sender = null;
    prot._isHeaderSetted = false;
    prot._xmlQueue = QZFL.queue("xhr" + cname, [function() {
        return new XMLHttpRequest();
    },
    function() {
        return new ActiveXObject("Msxml2.XMLHTTP");
    },
    function() {
        return new ActiveXObject("Microsoft.XMLHTTP");
    }]);
    this.onSuccess = QZFL.emptyFn;
    this.onError = QZFL.emptyFn;
    this.charset = "gb2312";
    this.proxyPath = "";
    return prot;
}
QZFL.XHR.instance = {};
QZFL.XHR.counter = 0;
QZFL.XHR._errCodeMap = {
    400: {
        msg: 'Bad Request'
    },
    401: {
        msg: 'Unauthorized'
    },
    403: {
        msg: 'Forbidden'
    },
    404: {
        msg: 'Not Found'
    },
    999: {
        msg: 'Proxy page error'
    },
    1000: {
        msg: 'Bad Response'
    },
    1001: {
        msg: 'No Network'
    },
    1002: {
        msg: 'No Data'
    },
    1003: {
        msg: 'Eval Error'
    }
};
QZFL.XHR.xsend = function(o, uri) {
    if (! (o instanceof QZFL.XHR)) {
        return false;
    }
    if (ua.firefox && ua.firefox < 3) {
        return false;
    }
    function clear(obj) {
        try {
            obj._sender = obj._sender.callback = obj._sender.errorCallback = obj._sender.onreadystatechange = null;
        } catch(ignore) {}
        if (ua.safari || ua.opera) {
            setTimeout('removeNode($("_xsend_frm_' + obj._name + '"))', 50);
        } else {
            removeNode($("_xsend_frm_" + obj._name));
        }
    }
    if (o._sender === null || o._sender === void(0)) {
        var sender = document.createElement("iframe");
        sender.id = "_xsend_frm_" + o._name;
        sender.style.width = sender.style.height = sender.style.borderWidth = "0";
        document.body.appendChild(sender);
        sender.callback = QZFL.event.bind(o,
        function(data) {
            o.onSuccess(data);
            clear(o);
        });
        sender.errorCallback = QZFL.event.bind(o,
        function(num) {
            o.onError(QZFL.XHR._errCodeMap[num]);
            clear(o);
        });
        o._sender = sender;
    }
    var tmp = QZFL.config.gbEncoderPath;
    o.GBEncoderPath = tmp ? tmp: "";
    o._sender.src = uri.protocol + "://" + uri.host + (this.proxyPath ? this.proxyPath: "/xhr_proxy_gbk.html");
    return true;
}
QZFL.XHR.genHttpParamString = function(o) {
    var r = [];
    for (var i in o) {
        r.push(i + "=" + encodeURIComponent(o[i]));
    }
    return r.join("&");
};
QZFL.XHR.prototype.send = function() {
    if (this._method == 'POST' && this._data == null) {
        return false;
    }
    var u = new QZFL.util.URI(this._uri);
    if (u == null) {
        return false;
    }
    this._uri = u.href;
    if (typeof(this._data) == "object") {
        this._data = QZFL.XHR.genHttpParamString(this._data);
    }
    var d = this._data;
    if (this._method == 'GET') {
        this._uri += (this._uri.indexOf("?") < 0) ? ("?" + d) : ("&" + d);
        d = null;
    }
    if (u.host != location.host) {
        return QZFL.XHR.xsend(this, u);
    }
    if (this._sender === null || this._sender === void(0)) {
        var sender = (function() {
            if (!this._xmlQueue.getLen()) {
                return null;
            }
            var _xhr = this._xmlQueue.shift();
            if (_xhr) {
                return _xhr;
            } else {
                return arguments.callee.call(this);
            }
        }).call(this);
        if (!sender) {
            return false;
        }
        this._sender = sender;
    }
    try {
        this._sender.open(this._method, this._uri, this._isAsync);
    } catch(err) {
        return false;
    }
    if (this._method == 'POST' && !this._isHeaderSetted) {
        this._sender.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        this._isHeaderSetted = true;
    }
    if (this._nc) {
        this._sender.setRequestHeader('If-Modified-Since', 'Thu, 1 Jan 1970 00:00:00 GMT');
        this._sender.setRequestHeader('Cache-Control', 'no-cache');
    }
    this._sender.onreadystatechange = QZFL.event.bind(this,
    function() {
        try {
            if (this._sender.readyState == 4) {
                if (this._sender.status >= 200 && this._sender.status < 300) {
                    this.onSuccess({
                        text: this._sender.responseText,
                        xmlDom: this._sender.responseXML
                    });
                } else {
                    if (ua.safari && (typeof(this._sender.status) == 'undefined')) {
                        this.onError(QZFL.XHR._errCodeMap[1002]);
                    } else {
                        this.onError(QZFL.XHR._errCodeMap[this._sender.status]);
                    }
                }
                delete this._sender;
                this._sender = null;
            }
        } catch(err) {}
    });
    this._sender.send(d);
    return true;
};
QZFL.XHR.prototype.destroy = function() {
    var n = this._name;
    delete QZFL.XHR.instance[n]._sender;
    QZFL.XHR.instance[n]._sender = null;
    delete QZFL.XHR.instance[n];
    QZFL.XHR.counter--;
    return null;
};
QZFL.FormSender = function(actionURL, method, data, charset) {
    this.name = "_fpInstence_" + QZFL.FormSender.counter;
    QZFL.FormSender.instance[this.name] = this;
    QZFL.FormSender.counter++;
    this.method = method || "POST";
    this.uri = actionURL;
    this.data = (typeof(data) == "object" || typeof(data) == 'string') ? data: null;
    this.proxyURL = (typeof(charset) == 'string' && charset.toUpperCase() == "UTF-8") ? QZFL.config.FSHelperPage.replace(/_gbk/, "_utf8") : QZFL.config.FSHelperPage;
    this._sender = null;
    this.onSuccess = QZFL.emptyFn;
    this.onError = QZFL.emptyFn;
};
QZFL.FormSender.instance = {};
QZFL.FormSender.counter = 0;
QZFL.FormSender._errCodeMap = {
    999: {
        msg: 'Connection or Server error'
    }
};
QZFL.FormSender.prototype.send = function() {
    if (this.method == 'POST' && this.data == null) {
        return false;
    }
    function clear(o) {
        o._sender = o._sender.callback = o._sender.errorCallback = o._sender.onreadystatechange = null;
        if (ua.safari || ua.opera) {
            setTimeout('removeNode($("_fp_frm_' + o.name + '"))', 50);
        } else {
            removeNode($("_fp_frm_" + o.name));
        }
    }
    if (this._sender === null || this._sender === void(0)) {
        var sender = document.createElement("iframe");
        sender.id = "_fp_frm_" + this.name;
        sender.style.width = sender.style.height = sender.style.borderWidth = "0";
        sender.style.display = "none";
        document.body.appendChild(sender);
        sender.callback = QZFL.event.bind(this,
        function(o) {
            clearInterval(interval);
            clear(this);
            this.onSuccess(o);
        });
        sender.errorCallback = QZFL.event.bind(this,
        function(o) {
            clearInterval(interval);
            clear(this);
            this.onError(o);
        });
        if (typeof sender.onreadystatechange != 'undefined') {
            sender.onreadystatechange = QZFL.event.bind(this,
            function() {
                if (this._sender.readyState == 'complete' && this._sender.submited) {
                    clear(this);
                    this.onError(QZFL.FormSender._errCodeMap[999]);
                }
            });
        } else {
            var interval = setInterval(QZFL.event.bind(this,
            function() {
                try {
                    var _t = this._sender.contentWindow.location.href;
                    if (_t.indexOf(this.uri) == 0) {
                        clear(this);
                        this.onError(QZFL.FormSender._errCodeMap[999]);
                        clearInterval(interval);
                    }
                } catch(err) {
                    clear(this);
                    this.onError(QZFL.FormSender._errCodeMap[999]);
                    clearInterval(interval);
                }
            }), 100);
        }
        this._sender = sender;
    }
    this._sender.src = this.proxyURL;
    return true;
};
QZFL.FormSender.prototype.destroy = function() {
    var n = this.name;
    delete QZFL.FormSender.instance[n]._sender;
    QZFL.FormSender.instance[n]._sender = null;
    delete QZFL.FormSender.instance[n];
    QZFL.FormSender.counter--;
    return null;
};
QZFL.JsLoader = function(isDebug) {
    this.loaded = false;
    this.debug = isDebug || (QZFL.config.debugLevel > 1);
    this.onload = QZFL.emptyFn;
    this.onerror = QZFL.emptyFn;
}
QZFL.JsLoader.scriptId = 1;
QZFL.JsLoader.prototype.load = function(src, doc, charset) {
    var sId = QZFL.JsLoader.scriptId;
    QZFL.JsLoader.scriptId++;
    var o = this;
    setTimeout(function() {
        o._load2.apply(o, [sId, src, doc, charset]);
        o = null;
    },
    0);
}
QZFL.JsLoader.prototype._load2 = function(sId, src, doc, charset) {
    _doc = doc || document;
    charset = charset || "gb2312";
    var _ie = QZFL.userAgent.ie,
    _js = _doc.createElement("script");
    QZFL.event.addEvent(_js, (_ie ? "readystatechange": "load"), (function(o) {
        return (function() {
            if (_ie) {
                if (_js && !(_js.readyState == "complete" || _js.readyState == "loaded")) {
                    return;
                }
            }
            o.onload();
            if (!o.debug) {
                QZFL.dom.removeElement(_js);
            }
            _js = null;
        });
    })(this));
    if (!_ie) {
        QZFL.event.addEvent(_js, "error", (function(o) {
            return (function() {
                o.onerror();
                if (!o.debug) {
                    QZFL.dom.removeElement(_js);
                }
                _js = null;
            });
        })(this));
    }
    _js.id = "js_" + sId;
    _js.defer = true;
    _js.charset = charset;
    _js.src = src;
    _doc.getElementsByTagName("head")[0].appendChild(_js);
};
QZFL["js" + "Loader"] = QZFL.JsLoader;
QZFL.JSONGetter = function(actionURL, cname, data, charset, junctionMode) {
    if (typeof(cname) != "string") {
        cname = "_jsonInstence_" + (QZFL.JSONGetter.counter + 1);
    }
    var prot;
    if (QZFL.JSONGetter.instance[cname] instanceof QZFL.JSONGetter) {
        prot = QZFL.JSONGetter.instance[cname];
    } else {
        prot = (QZFL.JSONGetter.instance[cname] = this);
        QZFL.JSONGetter.counter++;
    }
    prot._name = cname;
    prot._uri = actionURL;
    prot._data = (typeof(data) == "object" || typeof(data) == "string") ? data: null;
    prot._sender = null;
    prot._charset = (typeof(charset) != 'string') ? QZFL.config.defaultDataCharacterSet: charset;
    prot._jMode = !!junctionMode;
    prot._timer = null;
    this.onSuccess = QZFL.emptyFn;
    this.onError = QZFL.emptyFn;
    this.onTimeout = QZFL.emptyFn;
    this.timeout = 5000;
    this.clear = QZFL.emptyFn;
    return prot;
};
QZFL.JSONGetter.instance = {};
QZFL.JSONGetter.counter = 0;
QZFL.JSONGetter._errCodeMap = {
    999: {
        msg: 'Connection or Server error.'
    },
    998: {
        msg: 'Connection to Server timeout.'
    }
};
QZFL.JSONGetter.genHttpParamString = function(o) {
    var r = [];
    for (var i in o) {
        r.push(i + "=" + encodeURIComponent(o[i]));
    }
    return r.join("&");
};
QZFL.JSONGetter.prototype.send = function(callbackFnName) {
    var cfn = (typeof callbackFnName != 'string') ? "callback": callbackFnName,
    clear,
    da = this._uri + (typeof(this._data) == "object" ? ("?" + QZFL.JSONGetter.genHttpParamString(this._data)) : (typeof(this._data) == "string" ? ("?" + this._data) : "")),
    _s = new Date();
    if (this._jMode) {
        window[cfn] = this.onSuccess;
        var _sd = new QZFL.JsLoader();
        _sd.onerror = this.onError;
        _sd.load(da, void(0), this._charset);
        return;
    }
    this._timer = setTimeout(QZFL.event.bind(this,
    function() {
        QZFL.console.print("jsonGetter timeout", 3);
        QZFL.report.receive("QZFL.JSONGetter", 4, this._uri, (new Date()) - _s);
        this.onTimeout();
    }), this.timeout);
    if (ua.ie) {
        if (ua.ie >= 8 && (ua.beta || ua.windows > 6)) {
            var _hf = new ActiveXObject("htmlfile");
            this.clear = clear = function(o) {
                clearTimeout(o._timer);
                if (o._sender) {
                    o._sender.close();
                    o._sender.parentWindow[cfn] = o._sender.parentWindow["errorCallback"] = null;
                    o._sender = null;
                }
            };
            this._sender = _hf;
            var _cb = QZFL.event.bind(this,
            function(o, o1) {
                setTimeout((function(_o) {
                    return (function() {
                        _o.onSuccess(o, o1);
                        clear(_o);
                    })
                })(this), 0);
                QZFL.report.receive("QZFL.JSONGetter", 1, this._uri, (new Date()) - _s);
            });
            var _ecb = QZFL.event.bind(this,
            function() {
                this.onError(QZFL.JSONGetter._errCodeMap[999]);
                QZFL.report.receive("QZFL.JSONGetter", 2, this._uri, (new Date()) - _s);
                clear(this);
            });
            _hf.open();
            _hf.parentWindow[cfn] = function(o) {
                _cb(o);
            };
            _hf.parentWindow["errorCallback"] = _ecb;
            _hf.write("<script src=\"" + da + "\" charset=\"" + this._charset + "\"><\/script><script defer>setTimeout(\"try{errorCallback();}catch(ign){}\",0)<\/script>");
        } else {
            var df = document.createDocumentFragment(),
            sender = df.createElement("script");
            sender.charset = this._charset;
            this._senderDoc = df;
            this._sender = sender;
            this.clear = clear = function(o) {
                clearTimeout(o._timer);
                if (o._sender) {
                    o._sender.onreadystatechange = null;
                }
                o._senderDoc = o._sender = null;
            };
            df[cfn] = QZFL.event.bind(this,
            function(o, o1) {
                this.onSuccess(o, o1);
                QZFL.report.receive("QZFL.JSONGetter", 1, this._uri, (new Date()) - _s);
                clear(this);
            });
            sender.onreadystatechange = QZFL.event.bind(this,
            function() {
                if (this._sender && this._sender.readyState == "loaded") {
                    try {
                        this.onError(QZFL.JSONGetter._errCodeMap[999]);
                        QZFL.report.receive("QZFL.JSONGetter", 2, this._uri, (new Date()) - _s);
                        clear(this);
                    } catch(ignore) {}
                }
            });
            this._sender.src = da;
            df.appendChild(sender);
        }
    } else {
        this.clear = clear = function(o) {
            clearTimeout(o._timer);
            if (o._sender) {
                o._sender.src = "about:blank";
                o._sender = o._sender.callback = o._sender.errorCallback = null;
            }
            if (ua.safari || ua.opera) {
                setTimeout('removeNode($("_JSON_frm_' + o._name + '"))', 50);
            } else {
                removeNode($("_JSON_frm_" + o._name));
            }
        };
        var _cb = QZFL.event.bind(this,
        function(o, o1) {
            this.onSuccess(o, o1);
            clear(this);
            QZFL.report.receive("QZFL.JSONGetter", 1, this._uri, (new Date()) - _s);
        });
        var _ecb = QZFL.event.bind(this,
        function() {
            this.onError(QZFL.JSONGetter._errCodeMap[999]);
            QZFL.report.receive("QZFL.JSONGetter", 2, this._uri, (new Date()) - _s);
            clear(this);
        });
        var frm = document.createElement("iframe");
        frm.id = "_JSON_frm_" + this._name;
        frm.style.width = frm.style.height = frm.style.borderWidth = "0";
        this._sender = frm;
        var _dm = (document.domain == location.host) ? '': 'document.domain="' + document.domain + '";';
        var dout = '<html><head><meta http-equiv="Content-type" content="text/html; charset=' + this._charset + '"/></head><body><script>' + _dm + ';function ' + cfn + '(o,o1){frameElement.callback(o,o1);}<\/script><script charset="' + this._charset + '" src="' + da + '"><\/script><script>setTimeout(frameElement.errorCallback,50);<\/script></body></html>';
        frm.callback = _cb;
        frm.errorCallback = _ecb;
        if (ua.opera || ua.firefox < 3) {
            frm.src = "javascript:'" + dout + "'";
            document.body.appendChild(frm);
        } else {
            document.body.appendChild(frm);
            frm.contentWindow.document.open('text/html');
            frm.contentWindow.document.write(dout);
            frm.contentWindow.document.close();
        }
    }
};
QZFL.JSONGetter.prototype.destroy = function() {
    var n = this._name;
    this.clear(this);
    delete QZFL.JSONGetter.instance[n]._sender;
    QZFL.JSONGetter.instance[n]._sender = null;
    delete QZFL.JSONGetter.instance[n];
    QZFL.JSONGetter.counter--;
    return null;
};
QZFL.enviroment = (function() {
    var _p = {},
    hookPool = {};
    function envGet(kname) {
        return _p[kname];
    }
    function envDel(kname) {
        delete _p[kname];
        return true;
    }
    function envSet(kname, value) {
        if (typeof value == 'undefined') {
            if (typeof kname == 'undefined') {
                return false;
            } else if (! (_p[kname] === undefined)) {
                return false;
            }
        } else {
            _p[kname] = value;
            return true;
        }
    }
    return {
        get: envGet,
        set: envSet,
        del: envDel,
        hookPool: hookPool
    };
})();
var ENV = QZFL.enviroment;
QZFL.pageEvents = (function() {
    function _ihp() {
        var qs = location.search.substring(1),
        qh = location.hash.substring(1),
        s,
        h,
        n;
        ENV.set("_queryString", qs);
        ENV.set("_queryHash", qh);
        ENV.set("queryString", s = QZFL.util.splitHttpParamString(qs));
        ENV.set("queryHash", h = QZFL.util.splitHttpParamString(qh));
        if (s && s.DEBUG) {
            n = parseInt(s.DEBUG, 10);
            if (!isNaN(n)) {
                QZFL.config.debugLevel = n;
            }
        }
    }
    function _bootStrap() {
        if (document.addEventListener) {
            if (ua.safari) {
                var interval = setInterval(function() {
                    if ((/loaded|complete/).test(document.readyState)) {
                        _onloadHook();
                        clearInterval(interval);
                    }
                },
                50);
            } else {
                document.addEventListener("DOMContentLoaded", _onloadHook, true);
            }
        } else {
            var src = 'javascript:void(0)';
            if (window.location.protocol == 'https:') {
                src = '//:';
            }
            document.write('<script onreadystatechange="if(this.readyState==\'complete\'){this.parentNode.removeChild(this);QZFL.pageEvents._onloadHook();}" defer="defer" src="' + src + '"><\/script\>');
        }
        window.onload = QZFL.lang.chain(window.onload,
        function() {
            _onloadHook();
            _runHooks('onafterloadhooks');
        });
        window.onbeforeunload = function() {
            return _runHooks('onbeforeunloadhooks');
        };
        window.onunload = QZFL.lang.chain(window.onunload,
        function() {
            _runHooks('onunloadhooks');
        });
    }
    function _onloadHook() {
        _runHooks('onloadhooks');
        QZFL.enviroment.loaded = true;
    }
    function _runHook(handler) {
        try {
            handler();
        } catch(ex) {}
    }
    function _runHooks(hooks) {
        var isbeforeunload = (hooks == 'onbeforeunloadhooks'),
        warn = null,
        hc = window.ENV.hookPool;
        do {
            var h = hc[hooks];
            if (!isbeforeunload) {
                hc[hooks] = null;
            }
            if (!h) {
                break;
            }
            for (var ii = 0; ii < h.length; ii++) {
                if (isbeforeunload) {
                    warn = warn || h[ii]();
                } else {
                    h[ii]();
                }
            }
            if (isbeforeunload) {
                break;
            }
        }
        while (hc[hooks]);
        if (isbeforeunload) {
            if (warn) {
                return warn;
            } else {
                QZFL.enviroment.loaded = false;
            }
        }
    }
    function _addHook(hooks, handler) {
        var c = window.ENV.hookPool; (c[hooks] ? c[hooks] : (c[hooks] = [])).push(handler);
    }
    function _insertHook(hooks, handler, position) {
        var c = window.ENV.hookPool;
        if (typeof(position) == 'number' && position >= 0 && c[hooks]) {
            c[hooks].splice(position, 0, handler);
        } else {
            return false;
        }
    }
    function _lr(handler) {
        QZFL.enviroment.loaded ? _runHook(handler) : _addHook('onloadhooks', handler);
    }
    function _bulr(handler) {
        _addHook('onbeforeunloadhooks', handler);
    }
    function _ulr(handler) {
        _addHook('onunloadhooks', handler);
    }
    function pinit() {
        _bootStrap();
        _ihp();
        ua.adjustBehaviors();
        var _dt = $("__DEBUG_out");
        if (_dt) {
            ENV.set("dout", _dt);
        }
        var __dalert;
        if (!ENV.get("alertConverted")) {
            __dalert = alert;
            eval('var alert=function(msg){if(msg!=undefined){__dalert(msg);return msg;}}');
            ENV.set("alertConverted", true);
        }
        var t = ENV.get("queryHash");
        if (t && t.DEBUG) {
            QZFL.config.debugLevel = 2;
        }
    }
    return {
        onloadRegister: _lr,
        onbeforeunloadRegister: _bulr,
        onunloadRegister: _ulr,
        initHttpParams: _ihp,
        bootstrapEventHandlers: _bootStrap,
        _onloadHook: _onloadHook,
        insertHooktoHooksQueue: _insertHook,
        pageBaseInit: pinit
    };
})();
QZFL.lang = {
    isString: function(o) {
        return QZFL.object.getType(o) == "string";
    },
    isArray: function(o) {
        return QZFL.object.getType(o) == "array";
    },
    isHashMap: function(o) {
        return QZFL.object.getType(o) == "object";
    },
    isNode: function(o) {
        if (typeof(Node) == 'undefined') {
            Node = null;
        }
        try {
            if (!o || !((Node != undefined && o instanceof Node) || o.nodeName)) {
                return false;
            }
        } catch(ignored) {
            return false;
        }
        return true;
    },
    isElement: function(o) {
        return o && o.nodeType == 1;
    },
    isValidXMLdom: function(o) {
        if (!o) {
            return false;
        }
        if (!o.xml) {
            return false;
        }
        if (o.xml == "") {
            return false;
        }
        if (! (/^<\?xml/.test(o.xml))) {
            return false;
        }
        return true;
    },
    arg2arr: function(refArgs, start) {
        if (typeof start == 'undefined') {
            start = 0;
        }
        return Array.prototype.slice.apply(refArgs, [start, refArgs.length]);
    },
    getObjByNameSpace: function(ns, setup) {
        if (typeof(ns) != 'string') {
            return ns;
        }
        var l = ns.split("."),
        r = window;
        try {
            for (var i = 0, len = l.length; i < len; ++i) {
                if (typeof(r[l[i]]) == 'undefined') {
                    if (setup) {
                        r[l[i]] = {};
                    } else {
                        return void(0);
                    }
                }
                r = r[l[i]];
            }
            return r;
        } catch(ignore) {
            return void(0);
        }
    },
    objectClone: function(obj, preventName) {
        if ((typeof obj) == 'object') {
            var res = (QZFL.lang.isArray(obj)) ? [] : {};
            for (var i in obj) {
                if (i != preventName) res[i] = objectClone(obj[i], preventName);
            }
            return res;
        } else if ((typeof obj) == 'function') {
            return (new obj()).constructor;
        }
        return obj;
    },
    obj2str: function(obj) {
        var t,
        sw;
        if ((typeof obj) == 'object') {
            if (obj === null) {
                return 'null';
            }
            sw = QZFL.lang.isArray(obj);
            t = [];
            for (var i in obj) {
                t.push((sw ? "": ("\"" + QZFL.string.escString(i) + "\":")) + obj2str(obj[i]));
            }
            t = t.join();
            return sw ? ("[" + t + "]") : ("{" + t + "}");
        } else if ((typeof obj) == 'function') {
            return '';
        } else if ((typeof obj) == 'undefined') {
            return 'undefined';
        } else if ((typeof obj) == 'number') {
            return obj.toString();
        }
        return ! obj ? "\"\"": ("\"" + QZFL.string.escString(obj) + "\"");
    },
    propertieCopy: function(s, b, propertiSet, notOverWrite) {
        var l = (!propertiSet || typeof(propertiSet) != 'object') ? b: propertiSet;
        s = s || {};
        for (var p in l) {
            if (!notOverWrite || !(p in s)) {
                s[p] = l[p];
            }
        }
        return s;
    },
    tryThese: function() {
        var res;
        for (var ii = 0, len = arguments.length; ii < len; ii++) {
            try {
                res = arguments[ii]();
                return res;
            } catch(ignore) {}
        }
        return res;
    },
    chain: function(u, v) {
        var calls = [];
        for (var ii = 0, len = arguments.length; ii < len; ii++) {
            calls.push(arguments[ii]);
        }
        return (function() {
            for (var ii = 0, len = calls.length; ii < len; ii++) {
                if (calls[ii] && calls[ii].apply(null, arguments) === false) {
                    return false;
                }
            }
            return true;
        });
    },
    uniqueArray: function(arr) {
        var flag = {};
        var index = 0;
        while (index < arr.length) {
            if (flag[arr[index]] == typeof(arr[index])) {
                arr.splice(index, 1);
                continue;
            }
            flag[arr[index].toString()] = typeof(arr[index]); ++index;
        }
        return arr;
    }
};
QZFL.namespace.map(QZFL.lang); (function() {
    QZFL.object.extend(QZFL.string, {
        RegExps: {
            trim: /^\s*|\s*$/g,
            ltrim: /^\s*/g,
            rtrim: /\s*$/g,
            nl2br: /\n/g,
            s2nb: /[\x20]{2}/g,
            URIencode: /[\x09\x0A\x0D\x20\x21-\x29\x2B\x2C\x2F\x3A-\x3F\x5B-\x5E\x60\x7B-\x7E]/g,
            escHTML: {
                re_amp: /&/g,
                re_lt: /</g,
                re_gt: />/g,
                re_apos: /\x27/g,
                re_quot: /\x22/g
            },
            escString: {
                bsls: /\\/g,
                nl: /\n/g,
                rt: /\r/g,
                tab: /\t/g
            },
            restXHTML: {
                re_amp: /&/g,
                re_lt: /</g,
                re_gt: />/g,
                re_apos: /&(?:apos|#0?39);/g,
                re_quot: /"/g
            },
            write: /\{(\d{1,2})(?:\:([xodQqb]))?\}/g,
            isURL: /^(?:ht|f)tp(?:s)?\:\/\/(?:[\w\-\.]+)\.\w+/i,
            cut: /[\x00-\xFF]/,
            getRealLen: {
                r0: /[^\x00-\xFF]/g,
                r1: /[\x00-\xFF]/g
            }
        },
        commonReplace: function(s, p, r) {
            return s.replace(p, r);
        },
        listReplace: function(s, l) {
            if (isHashMap(l)) {
                for (var i in l) {
                    s = (QZFL.string.commonReplace(s, l[i], i) || s);
                }
                return s;
            } else {
                return s;
            }
        },
        trim: function(str) {
            return QZFL.string.commonReplace(str + "", QZFL.string.RegExps.trim, '');
        },
        ltrim: function(str) {
            return QZFL.string.commonReplace(str + "", QZFL.string.RegExps.ltrim, '');
        },
        rtrim: function(str) {
            return QZFL.string.commonReplace(str + "", QZFL.string.RegExps.rtrim, '');
        },
        nl2br: function(str) {
            return QZFL.string.commonReplace(str + "", QZFL.string.RegExps.nl2br, '<br />');
        },
        s2nb: function(str) {
            return QZFL.string.commonReplace(str + "", QZFL.string.RegExps.s2nb, '  ');
        },
        URIencode: function(str) {
            var cc,
            ccc;
            return (str + "").replace(QZFL.string.RegExps.URIencode,
            function(a) {
                if (a == "\x20") {
                    return "+";
                } else if (a == "\x0D") {
                    return "";
                }
                cc = a.charCodeAt(0);
                ccc = cc.toString(16);
                return "%" + ((cc < 16) ? ("0" + ccc) : ccc);
            });
        },
        escHTML: function(str) {
            var t = QZFL.string.RegExps.escHTML;
            return QZFL.string.listReplace((str + ""), {
                '&': t.re_amp,
                '<': t.re_lt,
                '>': t.re_gt,
                ''': t.re_apos,
                '"': t.re_quot
            });
        },
        escString: function(str) {
            var t = QZFL.string.RegExps.escString;
            return QZFL.string.listReplace((str + ""), {
                '\\\\': t.bsls,
                '\\n': t.nl,
                '': t.rt,
                '\\t': t.tab,
                '\\\'': t.re_apos,
                '\\"': t.re_quot
            });
        },
        restHTML: function(str) {
            if (!QZFL.string.restHTML.__utilDiv) {
                QZFL.string.restHTML.__utilDiv = document.createElement("div");
            }
            var t = QZFL.string.restHTML.__utilDiv;
            t.innerHTML = (str + "");
            if (typeof(t.innerText) != 'undefined') {
                return t.innerText;
            } else if (typeof(t.textContent) != 'undefined') {
                return t.textContent;
            } else if (typeof(t.text) != 'undefined') {
                return t.text;
            } else {
                return '';
            }
        },
        restXHTML: function(str) {
            var t = QZFL.string.RegExps.restXHTML;
            return QZFL.string.listReplace((str + ""), {
                '<': t.re_lt,
                '>': t.re_gt,
                '\x27': t.re_apos,
                '\x22': t.re_quot,
                '&': t.re_amp
            });
        },
        write: function(strFormat, someArgs) {
            if (arguments.length < 1 || !isString(strFormat)) {
                return '';
            }
            var rArr = arg2arr(arguments),
            result = rArr.shift(),
            tmp;
            return result.replace(QZFL.string.RegExps.write,
            function(a, b, c) {
                b = parseInt(b, 10);
                if (b < 0 || (typeof rArr[b] == 'undefined')) {
                    return '(n/a)';
                } else {
                    if (!c) {
                        return rArr[b];
                    } else {
                        switch (c) {
                        case 'x':
                            return '0x' + rArr[b].toString(16);
                        case 'o':
                            return 'o' + rArr[b].toString(8);
                        case 'd':
                            return rArr[b].toString(10);
                        case 'Q':
                            return '\x22' + rArr[b].toString(16) + '\x22';
                        case 'q':
                            return '`' + rArr[b].toString(16) + '\x27';
                        case 'b':
                            return '<' + !!rArr[b] + '>';
                        }
                    }
                }
            });
        },
        isURL: function(s) {
            return QZFL.string.RegExps.isURL.test(s);
        },
        customEncode: function(s, type) {
            var r;
            if (typeof type == 'undefined') {
                type = '';
            }
            switch (type.toUpperCase()) {
            case "URICPT":
                r = encodeURIComponent(s);
                break;
            default:
                r = encodeURIComponent(s);
            }
            return r;
        },
        escapeURI: function(s) {
            if (!isString(s)) {
                return '';
            }
            if (window.encodeURIComponent) {
                return encodeURIComponent(s);
            }
            if (window.escape) {
                return escape(s);
            }
        },
        parseXML: function(text) {
            if (window.ActiveXObject) {
                var doc = QZFL.lang.tryThese(function() {
                    return new ActiveXObject('MSXML2.DOMDocument.6.0');
                },
                function() {
                    return new ActiveXObject('MSXML2.DOMDocument.5.0');
                },
                function() {
                    return new ActiveXObject('MSXML2.DOMDocument.4.0');
                },
                function() {
                    return new ActiveXObject('MSXML2.DOMDocument.3.0');
                },
                function() {
                    return new ActiveXObject('MSXML2.DOMDocument');
                },
                function() {
                    return new ActiveXObject('Microsoft.XMLDOM');
                });
                doc.async = "false";
                doc.loadXML(text);
                if (doc.parseError.reason) {
                    return null;
                }
            } else {
                var parser = new DOMParser();
                var doc = parser.parseFromString(text, "text/xml");
                if (doc.documentElement.nodeName == 'parsererror') {
                    return null;
                }
            }
            var x = doc.documentElement;
            return x;
        },
        fillLength: function(s, l, ss, isBack) {
            if (typeof(s) != 'string') {
                s = s.toString();
            }
            if (s.length < l) {
                var res = (1 << (l - s.length)).toString(2).substring(1);
                if (typeof(ss) != 'undefined' && !!ss) {
                    res = res.replace("0", ss.toString()).substring(1);
                }
                return isBack ? (s + res) : (res + s);
            } else {
                return s;
            }
        },
        cut: function(s, bl, tails) {
            if (typeof(s) != 'string') return '';
            if (typeof(tails) == 'undefined') tails = "";
            if (getRealLen(s) <= bl) {
                return s;
            }
            var res = [],
            tmp;
            for (var i = 0, cnt = 0, len = s.length; i < len && cnt < bl; ++i) {
                res.push(tmp = s.charAt(i));
                if (QZFL.string.RegExps.cut.test(tmp)) {
                    cnt++;
                } else {
                    cnt += 2;
                }
            }
            return res.join("") + tails;
        },
        getRealLen: function(s, isUTF8) {
            if (typeof(s) != 'string') {
                return 0;
            }
            if (!isUTF8) {
                return s.replace(QZFL.string.RegExps.getRealLen.r0, "**").length;
            } else {
                var cc = s.replace(QZFL.string.RegExps.getRealLen.r1, "");
                return (s.length - cc.length) + (encodeURI(cc).length / 3);
            }
        }
    })
})();
QZFL.string.timeFormatString = function(s, format, t0) {
    var n,
    _s = QZFL.string.timeFormatString;
    if (!_s._init) {
        _s._dL = ["_ds", "_dm", "_dh", "_dd", "_dM", "_dy"];
        _s.re = /\{([_yYMdhms]{1,2})(\:[\d\w\s]|)\}/g;
        QZFL.object.each([1000, 60, 60, 24, 30, 12],
        function(value, key) {
            _s[_s._dL[key]] = !_s._dL[key - 1] ? value: _s[_s._dL[key - 1]] * value;
        });
        _s._init = true;
    }
    if (typeof(s) == 'number') {
        n = new Date();
        n.setTime(s);
        s = n;
    }
    if (typeof(s) == 'object') {
        try {
            s.getTime();
        } catch(err) {
            return "";
        }
        if (typeof(format) != 'string') {
            return s.toString();
        } else {
            return format.replace(_s.re,
            function(a, b, c) {
                var tmp = _s._fnSplit[b];
                return (typeof(tmp) == "function") ? tmp(s, c, _s, t0) : a;
            });
        }
    }
}
QZFL.string.timeFormatString._fnSplit = {
    'y': function(s, c) {
        var tmp = s.getYear().toString();
        return QZFL.string.fillLength(tmp.substring(tmp.length - 2), 2);
    },
    '_y': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._dy;
        return Math.floor(tmp);
    },
    'Y': function(s, c) {
        return QZFL.string.fillLength(s.getFullYear(), 2);
    },
    'M': function(s, c) {
        return QZFL.string.fillLength(s.getMonth() + 1, 2, c);
    },
    '_M': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._dM;
        return Math.floor(tmp);
    },
    'd': function(s, c) {
        return QZFL.string.fillLength(s.getDate(), 2, c);
    },
    '_d': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._dd;
        return Math.floor(tmp);
    },
    'h': function(s, c) {
        return QZFL.string.fillLength(s.getHours(), 2, c);
    },
    '_h': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._dh;
        return Math.floor(tmp);
    },
    'm': function(s, c) {
        return QZFL.string.fillLength(s.getMinutes(), 2);
    },
    '_m': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._dm;
        return Math.floor(tmp);
    },
    's': function(s, c) {
        return QZFL.string.fillLength(s.getSeconds(), 2);
    },
    '_s': function(s, c, _s, t0) {
        var tmp = Math.abs(s - t0) / _s._ds;
        return Math.floor(tmp);
    }
};
QZFL.string.StringBuilder = function() {
    this._strList = arg2arr(arguments);
};
QZFL.string.StringBuilder.prototype = {
    append: function(str) {
        if (isString(str)) {
            this._strList.push(str.toString());
        }
    },
    insertFirst: function(str) {
        if (isString(str)) {
            this._strList.unshift(str.toString());
        }
    },
    appendArray: function(arr) {
        if (isArray(arr)) {
            this._strList = this._strList.concat(arr);
        }
    },
    toString: function(spliter) {
        return this._strList.join(!spliter ? '': spliter);
    },
    clear: function() {
        this._strList.splice(0, this._strList.length);
    }
};; (function() {
    QZFL.object.extend(QZFL.util, {
        copyToClip: function(text) {
            if (ua.ie) {
                return clipboardData.setData("Text", text);
            } else {
                var o = QZFL.shareObject.getValidSO();
                return o ? o.setClipboard(text) : false;
            }
        },
        evalGlobal: function(js) {
            var obj = document.createElement('script');
            obj.type = 'text/javascript';
            obj.id = "__evalGlobal_" + QZFL.util.evalGlobal._counter;
            try {
                obj.innerHTML = js;
            } catch(e) {
                obj.text = js;
            }
            document.body.appendChild(obj);
            QZFL.util.evalGlobal._counter++;
            setTimeout('removeNode($("' + obj.id + '"));', 50);
        },
        runStyleGlobal: function(st) {
            if (ua.safari) {
                var obj = document.createElement('style');
                obj.type = 'text/css';
                obj.id = "__runStyle_" + QZFL.util.runStyleGlobal._counter;
                try {
                    obj.textContent = st;
                } catch(e) {
                    alert(e.message);
                }
                var h = document.getElementsByTagName("head")[0];
                if (h) {
                    h.appendChild(obj);
                    QZFL.util.runStyleGlobal._counter++;
                }
            } else {
                rt.warn("plz use runStyleGlobal() in Safari!");
            }
        },
        genHttpParamString: function(o) {
            if (QZFL.lang.isHashMap(o)) {
                var r = new QZFL.string.StringBuilder();
                try {
                    for (var i in o) {
                        r.append(i + "=" + QZFL.string.customEncode(o[i], "URICPT"));
                    }
                } catch(ignore) {
                    return '';
                }
                return r.toString("&");
            } else if (typeof(o) == 'string') {
                return o;
            } else {
                return '';
            }
        },
        splitHttpParamString: function(s) {
            return QZFL.util.commonDictionarySplit(s, "&");
        },
        commonDictionarySplit: function(s, esp, vq) {
            if (typeof(esp) == 'undefined') {
                esp = "&";
            }
            if (typeof(vq) == 'undefined') {
                vq = "";
            }
            var re_vq = new RegExp("^" + vq + "|" + vq + "$", "g");
            if (isString(s)) {
                var l = s.split(vq + esp),
                tmp,
                res = {};
                for (var i = 0, len = l.length; i < len; i++) {
                    tmp = l[i].split("=");
                    if (tmp.length > 1) {
                        res[tmp[0]] = (tmp.slice(1).join("=")).replace(re_vq, "");
                    } else {
                        res[l[i]] = true;
                    }
                }
                return res;
            } else {
                return {};
            }
        }
    });
    QZFL.util.evalGlobal._counter = 0;
    QZFL.util.runStyleGlobal._counter = 0;
})();
QZFL.media = {
    _tempImageList: [],
    _flashVersion: null,
    adjustImageSize: function(w, h, trueSrc, callback) {
        var ele = QZFL.event.getTarget();
        if (ua.firefox < 3 && ele === document) {
            ele = QZFL.event.getEvent().currentTarget;
        }
        ele.onload = null;
        var offset,
        _c = QZFL.media._tempImageList;
        _c[offset = _c.length] = new Image();
        _c[offset].onload = (function(mainImg, tempImg, ew, eh) {
            return function() {
                tempImg.onload = null;
                var ow = tempImg.width,
                oh = tempImg.height;
                if (ow / oh > ew / eh) {
                    if (ow > ew) {
                        mainImg.width = ew;
                    }
                } else {
                    if (oh > eh) {
                        mainImg.height = eh;
                    }
                }
                mainImg.src = tempImg.src;
                _c[offset] = null;
                delete _c[offset];
                if (typeof(callback) == 'function') {
                    callback(mainImg, w, h, tempImg, ow, oh);
                }
            };
        })(ele, _c[offset], w, h);
        _c[offset].onerror = function() {
            _c[offset] = null;
            delete _c[offset];
        };
        _c[offset].src = trueSrc;
    },
    getFlashHtml: function(flashArguments, requiredVersion, flashPlayerCID) {
        var _attrs = new QZFL.string.StringBuilder(),
        _params = new QZFL.string.StringBuilder();
        if (typeof(flashPlayerCID) == 'undefined') {
            flashPlayerCID = 'D27CDB6E-AE6D-11cf-96B8-444553540000';
        }
        for (var k in flashArguments) {
            switch (k) {
            case "movie":
                continue;
                break;
            case "id":
            case "name":
            case "width":
            case "height":
            case "style":
                _attrs.append(k + "='" + flashArguments[k] + "' ");
                break;
            default:
                _params.append("<param name='" + ((k == "src") ? "movie": k) + "' value='" + (flashArguments[k]) + "' />");
                _attrs.append(k + "='" + flashArguments[k] + "' ");
            }
        }
        if (requiredVersion && (requiredVersion instanceof QZFL.media.SWFVersion)) {
            var _ver = QZFL.media.getFlashVersion().major;
            var _needVer = requiredVersion.major;
            _attrs.append("codeBase='http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=" + requiredVersion + "' ");
        }
        if (ua.ie) {
            return "<object classid='clsid:" + flashPlayerCID + "' " + _attrs + ">" + _params + "</object>";
        } else {
            return "<embed " + _attrs + " pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed>";
        }
    },
    getWMMHtml: function(wmpArguments, cid) {
        var params = new QZFL.string.StringBuilder();
        var objArgm = new QZFL.string.StringBuilder();
        if (typeof(cid) == 'undefined') {
            cid = "clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6";
        }
        for (var k in wmpArguments) {
            switch (k) {
            case "id":
            case "width":
            case "height":
            case "style":
                objArgm.append(k + '="' + wmpArguments[k] + '" ');
                break;
            case "src":
                objArgm.append(k + '="' + wmpArguments[k] + '" ');
                break;
            default:
                objArgm.append(k + '="' + wmpArguments[k] + '" ');
                params.append('<param name="' + k + '" value="' + wmpArguments[k] + '" />');
            }
        }
        if (wmpArguments["src"]) {
            params.append('<param name="URL" value="' + wmpArguments["src"] + '" />');
        }
        if (ua.ie) {
            return '<object classid="' + cid + '" ' + objArgm + '>' + params + '</object>';
        } else {
            return '<embed ' + objArgm + '></embed>';
        }
    }
}
QZFL.media.SWFVersion = function() {
    var a;
    if (arguments.length > 1) {
        a = arg2arr(arguments);
    } else if (arguments.length == 1) {
        if (typeof(arguments[0]) == "object") {
            a = arguments[0];
        } else if (typeof arguments[0] == 'number') {
            a = [arguments[0]];
        } else {
            a = [];
        }
    } else {
        a = [];
    }
    this.major = parseInt(a[0], 10) || 0;
    this.minor = parseInt(a[1], 10) || 0;
    this.rev = parseInt(a[2], 10) || 0;
    this.add = parseInt(a[3], 10) || 0;
}
QZFL.media.SWFVersion.prototype.toString = function(spliter) {
    return ([this.major, this.minor, this.rev, this.add]).join((typeof spliter == 'undefined') ? ",": spliter);
};
QZFL.media.SWFVersion.prototype.toNumber = function() {
    var se = 0.001;
    return this.major + this.minor * se + this.rev * se * se + this.add * se * se * se;
};
QZFL.media.getFlashVersion = function() {
    if (!QZFL.media._flashVersion) {
        var resv = 0;
        if (navigator.plugins && navigator.mimeTypes.length) {
            var x = navigator.plugins['Shockwave Flash'];
            if (x && x.description) {
                resv = x.description.replace(/(?:[a-z]|[A-Z]|\s)+/, "").replace(/(?:\s+r|\s+b[0-9]+)/, ".").split(".");
            }
        } else {
            try {
                for (var i = (resv = 6), axo = new Object(); axo != null; ++i) {
                    axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash." + i);
                    resv = i;
                }
            } catch(e) {
                if (resv == 6) {
                    resv = 0;
                }
                resv = Math.max(resv - 1, 0);
            }
            try {
                resv = new QZFL.media.SWFVersion(axo.GetVariable("$version").split(" ")[1].split(","));
            } catch(ignore) {}
        }
        if (! (resv instanceof QZFL.media.SWFVersion)) {
            resv = new QZFL.media.SWFVersion(resv);
        }
        if (resv.major < 3) {
            resv.major = 0;
        }
        QZFL.media._flashVersion = resv;
    }
    return QZFL.media._flashVersion;
};
QZFL.media._changeFlashSrc = function(src, installVer, needVer) {
    if (installVer >= 6 && needVer > installVer) {
        src = "http://imgcache.qq.com/qzone/flashinstall.swf";
    }
    return src;
}
var insertFlash = QZFL.media.getFlashHtml;
QZFL.dataCenter = (function() {
    var keyPool = {},
    dataPool = [];
    function _mSave(k, v) {
        dataPool[k] = v;
        return true;
    }
    function _sSave(k, v) {
        var o = QZFL.shareObject.getValidSO();
        if (o) {
            return o.set("_dc_so_" + k, v);
        } else {
            return false;
        }
    }
    function _cSave(k, v) {
        var d = QZFL.config.DCCookieDomain;
        if (d) {
            return o.set("_dc_co_" + k, v, d, "/", 120);
        } else {
            return false;
        }
    }
    function getData(key) {
        var t = keyPool[key];
        var res,
        tmp;
        if (typeof(t) == 'undefined') {
            return t;
        } else {
            t = t[t.length - 1];
            switch (t[1]) {
            case "memory":
                res = dataPool[t[0]];
                break;
            case "soflash":
                {
                    tmp = QZFL.shareObject.getValidSO();
                    if (tmp) {
                        res = tmp.get("_dc_so_" + t[0]);
                    } else tmp = null;
                    break;
                }
            case "cookie":
                {
                    tmp = QZFL.cookie;
                    if (tmp) {
                        res = tmp.get("_dc_co_" + t[0]);
                    } else tmp = null;
                    break;
                }
            default:
                res = dataPool[t[0]];
            }
        }
        return res;
    }
    function deleteData(key) {
        var t = keyPool[key];
        var res,
        tmp;
        if (typeof(t) == 'undefined') {
            return false;
        } else {
            t = t[t.length - 1];
            switch (t[1]) {
            case "memory":
                delete dataPool[t[0]];
                break;
            case "soflash":
                {
                    tmp = QZFL.shareObject.getValidSO();
                    if (tmp) {
                        res = tmp.del("_dc_so_" + key);
                    }
                    break;
                }
            case "cookie":
                {
                    tmp = QZFL.cookie;
                    if (tmp) {
                        res = tmp.del("_dc_co_" + QZFL.config.DCCookieDomain, "/");
                    }
                    break;
                }
            default:
                delete dataPool[t[0]];
            }
        }
        delete keyPool[key];
        return true;
    }
    function saveData(key, value, level) {
        if (arguments.length < 2 || typeof(arguments[0]) != 'string') {
            throw (new Error("必须提供键值对:\nkeyName{String}:value{String/Object}"));
            return false;
        }
        var mapLink = dataPool.length;
        if (typeof(keyPool[key]) == 'undefined') {
            keyPool[key] = [];
        }
        keyPool[key].push([mapLink, level]);
        switch (level) {
        case "memory":
            return _mSave(mapLink, value);
        case "soflash":
            return _sSave(mapLink, value);
        case "cookie":
            return _cSave(mapLink, value);
        default:
            return _mSave(mapLink, value);
        }
        return false;
    }
    return {
        save: saveData,
        get: getData,
        load: getData,
        del: deleteData
    };
})();
QZFL.cookie = {
    set: function(name, value, domain, path, hour) {
        if (hour) {
            var today = new Date();
            var expire = new Date();
            expire.setTime(today.getTime() + 3600000 * hour);
        }
        document.cookie = name + "=" + value + "; " + (hour ? ("expires=" + expire.toGMTString() + "; ") : "") + (path ? ("path=" + path + "; ") : "path=/; ") + (domain ? ("domain=" + domain + ";") : ("domain=" + QZFL.config.domainPrefix + ";"));
        return true;
    },
    get: function(name) {
        var r = new RegExp("(?:^|;+|\\s+)" + name + "=([^;]*)");
        var m = document.cookie.match(r);
        return (!m ? "": m[1]);
    },
    del: function(name, domain, path) {
        document.cookie = name + "=; expires=Mon, 26 Jul 1997 05:00:00 GMT; " + (path ? ("path=" + path + "; ") : "path=/; ") + (domain ? ("domain=" + domain + ";") : ("domain=" + QZFL.config.domainPrefix + ";"));
    }
};
QZFL.shareObject = {};
QZFL.shareObject.create = function(path) {
    if (typeof(path) == 'undefined') {
        path = QZFL.config.defaultShareObject;
    }
    var t = new QZFL.shareObject.DataBase(path);
};
QZFL.shareObject.instance = {};
QZFL.shareObject.refCount = 0;
QZFL.shareObject.getValidSO = function() {
    var cnt = QZFL.shareObject.refCount + 1;
    for (var i = 1; i < cnt; ++i) {
        if (QZFL.shareObject.instance["so_" + i] && QZFL.shareObject.instance["so_" + i]._ready) {
            return QZFL.shareObject.instance["so_" + i];
        }
    }
    return null;
};
QZFL.shareObject.get = function(s) {
    var o = QZFL.shareObject.getValidSO();
    if (o) return o.get(s);
    else return void(0);
};
QZFL.shareObject.set = function(k, v) {
    var o = QZFL.shareObject.getValidSO();
    if (o) return o.set(k, v);
    else return false;
};
QZFL.shareObject.DataBase = function(soUrl) {
    if (QZFL.shareObject.refCount > 0) {
        return QZFL.shareObject.instance["so_1"];
    }
    this._ready = false;
    QZFL.shareObject.refCount++;
    var c = document.createElement("div");
    c.style.marginTop = "-1px";
    document.body.appendChild(c);
    c.innerHTML = QZFL.media.getFlashHtml({
        src: soUrl,
        id: "__so" + QZFL.shareObject.refCount,
        width: 0,
        height: 0,
        allowscriptaccess: "always"
    });
    this.ele = $("__so" + QZFL.shareObject.refCount);
    QZFL.shareObject.instance["so_" + QZFL.shareObject.refCount] = this;
};
QZFL.shareObject.DataBase.prototype.set = function(key, value) {
    if (this._ready) {
        this.ele.set("seed", Math.random());
        this.ele.set(key, value);
        this.ele.flush();
        return true;
    } else {
        return false;
    }
};
QZFL.shareObject.DataBase.prototype.del = function(key) {
    if (this._ready) {
        this.ele.set("seed", Math.random());
        this.ele.set(key, void(0));
        this.ele.flush();
        return true;
    } else {
        return false;
    }
};
QZFL.shareObject.DataBase.prototype.get = function(key) {
    return (this._ready) ? (this.ele.get(key)) : null;
};
QZFL.shareObject.DataBase.prototype.clear = function() {
    if (this._ready) {
        this.ele.clear();
        return true;
    } else {
        return false;
    }
};
QZFL.shareObject.DataBase.prototype.getDataSize = function() {
    if (this._ready) {
        return this.ele.getSize();
    } else {
        return - 1;
    }
};
QZFL.shareObject.DataBase.prototype.load = function(url, succFnName, errFnName, data) {
    if (this._ready) {
        this.ele.load(url, succFnName, errFnName, data);
        return true;
    } else {
        return false;
    }
};
QZFL.shareObject.DataBase.prototype.setReady = function() {
    this._ready = true;
};
function getShareObjectPrefix() {
    QZFL.shareObject.instance["so_" + QZFL.shareObject.refCount].setReady();
    return location.host.split(".")[0]
}
QZFL.shareObject.DataBase.prototype.setClipboard = function(value) {
    if (this._ready && isString(value)) {
        this.ele.setClipboard(value);
        return true;
    } else {
        return false;
    }
};
QZFL.Tween = function(el, property, func, startValue, finishValue, duration) {
    this._func = func || QZFL.transitions.simple;
    this._obj = QZFL.dom.get(el);
    this.isColor = /^#/.test(startValue);
    this._prop = property;
    var reSuffix = /\d+([a-z%]+)/i.exec(startValue);
    this._suffix = reSuffix ? reSuffix[1] : "";
    this._startValue = this.isColor ? 0: parseFloat(startValue);
    this._finishValue = this.isColor ? 100: parseFloat(finishValue);
    if (this.isColor) {
        this._startColor = QZFL.css.convertHexColor(startValue);
        this._finishColor = QZFL.css.convertHexColor(finishValue);
    }
    this._duration = duration || 10;
    this._timeCount = 0;
    this._startTime = 0;
    this._changeValue = this._finishValue - this._startValue;
    this.currentValue = 0;
    this.isPlayed = false;
    this.isLoop = false;
    this.onMotionStart = QZFL.emptyFn;
    this.onMotionChange = QZFL.emptyFn;
    this.onMotionStop = QZFL.emptyFn;
};
QZFL.Tween.prototype.start = function(loop) {
    this._reloadTimer();
    this.isPlayed = true;
    this._runTime();
    this.isLoop = loop ? true: false;
    this.onMotionStart.apply(this);
    return "d"
};
QZFL.Tween.prototype.pause = function() {
    this.isPlayed = false;
};
QZFL.Tween.prototype.stop = function() {
    this.isPlayed = false;
    this._playTime(this._duration + 0.1);
};
QZFL.Tween.prototype._reloadTimer = function() {
    this._startTime = new Date().getTime() - this._timeCount * 1000;
};
QZFL.Tween.prototype._playTime = function(time) {
    var _isEnd = false;
    if (time > this._duration) {
        time = this._duration;
        _isEnd = true;
    }
    var pValue = this._func(time, this._startValue, this._changeValue, this._duration);
    this.currentValue = /(opacity)/i.test(this._prop) ? pValue: Math.round(pValue);
    if (this.isColor) {
        this.currentValue = this.getColor(this._startColor, this._finishColor, pValue);
    }
    var _try2setCSS = QZFL.dom.setStyle(this._obj, this._prop, this.currentValue + this._suffix);
    if (!_try2setCSS) {
        this._obj[this._prop] = this.currentValue + this._suffix;
    }
    this.onMotionChange.apply(this, [this._obj, this._prop, this.currentValue]);
    if (_isEnd) {
        this.isPlayed = false;
        if (this.isLoop) {
            this.isPlayed = true;
            this._reloadTimer();
        }
        this.onMotionStop.apply(this);
        if (window.CollectGarbage) CollectGarbage();
    }
};
QZFL.Tween.prototype._runTime = function() {
    var o = this;
    if (o.isPlayed) {
        o._playTime((new Date().getTime() - this._startTime) / 1000);
        setTimeout(function() {
            o._runTime.apply(o, [])
        },
        0);
    }
};
QZFL.Tween.prototype.getPercent = function() {
    return (this.currentValue - this._startValue) / this._changeValue * 100;
};
QZFL.Tween.prototype.swapValue = function() {
    if (this.isColor) {
        var tempValue = this._startColor.join(",");
        this._startColor = this._finishColor;
        this._finishColor = tempValue.split(",");
    } else {
        var tempValue = this._startValue;
        this._startValue = this._finishValue;
        this._finishValue = tempValue;
        this._changeValue = this._finishValue - this._startValue;
    }
};
QZFL.Tween.prototype.getColor = function(startColor, finishColor, percent) {
    var _sc = startColor;
    var _fc = finishColor;
    var _color = [];
    if (percent > 100) {
        percent = 100;
    }
    if (percent < 0) {
        percent = 0;
    }
    for (var i = 0; i < 3; i++) {
        _color[i] = Math.floor(_sc[i] * 1 + (percent / 100) * (_fc[i] - _sc[i])).toString(16);
        if (_color[i].length < 2) {
            _color[i] = "0" + _color[i];
        }
    }
    return "#" + _color.join("");
};
QZFL.transitions = {
    simple: function(time, startValue, changeValue, duration) {
        return changeValue * time / duration + startValue;
    },
    regularEaseIn: function(t, b, c, d) {
        return c * (t /= d) * t + b;
    },
    regularEaseOut: function(t, b, c, d) {
        return - c * (t /= d) * (t - 2) + b;
    },
    regularEaseInOut: function(t, b, c, d) {
        if ((t /= d / 2) < 1) {
            return c / 2 * t * t + b;
        }
        return - c / 2 * ((--t) * (t - 2) - 1) + b;
    }
}
QZFL.object.extend(QZFL.transitions, {
    backEaseIn: function(t, b, c, d) {
        var s = 1.70158;
        return c * (t /= d) * t * ((s + 1) * t - s) + b;
    },
    backEaseOut: function(t, b, c, d, a, p) {
        var s = 1.70158;
        return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
    },
    backEaseInOut: function(t, b, c, d, a, p) {
        var s = 1.70158;
        if ((t /= d / 2) < 1) {
            return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
        }
        return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
    },
    bounceEaseOut: function(t, b, c, d) {
        if ((t /= d) < (1 / 2.75)) {
            return c * (7.5625 * t * t) + b;
        } else if (t < (2 / 2.75)) {
            return c * (7.5625 * (t -= (1.5 / 2.75)) * t + 0.75) + b;
        } else if (t < (2.5 / 2.75)) {
            return c * (7.5625 * (t -= (2.25 / 2.75)) * t + 0.9375) + b;
        } else {
            return c * (7.5625 * (t -= (2.625 / 2.75)) * t + 0.984375) + b;
        }
    },
    bounceEaseIn: function(t, b, c, d) {
        return c - QZFL.transitions.bounceEaseOut(d - t, 0, c, d) + b;
    },
    bounceEaseInOut: function(t, b, c, d) {
        if (t < d / 2) {
            return QZFL.transitions.bounceEaseIn(t * 2, 0, c, d) * 0.5 + b;
        } else return QZFL.transitions.bounceEaseOut(t * 2 - d, 0, c, d) * 0.5 + c * 0.5 + b;
    },
    strongEaseIn: function(t, b, c, d) {
        return c * (t /= d) * t * t * t * t + b;
    },
    strongEaseOut: function(t, b, c, d) {
        return c * ((t = t / d - 1) * t * t * t * t + 1) + b;
    },
    strongEaseInOut: function(t, b, c, d) {
        if ((t /= d / 2) < 1) {
            return c / 2 * t * t * t * t * t + b;
        }
        return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
    },
    elasticEaseIn: function(t, b, c, d, a, p) {
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * 0.3;
        if (!a || a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        return - (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    },
    elasticEaseOut: function(t, b, c, d, a, p) {
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * 0.3;
        if (!a || a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        return (a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b);
    },
    elasticEaseInOut: function(t, b, c, d, a, p) {
        if (t == 0) {
            return b;
        }
        if ((t /= d / 2) == 2) {
            return b + c;
        }
        if (!p) {
            var p = d * (0.3 * 1.5);
        }
        if (!a || a < Math.abs(c)) {
            var a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        if (t < 1) {
            return - 0.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
        }
        return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p) * 0.5 + c + b;
    }
});
QZFL.template = (function() {
    var version = '1.0';
    var regIndex = /<%=index%>/g;
    var regText = /<%=text%>/g;
    var regAttrGlobal = /<%=@(\w+)%>/g;
    var renderNodeSize = 0;
    var tagTPLBegin = '<!--TPL_BEGIN';
    var tagTPLEnd = 'TPL_END-->';
    var qzflObj = QZFL.object;
    function _isString(o) {
        return qzflObj.getType(o) == 'string';
    };
    function _isArray(o) {
        return qzflObj.getType(o) == 'array';
    };
    function _isHashMap(o) {
        return qzflObj.getType(o) == 'object';
    };
    function _isFunction(o) {
        return qzflObj.getType(o) == 'function';
    };
    function _getAttrVal(objJSON, attr) {
        var retVal = '';
        if (objJSON == null || attr == null) {
            return retVal;
        }
        attr += '';
        if (_isArray(objJSON)) {
            try {
                var arIndex = parseInt(attr, 10);
                if (objJSON[arIndex] != null) {
                    retVal = objJSON[arIndex];
                }
            } catch(e) {
                retVal = '';
            }
        } else if (_isHashMap(objJSON)) {
            if (objJSON[attr] != null) {
                retVal = objJSON[attr];
            }
        } else {
            retVal = objJSON;
        }
        return retVal;
    };
    function _queryJSONPath(objJSON, sPath) {
        if (objJSON == null || !_isString(sPath)) {
            return '';
        }
        var attrList = sPath.split("/");
        var pathNode = ['/'];
        for (var i = 0, len = attrList.length; i < len; i++) {
            if (attrList[i] && _isString(attrList[i])) {
                pathNode.push(attrList[i]);
            }
        }
        if (i == 0) {
            return objJSON;
        }
        var ret_obj = objJSON;
        for (var j = 1, len = pathNode.length; j < len; j++) {
            ret_obj = _getAttrVal(ret_obj, pathNode[j]);
        }
        return ret_obj;
    };
    function _toHashMap(str) {
        var map = {};
        if (/^(\w+:\w+;?)+$/.test(str)) {
            var list = str.split(';');
            var pair;
            for (var i = 0, len = list.length; i < len; i++) {
                pair = list[i].split(':');
                map[pair[0]] = pair[1];
            }
        }
        return map;
    };
    function _eachReplace(templetHTML, dataObject, xLevel, begIndex, endIndex, emptyCb) {
        var stRegPrefix = '<%repeat_' + xLevel + '\\s*(match="[^"]+")\\s*(map="[^"]+")?\\s*(callback="[^"]+")?[^%]*%>';
        var arPrefix = templetHTML.match(new RegExp(stRegPrefix, "g"));
        if (arPrefix == null) {
            alert('找不到匹配<%repeat_' + (xLevel) + '%>');
            return '';
        }
        var r_repeat_match_next_level = new RegExp("<%repeat_" + (xLevel + 1) + "\\s*match=\"([^\"]+)\"[^%]*%>");
        var startPos = 0;
        var endPos = 0;
        var arPrefixLen = arPrefix.length;
        var stPath;
        var objMap;
        var objCallback;
        for (var i = 0; i < arPrefixLen; i++) {
            stPath = arPrefix[i].replace(/^.*match=\"|\".*$/g, '');
            var sMap = '';
            if (arPrefix[i].indexOf('map=') != -1) {
                sMap = arPrefix[i].replace(/^.*map=\"|\".*$/g, '');
            }
            objMap = _toHashMap(sMap);
            var sCallback = '';
            if (arPrefix[i].indexOf('callback=') != -1) {
                sCallback = arPrefix[i].replace(/^.*callback=\"|\".*$/g, '');
            }
            objCallback = _toHashMap(sCallback);
            startPos = templetHTML.indexOf(arPrefix[i]);
            endPos = templetHTML.indexOf("<%_repeat_" + xLevel + "%>", startPos);
            var replaceContent = templetHTML.substring(startPos, endPos + 13);
            startPos += arPrefix[i].length;
            var repeatContent = templetHTML.substring(startPos, endPos);
            var nodes = _queryJSONPath(dataObject, stPath);
            if (nodes == null) {
                return _isFunction(emptyCb) ? emptyCb() : (emptyCb || '');
            }
            var arContent = [];
            var attributes = repeatContent.match(regAttrGlobal) || [];
            if (_isArray(nodes)) {
                var nodeLen = nodes.length;
                var sIndex = 0,
                eIndex = 0;
                if (begIndex == null || endIndex == null) {
                    sIndex = 0;
                    eIndex = (nodeLen - 1 < 0) ? 0: nodeLen - 1;
                } else {
                    sIndex = ((nodeLen - 1) < begIndex) ? (nodeLen - 1) : begIndex;
                    eIndex = ((nodeLen - 1) < endIndex) ? (nodeLen - 1) : endIndex;
                }
                renderNodeSize = eIndex - sIndex + 1;
                for (var j = sIndex; j <= eIndex; j++) {
                    var node = nodes[j];
                    var content = repeatContent;
                    if (repeatContent.match(r_repeat_match_next_level) != null) {
                        content = _eachReplace(repeatContent, node, xLevel + 1);
                    }
                    var sIdx = j;
                    if (objCallback["@index"]) {
                        var fn = objCallback["@index"]; (_isFunction(window[fn])) && (sIdx = window[fn](sIdx, node, nodeLen, renderNodeSize));
                    }
                    var s = content.replace(regIndex, sIdx).replace(regText, node.toString());
                    var attrLen = attributes.length;
                    var attrTag,
                    objTag,
                    attrValue = '',
                    isCallback = false;
                    for (var k = 0; k < attrLen; k++) {
                        objTag = attrTag = attributes[k].replace(/\W/g, ''); (objMap[attrTag]) && (objTag = objMap[attrTag]);
                        attrValue = _queryJSONPath(node, objTag);
                        if (attrValue == null) {
                            attrValue = '';
                        }
                        isCallback = false; (objCallback[attrTag]) && (_isFunction(window[objCallback[attrTag]])) && (isCallback = true, attrValue = window[objCallback[attrTag]](attrValue, node, j));
                        s = s.replace(attributes[k], isCallback ? attrValue: QZFL.string.escHTML(attrValue));
                    }
                    arContent[j] = s;
                }
            } else {
                renderNodeSize = 1;
                var content = repeatContent;
                if (repeatContent.match(r_repeat_match_next_level) != null) {
                    content = _eachReplace(repeatContent, nodes, xLevel + 1);
                }
                var s = content.replace(regText, nodes);
                var attrTag,
                objTag,
                attrValue,
                isCallback = false;
                for (var k = 0, attrLen = attributes.length; k < attrLen; k++) {
                    objTag = attrTag = attributes[k].replace(/\W/g, ''); (objMap[attrTag]) && (objTag = objMap[attrTag]);
                    attrValue = nodes[objTag] != null ? nodes[objTag] : '';
                    isCallback = false; (objCallback[attrTag]) && (_isFunction(window[objCallback[attrTag]])) && (isCallback = true, attrValue = window[objCallback[attrTag]](attrValue, nodes));
                    s = s.replace(attributes[k], isCallback ? attrValue: QZFL.string.escHTML(attrValue));
                }
                arContent[0] = s;
            }
            templetHTML = templetHTML.replace(replaceContent, arContent.join(''));
        }
        return templetHTML;
    };
    function _getTemplate(element) {
        if (element.templateHTML) {
            return element.templateHTML;
        }
        var templateHTML = element.innerHTML || '';
        var idxBegTPL = templateHTML.indexOf(tagTPLBegin);
        if (idxBegTPL >= 0) {
            var idxEndTPL = templateHTML.indexOf(tagTPLEnd);
            if (idxEndTPL >= 0) {
                templateHTML = templateHTML.substring(idxBegTPL + tagTPLBegin.length, idxEndTPL);
            }
        }
        element.templateHTML = templateHTML.replace(/%5b%/ig, "<%").replace(/%%5d/ig, "%>").replace(/\[%/g, "<%").replace(/%\]/g, "%>").replace(/\{%/g, "<%").replace(/%\}/g, "%>").replace(/\<!--%/g, "<%").replace(/%-->/g, "%>");
        return element.templateHTML;
    };
    function show(obj) {
        var element = _isString(obj.element) ? $(obj.element) : obj.element;
        if (!element) {
            alert('页面元素为空');
            return 0;
        }
        var templetHTML = _getTemplate(element);
        if (!_isArray(obj.JSON) && !_isHashMap(obj.JSON)) {
            return 0;
        }
        if (!obj.begIndex && obj.begIndex < 0) {
            obj.begIndex = 0;
        }
        if (!obj.endIndex && obj.endIndex < 0) {
            obj.endIndex = 0;
        }
        _isFunction(obj.begCb) && (obj.begCb());
        element.innerHTML = _eachReplace(templetHTML, obj.JSON, 0, obj.begIndex, obj.endIndex, obj.emptyCb);
        element.style.display = '';
        _isFunction(obj.endCb) && (obj.endCb());
        return renderNodeSize;
    };
    return {
        show: show
    };
})();
QZFL.speedReport = (function() {
    var _REPORT_URL = "http://isdspeed.qq.com/cgi-bin/r.cgi";
    var _FREQUENCY = 1.0;
    var _DELAY_TIME = 2000;
    var _FLAG1_VIP = 169;
    function send(timeArr, webFlag, pageFlag, freq, delayTime, extParam) {
        freq = freq || _FREQUENCY;
        delayTime = delayTime || _DELAY_TIME;
        if (Math.random() >= freq) {
            return;
        }
        var callback = (function(timeArr, webFlag, pageFlag, extParam) {
            return function() {
                var param = [];
                param.push('flag1=' + _FLAG1_VIP);
                param.push('flag2=' + webFlag);
                param.push('flag3=' + pageFlag);
                for (var i = 1, j = timeArr.length; i <= j; i++) {
                    if (timeArr[i]) {
                        param.push(i + "=" + timeArr[i]);
                    }
                }
                extParm = extParam || {};
                for (key in extParm) {
                    param.push(key + "=" + extParm[key]);
                }
                var sender = new Image();
                sender.src = _REPORT_URL + "?" + param.join("&");
            };
        })(timeArr, webFlag, pageFlag, extParam);
        setTimeout(callback, delayTime);
    };
    return {
        WEB_MY: 100,
        WEB_FACE: 101,
        WEB_MAGIC: 102,
        WEB_ECARD: 103,
        WEB_RING: 104,
        WEB_VIP: 105,
        WEB_HAOMA: 106,
        WEB_FAV: 107,
        WEB_FANLI: 108,
        WEB_SHANGHAI: 109,
        WEB_SHOP: 110,
        send: send
    };
})();
var QZFL = QZFL || {};
QZFL.lazyLoad = (function() {
    var map_element = {};
    var element_obj = [];
    var download_count = 0;
    var last_offset = -1;
    var doc_body;
    var doc_element;
    var lazy_load_tag;
    function initVar(tags) {
        doc_body = document.body;
        doc_element = document.compatMode == 'BackCompat' ? doc_body: document.documentElement;
        lazy_load_tag = tags || ["img", "iframe"];
    };
    function initElementMap() {
        var all_element = [];
        for (var i = 0, len = lazy_load_tag.length; i < len; i++) {
            var el = document.getElementsByTagName(lazy_load_tag[i]);
            for (var j = 0, len2 = el.length; j < len2; j++) {
                all_element.push(el[j]);
            }
        }
        for (key in all_element) {
            if (typeof(all_element[key]) == "object" && all_element[key].getAttribute("init_src")) {
                element_obj.push(all_element[key]);
            }
        }
        for (var i = 0, len = element_obj.length; i < len; i++) {
            var o_img = element_obj[i];
            var t_index = getAbsoluteTop(o_img);
            if (map_element[t_index]) {
                map_element[t_index].push(i);
            } else {
                var t_array = [];
                t_array[0] = i;
                map_element[t_index] = t_array;
                download_count++;
            }
        }
    };
    function initDownloadListen() {
        if (!download_count) return;
        var offset = (window.MessageEvent && !document.getBoxObjectFor) ? doc_body.scrollTop: doc_element.scrollTop;
        var visio_offset = offset + doc_element.clientHeight;
        if (last_offset == visio_offset) {
            setTimeout(initDownloadListen, 200);
            return;
        }
        last_offset = visio_offset;
        var visio_height = doc_element.clientHeight;
        var img_show_height = visio_height + offset;
        for (var key in map_element) {
            if (img_show_height > key) {
                var t_o = map_element[key];
                var img_vl = t_o.length;
                for (var l = 0; l < img_vl; l++) {
                    element_obj[t_o[l]].src = element_obj[t_o[l]].getAttribute("init_src");
                }
                delete map_element[key];
                download_count--;
            }
        }
        setTimeout(initDownloadListen, 200);
    };
    function getAbsoluteTop(element) {
        if (arguments.length != 1 || element == null) {
            return null;
        }
        var offsetTop = element.offsetTop;
        while (element = element.offsetParent) {
            offsetTop += element.offsetTop;
        }
        return offsetTop;
    }
    function init(tags) {
        initVar(tags);
        initElementMap();
        initDownloadListen();
    };
    return {
        init: init
    }
})() start_img_download_listener = QZFL.lazyLoad.init;
var QZFL = QZFL || {};
QZFL.quickLogin = (function() {
    var _pt2 = null;
    var _mask = null;
    var _mask_obj = null;
    var _valid = null;
    var _w = null;
    var _h = null;
    function _sc() {
        if (!_valid) return;
        try {
            _pt2.style.top = (window.innerHeight / 2 + pageYOffset) - _h / 2 + "px";
            _pt2.style.left = (window.innerWidth / 2 + pageXOffset) - _w / 2 + "px";
        } catch(e) {
            var _docElement = document.documentElement;
            if (!document.body.scrollTop) {
                _pt2.style.top = (_docElement.offsetHeight / 2 + _docElement.scrollTop) - _h / 2 + "px";
                _pt2.style.left = (_docElement.offsetWidth / 2 + _docElement.scrollLeft) - _w / 2 + "px";
            } else {
                _pt2.style.top = (_docElement.offsetHeight / 2 + document.body.scrollTop) - _h / 2 + "px";
                _pt2.style.left = (_docElement.offsetWidth / 2 + document.body.scrollLeft) - _w / 2 + "px";
            }
        }
        setTimeout(_sc, 500);
    };
    function _ex(s) {
        if (window.location.href.indexOf(s) > -1) {
            return true;
        }
        return false;
    };
    return {
        close: function() {
            if (_pt2) {
                _valid = false;
                _pt2.style.display = "none";
                _mask_obj && (_mask_obj.style.display = "none");
            }
        },
        resize: function(w, h) {
            if (_pt2) {
                _valid = true;
                _w = w;
                _h = h;
                _pt2.style.width = w + "px";
                _pt2.style.height = h + "px";
                _pt2.getElementsByTagName("iframe")[0].style.height = h + "px"
                if (_mask) {
                    if (!_mask_obj) {
                        var n = document.createElement("div");
                        n.id = "_ptlogin2_mask_body";
                        n.style.position = "absolute";
                        n.style.background = "#333333";
                        n.style.opacity = "0.3";
                        n.style.filter = "alpha(opacity=30)";
                        n.style.zIndex = "999";
                        n.style.width = document.body.scrollWidth + "px";
                        n.style.height = document.body.scrollHeight + "px";
                        n.style.top = "0px";
                        n.style.left = "0px";
                        document.getElementsByTagName("body")[0].appendChild(n);
                        _mask_obj = n;
                    } else {
                        _mask_obj.style.display = "block";
                    }
                }
                _sc(w, h);
            }
        },
        open: function(type, u, mask) {
            document.domain = "qq.com";
            if (!u) {
                u = window.location.href;
            }
            if (!_pt2) {
                var n = document.createElement("div");
                n.id = "_ptlogin2_div_container";
                n.style.position = "absolute";
                n.style.zIndex = "1000";
                n.style.width = "1px";
                n.style.height = "1px";
                n.style.top = "1px";
                n.style.left = "1px";
                n.innerHTML = '<iframe frameborder="no" scrolling="no" width="100%" height="100%" src="about:blank"></iframe>';
                document.getElementsByTagName("body")[0].appendChild(n);
                _pt2 = n;
            }
            _pt2.style.display = "block";
            var jump_name = '';
            var appid = '';
            if (_ex('act.vip.qq.com')) {
                jump_name = 'vipact';
                appid = '8000205';
            } else if (_ex('my.qq.com')) {
                jump_name = 'vipmyqq';
                appid = '8000203';
            } else if (_ex('fanli.qq.com') || _ex('jump.fanli.qq.com')) {
                jump_name = 'vipcomm';
                appid = '8000207';
            } else {
                jump_name = 'vipcomm';
                appid = '8000201';
            }
            switch (type) {
            case 2:
                {
                    url = "http://ui.ptlogin2.qq.com/cgi-bin/login?link_target=blank&target=blank&appid=" + appid + "&qlogin_jumpname=" + jump_name + "&f_url=loginerroralert&qlogin_auto_login=0&s_url=" + escape("http://imgcache.qq.com/club/portal_new/redirect.html?jump_url=" + escape(u)) + "&qlogin_param=" + escape("jump_url=" + escape(u));
                }
                break;
            default:
                {
                    url = "http://ui.ptlogin2.qq.com/cgi-bin/login?link_target=blank&target=blank&appid=" + appid + "&f_url=loginerroralert&s_url=" + escape("http://imgcache.qq.com/club/portal_new/redirect.html?jump_url=" + escape(u));
                }
                break;
            }
            _pt2.getElementsByTagName('iframe')[0].src = url;
            if ( !! mask) {
                _mask = true;
            } else {
                _mask = false;
            }
        }
    };
})();
window.openLogin = QZFL.quickLogin.open;
window.ptlogin2_onClose = QZFL.quickLogin.close;
window.ptlogin2_onResize = QZFL.quickLogin.resize;;
if (true) {
    var QQVIP;
    if (typeof(QZFL) == "object") {
        QQVIP = QZFL;
    } else {
        QQVIP = {};
    }
};
/*  |xGv00|f4fdda6521fdfef8309081d2feb9c349 */
