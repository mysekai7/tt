/**
 * class: FunctionCall
 */
if (!window.FunctionCall && window.defaultConfig)
    window.FunctionCall = null;

FunctionCall = function(name) {
    var info = FunctionCall.funcs[name];
    if (!info)
        throw Error(name +' is not a function');

    this.name = name;
    this.func = info[0];
    this.args = [];

    this.datatype = info[1];

    if (info[2]) {
        this.needContextPosition = true;
    }

    this.needContextNodeInfo = info[3];
    this.needContextNode = this.needContextNodeInfo[0]
};

FunctionCall.funcs = {

    // Original Function
    'context-node': [function() {
        if (arguments.length != 0) {
            throw Error('Function context-node expects ()');
        }
        var ns;
        ns = new NodeSet();
        ns.push(this.node);
        return ns;
    }, 'nodeset', false, [true]],

    // Original Function
    'root-node': [function() {
        if (arguments.length != 0) {
            throw Error('Function root-node expects ()');
        }
        var ns, ctxn;
        ns = new NodeSet();
        ctxn = this.node;
        if (ctxn.nodeType == 9)
            ns.push(ctxn);
        else
            ns.push(ctxn.ownerDocument);
        return ns;
    }, 'nodeset', false, []],

    last: [function() {
        if (arguments.length != 0) {
            throw Error('Function last expects ()');
        }
        return this.last;
    }, 'number', true, []],

    position: [function() {
        if (arguments.length != 0) {
            throw Error('Function position expects ()');
        }
        return this.position;
    }, 'number', true, []],

    count: [function(ns) {
        if (arguments.length != 1 || !(ns = ns.evaluate(this)).isNodeSet) {
            throw Error('Function count expects (nodeset)');
        }
        return ns.length;
    }, 'number', false, []],

    id: [function(s) {
        var ids, ns, i, id, elm, ctxn, doc;
        if (arguments.length != 1) {
            throw Error('Function id expects (object)');
        }
        ctxn = this.node;
        if (ctxn.nodeType == 9)
            doc = ctxn;
        else
            doc = ctxn.ownerDocument;
/*@cc_on
        all = doc.all;
@*/
        s = s.string(this);
        ids = s.split(/\s+/);
        ns = new NodeSet();
        for (i = 0, l = ids.length; i < l; i ++) {
            id = ids[i];

/*@cc_on @if (@_jscript)
            elm = all[id];
            if (elm) {
                if ((!elm.length || elm.nodeType) && id == elm.id) {
                    ns.push(elm)
                }
                else if (elm.length) {
                    var elms = elm;
                    for (var j = 0, l0 = elms.length; j < l0; j ++) {
                        var elem = elms[j];
                        if (id == elem.id) {
                            ns.push(elem);
                            break;
                        }
                    }
                }
            }
@else @*/
            elm = doc.getElementById(id);
            if (uai.opera && elm && elm.id != id) {
                var elms = doc.getElementsByName(id);
                for (var j = 0, l0 = elms.length; j < l0; j ++) {
                    elm = elms[j];
                    if (elm.id == id) {
                        ns.push(elm);
                    }
                }
            }
            else {
                if (elm) ns.push(elm)
            }
/*@end @*/

        }
        ns.isSorted = false;
        return ns;
    }, 'nodeset', false, []],

    'local-name': [function(ns) {
        var nd;
        switch (arguments.length) {
            case 0:
                nd = this.node;
                break;
            case 1:
                if ((ns = ns.evaluate(this)).isNodeSet) {
                    nd = ns.first();
                    break;
                }
            default:
                throw Error('Function local-name expects (nodeset?)');
                break;
        }
        return '' + nd.nodeName.toLowerCase();
    }, 'string', false, [true, false]],

    name: [function(ns) {
        // not implemented
        return FunctionCall.funcs['local-name'][0].apply(this, arguments);
    }, 'string', false, [true, false]],

    'namespace-uri': [function(ns) {
        // not implemented
        return '';
    }, 'string', false, [true, false]],

    string: [function(s) {
        switch (arguments.length) {
            case 0:
                s = NodeUtil.to('string', this.node);
                break;
            case 1:
                s = s.string(this);
                break;
            default:
                throw Error('Function string expects (object?)');
                break;
        }
        return s;
    }, 'string', false, [true, false]],

    concat: [function(s1, s2) {
        if (arguments.length < 2) {
            throw Error('Function concat expects (string, string[, ...])');
        }
        for (var t = '', i = 0, l = arguments.length; i < l; i ++) {
            t += arguments[i].string(this);
        }
        return t;
    }, 'string', false, []],

    'starts-with': [function(s1, s2) {
        if (arguments.length != 2) {
            throw Error('Function starts-with expects (string, string)');
        }
        s1 = s1.string(this);
        s2 = s2.string(this);
        return s1.indexOf(s2) == 0;
    }, 'boolean', false, []],

    contains: [function(s1, s2) {
        if (arguments.length != 2) {
            throw Error('Function contains expects (string, string)');
        }
        s1 = s1.string(this);
        s2 = s2.string(this);
        return s1.indexOf(s2) != -1;
    }, 'boolean', false, []],

    substring: [function(s, n1, n2) {
        var a1, a2;
        s = s.string(this);
        n1 = n1.number(this);
        switch (arguments.length) {
            case 2:
                n2 = s.length - n1 + 1;
                break;
            case 3:
                n2 = n2.number(this);
                break;
            default:
                throw Error('Function substring expects (string, string)');
                break;
        }
        n1 = Math.round(n1);
        n2 = Math.round(n2);
        a1 = n1 - 1;
        a2 = n1 + n2 - 1;
        if (a2 == Infinity) {
            return s.substring(a1 < 0 ? 0 : a1);
        }
        else {
            return s.substring(a1 < 0 ? 0 : a1, a2)
        }
    }, 'string', false, []],

    'substring-before': [function(s1, s2) {
        var n;
        if (arguments.length != 2) {
            throw Error('Function substring-before expects (string, string)');
        }
        s1 = s1.string(this);
        s2 = s2.string(this);
        n = s1.indexOf(s2);
        if (n == -1) return '';
        return s1.substring(0, n);
    }, 'string', false, []],

    'substring-after': [function(s1, s2) {
        if (arguments.length != 2) {
            throw Error('Function substring-after expects (string, string)');
        }
        s1 = s1.string(this);
        s2 = s2.string(this);
        var n = s1.indexOf(s2);
        if (n == -1) return '';
        return s1.substring(n + s2.length);
    }, 'string', false, []],

    'string-length': [function(s) {
        switch (arguments.length) {
            case 0:
                s = NodeUtil.to('string', this.node);
                break;
            case 1:
                s = s.string(this);
                break;
            default:
                throw Error('Function string-length expects (string?)');
                break;
        }
        return s.length;
    }, 'number', false, [true, false]],

    'normalize-space': [function(s) {
        switch (arguments.length) {
            case 0:
                s = NodeUtil.to('string', this.node);
                break;
            case 1:
                s = s.string(this);
                break;
            default:
                throw Error('Function normalize-space expects (string?)');
                break;
        }
        return s.replace(/\s+/g, ' ').replace(/^ /, '').replace(/ $/, '');
    }, 'string', false, [true, false]],

    translate: [function(s1, s2, s3) {
        if (arguments.length != 3) {
            throw Error('Function translate expects (string, string, string)');
        }
        s1 = s1.string(this);
        s2 = s2.string(this);
        s3 = s3.string(this);

        var map = [];
        for (var i = 0, l = s2.length; i < l; i ++) {
            var ch = s2.charAt(i);
            if (!map[ch]) map[ch] = s3.charAt(i) || '';
        }
        for (var t = '', i = 0, l = s1.length; i < l; i ++) {
            var ch = s1.charAt(i);
            var replace = map[ch]
            t += (replace != undefined) ? replace : ch;
        }
        return t;
    }, 'string', false, []],

    'boolean': [function(b) {
        if (arguments.length != 1) {
            throw Error('Function boolean expects (object)');
        }
        return b.bool(this)
    }, 'boolean', false, []],

    not: [function(b) {
        if (arguments.length != 1) {
            throw Error('Function not expects (object)');
        }
        return !b.bool(this)
    }, 'boolean', false, []],

    'true': [function() {
        if (arguments.length != 0) {
            throw Error('Function true expects ()');
        }
        return true;
    }, 'boolean', false, []],

    'false': [function() {
        if (arguments.length != 0) {
            throw Error('Function false expects ()');
        }
        return false;
    }, 'boolean', false, []],

    lang: [function(s) {
        // not implemented
        return false;
    }, 'boolean', false, []],

    number: [function(n) {
        switch (arguments.length) {
            case 0:
                n = NodeUtil.to('number', this.node);
                break;
            case 1:
                n = n.number(this);
                break;
            default:
                throw Error('Function number expects (object?)');
                break;
        }
        return n;
    }, 'number', false, [true, false]],

    sum: [function(ns) {
        var nodes, n, i, l;
        if (arguments.length != 1 || !(ns = ns.evaluate(this)).isNodeSet) {
            throw Error('Function sum expects (nodeset)');
        }
        nodes = ns.list();
        n = 0;
        for (i = 0, l = nodes.length; i < l; i ++) {
            n += NodeUtil.to('number', nodes[i]);
        }
        return n;
    }, 'number', false, []],

    floor: [function(n) {
        if (arguments.length != 1) {
            throw Error('Function floor expects (number)');
        }
        n = n.number(this);
        return Math.floor(n);
    }, 'number', false, []],

    ceiling: [function(n) {
        if (arguments.length != 1) {
            throw Error('Function ceiling expects (number)');
        }
        n = n.number(this);
        return Math.ceil(n);
    }, 'number', false, []],

    round: [function(n) {
        if (arguments.length != 1) {
            throw Error('Function round expects (number)');
        }
        n = n.number(this);
        return Math.round(n);
    }, 'number', false, []]
};

