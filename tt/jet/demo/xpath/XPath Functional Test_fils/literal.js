/**
 * class: Literal
 */
if (!window.Literal && window.defaultConfig)
    window.Literal = null;

Literal = function(text) {
    this.text = text.substring(1, text.length - 1);
};

Literal.parse = function(lexer) {
    var token = lexer.next();
    if (token.length < 2) {
        throw Error('unclosed literal string');
    }
    return new Literal(token)
};

Literal.prototype = new BaseExpr();

Literal.prototype.datatype = 'string';

Literal.prototype.evaluate = function(ctx) {
    return this.text;
};

Literal.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'literal: ' + this.text + '\n';
    return t;
};


