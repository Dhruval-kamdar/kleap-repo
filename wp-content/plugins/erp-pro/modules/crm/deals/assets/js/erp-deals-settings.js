+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("erpTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.erpTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){if(a(b.target).is(this))return b.handleObj.handler.apply(this,arguments)}})})}(jQuery),function(a){"use strict";function b(b,d){return this.each(function(){var e=a(this),f=e.data("erp.modal"),g=a.extend({},c.DEFAULTS,e.data(),"object"==typeof b&&b);f||e.data("erp.modal",f=new c(this,g)),"string"==typeof b?f[b](d):g.show&&f.show(d)})}var c=function(b,c){this.options=c,this.$body=a(document.body),this.$element=a(b),this.$dialog=this.$element.find(".erp-deal-modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.options.remote&&this.$element.find(".erp-deal-modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.erp.modal")},this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=300,c.BACKDROP_TRANSITION_DURATION=150,c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},c.prototype.show=function(b){var d=this,e=a.Event("show.erp.modal",{relatedTarget:b});this.$element.trigger(e),this.isShown||e.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("erp-deal-modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.erp.modal",'[data-dismiss="erp-deal-modal"]',a.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.erp.modal",function(){d.$element.one("mouseup.dismiss.erp.modal",function(b){a(b.target).is(d.$element)&&(d.ignoreBackdropClick=!0)})}),this.backdrop(function(){var e=a.support.transition&&d.$element.hasClass("fade");d.$element.parent().length||d.$element.appendTo(d.$body),d.$element.show().scrollTop(0),d.adjustDialog(),e&&d.$element[0].offsetWidth,d.$element.addClass("in"),d.enforceFocus();var f=a.Event("shown.erp.modal",{relatedTarget:b});e?d.$dialog.one("erpTransitionEnd",function(){d.$element.trigger("focus").trigger(f)}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))},c.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.erp.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),a(document).off("focusin.erp.modal"),this.$element.removeClass("in").off("click.dismiss.erp.modal").off("mouseup.dismiss.erp.modal"),this.$dialog.off("mousedown.dismiss.erp.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one("erpTransitionEnd",a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())},c.prototype.enforceFocus=function(){a(document).off("focusin.erp.modal").on("focusin.erp.modal",a.proxy(function(a){document===a.target||this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")},this))},c.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.erp.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.erp.modal")},c.prototype.resize=function(){this.isShown?a(window).on("resize.erp.modal",a.proxy(this.handleUpdate,this)):a(window).off("resize.erp.modal")},c.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.$body.removeClass("erp-deal-modal-open"),a.resetAdjustments(),a.resetScrollbar(),a.$element.trigger("hidden.erp.modal")})},c.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},c.prototype.backdrop=function(b){var d=this,e=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var f=a.support.transition&&e;if(this.$backdrop=a(document.createElement("div")).addClass("erp-deal-modal-backdrop "+e).appendTo(this.$body),this.$element.on("click.dismiss.erp.modal",a.proxy(function(a){if(this.ignoreBackdropClick)return void(this.ignoreBackdropClick=!1);a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide())},this)),f&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;f?this.$backdrop.one("erpTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var g=function(){d.removeBackdrop(),b&&b()};a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("erpTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()}else b&&b()},c.prototype.handleUpdate=function(){this.adjustDialog()},c.prototype.adjustDialog=function(){var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""})},c.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},c.prototype.checkScrollbar=function(){var a=window.innerWidth;if(!a){var b=document.documentElement.getBoundingClientRect();a=b.right-Math.abs(b.left)}this.bodyIsOverflowing=document.body.clientWidth<a,this.scrollbarWidth=this.measureScrollbar()},c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"",this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},c.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad)},c.prototype.measureScrollbar=function(){var a=document.createElement("div");a.className="erp-deal-modal-scrollbar-measure",this.$body.append(a);var b=a.offsetWidth-a.clientWidth;return this.$body[0].removeChild(a),b};var d=a.fn.erpDealModal;a.fn.erpDealModal=b,a.fn.erpDealModal.Constructor=c,a.fn.erpDealModal.noConflict=function(){return a.fn.erpDealModal=d,this},a(document).on("click.erp.modal.data-api",'[data-toggle="erp-deal-modal"]',function(c){var d=a(this),e=d.attr("href"),f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),g=f.data("erp.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());d.is("a")&&c.preventDefault(),f.one("show.erp.modal",function(a){a.isDefaultPrevented()||f.one("hidden.erp.modal",function(){d.is(":visible")&&d.trigger("focus")})}),b.call(f,g,this)})}(jQuery),function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("erp.tooltip"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("erp.tooltip",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",a,b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="erp-tooltip" role="tooltip"><div class="erp-tooltip-arrow"></div><div class="erp-tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},c.prototype.init=function(b,c,d){if(this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.$viewport=this.options.viewport&&a(a.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},c.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("erp."+this.type);return c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c)),b instanceof a.Event&&(c.inState["focusin"==b.type?"focus":"hover"]=!0),c.tip().hasClass("in")||"in"==c.hoverState?void(c.hoverState="in"):(clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show())},c.prototype.isInStateTrue=function(){for(var a in this.inState)if(this.inState[a])return!0;return!1},c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("erp."+this.type);if(c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c)),b instanceof a.Event&&(c.inState["focusout"==b.type?"focus":"hover"]=!1),!c.isInStateTrue()){if(clearTimeout(c.timeout),c.hoverState="out",!c.options.delay||!c.options.delay.hide)return c.hide();c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)}},c.prototype.show=function(){var b=a.Event("show.erp."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);var d=a.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(b.isDefaultPrevented()||!d)return;var e=this,f=this.tip(),g=this.getUID(this.type);this.setContent(),f.attr("id",g),this.$element.attr("aria-describedby",g),this.options.animation&&f.addClass("fade");var h="function"==typeof this.options.placement?this.options.placement.call(this,f[0],this.$element[0]):this.options.placement,i=/\s?auto?\s?/i,j=i.test(h);j&&(h=h.replace(i,"")||"top"),f.detach().css({top:0,left:0,display:"block"}).addClass(h).data("erp."+this.type,this),this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element),this.$element.trigger("inserted.erp."+this.type);var k=this.getPosition(),l=f[0].offsetWidth,m=f[0].offsetHeight;if(j){var n=h,o=this.getPosition(this.$viewport);h="bottom"==h&&k.bottom+m>o.bottom?"top":"top"==h&&k.top-m<o.top?"bottom":"right"==h&&k.right+l>o.width?"left":"left"==h&&k.left-l<o.left?"right":h,f.removeClass(n).addClass(h)}var p=this.getCalculatedOffset(h,k,l,m);this.applyPlacement(p,h);var q=function(){var a=e.hoverState;e.$element.trigger("shown.erp."+e.type),e.hoverState=null,"out"==a&&e.leave(e)};a.support.transition&&this.$tip.hasClass("fade")?f.one("erpTransitionEnd",q).emulateTransitionEnd(c.TRANSITION_DURATION):q()}},c.prototype.applyPlacement=function(b,c){var d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),b.top+=g,b.left+=h,a.offset.setOffset(d[0],a.extend({using:function(a){d.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),d.addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;"top"==c&&j!=f&&(b.top=b.top+f-j);var k=this.getViewportAdjustedDelta(c,b,i,j);k.left?b.left+=k.left:b.top+=k.top;var l=/top|bottom/.test(c),m=l?2*k.left-e+i:2*k.top-f+j,n=l?"offsetWidth":"offsetHeight";d.offset(b),this.replaceArrow(m,d[0][n],l)},c.prototype.replaceArrow=function(a,b,c){this.arrow().css(c?"left":"top",50*(1-a/b)+"%").css(c?"top":"left","")},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".erp-tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),e.$element&&e.$element.removeAttr("aria-describedby").trigger("hidden.erp."+e.type),b&&b()}var e=this,f=a(this.$tip),g=a.Event("hide.erp."+this.type);if(this.$element.trigger(g),!g.isDefaultPrevented())return f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one("erpTransitionEnd",d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),this.hoverState=null,this},c.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},c.prototype.hasContent=function(){return this.getTitle()},c.prototype.getPosition=function(b){b=b||this.$element;var c=b[0],d="BODY"==c.tagName,e=c.getBoundingClientRect();null==e.width&&(e=a.extend({},e,{width:e.right-e.left,height:e.bottom-e.top}));var f=window.SVGElement&&c instanceof window.SVGElement,g=d?{top:0,left:0}:f?null:b.offset(),h={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},i=d?{width:a(window).width(),height:a(window).height()}:null;return a.extend({},e,h,i,g)},c.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},c.prototype.getViewportAdjustedDelta=function(a,b,c,d){var e={top:0,left:0};if(!this.$viewport)return e;var f=this.options.viewport&&this.options.viewport.padding||0,g=this.getPosition(this.$viewport);if(/right|left/.test(a)){var h=b.top-f-g.scroll,i=b.top+f-g.scroll+d;h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,k=b.left+f+c;j<g.left?e.left=g.left-j:k>g.right&&(e.left=g.left+g.width-k)}return e},c.prototype.getTitle=function(){var a=this.$element,b=this.options;return a.attr("data-original-title")||("function"==typeof b.title?b.title.call(a[0]):b.title)},c.prototype.getUID=function(a){do{a+=~~(1e6*Math.random())}while(document.getElementById(a));return a},c.prototype.tip=function(){if(!this.$tip&&(this.$tip=a(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".erp-tooltip-arrow")},c.prototype.enable=function(){this.enabled=!0},c.prototype.disable=function(){this.enabled=!1},c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},c.prototype.toggle=function(b){var c=this;b&&((c=a(b.currentTarget).data("erp."+this.type))||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c))),b?(c.inState.click=!c.inState.click,c.isInStateTrue()?c.enter(c):c.leave(c)):c.tip().hasClass("in")?c.leave(c):c.enter(c)},c.prototype.destroy=function(){var a=this;clearTimeout(this.timeout),this.hide(function(){a.$element.off("."+a.type).removeData("erp."+a.type),a.$tip&&a.$tip.detach(),a.$tip=null,a.$arrow=null,a.$viewport=null,a.$element=null})};var d=a.fn.erpTooltip;a.fn.erpTooltip=b,a.fn.erpTooltip.Constructor=c,a.fn.erpTooltip.noConflict=function(){return a.fn.erpTooltip=d,this}}(jQuery);

