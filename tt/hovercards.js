
/* hunch hovercard */

$.hovercard = {


    //
    // VARIABLES
    //

    card: null,
    cardID: 'hunch-hovercard',
    cardClass: 'hunch-hovercard',
    cardInner: null,

    showTimeout: null,
    hideTimeout: null,

    emptyCfg: {},
    currentCfg: this.emptyCfg,
    currentElt: null,

    defaults: {
        ajaxCache: true, //NOTE - this only caches the url, not the data (maybe fix this in the future)

        gravity: 'top',

        // these offsetFoo values depend on which gravity is chosen
        offsetY: 13,

        showDelay: 120,
        hideDelay: 120,

        borderColor: '#ddd',
        borderWidth: '4px',
        borderRadius: '2px',

        arrow: {
            size: 12
        },

        arrowInner: {
            size: 12,
            color: '#fff',
            offset: 6
        },

        loadingCardWidth: 100,

        loader: {
            src: 'http://hunch.com/media/img/loading-333-fff-16px.gif',
            width: 16,
            height: 16
        }
    },


    //
    // METHODS
    //

    setup: function() {
        // for setting up global params like cardID, cardClass
    },

    //
    // Prepares the hovercard for use on this page (only runs once)
    //
    init: function(cfg) {
        if (this._did_init) return;
        this._did_init = true;

        var self = this;

        this.card = $('<div />', {
            'class': this.cardClass,
            'id': this.cardID
        })
            .appendTo('body')
            .html('<div class="hc-bubble"><div class="hc-content"></div></div><b class="arrow"></b><b class="arrow arrow-inner"></b>')
            .hover(function() {
                self._cardHoverIn();
            }, function() {
                self._cardHoverOut();
            });
        this.cardInner = this.card.find('.hc-content').first();
        this.cardBubble = this.card.find('.hc-bubble').first();
    },

    //
    // Shows the card next to the given element with the given content or a loader (after a timeout)
    //
    showCard: function(elt, cfg, content) {

        this._clearHideTimeout();
        if (!this._did_init) this.init(cfg);

        if (this.currentElt === elt) {
            return;
        }

        this._clearShowTimeout();

        var self = this;
        this.showTimeout = window.setTimeout(function() {

            self.clearCard();
            self.currentElt = elt;
            self.currentCfg = cfg;

            if (cfg.url && !content && cfg.loader) {
                content = $('<img/>', cfg.loader);
            }
            self.cardInner.html(content);
            if (cfg.loadingCardWidth) self.card.css('width', cfg.loadingCardWidth);

            if (cfg.borderColor) self.cardBubble.css('borderColor', cfg.borderColor);
            if (cfg.borderWidth) self.cardBubble.css('borderWidth', cfg.borderWidth);
            if (cfg.borderRadius) self.cardBubble.css('borderRadius', cfg.borderRadius);

            self.card.show();
            self._positionCard();

            if (cfg.content) {
                var content = $.isFunction(cfg.content) ? cfg.content(elt) : cfg.content;
                self.updateCard(content, elt, null, cfg);
            } else if (cfg.url) {
                 //TODO - support getting url from rel attribute or any other attribute too?
                var url = $.isFunction(cfg.url) ? cfg.url(elt) : cfg.url,
                    data = $.isFunction(cfg.data) ? cfg.data(elt) : cfg.data,
                    cache_url = data ? url + (url.split('?').length > 1 ? '&' : '?') + $.param(data) : url,
                    cached_data;

                // only do request if we got a url
                if (!url) {
                    self.card.hide();
                } else {
                    if (cfg.ajaxCache && (cached_data = self._cache.get(cache_url, cfg.namespace))) {
                        self.updateCard(cached_data, elt, cache_url, cfg)
                        if (cfg.postAjaxProcess) cfg.postAjaxProcess.call(self.card, self, cfg, cache_url);
                        self._positionCard();
                    } else {
                        var request = $.extend({
                            dataType: 'html',
                            type: 'get'
                        }, cfg.ajaxSettings, {
                            url: url,
                            success: function(data) {
                                if (cfg.ajaxProcess) data = cfg.ajaxProcess(data, elt, url, cfg);
                                if (data !== null) self.updateCard(data, elt, cache_url, cfg);
                                if (cfg.postAjaxProcess) cfg.postAjaxProcess.call(self.card, self, cfg, cache_url);
                            },
                            error: function(data, textStatus) {
                                try { console.log('Hovercard AJAX error:', data, textStatus); } catch (x) {}
                                if (cfg.ajaxError) cfg.ajaxError.call(this, data, textStatus);
                            }
                        });
                        if (data || cfg.data) request.data = data || cfg.data;

                        $.ajax(request);
                    }
                }
            }

        }, cfg.showDelay);
    },

    //
    // Updates the HTML and position of the hovercard
    //
    updateCard: function(ajax_data, elt, cache_url, cfg, cache_update) {
        this.updateCache(ajax_data, cache_url, cfg);
        if (this.currentElt === elt) {
            var html = ajax_data;
            if (this.responseIsJson(cfg)) {
                html = ajax_data.html;
                if (ajax_data.width) this.card.css('width', ajax_data.width + 'px');
            }
            this.cardInner.html(html);
            this._positionCard();
        }
    },

    //
    // Returns whether the ajax response will be JSON
    //
    responseIsJson: function(cfg) {
        return (cfg.ajaxSettings.dataType == 'json' || cfg.ajaxSettings.dataType == 'jsonp');
    },

    //
    // Updates the cached HTML for the given cache_url
    //
    updateCache: function(html, cache_url, cfg) {
        if (cfg.ajaxCache && cache_url) this._cache.add(html, cache_url, cfg.namespace);
    },

    //
    // Adds 'px' to all values in obj that are numbers, o/w sets them to "auto"
    //
    _addPx: function(obj) {
        for (var attr in obj) {
            if (typeof(obj[attr]) == 'number') obj[attr] += 'px';
            else obj[attr] = 'auto';
        }
        return obj;
    },

    //
    // Positions the card relative to currentElt
    //
    _positionCard: function() {
        if (!this.currentElt || this.currentCfg == this.emtpyCfg) return;

        var cfg = this.currentCfg,
            $curElt = $(this.currentElt),
            offset = $curElt.offset(),
            height = $curElt.outerHeight(),
            width = $curElt.outerWidth(),
            $window = $(window),
            $document = $(document),
            window_width = $window.width(),
            window_height = window.innerHeight ? window.innerHeight : $window.height(),
            scroll_top = $window.scrollTop(),
            scroll_left = $window.scrollLeft(),
            card_height = this.card.outerHeight(),
            card_width = this.card.outerWidth(),
            top, right, left, dir, pos;

        if (cfg.gravity == 'top') {

            dir = 'down';
            pos = 'left';

            top = offset.top - card_height - cfg.offsetY;
            if (top < scroll_top) {
                dir = 'up';
                top = offset.top + height + cfg.offsetY;
            }

            left = offset.left;

            // need to take scroll_left into account when determining if it will be visible or not
            // but when we actually position it, we ignore scroll_left, because left and right
            // inside the body tag are relative to the viewport!
            if (left + card_width > scroll_left + window_width) {
                pos = 'right';
                left = null;
                right = window_width - (offset.left + width);
            }

        } else {
            throw new Error('invalid gravity ' + cfg.gravity);
        }

        this._arrow(this.card.children('b.arrow').first(), this.currentCfg, this.currentCfg.arrow, dir, pos);
        this._arrow(this.card.children('b.arrow-inner').first(), this.currentCfg, this.currentCfg.arrowInner, dir, pos);

        this.card.css(this._addPx({
            top: top,
            right: right,
            left: left
        }));
    },

    //
    // Positions the arrow for the card
    //
    _arrow: function(elt, cfg, arrowCfg, dir, pos) {
        //TODO -- maybe this would have been easier if I just hardcoded it?
        var size = arrowCfg.size,
            offset = size - (arrowCfg.offset || 0),
            a = size + 'px', b = 'transparent',
            borderWidth = [a, a, a, a],
            borderColor = [b, b, b, b],
            width = {
                up: 0,
                left: 1,
                down: 2,
                right: 3
            }[dir],
            color = (width + 2) % 4, // the important color part is always opposite the important width
            top, right, bottom, left;

        borderWidth[width] = 0;
        borderColor[color] = arrowCfg.color || cfg.borderColor;

        switch (dir) {
          case 'up': top = -offset; break;
          case 'right': right = -offset; break;
          case 'left': left = -offset; break;
          case 'down': bottom = -offset; break;
        }

        switch (pos) {
            case 'top': top = size; break;
            case 'right': right = size; break;
            case 'bottom': bottom = size; break;
            case 'left': left = size; break;
        }

        var css = $.extend({
            borderWidth: borderWidth.join(' '),
            borderColor: borderColor.join(' ')
        }, this._addPx({
            top: top,
            right: right,
            bottom: bottom,
            left: left
        }));

        elt.css(css);

        //TODO - add ie6 support?
    },

    //
    // Hides the card (after a timeout)
    //
    hideCard: function(elt, cfg) {
        if (!this._did_init) this.init(cfg);

        this._clearShowTimeout();

        var self = this;
        this.hideTimeout = window.setTimeout(function() { self.clearCard(); }, this.currentCfg.hideDelay);
    },

    //
    // Timeout functions for showing/clearing the card
    //
    clearCard: function() {
        this.card.hide();
        this.currentElt = null;
    },
    _cardHoverIn: function(evt) {
        this._clearHideTimeout();
    },
    _cardHoverOut: function(evt) {
        this.hideCard(evt);
    },
    _clearShowTimeout: function() {
        if (this.showTimeout) {
            try { window.clearTimeout(this.showTimeout); } catch (x) {}
            this.showTimeout = null;
        }
    },
    _clearHideTimeout: function() {
        if (this.hideTimeout) {
            try { window.clearTimeout(this.hideTimeout); } catch (x) {}
            this.hideTimeout = null;
        }
    },

    //
    // The cache of url requests to content
    //
    _cache: {
        _asKey: function(url, namespace) {
            return (namespace ? namespace + '-' : '') + url;
        },
        _data: {},
        add: function(content, url, namespace) {
            var key = this._asKey(url, namespace);
            this._data[key] = content;
        },
        get: function(url, namespace) {
            return this._data[this._asKey(url, namespace)];
        }
    }
};

