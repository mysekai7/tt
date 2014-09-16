/**
 * class: UnaryExpr
 */
if (!window.UnaryExpr && window.defaultConfig)
    window.UnaryExpr = null;

UnaryExpr = function(op, expr) {
    this.op = op;
    this.expr = expr;

    this.needContextPosition = expr.needContextPosition;
    this.needContextNode = expr.needContextNode;
};

UnaryExpr.ops = { '-': 1 };

UnaryExpr.parse = function(lexer) {
    var token;
    if (this.ops[lexer.peek()])
        return new UnaryExpr(lexer.next(), UnaryExpr.parse(lexer));
    else
        return UnionExpr.parse(lexer);
};

UnaryExpr.prototype = new BaseExpr();

UnaryExpr.prototype.datatype = 'number';

UnaryExpr.prototype.evaluate = function(ctx) {
    return - this.expr.number(ctx);
};

UnaryExpr.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'unary: ' + this.op + '\n';
    indent += '    ';
    t += this.expr.show(indent);
    return t;
};


