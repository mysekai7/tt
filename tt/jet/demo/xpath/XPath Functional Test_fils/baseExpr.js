/**
 * abstract class: BaseExpr
 */
var BaseExpr = function() {};

BaseExpr.prototype.number = function(ctx) {
    var exrs = this.evaluate(ctx);
    if (exrs.isNodeSet) return exrs.number();
    return + exrs;
};

BaseExpr.prototype.string = function(ctx) {
    var exrs = this.evaluate(ctx);
    if (exrs.isNodeSet) return exrs.string();
    return '' + exrs;
};

BaseExpr.prototype.bool = function(ctx) {
    var exrs = this.evaluate(ctx);
    if (exrs.isNodeSet) return exrs.bool();
    return !! exrs;
};


/**
 * abstract class: BaseExprHasPredicates
 */
var BaseExprHasPredicates = function() {};

BaseExprHasPredicates.parsePredicates = function(lexer, expr) {
    while (lexer.peek() == '[') {
        lexer.next();
        if (lexer.empty()) {
            throw Error('missing predicate expr');
        }
        var predicate = BinaryExpr.parse(lexer);
        expr.predicate(predicate);
        if (lexer.empty()) {
            throw Error('unclosed predicate expr');
        }
        if (lexer.next() != ']') {
            lexer.back();
            throw Error('bad token: ' + lexer.next());
        }
    }
};

BaseExprHasPredicates.prototyps = new BaseExpr();

BaseExprHasPredicates.prototype.evaluatePredicates = function(nodeset, start) {
    var predicates, predicate, nodes, node, nodeset, position, reverse;

    reverse = this.reverse;
    predicates = this.predicates;

    nodeset.sort();

    for (var i = start || 0, l0 = predicates.length; i < l0; i ++) {
        predicate = predicates[i];

        var deleteIndexes = [];
        var nodes = nodeset.list();

        for (var j = 0, l1 = nodes.length; j < l1; j ++) {

            position = reverse ? (l1 - j) : (j + 1);
            exrs = predicate.evaluate(new Ctx(nodes[j], position, l1));

            switch (typeof exrs) {
                case 'number':
                    exrs = (position == exrs);
                    break;
                case 'string':
                    exrs = !!exrs;
                    break;
                case 'object':
                    exrs = exrs.bool();
                    break;
            }

            if (!exrs) {
                deleteIndexes.push(j);
            }
        }

        for (var j = deleteIndexes.length - 1, l1 = 0; j >= l1; j --) {
            nodeset.del(deleteIndexes[j]);
        }

    }

    return nodeset;
};


