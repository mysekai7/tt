if (!window.Logger)
    window.Logger = null;

if (!window.Counter)
    window.Counter = null;

Counter = function(elem_ok, elem_all, alertClassName) {
    this.countAll = 0;
    this.countOKs = 0;
    this.elem_all = elem_all;
    this.elem_ok = elem_ok;
    this.alertClassName = alertClassName;
};

Counter.prototype = {
    inc: function(ok) {
        this.elem_all.innerHTML = ++this.countAll;
        if (ok)
            this.elem_ok.innerHTML = ++this.countOKs;
        else
            this.elem_ok.className = this.alertClassName;
    }
};

Logger = function(header, html, id, prevId) {
    this.container = document.createElement('div');
    this.container.id = 'test-' + id;
    testLog.appendChild(this.container);


    this.h2 = document.createElement('h2');
    this.container.appendChild(this.h2);

    this.thisAnchor = document.createElement('a');
    this.thisAnchor.href = '#test-' + id;
    this.thisAnchor.appendChild(document.createTextNode(header));
    this.h2.appendChild(this.thisAnchor);

    this.localCountElm = document.createElement('span');
    this.localCountElm.className = 'local-counter';
    this.localCountElm.innerHTML = '<span class="local-counter-ok">0</span> / <span>0</span>'
    this.h2.appendChild(document.createTextNode(' '));
    this.h2.appendChild(this.localCountElm);

    this.localCounter = new Counter(this.localCountElm.firstChild, this.localCountElm.lastChild, 'local-counter-ng');


    this.anchorContainer = document.createElement('div');
    this.container.appendChild(this.anchorContainer);

    var anchor = document.createElement('a');
    anchor.href = '?' + id;
    anchor.appendChild(document.createTextNode('(only this test)'));
    anchor.className = 'only';
    this.anchorContainer.appendChild(anchor);

    if (prevId) {
        this.prevAnchor = document.createElement('a');
        this.prevAnchor.href = '#test-' + prevId;
        this.prevAnchor.appendChild(document.createTextNode('(prev)'));
        this.prevAnchor.className = 'prev';
        this.anchorContainer.appendChild(document.createTextNode(' '));
        this.anchorContainer.appendChild(this.prevAnchor);
    }

    this.pre = document.createElement('pre');
    if (uai.ie) {
        var dummy = document.createElement('pre');
        dummy.appendChild(document.createTextNode(html));
        this.pre.innerHTML = dummy.innerHTML.replace(/\n/g, '<br/>\n').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;').replace(/ /g, '&nbsp;');
    }
    else {
        this.pre.appendChild(document.createTextNode(html));
    }
    this.container.appendChild(this.pre);

    this.table = document.createElement('table');
    this.thead = document.createElement('thead');
    this.tbody = document.createElement('tbody');
    this.table.appendChild(this.thead);
    this.table.appendChild(this.tbody);
    this.theadRow = document.createElement('tr');
    this.thead.appendChild(this.theadRow);
    this.countCols = 0;

    this.appendHeader('expression');
    this.appendHeader('expects');
    this.appendHeader('result');
    this.appendHeader('time(ms)');
    this.appendHeader('links');

    this.container.appendChild(this.table);
    this.count = 0;
    this.id = id;
    
};

Logger.prototype = {
    appendHeader: function(label) {
        th = document.createElement('th');
        th.appendChild(document.createTextNode(label));
        this.theadRow.appendChild(th);
        this.countCols++;
        return th;
    },

    next:function (id) {
        this.nextAnchor = document.createElement('a');
        this.nextAnchor.href = '#test-' + id;
        this.nextAnchor.appendChild(document.createTextNode('(next)'));
        this.nextAnchor.className = 'next';
        this.anchorContainer.appendChild(document.createTextNode(' '));
        this.anchorContainer.appendChild(this.nextAnchor);
    },

    log: function(cols) {
        var td, tr = document.createElement('tr');
        this.tbody.appendChild(tr);

        for(;cols.length > 0;) {
            var c = cols.shift();
            if (c && c.label == 0)
                c.label = c.label.toString();

            if (!c || (!c.richLabel && !c.className))
                c = {label: c ? (c.label || c.toString()) : c};

            td = document.createElement('td');
            td.appendChild( c.richLabel || (document.createTextNode(c.label || '(none)')) );
            tr.appendChild(td);

            if (c.className)
                td.className = c.className;
        }

    },
    
    logFullSpan: function(content) {
        var td, tr = document.createElement('tr');
        this.tbody.appendChild(tr);
        td = document.createElement('td');
        td.appendChild(content);
        tr.appendChild(td);
        td.setAttribute("colSpan", this.countCols);
        td.className = "fullspan";
    },

    lazyLogFullSpan: function(labelText, closure) {
        var td, tr = document.createElement('tr');
        this.tbody.appendChild(tr);
        td = document.createElement('td');
        var label = document.createElement('span');
        label.innerHTML = labelText;
        label.className = "lazy-log-open";
        label.onclick = function(){
            td.innerHTML = "";
            td.appendChild(    closure() );
        };
        
        td.appendChild(label);
        tr.appendChild(td);
        td.setAttribute("colSpan", this.countCols);
        td.className = "fullspan";
    }

};

