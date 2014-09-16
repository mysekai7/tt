/**
 * NodeType
 */
if (!window.NodeType && window.defaultConfig)
    window.NodeType = null;
    
NodeType = function(name, literal) {
    this.name = name;
    this.literal = literal;

    switch (name) {
        case 'comment':
            this.type = 8;
            break;
        case 'text':
            this.type = 3;
            break;
        case 'processing-instruction':
            this.type = 7;
            break;
        case 'node':
            this.type = 0;
            break;
    }
};

NodeType.types = {
    'comment':1, 'text':1, 'processing-instruction':1, 'node':1
};

NodeType.parse = function(lexer) {
    var type, literal, ch;
    type = lexer.next();
    lexer.next();
    if (lexer.empty()) {
        throw Error('bad nodetype');
    }
    ch = lexer.peek().charAt(0);
    if (ch == '"' || ch == "'") {
        literal = Literal.parse(lexer);
    }
    if (lexer.empty()) {
        throw Error('bad nodetype');
    }
    if (lexer.next() != ')') {
        lexer.back();
        throw Error('bad token ' + lexer.next());
    }
    return new NodeType(type, literal);
};

NodeType.prototype = new BaseExpr();

NodeType.prototype.notOnlyElement = true;

NodeType.prototype.match = function(node) {
    return !this.type || this.type == node.nodeType;
};

NodeType.prototype.show = function(indent) {
    indent = indent || '';
    var t = '';
    t += indent + 'nodetype: ' + this.type + '\n';
    if (this.literal) {
        indent += '    ';
        t += this.literal.show(indent);
    }
    return t;
};


