/**
 * NodeType
 */
if (!window.NameTest && window.defaultConfig)
    window.NameTest = null;

NameTest = function(name) {
    this.name = name.toLowerCase();
};

NameTest.parse = function(lexer) {
    if (lexer.peek() != '*' &&  lexer.peek(1) == ':' && lexer.peek(2) == '*') {
        return new NameTest(lexer.next() + lexer.next() + lexer.next());
    }
    return new NameTest(lexer.next());
};

NameTest.prototype = new BaseExpr();

NameTest.prototype.match = function(node) {
    var type = node.nodeType;

    if (type == 1 || type == 2) {
        if (this.name == '*' || this.name == node.nodeName.toLowerCase()) {
            return true;
        }
    }
    return false;
};

NameTest.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'nametest: ' + this.name + '\n';
    return t;
};