;(function($) {
    'use strict';

    /* jshint unused: false */
    var dealUtils = {
        computed: {
            /**
             * Date format in erpDealsGlobal is specially for jQuery datepicker.
             * This formatting is for moment.js
             */
            dateFormat: function dateFormat() {
                return erpDealsGlobal.date.format.replace('yy', 'yyyy').toUpperCase();
            }
        },
    
        methods: {
            /**
             * Convert camelcase name to underscore names
             *
             * example: 'helloWorld' will convert to hello_world
             */
            camelToUnderscore: function camelToUnderscore(str) {
                return str.replace(/([A-Z])/g, function ($1) {
                    return '_' + $1.toLowerCase();
                });
            },
    
            /**
             * Change the camelcase keys to underscore keys
             *
             * example: { firstName: '' } will convert to { 'first_name': '' }
             */
            camelToUnderscoreObject: function camelToUnderscoreObject(obj) {
                var this$1 = this;
    
                var newObject = {};
    
                for (var prop in obj) {
                    newObject[this$1.camelToUnderscore(prop)] = obj[prop];
                }
    
                return newObject;
            },
    
            /**
             * Change the underscore keys to camelcase keys
             *
             * example: { first_name: '' } will convert to { 'firstName': '' }
             */
            underscoreToCamelObject: function underscoreToCamelObject(obj) {
                var newObject = {};
    
                for (var _prop in obj) {
                    var prop = _prop.replace(/_/g, '-');
                    newObject[Vue.util.camelize(prop)] = obj[_prop];
                }
    
                return newObject;
            },
    
            /**
             * Change the underscore keys to camelcase keys for a deep nested object
             * uses underscoreToCamelObject
             */
            camelizedObject: function camelizedObject(obj) {
                var this$1 = this;
    
                if ('[object Object]' === obj.toString()) {
                    obj = this.underscoreToCamelObject(obj);
    
                    for (var i in obj) {
                        if (obj[i] && Array.isArray(obj[i])) {
                            var j = 0;
    
                            for(j = 0; j < obj[i].length; j++) {
                                obj[i][j] = this$1.camelizedObject(obj[i][j]);
                            }
    
                        } else if (obj[i] && '[object Object]' === obj[i].toString()) {
                            obj[i] = this$1.camelizedObject(obj[i]);
                        }
                    }
                }
    
                return obj;
            },
    
            /**
             * Change the underscore keys to camelcase keys for deep nested objects
             * in an array
             */
            camelizedArray: function camelizedArray(arr) {
                var this$1 = this;
    
                var camelizedArray = [];
                var i = 0;
    
                for (i = 0; i < arr.length; i++) {
                    camelizedArray.push(this$1.camelizedObject(arr[i]));
                }
    
                return camelizedArray;
            },
    
            /**
             * Prevent modal from closing during an ajax operation.
             */
            modalKeepOpen: function modalKeepOpen(doingAjax) {
                if (!$(this.$el).data('erp.modal')) {
                    return;
                }
    
                if (doingAjax) {
                    $(this.$el).data('erp.modal').options.backdrop = 'static';
                    $(this.$el).data('erp.modal').options.keyboard = false;
                    $(this.$el).off('keydown.dismiss.erp.modal');
    
                } else {
                    $(this.$el).data('erp.modal').options.backdrop = true;
                    $(this.$el).data('erp.modal').options.keyboard = true;
                    $(this.$el).data('erp.modal').escape();
                }
            },
    
            /**
             * Create cookie
             *
             * Source: http://www.quirksmode.org/js/cookies.html
             */
            createCookie: function createCookie(name, value, days) {
                var expires;
    
                if (days) {
                    var date = new Date();
    
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    
                    expires = '; expires=' + date.toGMTString();
    
                } else {
                    expires = '';
                }
    
                document.cookie = name + '=' + value + expires + '; path=/';
            },
    
            /**
             * Read cookie
             *
             * Source: http://www.quirksmode.org/js/cookies.html
             */
            readCookie: function readCookie(name) {
                var nameEQ = name + '=';
                var ca = document.cookie.split(';');
                var i = 0;
    
                for(i=0; i < ca.length; i++) {
                    var c = ca[i];
    
                    while (' ' === c.charAt(0)) {
                        c = c.substring(1,c.length);
                    }
    
                    if (0 === parseInt(c.indexOf(nameEQ))) {
                        return c.substring(nameEQ.length, c.length);
                    }
                }
    
                return null;
            },
    
            /**
             * Pluck a certain field out of each object in a list
             *
             * Similar to wp_list_pluck
             */
            arrayPluck: function arrayPluck(array, key, isInt) {
                return array.map(function (obj) {
                    if (!obj.hasOwnProperty(key)) {
                        return null;
    
                    } else if (isInt) {
                        return parseInt(obj[key]);
    
                    } else {
                        return obj[key];
                    }
    
                });
            }
        }
    };
    
    Vue.directive('erp-sortable', {
        params: [
            'appendTo', 'axis', 'cancel', 'connectWith', 'containment', 'cursor', 'cursorAt',
            'dropOnEmpty', 'forcePlaceholderSize', 'forceHelperSize', 'grid', 'handle',
            'helper', 'items', 'opacity', 'placeholder', 'revert', 'scroll', 'scrollSensitivity',
            'scrollSpeed', 'scope', 'tolerance', 'zIndex',
    
            // callbacks
            'activate', 'beforeStop', 'change', 'deactivate', 'out', 'over', 'receive', 'remove', 'sort',
            'start', 'stop', 'update'
        ],
    
        bind: function bind() {
            var settings = $.extend({
                appendTo: 'parent',
                axis: false,
                cancel: '.ignore-sortable',
                connectWith: false,
                containment: false,
                cursor: 'auto',
                cursorAt: false,
                dropOnEmpty: true,
                forcePlaceholderSize: false,
                forceHelperSize: false,
                grid: false,
                handle: false,
                helper: 'original',
                items: '> *',
                opacity: false,
                placeholder: false,
                revert: false,
                scroll: true,
                scrollSensitivity: 20,
                scrollSpeed: 20,
                scope: 'default',
                tolerance: 'intersect',
                zIndex: 1000,
            }, this.params);
    
            // Callbacks
            settings.activate = this.params.activate ? this.vm[this.params.activate] : null;
            settings.beforeStop = this.params.beforeStop ? this.vm[this.params.beforeStop] : null;
            settings.change = this.params.change ? this.vm[this.params.change] : null;
            settings.deactivate = this.params.deactivate ? this.vm[this.params.deactivate] : null;
            settings.out = this.params.out ? this.vm[this.params.out] : null;
            settings.over = this.params.over ? this.vm[this.params.over] : null;
            settings.receive = this.params.receive ? this.vm[this.params.receive] : null;
            settings.remove = this.params.remove ? this.vm[this.params.remove] : null;
            settings.sort = this.params.sort ? this.vm[this.params.sort] : null;
            settings.start = this.params.start ? this.vm[this.params.start] : null;
            settings.stop = this.params.stop ? this.vm[this.params.stop] : null;
            settings.update = this.params.update ? this.vm[this.params.update] : null;
    
            $(this.el).sortable(settings);
        }
    });
    
    Vue.component('settings-activity-types', {
        /* global dealUtils, _ */
        template: '<div class="erp-deals-settings-activity-types"><h1>{{ i18n.activityTypes }}</h1><ul :class="[\'erp-subsubsub\', doingAjax ? \'disabled\' : \'\']"><li><a :class="[\'erp-nav-tab\', (\'active\' === filter) ? \'active\' : \'\']" href="#active" @click.prevent="filter = \'active\'">{{ i18n.active }}</a> ({{ activeTypeCounts }}) |</li><li><a :class="[\'erp-nav-tab\', (\'trashed\' === filter) ? \'active\' : \'\']" href="#trashed" @click.prevent="filter = \'trashed\'">{{ i18n.trashed }}</a> ({{ trashedTypeCounts }})</li></ul><ul class="erp-deals-settings-list" v-erp-sortable stop="updateTypeOrder"><li v-for="type in filteredTypes | orderBy \'order\'" class="clearfix" :id="\'activity-types-list-item-\' + type.id" :data-index="$index" :data-type-id="type.id"><div class="type-icon"><i :class="[\'picon picon-\' + type.icon]"></i></div><div class="type-title"><a :class="[doingAjax ? \'disabled\' : \'\']" href="#edit-activity" @click.prevent="openEditor(type)">{{ type.title }}</a></div><div v-if="\'active\' === filter" class="type-buttons"><button type="button" class="button button-small button-link" @click.prevent="openEditor(type)"><span class="dashicons dashicons-edit"></span> {{ i18n.edit }}</button> <button type="button" class="button button-small button-link" @click="trashType(type)"><span class="dashicons dashicons-trash"></span> {{ i18n.trash }}</button></div><div v-else class="type-buttons"><button type="button" class="button button-small button-link" @click="restoreType(type)"><span class="dashicons dashicons-image-rotate"></span> {{ i18n.restore }}</button></div></li><li v-if="!filteredTypes.length"><span v-if="\'active\' === filter">{{ i18n.noActiveTypeMsg }}</span> <span v-else>{{ i18n.noTrashedTypeMsg }}</span></li></ul><p><button class="button button-primary" :disabled="doingAjax" @click="openNewEditor">{{ i18n.addNew }}</button></p><div class="erp-deal-modal" id="deal-settings-act-type-modal" tabindex="-1"><div class="erp-deal-modal-dialog" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ editingType.id ? i18n.editActivityType : i18n.addActivityType }}</h4></div><div class="erp-deal-modal-body" id="deal-settings-act-type-modal-body"><div :class="[\'margin-bottom-20\', titleClass]"><label class="block-label"><strong>{{ i18n.name }}</strong></label> <input type="text" class="erp-deal-input" v-model="editingType.title" @focus="titleClass = \'\'"></div><div class="margin-bottom-20"><label class="block-label margin-bottom-4"><strong>{{ i18n.icon }}</strong></label><div class="activity-type-icons"><button v-for="typeIcon in typeIcons" type="button" :class="[\'button\', iconBtnClass(typeIcon)]" @click="editingType.icon = typeIcon"><span :class="[\'picon picon-\' + typeIcon]"></span></button></div></div></div><div class="erp-deal-modal-footer"><button type="button" class="button button-link" data-dismiss="erp-deal-modal" :disabled="doingAjax">{{ i18n.cancel }}</button> <button type="button" class="button button-primary" @click="saveType" :disabled="doingAjax">{{ i18n.save }}</button></div></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            activityTypes: {
                type: Array,
                required: true,
                default: [],
                twoWay: true
            },
        },
    
        data: function data() {
            return {
                filter: 'active',
                editingTypeSource: {},
                editingType: {},
                typeIcons: [
                    'ac-task', 'mail', 'ac-meeting', 'deadline', 'ac-call', 'ac-lunch',
                    'calendar', 'ac-downarrow', 'ac-document', 'ac-smartphone', 'ac-camera',
                    'ac-scissors', 'ac-cogs', 'ac-bubble', 'ac-uparrow', 'checkbox', 'ac-signpost',
                    'ac-shuffle', 'ac-addressbook', 'ac-linegraph', 'ac-picture', 'ac-car', 'ac-world',
                    'ac-search', 'ac-clip', 'ac-sound', 'ac-brush', 'ac-key', 'ac-padlock', 'ac-pricetag',
                    'ac-suitcase', 'ac-finish', 'ac-plane', 'ac-loop', 'ac-wifi', 'ac-truck', 'ac-cart',
                    'ac-bulb', 'bell', 'ac-presentation',
                ],
                doingAjax: false,
                titleClass: '',
            };
        },
    
        computed: {
            iconInUse: function iconInUse() {
                return this.activityTypes.map(function (type) {
                    return type.icon;
                });
            },
    
            filteredTypes: function filteredTypes() {
                var self = this;
    
                return this.activityTypes.filter(function (type) {
                    if (('active' === self.filter) && !type.deletedAt) {
                        return true;
                    } else if ('trashed' === self.filter && type.deletedAt) {
                        return true;
                    }
                });
            },
    
            activeTypeCounts: function activeTypeCounts() {
                return this.activityTypes.filter(function (type) { return !type.deletedAt; }).length;
            },
    
            trashedTypeCounts: function trashedTypeCounts() {
                return this.activityTypes.length - this.activeTypeCounts;
            }
        },
    
        methods: {
            openNewEditor: function openNewEditor() {
                this.editingTypeSource = {
                    icon: null,
                    id: 0,
                    order: 0,
                    title: '',
                };
    
                this.editingType = {
                    icon: null,
                    id: 0,
                    order: 0,
                    title: '',
                };
    
                $('#deal-settings-act-type-modal').erpDealModal();
            },
    
            openEditor: function openEditor(type) {
                this.editingTypeSource = type;
                this.editingType = $.extend(true, {}, type);
    
                $('#deal-settings-act-type-modal').erpDealModal();
            },
    
            updateType: function updateType(data) {
                var this$1 = this;
    
                if (this.editingType.id) {
                    for (var index in this.activityTypes) {
                        if (parseInt(this$1.activityTypes[index].id) === parseInt(this$1.editingType.id)) {
                            this$1.activityTypes.$set(index, this$1.camelizedObject(data.activity_type));
                        }
                    }
    
                } else {
                    this.activityTypes.$set(this.activityTypes.length, this.camelizedObject(data.activity_type));
                }
            },
    
            saveType: function saveType() {
                var self = this;
    
                if (!this.editingType.title) {
                    this.titleClass = 'input-error';
                    return false;
                }
    
                if(!this.editingType.icon) {
                    var iconNotInUse = _.difference(this.typeIcons, this.iconInUse);
    
                    this.editingType.icon = iconNotInUse[0];
                }
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity_type',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity_type: this.camelToUnderscoreObject(this.editingType)
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#deal-settings-act-type-modal-body'
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        if (self.editingType.id) {
                            for (var index in self.activityTypes) {
                                if (parseInt(self.activityTypes[index].id) === parseInt(self.editingType.id)) {
                                    self.activityTypes.$set(index, self.camelizedObject(response.data.activity_type));
                                }
                            }
    
                        } else {
                            self.activityTypes.$set(self.activityTypes.length, self.camelizedObject(response.data.activity_type));
                        }
    
                        $('#deal-settings-act-type-modal').erpDealModal('hide');
                    }
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            iconBtnClass: function iconBtnClass(typeIcon) {
                if (typeIcon === this.editingType.icon) {
                    return 'button-primary active';
                } else if (this.iconInUse.indexOf(typeIcon) >= 0 && (typeIcon !== this.editingTypeSource.icon)) {
                    return 'disabled';
                }
            },
    
            restoreType: function restoreType(type) {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity_type',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity_type: {
                            id: type.id,
                            restore: true
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#activity-types-list-item-' + type.id
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.editingType.id = response.data.activity_type.id;
                        self.updateType(response.data);
                    }
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            trashType: function trashType(type) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.trashTypeWarningMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: this.i18n.yesTrashIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: erpDealsGlobal.ajaxurl,
                            method: 'post',
                            dataType: 'json',
                            data: {
                                action: 'save_activity_type',
                                _wpnonce: erpDealsGlobal.nonce,
                                activity_type: { id: type.id },
                                trash: true
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                NProgress.configure({
                                    parent: '#activity-types-list-item-' + type.id
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                self.editingType.id = response.data.activity_type.id;
                                self.updateType(response.data);
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            updateTypeOrder: function updateTypeOrder(e, ui) {
                var self = this;
                var data = ui.item[0].dataset;
                var typeId = parseInt(data.typeId);
                var fromIndex = parseInt(data.index);
                var toIndex = parseInt($(ui.item).index());
    
                var filteredOutTypes = _.difference(this.activityTypes, this.filteredTypes);
                var clonedTypes = $.extend(true, [], this.filteredTypes);
    
                clonedTypes.move(fromIndex, toIndex);
    
                clonedTypes = clonedTypes.map(function (item, index) {
                    item.order = index;
                    return item;
                });
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'reorder_activity_types',
                        _wpnonce: erpDealsGlobal.nonce,
                        activities: clonedTypes
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#activity-types-list-item-' + typeId
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.activityTypes = clonedTypes.concat(filteredOutTypes);
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        }
    });
    
    Vue.component('settings-lost-reasons', {
        template: '<div class="erp-deals-settings-lost-reasons"><h1>{{ i18n.lostReasons }}</h1><p class="description">{{ i18n.lostReasonsTips }}</p><ul class="erp-deals-settings-list"><li v-if="!lostReasons.length">{{ i18n.nolostReasonsMsg }}</li><li v-for="reason in lostReasons" :id="\'deal-reason-id-\' + reason.id"><div v-if="reason.id === showEditor" class="deal-row"><div :class="[\'col-5\', inputClass]"><input type="text" class="erp-deal-input" v-model="editing.reason"></div><div class="col-1"><button type="button" class="button button-primary" @click="saveReason">{{ i18n.save }}</button> <button type="button" class="button" @click="reset">{{ i18n.cancel }}</button></div></div><div v-else class="clearfix"><div class="reason-text">{{ reason.reason }}</div><div class="pull-right type-buttons"><button type="button" class="button button-small button-link" @click="openEditor(reason)"><span class="dashicons dashicons-edit"></span> {{ i18n.edit }}</button> <button type="button" class="button button-small button-link" @click="deleteReason(reason)"><span class="dashicons dashicons-trash"></span> {{ i18n.delete }}</button></div></div></li><li v-if="showAddNewEditor" id="deal-reason-id-0"><div class="deal-row"><div :class="[\'col-5\', inputClass]"><input type="text" class="erp-deal-input" v-model="editing.reason"></div><div class="col-1"><button type="button" class="button button-primary" @click="saveReason">{{ i18n.save }}</button> <button type="button" class="button" @click="reset">{{ i18n.cancel }}</button></div></div></li></ul><button type="button" class="button button-primary" @click="openNewEditor">{{ i18n.addNew }}</button></div>',
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            lostReasons: {
                type: Array,
                required: true,
                default: [],
                twoWay: true,
            }
        },
    
        data: function data$1() {
            return {
                showAddNewEditor: false,
                newReason: '',
                showEditor: 0,
                editing: {},
                inputClass: ''
            };
        },
    
        computed: {
    
        },
    
        methods: {
            openEditor: function openEditor$1(reason) {
                this.showAddNewEditor = false;
                this.showEditor = reason.id;
                this.editing = $.extend(true, {}, reason);
            },
    
            openNewEditor: function openNewEditor$1() {
                this.showAddNewEditor = true;
                this.showEditor = 0;
                this.editing = {
                    id: 0, reason: ''
                };
            },
    
            reset: function reset() {
                this.showAddNewEditor = false;
                this.showEditor = 0;
                this.editing = {};
                this.inputClass = '';
            },
    
            saveReason: function saveReason() {
                var this$1 = this;
    
                var self = this;
    
                if (!this.editing.reason) {
                    this.inputClass = 'input-error';
                    return false;
                }
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_lost_reason',
                        _wpnonce: erpDealsGlobal.nonce,
                        lost_reason: this.editing
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#deal-reason-id-' + self.editing.id
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        if (self.editing.id) {
                            for (var index in self.lostReasons) {
                                if (parseInt(self.lostReasons[index].id) === parseInt(self.editing.id)) {
                                    self.lostReasons.$set(index, response.data.lost_reason);
                                }
                            }
    
                        } else {
                            self.lostReasons.$set(self.lostReasons.length, response.data.lost_reason);
                        }
    
                        this$1.reset();
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            deleteReason: function deleteReason(reason) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteReasonWarningMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: this.i18n.yesDeleteIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: erpDealsGlobal.ajaxurl,
                            method: 'post',
                            dataType: 'json',
                            data: {
                                action: 'delete_lost_reason',
                                _wpnonce: erpDealsGlobal.nonce,
                                lost_reason_id: reason.id
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                NProgress.configure({
                                    parent: '#deal-reason-id-' + reason.id
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                self.lostReasons.$remove(reason);
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
        },
    
        watch: {
            showEditor: function showEditor(newVal) {
                $('#deal-reason-id-' + newVal).find('input').focus();
            }
        }
    });
    
    /**
     * Sometimes we must set the parent close to interaction
     * point. In that case we'll choose another parent and after
     * start we'll call this function to set back the default parent
     */
    window.setDefaultNProgressParent = function() {
        NProgress.configure({
            parent: '#wpadminbar',
            afterDone: null
        });
    };
    window.setDefaultNProgressParent();
    
    Vue.config.debug = !!(erpDealsGlobal.scriptDebug);
    
    Array.prototype.move = function (from, to) {
        this.splice(to, 0, this.splice(from, 1)[0]);
    };
    
    if ($('#erp-deals-settings-page').length) {
        $('#mainform').on('submit', function (e) {
            e.preventDefault();
        });
    
        /* global dealUtils */
    
        new Vue({
            el: '#erp-deals-settings-page',
    
            mixins: [dealUtils],
    
            data: {
                i18n: erpDealsGlobal.i18n,
                isReady: false,
                settingsTabs: {
                    pipeline: erpDealsGlobal.i18n.pipeline,
                    activityTypes: erpDealsGlobal.i18n.activityTypes,
                    lostReasons: erpDealsGlobal.i18n.lostReasons
                },
                currentTab: 'pipeline',
                pipelines: [],
                lifeStages: {},
                doingAjax: false,
                defaultStage: {
                    title: '',
                    pipelineId: '',
                    probability: '',
                    isRottingOn: '',
                    rottingAfter: '',
                    lifeStage: 0,
                    order: 0,
                },
                editingStageSource: {},
                editingStage: {},
                stageTitleClass: '',
                transferToStage: 0,
                showDeleteStageDialogue: false,
    
                defaultPipeline: {
                    title: '',
                    stage: {
                        title: '',
                        pipelineId: '',
                        probability: '',
                        isRottingOn: '',
                        rottingAfter: '',
                        lifeStage: 0,
                        order: 0,
                    }
                },
                editingPipelineSource: {},
                editingPipeline: {
                    title: '',
                    stage: {
                        title: '',
                        pipelineId: '',
                        probability: '',
                        isRottingOn: '',
                        rottingAfter: '',
                        lifeStage: 0,
                        order: 0,
                    }
                },
                pipelineTitleClass: '',
                showDeletePipelineDialogue: false,
    
                activityTypes: [],
                lostReasons: [],
            },
    
            created: function created() {
                var this$1 = this;
    
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_erp_deals_settings',
                        _wpnonce: erpDealsGlobal.nonce,
                    }
                }).done(function (response) {
                    if (response.success) {
                        self.$set('pipelines', this$1.camelizedArray(response.data.pipelines));
                        self.$set('lifeStages', response.data.life_stages);
                        self.$set('activityTypes', this$1.camelizedArray(response.data.activity_types));
                        self.$set('lostReasons', response.data.lost_reasons);
    
                        self.isReady = true;
                    }
                });
            },
    
            ready: function ready() {
                var self = this;
    
                $('#deal-settings-stage').on('hidden.erp.modal', function () {
                    self.stageTitleClass = '';
                    self.transferToStage = 0;
                });
    
                $('#deal-settings-pipeline').on('hidden.erp.modal', function () {
                    self.editingPipeline= {
                        title: '',
                        stage: {
                            title: '',
                            pipelineId: '',
                            probability: '',
                            isRottingOn: '',
                            rottingAfter: '',
                            lifeStage: 0,
                            order: 0,
                        }
                    };
                    self.stageTitleClass = '';
                    self.pipelineTitleClass = '';
                    self.transferToStage = 0;
                });
            },
    
            computed: {
                transferableStages: function transferableStages() {
                    var self = this;
    
                    var pipeline = this.pipelines.filter(function (pipeline) {
                        return parseInt(pipeline.id) === parseInt(self.editingStage.pipelineId);
                    });
    
                    pipeline = pipeline[0];
    
                    var stages = pipeline.stages.filter(function (stage) {
                        return parseInt(stage.id) !== parseInt(self.editingStage.id);
                    });
    
                    if (stages.length) {
                        this.transferToStage = stages[0].id;
                    } else {
                        this.transferToStage = 0;
                    }
    
                    return stages;
                }
            },
    
            methods: {
                updateStageOrder: function updateStageOrder(e, ui) {
                    var data = ui.item[0].dataset;
                    var pipelineIndex = parseInt(data.pipelineIndex);
                    var pipelineId = parseInt(data.pipelineId);
                    var fromIndex = parseInt(data.index);
                    var toIndex = parseInt($(ui.item).index());
    
                    if (fromIndex === toIndex) {
                        return false;
                    }
    
                    var pipeline = this.pipelines.filter(function (pipe) {
                        return parseInt(pipe.id) === pipelineId;
                    });
    
                    pipeline = pipeline[0];
    
                    var clonedStages = $.extend(true, [], pipeline.stages);
    
                    clonedStages.move(fromIndex, toIndex);
    
                    clonedStages = clonedStages.map(function (item, index) {
                        item.order = index;
                        return item;
                    });
    
    
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'reorder_stages',
                            _wpnonce: erpDealsGlobal.nonce,
                            stages: clonedStages
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#settings-pipeline-id-' + pipelineId,
                                afterDone: function afterDone(nprogress) {
                                    nprogress.remove();
                                }
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.pipelines[pipelineIndex].stages = clonedStages;
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
    
                openStageEditorModal: function openStageEditorModal(stage) {
                    this.editingStageSource = stage;
                    this.editingStage = $.extend(true, {}, stage);
    
                    this.showDeleteStageDialogue = false;
                    $('#deal-settings-stage').erpDealModal();
                },
    
                addNewStage: function addNewStage(pipeline) {
                    this.editingStage = $.extend(true, {}, this.defaultStage);
                    this.editingStage.pipelineId = pipeline.id;
    
                    this.showDeleteStageDialogue = false;
                    $('#deal-settings-stage').erpDealModal();
                },
    
                saveStage: function saveStage() {
                    var self = this;
    
                    if (!this.editingStage.title) {
                        this.stageTitleClass = 'input-error';
                        return false;
                    }
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'save_stage',
                            _wpnonce: erpDealsGlobal.nonce,
                            stage: this.camelToUnderscoreObject(this.editingStage)
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-stage-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            var pipeline = self.pipelines.filter(function (pipeline) {
                                return parseInt(pipeline.id) === parseInt(self.editingStage.pipelineId);
                            });
    
                            pipeline = pipeline[0];
    
                            if (self.editingStage.id) {
                                for (var index in pipeline.stages) {
                                    if (parseInt(pipeline.stages[index].id) === parseInt(self.editingStage.id)) {
                                        pipeline.stages.$set(index, self.camelizedObject(response.data.stage));
                                    }
                                }
    
                            } else {
                                pipeline.stages.$set(pipeline.stages.length, self.camelizedObject(response.data.stage));
                            }
    
                            $('#deal-settings-stage').erpDealModal('hide');
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
    
                openDeleteStageDialogue: function openDeleteStageDialogue() {
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'get_stage_deals_count',
                            _wpnonce: erpDealsGlobal.nonce,
                            stage_id: this.editingStage.id
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-stage-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.editingStage.dealCount = response.data.deal_count;
                            self.showDeleteStageDialogue = true;
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
    
                deleteStage: function deleteStage() {
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'delete_stage',
                            _wpnonce: erpDealsGlobal.nonce,
                            stage_id: this.editingStage.id,
                            transfer_to_stage_id: this.transferToStage
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-stage-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            var pipeline = self.pipelines.filter(function (pipeline) {
                                return parseInt(pipeline.id) === parseInt(self.editingStage.pipelineId);
                            });
    
                            pipeline = pipeline[0];
    
                            // get stages after delete
                            $.ajax({
                                url: erpDealsGlobal.ajaxurl,
                                method: 'post',
                                dataType: 'json',
                                data: {
                                    action: 'get_pipeline_stages',
                                    _wpnonce: erpDealsGlobal.nonce,
                                    pipeline_id: pipeline.id,
                                }
    
                            }).done(function (response) {
                                if (response.success) {
                                    pipeline.stages = self.camelizedArray(response.data.stages);
                                    $('#deal-settings-stage').erpDealModal('hide');
                                }
    
                            }).always(function () {
                                self.doingAjax = false;
                                NProgress.done();
                                window.setDefaultNProgressParent();
                            });
                        }
                    });
                },
    
                openPipelineEditorModal: function openPipelineEditorModal(pipeline) {
                    this.editingPipelineSource = pipeline;
                    this.editingPipeline = $.extend(true, {}, pipeline);
    
                    this.showDeletePipelineDialogue = false;
                    $('#deal-settings-pipeline').erpDealModal();
                },
    
                addNewPipeline: function addNewPipeline() {
                    this.editingPipeline = $.extend(true, {}, this.defaultPipeline);
    
                    this.showDeletePipelineDialogue = false;
                    $('#deal-settings-pipeline').erpDealModal();
                },
    
                savePipeline: function savePipeline() {
                    var self = this;
    
                    if (!this.editingPipeline.title) {
                        this.pipelineTitleClass = 'input-error';
                        return false;
                    }
    
                    if (!this.editingPipeline.id && !this.editingPipeline.stage.title) {
                        this.stageTitleClass = 'input-error';
                        return false;
                    }
    
                    var pipeline = {
                        title: this.editingPipeline.title
                    };
    
                    if (this.editingPipeline.id) {
                        pipeline.id = this.editingPipeline.id;
                    } else {
                        pipeline.stage = {
                            title: this.editingPipeline.stage.title,
                            probability: this.editingPipeline.stage.probability,
                            is_rotting_on: this.editingPipeline.stage.isRottingOn,
                            rotting_after: this.editingPipeline.stage.rottingAfter,
                            life_stage: this.editingPipeline.stage.lifeStage,
                            order: this.editingPipeline.stage.order,
                        };
                    }
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'save_pipeline',
                            _wpnonce: erpDealsGlobal.nonce,
                            pipeline: pipeline
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-pipeline-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            if (!pipeline.id) {
                                self.pipelines.$set(self.pipelines.length, response.data.pipeline);
    
                            } else {
                                for (var i in self.pipelines) {
                                    if (parseInt(self.pipelines[i].id) === parseInt(response.data.pipeline.id)) {
                                        self.pipelines[i].title = response.data.pipeline.title;
                                    }
                                }
                            }
    
                            $('#deal-settings-pipeline').erpDealModal('hide');
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
    
                openDeletePipelineDialogue: function openDeletePipelineDialogue() {
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'get_pipeline_deals_count',
                            _wpnonce: erpDealsGlobal.nonce,
                            pipeline_id: this.editingPipeline.id
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-pipeline-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.editingPipeline.dealCount = response.data.deal_count;
                            self.showDeletePipelineDialogue = true;
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
    
                transferablePipelines: function transferablePipelines() {
                    var self = this;
    
                    var pipelines = this.pipelines.filter(function (pipeline) {
                        return parseInt(pipeline.id) !== parseInt(self.editingPipeline.id);
                    });
    
                    this.transferToStage = pipelines[0].stages[0].id;
    
                    return pipelines;
                },
    
                deletePipeline: function deletePipeline() {
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'delete_pipeline',
                            _wpnonce: erpDealsGlobal.nonce,
                            pipeline_id: this.editingPipeline.id,
                            transfer_to_stage_id: this.transferToStage
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                            NProgress.configure({
                                parent: '#deal-settings-pipeline-body'
                            });
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.pipelines.$remove(self.editingPipelineSource);
                            $('#deal-settings-pipeline').erpDealModal('hide');
                        }
                    }).always(function () {
                        self.doingAjax = false;
                        NProgress.done();
                        window.setDefaultNProgressParent();
                    });
                },
            }
    
        });
    
    }
})(jQuery);
