/*
Extbox - Customizable lightbox plugin for Ext Core.
Copyright (C) 2010  atma <atma@atmaworks.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details <http://www.gnu.org/licenses/gpl-3.0.html>.
*/
Ext.ns('Ext.ux');
Ext.ux.extbox = (function() {
    var els = {},
        items = [],
        activeItem,
        extboxBorders = [],
        interfaceWidth,
        interfaceHeight,
        currentWidth = 250,
        currentHeight = 250,
        currentX,
        currentY,
        isImg = false,
        initialized = false,
        selectors = [],
        wrapper = false;

    return {
        version: '1.0',
        opts: {},
        defaults: {
            current: 'image {current} of {total}',
            previous: '&#8592;',
            next: '&#8594;',
            close: 'close',
            width: false,
            height: false,
            innerWidth: false,
            innerHeight: false,
            maxWidth: '90%',
            maxHeight: '90%',
            animate: true,
            scale: true,
            iframe: false,
            inline: false,
            resizeDuration: 0.3,
            overlayOpacity: 0.8,
            overlayDuration: 0.2,
            hideInfo: false,
            easing: 'easeOut',
            href: false,
            title: false
        },
        init: function() {
            if(!initialized) {
                Ext.apply(this, Ext.util.Observable.prototype);
                Ext.util.Observable.constructor.call(this);
                this.addEvents('open', 'close');
                this.initMarkup();
                this.initEvents();
                initialized = true;
            }
        },
        initMarkup: function() {
            els.overlay = Ext.DomHelper.insertFirst(document.body, {
                id: 'ux-extbox-overlay'
            }, true);
            els.overlay.setVisibilityMode(Ext.Element.DISPLAY).hide();
            
            if (Ext.isIE6) {
                els.shim = Ext.DomHelper.insertFirst(document.body, {
                    tag: 'iframe',
                    id: 'ux-extbox-shim',
                    frameborder: 0
                }, true);
                els.shim.setVisibilityMode(Ext.Element.DISPLAY);
                els.shim.hide();
            }
            
            var extboxTpl = new Ext.Template(this.getTemplate());
            els.extbox = extboxTpl.insertAfter(els.overlay, {}, true);
            els.extbox.setVisibilityMode(Ext.Element.DISPLAY).hide();
            
            var ids = ['container', 'content', 'loadingOverlay', 'loading', 'navPrev', 'navNext', 'navClose', 'info', 'title', 'current'];
            Ext.each(ids, function(id){
                els[id] = Ext.get('ux-extbox-' + id);
            });
            extboxBorders = [(
                els.extbox.getPadding('t') +
                els.extbox.getBorderWidth('t')
            ), (
                els.extbox.getPadding('r') +
                els.extbox.getBorderWidth('r')
            ), (
                els.extbox.getPadding('b') +
                els.extbox.getBorderWidth('b')
            ), (
                els.extbox.getPadding('l') +
                els.extbox.getBorderWidth('l')
            )];
            interfaceWidth =
                els.container.getPadding('rl') +
                els.container.getBorderWidth('rl') +
                els.content.getPadding('rl') +
                els.content.getBorderWidth('rl') +
                parseInt(els.container.getStyle('margin-left'), 10) +
                parseInt(els.container.getStyle('margin-right'), 10);
            interfaceHeight =
                els.container.getPadding('tb') +
                els.container.getBorderWidth('tb') +
                els.content.getPadding('tb') +
                els.content.getBorderWidth('tb') +
                parseInt(els.container.getStyle('margin-top'), 10) +
                parseInt(els.container.getStyle('margin-bottom'), 10);

            els.extbox.setStyle({
                width: currentWidth + 'px',
                height: currentHeight + 'px'
            });
            if (wrapper) {
                this.wrapBox();
            }
        },
        getTemplate : function() {
            return [
                '<div id="ux-extbox">',
                    '<div id="ux-extbox-container">',
                        '<div id="ux-extbox-content">',

                        '</div>',
                        '<div id="ux-extbox-loadingOverlay">',
                            '<div id="ux-extbox-loading"></div>',
                        '</div>',
                        '<div id="ux-extbox-navPrev"></div>',
                        '<div id="ux-extbox-navNext"></div>',
                        '<div id="ux-extbox-navClose"></div>',
                        '<div id="ux-extbox-info">',
                            '<div id="ux-extbox-title"></div>',
                            '<div id="ux-extbox-current"></div>',
                        '</div>',
                    '</div>',
                '</div>'
            ];
        },
        initEvents: function() {
            var close = function(ev) {
                ev.preventDefault();
                this.close();
            };

            els.overlay.on('click', close, this);
            els.navClose.on('click', close, this);

            els.extbox.on('click', function(ev) {
                if(ev.getTarget().id == 'ux-extbox') {
                    this.close();
                }
            }, this);
            
            els.navPrev.on('click', function(ev) {
                ev.preventDefault();
                this.loadItem(activeItem - 1);
            }, this);

            els.navNext.on('click', function(ev) {
                ev.preventDefault();
                this.loadItem(activeItem + 1);
            }, this);
        },
        register: function(sel, group, options) {
            if(selectors.indexOf(sel) === -1) {
                selectors.push(sel);
                Ext.fly(document).on('click', function(ev){
                    var target = ev.getTarget(sel);

                    if (target) {
                        ev.preventDefault();
                        this.open(target, sel, group, options);
                    }
                }, this);
            }
        },
        open: function(item, sel, group, options) {
            group = group || false;
            Ext.apply(this.opts, options, this.defaults);
            this.opts.resizeDuration = this.opts.animate ? this.opts.resizeDuration : 0;
            this.opts.overlayDuration = this.opts.animate ? this.opts.overlayDuration : 0;
            this.setViewSize();
            els.overlay.fadeIn({
                duration: this.opts.overlayDuration,
                endOpacity: this.opts.overlayOpacity,
                callback: function() {
                    items = [];
                    var index = 0;
                    if(!group) {
                        items.push([(this.opts.href || item.href), (this.opts.title || item.title)]);
                    } else {
                        var setItems = Ext.query(sel);
                        Ext.each(setItems, function(item) {
                            if(item.href) {
                                items.push([item.href, item.title]);
                            }
                        });

                        while (items[index][0] != item.href) {
                            index++;
                        }
                    }

                    // calculate top and left offset for the extbox
                    var pageScroll = Ext.fly(document).getScroll();
                    var extboxTop = (Ext.lib.Dom.getViewportHeight() - currentHeight) / 2 + pageScroll.top;
                    var extboxLeft = (Ext.lib.Dom.getViewportWidth() - currentWidth) / 2 + pageScroll.left;

                    els.extbox.setStyle({
                        top: extboxTop + 'px',
                        left: extboxLeft + 'px'
                    }).show();

                    this.loadItem(index);
                    this.updateControls();
                    this.checkInfoVisibility();

                    Ext.fly(window).on('resize', this.resizeWindow, this);
                    this.fireEvent('open', items[index]);
                },
                scope: this
            });
        },
        setViewSize: function() {
            var viewSize = [
                Math.max(Ext.lib.Dom.getViewWidth(), Ext.lib.Dom.getDocumentWidth()),
                Math.max(Ext.lib.Dom.getViewHeight(), Ext.lib.Dom.getDocumentHeight())
            ];
            if (Ext.isIE6) {
                els.shim.setStyle({
                    width: viewSize[0] + 'px',
                    height: viewSize[1] + 'px'
                }).setOpacity(0).show();
                els.overlay.setStyle({
                    width: viewSize[0] + 'px',
                    height: viewSize[1] + 'px',
                    position: 'absolute'
                });
            } else {
                els.overlay.setStyle({
                    width: viewSize[0] + 'px',
                    height: viewSize[1] + 'px'
                });
            }
        },
        loadItem: function(index) {
            var timeout, loadContent = {};
            activeItem = index;
            this.disableKeyNav();
            if (this.opts.animate) {
                els.loadingOverlay.show();
                els.loading.show();
            }

            if (this.opts.inline) {
                isImg = false;
                currentX = false;
                currentY = false;
                var cnt = Ext.query(this.opts.href);
                loadContent = {
                    tag: 'div',
                    id: 'ux-extbox-loadedContent',
                    html: cnt[0].innerHTML,
                    style: {display: 'none'}
                };
                
                Ext.DomHelper.overwrite(els.content, loadContent);
                this.resize();
            } else if (this.opts.iframe) {
                isImg = false;
                currentX = false;
                currentY = false;
                loadContent = {
                    tag: 'iframe',
                    id: 'ux-extbox-loadedContent',
                    frameborder: 0,
                    src: items[activeItem][0],
                    style: {display: 'none'}
                };
                Ext.DomHelper.overwrite(els.content, loadContent);
                this.resize();
            } else if (this.isImage(items[activeItem][0])) {
                var img = new Image();
                timeout = (Ext.isIE) ? 250 : 100;
                img.onload = (function() {
                    currentX = img.width;
                    currentY = img.height;
                    (function() {this.resize(currentX, currentY)}).createDelegate(this).defer(timeout);
                    //this.resize(img.width, img.height);
                    if (Ext.isIE) {
                        img.style.msInterpolationMode='bicubic';
                    }
                }).createDelegate(this);
                img.src = items[activeItem][0];
                loadContent = {
                    tag: 'img',
                    id: 'ux-extbox-loadedContent',
                    src: items[activeItem][0],
                    style: {display: 'none'}
                };
                Ext.DomHelper.overwrite(els.content, loadContent);
            } else {
                isImg = false;
                currentX = false;
                currentY = false;
                loadContent = {
                    tag: 'div',
                    id: 'ux-extbox-loadedContent',
                    style: {display: 'none'}
                };
                Ext.Ajax.request({
                   url: items[activeItem][0],
                   method: 'GET',
                   success: function(response) {
                       loadContent.html = response.responseText;
                       Ext.DomHelper.overwrite(els.content, loadContent);
                       this.resize();
                   },
                   scope: this,
                   failure: function(response) {
                       if (console) console.dir(response);
                   }
                });
            }
            
            (function () {
                this.updateNav();
                if (this.opts.animate) {
                    els.loadingOverlay.hide();
                    els.loading.hide();
                }
                this.preloadImages();
            }).defer(this.opts.resizeDuration * 1000, this);
        },
        resize: function (w, h) {
            var c, x, y, cx, cy, cl, ct, loadedContent;
            var viewSize = this.getViewSize();
            var pageScroll = Ext.fly(document).getScroll();
            var maxW = this.setSize(this.opts.maxWidth, 'x') - extboxBorders[3] - extboxBorders[1] - interfaceWidth;
            var maxH = this.setSize(this.opts.maxWidth, 'y') - extboxBorders[0] - extboxBorders[2] - interfaceHeight;
            cx = w || this.opts.innerWidth;
            cy = h || this.opts.innerHeight;
            x = (cx) ?
                cx : (this.opts.width) ?
                    this.opts.width - extboxBorders[3] - extboxBorders[1] : maxW;
            y = (cy) ?
                cy : (this.opts.height) ?
                    this.opts.height - extboxBorders[0] - extboxBorders[2] : maxH;
            if (isImg && this.opts.scale) {
                if (x > maxW || y > maxH) {
                    c = maxH/y;
                    if (c*x > maxW) {
                        c = maxW/x;
                        x = maxW;
                        y = c*y;
                    } else {
                        y = maxH;
                        x = c*x;
                    }
                }
            } else if (this.opts.scale) {
                x = (x > maxW) ? maxW : x;
                y = (y > maxH) ? maxH : y;
            }
            x = parseInt(x, 10);
            y = parseInt(y, 10);
            currentWidth = x + interfaceWidth + extboxBorders[1] + extboxBorders[3];
            currentHeight = y + interfaceHeight + extboxBorders[0] + extboxBorders[2];
            cl = ((viewSize[0] - x - extboxBorders[1] - extboxBorders[3] - interfaceWidth) / 2) + pageScroll.left;
            ct = ((viewSize[1] - y - extboxBorders[0] - extboxBorders[2] - interfaceHeight) / 2)  + pageScroll.top;
            cl = (cl > 0) ? cl : 0;
            ct = (ct > 0) ? ct : 0;
            Ext.Fx.syncFx();

            els.extbox.shift({
                width: currentWidth,
                height: currentHeight,
                left: cl,
                top: ct,
                easing: this.opts.easing,
                duration: this.opts.resizeDuration,
                scope: this
            });
            els.content.shift({
                width: x,
                height: y,
                easing: this.opts.easing,
                duration: this.opts.resizeDuration,
                scope: this,
                callback: function() {
                    this.updateDetails();
                }
            });
            loadedContent = Ext.get('ux-extbox-loadedContent');
            if (loadedContent !== null && loadedContent.isVisible()) {
                loadedContent.shift({
                    width: x,
                    height: y,
                    easing: this.opts.easing,
                    duration: this.opts.resizeDuration
                });
            } else {
                loadedContent.shift({
                    width: x,
                    height: y,
                    easing: this.opts.easing,
                    duration: this.opts.resizeDuration
                }).fadeIn({duration: this.opts.resizeDuration/2});
            }
            Ext.Fx.sequenceFx();
        },
        resizeWindow: function() {
                    this.setViewSize();
                    this.resize(currentX, currentY);
        },
        updateDetails: function(){
            els.title.update(items[activeItem][1]);
            //els.title.show();
            if (items.length > 1) {
				els.current.update(this.opts.current.replace(/\{current\}/, activeItem+1).replace(/\{total\}/, items.length));
                //els.current.show();
            } else {
                els.current.update('');
            }
        },
        checkInfoVisibility: function() {
            if (this.opts.hideInfo == 'auto') {
                els.extbox.on('mouseenter', this.showInfo, this);
                els.extbox.on('mouseleave', this.hideInfo, this);
                els.info.hide();
            } else if (this.opts.hideInfo === false) {
                els.extbox.un('mouseenter', this.showInfo, this);
                els.extbox.un('mouseleave', this.hideInfo, this);
                els.info.show();
            } else if (this.opts.hideInfo === true) {
                els.extbox.un('mouseenter', this.showInfo, this);
                els.extbox.un('mouseleave', this.hideInfo, this);
                els.info.hide();
            }
        },
        showInfo: function() {
            els.info.stopFx().fadeIn({duration: this.opts.resizeDuration});
        },
        hideInfo: function() {
            els.info.stopFx().fadeOut({duration: this.opts.resizeDuration});
        },
        updateControls: function() {
            els.navPrev.update(this.opts.previous);
            els.navNext.update(this.opts.next);
            els.navClose.update(this.opts.close);
        },
        updateNav: function() {
            this.enableKeyNav();

            // if not first image in set, display prev image button
            if (activeItem < 1) {
                els.navPrev.hide();
            } else {
                els.navPrev.show();
            }

            // if not last image in set, display next image button
            if (activeItem >= (items.length - 1)) {
                els.navNext.hide();
            } else {
                els.navNext.show();
            }
                
        },
        enableKeyNav: function() {
            Ext.fly(document).on('keydown', this.keyNavAction, this);
        },
        disableKeyNav: function() {
            Ext.fly(document).un('keydown', this.keyNavAction, this);
        },
        keyNavAction: function(ev) {
            var keyCode = ev.getKey();
            if (
                keyCode == 88 || // x
                keyCode == 67 || // c
                keyCode == 27
            ) {
                this.close();
            } else if (keyCode == 80 || keyCode == 37) { // display previous item
                if (activeItem != 0){
                    this.loadItem(activeItem - 1);
                }
            } else if (keyCode == 78 || keyCode == 39) { // display next item
                if (activeItem != (items.length - 1)) {
                    this.loadItem(activeItem + 1);
                }
            }
        },
        preloadImages: function() {
            var next, prev;
            if (items.length > activeItem + 1) {
                next = new Image();
                next.src = items[activeItem + 1][0];
            }
            if (activeItem > 0) {
                prev = new Image();
                prev.src = items[activeItem - 1][0];
            }
        },
        close: function() {
            this.disableKeyNav();
            els.extbox.hide();
            els.overlay.fadeOut({
                duration: this.opts.overlayDuration
            });
            if (Ext.isIE6) els.shim.hide();
            Ext.DomHelper.overwrite(els.content, '');
            Ext.DomHelper.overwrite(els.title, '');
            Ext.DomHelper.overwrite(els.current, '');
            Ext.fly(window).un('resize', this.resizeWindow, this);
            this.fireEvent('close', activeItem);
        },
        getViewSize: function() {
            return [Ext.lib.Dom.getViewWidth(), Ext.lib.Dom.getViewHeight()];
        },
        setSize: function (size, dimension) {
            dimension = dimension === 'x' ? Ext.lib.Dom.getViewWidth() : Ext.lib.Dom.getViewHeight();
            return (typeof size === 'string') ? Math.round((size.match(/%/) ? (dimension / 100) * parseInt(size, 10) : parseInt(size, 10))) : size;
        },
        isImage: function (url) {
            isImg = url.match(/^.*\.(gif|png|jpg|jpeg|bmp)$/i) ? true : false;
            return isImg;
        },
        wrapBox: function() {
            els.wrapper = els.container.wrap({tag: 'div', id: 'ux-extbox-trc'})
                .wrap({tag: 'div', id: 'ux-extbox-tlc'})
                .wrap({tag: 'div', id: 'ux-extbox-tb'})
                .wrap({tag: 'div', id: 'ux-extbox-brc'})
                .wrap({tag: 'div', id: 'ux-extbox-blc'})
                .wrap({tag: 'div', id: 'ux-extbox-bb'})
                .wrap({tag: 'div', id: 'ux-extbox-rb'})
                .wrap({tag: 'div', id: 'ux-extbox-lb'});
        }
    }
})();

Ext.onReady(Ext.ux.extbox.init, Ext.ux.extbox);