$.fn.extend({
    hovercard: function(cfg) {
        var hovercard = $.hovercard;
        cfg = $.extend({}, hovercard.defaults, cfg);

        // preload loader image
        if (!cfg.didSetup && cfg.loader && cfg.loader.src) {
            cfg.didSetup = true;
            (new Image).src = cfg.loader.src;
        }

        return $(this).each(function() {
            function hoverIn(evt) { hovercard.showCard(this, cfg); }
            function hoverOut(evt) { hovercard.hideCard(this, cfg); }
            $(this).hover(hoverIn, hoverOut);
        });
    },
    personhover: function(cfg) {
        return this.hovercard($.extend(
            {
                url: '/feed/ws/hovercard/',
                data: function(elt) {
                    return {
                        user_id: $(elt).data('user_id')
                    };
                },
                showDelay: 400,
                ajaxSettings: {
                    dataType: 'json'
                },
                loadingCardWidth: 375,
                postAjaxProcess: function(hovercard, cfg, cache_url) {
                    $(this).find('a.follow').followtoggle({
                        followClickTrackPage: "personhover",
                        callback: function(success, newState) {
                            hovercard.updateCache(hovercard.card.html(), cache_url, cfg);
                        }
                    });
                    $(this).find('img[title]').darkTooltip();
                }
            },
            cfg
        ));
    },
    similarityhover: function(cfg) {
        return this.hovercard($.extend(
            {
                url: '/people/ws/similaritycard/',
                data: function(elt) {
                    return {
                        user_id: $(elt).data('user_id'),
                        category: $(elt).data('category')
                    };
                },
                showDelay: 150,
                ajaxSettings: {
                    dataType: 'json'
                },
                loadingCardWidth: 208,
                postAjaxProcess: function(hovercard, cfg, cache_url) {
                    $(this).find('img[title]').darkTooltip();
                }
            },
            cfg
        ));
    }
});
