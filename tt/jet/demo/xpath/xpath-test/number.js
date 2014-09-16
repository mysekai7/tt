/**
 * class: Number
 */
if (!window.Number && window.defaultConfig)
    window.Number = null;

Number = function(digit) {
    this.digit = +digit;
};


Number.parse = function(lexer) {
    return new Number(lexer.next());
};

Number.prototype = new BaseExpr();

Number.prototype.datatype = 'number';

Number.prototype.evaluate = function(ctx) {
    return this.digit;
};

Number.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'number: ' + this.digit + '\n';
    return t;
};


