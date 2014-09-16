/**
 * class: UnionExpr
 */
if (!window.UnionExpr && window.defaultConfig)
    window.UnionExpr = null;

UnionExpr = function() {
    this.paths = [];
};

UnionExpr.ops = { '|': 1 };


UnionExpr.parse = function(lexer) {
    var union, expr;

    expr = PathExpr.parse(lexer);
    if (!this.ops[lexer.peek()])
        return expr;

    union = new UnionExpr();
    union.path(expr);

    while (true) {
        if (!this.ops[lexer.next()]) break;
        if (lexer.empty()) {
            throw Error('missing next union location path');
        }
        union.path(PathExpr.parse(lexer));
    }



    lexer.back();
    return union;
};

UnionExpr.prototype = new BaseExpr();

UnionExpr.prototype.datatype = 'nodeset';

UnionExpr.prototype.evaluate = function(ctx) {
    var paths = this.paths;
    var nodeset = new NodeSet();
    for (var i = 0, l = paths.length; i < l; i ++) {
        var exrs = paths[i].evaluate(ctx);
        if (!exrs.isNodeSet) throw Error('PathExpr must be nodeset');
        nodeset.merge(exrs);
    }
    return nodeset;
};

UnionExpr.prototype.path = function(path) {
    this.paths.push(path);

    if (path.needContextPosition) {
        this.needContextPosition = true;
    }
    if (path.needContextNode) {
        this.needContextNode = true;
    }
}
UnionExpr.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'union:' + '\n';
    indent += '    ';
    for (var i = 0; i < this.paths.length; i ++) {
        t += this.paths[i].show(indent);
    }
    return t;
};


