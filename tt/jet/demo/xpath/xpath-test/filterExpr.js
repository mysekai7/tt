/**
 * class: FilterExpr
 */
if (!window.FilterExpr && window.defaultConfig)
    window.FilterExpr = null;

FilterExpr = function(primary) {
    this.primary = primary;
    this.predicates = [];

    this.datatype = primary.datatype;

    this.needContextPosition = primary.needContextPosition;

    this.needContextNode = primary.needContextNode;
};

FilterExpr.parse = function(lexer) {
    var expr, filter, token, ch;

    token = lexer.peek();
    ch = token.charAt(0);

    switch (ch) {
        case '$':
            expr = VariableReference.parse(lexer);
            break;

        case '(':
            lexer.next();
            expr = BinaryExpr.parse(lexer);
            if (lexer.empty()) {
                throw Error('unclosed "("');
            }
            if (lexer.next() != ')') {
                lexer.back();
                throw Error('bad token: ' + lexer.next());
            }
            break;

        case '"':
        case "'":
            expr = Literal.parse(lexer);
            break;

        default:
            if (!isNaN(+token)) {
                expr = Number.parse(lexer);
            }

            else if (NodeType.types[token]) {
                return null;
            }

            else if (/(?![0-9])[\w]/.test(ch) && lexer.peek(1) == '(') {
                expr = FunctionCall.parse(lexer);
            }
            else {
                return null;
            }
            break;
    }

    if (lexer.peek() != '[') return expr;

    filter = new FilterExpr(expr);

    BaseExprHasPredicates.parsePredicates(lexer, filter);

    return filter;
};

FilterExpr.root = function() {
    return new FunctionCall('root-node');
};
FilterExpr.context = function() {
    return new FunctionCall('context-node');
};

FilterExpr.prototype = new BaseExprHasPredicates();

FilterExpr.prototype.evaluate = function(ctx) {
    var nodeset = this.primary.evaluate(ctx);
    if(!nodeset.isNodeSet) {
        if (this.predicates.length)
            throw Error(
                'Primary result must be nodeset type ' +
                'if filter have predicate expression');
        return nodeset;
    }

    return  this.evaluatePredicates(nodeset);
};

FilterExpr.prototype.predicate = function(predicate) {
    this.predicates.push(predicate);
};

FilterExpr.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'filter: ' + '\n';
    indent += '    ';
    t += this.primary.show(indent);
    if (this.predicates.length) {
        t += indent + 'predicates: ' + '\n';
        indent += '    ';
        for (var i = 0; i < this.predicates.length; i ++) {
            t += this.predicates[i].show(indent);
        }
    }
    return t;
};