FunctionCall.parse = function(lexer) {
    var expr, func = new FunctionCall(lexer.next());
    lexer.next();
    while (lexer.peek() != ')') {
        if (lexer.empty()) {
            throw Error('missing function argument list');
        }
        expr = BinaryExpr.parse(lexer);
        func.arg(expr);
        if (lexer.peek() != ',') break;
        lexer.next();
    }
    if (lexer.empty()) {
        throw Error('unclosed function argument list');
    }
    if (lexer.next() != ')') {
        lexer.back();
        throw Error('bad token: ' + lexer.next());
    }
    return func
};

FunctionCall.prototype = new BaseExpr();

FunctionCall.prototype.evaluate = function (ctx) {
    return this.func.apply(ctx, this.args);
};

FunctionCall.prototype.arg = function(arg) {
    this.args.push(arg);

    if (arg.needContextPosition) {
        this.needContextPosition = true;
    }

    var args = this.args;
    if (arg.needContextNode) {
        args.needContexNode = true;
    }
    this.needContextNode = args.needContextNode ||
                            this.needContextNodeInfo[args.length];
};

FunctionCall.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'function: ' + this.name + '\n';
    indent += '    ';

    if (this.args.length) {
        t += indent + 'arguments: ' + '\n';
        indent += '    ';
        for (var i = 0; i < this.args.length; i ++) {
            t += this.args[i].show(indent);
        }
    }

    return t;
};


