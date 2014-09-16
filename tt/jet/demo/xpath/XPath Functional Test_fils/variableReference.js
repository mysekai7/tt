/**
 * class: VariableRefernce
 */
if (!window.VariableReference && window.defaultConfig)
    window.VariableReference = null;
    
VariableReference = function(name) {
    this.name = name.substring(1);
};


VariableReference.parse = function(lexer) {
    var token = lexer.next();
    if (token.length < 2) {
        throw Error('unnamed variable reference');
    }
    return new VariableReference(token)
};

VariableReference.prototype = new BaseExpr();

VariableReference.prototype.datatype = 'void';

VariableReference.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'variable: ' + this.name + '\n';
    return t;
};


