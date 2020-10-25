+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("erpTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.erpTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){if(a(b.target).is(this))return b.handleObj.handler.apply(this,arguments)}})})}(jQuery),function(a){"use strict";function b(b,d){return this.each(function(){var e=a(this),f=e.data("erp.modal"),g=a.extend({},c.DEFAULTS,e.data(),"object"==typeof b&&b);f||e.data("erp.modal",f=new c(this,g)),"string"==typeof b?f[b](d):g.show&&f.show(d)})}var c=function(b,c){this.options=c,this.$body=a(document.body),this.$element=a(b),this.$dialog=this.$element.find(".erp-deal-modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.options.remote&&this.$element.find(".erp-deal-modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.erp.modal")},this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=300,c.BACKDROP_TRANSITION_DURATION=150,c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},c.prototype.show=function(b){var d=this,e=a.Event("show.erp.modal",{relatedTarget:b});this.$element.trigger(e),this.isShown||e.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("erp-deal-modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.erp.modal",'[data-dismiss="erp-deal-modal"]',a.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.erp.modal",function(){d.$element.one("mouseup.dismiss.erp.modal",function(b){a(b.target).is(d.$element)&&(d.ignoreBackdropClick=!0)})}),this.backdrop(function(){var e=a.support.transition&&d.$element.hasClass("fade");d.$element.parent().length||d.$element.appendTo(d.$body),d.$element.show().scrollTop(0),d.adjustDialog(),e&&d.$element[0].offsetWidth,d.$element.addClass("in"),d.enforceFocus();var f=a.Event("shown.erp.modal",{relatedTarget:b});e?d.$dialog.one("erpTransitionEnd",function(){d.$element.trigger("focus").trigger(f)}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))},c.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.erp.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),a(document).off("focusin.erp.modal"),this.$element.removeClass("in").off("click.dismiss.erp.modal").off("mouseup.dismiss.erp.modal"),this.$dialog.off("mousedown.dismiss.erp.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one("erpTransitionEnd",a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())},c.prototype.enforceFocus=function(){a(document).off("focusin.erp.modal").on("focusin.erp.modal",a.proxy(function(a){document===a.target||this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")},this))},c.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.erp.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.erp.modal")},c.prototype.resize=function(){this.isShown?a(window).on("resize.erp.modal",a.proxy(this.handleUpdate,this)):a(window).off("resize.erp.modal")},c.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.$body.removeClass("erp-deal-modal-open"),a.resetAdjustments(),a.resetScrollbar(),a.$element.trigger("hidden.erp.modal")})},c.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},c.prototype.backdrop=function(b){var d=this,e=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var f=a.support.transition&&e;if(this.$backdrop=a(document.createElement("div")).addClass("erp-deal-modal-backdrop "+e).appendTo(this.$body),this.$element.on("click.dismiss.erp.modal",a.proxy(function(a){if(this.ignoreBackdropClick)return void(this.ignoreBackdropClick=!1);a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide())},this)),f&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;f?this.$backdrop.one("erpTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var g=function(){d.removeBackdrop(),b&&b()};a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("erpTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()}else b&&b()},c.prototype.handleUpdate=function(){this.adjustDialog()},c.prototype.adjustDialog=function(){var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""})},c.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},c.prototype.checkScrollbar=function(){var a=window.innerWidth;if(!a){var b=document.documentElement.getBoundingClientRect();a=b.right-Math.abs(b.left)}this.bodyIsOverflowing=document.body.clientWidth<a,this.scrollbarWidth=this.measureScrollbar()},c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"",this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},c.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad)},c.prototype.measureScrollbar=function(){var a=document.createElement("div");a.className="erp-deal-modal-scrollbar-measure",this.$body.append(a);var b=a.offsetWidth-a.clientWidth;return this.$body[0].removeChild(a),b};var d=a.fn.erpDealModal;a.fn.erpDealModal=b,a.fn.erpDealModal.Constructor=c,a.fn.erpDealModal.noConflict=function(){return a.fn.erpDealModal=d,this},a(document).on("click.erp.modal.data-api",'[data-toggle="erp-deal-modal"]',function(c){var d=a(this),e=d.attr("href"),f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),g=f.data("erp.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());d.is("a")&&c.preventDefault(),f.one("show.erp.modal",function(a){a.isDefaultPrevented()||f.one("hidden.erp.modal",function(){d.is(":visible")&&d.trigger("focus")})}),b.call(f,g,this)})}(jQuery),function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("erp.tooltip"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("erp.tooltip",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",a,b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="erp-tooltip" role="tooltip"><div class="erp-tooltip-arrow"></div><div class="erp-tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},c.prototype.init=function(b,c,d){if(this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.$viewport=this.options.viewport&&a(a.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},c.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("erp."+this.type);return c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c)),b instanceof a.Event&&(c.inState["focusin"==b.type?"focus":"hover"]=!0),c.tip().hasClass("in")||"in"==c.hoverState?void(c.hoverState="in"):(clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show())},c.prototype.isInStateTrue=function(){for(var a in this.inState)if(this.inState[a])return!0;return!1},c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("erp."+this.type);if(c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c)),b instanceof a.Event&&(c.inState["focusout"==b.type?"focus":"hover"]=!1),!c.isInStateTrue()){if(clearTimeout(c.timeout),c.hoverState="out",!c.options.delay||!c.options.delay.hide)return c.hide();c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)}},c.prototype.show=function(){var b=a.Event("show.erp."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);var d=a.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(b.isDefaultPrevented()||!d)return;var e=this,f=this.tip(),g=this.getUID(this.type);this.setContent(),f.attr("id",g),this.$element.attr("aria-describedby",g),this.options.animation&&f.addClass("fade");var h="function"==typeof this.options.placement?this.options.placement.call(this,f[0],this.$element[0]):this.options.placement,i=/\s?auto?\s?/i,j=i.test(h);j&&(h=h.replace(i,"")||"top"),f.detach().css({top:0,left:0,display:"block"}).addClass(h).data("erp."+this.type,this),this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element),this.$element.trigger("inserted.erp."+this.type);var k=this.getPosition(),l=f[0].offsetWidth,m=f[0].offsetHeight;if(j){var n=h,o=this.getPosition(this.$viewport);h="bottom"==h&&k.bottom+m>o.bottom?"top":"top"==h&&k.top-m<o.top?"bottom":"right"==h&&k.right+l>o.width?"left":"left"==h&&k.left-l<o.left?"right":h,f.removeClass(n).addClass(h)}var p=this.getCalculatedOffset(h,k,l,m);this.applyPlacement(p,h);var q=function(){var a=e.hoverState;e.$element.trigger("shown.erp."+e.type),e.hoverState=null,"out"==a&&e.leave(e)};a.support.transition&&this.$tip.hasClass("fade")?f.one("erpTransitionEnd",q).emulateTransitionEnd(c.TRANSITION_DURATION):q()}},c.prototype.applyPlacement=function(b,c){var d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),b.top+=g,b.left+=h,a.offset.setOffset(d[0],a.extend({using:function(a){d.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),d.addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;"top"==c&&j!=f&&(b.top=b.top+f-j);var k=this.getViewportAdjustedDelta(c,b,i,j);k.left?b.left+=k.left:b.top+=k.top;var l=/top|bottom/.test(c),m=l?2*k.left-e+i:2*k.top-f+j,n=l?"offsetWidth":"offsetHeight";d.offset(b),this.replaceArrow(m,d[0][n],l)},c.prototype.replaceArrow=function(a,b,c){this.arrow().css(c?"left":"top",50*(1-a/b)+"%").css(c?"top":"left","")},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".erp-tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),e.$element&&e.$element.removeAttr("aria-describedby").trigger("hidden.erp."+e.type),b&&b()}var e=this,f=a(this.$tip),g=a.Event("hide.erp."+this.type);if(this.$element.trigger(g),!g.isDefaultPrevented())return f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one("erpTransitionEnd",d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),this.hoverState=null,this},c.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},c.prototype.hasContent=function(){return this.getTitle()},c.prototype.getPosition=function(b){b=b||this.$element;var c=b[0],d="BODY"==c.tagName,e=c.getBoundingClientRect();null==e.width&&(e=a.extend({},e,{width:e.right-e.left,height:e.bottom-e.top}));var f=window.SVGElement&&c instanceof window.SVGElement,g=d?{top:0,left:0}:f?null:b.offset(),h={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},i=d?{width:a(window).width(),height:a(window).height()}:null;return a.extend({},e,h,i,g)},c.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},c.prototype.getViewportAdjustedDelta=function(a,b,c,d){var e={top:0,left:0};if(!this.$viewport)return e;var f=this.options.viewport&&this.options.viewport.padding||0,g=this.getPosition(this.$viewport);if(/right|left/.test(a)){var h=b.top-f-g.scroll,i=b.top+f-g.scroll+d;h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,k=b.left+f+c;j<g.left?e.left=g.left-j:k>g.right&&(e.left=g.left+g.width-k)}return e},c.prototype.getTitle=function(){var a=this.$element,b=this.options;return a.attr("data-original-title")||("function"==typeof b.title?b.title.call(a[0]):b.title)},c.prototype.getUID=function(a){do{a+=~~(1e6*Math.random())}while(document.getElementById(a));return a},c.prototype.tip=function(){if(!this.$tip&&(this.$tip=a(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".erp-tooltip-arrow")},c.prototype.enable=function(){this.enabled=!0},c.prototype.disable=function(){this.enabled=!1},c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},c.prototype.toggle=function(b){var c=this;b&&((c=a(b.currentTarget).data("erp."+this.type))||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("erp."+this.type,c))),b?(c.inState.click=!c.inState.click,c.isInStateTrue()?c.enter(c):c.leave(c)):c.tip().hasClass("in")?c.leave(c):c.enter(c)},c.prototype.destroy=function(){var a=this;clearTimeout(this.timeout),this.hide(function(){a.$element.off("."+a.type).removeData("erp."+a.type),a.$tip&&a.$tip.detach(),a.$tip=null,a.$arrow=null,a.$viewport=null,a.$element=null})};var d=a.fn.erpTooltip;a.fn.erpTooltip=b,a.fn.erpTooltip.Constructor=c,a.fn.erpTooltip.noConflict=function(){return a.fn.erpTooltip=d,this}}(jQuery);var deepClone=function(a){if(Array.isArray(a))return a.map(deepClone);if(a&&"object"==typeof a){for(var b={},c=Object.keys(a),d=0,e=c.length;d<e;d++){var f=c[d];b[f]=deepClone(a[f])}return b}return a},multiselectMixin={data:function(){return{search:"",isOpen:!1,value:this.selected?deepClone(this.selected):this.multiple?[]:null}},props:{localSearch:{type:Boolean,default:!0},options:{type:Array,required:!0},multiple:{type:Boolean,default:!1},selected:{},key:{type:String,default:!1},label:{type:String,default:!1},searchable:{type:Boolean,default:!0},clearOnSelect:{type:Boolean,default:!0},hideSelected:{type:Boolean,default:!1},placeholder:{type:String,default:"Select option"},maxHeight:{type:Number,default:300},allowEmpty:{type:Boolean,default:!0},resetAfter:{type:Boolean,default:!1},closeOnSelect:{type:Boolean,default:!0},customLabel:{type:Function,default:!1},taggable:{type:Boolean,default:!1},tagPlaceholder:{type:String,default:"Press enter to create a tag"},max:{type:Number,default:!1},id:{default:null}},created:function(){this.searchable&&this.adjustSearch()},computed:{filteredOptions:function(){var a=this.search||"",b=this.hideSelected?this.options.filter(this.isNotSelected):this.options;return this.localSearch&&(b=this.$options.filters.filterBy(b,this.search)),this.taggable&&a.length&&!this.isExistingOption(a)&&b.unshift({isTag:!0,label:a}),b},valueKeys:function(){var a=this;return this.key?this.multiple?this.value.map(function(b){return b[a.key]}):this.value[this.key]:this.value},optionKeys:function(){var a=this;return this.label?this.options.map(function(b){return b[a.label]}):this.options},currentOptionLabel:function(){return this.getOptionLabel(this.value)}},watch:{value:function(){this.resetAfter&&(this.$set("value",null),this.$set("search",null),this.$set("selected",null)),this.adjustSearch()},search:function(){this.search!==this.currentOptionLabel&&this.$emit("search-change",this.search,this.id)},selected:function(a,b){this.value=deepClone(this.selected)}},methods:{isExistingOption:function(a){return!!this.options&&this.optionKeys.indexOf(a)>-1},isSelected:function(a){if(!this.value)return!1;var b=this.key?a[this.key]:a;return this.multiple?this.valueKeys.indexOf(b)>-1:this.valueKeys===b},isNotSelected:function(a){return!this.isSelected(a)},getOptionLabel:function(a){return"object"!=typeof a||null===a?a:this.customLabel?this.customLabel(a):this.label&&a[this.label]?a[this.label]:a.label?a.label:void 0},select:function(a){if(!this.max||!this.multiple||this.value.length!==this.max)if(a.isTag)this.$emit("tag",a.label,this.id),this.search="";else{if(this.multiple)this.isNotSelected(a)?(this.value.push(a),this.$emit("select",deepClone(a),this.id),this.$emit("update",deepClone(this.value),this.id)):this.removeElement(a);else{var b=this.isSelected(a);if(b&&!this.allowEmpty)return;this.value=b?null:a,this.$emit("select",deepClone(a),this.id),this.$emit("update",deepClone(this.value),this.id)}this.closeOnSelect&&this.deactivate()}},removeElement:function(a){if(this.allowEmpty||!(this.value.length<=1)){if(this.multiple&&"object"==typeof a){var b=this.valueKeys.indexOf(a[this.key]);this.value.splice(b,1)}else this.value.$remove(a);this.$emit("remove",deepClone(a),this.id),this.$emit("update",deepClone(this.value),this.id)}},removeLastElement:function(){0===this.search.length&&Array.isArray(this.value)&&this.removeElement(this.value[this.value.length-1])},activate:function(){this.isOpen||(this.isOpen=!0,this.searchable?(this.search="",this.$els.search.focus()):this.$el.focus(),this.$emit("open",this.id))},deactivate:function(){this.isOpen&&(this.isOpen=!1,this.searchable?(this.$els.search.blur(),this.adjustSearch()):this.$el.blur(),this.$emit("close",deepClone(this.value),this.id))},adjustSearch:function(){this.searchable&&this.clearOnSelect&&(this.search=this.multiple?"":this.currentOptionLabel)},toggle:function(){this.isOpen?this.deactivate():this.activate()}}},pointerMixin={data:function(){return{pointer:0,visibleElements:this.maxHeight/this.optionHeight}},props:{showPointer:{type:Boolean,default:!0},optionHeight:{type:Number,default:40}},computed:{pointerPosition:function(){return this.pointer*this.optionHeight}},watch:{filteredOptions:function(){this.pointerAdjust()}},methods:{addPointerElement:function(){this.filteredOptions.length>0&&this.select(this.filteredOptions[this.pointer]),this.pointerReset()},pointerForward:function(){this.pointer<this.filteredOptions.length-1&&(this.pointer++,this.$els.list.scrollTop<=this.pointerPosition-this.visibleElements*this.optionHeight&&(this.$els.list.scrollTop=this.pointerPosition-(this.visibleElements-1)*this.optionHeight))},pointerBackward:function(){this.pointer>0&&(this.pointer--,this.$els.list.scrollTop>=this.pointerPosition&&(this.$els.list.scrollTop=this.pointerPosition))},pointerReset:function(){this.closeOnSelect&&(this.pointer=0,this.$els.list&&(this.$els.list.scrollTop=0))},pointerAdjust:function(){this.pointer>=this.filteredOptions.length-1&&(this.pointer=this.filteredOptions.length?this.filteredOptions.length-1:0)},pointerSet:function(a){this.pointer=a}}};Vue.component("multiselect",{template:'<div tabindex="0" :class="{ \'multiselect--active\': isOpen, \'multiselect--disabled\': disabled }" @focus="activate()" @blur="searchable ? false : deactivate()" @keydown.self.down.prevent="pointerForward()" @keydown.self.up.prevent="pointerBackward()" @keydown.enter.stop.prevent.self="addPointerElement()" @keyup.esc="deactivate()" class="multiselect"><div @mousedown.prevent="toggle()" class="multiselect__select"></div><div v-el:tags class="multiselect__tags"><span v-if="multiple" v-for="option in visibleValue" track-by="$index" onmousedown="event.preventDefault()" class="multiselect__tag"><span v-text="getOptionLabel(option)"></span> <i aria-hidden="true" tabindex="1" @keydown.enter.prevent="removeElement(option)" @mousedown.prevent="removeElement(option)" class="multiselect__tag-icon"></i></span><template v-if="value && value.length > limit"><strong v-text="limitText(value.length - limit)"></strong></template><div v-show="loading" transition="multiselect__loading" class="multiselect__spinner"></div><input name="search" type="text" autocomplete="off" :placeholder="placeholder" v-el:search v-if="searchable" v-model="search" :disabled="disabled" @focus.prevent="activate()" @blur.prevent="deactivate()" @keyup.esc="deactivate()" @keyup.down="pointerForward()" @keyup.up="pointerBackward()" @keydown.enter.stop.prevent.self="addPointerElement()" @keydown.delete="removeLastElement()" class="multiselect__input"> <span v-if="!searchable && !multiple" class="multiselect__single" v-text="currentOptionLabel || placeholder"></span></div><ul transition="multiselect" :style="{ maxHeight: maxHeight + \'px\' }" v-el:list v-show="isOpen" class="multiselect__content"><slot name="beforeList"></slot><li v-if="multiple && max === value.length"><span class="multiselect__option"><slot name="maxElements">Maximum of {{ max }} options selected. First remove a selected option to select another.</slot></span></li><template v-if="!max || value.length < max"><li v-for="option in filteredOptions" track-by="$index" tabindex="0" :class="{ \'multiselect__option--highlight\': $index === pointer && this.showPointer, \'multiselect__option--selected\': !isNotSelected(option) }" class="multiselect__option" @mousedown.prevent="select(option)" @mouseenter="pointerSet($index)" :data-select="option.isTag ? tagPlaceholder : selectLabel" :data-selected="selectedLabel" :data-deselect="deselectLabel"><partial :name="optionPartial" v-if="optionPartial.length"></partial><span v-else v-text="getOptionLabel(option)"></span></li></template><li v-show="filteredOptions.length === 0 && search"><span class="multiselect__option"><slot name="noResult">No elements found. Consider changing the search query.</slot></span></li><slot name="afterList"></slot></ul></div>',mixins:[multiselectMixin,pointerMixin],props:{optionPartial:{type:String,default:""},selectLabel:{type:String,default:"Press enter to select"},selectedLabel:{type:String,default:"Selected"},deselectLabel:{type:String,default:"Press enter to remove"},showLabels:{type:Boolean,default:!0},limit:{type:Number,default:99999},limitText:{type:Function,default:function(a){return"and "+a+" more"}},loading:{type:Boolean,default:!1},disabled:{type:Boolean,default:!1}},computed:{visibleValue:function(){return this.multiple?this.value.slice(0,this.limit):this.value}},ready:function(){this.showLabels||(this.deselectLabel=this.selectedLabel=this.selectLabel="")}});

;(function($) {
    'use strict';

    /* jshint unused: false */
    var dealAttachment = {
        data: function data() {
            return {
                fileFrame: null
            };
        },
    
        computed: {
            currentUser: function currentUser() {
                var self = this;
                var user = this.users.crmAgents.filter(function (agent) {
                    return parseInt(agent.id) === parseInt(self.users.currentUserId);
                });
    
                return user[0];
            }
        },
    
        methods: {
            uploadFiles: function uploadFiles(e) {
                e.preventDefault();
    
                var self = this;
                var selectedFile = {
                    id: 0,
                    filename: '',
                    url: '',
                    filesize: '',
                    type: ''
                };
    
                // If the media frame already exists, reopen it.
                if ( this.fileFrame ) {
                    this.fileFrame.open();
                    return;
                }
    
                var fileStates = [
                    // Main states.
                    new wp.media.controller.Library({
                        library:   wp.media.query(),
                        multiple:  true,
                        title:     self.i18n.addAttachments,
                        priority:  20,
                        filterable: 'uploaded'
                    })
                ];
    
                // Create the media frame.
                this.fileFrame = wp.media.frames.dealAttachment = wp.media({
                    // Set the title of the modal.
                    title: self.i18n.selectFiles,
                    library: {
                        type: ''
                    },
                    button: {
                        text: self.i18n.selectFiles
                    },
                    multiple: true,
                    states: fileStates
                });
    
                // When an image is selected, run a callback.
                this.fileFrame.on('select', function () {
                    var selection = self.fileFrame.state().get('selection');
    
                    selection.map(function (attachment) {
                        attachment = attachment.toJSON();
    
                        if (attachment.id) {
                            selectedFile.id = attachment.id;
                        }
    
                        if (attachment.filename) {
                            selectedFile.filename = attachment.filename;
                        }
    
                        if (attachment.url) {
                            selectedFile.url = attachment.url;
                        }
    
                        if (attachment.filesizeHumanReadable) {
                            selectedFile.filesize = attachment.filesizeHumanReadable;
                        }
    
                        if (attachment.type) {
                            selectedFile.type = attachment.type;
                        }
    
                        self.onSelectFile(selectedFile);
    
                    });
    
                });
    
                // Set post to 0 and set our custom type.
                this.fileFrame.on('ready', function () {
                    self.fileFrame.uploader.options.uploader.params = {
                        type: 'erp-deal-attachment',
                        deal_id: self.deal.id
                    };
                });
    
                // Finally, open the modal.
                this.fileFrame.open();
            },
    
            onSelectFile: function onSelectFile(attachment) {
                // check if selected media is already attached or not
                var duplicate = this.deal.attachments.filter(function (dealAttachment) {
                    return parseInt(dealAttachment.id) === parseInt(attachment.id);
                });
    
                // prevent duplicating
                if (!duplicate.length) {
                    // that's right baby, you have to extend it!!!
                    // otherwise attachments will be an array of the same object.
                    var newAttachment = Vue.util.extend({}, attachment);
                    newAttachment.addedBy = Vue.util.extend({}, this.currentUser);
                    newAttachment.createdAt = moment().format('YYYY-MM-DD HH:mm:ss');
    
                    this.deal.attachments.$set(this.deal.attachments.length, newAttachment);
    
                    var self = this;
    
                    // update db
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'add_deal_attachment',
                            _wpnonce: erpDealsGlobal.nonce,
                            deal_id: this.deal.id,
                            attachment_id: attachment.id
                        }
                    });
                }
    
                if ('function' === typeof this.afterDoneAttaching) {
                    this.afterDoneAttaching(attachment);
                }
            },
    
            getMimeIconClass: function getMimeIconClass(type) {
                var icon = '';
    
                switch(type) {
                    case 'image':
                        icon = 'format-image';
                        break;
    
                    case 'video':
                        icon = 'format-video';
                        break;
    
                    default:
                        icon = 'format-aside';
                        break;
                }
    
                return ("dashicons-" + icon);
            },
    
            removeAttachment: function removeAttachment(attachmentId) {
                var attachment = this.deal.attachments.filter(function (attachment) {
                    return parseInt(attachment.id) === parseInt(attachmentId);
                });
    
                attachment = this.deal.attachments.$remove(attachment[0]);
    
                if ('function' === typeof this.afterRemoveAttachment) {
                    this.afterRemoveAttachment(attachmentId);
                }
    
                // update db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'remove_deal_attachment',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.deal.id,
                        attachment_id: attachmentId
                    }
                });
            }
        }
    };
    
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
    
    /* jshint unused: false */
    var userCapabilities = {
        methods: {
            userCanEditActivity: function userCanEditActivity(activity) {
                // Site Admin and CRM Manager can delete
                if (erpDealsGlobal.isUserAnAdmin || erpDealsGlobal.isUserAManager) {
                    return true;
    
                // agents can only delete activity created by him/herself
                } else if (erpDealsGlobal.isUserAnAgent && (parseInt(erpDealsGlobal.currentUserId) === parseInt(activity.assignedToId))) {
                    return true;
                }
    
                return false;
            },
    
            userCanDeleteActivity: function userCanDeleteActivity(activity) {
                // Site Admin and CRM Manager can delete
                if (erpDealsGlobal.isUserAnAdmin || erpDealsGlobal.isUserAManager) {
                    return true;
    
                // agents can only delete activity created by him/herself
                } else if (erpDealsGlobal.isUserAnAgent && (parseInt(erpDealsGlobal.currentUserId) === parseInt(activity.createdBy))) {
                    return true;
                }
    
                return false;
            },
        }
    };
    
    Vue.partial('addParticipantsOptions',
        "<div class=\"add-participants-option\">\n        <span v-if=\"'contact' === option.type\">\n            <i class=\"dashicons dashicons-admin-users\"></i> {{ option.name }} <span v-if=\"option.company\">({{ option.company }})</span>\n        </span>\n        <span v-else>\n             <i class=\"dashicons dashicons-building\"></i> {{ option.name }}\n        </span>\n    </div>"
    );
    
    Vue.partial('changelog-footer',
        "<div class=\"timeline-item-footer\">\n        <ul class=\"list-inline\">\n            <li class=\"created-at\">{{ getTimelineTimeFormat(singleItem.log.createdAt) }}</li>\n            <li class=\"created-by\">{{ singleItem.log.createdBy }}</li>\n        </ul>\n    </div>"
    );
    
    Vue.partial('contactNamesWithEmail',
        "<div class=\"contact-name-with-email\">\n        <span v-if=\"'contact' === option.type\">\n            <i class=\"dashicons dashicons-admin-users\"></i> {{ option.name }} <span><{{ option.email }}></span>\n        </span>\n        <span v-else>\n             <i class=\"dashicons dashicons-building\"></i> {{ option.name }} <span><{{ option.email }}></span>\n        </span>\n    </div>"
    );
    
    Vue.directive('erp-datepicker', {
        params: ['exclude'],
    
        bind: function bind() {
            var settings = {
                dateFormat: erpDealsGlobal.date.format,
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+5',
            };
    
            switch(this.params.exclude) {
                case 'prev':
                    settings.minDate = 0;
                    break;
    
                case 'next':
                    settings.maxDate = 0;
                    break;
    
                default:
                    break;
            }
    
            $(this.el).datepicker(settings);
        }
    });
    
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
    
        bind: function bind$1() {
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
    
    Vue.directive('erp-timepicker', {
        params: ['scrollDefault', 'step', 'timeFormat', 'minTime', 'maxTime'],
    
        bind: function bind$2() {
            var settings = $.extend({
                scrollDefault: 'now',
                step: 15,
                timeFormat: 'h:i A',
                minTime: '12:00am',
                maxTime: '24 hours after minTime'
            }, this.params);
    
            $(this.el).timepicker(settings);
        }
    });
    
    Vue.directive('erp-tooltip', {
        params: [
            'animation', 'container', 'delay', 'html', 'placement',
            'selector', 'template', 'tooltipTitle', 'trigger', 'viewport'
        ],
    
        bind: function bind$3() {
            var self = this;
    
            var settings = $.extend({
                animation: true,
                placement: 'top',
                selector: false,
                template: '<div class="erp-tooltip" role="tooltip"><div class="erp-tooltip-arrow"></div><div class="erp-tooltip-inner"></div></div>',
                trigger: 'hover focus',
                title: this.params.tooltipTitle || ':tooltip-title is missing',
                delay: 0,
                html: false,
                container: 'body',
                viewport: {
                    selector: 'body',
                    padding: 0
                }
            }, this.params);
    
            $(this.el).erpTooltip(settings);
    
            $(this.el).on('click', function () {
                $(self.el).erpTooltip('hide');
            });
        },
    
        paramWatchers: {
            tooltipTitle: function tooltipTitle() {
                var self = this;
    
                // after destroy, I don't know why we have to wait to
                // make instance again!!!
                $(this.el).erpTooltip('destroy');
                setTimeout(function () {
                    self.bind();
                }, 400);
    
            }
        }
    });
    
    Vue.component('activities-popover', {
        /* global dealUtils */
    
        template: '<div class="deal-activities"><a :class="[\'view-activities ignore-sortable\', buttonClass]" href="#view-tasks" @click.prevent="onClickButton" @focusout="onFocusOutButton">&nbsp;</a><div :class="[\'erp-popover\', \'ignore-sortable\', popoverClass, !activities.length ? \'no-activity\' : \'\']" :style="popoverStyle" @click.prevent="onClickPopover" role="tooltip"><div class="erp-popover-arrow" :style="popoverArrowStyle"></div><h3 v-if="activities.length" class="erp-popover-title"><span v-if="overdues.length" class="text-danger">{{ i18n.overdue }} ({{ overdues.length }})</span> <span v-else>{{ i18n.planned }} ({{ planned.length }})</span></h3><div class="erp-popover-content"><div v-if="!activities.length" class="no-activity-msg text-center"><span v-if="!doingAjax">{{ i18n.noActivityMsg }}</span><div v-else class="erp-spinner"></div></div><div v-for="overdueDeal in overdues | orderBy \'start\'" class="activity-in-popover clearfix" @click.prevent="openActivityModal(overdueDeal)"><div class="clearfix"><span class="deal-icon"><i :class="[\'picon\', \'picon-\' + getActivityTypeIcon(overdueDeal.type)]"></i></span> <span :class="[\'deal-mark-as-done\', overdueDeal.doneAt ? \'done\' : \'\']"><i class="dashicons dashicons-yes" @click.prevent.stop="markAsDone(overdueDeal)"></i></span><div class="activity-names"><span class="activity-title">{{ overdueDeal.title }}</span> <span><span :class="[\'date-diff\', statusClass(overdueDeal.start, overdueDeal.isStartTimeSet)]">{{ dateDiff( overdueDeal.start, \'overdue\', overdueDeal.isStartTimeSet ) }}</span> · <span class="assigned-to">{{ overdueDeal.assignedTo }}</span></span></div></div><div v-if="overdueDeal.note" class="activity-notes">{{{ overdueDeal.note }}}</div></div><span v-if="overdues.length && planned.length" class="planned-activities">{{ i18n.planned }} ({{ planned.length }})</span><div v-for="plannedDeal in planned | orderBy \'start\'" class="activity-in-popover clearfix" @click.prevent="openActivityModal(plannedDeal)"><div class="clearfix"><span class="deal-icon"><i :class="[\'picon\', \'picon-\' + getActivityTypeIcon(plannedDeal.type)]"></i></span> <span :class="[\'deal-mark-as-done\', plannedDeal.doneAt ? \'done\' : \'\']"><i class="dashicons dashicons-yes" @click.prevent.stop="markAsDone(plannedDeal)"></i></span><div class="activity-names"><span class="activity-title">{{ plannedDeal.title }}</span> <span><span :class="[\'date-diff\', statusClass(plannedDeal.start, plannedDeal.isStartTimeSet)]">{{ dateDiff( plannedDeal.start, \'planned\', plannedDeal.isStartTimeSet ) }}</span> · <span class="assigned-to">{{ plannedDeal.assignedTo }}</span></span></div></div><div v-if="plannedDeal.note" class="activity-notes">{{{ plannedDeal.note }}}</div></div></div><a v-if="!doingAjax" class="popover-add-activity text-center" href="#add-activity" @click.prevent="openActivityModal({})">+{{ i18n.scheduleAnActivity }}</a></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            deal: {
                type: Object,
                required: true
            }
        },
    
        data: function data$1() {
            return {
                activities: [],
                activityTypes: erpDealsGlobal.activityTypes,
                isInsideRegion: false,
                popoverClass: 'right',
                popoverStyle: {},
                popoverArrowStyle: {},
                doingAjax: true,
                updateDeals: []
            };
        },
    
        computed: {
            overdues: function overdues() {
                return this.activities.filter(function (activity) {
                    var now = moment();
                    var today = now.format('YYYY-MM-DD');
                    var startTime = moment(activity.start, 'YYYY-MM-DD HH:mm:ss');
                    var startDay = startTime.format('YYYY-MM-DD');
    
                    if (moment(startDay).isSame(today) && !parseInt(activity.isStartTimeSet)) {
                        return false;
    
                    } else {
                        return startTime.isBefore(now);
                    }
                });
            },
    
            planned: function planned() {
                var this$1 = this;
    
                return this.activities.filter(function (activity) { return (this$1.overdues.indexOf(activity) < 0); });
            }
        },
    
        methods: {
            onClickButton: function onClickButton() {
                var this$1 = this;
    
                var popover = $(this.$el).children('.erp-popover');
    
                // close popover if it is currently open
                if (popover.hasClass('active')) {
                    popover.removeClass('active');
    
                } else {
                    var self = this;
                    this.activities = [];
    
                    $('.view-activities').next().removeClass('active');
                    $(this.$el).children('.view-activities').focus().next().addClass('active');
                    this.calculatePopoverPosition();
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'get_activities',
                            _wpnonce: erpDealsGlobal.nonce,
                            deal_id: this.deal.id,
                            args: {
                                with_names: true,
                                only_incomplete: true
                            }
                        },
                        beforeSend: function beforeSend() {
                            self.doingAjax = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            var i = 0;
    
                            // update the vue model
                            for (i = 0; i < response.data.activities.length; i++) {
                                self.activities.push( this$1.underscoreToCamelObject(response.data.activities[i]) );
                            }
                        }
    
                    }).always(function () {
                        self.doingAjax = false;
                    });
                }
            },
    
            onFocusOutButton: function onFocusOutButton() {
                var self = this;
    
                // we don't want to close the popover when we click inside the popover region.
                // for more see onClickPopover method
                setTimeout(function() {
                    if (!self.isInsideRegion) {
                        $(self.$el).children('.view-activities').next().removeClass('active');
    
                        // update deal actStart time AFTER popover closes
                        if (self.updateDeals.length) {
                            var i = 0;
    
                            for (i = 0; i < self.updateDeals.length; i++) {
                                self.$dispatch('update-deal-activity-start', self.updateDeals[i]);
                            }
                        }
    
                    } else {
                        $(self.$el).children('.view-activities').focus();
                    }
    
                    self.$set('isInsideRegion', false);
    
                }, 400);
            },
    
            onClickPopover: function onClickPopover() {
                // This makes sure to keep open the popover open when we
                // click inside the popover region. Otherwise, the button loose
                // focus and close the popover
                this.isInsideRegion = true;
            },
    
            calculatePopoverPosition: function calculatePopoverPosition() {
                this.popoverStyle = {};
                this.popoverArrowStyle = {};
    
                var popoverClass = 'right';
                var container = $('#erp-deals-pipeline-view').parent();
                var button = $(this.$el).children('.view-activities');
                var popover = $(this.$el).children('.erp-popover');
    
                var containerEdgeX = container.offset().left + container.width();
                var popoverEdgeX = button.offset().left + popover.width() + 32; // 16 is the width of button
    
                if (popoverEdgeX > containerEdgeX) {
                    popoverClass = 'left';
                    this.popoverStyle.left = -popover.outerWidth() + 'px';
                }
    
                var containerEdgeY = container.offset().top + container.height();
                var popoverEdgeY = button.offset().top + popover.height();
    
                if (popoverEdgeY > containerEdgeY) {
                    var outerHeight = popover.outerHeight();
    
                    popoverClass += ' pull-up';
                    this.popoverStyle.top = -(outerHeight - 35) + 'px';
                    this.popoverArrowStyle.top = (outerHeight - 28) + 'px';
                }
    
                this.$set('popoverClass', popoverClass);
            },
    
            statusClass: function statusClass(time, isStartTimeSet) {
                // const statuses = ['warning', 'overdue', 'today', 'future', 'rotten', 'status-won'];
    
                var className = 'warning';
    
                var now = moment();
                var today = now.format('YYYY-MM-DD');
                var tonight = today + ' 23:59:59';
                var date = moment(time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
    
                if (!parseInt(isStartTimeSet) && moment(date).isSame(moment(today))) {
                    className = 'today';
                } else if ( moment(time).isBefore(now) ) {
                    className = 'overdue';
                } else if ( moment(time).isBetween(now, tonight) ) {
                    className = 'today';
                } else if ( moment(time).isAfter(now) ) {
                    className = 'future';
                }
    
                return className;
            },
    
            dateDiff: function dateDiff(time, type, isStartTimeSet) {
                var now = moment();
                var today = now.format('YYYY-MM-DD');
                var date = moment(time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
    
                var timeDiff = moment(time).from(now);
    
                if ('overdue' === type) {
                    timeDiff = timeDiff.replace('ago', 'overdue');
                } else {
    
                    if (!parseInt(isStartTimeSet) && moment(date).isSame(moment(today))) {
                        timeDiff = 'Today';
                    } else {
                        timeDiff = 'Due ' + timeDiff;
                    }
    
                }
    
                return timeDiff;
            },
    
            openActivityModal: function openActivityModal(activity) {
                // this will send signal to pipeline-view parent component
                var names = {
                    contactId: this.deal.contactId,
                    contact: this.deal.contact,
                    companyId: this.deal.companyId,
                    company: this.deal.company
                };
    
                this.$dispatch('open-activity-modal', activity, this.deal.id, names);
            },
    
            markAsDone: function markAsDone(activity) {
                var self = this;
    
                this.isInsideRegion = true;
                activity.doneAt = activity.doneAt ? null : moment().format('YYYY-MM-DD HH:mm:ss');
    
                // ajax update
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity: {
                            id: activity.id,
                            done_at: activity.doneAt
                        }
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        // we want to remove the update the deal actStart AFTER popover closes
                        self.updateDeals.push(response.data.deal);
                    }
                });
            },
    
            getActivityTypeIcon: function getActivityTypeIcon(activityTypeId) {
                var type = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(activityTypeId);
                });
    
                return type[0].icon;
            }
        }
    });
    
    Vue.component('activity-form', {
        /* global dealUtils */
    
        template: '<form class="activity-form-container" @reset.prevent="resetForm" @submit.prevent="submitForm"><div class="erp-deal-modal-body" :id="\'activity-form-\' + _uid"><div class="activity-types-container margin-bottom-15"><ul class="clearfix"><li v-for="activityType in activityTypes" :class="[parseInt(activity.type) === parseInt(activityType.id) ? \'active\' : \'\']"><a href="#set-activity-type" @click.prevent="activity.type = parseInt(activityType.id)"><i :class="[\'picon\', \'picon-\' + activityType.icon]"></i> <span>{{ activityType.title }}</span></a></li></ul></div><input type="text" class="input-large margin-bottom-15" :placeholder="titlePlaceholder" v-model="activity.title"><div class="deal-row activity-datetimes margin-bottom-15"><div class="col-2"><label>{{ i18n.date }} <input type="text" class="erp-deal-input" v-model="activityDate" v-erp-datepicker></label></div><div class="col-2"><label>{{ i18n.time }} <input type="text" class="erp-deal-input" v-model="activityTime" v-erp-timepicker></label> <span class="reset" @click="resetTime">×</span></div><div class="col-2"><label>{{ i18n.duration }} <small>({{ i18n.hour | lowercase }}:{{ i18n.min | lowercase }})</small> <input type="text" class="erp-deal-input" v-model="activityDuration" v-erp-timepicker time-format="H:i" min-time="00:15" max-time="08:00" scroll-default="00:15"></label> <span class="reset" @click="resetDuration">×</span></div></div><div class="margin-bottom-15"><note :placeholder="i18n.notes" :content.sync="activity.note"></note></div><div v-if="showDealDropdown && !activity.id" :class="[\'margin-bottom-15\', dealErrorClass]"><label>{{ i18n.deal }}</label><multiselect :options="dealDropdownList" :selected="dealSelected" :local-search="false" :loading="isDealSearching" :searchable="true" :show-labels="false" @search-change="searchDeals" @update="onDealSelect" @open="dealErrorClass = \'\'" label="title" key="id" :placeholder="i18n.searchMinCharMsg"></multiselect></div><div class="deal-row"><div v-if="isUserAManager" class="margin-bottom-15 col-2"><label>{{ i18n.assignedTo }}</label><multiselect :options="crmAgents" :selected="assignedToSelected" :multiple="false" :searchable="false" :close-on-select="true" :show-labels="false" :allow-empty="false" @update="onAgentSelect" label="name" key="id"></multiselect></div><div :class="[\'margin-bottom-15\', contactErrorClass, isUserAManager ? \'col-2\' : \'col-3\']"><label>{{ i18n.contact }}</label><multiselect :options="contactList" :selected="contactSelected" :local-search="false" :loading="isContactSearching" :searchable="true" :show-labels="false" @search-change="searchContacts" @update="onContactSelect" @open="contactErrorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg"><span slot="noResult">{{ i18n.noContactFound }}</span></multiselect></div><div :class="[\'margin-bottom-15\', companyErrorClass, isUserAManager ? \'col-2\' : \'col-3\']"><label>{{ i18n.company }}</label><multiselect :options="companyList" :selected="companySelected" :local-search="false" :loading="isCompanySearching" :searchable="true" :show-labels="false" @search-change="searchCompanies" @update="onCompanySelect" @open="companyErrorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg"><span slot="noResult">{{ i18n.noContactFound }}</span></multiselect></div></div></div><div class="erp-deal-modal-footer" :id="\'activity-form-footer-\' + _uid"><label><input type="checkbox" v-model="markAsDone"> {{ i18n.markAsDone }}</label> <button type="reset" class="button" :data-dismiss="isInModal ? \'erp-deal-modal\' : \'\'" :disabled="doingAjax">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary" :disabled="doingAjax">{{ i18n.saveActivity }}</button></div></form>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            users: {
                type: Object,
                required: true,
                default: {}
            },
    
            activity: {
                type: Object,
                required: true,
                default: {},
                twoWay: true
            },
    
            doingAjax: {
                type: Boolean,
                required: true,
                default: false,
                twoWay: true
            },
    
            isInModal: {
                type: Boolean,
                default: true
            },
    
            showDealDropdown: {
                type: Boolean,
                default: false
            }
        },
    
        data: function data$2() {
            return {
                activityTypes: erpDealsGlobal.activityTypes,
                isUserAManager: erpDealsGlobal.isUserAManager,
                activityDate: erpDealsGlobal.date.placeholder,
                initialData: {},
                activityTime: null,
                activityDuration: null,
                assignedToSelected: {},
                contactList: [],
                contactSelected: {},
                isContactSearching: false,
                contactErrorClass: '',
                companyList: [],
                companySelected: {},
                isCompanySearching: false,
                companyErrorClass: '',
                markAsDone: this.activity.doneAt ? true : false,
                ajaxHandler: {
                    abort: function abort() {}
                },
                dealDropdownList: [],
                dealSelected: {},
                isDealSearching: false,
                dealErrorClass: '',
            };
        },
    
        ready: function ready() {
            this.initialData = $.extend(true, {}, this.activity);
            this.initForm();
        },
    
        computed: {
            titlePlaceholder: function titlePlaceholder() {
                var self = this;
    
                var placeholder = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(self.activity.type);
                });
    
                if (placeholder.length) {
                    return placeholder[0].title;
                } else {
                    return this.activityTypes[0].title;
                }
            },
    
            crmAgents: function crmAgents() {
                var this$1 = this;
    
                var agents = [];
    
                for (var i in this.users.crmAgents) {
                    var id = parseInt(this$1.users.crmAgents[i].id);
    
                    agents[i] = {
                        id: id,
                        name: this$1.users.crmAgents[i].name
                    };
    
                    if (parseInt(id) === parseInt(this$1.users.currentUserId)) {
                        agents[i].name += ' (' + this$1.i18n.you + ')';
                    }
                }
    
                return agents;
            }
        },
    
        methods: {
            initForm: function initForm() {
                // editing existing activity
                if (this.activity.hasOwnProperty('id') && this.activity.id) {
                    if (this.isUserAManager) {
                        this.assignedToSelected = {
                            id: parseInt(this.activity.assignedToId),
                            name: this.activity.assignedTo
                        };
                    }
    
                // create new activity
                } else {
                    if (this.isUserAManager) {
                        var self = this;
    
                        var manager = this.users.crmAgents.filter(function (agent) {
                            return parseInt(agent.id) === parseInt(self.users.currentUserId);
                        });
    
                        this.assignedToSelected = {
                            id: parseInt(manager[0].id),
                            name: manager[0].name
                        };
    
                        this.activity.assignedToId = manager[0].id;
                        this.activity.assignedTo = manager[0].name;
                    }
                }
    
                var contactId = parseInt(this.activity.contactId);
                this.contactSelected = {
                    id: contactId ? contactId : 0,
                    name: contactId ? this.activity.contact : null
                };
    
                var companyId = parseInt(this.activity.companyId);
                this.companySelected = {
                    id: companyId ? companyId : 0,
                    name: companyId ? this.activity.company : null
                };
    
                if (this.activity.start) {
                    var start = moment(this.activity.start, 'YYYY-MM-DD HH:mm:ss');
    
                    this.activityDate = start.format(this.dateFormat);
                    this.activityTime = parseInt(this.activity.isStartTimeSet) ? start.format('hh:mm A') : null;
    
                    this.calculateDuration();
                } else {
                    this.activityDate = moment().format(this.dateFormat);
                    this.activityTime = null;
                    this.setActivityStart();
                }
    
                // in case of new activity, lets use the first activity as active type
                if (!parseInt(this.activity.type)) {
                    this.activity.type = this.activityTypes[0].id;
                }
    
                this.$broadcast('reset-note', this.activity.note);
            },
    
            setActivityStart: function setActivityStart() {
                // take the Date and Time inputs and set them to activity.start
                var startDateTime = this.activityDate + ' ';
    
                startDateTime += this.activityTime ? this.activityTime : '12:00 AM';
                this.activity.start = moment(startDateTime, this.dateFormat + ' hh:mm A').format('YYYY-MM-DD HH:mm:ss');
    
                this.setActivityEnd();
            },
    
            setActivityEnd: function setActivityEnd() {
                var endDate = this.activity.start;
    
                if (this.activityDuration) {
                    var hr = parseInt(this.activityDuration.split(':')[0]);
                    var min = parseInt(this.activityDuration.split(':')[1]);
    
                    endDate = moment(this.activity.start, 'YYYY-MM-DD HH:mm:ss')
                                .add(hr, 'h')
                                .add(min, 'm')
                                .format('YYYY-MM-DD HH:mm:ss');
                }
    
                this.activity.end = endDate;
            },
    
            calculateDuration: function calculateDuration() {
                if (this.activity.start && this.activity.end) {
                    var start = moment(this.activity.start, 'YYYY-MM-DD HH:mm:ss');
                    var end = moment(this.activity.end, 'YYYY-MM-DD HH:mm:ss');
                    var diff = end.diff(start);
                    var duration = moment.duration(diff);
                    var durHrs = duration.hours();
                    var durMins = duration.minutes();
    
                    if (!diff) {
                        this.activityDuration = null;
    
                    } else {
                        if (durHrs < 10) {
                            durHrs = '0' + durHrs;
                        }
    
                        if (durMins < 10) {
                            durMins = '0' + durMins;
                        }
    
                        this.activityDuration = durHrs + ':' + durMins;
                    }
                }
            },
    
            resetTime: function resetTime() {
                this.activityTime = null;
            },
    
            resetDuration: function resetDuration () {
                this.activity.end = this.activity.start;
                this.calculateDuration();
            },
    
            onAgentSelect: function onAgentSelect(agent) {
                this.assignedToSelected = agent;
                this.activity.assignedToId = agent.id;
                this.activity.assignedTo = agent.name;
            },
    
            searchContacts: function searchContacts(s) {
                var self = this;
    
                if (s.length < 3) {
                    this.contactList = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_people',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s,
                            contact: true
                        },
                        beforeSend: function beforeSend() {
                            self.isContactSearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.contactList = response.data.contacts;
                        }
    
                    }).always(function () {
                        self.isContactSearching = false;
                    });
                }
            },
    
            onContactSelect: function onContactSelect(contact) {
                // in case of deselect/remove selection
                if (!contact) {
                    this.contactSelected = {};
    
                    return;
                }
    
                this.contactSelected = contact;
    
                this.activity.contactId = contact.id;
                this.activity.contact = contact.name;
            },
    
            searchCompanies: function searchCompanies(s) {
                var self = this;
    
                if (s.length < 3) {
                    this.companyList = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_people',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s,
                            company: true
                        },
                        beforeSend: function beforeSend() {
                            self.isCompanySearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.companyList = response.data.companies;
                        }
    
                    }).always(function () {
                        self.isCompanySearching = false;
                    });
                }
            },
    
            onCompanySelect: function onCompanySelect(company) {
                this.companySelected = company;
                this.activity.companyId = company.id;
                this.activity.company = company.name;
            },
    
            searchDeals: function searchDeals(s) {
                var this$1 = this;
    
                var self = this;
    
                if (s.length < 3) {
                    this.dealDropdownList = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_deals',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s,
                            company: true
                        },
                        beforeSend: function beforeSend() {
                            self.isDealSearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.dealDropdownList = this$1.camelizedArray(response.data.deals);
                        }
    
                    }).always(function () {
                        self.isDealSearching = false;
                    });
                }
            },
    
            onDealSelect: function onDealSelect(deal) {
                this.dealSelected = deal;
                this.activity.dealId = deal.id;
                this.activity.deal = deal.title;
    
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_deal_primary_contacts',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: deal.id
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        self.isContactSearching = true;
                        self.isCompanySearching = true;
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        if (response.data.company) {
                            self.activity.companyId = response.data.company.id;
                            self.activity.company = response.data.company.company;
                        } else {
                            self.activity.companyId = 0;
                            self.activity.company = null;
                        }
    
                        if (response.data.contact) {
                            self.activity.contactId = response.data.contact.id;
                            self.activity.contact = response.data.contact.first_name + ' ' + response.data.contact.last_name;
                        } else {
                            self.activity.contactId = 0;
                            self.activity.contact = null;
                        }
    
                        self.contactSelected = {
                            id: self.activity.contactId,
                            name: self.activity.contact
                        };
    
                        self.companySelected = {
                            id: self.activity.companyId,
                            name: self.activity.company
                        };
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    self.isContactSearching = false;
                    self.isCompanySearching = false;
                });
    
            },
    
            resetForm: function resetForm() {
                this.activity = $.extend(true, {}, this.initialData);
                this.initForm();
                this.markAsDone = false;
                this.activityDuration = null;
            },
    
            submitForm: function submitForm() {
                var this$1 = this;
    
                var self = this;
    
                if (!parseInt(this.activity.dealId)) {
                    this.dealErrorClass = 'input-error';
                    return false;
                }
    
                // if no title is set, then use the placeholder or the activity title
                if (!this.activity.title) {
                    this.activity.title = this.titlePlaceholder;
                }
    
                // store data to db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity: this.camelToUnderscoreObject(this.activity)
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#activity-form-footer-' + self._uid
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.$dispatch(
                            'activity-form-saved',
                            response.data.deal,
                            self.camelizedObject(response.data.activity)
                        );
    
                        this$1.resetForm();
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
    
        },
    
        watch: {
            doingAjax: function doingAjax(newVal) {
                this.modalKeepOpen(newVal);
            },
    
            activityDate: function activityDate(newVal) {
                if (!newVal) {
                    this.activityDate = moment().format(this.dateFormat);
                }
    
                this.setActivityStart();
            },
    
            activityTime: function activityTime(newVal) {
                this.activity.isStartTimeSet = newVal ? true : false;
                this.setActivityStart();
            },
    
            activityDuration: function activityDuration() {
                this.setActivityEnd();
            },
    
            markAsDone: function markAsDone$1(newVal) {
                if (newVal) {
                    this.activity.doneAt = moment().format('YYYY-MM-DD HH:mm:ss');
                } else {
                    this.activity.doneAt = null;
                }
            }
        }
    });
    
    Vue.component('activity-list', {
        /* global userCapabilities, dealUtils */
        template: '<div v-if="isReady" class="erp-deal-activity-list"><div class="clearfix"><h1 class="pull-left">{{ i18n.activities }} <a href="#add-new-activity" class="add-new-h2" @click.prevent="openNewActivityModal">{{ i18n.addNew }}</a></h1></div><div class="clearfix activity-filters"><div class="pull-left"><div class="filter-activity-type button-group"><button type="button" :class="[\'button\', (parseInt(filters.type) === 0) ? \'button-primary active\' : \'\']" @click="selectTypeFilter({ id: 0 })">{{ i18n.all }}</button> <button v-for="type in activityTypes" type="button" :class="[\'button\', (parseInt(filters.type) === parseInt(type.id)) ? \'button-primary active\' : \'\']" @click="selectTypeFilter(type)" v-erp-tooltip :tooltip-title="type.title"><i :class="[\'picon picon-\' + type.icon]"></i></button></div></div><div class="pull-right"><div class="filter-activity-status button-group"><button type="button" :class="[\'button\', (\'incomplete\' === filters.status) ? \'button-primary active\' : \'\']" @click="selectStatusFilter(\'incomplete\')">{{ i18n.todo }}</button> <button type="button" :class="[\'button\', (\'completed\' === filters.status) ? \'button-primary active\' : \'\']" @click="selectStatusFilter(\'completed\')">{{ i18n.completed }}</button></div><div class="filter-activity-period button-group"><button type="button" :class="[\'button\', (\'planned\' === filters.period) && !openPeriodRangeEditor ? \'button-primary active\' : \'\']" @click="selectPeriodFilter(\'planned\')">{{ i18n.planned }}</button> <button type="button" :class="[\'button\', (\'overdue\' === filters.period) && !openPeriodRangeEditor ? \'button-primary active\' : \'\']" @click="selectPeriodFilter(\'overdue\')">{{ i18n.overdue }}</button> <button type="button" :class="[\'button\', (\'range\' === filters.period) || openPeriodRangeEditor ? \'button-primary active\' : \'\']" @click="openPeriodRangeDropdown">{{ selectPeriodBtnTitle }}</button><div v-if="openPeriodRangeEditor" class="filter-activity-period-range-editor erp-popover bottom arrow-right"><form @submit.prevent="setPeriodRange()"><div class="erp-popover-content"><label class="popover-title"><strong>{{ i18n.selectPeriod }}</strong></label><div class="deal-row"><div :class="[\'col-3\', fromErrorClass]"><label>{{ i18n.from }}</label> <input type="text" class="erp-deal-input" v-erp-datepicker v-model="periodRange.from" autofocus @focus="fromErrorClass = \'\'"></div><div :class="[\'col-3\', toErrorClass]"><label>{{ i18n.to }}</label> <input type="text" class="erp-deal-input" v-erp-datepicker v-model="periodRange.to" @focus="toErrorClass = \'\'"></div></div></div><div class="erp-popover-footer clearfix"><div v-if="\'range\' === filters.period" class="pull-left"><button type="button" class="button" @click="resetPeriodRange">{{ i18n.remove }}</button></div><div class="pull-right"><button type="button" class="button" @click="openPeriodRangeEditor = false">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.apply }}</button></div></div></form></div></div></div></div><div class="list-table-container"><table :class="[\'wp-list-table widefat striped\', doingAjax ? \'disabled\' : \'\']"><thead><tr><th class="column-done">{{ i18n.done }}</th><th class="column-type">{{ i18n.type }}</th><th class="column-title">{{ i18n.title }}</th><th class="column-deal">{{ i18n.deal }}</th><th class="column-contact">{{ i18n.contacts }}</th><th class="column-due-date">{{ i18n.dueDate }}</th><th class="column-assigned-to">{{ i18n.assignedTo }}</th></tr></thead><tbody v-if="!activityList.length"><tr><td v-if="!isFiltering" colspan="7" class="text-center">{{ i18n.emptyActivityListMsg }}</td><td v-else colspan="7" class="text-center"><span class="erp-spinner"></span></td></tr></tbody><tbody v-else><tr v-for="activity in activityList | orderBy \'start\' \'id\'" :id="\'open-activity-row-id-\' + activity.id" class="has-action-btns"><td class="column-done"><span v-if="userCanEditActivity(activity)" :class="[\'deal-mark-as-done\', activity.doneAt ? \'done\' : \'\']" v-erp-tooltip :tooltip-title="!activity.doneAt ? i18n.markAsDone : i18n.markAsToDo" @click="markAsDone(activity)"><i class="dashicons dashicons-yes"></i></span></td><td class="column-type"><span class="deal-icon" v-erp-tooltip :tooltip-title="getActivityTypeTitle(activity.type)"><i :class="[\'picon\', \'picon-\' + getActivityTypeIcon(activity.type)]"></i></span></td><td class="column-title"><a :class="[\'activity-title\', statusClass(activity.start, activity.isStartTimeSet)]" href="#edit-activity" @click.prevent="openActivityModal(activity)">{{ activity.title }}</a> <a href="#delete-activity" @click.prevent="deleteActivity(activity)" class="delete-activity">{{ i18n.delete }}</a></td><td class="column-deal"><a :href="dealURL(activity.dealId)">{{ activity.dealTitle }}</a></td><td class="column-contact"><span v-if="parseInt(activity.companyId)"><i class="dashicons dashicons-building"></i> <a href="#contact" @click.prevent="viewPeople(activity, activity.companyId, \'company\')">{{ activity.company }}</a><br></span><span v-if="parseInt(activity.contactId)"><i class="dashicons dashicons-admin-users"></i> <a href="#contact" @click.prevent="viewPeople(activity, activity.contactId, \'contact\')">{{ activity.contact }}</a></span></td><td class="column-due-date">{{ getActivityDueDate(activity) }}</td><td class="column-assigned-to">{{{ getAssignedToName(activity) }}}</td></tr></tbody></table><div :class="[\'list-table-row-progress\', showRowProgress ? \'in\' : \'\']" :style="rowProgressStyle" :id="\'list-table-row-progress-\' + _uid"></div><div v-if="!isFiltering && activitiesCount && (activitiesCount > activities.length)" class="margin-top-15"><button v-if="isLoadingMore" type="button" class="button button-hero button-block button-link" disabled><span class="erp-spinner"></span></button> <button v-else type="button" class="button button-hero button-block button-link" @click="loadMore">{{ i18n.loadMore }}...</button></div></div><activity-modal :i18n="i18n" :users="users" :show-deal-dropdown="true"></activity-modal><div class="erp-deal-modal" id="erp-deal-people-modal" tabindex="-1"><div class="erp-deal-modal-dialog modal-sm" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">&times;</span></button><h4 class="erp-deal-modal-title">{{ peopleModalTitle }}</h4></div><div class="erp-deal-modal-body" id="erp-deal-people-modal-body"><div v-if="doingAjax" class="loading-details"><span class="erp-spinner"></span></div><div v-else><div class="postbox erp-deal-postbox profile-info-box"><div class="postbox-inside"><div class="profile-summery deal-row"><div class="col-2 avatar padding-right-0">{{{ peopleModalPeople.people.avatar.img }}}</div><div class="col-4 summery"><h3 v-if="\'contact\' === peopleModalPeople.type"><a :href="peopleModalPeople.people.detailsUrl" target="_blank">{{ peopleModalPeople.people.firstName }} {{ peopleModalPeople.people.lastName }}</a></h3><h3 v-else><a :href="peopleModalPeople.people.detailsUrl" target="_blank">{{ peopleModalPeople.people.company }}</a></h3><div><p v-if="peopleModalPeople.people.email"><i class="dashicons dashicons-email-alt"></i> <a :href="\'mailto:\' + peopleModalPeople.people.email">{{ peopleModalPeople.people.email }}</a></p><p v-if="peopleModalPeople.people.phone"><i class="dashicons dashicons-phone"></i> <a :href="\'tel:\' + peopleModalPeople.people.phone">{{ peopleModalPeople.people.phone }}</a></p><p v-if="peopleModalPeople.people.mobile"><i class="dashicons dashicons-smartphone"></i> <a :href="\'tel:\' + peopleModalPeople.people.mobile">{{ peopleModalPeople.people.mobile }}</a></p></div></div></div><table class="table-profile"><tr><td class="label">{{ i18n.street1 }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.street1 }}}</td></tr><tr><td class="label">{{ i18n.street2 }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.street2 }}}</td></tr><tr><td class="label">{{ i18n.city }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.city }}}</td></tr><tr><td class="label">{{ i18n.state }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.stateName }}}</td></tr><tr><td class="label">{{ i18n.country }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.countryName }}}</td></tr><tr><td class="label">{{ i18n.postalCode }}</td><td class="sep">:</td><td class="value">{{{ peopleModalPeople.people.postalCode }}}</td></tr></table></div></div></div></div><div class="erp-deal-modal-footer" :id="\'activity-form-footer-\' + _uid"><button type="reset" class="button" data-dismiss="erp-deal-modal" :disabled="doingAjax">{{ i18n.close }}</button></div></div></div></div></div>',
    
        mixins: [userCapabilities, dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            }
        },
    
        data: function data$3() {
            return {
                isReady: false,
                users: this.camelizedObject(erpDealsGlobal.users),
                activities: [],
                activitiesCount: 0,
                doingAjax: false,
                showRowProgress: false,
                rowProgressStyle: {},
                bulkSelectAll: [],
                bulkSelect: [],
                peopleModalTitle: '',
                peopleModalPeople: {
                    people: { avatar: {} },
                    type: ''
                },
                isLoadingMore: false,
                activityTypes: erpDealsGlobal.activityTypes,
                filters: {
                    type: 0,
                    status: 'incomplete',
                    period: '',
                    from: '',
                    to: '',
                },
                periodRange: {
                    to: '', from: ''
                },
                isFiltering: false,
                openPeriodRangeEditor: false,
                fromErrorClass: '',
                toErrorClass: ''
            };
        },
    
        ready: function ready$1() {
            this.getActivityList();
        },
    
        computed: {
            activityList: function activityList() {
                var self = this;
                var activities = [];
    
                activities = self.activities.filter(function (activity) {
                    return ('incomplete' === self.filters.status) ? !activity.doneAt : activity.doneAt;
                });
    
                var yesterday = moment().add(-1, 'days');
                var yesterdayNight = yesterday.format('YYYY-MM-DD 23:59:59');
    
                switch(self.filters.period) {
    
                    case 'planned':
                        activities = self.activities.filter(function (activity) {
                            return moment(activity.start).isAfter(yesterdayNight);
                        });
                        break;
    
                    case 'overdue':
                        activities = self.activities.filter(function (activity) {
                            return moment(activity.end).isBefore(yesterdayNight);
                        });
                        break;
    
                    case 'range':
                        if (self.filters.from && self.filters.to) {
                            activities = self.activities.filter(function (activity) {
                                var isSameOrAfter = (moment(activity.start).isSame(self.filters.from) || moment(activity.start).isAfter(self.filters.from));
                                var isBefore = moment(activity.end).isBefore(self.filters.to);
    
                                return isSameOrAfter && isBefore;
                            });
                        }
                        break;
                }
    
    
                return activities;
            },
    
            selectPeriodBtnTitle: function selectPeriodBtnTitle() {
                if ('range' !== this.filters.period) {
                    return this.i18n.selectPeriod;
                } else {
                    var from = moment(this.filters.from, 'YYYY-MM-DD hh:mm:ss').format('DD MMM, YYYY');
                    var to = moment(this.filters.to, 'YYYY-MM-DD hh:mm:ss').format('DD MMM, YYYY');
    
                    return from + ' - ' + to;
                }
            }
        },
    
        methods: {
            getActivityList: function getActivityList() {
                var self = this;
    
                var exclude = [];
    
                // if we're loading more instead of filtering, then we don't need to
                // fetch the current activities again, just fetch after the last of current activities.
                if (!this.isFiltering) {
                    exclude = this.activities.map(function (activity) {
                        return activity.id;
                    });
                } else {
                    this.activities = [];
                }
    
                // store data to db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_activity_list',
                        _wpnonce: erpDealsGlobal.nonce,
                        filters: this.filters,
                        exclude: exclude
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        self.isLoadingMore = true;
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.isReady = true;
                        self.activities = self.activities.concat(self.camelizedArray(response.data.activities));
                        self.activitiesCount = response.data.count;
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    self.isLoadingMore = false;
                    self.isFiltering = false;
                    NProgress.done();
                });
            },
    
            openNewActivityModal: function openNewActivityModal() {
                var activity = {
                    isStartTimeSet: 0,
                    contact: '',
                    company: '',
                    assignedTo: '',
                    assignedToId: 0,
                    companyId: 0,
                    doneBy: null,
                    createdBy: 0,
                    createdAt: null,
                    contactId: 0,
                    dealId: 0,
                    deletedAt: null,
                    doneAt: null,
                    end: 0,
                    id: 0,
                    note: '',
                    start: 0,
                    title: '',
                    type: 0,
                    updatedAt: null,
                };
    
    
                var names = {
                    contact: '',
                    company: '',
                    companyId: 0,
                    contactId: 0,
                };
    
                this.$dispatch('open-activity-modal', activity, activity.dealId, names);
            },
    
            selectAll: function selectAll() {
                this.bulkSelect = this.activities.map(function (activity) {
                    return activity.id;
                });
            },
    
            markAsDone: function markAsDone$2(activityData) {
                var self = this;
                var activity = $.extend(true, {}, activityData);
    
                activity.doneAt = activity.doneAt ? null : true;
    
                // ajax update
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity: {
                            id: activity.id,
                            done_at: activity.doneAt
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.initRowProgress(activity);
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        setTimeout(function () {
                            for (var i in self.activities) {
                                if (parseInt(self.activities[i].id) === parseInt(response.data.activity.id)) {
                                    self.activities.$set(i, self.camelizedObject(response.data.activity));
                                    break;
                                }
                            }
                            self.showRowProgress = false;
                        }, 201); // 201 is the NProgress default speed settings
    
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            getActivityTypeTitle: function getActivityTypeTitle(activityTypeId) {
                var type = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(activityTypeId);
                });
    
                type = type[0];
    
                return type.title;
            },
    
            getActivityTypeIcon: function getActivityTypeIcon$1(activityTypeId) {
                var type = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(activityTypeId);
                });
    
                return type[0].icon;
            },
    
            openActivityModal: function openActivityModal$1(activity) {
                var names = {
                    contactId: activity.contactId,
                    contact: activity.contact,
                    companyId: activity.companyId,
                    company: activity.company
                };
    
                this.$dispatch('open-activity-modal', activity, activity.dealId, names);
            },
    
            getActivityDueDate: function getActivityDueDate(activity) {
                var dueDate = '';
                var dateFormat = erpDealsGlobal.date.format.replace('yy', 'yyyy').toUpperCase();
    
                if (parseInt(activity.isStartTimeSet)) {
                    dueDate = moment(activity.start, 'YYYY-MM-DD HH:mm:ss').format(dateFormat + ' hh:mm A');
                } else {
                    dueDate = moment(activity.start, 'YYYY-MM-DD HH:mm:ss').format(dateFormat);
                }
    
                return dueDate;
            },
    
            getActivityDuration: function getActivityDuration(activity) {
                var activityDuration = null;
    
                if (activity.start && activity.end) {
                    var start = moment(activity.start, 'YYYY-MM-DD HH:mm:ss');
                    var end = moment(activity.end, 'YYYY-MM-DD HH:mm:ss');
                    var diff = end.diff(start);
                    var duration = moment.duration(diff);
                    var durHrs = duration.hours();
                    var durMins = duration.minutes();
    
                    if (!diff) {
                        activityDuration = null;
    
                    } else {
                        if (durHrs < 10) {
                            durHrs = '0' + durHrs;
                        }
    
                        if (durMins < 10) {
                            durMins = '0' + durMins;
                        }
    
                        activityDuration = durHrs + ':' + durMins;
                    }
                }
    
                return activityDuration;
            },
    
            getAssignedToName: function getAssignedToName(activity) {
                var assignedTo = this.users.crmAgents.filter(function (agent) {
                    return parseInt(agent.id) === parseInt(activity.assignedToId);
                });
    
                assignedTo = assignedTo.pop();
    
                return ("<a href=\"" + (assignedTo.link) + "\" target=\"_blank\"><img src=\"" + (assignedTo.avatar) + "\">" + (assignedTo.name) + "</a>");
            },
    
            deleteActivity: function deleteActivity(activity) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteActivityWarningMsg,
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
                                action: 'delete_activity',
                                _wpnonce: erpDealsGlobal.nonce,
                                id: activity.id
                            },
                            beforeSend: function beforeSend() {
                                self.initRowProgress(activity);
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.activities.$remove(activity);
                                    self.showRowProgress = false;
                                }, 201); // 201 is the NProgress default speed settings
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            initRowProgress: function initRowProgress(activity) {
                this.doingAjax = true;
                this.showRowProgress = true;
    
                var tr = $('#open-activity-row-id-' + activity.id);
                var top = tr.position().top;
                var height = tr.height();
    
                this.rowProgressStyle = {
                    top: top + 'px', height: height + 'px'
                };
    
                NProgress.configure({
                    parent: '#list-table-row-progress-' + this._uid,
                    // afterDone: (nprogress) => {
                    //     nprogress.remove();
    
                    // }
                });
                NProgress.start();
            },
    
            statusClass: function statusClass$1(time, isStartTimeSet) {
                // const statuses = ['warning', 'overdue', 'today', 'future', 'rotten', 'status-won'];
    
                var className = 'warning';
    
                var now = moment();
                var today = now.format('YYYY-MM-DD');
                var tonight = today + ' 23:59:59';
                var date = moment(time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
    
                if (!parseInt(isStartTimeSet) && moment(date).isSame(moment(today))) {
                    className = 'today';
                } else if ( moment(time).isBefore(now) ) {
                    className = 'overdue';
                } else if ( moment(time).isBetween(now, tonight) ) {
                    className = 'today';
                } else if ( moment(time).isAfter(now) ) {
                    className = 'future';
                }
    
                return className;
            },
    
            dealURL: function dealURL(dealId) {
                return erpDealsGlobal.singlePageURL.replace('DEALID', dealId);
            },
    
            viewPeople: function viewPeople(activity, peopleId, type) {
                var this$1 = this;
    
                var self = this;
    
                this.peopleModalTitle = ('contact' === type) ? this.i18n.contact : this.i18n.company;
    
                $('#erp-deal-people-modal').erpDealModal();
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_people',
                        _wpnonce: erpDealsGlobal.nonce,
                        id: peopleId,
                        type: type
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.peopleModalPeople = {
                            people: this$1.camelizedObject(response.data.people),
                            type: type
                        };
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                });
            },
    
            loadMore: function loadMore() {
                this.getActivityList();
            },
    
            selectTypeFilter: function selectTypeFilter(type) {
                this.isFiltering = true;
                this.filters.type = type.id;
            },
    
            selectStatusFilter: function selectStatusFilter(status) {
                this.isFiltering = true;
                this.filters.status = status;
            },
    
            selectPeriodFilter: function selectPeriodFilter(period) {
                this.isFiltering = true;
    
                if (period === this.filters.period) {
                    this.filters.period = '';
                } else {
                    this.filters.period = period;
                }
            },
    
            openPeriodRangeDropdown: function openPeriodRangeDropdown() {
                this.openPeriodRangeEditor = true;
            },
    
            setPeriodRange: function setPeriodRange() {
                this.isFiltering = true;
    
                if (!this.periodRange.from) {
                    this.fromErrorClass = 'input-error';
                    return false;
                }
    
                if (!this.periodRange.to) {
                    this.toErrorClass = 'input-error';
                    return false;
                }
    
                this.filters = {
                    type: this.filters.type,
                    status: this.filters.status,
                    period: 'range',
                    from: moment(this.periodRange.from, this.dateFormat).format('YYYY-MM-DD 00:00:00'),
                    to: moment(this.periodRange.to, this.dateFormat).format('YYYY-MM-DD 23:59:59'),
                };
            },
    
            resetPeriodRange: function resetPeriodRange() {
                this.isFiltering = true;
    
                this.filters = {
                    type: this.filters.type,
                    status: this.filters.status,
                    period: '',
                    from: '',
                    to: '',
                };
            }
        },
    
        events: {
            'save-activity': function save_activity(activity, deal) {
                activity.dealTitle = deal.title;
    
                var existingActivity = this.activities.filter(function (act) {
                    return parseInt(act.id) === parseInt(activity.id);
                });
    
                if (existingActivity.length) {
                    existingActivity = existingActivity[0];
                    this.activities.$remove(existingActivity);
                }
    
                this.activities.$set(this.activities.length, activity);
            }
        },
    
        watch: {
            filters: {
                deep: true,
                handler: function handler(newVal) {
                    if ('range' !== newVal.period) {
                        this.periodRange = { to: '', from: '' };
                    }
    
                    this.openPeriodRangeEditor = false;
                    this.getActivityList();
                }
            }
        }
    });
    
    Vue.component('activity-modal', {
        /* global dealUtils */
    
        template: '<div class="erp-deal-modal erp-deal-activity-modal" tabindex="-1"><div class="erp-deal-modal-dialog modal-lg" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">&times;</span></button><h4 class="erp-deal-modal-title">{{ modalTitle }}</h4></div><activity-form v-if="includeForm" :i18n="i18n" :users="users" :activity.sync="activity" :doing-ajax.sync="doingAjax" :show-deal-dropdown="showDealDropdown"></activity-form></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            users: {
                type: Object,
                required: true,
                default: {}
            },
    
            showDealDropdown: {
                type: Boolean,
                default: false
            }
        },
    
        data: function data$4() {
            return {
                default: {
                    type: 0,
                    title: null,
                    dealId: 0,
                    contactId: 0,
                    contact: null,
                    companyId: 0,
                    company: null,
                    assignedToId: 0,
                    assignedTo: null,
                    start: null,
                    end: null,
                    isStartTimeSet: false,
                    note: null,
                    doneAt: null
                },
                activity: {},
                doingAjax: false,
                includeForm: false
            };
        },
    
        ready: function ready$2() {
            var self = this;
    
            $(self.$el).on('show.erp.modal', function () {
                self.includeForm = true;
            });
    
            $(self.$el).on('hide.erp.modal', function () {
                $(self.$el).find('.activity-types-container').scrollLeft(0);
            });
    
            $(self.$el).on('hidden.erp.modal', function () {
                self.includeForm = false;
            });
        },
    
        computed: {
            modalTitle: function modalTitle() {
                var title = this.i18n.scheduleAnActivity;
    
                if (this.activity.hasOwnProperty('id') && this.activity.id) {
                    title = this.i18n.editActivity;
                }
    
                return title;
            }
        },
    
        events: {
            'open-activity-modal': function open_activity_modal(activity, dealId, dealNames) {
                // reset to defaults in case of the modal open for second time
                this.activity = {};
    
                // editing existing activity
                if (activity.hasOwnProperty('id')) {
                    this.activity = $.extend(true, {}, this.underscoreToCamelObject(activity));
    
                // create new activity
                } else {
                    // use the default empty activity object
                    this.activity = $.extend(true, {}, this.default);
    
                    // set the deal id
                    this.activity.dealId = dealId;
    
                    // set contact id and name of the deal
                    this.activity.contactId = parseInt(dealNames.contactId);
                    this.activity.contact = dealNames.contact;
    
                    // set company id and name of the deal
                    this.activity.companyId = parseInt(dealNames.companyId);
                    this.activity.company = dealNames.company;
    
                }
    
                $(this.$el).erpDealModal();
            },
    
            'activity-form-saved': function activity_form_saved(deal, activity) {
                this.$dispatch('update-deal-activity-start', deal);
                $(this.$el).erpDealModal('hide');
    
                this.$dispatch('save-activity', activity, deal);
            }
        }
    });
    
    Vue.component('changelog', {
        /* global dealUtils */
        template: '<div class="deal-changelog erp-deals-timeline"><div v-if="!isReady" class="not-ready text-center"><span class="erp-spinner"></span><p>{{ i18n.loading }}...</p></div><div v-else class="timeline-content-container"><div v-for="monthItems in timelineItems | orderBy \'firstDayOfMonth\' -1" class="items-in-a-month"><div class="time-label">{{ monthItems.monthLabel }}</div><div v-for="singleItem in monthItems.items | orderBy \'createdAt\' -1" class="single-item clearfix"><i class="timeline-icon"></i><div class="timeline-item-container"><div v-if="\'add\' === singleItem.log.changeType" class="timeline-item-flat"><p class="timeline-item-title"><span v-if="\'deal\' === singleItem.log.type">{{ i18n.dealCreated }} </span><span v-if="\'activity\' === singleItem.log.type">{{ i18n.activityCreated }}: {{ singleItem.log.title }}</span></p><div class="timeline-item-footer"><ul class="list-inline"><li class="created-at">{{ getTimelineTimeFormat(singleItem.log.createdAt) }}</li><li class="created-by">{{ singleItem.log.createdBy }}</li></ul></div></div><div v-if="\'edit\' === singleItem.log.changeType" class="timeline-item-flat"><div v-if="hasSingleFieldUpdate(singleItem)"><div v-if="\'deal\' === singleItem.log.type" class="timeline-item-title">{{ i18n[singleItem.log.type] }} - {{{ getSingleFieldChange(singleItem) }}}</div><div v-if="\'activity\' === singleItem.log.type" class="timeline-item-title">{{ i18n[singleItem.log.type] }} - {{ singleItem.log.title }} - {{{ getSingleFieldChange(singleItem) }}}</div><div v-if="titleOnlyTypes.indexOf(singleItem.log.type) >= 0" class="timeline-item-title">{{ i18n[singleItem.log.type] }} - {{{ singleItem.log.title }}}</div><partial name="changelog-footer"></partial></div><div v-if="\'deal\' === singleItem.log.type && \'sticky\' === singleItem.log.subChangeType"><div v-if="\'deal\' === singleItem.log.type" class="timeline-item-title">{{ i18n[singleItem.log.type] }} - {{{ getSingleFieldChange(singleItem) }}}</div><partial name="changelog-footer"></partial></div><div v-if="showTableLog(singleItem)"><div class="timeline-item-title margin-bottom-8">{{ i18n[singleItem.log.type] }} - {{ singleItem.log.title }}</div><table class="wp-list-table widefat striped"><thead><tr><th>{{ i18n.field }}</th><th>{{ i18n.oldValue }}</th><th>{{ i18n.newValue }}</th></tr></thead><tbody><tr v-for="changes in getMultiFieldChanges(singleItem)"><td>{{ changes.field }}</td><td>{{{ changes.old }}}</td><td>{{{ changes.new }}}</td></tr></tbody></table><partial name="changelog-footer"></partial></div></div><div v-if="\'delete\' === singleItem.log.changeType" class="timeline-item-flat"><div class="timeline-item-title">{{ i18n[singleItem.log.type] }} {{ i18n.deleted }} - {{ singleItem.log.title }}</div><partial name="changelog-footer"></partial></div></div></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            dealId: {
                required: true
            }
        },
    
        data: function data$5() {
            return {
                changelog: [],
                isReady: false,
                titleOnlyTypes: ['agents', 'attachment']
            };
        },
    
        ready: function ready$3() {
            var self = this;
    
            // ajax update
            $.ajax({
                url: erpDealsGlobal.ajaxurl,
                method: 'post',
                dataType: 'json',
                data: {
                    action: 'get_changelog',
                    _wpnonce: erpDealsGlobal.nonce,
                    deal_id: this.dealId
                }
    
            }).done(function (response) {
                if (response.success) {
                    self.changelog = self.camelizedArray(response.data.log);
                }
            }).always(function () {
                self.isReady = true;
            });
        },
    
        computed: {
            timelineItems: function timelineItems() {
                var timelineItems = [];
                var items = {};
    
                this.changelog.map(function (log) {
                    var firstDayOfMonth = moment(log.createdAt, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-01');
    
                    if (!items.hasOwnProperty(firstDayOfMonth)) {
                        items[firstDayOfMonth] = {
                            monthLabel: moment(log.createdAt, 'YYYY-MM-DD HH:mm:ss').format('MMMM, YYYY'),
                            items: []
                        };
                    }
    
                    items[firstDayOfMonth].items.push({
                        createdAt: log.createdAt,
                        log: log
                    });
                });
    
                for(var firstDayOfMonth in items) {
                    timelineItems.push({
                        firstDayOfMonth: firstDayOfMonth,
                        monthLabel: items[firstDayOfMonth].monthLabel,
                        items: items[firstDayOfMonth].items
                    });
                }
    
                return timelineItems;
            },
        },
    
        methods: {
            getTimelineTimeFormat: function getTimelineTimeFormat(time) {
                return moment(time, 'YYYY-MM-DD HH:mm:ss').format('ddd MMM DD, hh:mm A');
            },
    
            hasSingleFieldUpdate: function hasSingleFieldUpdate(item) {
                if (item.log.newValues.hasOwnProperty('lostAt') && item.log.newValues.lostAt) {
                    return true;
                }
    
                if (item.log.oldValues.hasOwnProperty('lostAt') && item.log.oldValues.lostAt) {
                    return true;
                }
    
                return Object.keys(item.log.oldValues).length < 2;
            },
    
            getSingleFieldChange: function getSingleFieldChange(item) {
                var log = item.log;
                var field = Object.keys(log.oldValues)[0];
    
                if ('doneAt' === field) {
                    return log.newValues.doneAt ? this.i18n.markedAsDone : this.i18n.markedAsTodo;
                }
    
                var oldValue = this.getFormattedField(log.type, 'old', field, log.oldValues[field]);
                var newValue = this.getFormattedField(log.type, 'new', field, log.newValues[field]);
    
                if ('note' === field) {
                    oldValue = oldValue || '';
                    newValue = newValue || '';
    
                    var tmplt = (this.i18n[field]) + ": " + (log.subChangeMsg) + "<br>";
    
                    if ('add' !== log.subChangeType && 'sticky' !== log.subChangeType) {
                        tmplt += "<div class=\"changelog-note-wrapper\">\n                                <h5 data-sub-change-type=\"" + (log.subChangeType) + "\">" + (this.i18n.oldValue) + "</h5>\n                                <div class=\"changelog-note old-value\">" + oldValue + "</div>\n                             </div>";
                    }
    
                    if ('delete' !== log.subChangeType) {
                        tmplt += "<div class=\"changelog-note-wrapper\">\n                                <h5 data-sub-change-type=\"" + (log.subChangeType) + "\">" + (this.i18n.newValue) + "</h5>\n                                <div class=\"changelog-note\">" + newValue + "</div>\n                             </div>";
                    }
    
                    return tmplt;
                }
    
                if ('wonAt' === field) {
                    if (!log.oldValues.wonAt) {
                        return this.i18n.markedAsWon;
                    } else {
                        return this.i18n.reopened;
                    }
                }
    
                if (log.newValues.lostAt) {
                    return this.i18n.markedAsLost;
                } else if (log.oldValues.lostAt) {
                    return this.i18n.reopened;
                }
    
                if (log.subChangeType && 'trashed' === log.subChangeType) {
                    return this.i18n.trashed;
                }
    
                if (log.subChangeType && 'restored' === log.subChangeType) {
                    return this.i18n.restored;
                }
    
                if ('contact' === field || 'company' === field) {
                    var matchOldPeople = log.oldValues[field].match(/>(.*?)<\/a>/);
                    var matchNewPeople = log.newValues[field].match(/>(.*?)<\/a>/);
    
                    if (!matchOldPeople || !matchOldPeople[1].trim()) {
                        return this.i18n[field + 'Added'] + ' ' + newValue;
                    }
    
                    if (!matchNewPeople || !matchNewPeople[1].trim()) {
                        return this.i18n[field + 'Removed'] + ' ' + oldValue;
                    }
                }
    
                return ((this.i18n[field]) + ": " + oldValue + " → " + newValue);
            },
    
            getMultiFieldChanges: function getMultiFieldChanges(item) {
                var this$1 = this;
    
                var log = item.log;
                var changes = [];
    
                for (var field in log.oldValues) {
                    if ('doneAt' === field) {
                        changes.push({
                            field: this$1.i18n.status,
                            old: log.newValues.doneAt ? '-' : this$1.i18n.markedAsTodo,
                            new: log.newValues.doneAt ? this$1.i18n.markedAsDone : '-'
                        });
                    } else {
                        changes.push({
                            field: this$1.i18n[field],
                            old: this$1.getFormattedField(log.type, 'old', field, log.oldValues[field]),
                            new: this$1.getFormattedField(log.type, 'new', field, log.newValues[field])
                        });
                    }
                }
    
                return changes;
            },
    
            getFormattedField: function getFormattedField(type, age, field, value) {
                switch(field) {
    
                    case 'expectedCloseDate':
                        if (!value) {
                            value = ('old' === age) ? ("(" + (this.i18n.notSet) + ")") : ("(" + (this.i18n.removed) + ")");
                        } else {
                            value = moment(value, 'YYYY-MM-DD HH:mm:ss').format('MMMM DD, YYYY');
                        }
                        break;
    
                    case 'time':
                    case 'duration':
                        if ('activity' === type && !value) {
                            value = ('old' === age) ? ("(" + (this.i18n.notSet) + ")") : ("(" + (this.i18n.removed) + ")");
                        }
                        break;
    
                }
    
                return value;
            },
    
            showTableLog: function showTableLog(item) {
                if ( this.hasSingleFieldUpdate(item) ) {
                    return false;
                }
    
                if ( 'deal' === item.log.type && 'sticky' === item.log.subChangeType ) {
                    return false;
                }
    
                return true;
    
            }
        }
    });
    
    Vue.component('competitors', {
        /* global dealUtils */
    
        template: '<div class="deal-competitors"><div class="clearfix"><h3 class="postbox-title-outside pull-left">{{ i18n.competitors }}</h3><button type="button" class="button button-small button-link pull-right add-new-btn" @click="openCompetitorModal(default)"><i class="dashicons dashicons-plus"></i> {{ i18n.addNewCompetitor }}</button></div><div class="postbox erp-deal-postbox margin-bottom-20"><div class="postbox-inside no-padding"><table :class="[\'wp-list-table widefat striped\', doingAjax ? \'disabled\' : \'\']"><thead><tr><th class="column-competitor-name">{{ i18n.name }}</th><th class="column-website">{{ i18n.website }}</th><th class="column-strengths">{{ i18n.strengths }}</th><th class="column-weaknesses">{{ i18n.weaknesses }}</th><th class="column-action"></th></tr></thead><tbody v-if="!competitors.length"><tr><td colspan="5" class="text-center">{{ i18n.noCompetitorsMsg }}</td></tr></tbody><tbody v-else><tr v-for="competitor in competitors" :id="\'competitor-row-id-\' + competitor.id" class="has-action-btns"><td class="column-competitor-name"><a href="#edit-competitor" @click.prevent="openCompetitorModal(competitor)">{{ competitor.competitorName }}</a></td><td class="column-website"><a v-if="competitor.website" :href="competitor.website" target="_blank">{{ competitor.website }}</a></td><td class="column-strengths">{{ competitor.strengths }}</td><td class="column-weaknesses">{{ competitor.weaknesses }}</td><td class="column-action"><button type="button" class="button button-link" @click="openCompetitorModal(competitor)" v-erp-tooltip :tooltip-title="i18n.edit" :disabled="doingAjax"><i class="dashicons dashicons-edit"></i></button> <button v-if="isUserCanDeleteCompetitor(competitor)" type="button" class="button button-link" @click="deleteCompetitor(competitor)" v-erp-tooltip :tooltip-title="i18n.delete" :disabled="doingAjax"><i class="dashicons dashicons-trash"></i></button></td></tr></tbody></table><div :class="[\'list-table-row-progress\', showRowProgress ? \'in\' : \'\']" :style="rowProgressStyle" :id="\'list-table-row-progress-\' + _uid"></div></div></div><div class="erp-deal-modal erp-deal-competitor-modal" tabindex="-1" :id="\'competitor-modal-\' + _uid"><div class="erp-deal-modal-dialog modal-sm" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">&times;</span></button><h4 class="erp-deal-modal-title">{{ modalTitle }}</h4></div><form @reset="resetForm" @submit.prevent="submitForm"><div class="erp-deal-modal-body" :id="\'competitor-form-\' + _uid"><div :class="[\'margin-bottom-15\', competitorNameErrorClass]"><label>{{ i18n.competitorName }} <input type="text" class="erp-deal-input" v-model="competitor.competitorName" @input="competitorNameErrorClass = \'\'"></label></div><div class="margin-bottom-15"><label>{{ i18n.website }} <input type="url" class="erp-deal-input" v-model="competitor.website"></label></div><div class="margin-bottom-15"><label>{{ i18n.strengths }} <input type="text" class="erp-deal-input" v-model="competitor.strengths"></label></div><div class="margin-bottom-15"><label>{{ i18n.weaknesses }} <input type="text" class="erp-deal-input" v-model="competitor.weaknesses"></label></div></div><div class="erp-deal-modal-footer"><button type="reset" class="button button-link" :disabled="doingAjax" @click="closeModal">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary" :disabled="doingAjax">{{ i18n.saveActivity }}</button></div></form></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            dealId: {
                required: true,
                default: 0
            },
    
            competitors: {
                type: Array,
                required: true,
                default: [],
                twoWay: true
            },
        },
    
        data: function data$6() {
            return {
                default: {
                    dealId: this.dealId,
                    competitorName: '',
                    website: '',
                    strengths: '',
                    weaknesses: ''
                },
                competitor: {},
                doingAjax: false,
                competitorNameErrorClass: '',
                showRowProgress: false,
                rowProgressStyle: {}
            };
        },
    
        ready: function ready$4() {
            var self = this;
    
            $('#competitor-modal-' + this._uid).on('hidden.erp.modal', function () {
                self.resetForm();
            });
        },
    
        computed: {
            modalTitle: function modalTitle$1() {
                var title = this.i18n.addNewCompetitor;
    
                if (this.competitor.hasOwnProperty('id') && this.competitor.id) {
                    title = this.i18n.editCompetitor;
                }
    
                return title;
            }
        },
    
        methods: {
            openCompetitorModal: function openCompetitorModal(competitor) {
                this.competitor = $.extend(true, {}, competitor);
    
                $('#competitor-modal-' + this._uid).erpDealModal();
            },
    
            resetForm: function resetForm$1() {
                this.competitor = $.extend(true, {}, this.default);
            },
    
            submitForm: function submitForm$1() {
                var self = this;
    
                if (!this.competitor.competitorName) {
                    this.competitorNameErrorClass = 'input-error';
    
                    return false;
                }
    
    
                // store data to db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_competitor',
                        _wpnonce: erpDealsGlobal.nonce,
                        competitor: this.camelToUnderscoreObject(this.competitor)
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#competitor-form-' + self._uid
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        var competitor = self.camelizedObject(response.data.competitor);
    
                        // push new
                        if (!parseInt(self.competitor.id)) {
                            self.competitors.$set(self.competitors.length, competitor);
    
                        } else {
                            var index = self.competitors.length;
    
                            while (index--) {
                                if (parseInt(self.competitors[index].id) === parseInt(response.data.competitor.id)) {
                                    self.competitors.$set(index, competitor);
                                }
                            }
                        }
    
                        self.closeModal();
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            closeModal: function closeModal() {
                $('#competitor-modal-' + this._uid).erpDealModal('hide');
            },
    
            deleteCompetitor: function deleteCompetitor(competitor) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteCompetitorWarningMsg,
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
                                action: 'delete_competitor',
                                _wpnonce: erpDealsGlobal.nonce,
                                id: competitor.id
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                self.showRowProgress = true;
    
                                var tr = $('#competitor-row-id-' + competitor.id);
                                var top = tr.position().top;
                                var height = tr.height();
    
                                self.rowProgressStyle = {
                                    top: top + 'px', height: height + 'px'
                                };
    
                                NProgress.configure({
                                    parent: '#list-table-row-progress-' + self._uid,
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.competitors.$remove(competitor);
                                    self.showRowProgress = false;
                                }, 201);
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            isUserCanDeleteCompetitor: function isUserCanDeleteCompetitor( competitor ) {
                // Site Admin and CRM Manager can delete
                if ( erpDealsGlobal.isUserAnAdmin || erpDealsGlobal.isUserAManager ) {
                    return true;
    
                // agents can only delete competitor created by him/herself
                } else if ( erpDealsGlobal.isUserAnAgent && ( parseInt(erpDealsGlobal.currentUserId) === parseInt( competitor.createdBy ) ) ) {
                    return true;
                }
    
                return false;
            }
        }
    });
    
    Vue.component('deal-agents', {
        template: '<div class="postbox erp-deal-postbox deal-agent-postbox"><div class="hndle-btn-with-popover"><button type="button" class="button button-link button-small hndle-btn" v-erp-tooltip :tooltip-title="i18n.addAgents" @click="openPopover = true"><i class="dashicons dashicons-plus"></i></button><div v-if="openPopover" class="erp-popover top arrow-right"><div class="erp-popover-arrow"></div><form @submit.prevent="addAgents"><div class="erp-popover-content"><label>{{ i18n.addAgents }}</label><multiselect :options="crmAgentOptions" :selected="selectedAgents" :multiple="true" :searchable="false" :close-on-select="false" :show-labels="false" :allow-empty="true" :placeholder="i18n.addAgents" @update="onAgentSelect" label="name" key="id"></multiselect></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="openPopover = false">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></form></div></div><h3 class="hndle">{{ i18n.agents }}</h3><div class="postbox-inside"><ul v-if="dealAgents.length" class="deal-postbox-list agent-list"><li v-for="agent in dealAgents" class="clearfix"><a class="name link-black pull-left" :href="agent.link" target="_blank"><img :src="agent.avatar" alt="agent.name"> {{ agent.name }} </a><a href="#remove-agent" class="remove-name pull-right" @click.prevent="removeAgent(agent.id)">{{ i18n.remove }}</a></li></ul><span v-else>{{ i18n.noAgentMsg }}</span></div></div>',
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            agents: {
                type: Array,
                required: true,
                default: [],
                twoWay: true
            },
    
            crmAgents: {
                type: Array,
                required: true,
                default: []
            },
    
            dealOwnerId: {
                type: [Number, String],
                required: true,
                default: 0
            }
        },
    
        data: function data$7() {
            return {
                openPopover: false,
                selectedAgents: []
            };
        },
    
        computed: {
            dealAgents: function dealAgents() {
                var agents = this.agents.map(function (agentId) {
                    return parseInt(agentId);
                });
    
                return this.crmAgents.filter(function (crmAgent) {
                    return (agents.indexOf(parseInt(crmAgent.id)) >= 0);
                });
            },
    
            crmAgentOptions: function crmAgentOptions() {
                var self = this;
    
                return this.crmAgents.filter(function (crmAgent) {
                    return (self.agents.indexOf(parseInt(crmAgent.id)) < 0) && (parseInt(crmAgent.id) !== parseInt(self.dealOwnerId));
                });
            }
        },
    
        methods: {
            addAgents: function addAgents() {
                this.$dispatch('add-agents', this.selectedAgents);
                this.openPopover = false;
                this.selectedAgents = [];
            },
    
            onAgentSelect: function onAgentSelect$1(agents) {
                this.selectedAgents = agents;
            },
    
            removeAgent: function removeAgent(agentId) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.removeAgentWarnMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: this.i18n.yesRemoveIt,
                    // closeOnConfirm: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        self.$dispatch('remove-agent', agentId);
                    }
    
                    // swal.close();
                });
            }
        }
    });
    
    Vue.component('deal-feed-editors', {
        template: '<div class="erp-feed-editors-container"><div class="erp-feed-editors-tabs"><ul class="list-inline"><li v-for="feed in feeds" :class="[\'feed-\' + feed.type, feed.type === currentTab ? \'active\' : \'\']"><button type="button" class="button button-link" @click="currentTab = feed.type"><i :class="feed.icon"></i> {{ feed.tabTitle }}</button></li></ul></div><div class="erp-feed-editors"><template v-for="feed in feeds"><template v-if="feed.type === currentTab"><component :is="\'feed-editor-\' + feed.type" :i18n="i18n" :deal="deal" :users="users"></component></template></template></div></div>',
    
        props: {
            i18n: {
                type: Object,
                default: {},
                required: true,
            },
    
            feeds: {
                type: Array,
                default: [],
                required: true
            },
    
            deal: {
                type: Object,
                default: {},
                required: true,
            },
    
            users: {
                type: Object,
                default: {},
                required: true
            }
        },
    
        data: function data$8() {
            return {
                currentTab: ''
            };
        },
    
        ready: function ready$5() {
            if (this.feeds.length) {
                this.currentTab = this.feeds[0].type;
            }
        }
    });
    
    Vue.component('deal-menu', {
        /* global dealUtils */
        template: '<div class="deal-menu"><a v-if="canPerformActions" class="menu-button ignore-sortable" href="#deal-menu" @click.prevent="onButtonClick" @focusout="onButtonFocusOut">⋯ </a><a v-else class="menu-button ignore-sortable disabled" href="#deal-menu">⋯</a><div v-show="showDealMenu" :class="[\'erp-popover\', popoverClass]"><div class="erp-popover-arrow" :style="popoverArrowStyle"></div><div class="erp-popover-content"><ul><li v-if="!deal.wonAt && !deal.lostAt && !deal.deletedAt"><a href="#won" @click.prevent="wonDeal">{{ i18n.markAsWon }}</a></li><li v-if="!deal.wonAt && !deal.lostAt && !deal.deletedAt"><a href="#lost" @click.prevent="lostDeal">{{ i18n.markAsLost }}</a></li><li v-if="(deal.wonAt || deal.lostAt) && !deal.deletedAt"><a href="#reopen" @click.prevent="reopenDeal">{{ i18n.reopen }}</a></li><li v-if="!deal.deletedAt"><a href="#reopen" @click.prevent="deleteDeal(\'trash\')">{{ i18n.trash }}</a></li><li v-if="deal.deletedAt"><a href="#reopen" @click.prevent="dealOperations(\'restore\')">{{ i18n.restore }}</a></li><li v-if="deal.deletedAt"><a href="#reopen" @click.prevent="deleteDeal(\'delete\')">{{ i18n.delete }}</a></li></ul></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                default: {},
                twoWay: true
            },
        },
    
        data: function data$9() {
            return {
                showDealMenu: false,
                popoverClass: 'right',
                popoverStyle: {},
                popoverArrowStyle: {},
            };
        },
    
        computed: {
            canPerformActions: function canPerformActions() {
                if (parseInt(erpDealsGlobal.currentUserId) === parseInt(this.deal.owner.id)) {
                    return true;
                } else if (erpDealsGlobal.isUserAManager || erpDealsGlobal.isUserAnAdmin) {
                    return true;
                }
    
                return false;
            }
        },
    
        methods: {
            onButtonClick: function onButtonClick() {
                this.showDealMenu = true;
                this.calculatePopoverPosition();
            },
    
            calculatePopoverPosition: function calculatePopoverPosition$1() {
                this.popoverStyle = {};
                this.popoverArrowStyle = {};
    
                var popoverClass = 'right';
                var container = $('#erp-deals-pipeline-view').parent();
                var button = $(this.$el).children('.menu-button');
                var popover = $(this.$el).children('.erp-popover');
    
                var containerEdgeX = container.offset().left + container.width();
                var popoverEdgeX = button.offset().left + popover.width() + 32;
    
                if (popoverEdgeX > containerEdgeX) {
                    popoverClass = 'left';
                    this.popoverStyle.left = -popover.outerWidth() + 'px';
                }
    
                var containerEdgeY = container.offset().top + container.height();
                var popoverEdgeY = button.offset().top + popover.height();
    
                if (popoverEdgeY > containerEdgeY) {
                    var outerHeight = popover.outerHeight();
    
                    popoverClass += ' pull-up';
                    this.popoverStyle.top = -(outerHeight - 35) + 'px';
                    this.popoverArrowStyle.top = (outerHeight - 28) + 'px';
                }
    
                this.$set('popoverClass', popoverClass);
            },
    
            onButtonFocusOut: function onButtonFocusOut() {
                this.showDealMenu = false;
            },
    
            wonDeal: function wonDeal() {
                var self = this;
    
                this.showDealMenu = false;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            won: true
                        }
                    },
                    beforeSend: function beforeSend() {
                       NProgress.configure({
                            parent: '#deal-id-' + self.deal.id
                        });
    
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        setTimeout(function () {
                            self.$dispatch('deal-won', self.camelizedObject(response.data.deal));
                        }, 201);
                    }
    
                }).always(function () {
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            lostDeal: function lostDeal() {
                this.$dispatch('open-lost-reason-modal', this.deal.id);
            },
    
            reopenDeal: function reopenDeal() {
                var self = this;
    
                this.showDealMenu = false;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            reopen: true
                        }
                    },
                    beforeSend: function beforeSend() {
                        NProgress.configure({
                            parent: '#deal-id-' + self.deal.id
                        });
    
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        setTimeout(function () {
                            self.$dispatch('remove-deal', self.deal);
                        }, 201);
                    }
    
                }).always(function () {
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            deleteDeal: function deleteDeal(action) {
                var self = this;
    
                swal({
                    title: ('trash' === action) ? '' : this.i18n.deleteDealWarningTitle,
                    text: ('trash' === action) ? this.i18n.trashDealWarningMsg : this.i18n.deleteDealWarningMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: ('trash' === action) ? this.i18n.yesTrashIt : this.i18n.yesDeleteIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        self.dealOperations(action);
                    }
                });
            },
    
            dealOperations: function dealOperations(action) {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'delete_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            action: action
                        }
                    },
                    beforeSend: function beforeSend() {
                        NProgress.configure({
                            parent: '#deal-id-' + self.deal.id
                        });
    
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        setTimeout(function () {
                            self.$dispatch('remove-deal', self.deal);
                        }, 201);
                    }
    
                }).always(function () {
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        }
    });
    
    Vue.component('deal-participants', {
        template: '<div class="postbox erp-deal-postbox"><button type="button" class="button button-link button-small hndle-btn" v-erp-tooltip :tooltip-title="i18n.addParticipants" @click="addParticipants"><i class="dashicons dashicons-plus"></i></button><h3 class="hndle">{{ i18n.participants }}</h3><div class="postbox-inside"><ul v-if="participants.length" class="deal-postbox-list participant-list"><li v-for="participant in participants" class="clearfix"><template v-if="\'contact\' === participant.peopleType"><i class="dashicons dashicons-admin-users"></i> <a class="name" :href="participant.detailsUrl" target="_blank" @click.prevent="openPopover = participant.id">{{ participant.firstName }} {{ participant.lastName }}</a></template><template v-else><i class="dashicons dashicons-building"></i> <a class="name" :href="participant.detailsUrl" target="_blank" @click.prevent="openPopover = participant.id">{{ participant.company }}</a></template><div v-if="participant.id === openPopover" class="erp-popover bottom arrow-left"><div class="erp-popover-arrow"></div><div class="erp-popover-content"><div class="profile-summery deal-row"><div class="col-2 avatar padding-right-0">{{{ participant.avatar.img }}}</div><div class="col-4 summery"><h3 v-if="\'contact\' === participant.peopleType"><a :href="participant.detailsUrl" target="_blank">{{ participant.firstName }} {{ participant.lastName }}</a></h3><h3 v-else><a :href="participant.detailsUrl" target="_blank">{{ participant.company }}</a></h3><div><p v-if="participant.email"><i class="dashicons dashicons-email-alt"></i> <a :href="\'mailto:\' + participant.email">{{ participant.email }}</a></p><p v-if="participant.phone"><i class="dashicons dashicons-phone"></i> <a :href="\'tel:\' + participant.phone">{{ participant.phone }}</a></p><p v-if="participant.mobile"><i class="dashicons dashicons-smartphone"></i> <a :href="\'tel:\' + participant.mobile">{{ participant.mobile }}</a></p></div></div></div><table class="table-profile"><tr><td class="label">{{ i18n.street1 }}</td><td class="sep">:</td><td class="value">{{{ participant.street1 }}}</td></tr><tr><td class="label">{{ i18n.street2 }}</td><td class="sep">:</td><td class="value">{{{ participant.street2 }}}</td></tr><tr><td class="label">{{ i18n.city }}</td><td class="sep">:</td><td class="value">{{{ participant.city }}}</td></tr><tr><td class="label">{{ i18n.state }}</td><td class="sep">:</td><td class="value">{{{ participant.stateName }}}</td></tr><tr><td class="label">{{ i18n.country }}</td><td class="sep">:</td><td class="value">{{{ participant.countryName }}}</td></tr><tr><td class="label">{{ i18n.postalCode }}</td><td class="sep">:</td><td class="value">{{{ participant.postalCode }}}</td></tr></table></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="openPopover = 0">{{ i18n.close }}</button> <button type="submit" class="button button-danger" @click="removePeople(participant.id)">{{ i18n.removeParticipant }}</button></div></div></li></ul><span v-else>{{ i18n.noParticipantsMsg }}</span></div></div>',
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            participants: {
                type: Array,
                required: true,
                default: [],
                twoWay: true
            }
        },
    
        data: function data$10() {
            return {
                openPopover: 0
            };
        },
    
        computed: {
    
        },
    
        methods: {
            addParticipants: function addParticipants() {
                this.$dispatch('open-add-participants-modal');
            },
    
            removePeople: function removePeople(peopleId) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.removeParticipantWarnMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: this.i18n.yesRemoveIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        self.$dispatch('remove-participant', peopleId);
                    }
                });
            }
        }
    });
    
    Vue.component('feed-editor-activity', {
        template: '<div class="erp-feed-editor-activity"><activity-form :i18n="i18n" :users="users" :activity.sync="activity" :doing-ajax.sync="doingAjax" :is-in-modal="false"></activity-form></div>',
    
        props: {
            i18n: {
                type: Object,
                default: {},
                required: true
            },
    
            deal: {
                type: Object,
                default: {},
                required: true
            },
    
            users: {
                type: Object,
                default: {},
                required: true
            }
        },
    
        data: function data$11() {
            return {
                activity: {
                    type: 0,
                    title: null,
                    dealId: this.deal.id,
                    contactId: this.deal.contact.id,
                    contact: this.deal.contact.firstName + ' ' + this.deal.contact.lastName,
                    companyId: this.deal.company.id,
                    company: this.deal.company.company,
                    assignedToId: 0,
                    assignedTo: null,
                    start: null,
                    end: null,
                    isStartTimeSet: false,
                    note: null,
                    doneAt: null
                },
    
                doingAjax: false
            };
        },
    
        events: {
            'activity-form-saved': function activity_form_saved$1(deal, activity) {
                this.$dispatch('save-activity', activity);
            }
        }
    });
    
    Vue.component('feed-editor-attachment', {
        /* global dealAttachment*/
        template: '<div class="erp-feed-editor-attachment"><div v-if="!deal.attachments.length" class="upload-button-container"><p>{{ i18n.addAttachmentMsg }}</p><button type="button" class="button button-hero" @click="uploadFiles">{{ i18n.addAttachment }}</button></div><div v-else><table class="wp-list-table widefat fixed striped valign-top uploaded-attachments"><tr v-for="attachment in deal.attachments"><td class="mime-type-icon"><i :class="[\'dashicons\', getMimeIconClass(attachment.type)]"></i></td><td class="filename"><a :href="attachment.url" target="_blank">{{ attachment.filename }}</a></td><td class="attachment-size">{{ attachment.filesize }}</td><td class="remove-attachment"><button type="button" class="button button-link" v-erp-tooltip :tooltip-title="i18n.remove" @click="removeAttachment(attachment.id)"><i class="dashicons dashicons-trash"></i></button></td></tr></table><div class="editor-footer"><button type="button" class="button" @click="uploadFiles">{{ i18n.attachMoreFiles }}</button></div></div></div>',
    
        mixins: [dealAttachment],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                default: {}
            },
    
            users: {
                type: Object,
                default: {}
            }
        },
    });
    
    Vue.component('feed-editor-email', {
        /* global dealUtils, dealAttachment */
        template: '<div class="erp-feed-editor-email"><form @submit.prevent="sendEmail" @reset.prevent="resetForm"><div class="feed-editor-content"><table class="email-headers"><tr><td class="label">{{ i18n.emailTemplate }}</td><td><multiselect :options="emailTemplates" :selected="selectedTemplate" :local-search="false" :show-labels="false" :disabled="doingAjax" @update="onTemplateSelect" label="name" key="id" :placeholder="i18n.selectEmailTemplate"><span v-if="!emailTemplates.length" slot="beforeList" class="multiselect__option">{{{ i18n.noTemplateMsg }}}</span></multiselect></td></tr><tr><td class="label">{{ i18n.to }}</td><td v-if="!replyToPeople"><multiselect :options="availableContacts" option-partial="contactNamesWithEmail" :selected="email.to" :multiple="true" :close-on-select="false" :show-labels="false" :disabled="doingAjax" @update="onContactSelect" placeholder="" label="name" key="email"></multiselect></td><td v-else>{{ replyTo.name }} &lt;{{ replyTo.email }}&gt;</td></tr><tr><td class="label">{{ i18n.subject }}</td><td><input type="text" class="erp-deal-input" v-model="email.subject" :disabled="doingAjax"></td></tr><tr><td colspan="2" class="text-editor-container"><text-editor :content.sync="email.content"></text-editor></td></tr></table><div class="email-attachment-list"><button type="button" class="button button-link" @click="openModal" :disabled="doingAjax"><i class="dashicons dashicons-plus"></i> {{ i18n.attachments }}</button><ul v-if="emailAttachments.length"><li v-for="attachment in emailAttachments"><a :href="attachment.url" target="_blank">{{ attachment.filename }}</a> ({{ attachment.filesize }}) <button type="button" class="button button-link" v-erp-tooltip :tooltip-title="i18n.remove" @click="afterRemoveAttachment(attachment.id)">×</button></li></ul></div></div><div class="editor-footer clearfix" :id="\'email-feed-footer-\' + editorId"><div class="pull-left"><div v-if="saveTemplate.showInput" class="erp-deal-button-group"><input type="text" @keyup.enter="saveEmailTemplate" @keydown.enter="saveEmailTemplate" :disabled="doingAjax" :placeholder="email.subject" v-model="saveTemplate.email.name" required autofocus> <button type="button" class="button" @click="saveEmailTemplate" :disabled="doingAjax">{{ i18n.saveTemplate }}</button> <span :class="[\'cancel-save-template\', doingAjax ? \'disabled\' : \'\']" v-erp-tooltip :tooltip-title="i18n.cancel" @click="saveTemplate.showInput = false">×</span></div><button v-else type="button" class="button" @click="saveTemplate.showInput = true" :disabled="doingAjax || saveTemplate.disabled">{{ i18n.saveThisTemplate }}</button></div><div class="pull-right"><button type="reset" class="button button-link" :disabled="doingAjax">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary" :disabled="isSendBtnDisabled">{{ i18n.sendEmail }}</button></div></div></form><div :class="[\'erp-deal-modal\', \'editor-mail-modal\', deal.attachments.length ? \'has-attachments\' : \'\']" :id="\'editor-mail-modal-\' + editorId" tabindex="-1"><div class="erp-deal-modal-dialog" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ i18n.attachments }}</h4></div><div class="erp-deal-modal-body" :id="\'editor-mail-modal-body\' + editorId"><div v-if="!deal.attachments.length" class="upload-button-container text-center"><p>{{ i18n.addAttachmentMsg }}</p><button type="button" class="button button-hero" @click="initUploadFiles">{{ i18n.addAttachment }}</button></div><div v-else><p class="lead-text">{{ i18n.chooseEmailAttachmentMsg }} -</p><table class="wp-list-table widefat fixed striped valign-top uploaded-attachments"><thead><tr><th class="selected-ids"><input type="checkbox" @click="toggleSelectAll" :checked="selectAll"></th><th class="filename">{{ i18n.filename }}</th><th class="attachment-size">{{ i18n.size }}</th><th class="remove-attachment">&nbsp;</th></tr></thead><tbody><tr v-for="attachment in deal.attachments"><td class="selected-ids"><input type="checkbox" :value="parseInt(attachment.id)" v-model="emailAttachmentIds"></td><td class="filename"><a :href="attachment.url" target="_blank"><i :class="[\'dashicons\', getMimeIconClass(attachment.type)]"></i> {{ attachment.filename }}</a></td><td class="attachment-size">{{ attachment.filesize }}</td><td class="remove-attachment"><button type="button" class="button button-link" v-erp-tooltip :tooltip-title="i18n.remove" @click="removeAttachment(attachment.id)"><i class="dashicons dashicons-trash"></i></button></td></tr></tbody></table><div class="editor-footer clearfix"><button type="button" class="button button-link" data-dismiss="erp-deal-modal">{{ i18n.close }}</button> <button type="button" class="button" @click="initUploadFiles">{{ i18n.attachMoreFiles }}</button></div></div></div></div></div></div></div>',
    
        mixins: [dealUtils, dealAttachment],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                default: {}
            },
    
            users: {
                // using in dealAttachment mixin
                type: Object,
                default: {}
            },
    
            parentEmail: {
                // in case of reply email parentEmail is the email of which we're replying
                type: Object,
                default: function default$1() {
                    return {};
                }
            },
        },
    
        data: function data$12() {
            return {
                emailTemplates: erpDealsGlobal.emailTemplates,
                selectedTemplate: {},
                email: {
                    to: [],
                    subject: '',
                    content: ''
                },
                editorId: this._uid,
                texteditorId: 0,
                doingAjax: false,
                emailAttachmentIds: [],
                selectAll: false,
                saveTemplate: {
                    disabled: true,
                    showInput: false,
                    email: {
                        name: '',
                        subject: '',
                        template: ''
                    }
                }
            };
        },
    
        computed: {
            availableContacts: function availableContacts() {
                var this$1 = this;
    
                var contacts = [];
    
                if (this.deal.company.email) {
                    contacts.push({
                        id: this.deal.company.id,
                        email: this.deal.company.email,
                        name: ("" + (this.deal.company.company)),
                        type: 'company'
                    });
                }
    
                if (this.deal.contact.email) {
                    contacts.push({
                        id: this.deal.contact.id,
                        email: this.deal.contact.email,
                        name: ((this.deal.contact.firstName) + " " + (this.deal.contact.lastName)),
                        type: 'contact'
                    });
                }
    
    
                if (this.deal.participants.length) {
                    var i = 0;
    
                    for (i = 0; i < this.deal.participants.length; i++) {
    
                        if (this$1.deal.participants[i].email) {
                            contacts.push({
                                id: this$1.deal.participants[i].id,
                                email: this$1.deal.participants[i].email,
                                name: ('contact' === this$1.deal.participants[i].peopleType) ?
                                        ((this$1.deal.participants[i].firstName) + " " + (this$1.deal.participants[i].lastName)) :
                                        ("" + (this$1.deal.participants[i].company)),
                                type: this$1.deal.participants[i].peopleType
                            });
                        }
                    }
                }
    
                return contacts;
            },
    
            emailAttachments: function emailAttachments() {
                var self = this;
    
                return this.deal.attachments.filter(function (attachment) {
                    return (self.emailAttachmentIds.indexOf(parseInt(attachment.id)) >= 0) ;
                });
            },
    
            isSendBtnDisabled: function isSendBtnDisabled() {
                if (this.doingAjax || !this.email.subject || !this.email.content || !this.email.to.length) {
                    return true;
                } else {
                    return false;
                }
            },
    
            replyToPeople: function replyToPeople() {
                return parseInt(this.parentEmail.userId);
            },
    
            parentId: function parentId() {
                return parseInt(this.parentEmail.parentId);
            },
    
            replyTo: function replyTo() {
                var self = this;
    
                var people = this.availableContacts.filter(function (contact) {
                    return parseInt(contact.id) === parseInt(self.replyToPeople);
                });
    
                if (people.length) {
                    return {
                        name: people[0].name,
                        email: people[0].email
                    };
    
                } else {
                    return {
                        name: '',
                        email: this.parentEmail.email
                    };
                }
            }
        },
    
        ready: function ready$6() {
            if (this.deal.company.email) {
                this.email.to.push({
                    email: this.deal.company.email,
                    name: ("" + (this.deal.company.company))
                });
            }
    
            if (this.deal.contact.email) {
                this.email.to.push({
                    email: this.deal.contact.email,
                    name: ((this.deal.contact.firstName) + " " + (this.deal.contact.lastName))
                });
            }
    
            if (this.replyToPeople) {
                this.email.to = [this.replyTo];
                this.email.subject = 'Re: ' + this.parentEmail.emailSubject;
            }
    
        },
    
        methods: {
            openModal: function openModal() {
                $('#editor-mail-modal-' + this.editorId).erpDealModal();
            },
    
            onTemplateSelect: function onTemplateSelect(template) {
                this.selectedTemplate = template;
    
                if (template) {
                    this.$set('saveTemplate.email.subject', template.subject);
                    this.$set('email.subject', template.subject);
    
                    this.$set('saveTemplate.email.template', template.template);
                    this.$set('email.content', template.template);
    
                    this.$broadcast('set-tinymce-content', template.template);
                }
            },
    
            saveEmailTemplate: function saveEmailTemplate() {
                var self = this;
    
                if (!this.saveTemplate.email.name) {
                    this.saveTemplate.email.name = this.email.subject;
                }
    
                var template = {
                    name: this.saveTemplate.email.name,
                    subject: this.email.subject,
                    template: this.email.content
                };
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'erp_deals_save_email_template',
                        _wpnonce: erpDealsGlobal.nonce,
                        template: template
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
    
                        NProgress.configure({
                            parent: '#email-feed-footer-' + self.editorId,
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.emailTemplates.push(template);
    
                        // reset
                        self.saveTemplate = {
                            disabled: true,
                            showInput: false,
                            email: {
                                name: '',
                                subject: self.email.subject,
                                template: self.email.content
                            }
                        };
    
                    }
    
                    if (response.data.msg) {
                        swal('', response.data.msg, response.success ? 'success' : 'error');
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            initUploadFiles: function initUploadFiles(e) {
                $('#editor-mail-modal-' + this.editorId).erpDealModal('hide');
                this.uploadFiles(e);
            },
    
            afterDoneAttaching: function afterDoneAttaching(attachment) {
                var attachmentId = parseInt(attachment.id);
    
                // prevent duplicating
                if (this.emailAttachmentIds.indexOf(attachmentId) < 0) {
                    this.emailAttachmentIds.push(attachmentId);
                }
    
                this.openModal();
            },
    
            afterRemoveAttachment: function afterRemoveAttachment(attachmentId) {
                this.emailAttachmentIds.$remove(parseInt(attachmentId));
            },
    
            toggleSelectAll: function toggleSelectAll() {
                var this$1 = this;
    
                this.selectAll = !this.selectAll;
    
                if (this.selectAll) {
                    var ids = [];
    
                    for(var i in this.deal.attachments) {
                        ids.push(parseInt(this$1.deal.attachments[i].id));
                    }
    
                    this.emailAttachmentIds = ids;
                } else {
                    this.emailAttachmentIds = [];
                }
            },
    
            activateSaveTmpltBtn: function activateSaveTmpltBtn() {
                if ((this.email.subject !== this.saveTemplate.email.subject) || (this.email.content !== this.saveTemplate.email.template)) {
                    this.saveTemplate.disabled = false;
                } else {
                    this.saveTemplate.disabled = true;
                }
    
                if (!this.email.subject || !this.email.content) {
                    this.saveTemplate.disabled = true;
                }
            },
    
            resetForm: function resetForm$2() {
                this.$broadcast('set-tinymce-content', '');
    
                this.$set('selectedTemplate', {});
    
                this.$set('emailAttachmentIds', []);
    
                var to      = this.replyToPeople ? [this.replyTo] : [];
                var subject = this.replyToPeople ? 'Re: ' + this.parentEmail.emailSubject : '';
    
                this.$set('email', {
                    to: to,
                    subject: subject,
                    content: ''
                });
    
                this.$set('saveTemplate', {
                    disabled: true,
                    showInput: false,
                    email: {
                        name: '',
                        subject: '',
                        template: ''
                    }
                });
            },
    
            onContactSelect: function onContactSelect$1(contacts) {
                this.email.to = contacts;
            },
    
            sendEmail: function sendEmail() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'erp_deals_send_email',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.deal.id,
                        parent_id: this.parentEmail.id,
                        email: this.email,
                        attachments: this.emailAttachmentIds
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
    
                        NProgress.configure({
                            parent: '#email-feed-footer-' + self.editorId,
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.resetForm();
    
                        if (response.data.emails.length) {
                            response.data.emails.map(function (email) {
                                self.deal.emails.$set(self.deal.emails.length, self.camelizedObject(email));
                            });
                        }
                    }
    
                    if (response.data.msg) {
                        swal('', response.data.msg, response.success ? 'success' : 'error');
                    }
    
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        },
    
        watch: {
            doingAjax: function doingAjax$1(status) {
                this.$broadcast('toggle-tinymce-mode', status);
            },
    
            emailAttachmentIds: function emailAttachmentIds(newIds) {
                // select all checkbox checked status
                if (newIds.length === this.deal.attachments.length) {
                    this.selectAll = true;
    
                } else {
                    this.selectAll = false;
                }
            },
    
            'email.content': function email_content() {
                this.activateSaveTmpltBtn();
            },
    
            'email.subject': function email_subject() {
                this.activateSaveTmpltBtn();
            }
        },
    
        events: {
            'open-attachment-modal': function open_attachment_modal(texteditorId) {
                this.texteditorId = texteditorId;
    
                this.openModal();
            }
        }
    });
    
    Vue.component('feed-editor-note', {
        /* global dealUtils */
        template: '<form @submit.prevent="saveNote" :class="[isDoingAjax ? \'disabled\' : \'\']"><div class="erp-feed-editor-note"><note :content.sync="content"></note></div><div class="editor-footer erp-feed-editor-note-footer"><label :class="[isBtnsDisabled ? \'disabled\' : \'\']"><input type="checkbox" v-model="isSticky"> {{ i18n.pinThisNote }}</label> <button type="reset" class="button button-default" :disabled="isBtnsDisabled">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary" :disabled="isBtnsDisabled">{{ i18n.save }}</button></div></form>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                default: {}
            },
    
            users: {
                type: Object,
                default: {},
                required: true
            }
        },
    
        data: function data$13() {
            return {
                content: '',
                isSticky: false,
                isDoingAjax: false
            };
        },
    
        computed: {
            isBtnsDisabled: function isBtnsDisabled() {
                return (!this.content || this.isDoingAjax);
            }
        },
    
        methods: {
            saveNote: function saveNote() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal_note',
                        _wpnonce: erpDealsGlobal.nonce,
                        note: {
                            deal_id: this.deal.id,
                            note: this.content,
                            is_sticky: this.isSticky
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.isDoingAjax = true;
                        NProgress.configure({ parent: '.editor-footer' });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.$el.reset();
                        self.isSticky = false;
                        self.$dispatch('added-deal-note');
    
                        self.deal.notes.$set(self.deal.notes.length, self.camelizedObject(response.data.note));
                    }
    
                }).always(function () {
                    self.isDoingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        }
    });
    
    Vue.component('lost-reason-modal', {
        /* global dealUtils */
        template: '<div class="erp-deal-modal fade" tabindex="-1"><div class="erp-deal-modal-dialog modal-sm" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ i18n.markAsLost }}</h4></div><div class="erp-deal-modal-body" id="lost-reason-modal-body"><div class="margin-bottom-15" :class="[\'margin-bottom-15\', lostReasonSelectInputClass]"><label>{{ i18n.lostReason }}</label><multiselect :options="lostReasonList" :selected="lostReasonSelected" :multiple="false" :searchable="false" :close-on-select="true" :show-labels="false" :allow-empty="false" :placeholder="i18n.selectAReason" @update="onLostReasonSelect" label="reason" key="id"></multiselect></div><div v-if="showOtherLostReason" :class="[\'margin-bottom-15\', lostReasonInputClass]"><label>{{ i18n.otherLostReason }}</label> <input type="text" class="erp-deal-input" v-model="otherLostReason" @focus.prevent="lostReasonInputClass = \'\'"></div><div><label>{{ i18n.lostReasonComment }} ({{ i18n.optional }})</label> <textarea class="erp-deal-input" rows="3" v-model="lostReasonComment"></textarea></div></div><div class="erp-deal-modal-footer"><button type="button" class="button" data-dismiss="erp-deal-modal" :disabled="doingAjax">{{ i18n.cancel }}</button> <button type="button" class="button button-danger" @click="saveData" :disabled="doingAjax">{{ i18n.markAsLost }}</button></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                required: true
            }
        },
    
        data: function data$14() {
            return {
                dealId: 0,
                lostReasonId: -1,
                lostReasonSelected: {},
                otherLostReason: null,
                lostReasonComment: null,
                doingAjax: false,
                lostReasonInputClass: '',
                lostReasonSelectInputClass: ''
            };
        },
    
        ready: function ready$7() {
            var self = this;
    
            // reset modal data on close
            $(self.$el).on('hidden.erp.modal', function () {
                self.dealId = 0;
                self.lostReasonId = -1;
                self.lostReasonSelected = {};
                self.otherLostReason = null;
                self.lostReasonComment = null;
                self.doingAjax = false;
                self.lostReasonInputClass = '';
                self.lostReasonSelectInputClass = '';
            });
        },
    
        computed: {
            lostReasonList: function lostReasonList() {
                var reasons = [ { id: 0, reason: this.i18n.other } ];
    
                return erpDealsGlobal.lostReasons.concat(reasons);
            },
    
            showOtherLostReason: function showOtherLostReason() {
                return !parseInt(this.lostReasonId);
            }
        },
    
        methods: {
            onLostReasonSelect: function onLostReasonSelect(reason) {
                this.lostReasonSelected = reason;
                this.lostReasonId = parseInt(reason.id);
                this.lostReasonSelectInputClass = '';
            },
    
            saveData: function saveData() {
                var this$1 = this;
    
                var self = this;
                var lostReasonId = parseInt(this.lostReasonId);
    
                // if no reason is set, then use the placeholder or the activity reason
                if (!lostReasonId && !this.otherLostReason) {
                    this.lostReasonInputClass = 'input-error';
    
                    return false;
                } else if (lostReasonId < 0) {
                    this.lostReasonSelectInputClass = 'input-error';
    
                    return false;
                }
    
                var deal = {
                    id: this.dealId,
                    lost_reason_comment: this.lostReasonComment
                };
    
                if (lostReasonId) {
                    deal.lost_reason_id = lostReasonId;
                } else {
                    deal.lost_reason = this.otherLostReason;
                }
    
                // store data to db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: deal
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#lost-reason-modal-body',
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.$dispatch('deal-lost', this$1.camelizedObject(response.data.deal));
    
                        $(self.$el).erpDealModal('hide');
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
        },
    
        events: {
            'open-lost-reason-modal': function open_lost_reason_modal(dealId) {
                this.dealId = parseInt(dealId);
    
                $(this.$el).erpDealModal();
            }
        }
    });
    
    Vue.component('new-deal-modal', {
        /* global dealUtils */
    
        template: '<div class="erp-deal-modal fade" tabindex="-1"><div class="erp-deal-modal-dialog modal-sm" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">&times;</span></button><h4 class="erp-deal-modal-title">{{ i18n.addNewDeal }}</h4></div><div class="erp-deal-modal-body" id="new-deal-modal-body"><div :class="[\'margin-bottom-15\', peopleErrorClass]"><label>{{ i18n.contact }}</label><multiselect :options="deal.contacts" :selected="deal.contactSelected" :local-search="false" :loading="deal.isContactSearching" :searchable="true" :show-labels="false" @search-change="searchContacts" @update="onContactSelect" @open="peopleErrorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg"><span slot="noResult">{{ i18n.noContactFound }}</span></multiselect></div><div :class="[\'margin-bottom-15\', peopleErrorClass]"><label>{{ i18n.company }}</label><multiselect :options="deal.companies" :selected="deal.companySelected" :local-search="false" :loading="deal.isCompanySearching" :searchable="true" :show-labels="false" @search-change="searchCompanies" @update="onCompanySelect" @open="peopleErrorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg"><span slot="noResult">{{ i18n.noCompanyFound }}</span></multiselect><p v-if="peopleErrorClass" class="text-danger"><small>{{ i18n.newDealPeopleError }}</small></p></div><div :class="[\'margin-bottom-15\', titleErrorClass]"><label>{{ i18n.dealTitle }}</label> <input type="text" class="erp-deal-input" v-model="deal.title" @focus.prevent="titleErrorClass = \'\'"></div><div class="margin-bottom-15"><label>{{ i18n.dealValue }} ({{ currencySymbol }})</label> <input type="number" step="any" class="erp-deal-input" v-model="deal.value"></div><div class="margin-bottom-15"><label>{{ i18n.pipelineStage }}</label><div class="step-progressbar"><ul><li v-for="stage in pipelineStages" :class="[(stage.id === deal.stageId) ? \'active\' : \'\']" @click="deal.stageId = stage.id" v-erp-tooltip :tooltip-title="stage.title"></li></ul></div></div><div v-if="isUserAManager" class="margin-bottom-15"><label>{{ i18n.owner }}</label><multiselect :options="crmAgents" :selected="deal.owner" :multiple="false" :searchable="false" :close-on-select="true" :show-labels="false" :allow-empty="false" @update="onOwnerSelect" label="name" key="id"></multiselect></div><div><label>{{ i18n.expectedCloseDate }}</label> <input type="text" class="erp-deal-input" v-model="deal.expectedCloseDate" v-erp-datepicker></div></div><div class="erp-deal-modal-footer"><button type="button" class="button" data-dismiss="erp-deal-modal" :disabled="doingAjax">{{ i18n.cancel }}</button> <button type="button" class="button button-primary" @click="saveData" :disabled="doingAjax">{{ i18n.save }}</button></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            currencySymbol: {
                type: String,
                required: true,
                default: ''
            },
    
            pipelineStages: {
                type: Array,
                required: true,
                default: []
            },
    
            users: {
                type: Object,
                required: true,
                default: {}
            }
        },
    
        data: function data$15() {
            return {
                isUserAManager: erpDealsGlobal.isUserAManager,
                default: {
                    contactSelected: {},
                    contacts: [],
                    isContactSearching: false,
                    companySelected: {},
                    companies: [],
                    isCompanySearching: false,
                    title: null,
                    value: null,
                    stageId: 0,
                    owner: {},
                    expectedCloseDate: null
                },
                deal: {},
                ajaxHandler: {
                    abort: function abort() {}
                },
                peopleErrorClass: '',
                titleErrorClass: '',
            };
        },
    
        computed: {
            doingAjax: function doingAjax$2() {
                return (this.deal.isContactSearching || this.deal.isCompanySearching);
            },
    
            crmAgents: function crmAgents$1() {
                var this$1 = this;
    
                var agents = [];
    
                for (var i in this.users.crmAgents) {
                    var id = parseInt(this$1.users.crmAgents[i].id);
    
                    agents[i] = {
                        id: id,
                        name: this$1.users.crmAgents[i].name
                    };
    
                    if (id === parseInt(this$1.users.currentUserId)) {
                        agents[i].name += ' (' + erpDealsGlobal.i18n.you + ')';
                    }
                }
    
                return agents;
            }
        },
    
        created: function created() {
            this.deal = $.extend(true, {}, this.default);
        },
    
        methods: {
            searchContacts: function searchContacts$1(s) {
                var self = this;
    
                if (s.length < 3) {
                    this.deal.contacts = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_people',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s,
                            contact: true
                        },
                        beforeSend: function beforeSend() {
                            self.deal.isContactSearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.deal.contacts = response.data.contacts;
                        }
    
                    }).always(function () {
                        self.deal.isContactSearching = false;
                    });
                }
            },
    
            onContactSelect: function onContactSelect$2(contact) {
                // in case of deselect/remove selection
                if (!contact) {
                    this.deal.contactSelected = {};
    
                    return;
                }
    
                this.deal.contactSelected = contact;
    
                if (contact.company_id) {
                    this.deal.companies = [
                        { id: contact.company_id, name: contact.company }
                    ];
    
                    this.deal.companySelected = { id: contact.company_id, name: contact.company };
    
                } else {
                    this.deal.companies = [];
                    this.deal.companySelected = {};
                }
            },
    
            searchCompanies: function searchCompanies$1(s) {
                var self = this;
    
                if (s.length < 3) {
                    this.deal.companies = [];
    
                } else {
                    this.deal.isCompanySearching = true;
    
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_people',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s,
                            company: true
                        },
                        beforeSend: function beforeSend() {
                            self.deal.isCompanySearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.deal.companies = response.data.companies;
                        }
    
                    }).always(function () {
                        self.deal.isCompanySearching = false;
                    });
                }
            },
    
            onCompanySelect: function onCompanySelect$1(company) {
                this.deal.companySelected = company ? company : {};
            },
    
            onOwnerSelect: function onOwnerSelect(owner) {
                this.deal.owner = owner;
            },
    
            saveData: function saveData$1() {
                var self = this;
    
                // validations
                if (!this.deal.contactSelected.id && !this.deal.companySelected.id) {
                    this.peopleErrorClass = 'input-error';
                }
    
                if (!this.deal.title) {
                    this.titleErrorClass = 'input-error';
                }
    
                if (this.peopleErrorClass || this.titleErrorClass) {
                    return;
                }
    
                var close_date = this.deal.expectedCloseDate ?
                                    moment(this.deal.expectedCloseDate, this.dateFormat).format('YYYY-MM-DD 23:59:59') :
                                    null;
    
                // store data to db
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            contact_id: this.deal.contactSelected.id,
                            company_id: this.deal.companySelected.id,
                            title: this.deal.title,
                            value: this.deal.value,
                            stage_id: this.deal.stageId,
                            owner_id: this.deal.owner.id,
                            expected_close_date: close_date
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#new-deal-modal-body',
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        var deal = self.camelizedObject(response.data.deal);
    
                        var newDeal = {
                            id: deal.id,
                            title: deal.title,
                            stageId: deal.stageId,
                            contactId: deal.contactId,
                            companyId: deal.companyId,
                            value: deal.value,
                            currency: deal.currency,
                            wonAt: null,
                            lostAt: null,
                            actStart: null,
                            contact: self.deal.contactSelected.name,
                            company: self.deal.companySelected.name,
                        };
    
                        var owner = self.users.crmAgents.filter(function (agent) {
                            return parseInt(agent.id) === parseInt(deal.ownerId);
                        });
    
                        owner = owner[0];
                        newDeal.owner = {
                            id: owner.id,
                            name: owner.name,
                            img: owner.avatar
                        };
    
                        self.$dispatch('new-deal-added', newDeal);
    
                        swal({
                            title: '',
                            text: self.i18n.dealCreateSuccessMsg,
                            type: 'success',
    
                        }, function () {
                            $(self.$el).erpDealModal('hide');
                        });
    
                    } else if (response.data.msg) {
                        swal({
                            title: '',
                            text: response.data.msg,
                            type: 'error'
                        });
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        },
    
        events: {
            'open-new-deal-modal': function open_new_deal_modal() {
                var this$1 = this;
    
                this.deal = $.extend(true, {}, this.default);
                this.deal.stageId = this.pipelineStages[0].id;
    
                if (this.isUserAManager) {
                    var currentUser = this.users.crmAgents.filter(function (agent) {
                        return (parseInt(this$1.users.currentUserId) === parseInt(agent.id));
                    });
    
                    this.deal.owner = {
                        id: currentUser[0].id,
                        name: currentUser[0].name + ' (' + erpDealsGlobal.i18n.you + ')'
                    };
                }
    
                $(this.$el).erpDealModal();
            }
        },
    
        watch: {
            doingAjax: function doingAjax$3(newVal) {
                this.modalKeepOpen(newVal);
            },
    
            'deal.contactSelected': function deal_contactSelected(newVal) {
                if (newVal.company) {
                    this.deal.title = newVal.company + ' deal';
    
                } else if (newVal.name) {
                    this.deal.title = newVal.name + ' deal';
                }
    
                if (newVal.id) {
                    this.peopleErrorClass = '';
                }
            },
    
            'deal.companySelected': function deal_companySelected(newVal) {
                if (!newVal.name && this.deal.contactSelected.name) {
                    this.deal.title = this.deal.contactSelected.name + ' deal';
    
                } else if (newVal.name) {
                    this.deal.title = newVal.name + ' deal';
                }
    
                if (newVal.id) {
                    this.peopleErrorClass = '';
                }
            },
    
            'deal.title': function deal_title(newVal) {
                if (newVal) {
                    this.titleErrorClass = '';
                }
            }
        }
    });
    
    Vue.component('note', {
        template: '<div :class="[\'erp-deal-note-container\', isFocused ? \'focused\' : \'\']"><trix-toolbar class="trix-toolbar" :id="toolbarId"><div class="trix-button-groups"><span class="trix-button-group"><button @click="isFocused = true" type="button" class="bold" data-trix-attribute="bold" data-trix-key="b" title="Bold"><i class="dashicons dashicons-editor-bold"></i></button> <button @click="isFocused = true" type="button" class="italic" data-trix-attribute="italic" data-trix-key="i" title="Italic"><i class="dashicons dashicons-editor-italic"></i></button> <button @click="isFocused = true" type="button" class="strike" data-trix-attribute="strike" title="Strikethrough"><i class="dashicons dashicons-editor-strikethrough"></i></button> <button @click="isFocused = true" type="button" class="link" data-trix-attribute="href" data-trix-action="link" data-trix-key="k" title="Link"><i class="dashicons dashicons-admin-links"></i></button> </span><span class="trix-button-group"><button @click="isFocused = true" type="button" class="list bullets" data-trix-attribute="bullet" title="Bullets"><i class="dashicons dashicons-editor-ul"></i></button> <button @click="isFocused = true" type="button" class="list numbers" data-trix-attribute="number" title="Numbers"><i class="dashicons dashicons-editor-ol"></i></button></span></div><div class="trix-dialogs"><div class="dialog link_dialog" data-trix-attribute="href" data-trix-dialog="href"><div class="link_url_fields"><div class="button-group"><input type="url" class="trix-link-input erp-deal-input" required name="href" placeholder="Enter a URL…" disabled> <input type="button" class="button" value="Link" data-trix-method="setAttribute"> <input type="button" class="button" value="Unlink" data-trix-method="removeAttribute"></div></div></div></div></trix-toolbar><trix-editor class="trix-editor" :input="editorId" :toolbar="toolbarId"></trix-editor><input type="hidden" :id="editorId" v-model="content"> <span v-if="!content" class="note-label">{{ placeholder }}</span></div>',
    
        props: {
            content: {
                required: true,
                default: '',
                twoWay: true,
                validator: function validator(val) {
                    return val === null || typeof val === 'string';
                }
            },
    
            placeholder: {
                type: String,
                default: erpDealsGlobal.i18n.notes
            }
        },
    
        data: function data$16() {
            return {
                isFocused: false
            };
        },
    
        ready: function ready$8() {
            var self = this;
    
            $(this.$el).find('trix-editor').html(this.content);
    
            $(this.$el).find('trix-editor').get(0).addEventListener('trix-change', function (e) {
                self.$set('content', e.target.innerHTML);
            });
    
            $(this.$el).find('trix-editor').on('trix-focus', function () {
                self.isFocused = true;
            });
    
            $(this.$el).find('trix-editor').on('trix-blur', function () {
                self.isFocused = false;
            });
        },
    
        computed: {
            editorId: function editorId() {
                return 'erp-deal-note-' + this._uid;
            },
    
            toolbarId: function toolbarId() {
                return 'erp-deal-note-toolbar-' + this._uid;
            }
        },
    
        events: {
            'reset-note': function reset_note(content) {
                $(this.$el).find('trix-editor').get(0).reset();
                this.$set('content', content);
            }
        }
    });
    
    Vue.component('open-activities', {
        /* global userCapabilities, dealUtils */
        template: '<div><h3 class="postbox-title-outside">{{ i18n.openActivities }}</h3><div class="postbox erp-deal-postbox deal-open-activities margin-bottom-20"><div class="postbox-inside no-padding"><table :class="[\'wp-list-table widefat striped\', doingAjax ? \'disabled\' : \'\']"><thead><tr><th class="column-done">{{ i18n.done }}</th><th class="column-type">{{ i18n.type }}</th><th class="column-title">{{ i18n.title }}</th><th class="column-due-date">{{ i18n.dueDate }}</th><th class="column-assigned-to">{{ i18n.assignedTo }}</th><th class="column-action"></th></tr></thead><tbody v-if="!openActivities.length"><tr><td colspan="6" class="text-center">{{ i18n.noUpcomingActivityMsg }}</td></tr></tbody><tbody v-else><tr v-for="activity in openActivities | orderBy \'start\'" :id="\'open-activity-row-id-\' + activity.id" class="has-action-btns"><td class="column-done"><span v-if="userCanEditActivity(activity)" :class="[\'deal-mark-as-done\', activity.doneAt ? \'done\' : \'\']" v-erp-tooltip :tooltip-title="i18n.markAsDone" @click="markAsDone(activity)"><i class="dashicons dashicons-yes"></i></span></td><td class="column-type"><span class="deal-icon" v-erp-tooltip :tooltip-title="getActivityTypeTitle(activity.type)"><i :class="[\'picon\', \'picon-\' + getActivityTypeIcon(activity.type)]"></i></span></td><td class="column-title"><a :class="statusClass(activity.start, activity.isStartTimeSet)" href="#edit-activity" @click.prevent="openActivityModal(activity)">{{ activity.title }}</a></td><td class="column-due-date">{{ getActivityDueDate(activity) }}</td><td class="column-assigned-to">{{{ getAssignedToName(activity) }}}</td><td class="column-action"><button v-if="userCanEditActivity(activity)" type="button" class="button button-link" @click="openActivityModal(activity)" v-erp-tooltip :tooltip-title="i18n.edit" :disabled="doingAjax"><i class="dashicons dashicons-edit"></i></button> <button v-if="userCanDeleteActivity(activity)" type="button" class="button button-link" @click="deleteActivity(activity)" v-erp-tooltip :tooltip-title="i18n.delete" :disabled="doingAjax"><i class="dashicons dashicons-trash"></i></button></td></tr></tbody></table><div :class="[\'list-table-row-progress\', showRowProgress ? \'in\' : \'\']" :style="rowProgressStyle" :id="\'list-table-row-progress-\' + _uid"></div></div></div></div>',
    
        mixins: [userCapabilities, dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            activities: {
                type: Array,
                default: [],
                required: true,
                twoWay: true
            },
    
            users: {
                type: Object,
                default: {},
                required: true
            }
        },
    
        data: function data$17() {
            return {
                activityTypes: erpDealsGlobal.activityTypes,
                doingAjax: false,
                showRowProgress: false,
                rowProgressStyle: {}
            };
        },
    
        computed: {
            openActivities: function openActivities() {
                return this.activities.filter(function (activity) {
                    return !activity.doneAt;
                });
            }
        },
    
        methods: {
            markAsDone: function markAsDone$3(activityData) {
                var self = this;
                var activity = $.extend(true, {}, activityData);
    
                activity.doneAt = activity.doneAt ? null : true;
    
                // ajax update
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity: {
                            id: activity.id,
                            done_at: activity.doneAt
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.initRowProgress(activity);
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        setTimeout(function () {
                            for (var i in self.activities) {
                                if (parseInt(self.activities[i].id) === parseInt(response.data.activity.id)) {
                                    self.activities.$set(i, self.camelizedObject(response.data.activity));
                                    break;
                                }
                            }
                            self.showRowProgress = false;
                        }, 201); // 201 is the NProgress default speed settings
    
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            getActivityTypeTitle: function getActivityTypeTitle$1(activityTypeId) {
                var type = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(activityTypeId);
                });
    
                type = type[0];
    
                return type.title;
            },
    
            getActivityTypeIcon: function getActivityTypeIcon$2(activityTypeId) {
                var type = this.activityTypes.filter(function (actType) {
                    return parseInt(actType.id) === parseInt(activityTypeId);
                });
    
                return type[0].icon;
            },
    
            openActivityModal: function openActivityModal$2(activity) {
                var names = {
                    contactId: activity.contactId,
                    contact: activity.contact,
                    companyId: activity.companyId,
                    company: activity.company
                };
    
                this.$dispatch('open-activity-modal', activity, activity.dealId, names);
            },
    
            getActivityDueDate: function getActivityDueDate$1(activity) {
                var dueDate = '';
                var dateFormat = erpDealsGlobal.date.format.replace('yy', 'yyyy').toUpperCase();
    
                if (parseInt(activity.isStartTimeSet)) {
                    dueDate = moment(activity.start, 'YYYY-MM-DD HH:mm:ss').format(dateFormat + ' hh:mm A');
                } else {
                    dueDate = moment(activity.start, 'YYYY-MM-DD HH:mm:ss').format(dateFormat);
                }
    
                return dueDate;
            },
    
            getAssignedToName: function getAssignedToName$1(activity) {
                var assignedTo = this.users.crmAgents.filter(function (agent) {
                    return parseInt(agent.id) === parseInt(activity.assignedToId);
                });
    
                assignedTo = assignedTo.pop();
    
                return ("<a href=\"" + (assignedTo.link) + "\" target=\"_blank\"><img src=\"" + (assignedTo.avatar) + "\">" + (assignedTo.name) + "</a>");
            },
    
            deleteActivity: function deleteActivity$1(activity) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteActivityWarningMsg,
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
                                action: 'delete_activity',
                                _wpnonce: erpDealsGlobal.nonce,
                                id: activity.id
                            },
                            beforeSend: function beforeSend() {
                                self.initRowProgress(activity);
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.activities.$remove(activity);
                                    self.showRowProgress = false;
                                }, 201); // 201 is the NProgress default speed settings
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            initRowProgress: function initRowProgress$1(activity) {
                this.doingAjax = true;
                this.showRowProgress = true;
    
                var tr = $('#open-activity-row-id-' + activity.id);
                var top = tr.position().top;
                var height = tr.height();
    
                this.rowProgressStyle = {
                    top: top + 'px', height: height + 'px'
                };
    
                NProgress.configure({
                    parent: '#list-table-row-progress-' + this._uid,
                    // afterDone: (nprogress) => {
                    //     nprogress.remove();
    
                    // }
                });
                NProgress.start();
            },
    
            statusClass: function statusClass$2(time, isStartTimeSet) {
                // const statuses = ['warning', 'overdue', 'today', 'future', 'rotten', 'status-won'];
    
                var className = 'warning';
    
                var now = moment();
                var today = now.format('YYYY-MM-DD');
                var tonight = today + ' 23:59:59';
                var date = moment(time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
    
                if (!parseInt(isStartTimeSet) && moment(date).isSame(moment(today))) {
                    className = 'today';
                } else if ( moment(time).isBefore(now) ) {
                    className = 'overdue';
                } else if ( moment(time).isBetween(now, tonight) ) {
                    className = 'today';
                } else if ( moment(time).isAfter(now) ) {
                    className = 'future';
                }
    
                return className;
            },
        }
    });
    
    Vue.component('overview', {
        /* global dealUtils */
        template: '<div class="erp-deal-pipeline-view-container"><h1>{{ i18n.overview }}</h1><div v-if="isReady" id="deal-overview" class="margin-top-8"><div class="deal-row"><div class="col-4 deal-overview-left"><div class="deal-row margin-bottom-24"><div v-for="type in [\'new\', \'won\', \'lost\']" :class="[\'col-2 dashboard-info-box\', \'dashboard-info-box-\' + type]"><div class="box-body"><div class="clearfix"><div v-for="time in [\'current\', \'previous\']" class="column-content"><div class="header">{{ infoBox[type][time].header }}</div><div class="deal-count"><span class="count-no">{{ infoBox[type][time].count }}</span> {{ infoBox[type][time].count | pluralize i18n.deal }}</div><div class="deal-price">{{{ infoBox[type][time].price | currency companyCurrencySymbol }}}</div></div></div></div><div class="box-footer"><a href="#" @click.prevent="openDealListModal(type)">{{ i18n[type + \'Deals\'] }}</a></div></div></div><div class="postbox erp-deal-postbox margin-bottom-24 overview-progress-stat"><h3 class="hndle">{{ i18n.dealProgress }}</h3><div class="postbox-inside"><div class="clearfix progress-filters dashboard-filters"><div class="pull-left"><div :class="[\'erp-deal-dropdown\', showPipelineDropdown ? \'open\' : \'\']"><button class="button" @click="showPipelineDropdown = !showPipelineDropdown" @blur="showPipelineDropdown = false"><i class="dashicons dashicons-chart-bar"></i> {{ selectedPipeline.title }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="pipeline in pipelines" :class="[parseInt(pipeline.id) === parseInt(selectedPipeline.id) ? \'active\': \'\']"><a href="#" @mousedown.prevent="filterDealProgressPipeline(pipeline)">{{ pipeline.title }}</a></li></ul></div></div><div class="pull-left"><div :class="[\'erp-deal-dropdown\', showAgentDropdown ? \'open\' : \'\']"><button class="button" @click="showAgentDropdown = !showAgentDropdown" @blur="showAgentDropdown = false"><i class="dashicons dashicons-admin-users"></i> {{ selectedAgent.name }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="owner in crmOwners" :class="[parseInt(owner.id) === parseInt(selectedAgent.id) ? \'active\': \'\']"><a href="#" @mousedown.prevent="filterDealProgressAgent(owner)"><img class="avatar" :src="owner.avatar" :alt="owner.name" :title="owner.name"> {{ owner.name }}</a></li></ul></div></div><div class="pull-left"><div :class="[\'erp-deal-dropdown\', showTimeDropdown ? \'open\' : \'\']"><button class="button" @click="showTimeDropdown = !showTimeDropdown" @blur="showTimeDropdown = false"><i class="dashicons dashicons-calendar-alt"></i> {{ selectedTime.name }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="time in timeFilterOpts" :class="[time.slug === selectedTime.slug ? \'active\': \'\']"><a href="#" @mousedown.prevent="filterDealProgressTime(time)">{{ time.name }}</a></li></ul></div></div><div class="pull-right button-group"><button :class="[\'button\', (\'value\' === selelecteProgressType) ? \'active\' : \'\']" @click="selelecteProgressType = \'value\'">{{ i18n.value }}</button> <button :class="[\'button\', (\'count\' === selelecteProgressType) ? \'active\' : \'\']" @click="selelecteProgressType = \'count\'">{{ i18n.count }}</button></div></div><div class="progress-chart"><div id="deal-progress-chart" class="overview-big-chart"></div></div><div class="progress-data-table"><table class="wp-list-table widefat striped"><thead><tr><th class="text-left stage-name">{{ i18n.stage }}</th><th class="text-center">{{ i18n.countsOfDealsReachedTheStage }}</th><th class="text-center">{{ i18n.valuesOfDealsReachedTheStage }}</th><th class="text-center">{{ i18n.averageDealValue }} ({{ companyCurrency }})</th><th class="text-center">{{ i18n.avrgTimeToReachStg }}</th></tr></thead><tbody v-if="!dealProgress.length"><tr><td colspan="5" class="text-center">{{ i18n.noStatFound }}</td></tr></tbody><tbody v-else><tr v-for="stage in dealProgress"><td class="text-left stage-name">{{ stage.title }}</td><td class="text-center">{{ stage.dealCount }}</td><td class="text-center">{{ stage.totalValue }}</td><td class="text-center">{{ getStageAverageValue(stage) }}</td><td class="text-center">{{ getStageAverageReachTime(stage) }}</td></tr></tbody></table></div></div></div><div class="postbox erp-deal-postbox overview-progress-stat"><h3 class="hndle">{{ i18n.activityProgress }}</h3><div class="postbox-inside"><div class="clearfix progress-filters dashboard-filters"><div class="pull-left"><div :class="[\'erp-deal-dropdown\', showActAgentDropdown ? \'open\' : \'\']"><button class="button" @click="showActAgentDropdown = !showActAgentDropdown" @blur="showActAgentDropdown = false"><i class="dashicons dashicons-admin-users"></i> {{ actSelectedAgent.name }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="agent in crmAgents" :class="[parseInt(agent.id) === parseInt(actSelectedAgent.id) ? \'active\': \'\']"><a href="#" @mousedown.prevent="filterActProgressAgent(agent)"><img class="avatar" :src="agent.avatar" :alt="agent.name" :title="agent.name"> {{ agent.name }}</a></li></ul></div></div><div class="pull-left"><div :class="[\'erp-deal-dropdown\', showActTimeDropdown ? \'open\' : \'\']"><button class="button" @click="showActTimeDropdown = !showActTimeDropdown" @blur="showActTimeDropdown = false"><i class="dashicons dashicons-calendar-alt"></i> {{ actSelectedTime.name }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="time in timeFilterOpts" :class="[time.slug === actSelectedTime.slug ? \'active\': \'\']"><a href="#" @mousedown.prevent="filterActProgressTime(time)">{{ time.name }}</a></li></ul></div></div></div><div class="progress-chart"><div id="activity-progress-chart" class="overview-big-chart"></div></div><div class="progress-data-table"><table class="wp-list-table widefat striped"><thead><tr><th class="text-left type-name">{{ i18n.type }}</th><th class="text-center">{{ i18n.total }}</th><th class="text-center">{{ i18n.open }}</th><th class="text-center">{{ i18n.done }}</th></tr></thead><tbody v-if="!activityProgress.length"><tr><td colspan="5" class="text-center">{{ i18n.noStatFound }}</td></tr></tbody><tbody v-else><tr v-for="type in activityProgress"><td class="text-left type-name"><span :class="[\'picon picon-\' + type.icon]"></span> {{ type.title }}</td><td class="text-center">{{ type.total }}</td><td class="text-center">{{ parseInt(type.total) - parseInt(type.done) }}</td><td class="text-center">{{ type.done }}</td></tr></tbody></table></div></div></div></div><div class="col-2 deal-overview-right"><div class="postbox erp-deal-postbox overview-list-stat"><h3 class="hndle">{{ i18n.mostRecentOpenDeals }}</h3><div class="postbox-inside"><ul v-if="lastOpenDeals.length"><li v-for="deal in lastOpenDeals"><a :href="getDealLink(deal.id)">{{ deal.title }}</a><div class="smaill-faded-text list-stat-footer clearfix"><span class="pull-left">{{{ deal.value | currency companyCurrencySymbol }}}</span> <span class="pull-right">{{ getTimeFromNow(deal.createdAt) }}</span></div></li></ul><ul v-else><li class="text-center">{{ i18n.noDealFound }}</li></ul></div></div><div class="postbox erp-deal-postbox overview-list-stat"><h3 class="hndle">{{ i18n.mostRecentWonDeals }}</h3><div class="postbox-inside"><ul v-if="lastWonDeals.length"><li v-for="deal in lastWonDeals"><a :href="getDealLink(deal.id)">{{ deal.title }}</a><div class="smaill-faded-text list-stat-footer clearfix"><span class="pull-left">{{{ deal.value | currency companyCurrencySymbol }}}</span> <span class="pull-right">{{ getTimeFromNow(deal.createdAt) }}</span></div></li></ul><ul v-else><li class="text-center">{{ i18n.noDealFound }}</li></ul></div></div></div></div></div><div class="erp-deal-modal single-search-input-modal" id="deal-list-modal" tabindex="-1"><div class="erp-deal-modal-dialog modal-lg" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="doingAjax"><span aria-hidden="true" :class="[doingAjax ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ dealListModalTitle }}</h4></div><div class="erp-deal-modal-body" id="deal-list-modal-body"><span v-if="dealsLoading" class="erp-spinner"></span><div v-else class="deal-list-table"><table class="wp-list-table widefat striped"><thead><tr><th>{{ i18n.title }}</th><th class="text-right">{{ i18n.value }}</th><th>{{ i18n.company }}</th><th>{{ i18n.contact }}</th><th class="expt-date">{{ i18n.expCloseDate }}</th><th>{{ i18n.owner }}</th></tr></thead><tbody><tr><td class="list-table-section-title" colspan="6">This Month</td></tr><tr v-for="deal in currentMonthdeals | filterBy filterDealList"><td><a :href="getDealLink(deal.id)" target="_blank">{{ deal.title }}</a></td><td class="text-right">{{{ deal.value | currency deal.currencySymbol 2 }}}</td><td>{{ deal.company ? deal.company.company : \'\' }}</td><td>{{ deal.contact ? (deal.contact.firstName + \' \' + deal.contact.lastName) : \'\' }}</td><td>{{ getDealClosingDate(deal.expectedCloseDate) }}</td><td>{{ deal.owner }}</td></tr><tr v-if="!infoBox[currentDealListType].current.count"><td colspan="6" class="text-center"><em>{{ i18n.noDealFound }}</em></td></tr><tr><td class="list-table-section-title" colspan="6">Last Month</td></tr><tr v-for="deal in previousMonthdeals | filterBy filterDealList"><td><a :href="getDealLink(deal.id)" target="_blank">{{ deal.title }}</a></td><td class="text-right">{{{ deal.value | currency deal.currencySymbol 2 }}}</td><td>{{ deal.company ? deal.company.company : \'\' }}</td><td>{{ deal.contact ? (deal.contact.firstName + \' \' + deal.contact.lastName) : \'\' }}</td><td>{{ getDealClosingDate(deal.expectedCloseDate) }}</td><td>{{ deal.owner }}</td></tr><tr v-if="!infoBox[currentDealListType].previous.count"><td colspan="6" class="text-center"><em>{{ i18n.noDealFound }}</em></td></tr></tbody></table></div></div><div class="erp-deal-modal-footer"><button type="button" class="button" data-dismiss="erp-deal-modal" :disabled="doingAjax">{{ i18n.close }}</button></div></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
        },
    
        data: function data$18() {
            return {
                wpTimezone: erpDealsGlobal.wpTimezone,
                isReady: false,
                pipelines: erpDealsGlobal.pipes,
                dealSummery: {},
                selectedPipeline: {
                    id: 0,
                    title: 'Pipeline'
                },
                showPipelineDropdown: false,
                selectedAgent: {
                    id: 0,
                    name: this.i18n.allOwners
                },
                showAgentDropdown: false,
                timeFilterOpts: [
                    { slug: 'week', name: this.i18n.thisWeek },
                    { slug: 'month', name: this.i18n.thisMonth },
                    { slug: 'year', name: this.i18n.thisYear },
                ],
                selectedTime: {
                    slug: 'month',
                    name: this.i18n.thisMonth
                },
                showTimeDropdown: false,
                selelecteProgressType: 'value',
                companyCurrency: '',
                companyCurrencySymbol: '',
                dealProgress: [],
                showActAgentDropdown: false,
                actSelectedAgent: {
                    id: 0,
                    name: this.i18n.allAgents
                },
                showActTimeDropdown: false,
                actSelectedTime: {
                    slug: 'month',
                    name: this.i18n.thisMonth
                },
                activityProgress: [],
                lastOpenDeals: [],
                lastWonDeals: [],
                dealList: [],
                dealListModalTitle: '',
                currentDealListType: '',
                doingAjax: false
            };
        },
    
        ready: function ready$9() {
            this.selectedPipeline = {
                id: this.pipelines[0].id,
                title: this.pipelines[0].title,
            },
    
            this.getOverviewData();
        },
    
        computed: {
            infoBox: function infoBox() {
                var info = {};
                var self = this;
    
                info = {
                    new: {
                        current: {
                            header: self.i18n.thisMonth,
                            count: self.dealSummery.thisMonth.new.total,
                            price: self.dealSummery.thisMonth.new.value,
                        },
                        previous: {
                            header: self.i18n.lastMonth,
                            count: self.dealSummery.lastMonth.new.total,
                            price: self.dealSummery.lastMonth.new.value,
                        }
                    },
    
                    won: {
                        current: {
                            header: self.i18n.thisMonth,
                            count: self.dealSummery.thisMonth.won.total,
                            price: self.dealSummery.thisMonth.won.value,
                        },
                        previous: {
                            header: self.i18n.lastMonth,
                            count: self.dealSummery.lastMonth.won.total,
                            price: self.dealSummery.lastMonth.won.value,
                        }
                    },
    
                    lost: {
                        current: {
                            header: self.i18n.thisMonth,
                            count: self.dealSummery.thisMonth.lost.total,
                            price: self.dealSummery.thisMonth.lost.value,
                        },
                        previous: {
                            header: self.i18n.lastMonth,
                            count: self.dealSummery.lastMonth.lost.total,
                            price: self.dealSummery.lastMonth.lost.value,
                        }
                    },
                };
    
                return info;
            },
    
            crmOwners: function crmOwners() {
                var owners = [
                    {
                        id: 0,
                        name: this.i18n.allOwners,
                        avatar: 'https://www.gravatar.com/avatar/?d=mm&f=y'
                    }
                ];
    
                return owners.concat(erpDealsGlobal.crmAgents);
            },
    
            crmAgents: function crmAgents$2() {
                var agents = [
                    {
                        id: 0,
                        name: this.i18n.allAgents,
                        avatar: 'https://www.gravatar.com/avatar/?d=mm&f=y'
                    }
                ];
    
                return agents.concat(erpDealsGlobal.crmAgents);
            },
    
            currentMonthdeals: function currentMonthdeals() {
                var self = this;
                var firstDayOfCurrentMonth = moment.tz(self.wpTimezone).format('YYYY-MM-01 00:00:00');
    
                return self.dealList.filter(function (deal) {
                    return moment.tz(deal.createdAt, "YYYY-MM-DD hh:mm:ss", self.wpTimezone).isSameOrAfter(firstDayOfCurrentMonth);
                });
            },
    
            previousMonthdeals: function previousMonthdeals() {
                var self = this;
                var firstDayOfCurrentMonth = moment.tz(self.wpTimezone).format('YYYY-MM-01 00:00:00');
    
                return self.dealList.filter(function (deal) {
                    return moment.tz(deal.createdAt, "YYYY-MM-DD hh:mm:ss", self.wpTimezone).isBefore(firstDayOfCurrentMonth);
                });
            },
    
        },
    
        methods: {
            getOverviewData: function getOverviewData(section) {
                var self = this;
                var filters = {};
    
                var dealProgress = {
                    pipeline_id: self.selectedPipeline.id,
                    agent_id: self.selectedAgent.id,
                    time: self.selectedTime.slug
                };
    
                var activityProgress = {
                    agent_id: self.actSelectedAgent.id,
                    time: self.actSelectedTime.slug
                };
    
                switch(section) {
                    case 'deal_progress':
                        filters = dealProgress;
                        break;
    
                    case 'activity_progress':
                        filters = activityProgress;
                        break;
    
                    default:
                        filters = {
                            deal_progress: dealProgress,
                            activity_progress: activityProgress
                        };
                        break;
                }
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_overview_data',
                        _wpnonce: erpDealsGlobal.nonce,
                        section: section,
                        filters: filters
                    },
                    beforeSend: function beforeSend() {
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        if (!self.isReady) {
                            self.isReady = true;
                        }
    
                        if (response.data.company_currency) {
                            self.companyCurrency = response.data.company_currency;
                        }
    
                        if (response.data.company_currency_symbol) {
                            self.companyCurrencySymbol = response.data.company_currency_symbol;
                        }
    
                        if (response.data.deal_summery) {
                            self.dealSummery = self.camelizedObject(response.data.deal_summery);
                        }
    
                        if (response.data.deals_progress_by_stages) {
                            self.dealProgress = self.camelizedArray(response.data.deals_progress_by_stages);
    
                            if ($('#deal-progress-chart > canvas').length) {
                                $.plot('#deal-progress-chart', []).shutdown();
                                $('#deal-progress-chart').html('').removeAttr('style');
                            }
    
                            setTimeout(function () {
                                self.drawDealProgress();
                            }, 300);
                        }
    
                        if (response.data.activity_progress) {
                            self.activityProgress = self.camelizedArray(response.data.activity_progress);
    
                            if ($('#activity-progress-chart > canvas').length) {
                                $.plot('#activity-progress-chart', []).shutdown();
                                $('#activity-progress-chart').html('').removeAttr('style');
                            }
    
                            setTimeout(function () {
                                self.drawActivityProgress();
                            }, 300);
                        }
    
                        if (response.data.last_open_deals) {
                            self.lastOpenDeals = self.camelizedArray(response.data.last_open_deals);
                        }
    
                        if (response.data.last_won_deals) {
                            self.lastWonDeals = self.camelizedArray(response.data.last_won_deals);
                        }
    
                    }
    
                }).always(function () {
                    NProgress.done();
                });
            },
    
            filterDealProgressPipeline: function filterDealProgressPipeline(pipeline) {
                this.selectedPipeline = {
                    id: pipeline.id, title: pipeline.title
                };
    
                this.showPipelineDropdown = false;
    
                this.getOverviewData('deal_progress');
            },
    
            filterDealProgressAgent: function filterDealProgressAgent(agent) {
                this.selectedAgent = {
                    id: agent.id, name: agent.name
                };
    
                this.showAgentDropdown = false;
    
                this.getOverviewData('deal_progress');
            },
    
            filterDealProgressTime: function filterDealProgressTime(time) {
                this.selectedTime = {
                    slug: time.slug, name: time.name
                };
    
                this.showTimeDropdown = false;
    
                this.getOverviewData('deal_progress');
            },
    
            getStageAverageValue: function getStageAverageValue(stage) {
                var dealCount = parseInt(stage.dealCount);
    
                if (!dealCount) {
                    return '0.00';
                }
    
                var value = parseFloat(stage.totalValue) / dealCount;
    
                return value.toFixed(2);
            },
    
            getStageAverageReachTime: function getStageAverageReachTime(stage) {
                var dealCount = parseInt(stage.dealCount);
    
                if (!dealCount) {
                    return 0;
                }
    
                var value = 0;
    
                if (stage.totalDaysToReach) {
                    parseInt(stage.totalDaysToReach) / parseInt(stage.dealCount);
                }
    
                return value.toFixed(0);
            },
    
            drawDealProgress: function drawDealProgress() {
                var self = this;
                var data = [];
                var ticks = [];
                var i = 0;
                var maxValue = 0;
    
                for (i = (self.dealProgress.length - 1); i >= 0; i--) {
    
                    var value = ('value' === self.selelecteProgressType) ?
                                    self.dealProgress[i].totalValue :
                                    self.dealProgress[i].dealCount;
    
                    if (parseInt(value) > parseInt(maxValue)) {
                        maxValue = value;
                    }
    
                    data.push([value, self.dealProgress[i].title]);
                    ticks.push([self.dealProgress.length - (i+1), self.dealProgress[i].title]);
                }
    
                var maxXValue = ('value' === self.selelecteProgressType) ?
                                    Math.ceil(maxValue) + 1500 :
                                    parseInt(maxValue) + 1;
    
                var plotOptions = {
                    legend: {
                        show: false
                    },
    
                    series: {
                        bars: {
                            show: true,
                            align: 'center',
                            horizontal: true,
    
                            barWidth: 0.55,
                            lineWidth: 0,
                            fillColor: {
                                colors: [{
                                    opacity: 1.0
                                }, {
                                    opacity: 1.0
                                }]
                            }
                        }
                    },
    
                    yaxis: {
                        mode: 'categories',
                        ticks: ticks,
                        tickLength: 2,
                    },
    
                    xaxis: {
                        min: 0,
                        ticks: 8,
                        tickDecimals: 0,
                        max: maxXValue
                    },
    
                    grid: {
                        borderWidth: 0,
                        hoverable: true,
                    }
                };
    
                $.plot('#deal-progress-chart', [data], plotOptions);
    
                // reset first
                $('#deal-progress-chart-tooltip').remove();
                $('#deal-progress-chart').off('plothover');
    
                // bind
                $('<div id="deal-progress-chart-tooltip" class="erp-tooltip right"><div class="erp-tooltip-arrow"></div><div class="erp-tooltip-inner"></div></div>').css({
                    position: 'absolute',
                    display: 'none',
                    opacity: 0.80
                }).appendTo('body');
    
                $('#deal-progress-chart').on('plothover', function (event, pos, item) {
                    if (item) {
                        var val = ('value' === self.selelecteProgressType) ?
                                    self.companyCurrencySymbol + item.datapoint[0] :
                                    item.datapoint[0];
    
                        $('#deal-progress-chart-tooltip')
                            .css({top: item.pageY-12, left: item.pageX})
                            .fadeIn(200)
                            .children('.erp-tooltip-inner').html(val);
                    } else {
                        $('#deal-progress-chart-tooltip').hide();
                    }
                });
            },
    
            filterActProgressAgent: function filterActProgressAgent(agent) {
                this.actSelectedAgent = {
                    id: agent.id, name: agent.name
                };
    
                this.showActAgentDropdown = false;
    
                this.getOverviewData('activity_progress');
            },
    
            filterActProgressTime: function filterActProgressTime(time) {
                this.actSelectedTime = {
                    slug: time.slug, name: time.name
                };
    
                this.showActTimeDropdown = false;
    
                this.getOverviewData('activity_progress');
            },
    
            drawActivityProgress: function drawActivityProgress() {
                var self = this;
                var openData = {
                    label: 'open',
                    data: []
                };
                var doneData = {
                    label: 'done',
                    data: []
                };
                var ticks = [];
                var i = 0;
                var maxValue = 0;
    
                for (i = (self.activityProgress.length - 1); i >= 0; i--) {
    
                    var total = parseInt(self.activityProgress[i].total);
                    var done = parseInt(self.activityProgress[i].done);
                    var open = total - done;
    
                    if (total > parseInt(maxValue)) {
                        maxValue = total;
                    }
    
                    openData.data.push([open, self.activityProgress[i].title]);
                    doneData.data.push([done, self.activityProgress[i].title]);
                    ticks.push([self.activityProgress.length - (i+1), self.activityProgress[i].title]);
                }
    
                var plotOptions = {
    
                    legend: {
                        show: false
                    },
    
                    series: {
                        stack: true,
                        bars: {
                            show: true,
                            align: 'center',
                            horizontal: true,
    
                            barWidth: 0.55,
                            lineWidth: 0,
                            fillColor: {
                                colors: [{
                                    opacity: 1.0
                                }, {
                                    opacity: 1.0
                                }]
                            }
                        }
                    },
    
                    yaxis: {
                        mode: 'categories',
                        ticks: ticks,
                        tickLength: 2,
                    },
    
                    xaxis: {
                        min: 0,
                        ticks: 8,
                        tickDecimals: 0,
                        max: maxValue + 1
                    },
    
                    grid: {
                        borderWidth: 0,
                        hoverable: true,
                    }
                };
    
                $.plot('#activity-progress-chart', [openData, doneData], plotOptions);
    
                // reset first
                $('#activity-progress-chart-tooltip').remove();
                $('#activity-progress-chart').off('plothover');
    
                // bind
                $('<div id="activity-progress-chart-tooltip" class="erp-tooltip right"><div class="erp-tooltip-arrow"></div><div class="erp-tooltip-inner"></div></div>').css({
                    position: 'absolute',
                    display: 'none',
                    opacity: 0.80
                }).appendTo('body');
    
                $('#activity-progress-chart').on('plothover', function (event, pos, item) {
                    if (item) {
                        var html = '';
                        var val = item.datapoint[0];
    
                        if ('open' === item.series.label) {
                            html = self.i18n.open + ': ' + val;
                        } else {
                            html = self.i18n.done + ': ' + doneData.data[item.dataIndex][0];
                        }
    
    
                        $('#deal-progress-chart-tooltip')
                            .css({top: item.pageY-12, left: item.pageX})
                            .fadeIn(200)
                            .children('.erp-tooltip-inner').html(html);
                    } else {
                        $('#deal-progress-chart-tooltip').hide();
                    }
                });
            },
    
            getDealLink: function getDealLink(id) {
                return erpDealsGlobal.singlePageURL.replace('DEALID', id);
            },
    
            getTimeFromNow: function getTimeFromNow(time) {
                return moment.tz(time, 'YYYY-MM-DD HH:mm:ss', this.timezone).fromNow();
            },
    
            // source http://stackoverflow.com/a/26131085/1646296
            getMonthDateRange: function getMonthDateRange(year, month) {
                // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
                // array is 'year', 'month', 'day', etc
                var startDate = moment([year, month]).add(-1,"month");
    
                // Clone the value before .endOf()
                var endDate = moment(startDate).endOf('month');
    
                // make sure to call toDate() for plain JavaScript date type
                return { start: startDate, end: endDate };
            },
    
            openDealListModal: function openDealListModal(type) {
                var self = this;
    
                switch(type) {
                    case 'won':
                        self.dealListModalTitle = self.i18n[type + 'Deals'];
                        self.currentDealListType = 'won';
                        break;
    
                    case 'lost':
                        self.dealListModalTitle = self.i18n[type + 'Deals'];
                        self.currentDealListType = 'lost';
                        break;
    
                    case 'new':
                    /* falls through */ // cancels jshint warning "Expected a 'break' statement before 'default'."
                    default:
                        self.dealListModalTitle = self.i18n[type + 'Deals'];
                        self.currentDealListType = 'new';
                        break;
                }
    
    
                $('#deal-list-modal').erpDealModal();
    
                if (!self.dealList.length) {
                    self.getDeals();
                }
            },
    
            getDeals: function getDeals() {
                var self = this;
                var now = moment.tz(self.wpTimezone);
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_deals',
                        _wpnonce: erpDealsGlobal.nonce,
                        args: {
                            to: now.format('YYYY-MM-DD HH:mm:ss'),
                            from: now.subtract(1, 'months').date(1).format('YYYY-MM-DD 00:00:00'),
                            limit: -1,
                            with_names: true
                        }
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.dealList = self.camelizedArray(response.data.deals);
                    }
    
                }).always(function () {
                   self.doingAjax = false;
                });
            },
    
            filterDealList: function filterDealList(deal) {
                var filter = false;
    
                switch(this.currentDealListType) {
                    case 'won':
                        filter = deal.wonAt;
                        break;
    
                    case 'lost':
                        filter = deal.lostAt;
                        break;
    
                    default:
                        filter = true;
                        break;
                }
    
                return filter;
            },
    
            getDealClosingDate: function getDealClosingDate(date) {
                if (!date) {
                    return null;
                }
    
                return moment.tz(date, "YYYY-MM-DD hh:mm:ss", this.wpTimezone).format('MMM DD, YYYY');
            }
        },
    
        watch: {
            selelecteProgressType: function selelecteProgressType() {
                if ($('#deal-progress-chart > canvas').length) {
                    $.plot('#deal-progress-chart', []).shutdown();
                    $('#deal-progress-chart').html('').removeAttr('style');
                }
    
                this.drawDealProgress();
            }
        }
    });
    
    Vue.component('pipeline-view', {
        /*global CountUp, dealUtils*/
    
        template: '<div class="erp-deal-pipeline-view-container"><div class="clearfix"><h1 class="pull-left">{{ i18n.deals }} <a href="#add-new-deal" class="add-new-h2" @click.prevent="openNewDealModal">{{ i18n.addNew }}</a></h1><div class="pull-right deal-filters"><div v-if="pipeList.length > 1" :class="[\'deal-filter-dropdown\', showPipeFilterDropdown ? \'open\' : \'\']"><button class="page-title-action filter-pipe" @click="showPipeFilterDropdown = !showPipeFilterDropdown" @focusout="showPipeFilterDropdown = false"><i class="dashicons dashicons-chart-bar"></i> {{ pipe.title }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="pipe in pipeList"><a href="#set-pipeline" @mousedown="setPipe(pipe.id)" @click.prevent="">{{ pipe.title }}</a></li></ul></div><div :class="[\'deal-filter-dropdown\', showStatusFilterDropdown ? \'open\' : \'\']"><button class="page-title-action filter-status" @click="showStatusFilterDropdown = !showStatusFilterDropdown" @focusout="showStatusFilterDropdown = false"><i class="dashicons dashicons-filter"></i> {{ statusFilterTitle }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="option in statusFilters"><a href="#open-deals" @mousedown="setStatusFilter(option)" @click.prevent="">{{ i18n[option] }}</a></li></ul></div><div :class="[\'deal-filter-dropdown deal-filter-owner\', showOwnerFilterDropdown ? \'open\' : \'\']"><button class="page-title-action filter-owner" @click="showOwnerFilterDropdown = !showOwnerFilterDropdown" @focusout="showOwnerFilterDropdown = false"><i class="dashicons dashicons-admin-users"></i> {{ ownerFilterTitle }} <i class="dashicons dashicons-arrow-down"></i></button><ul class="erp-dropdown-menu"><li v-for="owner in ownerDropdownOptions"><a href="#filter-owner" @mousedown.prevent="setOwnerFilter(owner)" @click.prevent=""><img class="avatar" :src="owner.avatar" :alt="owner.name" :title="owner.name"> {{ owner.name }}</a></li></ul></div></div></div><div class="deals-container"><div id="erp-deals-pipeline-view" :style="{width: dealsContainerWidth}"><div class="clearfix" id="pipeline-stage-headers"><div v-for="stage in pipeline" class="pipeline-stage-column" :style="[columnWidth]"><div class="faux-block"><div>&nbsp;</div></div><table><tr><td><h4 :style="[stageTitleWidth]">{{ stage.title }}</h4><h6><span class="stage-value-counter"></span> - <small>{{ stageDealCounts(stage.deals.length) }}</small></h6></td></tr></table></div></div><div class="clearfix" id="pipeline-stage-deals"><div v-for="stage in pipeline" class="pipeline-stage-column" :style="[columnHeight, columnWidth]" :data-stage-index="$index" v-erp-sortable connect-with=".pipeline-stage-column" receive="onSortableRecieve" over="onSortableOver" stop="onSortableStop"><div v-for="deal in stage.deals | orderBy sortedStageDeals" :class="[\'deal-in-stage\', statusClass(deal)]" :data-deal-id="deal.id" :id="\'deal-id-\' + deal.id" @click="onDealInStageClicked"><div class="deal-info"><a :href="getDealURL(deal.id)"><h5><img :src="deal.owner.img" :alt="deal.owner.name" v-erp-tooltip :tooltip-title="deal.owner.name"> {{ deal.title }}</h5><p v-if="deal.company" class="deal-info-footer">{{ getDealValue(deal.value) }} | {{ deal.company }}</p><p v-else class="deal-info-footer">{{ getDealValue(deal.value) }}</p></a></div><deal-menu :i18n="i18n" :deal.sync="deal"></deal-menu><activities-popover v-if="!(deal.deletedAt || deal.wonAt || deal.lostAt)" :i18n="i18n" :deal="deal"></activities-popover></div></div></div></div></div></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            pipeline: {
                type: Array,
                default: []
            },
    
            filters: {
                type: Object,
                default: {},
                twoWay: true
            },
    
            currencySymbol: {
                type: String,
                default: '$'
            },
    
            crmAgents: {
                type: Array,
                default: []
            }
        },
    
        data: function data$19() {
            return {
                dealsContainerWidth: '100%',
                stageTitleWidth: {},
                pipe: {},
                showPipeFilterDropdown: false,
                showStatusFilterDropdown: false,
                statusFilterTitle: '',
                statusFilters: ['filterOpen', 'filterWon', 'filterLost', 'filterDeleted'],
                status: '',
                showOwnerFilterDropdown: false,
            };
        },
    
        ready: function ready$10() {
            // pipeline
            var currentPipe = this.readCookie('current-pipeline-id');
    
            if (currentPipe) {
                this.setPipe(currentPipe);
            } else {
                this.setPipe(erpDealsGlobal.pipes[0].id);
            }
    
            // filter - status
            var filterStatus = this.readCookie('current-filter-status');
    
            if (!filterStatus) {
                filterStatus = 'filterOpen';
            }
    
            this.createCookie('current-filter-status', filterStatus, 30);
    
            this.filters.status = filterStatus.replace('filter', '').toLowerCase();
    
            this.statusFilterTitle = this.i18n[filterStatus];
    
            // owner
            var filterOwner = this.readCookie('current-filter-owner');
    
            if (!filterOwner) {
                filterOwner = 0;
            }
    
            this.createCookie('current-filter-owner', filterOwner, 30);
    
            this.filters.owner = filterOwner;
        },
    
        computed: {
            pipeList: function pipeList() {
                return erpDealsGlobal.pipes;
            },
    
            columnHeight: function columnHeight() {
                var deals = this.pipeline.map(function (pipe) {
                    return pipe.deals.length;
                });
    
                // 71 is the fixed height for .deal-in-stage
                var height = (Math.max.apply(Math, deals) * 71);
    
                return {
                    height: height + 'px'
                };
            },
    
            columnWidth: function columnWidth() {
                // minimum column width
                var minWidth = 268.5,
                    containerWidth = $('#erp-deals').width();
    
                var width = 0;
    
                if ((this.pipeline.length * minWidth) <= containerWidth ) {
                    width = (100 / this.pipeline.length) + '%';
                    this.dealsContainerWidth = '100%';
    
                    this.stageTitleWidth = {
                        width: (($('#pipeline-stage-headers').width() / this.pipeline.length) - 64) + 'px'
                    };
    
                } else {
                    width = minWidth + 'px';
                    this.dealsContainerWidth = (minWidth * this.pipeline.length) + 'px';
                    this.stageTitleWidth = {
                        width: 'calc(' + width + ' - 64px)'
                    };
                }
    
                return {
                    width: width
                };
            },
    
            stageValue: function stageValue() {
                var values = this.pipeline.map(function (stage) {
    
                    var totalVal = 0;
    
                    stage.deals.forEach(function (deal) {
                        var dealVal = parseFloat(deal.value);
    
                        if (isNaN(dealVal)) {
                            dealVal = 0.00;
                        }
    
                        totalVal += dealVal;
                    });
    
                    return totalVal;
                });
    
                return values;
            },
    
            ownerDropdownOptions: function ownerDropdownOptions() {
                var agents = [
                    {
                        id: 0,
                        name: this.i18n.allOwners,
                        avatar: 'https://www.gravatar.com/avatar/?d=mm&f=y'
                    }
                ];
    
                return agents.concat(this.crmAgents);
            },
    
            ownerFilterTitle: function ownerFilterTitle() {
                var self = this;
    
                var owner = self.ownerDropdownOptions.filter(function (owner) {
                    return parseInt(owner.id) === parseInt(self.filters.owner);
                });
    
                if (!owner.length) {
                    return self.i18n.allOwners;
                }
    
                owner = owner[0];
    
                return owner.name;
            }
        },
    
        methods: {
            getDealValue: function getDealValue(value) {
                if (!value) {
                    return this.$options.filters.currency(0, this.currency, 0);
                } else if (value.match(/\.00$/)) {
                    return this.$options.filters.currency(value, this.currency, 0);
                } else {
                    return this.$options.filters.currency(value, this.currency, 2);
                }
            },
    
            openNewDealModal: function openNewDealModal() {
                this.$dispatch('open-new-deal-modal');
            },
    
            stageDealCounts: function stageDealCounts(count) {
                var label = '';
    
                if (count > 1) {
                    label = count + ' ' + this.i18n.deals.toLowerCase();
                } else {
                    label = count + ' ' + this.i18n.deal.toLowerCase();
                }
    
                return label;
            },
    
            statusClass: function statusClass$3(deal) {
                // const statuses = ['warning', 'overdue', 'today', 'future', 'rotten', 'status-won'];
    
                var className = 'warning';
                var now = moment();
                var today = now.format('YYYY-MM-DD');
                var tonight = now.format('YYYY-MM-DD') + ' 23:59:59';
    
                if ( (moment(deal.actStart).isSame(today) && !parseInt(deal.isStartTimeSet)) || moment(deal.actStart).isBetween(now, tonight) ) {
                    className = 'today';
                } else if ( moment(deal.actStart).isBefore(now) ) {
                    className = 'overdue';
                } else if ( moment(deal.actStart).isAfter(now) ) {
                    className = 'future';
                }
    
                className += ' ' + this.filters.status;
    
                return className;
            },
    
            // trigger after drop a deal into a stage
            onSortableRecieve: function onSortableRecieve(e, ui) {
                var fromStageIndex  = parseInt(ui.sender.get(0).dataset.stageIndex);
                var toStageIndex    = parseInt(e.target.dataset.stageIndex);
                var dealId          = parseInt(ui.item.get(0).dataset.dealId);
    
                var deal = this.pipeline[fromStageIndex].deals.filter(function (deal) {
                    return (deal.id === dealId) || false;
                });
    
                deal = deal[0];
    
                this.pipeline[fromStageIndex].deals.$remove(deal);
                this.pipeline[toStageIndex].deals.$set(this.pipeline[toStageIndex].deals.length, deal);
    
                // update deal position in new stage
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: deal.id,
                            stage_id: this.pipeline[toStageIndex].id
                        }
                    },
    
                }).done(function (response) {
                    if (!response.success && response.data.msg) {
                        swal({
                            title: '',
                            text: response.data.msg,
                            type: 'error'
                        });
                    }
                });
            },
    
            onSortableOver: function onSortableOver(e) {
                $('.sortable-hovering').removeClass('sortable-hovering');
                $(e.target).addClass('sortable-hovering');
            },
    
            onSortableStop: function onSortableStop() {
                $('.sortable-hovering').removeClass('sortable-hovering');
            },
    
            // for some reason popover still open even we click .deal-in-stage outside the button area.
            // this is a fix for that.
            onDealInStageClicked: function onDealInStageClicked(e) {
                if(!$(e.target).parents('.deal-activities').length) {
                    $('.view-activities').blur();
                }
            },
    
            sortedStageDeals: function sortedStageDeals(a, b) {
                if (a.actStart && !b.actStart) {
                    return -1;
                }
    
                if (!a.actStart && b.actStart) {
                    return 1;
                }
    
                return new Date(a.actStart) - new Date(b.actStart);
            },
    
            getDealURL: function getDealURL(dealId) {
                return erpDealsGlobal.singlePageURL.replace('DEALID', dealId);
            },
    
            setPipe: function setPipe(pipeId) {
                this.createCookie('current-pipeline-id', pipeId, 30);
    
                var selectedPipe = this.pipeList.filter(function (pipe) {
                    return parseInt(pipe.id) === parseInt(pipeId);
                });
    
                if (selectedPipe.length) {
                    this.pipe = selectedPipe[0];
                } else {
                    // Suppose we select pipeline from dropdown, then deleted that pipeline.
                    // In that case choose the first pipeline and remove the cookie
                    pipeId = erpDealsGlobal.pipes[0].id;
                    this.createCookie('current-pipeline-id', '', -1);
                }
    
                this.$dispatch('switch-pipeline', pipeId);
            },
    
            setStatusFilter: function setStatusFilter(filter) {
                this.createCookie('current-filter-status', filter, 30);
    
                this.filters.status = filter.replace('filter', '').toLowerCase();
    
                this.statusFilterTitle = this.i18n[filter];
    
                this.pipeline = [];
    
                this.$dispatch('switch-filter-status');
            },
    
            setOwnerFilter: function setOwnerFilter(owner) {
                var ownerId = parseInt(owner.id);
    
                this.createCookie('current-filter-owner', ownerId, 30);
    
                this.filters.owner = ownerId;
    
                this.$dispatch('switch-filter-owner');
    
                this.showOwnerFilterDropdown = false;
            },
    
            removeDealFromList: function removeDealFromList(deal) {
                var stage = this.pipeline.filter(function (pipeStage) {
                    return parseInt(pipeStage.id) === parseInt(deal.stageId);
                });
    
                stage = stage[0];
    
                var stageDeal = stage.deals.filter(function (_deal) {
                    return parseInt(_deal.id) === parseInt(deal.id);
                });
    
                stageDeal = stageDeal[0];
    
                stage.deals.$remove(stageDeal);
            }
        },
    
        watch: {
            stageValue: function stageValue$1(newVal, oldVal) {
                var currencySymbol = this.currencySymbol;
    
                $('.stage-value-counter').each(function (i, el) {
                    var options = {
                        useEasing: true,
                        useGrouping: true,
                        separator: ',',
                        decimal: '.',
                        prefix: currencySymbol,
                        suffix: ''
                    };
    
                    var counter = new CountUp(el, (oldVal[i] || 0), newVal[i], 2, 0.8, options);
                    counter.start();
                });
            }
        },
    
        events: {
            'deal-won': function deal_won(deal) {
                this.removeDealFromList(deal);
            },
    
            'deal-lost': function deal_lost(deal) {
                this.removeDealFromList(deal);
            },
    
            'remove-deal': function remove_deal(deal) {
                var stage = this.pipeline.filter(function (pipeStage) {
                    return parseInt(pipeStage.id) === parseInt(deal.stageId);
                });
    
                stage = stage[0];
    
                stage.deals.$remove(deal);
            }
        }
    });
    
    Vue.component('profile-info-box', {
        template: '<div class="postbox erp-deal-postbox profile-info-box"><button v-if="showRemoveBtn" type="button" class="button button-link button-small profile-action remove-profile" v-erp-tooltip :tooltip-title="i18n.remove" @click="removeProfile"><i class="dashicons dashicons-no-alt"></i></button> <button type="button" class="button button-link button-small profile-action change-profile" v-erp-tooltip :tooltip-title="switchBtnLabel" @click="switchProfile"><i class="dashicons dashicons-edit"></i></button><h3 class="hndle">{{ title }}</h3><div v-if="parseInt(people.id)" class="postbox-inside"><div class="profile-summery deal-row"><div class="col-2 avatar padding-right-0">{{{ people.avatar.img }}}</div><div class="col-4 summery"><h3 v-if="\'contact\' === type"><a :href="people.detailsUrl" target="_blank">{{ people.firstName }} {{ people.lastName }}</a></h3><h3 v-else><a :href="people.detailsUrl" target="_blank">{{ people.company }}</a></h3><div><p v-if="people.email"><i class="dashicons dashicons-email-alt"></i> <a :href="\'mailto:\' + people.email">{{ people.email }}</a></p><p v-if="people.phone"><i class="dashicons dashicons-phone"></i> <a :href="\'tel:\' + people.phone">{{ people.phone }}</a></p><p v-if="people.mobile"><i class="dashicons dashicons-smartphone"></i> <a :href="\'tel:\' + people.mobile">{{ people.mobile }}</a></p></div></div></div><table class="table-profile"><tr><td class="label">{{ i18n.street1 }}</td><td class="sep">:</td><td class="value">{{{ people.street1 }}}</td></tr><tr><td class="label">{{ i18n.street2 }}</td><td class="sep">:</td><td class="value">{{{ people.street2 }}}</td></tr><tr><td class="label">{{ i18n.city }}</td><td class="sep">:</td><td class="value">{{{ people.city }}}</td></tr><tr><td class="label">{{ i18n.state }}</td><td class="sep">:</td><td class="value">{{{ people.stateName }}}</td></tr><tr><td class="label">{{ i18n.country }}</td><td class="sep">:</td><td class="value">{{{ people.countryName }}}</td></tr><tr><td class="label">{{ i18n.postalCode }}</td><td class="sep">:</td><td class="value">{{{ people.postalCode }}}</td></tr></table></div><div v-else class="text-center"><p v-if="\'company\' === type">{{ i18n.noCompLinkedToDealMsg }}</p><p v-else>{{ i18n.noContLinkedToDealMsg }}</p></div></div>',
    
        props: {
            i18n: {
                type: Object,
                required: true
            },
    
            people: {
                type: Object,
                required: true,
                default: {},
                twoWay: true
            },
    
            type: {
                type: String,
                required: true
            },
    
            title: {
                type: String,
                required: true
            },
    
            showRemoveBtn: {
                type: Boolean,
                default: false
            }
        },
    
        computed: {
            switchBtnLabel: function switchBtnLabel() {
                return this.i18n[ 'switch' + this.type ];
            }
        },
    
        methods: {
            switchProfile: function switchProfile() {
                this.$dispatch('switch-people', this.type);
            },
    
            removeProfile: function removeProfile() {
                var self = this;
    
                swal({
                    title: '',
                    text: ('company' === self.type) ? self.i18n.removeDealCompWarnMsg : self.i18n.removeDealContWarnMsg,
                    type: 'warning',
    
                }, function (isConfirm) {
                    if (isConfirm) {
                        self.$dispatch('remove-people', self.type);
                    }
                });
            }
        }
    });
    
    Vue.component('single-deal', {
        /*global dealUtils*/
    
        template: '<div v-if="isReady" :class="[\'erp-deal-pipeline-view-container\', doingAjax ? \'disabled\' : \'\']" id="erp-deal-single"><div class="clearfix"><div class="pull-left"><div class="deal-title margin-bottom-15"><h1 class="editable-content" @click="openEditor = \'title\'">{{ deal.title }}</h1><span v-if="deal.wonAt" class="erp-badge erp-badge-success">{{ i18n.won }}</span> <span v-if="deal.lostAt" class="erp-badge erp-badge-danger">{{ i18n.lost }}</span> <span v-if="deal.deletedAt" class="erp-badge">{{ i18n.trashed }}</span><div v-if="\'title\' === openEditor" class="erp-popover bottom arrow-left"><div class="erp-popover-arrow"></div><form @submit.prevent="setDealTitle"><div class="erp-popover-content"><input type="text" class="input-large" v-model="tmpDealTitle" autofocus required></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="openEditor = \'\'">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></form></div></div><ul class="list-inline deal-summery-top"><li class="deal-value"><span class="editable-content" @click="openEditor = \'value\'">{{ deal.value | currency deal.currencySymbol 2 }}</span><div v-if="\'value\' === openEditor" class="erp-popover bottom arrow-left"><div class="erp-popover-arrow"></div><form @submit.prevent="setDealValue"><div class="erp-popover-content"><input type="text" class="erp-deal-input" v-model="tmpDealValue" autofocus pattern="[0-9]+([\\.][0-9]+)?" :title="i18n.errorInvalidValueFormat"></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="openEditor = \'\'">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></form></div></li><li v-if="deal.contact.firstName || deal.contact.lastName" class="deal-contact"><i class="dashicons dashicons-admin-users"></i> <a :href="deal.contact.detailsUrl" class="link-black">{{ deal.contact.firstName }} {{ deal.contact.lastName }}</a></li><li v-if="deal.company.id" class="deal-company"><i class="dashicons dashicons-building"></i> <a :href="deal.company.detailsUrl" class="link-black">{{ deal.company.company }}</a></li></ul></div><div class="pull-right"><div class="deal-owner pull-left"><img class="pull-left" :src="owner.avatar" :title="owner.name" :alt="owner.name"><h4 class="pull-left"><a :href="owner.link" class="link-black">{{ owner.name }}</a> <small>{{ i18n.owner.toLowerCase() }}</small></h4><button v-if="canPerformActions" type="button" class="button pull-left button-link" @click="openEditor = \'owner\'"><i class="fa fa-angle-down"></i></button><div v-if="\'owner\' === openEditor" class="erp-popover bottom arrow-right"><div class="erp-popover-arrow"></div><form @submit.prevent="setOwner"><div class="erp-popover-content"><label>{{ i18n.transferOwnerShip }}</label><multiselect :options="crmAgents" :selected="selectedOwner" :multiple="false" :searchable="false" :close-on-select="true" :show-labels="false" :allow-empty="false" :placeholder="i18n.selectOwner" @update="onOwnerSelect" label="name" key="id"></multiselect></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="resetOwnerDropdown">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></form></div></div><div v-if="canPerformActions" class="pull-right deal-status-buttons"><button v-if="!deal.wonAt && !deal.lostAt && !deal.deletedAt" type="button" class="button button-success" @click="wonDeal">{{ i18n.won }}</button> <button v-if="!deal.wonAt && !deal.lostAt && !deal.deletedAt" type="button" class="button button-danger" @click="lostDeal">{{ i18n.lost }}</button> <button v-if="(deal.wonAt || deal.lostAt) && !deal.deletedAt" type="button" class="button button-primary" @click="reopenDeal">{{ i18n.reopen }}</button> <button v-if="!deal.deletedAt" type="button" class="button" @click="deleteDeal(\'trash\')">{{ i18n.trash }}</button> <button v-if="deal.deletedAt" type="button" class="button" @click="dealOperations(\'restore\')">{{ i18n.restore }}</button> <button v-if="deal.deletedAt" type="button" class="button button-danger" @click="deleteDeal(\'delete\')">{{ i18n.delete }}</button></div></div></div><div class="margin-bottom-20"><div class="step-progressbar margin-bottom-8"><ul><li v-for="stage in pipelineStages" :class="[(parseInt(stage.id) === parseInt(deal.stageId)) ? \'active\' : \'\']" v-erp-tooltip :tooltip-title="stageHistoryTooltip(stage.title, stage.history)" :html="true" @click="changeDealStage(stage.id)">{{ stageHistory(stage.history) }} {{ i18n.days }}</li></ul></div><div class="deal-row"><div class="col-3"><div class="deal-change-pipeline"><span class="editable-content" @click="openEditor = \'change-pipeline\'">{{ deal.pipelineTitle }} <i class="dashicons dashicons-arrow-right"></i> {{ deal.stageTitle }}</span><div v-if="\'change-pipeline\' === openEditor" class="erp-popover bottom arrow-left"><div class="erp-popover-arrow"></div><form @submit.prevent="changeDealPipeline"><div class="erp-popover-content"><label>{{ i18n.pipeline }}</label><multiselect :options="pipelines" :selected="selectedPipeline" :multiple="false" :searchable="false" :close-on-select="true" :show-labels="false" :allow-empty="false" :placeholder="i18n.selectPipeline" @update="onPipelineSelect" label="title" key="id"></multiselect><label v-if="tmpPipelineId" class="margin-top-15">{{ i18n.pipelineStage }}</label><div v-if="tmpPipelineId" class="step-progressbar"><ul><li v-for="stage in tmpPipelineStages" :class="[(parseInt(stage.id) === parseInt(tmpPipelineStageId)) ? \'active\' : \'\']" v-erp-tooltip :tooltip-title="stage.title" @click="setTmpPipelineStage(stage.id)"></li></ul></div><strong v-if="tmpPipelineId && !tmpPipelineStages.length">{{ i18n.errorNoPipelineStage }}</strong></div><div class="erp-popover-footer text-right"><button type="button" class="button" @click="openEditor = \'\'">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></form></div></div></div><div class="col-3"><div class="deal-expected-close-date pull-right"><span class="dashicons dashicons-calendar-alt"></span> <a v-if="!deal.expectedCloseDate" href="#set-expected-close-date" @click.prevent="openEditor = \'expected-close-date\'">{{ i18n.setExpectedCloseDate }} </a><span v-else class="editable-content" @click.prevent="openEditor = \'expected-close-date\'" v-erp-tooltip :tooltip-title="expCloseDateTitle" :html="true">{{{ currentExpectedCloseDate }}}</span><div v-if="\'expected-close-date\' === openEditor" class="erp-popover bottom arrow-right"><div class="erp-popover-arrow"></div><form @submit.prevent="setExpectedCloseDate()"><div class="erp-popover-content"><input type="text" class="erp-deal-input" v-erp-datepicker v-model="tmpExpectedCloseDate" autofocus></div><div class="erp-popover-footer clearfix"><button type="button" class="button" @click="setExpectedCloseDate(true)"><i class="dashicons dashicons-trash"></i></button><div class="pull-right"><button type="button" class="button" @click="openEditor = \'\'">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary">{{ i18n.save }}</button></div></div></form></div></div></div></div></div><div v-if="deal.id" class="deal-row"><div class="col-2"><profile-info-box :i18n="i18n" :people.sync="deal.company" type="company" :title="i18n.company" :show-remove-btn="showPeopleRemoveBtn"></profile-info-box><profile-info-box :i18n="i18n" :people.sync="deal.contact" type="contact" :title="i18n.contact" :show-remove-btn="showPeopleRemoveBtn"></profile-info-box><deal-participants :i18n="i18n" :participants.sync="deal.participants"></deal-participants><deal-agents :i18n="i18n" :agents.sync="deal.agents" :crm-agents="crmAgents" :deal-owner-id.sync="deal.ownerId"></deal-agents><single-deal-overview :i18n="i18n" :deal.sync="deal"></single-deal-overview></div><div class="col-4"><sticky-notes :i18n="i18n" :notes.sync="stickyNotes" :crm-agents="crmAgents"></sticky-notes><deal-feed-editors v-if="!deal.deletedAt && !deal.wonAt && !deal.lostAt" :i18n="i18n" :feeds="feeds" :deal="deal" :users="users"></deal-feed-editors><open-activities :i18n="i18n" :activities.sync="deal.activities" :users="users"></open-activities><competitors :i18n="i18n" :deal-id="deal.id" :competitors.sync="deal.competitors"></competitors><timeline :i18n="i18n" :deal.sync="deal" :agents="crmAgents"></timeline></div></div><div class="erp-deal-modal fade single-search-input-modal" id="switch-profile-modal" tabindex="-1"><div class="erp-deal-modal-dialog" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="switchPeople.isSearching"><span aria-hidden="true" :class="[switchPeople.isSearching ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ switchPeople.modalTitle }}</h4></div><div class="erp-deal-modal-body" id="switch-profile-modal-body"><div :class="[switchPeople.errorClass]"><multiselect :options="switchPeople.options" :selected="switchPeople.selected" :local-search="false" :loading="switchPeople.isSearching && !switchPeople.isUpdating" :searchable="true" :show-labels="false" :disabled="switchPeople.isUpdating" @search-change="searchPeople" @update="onPeopleSelect" @open="switchPeople.errorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg"><span slot="noResult">{{ i18n.noContactFound }}</span></multiselect></div></div><div class="erp-deal-modal-footer"><button type="button" class="button" data-dismiss="erp-deal-modal" :disabled="switchPeople.isSearching || doingAjax">{{ i18n.cancel }}</button> <button type="button" class="button button-primary" @click="updatePeople" :disabled="switchPeople.isSearching || doingAjax">{{ i18n.save }}</button></div></div></div></div><div class="erp-deal-modal fade single-search-input-modal" id="add-participants-modal" tabindex="-1"><div class="erp-deal-modal-dialog" role="document"><div class="erp-deal-modal-content"><div class="erp-deal-modal-header"><button type="button" class="erp-close" data-dismiss="erp-deal-modal" aria-label="Close" :disabled="addParticipants.isSearching"><span aria-hidden="true" :class="[addParticipants.isSearching ? \'disabled\': \'\']">×</span></button><h4 class="erp-deal-modal-title">{{ addParticipants.modalTitle }}</h4></div><form @submit.prevent="updateParticpants"><div class="erp-deal-modal-body" id="add-participants-modal-body"><div :class="[addParticipants.errorClass]"><multiselect :options="addParticipants.options" :selected="addParticipants.selected" :local-search="false" :loading="addParticipants.isSearching && !addParticipants.isUpdating" :searchable="true" :show-labels="false" :disabled="addParticipants.isUpdating" @search-change="searchParticipants" @update="onParticipantsSelect" @open="addParticipants.errorClass = \'\'" label="name" key="id" :placeholder="i18n.searchMinCharMsg" option-partial="addParticipantsOptions"><span slot="noResult">{{ i18n.noContactFound }}</span></multiselect></div></div><div class="erp-deal-modal-footer"><button type="button" class="button" data-dismiss="erp-deal-modal" :disabled="addParticipants.isSearching">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary" :disabled="addParticipants.isSearching || !addParticipants.selected.id">{{ i18n.add }}</button></div></form></div></div></div><activity-modal v-if="\'single-deal\' !== view" :i18n="i18n" :users="users"></activity-modal></div>',
    
        mixins: [dealUtils],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            dealId: {
                type: Number,
                required: true
            }
        },
    
        data: function data$20() {
            return {
                isReady: false,
                deal: {
                    contact: {},
                    company: {},
                    notes: [],
                    activities: [],
                    competitors: [],
                    emails: []
                },
                crmAgents: [],
                currentUserId: 0,
                pipelines: [],
                feeds: [],
                selectedOwner: {},
                selectedPipeline: {},
                openEditor: '',
                tmpDealTitle: '',
                tmpDealValue: '',
                tmpPipelineId: 0,
                tmpPipelineStageId: 0,
                tmpExpectedCloseDate: null,
                ajaxHandler: {
                    abort: function abort() {}
                },
                switchPeople: {
                    type: null,
                    modalTitle: '',
                    errorClass: '',
                    options: [],
                    selected: {},
                    isSearching: false,
                    isUpdating: false
                },
                addParticipants: {
                    modalTitle: '',
                    errorClass: '',
                    options: [],
                    selected: {},
                    isSearching: false,
                    isUpdating: false
                },
                doingAjax: false,
            };
        },
    
        ready: function ready$11() {
            var self = this;
    
            this.getDealDetails();
    
            // modal with single search inputs, focus after shown
            $('.single-search-input-modal').on('shown.erp.modal', function () {
                $(self.$el).find('.single-search-input-modal.in input').focus();
            });
    
            // wp admin notice
            $( 'div.updated, div.error, div.notice' ).not( '.inline, .below-h2' ).prependTo( $( '.wrap' ).first() );
        },
    
        computed: {
            owner: function owner() {
                var self = this;
    
                var owner = this.crmAgents.filter(function (item) {
                    return parseInt(item.id) === parseInt(self.deal.ownerId);
                });
    
                if (owner.length) {
                    return owner[0];
                } else {
                    return {};
                }
            },
    
            pipelineStages: function pipelineStages() {
                var self = this;
    
                var pipeline = this.pipelines.filter(function (pipe) {
                    return parseInt(pipe.id) === parseInt(self.deal.pipelineId);
                }).pop();
    
                if (!pipeline) {
                    return [];
                }
    
                // calculate stage history
                pipeline.stages.map(function (stage) {
                    var milliseconds = 0;
    
                    self.deal.stageHistories.filter(function (history) {
                        if (parseInt(history.stageId) === parseInt(stage.id)) {
                            if (!history.out) {
                                milliseconds += moment().diff(moment(history.in));
                            } else {
                                milliseconds += moment(history.out).diff(moment(history.in));
                            }
                        }
                    });
    
                    return stage.history = milliseconds;
                });
    
    
                return pipeline ? pipeline.stages : [];
            },
    
            tmpPipelineStages: function tmpPipelineStages() {
                var self = this;
    
                var pipeline =  this.pipelines.filter(function (pipe) {
                    return parseInt(pipe.id) === parseInt(self.tmpPipelineId);
                }).pop();
    
                return pipeline ? pipeline.stages : [];
            },
    
            currentExpectedCloseDate: function currentExpectedCloseDate() {
                var date = moment(this.deal.expectedCloseDate, 'YYYY-MM-DD HH:mm:ss');
                var now = moment();
    
                if (date.isBefore(now)) {
                    return '<span class="text-danger">' + date.format('MMMM DD, YYYY') + '</span>';
                } else {
                    return date.format('MMMM DD, YYYY');
                }
            },
    
            // expected close date tooltip title
            expCloseDateTitle: function expCloseDateTitle() {
                var title = "<span>" + (this.i18n.expectedCloseDate) + "</span>";
                var date = moment(this.deal.expectedCloseDate, 'YYYY-MM-DD HH:mm:ss');
                var now = moment();
    
                if (date.isBefore(now)) {
                    var overdue = now.diff(date, 'days') + 1;
                    title += '<br><span>' + this.i18n.expectedCloseDateOverdue.replace(/\%s/, overdue) + '</span>';
                }
    
                return title;
            },
    
            users: function users() {
                return {
                    crmAgents: this.crmAgents,
                    currentUserId: this.currentUserId
                };
            },
    
            stickyNotes: function stickyNotes() {
                return this.deal.notes.filter(function (note) {
                    return parseInt(note.isSticky);
                });
            },
    
            canPerformActions: function canPerformActions$1() {
                if (parseInt(erpDealsGlobal.currentUserId) === parseInt(this.deal.ownerId)) {
                    return true;
                } else if (erpDealsGlobal.isUserAManager || erpDealsGlobal.isUserAnAdmin) {
                    return true;
                }
    
                return false;
            },
    
            showPeopleRemoveBtn: function showPeopleRemoveBtn() {
                if (!parseInt(this.deal.contact.id) || !parseInt(this.deal.company.id)) {
                    return false;
                }
    
                return true;
            }
        },
    
        methods: {
            getDealDetails: function getDealDetails() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'get',
                    dataType: 'json',
                    data: {
                        action: 'get_single_deal_data',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.dealId
                    },
                    beforeSend: function beforeSend() {
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        response.data.deal.company = response.data.deal.company ? response.data.deal.company : { id: 0 };
                        response.data.deal.contact = response.data.deal.contact ? response.data.deal.contact : { id: 0 };
    
                        self.deal = self.camelizedObject(response.data.deal);
                        self.crmAgents = response.data.crm_agents;
                        self.currentUserId = response.data.current_user_id;
                        self.pipelines = response.data.pipelines;
                        self.feeds = response.data.feeds;
    
                        var owner = response.data.crm_agents.filter(function (user) {
                            return parseInt(user.id) === parseInt(self.deal.ownerId);
                        });
    
                        self.selectedOwner = owner[0];
    
                        // make sure deal agents contains integer ids
                        var dealAgents = self.deal.agents.map(function (agentId) {
                            return parseInt(agentId);
                        });
    
                        self.deal.agents = dealAgents;
    
                        self.isReady = true;
                    }
    
                }).always(function () {
                    NProgress.done();
                });
            },
    
            saveDealData: function saveDealData(data) {
                var deal = {
                    id: this.deal.id
                };
    
                deal = Vue.util.extend(deal, data);
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: deal
                    }
                });
            },
    
            setDealTitle: function setDealTitle() {
                this.deal.title = this.tmpDealTitle;
                this.openEditor = '';
    
                this.saveDealData({ title: this.deal.title });
            },
    
            setDealValue: function setDealValue() {
                if (!this.tmpDealValue) {
                    this.deal.value = 0;
                } else {
                    this.deal.value = this.tmpDealValue;
                }
    
                this.openEditor = '';
    
                this.saveDealData({ value: this.deal.value });
            },
    
            onOwnerSelect: function onOwnerSelect$1(owner) {
                this.selectedOwner = owner;
            },
    
            setOwner: function setOwner() {
                this.deal.ownerId = this.selectedOwner.id;
                this.openEditor = '';
    
                this.saveDealData({ owner_id: this.deal.ownerId });
            },
    
            resetOwnerDropdown: function resetOwnerDropdown() {
                var this$1 = this;
    
                this.openEditor = '';
    
                var owner = this.crmAgents.filter(function (user) {
                    return parseInt(user.id) === parseInt(this$1.deal.ownerId);
                });
    
                this.selectedOwner = owner[0];
            },
    
            stageHistory: function stageHistory(milliseconds) {
                return moment.duration(milliseconds).days();
            },
    
            stageHistoryTooltip: function stageHistoryTooltip(title, history) {
                var timeSpan = '';
    
                if (!history) {
                    timeSpan = this.i18n.notYetInStage;
    
                } else if (history < (60 * 1000)) {
                    timeSpan = this.i18n.beenHereForFewSecs;
    
                } else if (history < (60 * 60 * 1000)) {
                    timeSpan = this.i18n.beenHereForFewMins;
    
                } else if (history < (24 * 60 * 60 * 1000)) {
                    timeSpan = this.i18n.beenHereForFewHours;
    
                } else {
                    timeSpan = this.i18n.beenHereFor + ' ' + moment.duration(history).days() + ' ' + this.i18n.days;
                }
                return '<div class="stage-history-tooltip">' +
                            '<strong>' + title + '</strong>' +
                            timeSpan +
                        '</div>';
            },
    
            changeDealStage: function changeDealStage(newStageId) {
                // on page Vue model updates
                var now = moment().format('YYYY-MM-DD HH:mm:ss');
                this.deal.stageHistories[this.deal.stageHistories.length - 1].out = now;
    
                this.deal.stageHistories.$set(this.deal.stageHistories.length, {
                    stageId: newStageId,
                    in: now,
                    out: null
                });
    
                this.deal.stageTitle = this.pipelineStages.filter(function (stage) {
                    return parseInt(stage.id) === parseInt(newStageId);
                }).pop().title;
    
                this.deal.stageId = newStageId;
    
                // update in server DB
                this.saveDealData({ stage_id: newStageId });
            },
    
            onPipelineSelect: function onPipelineSelect(pipeline) {
                if (parseInt(pipeline.id) !== parseInt(this.deal.pipelineId)) {
                    this.tmpPipelineId = pipeline.id;
    
                    this.tmpPipelineStageId = this.tmpPipelineStages.length ? this.tmpPipelineStages[0].id : 0;
                } else {
                    this.tmpPipelineId = 0;
                    this.tmpPipelineStageId = 0;
                }
            },
    
            setTmpPipelineStage: function setTmpPipelineStage(newPipeStageId) {
                this.tmpPipelineStageId = newPipeStageId;
            },
    
            // Actually we'll update the stage id for this deal.
            // Stage id is unique, no need to work with pipeline id.
            // We'll just make some changes for on page
            changeDealPipeline: function changeDealPipeline() {
                var self = this;
    
                var pipeline = this.pipelines.filter(function (pipe) {
                    return parseInt(pipe.id) === parseInt(self.tmpPipelineId);
                }).pop();
    
                var stage = {};
    
                if (pipeline) {
                    stage = pipeline.stages.filter(function (stage) {
                        return parseInt(stage.id) === parseInt(self.tmpPipelineStageId);
                    }).pop();
    
                    if (stage) {
                        this.deal.pipelineId = pipeline.id;
                        this.deal.pipelineTitle = pipeline.title;
                        this.deal.stageId = stage.id;
                        this.deal.stageTitle = stage.title;
    
                        this.changeDealStage(stage.id);
                    }
                }
    
                this.openEditor = '';
            },
    
            setExpectedCloseDate: function setExpectedCloseDate(remove) {
                var date = null;
    
                if (!remove) {
                    date = moment(this.tmpExpectedCloseDate, this.dateFormat).format('YYYY-MM-DD 23:59:59');
                }
    
                this.deal.expectedCloseDate = date;
                this.saveDealData({ expected_close_date: date });
    
                this.openEditor = '';
            },
    
            searchPeople: function searchPeople(s) {
                var self = this;
                var action = '';
    
                var data = {
                    action: 'search_people',
                    _wpnonce: erpDealsGlobal.nonce,
                    s: s
                };
    
                if ('contact' === this.switchPeople.type) {
                    action = 'contacts';
                    data.contact = true;
                } else {
                    action = 'companies';
                    data.company = true;
                }
    
                if (s.length < 3) {
                    this.switchPeople.options = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: data,
                        beforeSend: function beforeSend() {
                            self.switchPeople.isSearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            self.switchPeople.options = response.data[action];
                        }
    
                    }).always(function () {
                        self.switchPeople.isSearching = false;
                    });
                }
            },
    
            onPeopleSelect: function onPeopleSelect(people) {
                this.switchPeople.selected = people;
            },
    
            updatePeople: function updatePeople() {
                var self = this;
                var type = this.switchPeople.type;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'update_deal_people',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.deal.id,
                        type: type,
                        people_id: this.switchPeople.selected.id
                    },
                    beforeSend: function beforeSend() {
                        self.switchPeople.isSearching = true;
                        self.switchPeople.isUpdating = true;
                        NProgress.configure({
                            parent: '#switch-profile-modal-body',
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.deal[type] = self.camelizedObject(response.data.people);
                        self.deal[type + 'Id'] = response.data.people.id;
                        $('#switch-profile-modal').erpDealModal('hide');
                    }
    
                }).always(function () {
                    self.switchPeople.isSearching = false;
                    self.switchPeople.isUpdating = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            searchParticipants: function searchParticipants(s) {
                var self = this;
    
                if (s.length < 3) {
                    this.addParticipants.options = [];
    
                } else {
                    this.ajaxHandler.abort();
    
                    this.ajaxHandler = $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'search_people',
                            _wpnonce: erpDealsGlobal.nonce,
                            s: s
                        },
                        beforeSend: function beforeSend() {
                            self.addParticipants.isSearching = true;
                        }
    
                    }).done(function (response) {
                        if (response.success) {
                            var companies = response.data.companies.map(function (company) {
                                company.type = 'company';
                                return company;
                            });
    
                            var contacts = response.data.contacts.map(function (contact) {
                                contact.type = 'contact';
                                return contact;
                            });
    
                            var people = companies.concat(contacts);
                            var currentParticipantIds = self.arrayPluck(self.deal.participants, 'id', true);
    
                            self.addParticipants.options = people.filter(function (participant) {
                                return (currentParticipantIds.indexOf(parseInt(participant.id)) < 0 );
                            });
                        }
    
                    }).always(function () {
                        self.addParticipants.isSearching = false;
                    });
                }
            },
    
            onParticipantsSelect: function onParticipantsSelect(people) {
                this.addParticipants.selected = people;
            },
    
            updateParticpants: function updateParticpants() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            add_participants: [this.addParticipants.selected]
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.addParticipants.isSearching = true;
                        self.addParticipants.isUpdating = true;
                        NProgress.configure({
                            parent: '#add-participants-modal-body',
                            afterDone: function (nprogress) {
                                nprogress.remove();
                            }
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.deal.participants = response.data.participants.map(function (participant) {
                            return self.camelizedObject(participant);
                        });
    
                        self.addParticipants = {
                            modalTitle: self.i18n.addParticipant,
                            errorClass: '',
                            options: [],
                            selected: {},
                            isSearching: false,
                            isUpdating: false
                        };
                    }
    
                }).always(function () {
                    self.addParticipants.isSearching = false;
                    self.addParticipants.isUpdating = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            reopenDeal: function reopenDeal$1() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            reopen: true
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.deal.wonAt = response.data.deal.won_at;
                        self.deal.lostAt = response.data.deal.lost_at;
                        self.deal.lostReasonId = response.data.deal.lost_reason_id;
                        self.deal.lostReason = response.data.deal.lost_reason;
                        self.deal.lostReasonComment = response.data.deal.lost_reason_comment;
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                });
            },
    
            wonDeal: function wonDeal$1() {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            won: true
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.deal.wonAt = response.data.deal.won_at;
                        self.deal.lostAt = response.data.deal.lost_at;
                        self.deal.lostReasonId = response.data.deal.lost_reason_id;
                        self.deal.lostReason = response.data.deal.lost_reason;
                        self.deal.lostReasonComment = response.data.deal.lost_reason_comment;
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                });
            },
    
            lostDeal: function lostDeal$1() {
                this.$dispatch('open-lost-reason-modal', this.deal.id);
            },
    
            deleteDeal: function deleteDeal$1(action) {
                var self = this;
    
                swal({
                    title: ('trash' === action) ? '' : this.i18n.deleteDealWarningTitle,
                    text: ('trash' === action) ? this.i18n.trashDealWarningMsg : this.i18n.deleteDealWarningMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: ('trash' === action) ? this.i18n.yesTrashIt : this.i18n.yesDeleteIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        self.dealOperations(action);
                    }
                });
            },
    
            dealOperations: function dealOperations$1(action) {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'delete_deal',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal: {
                            id: this.deal.id,
                            action: action
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        if ('delete' === action) {
                            window.location.href = erpDealsGlobal.pipelineURL;
                        } else {
                            window.location.href = window.location.href;
                        }
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                });
            }
        },
    
        watch: {
            openEditor: function openEditor(editor, oldEditor) {
                switch(editor) {
                    case 'title':
                        this.tmpDealTitle = this.deal.title;
                        break;
    
                    case 'value':
                        this.tmpDealValue = this.deal.value;
                        break;
    
                    case 'change-pipeline':
                        this.selectedPipeline = {
                            id: this.deal.pipelineId,
                            title: this.deal.pipelineTitle
                        };
                        break;
    
                    case 'expected-close-date':
    
                        this.tmpExpectedCloseDate = this.deal.expectedCloseDate ?
                                                        moment(this.deal.expectedCloseDate, 'YYYY-MM-DD HH:mm:ss').format(this.dateFormat) :
                                                        null;
                        break;
                }
    
                switch(oldEditor) {
                    case 'change-pipeline':
                        this.tmpPipelineId = 0;
                        this.tmpPipelineStageId = 0;
                        break;
                }
    
                $('[autofocus]').focus();
            }
        },
    
        events: {
            'switch-people': function switch_people(type) {
                this.switchPeople = {
                    type: type,
                    modalTitle: this.i18n[ 'switch' + type ],
                    errorClass: '',
                    options: [],
                    selected: {},
                    isSearching: false,
                    isUpdating: false
                };
    
                $('#switch-profile-modal').erpDealModal();
            },
    
            'remove-people': function remove_people(type) {
                var data = {};
                var people_id = ('company' === type) ? 'company_id' : 'contact_id';
                data[people_id] = null;
    
                this.saveDealData(data);
                this.deal[type] = { id: 0 };
    
                var prop = ('company' === type) ? 'companyId' : 'contactId';
                this.deal[prop] = 0;
            },
    
            'open-add-participants-modal': function open_add_participants_modal() {
                this.addParticipants = {
                    modalTitle: this.i18n.addParticipant,
                    errorClass: '',
                    options: [],
                    selected: {},
                    isSearching: false,
                    isUpdating: false
                };
    
                $('#add-participants-modal').erpDealModal();
            },
    
            'remove-participant': function remove_participant(peopleId) {
                this.saveDealData({ remove_participants: [ peopleId ] });
    
                var participant = this.deal.participants.filter(function (item) {
                    return parseInt(item.id) === parseInt(peopleId);
                }).pop();
    
                this.deal.participants.$remove(participant);
            },
    
            'add-agents': function add_agents(agents) {
                var newAgentIds = this.arrayPluck(agents, 'id', true);
    
                this.deal.agents = this.deal.agents.concat(newAgentIds);
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'add_agents',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.deal.id,
                        agents: newAgentIds
                    }
                });
    
            },
    
            'remove-agent': function remove_agent(agentId) {
                this.deal.agents.$remove(parseInt(agentId));
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'remove_agents',
                        _wpnonce: erpDealsGlobal.nonce,
                        deal_id: this.deal.id,
                        agents: [agentId]
                    }
                });
            },
    
            'save-activity': function save_activity$1(activity) {
                var this$1 = this;
    
                var i = 0;
                var index = -1;
    
                for (i = 0; i < this.deal.activities.length; i++) {
                    if (parseInt(this$1.deal.activities[i].id) === parseInt(activity.id)) {
                        index = i;
                    }
                }
    
                // new
                if (index < 0) {
                    this.deal.activities.$set(this.deal.activities.length, activity);
                // editing existing
                } else {
                    this.deal.activities.$set(index, activity);
                }
            },
    
            'deal-lost': function deal_lost$1(deal) {
                this.deal.wonAt = deal.wonAt;
                this.deal.lostAt = deal.lostAt;
                this.deal.lostReasonId = deal.lostReasonId;
                this.deal.lostReason = deal.lostReason;
                this.deal.lostReasonComment = deal.lostReasonComment;
            }
        }
    });
    
    Vue.component('single-deal-overview', {
        template: '<div class="postbox erp-deal-postbox single-deal-overview"><h3 class="hndle">{{ i18n.overview }}</h3><div class="postbox-inside"><blockquote v-if="deal.lostAt && !deal.wonAt" class="deal-lost-reason">{{ dealLostReason }}</blockquote><table><tr v-if="deal.lostAt && !deal.wonAt" class="text-danger"><td class="half-width">{{ i18n.lostAt }}:</td><td class="text-right" colspan="2">{{ getFormattedDateTime(deal.lostAt) }}</td></tr><tr v-if="!deal.lostAt && deal.wonAt" class="text-success"><td class="half-width">{{ i18n.wonAt }}:</td><td class="text-right" colspan="2">{{ getFormattedDateTime(deal.wonAt) }}</td></tr><tr><td class="half-width">{{ i18n.createdAt }}:</td><td class="text-right" colspan="2">{{ getFormattedDateTime(deal.createdAt) }}</td></tr><tr><td colspan="3"><strong>Top activities</strong></td></tr><tr v-if="!deal.activities.length"><td class="text-center" colspan="3">{{ i18n.dealHasNoActMsg }}</td></tr><tr v-else v-for="activity in activityCounts | orderBy \'count\' -1 | limitBy 5"><td class="half-width">{{ activity.title }}</td><td class="text-center">{{ activity.count }}</td><td class="text-right">{{ activityPercentage(activity.count) }}%</td></tr></table></div></div>',
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                twoWay: true
            }
        },
    
        data: function data$21() {
            return {
                wpTimezone: erpDealsGlobal.wpTimezone,
            };
        },
    
        computed: {
            activityCounts: function activityCounts() {
                var self = this;
                var i;
                var counts = {};
                var activityTypes = erpDealsGlobal.activityTypes;
    
                var loop = function (  ) {
                    var activity = self.deal.activities[i];
                    if (!counts.hasOwnProperty(activity.type)) {
                        counts[activity.type] = {
                            count: 0
                        };
    
                        /*jshint loopfunc: true */
                        var type = activityTypes.filter(function (actType) {
                            return parseInt(actType.id) === parseInt(activity.type);
                        });
    
                        counts[activity.type].title = type[0].title;
                    }
    
                    counts[activity.type].count += 1;
                };
    
                for (i in self.deal.activities) loop(  );
    
                return counts;
            },
    
            dealLostReason: function dealLostReason() {
                var self = this;
    
                if (self.deal.lostReasonId) {
                    var lostReason = erpDealsGlobal.lostReasons.filter(function (reason) {
                        return parseInt(reason.id) === parseInt(self.deal.lostReasonId);
                    });
    
                    return lostReason[0].reason;
    
                } else if (self.deal.lostReason) {
                    return self.deal.lostReason;
                }
    
                return '';
            }
        },
    
        methods: {
            getFormattedDateTime: function getFormattedDateTime(dateTime) {
                return moment.tz(dateTime, 'YYYY-MM-DD hh:mm:ss', this.wpTimezone).format('MMM DD, YYYY hh:mm a');
            },
    
            activityPercentage: function activityPercentage(count) {
                return (parseFloat(count/this.deal.activities.length) * 100).toFixed(1);
            },
        }
    });
    
    Vue.component('sticky-notes', {
        template: '<div v-if="notes.length" class="sticky-notes"><div v-for="note in notes | orderBy \'createdAt\' -1" class="sticky-note erp-deals-note-content" :id="\'sticky-note\' + uid + $index"><div v-if="!minimized[$index]" class="sticky-note-inside"><div class="sticky-note-header clearfix"><span>{{ createdTime(note.createdAt) }} · {{ createdBy(note.createdBy) }}</span> <button type="button" class="button button-small button-link pull-right" @click="minimize($index)">{{ i18n.close }}</button></div><div v-else class="sticky-note-content">{{{ note.note }}}</div></div><div v-else class="minimized-content">{{ minimizedContent(note.note) }}</div><button class="button button-small button-link pin-icon" v-erp-tooltip :tooltip-title="i18n.unpin" @click="removeSticky($index, note)"><i class="dashicons dashicons-admin-post"></i></button> <span v-if="minimized[$index]" class="sticky-note-overlay" @click="unminimize($index)"></span></div></div>',
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            notes: {
                type: Array,
                required: true,
                default: [],
                twoWay: true
            },
    
            crmAgents: {
                type: Array,
                required: true,
                default: []
            }
        },
    
        data: function data$22() {
            return {
                wpTimezone: erpDealsGlobal.wpTimezone,
                minimized: [],
                uid: this._uid
            };
        },
    
        ready: function ready$12() {
            this.mapMinimize();
        },
    
        methods: {
            createdTime: function createdTime(time) {
                return moment.tz(time, 'YYYY-MM-DD HH:mm:ss', this.wpTimezone).fromNow();
            },
    
            createdBy: function createdBy(agentId) {
                var agent = this.crmAgents.filter(function (agent) {
                    return parseInt(agent.id) === parseInt(agentId);
                }).pop();
    
                return agent.name;
            },
    
            minimizedContent: function minimizedContent(content) {
                // remove html tags
                content = content.replace(/<\/.+?>/g, ' ');
                content = content.replace(/<.+?>/g, ' ');
    
                return content;
            },
    
            mapMinimize: function mapMinimize() {
                var self = this;
    
                this.$set('minimized', []);
    
                this.notes.map(function () {
                    self.minimized.push(true);
                });
            },
    
            minimize: function minimize(index) {
                this.minimized.$set(index, true);
            },
    
            unminimize: function unminimize(index) {
                this.minimized.$set(index, false);
            },
    
            removeSticky: function removeSticky(index, note) {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal_note',
                        _wpnonce: erpDealsGlobal.nonce,
                        note: {
                            id: note.id,
                            is_sticky: false
                        }
                    },
                    beforeSend: function beforeSend() {
                        NProgress.configure({ parent: '#sticky-note' + self.uid + index });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        note.isSticky = false;
                        self.notes.$set(index, note);
                    }
    
                }).always(function () {
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            }
        },
    
        watch: {
            notes: function notes() {
                this.mapMinimize();
            }
        }
    });
    
    Vue.component('text-editor', {
        template: '<textarea :id="\'vue-text-editor-\' + editorId" class="vue-text-editor">{{ content }}</textarea>',
    
        props: {
            content: {
                type: String,
                default: '',
                required: true,
                twoWay: true
            }
        },
    
        data: function data$23() {
            return {
                editorId: this._uid,
                tinymce: null
            };
        },
    
        computed: {
            shortcodes: function shortcodes() {
                return erpDealsGlobal.shortcodes;
            },
    
            pluginURL: function pluginURL() {
                return erpDealsGlobal.pluginURL;
            }
        },
    
        ready: function ready$13() {
            var self = this;
    
            window.tinymce.init({
                selector: 'textarea#vue-text-editor-' + this.editorId,
                height: 300,
                menubar: false,
                convert_urls: false,
                theme: 'modern',
                skin: 'lightgray',
                content_css: self.pluginURL + '/assets/css/text-editor.css',
                setup: function setup(editor) {
                    var menuItems = [];
    
                    self.tinymce = editor;
    
                    var loop = function ( shortcodeType ) {
                        menuItems.push({
                            text: self.shortcodes[shortcodeType].title,
                            classes: 'menu-section-title'
                        });
    
                        var loop$1 = function ( shortcode ) {
                            var shortcodeDetails = self.shortcodes[shortcodeType].codes[shortcode];
    
                            /*jshint loopfunc: true */
                            menuItems.push({
                                text: shortcodeDetails.title,
                                onclick: function onclick() {
                                    var code = '{' + shortcodeType + ':' + shortcode + '}';
    
                                    if (shortcodeDetails.default) {
                                        code = '{' + shortcodeType + ':' + shortcode + ' default="' + shortcodeDetails.default + '"}';
                                    }
    
                                    if (shortcodeDetails.text) {
                                        code = '{' + shortcodeType + ':' + shortcode + ' text="' + shortcodeDetails.text + '"}';
                                    }
    
                                    if (shortcodeDetails.plain_text && shortcodeDetails.text) {
                                        code = shortcodeDetails.text;
                                    }
    
                                    editor.insertContent(code);
                                }
                            });
    
                        };
    
                        for (var shortcode in self.shortcodes[shortcodeType].codes) loop$1( shortcode );
                    };
    
                    for (var shortcodeType in self.shortcodes) loop( shortcodeType );
    
                    // register shortcode button
                    editor.addButton('shortcodes', {
                        type: 'menubutton',
                        icon: 'shortcode',
                        tooltip: 'Shortcodes',
                        menu: menuItems
                    });
    
                    // attachment button
                    editor.addButton('attachments', {
                        icon: 'attachments',
                        tooltip: 'Attachments',
                        onclick : function() {
                            self.$dispatch('open-attachment-modal', self.editorId);
                        },
                    });
    
                    // editor change triggers
                    editor.on('change', function () {
                        self.$set('content', editor.getContent());
                    });
                    editor.on('keyup', function () {
                        self.$set('content', editor.getContent());
                    });
                    editor.on('NodeChange', function () {
                        self.$set('content', editor.getContent());
                    });
                },
                fontsize_formats: '10px 11px 13px 14px 16px 18px 22px 25px 30px 36px 40px 45px 50px 60px 65px 70px 75px 80px',
                font_formats : 'Arial=arial,helvetica,sans-serif;'+
                    'Comic Sans MS=comic sans ms,sans-serif;'+
                    'Courier New=courier new,courier;'+
                    'Georgia=georgia,palatino;'+
                    'Lucida=Lucida Sans Unicode, Lucida Grande, sans-serif;'+
                    'Tahoma=tahoma,arial,helvetica,sans-serif;'+
                    'Times New Roman=times new roman,times;'+
                    'Trebuchet MS=trebuchet ms,geneva;'+
                    'Verdana=verdana,geneva;',
                plugins: 'textcolor colorpicker wplink wordpress code hr',
                toolbar1: 'shortcodes bold italic strikethrough bullist numlist alignleft aligncenter alignjustify alignright link forecolor backcolor underline hr attachments',
                toolbar2: 'formatselect fontselect fontsizeselect blockquote code removeformat undo redo',
                // toolbar3: '',
            });
        },
    
        events: {
            'set-tinymce-content': function set_tinymce_content(content) {
                this.tinymce.setContent(content);
            },
    
            'toggle-tinymce-mode': function toggle_tinymce_mode(disable) {
                if (disable) {
                    this.tinymce.setMode('readonly');
                } else {
                    this.tinymce.setMode('design');
                }
            }
        }
    });
    
    Vue.component('timeline', {
        /* global userCapabilities, dealUtils, dealAttachment */
        template: '<div class="erp-deals-timeline"><ul class="timeline-tabs"><li v-for="tab in tabs" :class="[(currentTab === tab.name) ? \'active\' : \'\' ]"><button type="button" class="button button-link" @click="currentTab = tab.name">{{ tab.title }} <span v-if="tabItemCounts[tab.name]" class="tab-item-count">({{ tabItemCounts[tab.name] }})</span></button></li><li :class="[(currentTab === \'changelog\') ? \'active\' : \'\' ]"><button type="button" class="button button-link" @click="currentTab = \'changelog\'">{{ i18n.changelog }}</button></li></ul><div v-if="!timelineItems.length"><p v-if="\'changelog\' !== currentTab" class="text-center">{{ i18n.noTimelineItemMsg }}</p><changelog v-else :i18n="i18n" :deal-id="deal.id"></changelog></div><div v-else class="timeline-content-container"><div v-for="monthItems in timelineItems | orderBy \'firstDayOfMonth\' -1" class="items-in-a-month"><div class="time-label">{{ monthItems.monthLabel }}</div><div v-for="singleItem in monthItems.items | orderBy \'createdAt\' -1" :class="[\'single-item clearfix\', \'single-item-\' + singleItem.type]"><i :class="[\'timeline-icon\', singleItem.iconClass]" v-erp-tooltip :tooltip-title="i18n[singleItem.type]"></i><div class="timeline-item-container"><div :class="[\'timeline-item\', doingAjax ? \'disabled\' : \'\']" :id="\'timeline-item-\' + singleItem.type + \'-\' + _uid + singleItem.details.id"><div v-if="\'activity\' === singleItem.type && userCanEditActivity(singleItem.details)" class="activity"><span :class="[\'deal-mark-as-done\', singleItem.details.doneAt ? \'done\' : \'\']" v-erp-tooltip :tooltip-title="singleItem.details.doneAt ? i18n.markAsToDo : i18n.markAsDone" @click="markActivityAsDone(singleItem)"><i class="dashicons dashicons-yes"></i></span> <a href="#edit-activity" class="edit-activity" @click.prevent="openActivityModal(singleItem)">{{ singleItem.details.title }}</a><div class="timeline-item-footer"><ul class="list-inline"><li class="created-at">{{ getTimelineTimeFormat(singleItem.details.createdAt) }}</li><li class="created-by">{{{ getTimelineCreator(singleItem.details.createdBy) }}}</li></ul></div></div><div v-if="\'note\' === singleItem.type" class="note"><div v-if="parseInt(singleItem.details.id) === parseInt(note.editing)"><form @submit.prevent="saveNote(singleItem)" :class="[(parseInt(note.doingAjax) === parseInt(singleItem.details.id)) ? \'disableds\' : \'\']" :id="\'timeline-note-id-\' + _uid + singleItem.details.id"><div class="erp-feed-editor-note"><note :content.sync="note.content"></note></div><div class="editor-footer"><label :class="[note.doingAjax ? \'disableds\' : \'\']"><input type="checkbox" v-model="note.isSticky"> {{ i18n.pinThisNote }}</label> <button type="reset" class="button button-link button-small" @click="resetNoteForm(singleItem)">{{ i18n.cancel }}</button> <button type="submit" class="button button-primary button-small" :disableds="note.doingAjax">{{ i18n.save }}</button></div></form></div><div v-else>{{{ singleItem.details.note }}}<div class="timeline-item-footer"><ul class="list-inline"><li class="created-at">{{ getTimelineTimeFormat(singleItem.details.createdAt) }}</li><li class="created-by">{{{ getTimelineCreator(singleItem.details.createdBy) }}}</li><li v-if="parseInt(singleItem.details.isSticky)" class="pinned-note"><i class="dashicons dashicons-admin-post"></i> {{ i18n.pinnedNote }}</li></ul></div></div></div><div v-if="\'email\' === singleItem.type" class="email"><a :name="\'timeline-email-\' + singleItem.details.id"></a><div class="email-item-title clearfix">{{{ getEmailItemTitle(singleItem.details) }}}</div><div class="email-content"><p class="email-subject"><em>{{ i18n.subject }}: {{ singleItem.details.emailSubject }}</em></p>{{{ singleItem.details.message }}}</div><div v-if="!singleItem.details.hash && parseInt(singleItem.details.id) !== openEmailReply" class="timeline-item-footer"><a class="reply-btn" href="#reply-email" @click.prevent="openEmailReply = parseInt(singleItem.details.id)">{{ i18n.reply }}</a></div><div v-if="singleItem.details.parentId && parseInt(singleItem.details.id) === openEmailReply" class="email-reply-editor"><h4 class="clearfix">{{ i18n.replyMessage }} <a class="pull-right" href="#close" @click.prevent="openEmailReply = 0">{{ i18n.close }}</a></h4><feed-editor-email :i18n="i18n" :deal="deal" :users="users" :parent-email="singleItem.details"></feed-editor-email></div></div><div v-if="\'attachment\' === singleItem.type" class="deal-attachment"><table><tr><td class="mime-type-icon"><i :class="[\'dashicons\', getMimeIconClass(singleItem.details.type)]"></i></td><td class="filename"><a :href="singleItem.details.url" target="_blank">{{ singleItem.details.filename }}</a><div class="timeline-item-footer"><ul class="list-inline"><li class="created-at">{{ getTimelineTimeFormat(singleItem.details.createdAt) }}</li><li class="filesize">{{ singleItem.details.filesize }}</li><li class="created-by">{{{ getTimelineCreator(singleItem.details.addedBy.id) }}}</li></ul></div></td></tr></table></div><div v-if="\'email\' !== singleItem.type" :class="[\'item-menu\', (openItemMenu == singleItem.type + \'-\' + singleItem.details.id) ? \'open\' : \'\']"><button class="button button-link" @click="openMenu(singleItem)" @blur="onBlurMenuButton"><span v-if="!(openItemMenu == singleItem.type + \'-\' + singleItem.details.id)" class="fa fa-angle-down"></span> <span v-else>×</span></button><ul class="erp-dropdown-menu"><li><a class="erp-dropdown-menu-item" v-if="\'attachment\' !== singleItem.type" href="#edit" @click.prevent="editTimelineItem(singleItem)">{{ i18n.edit }}</a> <a class="erp-dropdown-menu-item" v-if="\'activity\' === singleItem.type && singleItem.details.doneAt" href="#mark-as-do-to" @click.prevent="markActivityAsDone(singleItem)">{{ i18n.markAsToDo }}</a> <a class="erp-dropdown-menu-item" v-if="\'activity\' === singleItem.type && !singleItem.details.doneAt" href="#mark-done" @click.prevent="markActivityAsDone(singleItem)">{{ i18n.markAsDone }}</a> <a class="erp-dropdown-menu-item" v-if="\'note\' === singleItem.type && !parseInt(singleItem.details.isSticky)" href="#pin-this-note" @click.prevent="markNoteAsSticky(singleItem)">{{ i18n.pinThisNote }}</a> <a class="erp-dropdown-menu-item" v-if="\'note\' === singleItem.type && parseInt(singleItem.details.isSticky)" href="#unpin-this-note" @click.prevent="markNoteAsNonSticky(singleItem)">{{ i18n.unpinThisNote }}</a> <a v-if="\'attachment\' === singleItem.type" class="erp-dropdown-menu-item" :href="singleItem.details.url" target="_blank" @click="openItemMenu = \'\'">{{ i18n.download }}</a> <a class="erp-dropdown-menu-item" href="#delete" @click.prevent="deleteTimelineItem(singleItem)">{{ (\'attachment\' !== singleItem.type) ? i18n.delete : i18n.remove }}</a></li></ul></div></div></div></div></div></div></div>',
    
        mixins: [userCapabilities, dealUtils, dealAttachment],
    
        props: {
            i18n: {
                type: Object,
                default: {}
            },
    
            deal: {
                type: Object,
                required: true,
                default: {},
                twoWay: true
            },
    
            agents: {
                type: Array,
                required: true,
                default: []
            }
        },
    
        data: function data$24() {
            return {
                tabs: [
                    { name: 'all', title: this.i18n.all },
                    { name: 'activities', title: this.i18n.activities },
                    { name: 'notes', title: this.i18n.notes },
                    { name: 'emails', title: this.i18n.emails },
                    { name: 'attachments', title: this.i18n.attachments },
                ],
                currentTab: 'all',
                openItemMenu: '',
                doingAjax: false,
                note: {
                    content: '',
                    editing: 0,
                    doingAjax: false,
                    isSticky: false,
                },
                peopleNames: [
                    { id: 10, name: 'hello world' }
                ],
                openEmailReply: 0
            };
        },
    
        computed: {
            activityIcons: function activityIcons() {
                var icons = {};
    
                erpDealsGlobal.activityTypes.map(function (type) {
                    icons[type.id] = type.icon;
                });
    
                return icons;
            },
    
            timelineItems: function timelineItems$1() {
                var self = this;
                var timelineItems = [];
                var items = {};
    
                // activities
                if (this.currentTab === 'all' || this.currentTab === 'activities') {
                    this.deal.activities.map(function (activity, index) {
                        var firstDayOfMonth = moment(activity.createdAt, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-01');
    
                        if (!items.hasOwnProperty(firstDayOfMonth)) {
                            items[firstDayOfMonth] = {
                                monthLabel: moment(activity.createdAt, 'YYYY-MM-DD HH:mm:ss').format('MMMM, YYYY'),
                                items: []
                            };
                        }
    
                        items[firstDayOfMonth].items.push({
                            type: 'activity',
                            iconClass: 'picon picon-' + self.activityIcons[activity.type],
                            createdAt: activity.createdAt,
                            index: index,
                            details: activity
                        });
                    });
                }
    
                // notes
                if (this.currentTab === 'all' || this.currentTab === 'notes') {
                    this.deal.notes.map(function (note, index) {
                        var firstDayOfMonth = moment(note.createdAt, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-01');
    
                        if (!items.hasOwnProperty(firstDayOfMonth)) {
                            items[firstDayOfMonth] = {
                                monthLabel: moment(note.createdAt, 'YYYY-MM-DD HH:mm:ss').format('MMMM, YYYY'),
                                items: []
                            };
                        }
    
                        items[firstDayOfMonth].items.push({
                            type: 'note',
                            iconClass: 'dashicons dashicons-edit',
                            createdAt: note.createdAt,
                            index: index,
                            details: note
                        });
                    });
                }
    
                // emails
                if (this.currentTab === 'all' || this.currentTab === 'emails') {
                    this.deal.emails.map(function (email, index) {
                        var firstDayOfMonth = moment(email.createdAt, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-01');
    
                        if (!items.hasOwnProperty(firstDayOfMonth)) {
                            items[firstDayOfMonth] = {
                                monthLabel: moment(email.createdAt, 'YYYY-MM-DD HH:mm:ss').format('MMMM, YYYY'),
                                items: []
                            };
                        }
    
                        items[firstDayOfMonth].items.push({
                            type: 'email',
                            iconClass: 'dashicons dashicons-email-alt',
                            createdAt: email.createdAt,
                            index: index,
                            details: email
                        });
                    });
                }
    
                // attachments
                if (this.currentTab === 'all' || this.currentTab === 'attachments') {
                    this.deal.attachments.map(function (attachment, index) {
                        var firstDayOfMonth = moment(attachment.createdAt, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-01');
    
                        if (!items.hasOwnProperty(firstDayOfMonth)) {
                            items[firstDayOfMonth] = {
                                monthLabel: moment(attachment.createdAt, 'YYYY-MM-DD HH:mm:ss').format('MMMM, YYYY'),
                                items: []
                            };
                        }
    
                        items[firstDayOfMonth].items.push({
                            type: 'attachment',
                            iconClass: 'dashicons dashicons-paperclip',
                            createdAt: attachment.createdAt,
                            index: index,
                            details: attachment
                        });
                    });
                }
    
                for(var firstDayOfMonth in items) {
                    timelineItems.push({
                        firstDayOfMonth: firstDayOfMonth,
                        monthLabel: items[firstDayOfMonth].monthLabel,
                        items: items[firstDayOfMonth].items
                    });
                }
    
                return timelineItems;
            },
    
            tabItemCounts: function tabItemCounts() {
                return {
                    all: 0,
                    activities: this.deal.activities.length,
                    notes: this.deal.notes.length,
                    emails: this.deal.emails.length,
                    attachments: this.deal.attachments.length,
                };
            },
    
            users: function users$1() {
                return {
                    currentUserId: erpDealsGlobal.currentUserId,
                    crmAgents: this.agents
                };
            }
        },
    
        methods: {
            openMenu: function openMenu(singleItem) {
                var item = singleItem.type + '-' + singleItem.details.id;
    
                if (this.openItemMenu === item) {
                    this.openItemMenu = '';
                } else {
                    this.openItemMenu = item;
                }
            },
    
            onBlurMenuButton: function onBlurMenuButton(e) {
                if(!$(e.relatedTarget).hasClass('erp-dropdown-menu-item')) {
                    this.openItemMenu = '';
                }
            },
    
            editTimelineItem: function editTimelineItem(timelineItem) {
                if ('activity' === timelineItem.type) {
                    this.openActivityModal(timelineItem);
    
                } else if ('note' === timelineItem.type) {
                    var note = $.extend(true, {}, timelineItem.details);
                    this.note.content = note.note;
                    this.note.editing = note.id;
                    this.note.isSticky = parseInt(note.isSticky) ? 1 : 0;
                }
    
                this.openItemMenu = '';
            },
    
            openActivityModal: function openActivityModal$3(timelineItem) {
                var activity = timelineItem.details;
    
                var names = {
                    contactId: activity.contactId,
                    contact: activity.contact,
                    companyId: activity.companyId,
                    company: activity.company
                };
    
                this.$dispatch('open-activity-modal', activity, activity.dealId, names);
            },
    
            markActivityAsDone: function markActivityAsDone(timelineItem) {
                var self = this;
                var activity = $.extend(true, {}, timelineItem.details);
    
                activity.doneAt = activity.doneAt ? null : true;
    
                // ajax update
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_activity',
                        _wpnonce: erpDealsGlobal.nonce,
                        activity: {
                            id: activity.id,
                            done_at: activity.doneAt
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#timeline-item-activity-' + self._uid + activity.id,
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.deal.activities.$set(timelineItem.index, self.camelizedObject(response.data.activity));
                    }
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
    
                this.openItemMenu = '';
            },
    
            getTimelineTimeFormat: function getTimelineTimeFormat$1(time) {
                return moment(time, 'YYYY-MM-DD HH:mm:ss').format('ddd MMM DD, hh:mm A');
            },
    
            getTimelineCreator: function getTimelineCreator(agentId) {
                var agent = this.agents.filter(function (agent) {
                    return parseInt(agent.id) === parseInt(agentId);
                });
    
                agent = agent[0];
    
                return ("<a href=\"" + (agent.link) + "\" target=\"_blank\"><img src=\"" + (agent.avatar) + "\">" + (agent.name) + "</a>");
            },
    
            resetNoteForm: function resetNoteForm(timelineItem) {
                if ($('#timeline-note-id-' + this._uid + timelineItem.details.id).length) {
                    $('#timeline-note-id-' + this._uid + timelineItem.details.id).get(0).reset();
                }
    
                this.note = {
                    content: '',
                    editing: 0,
                    doingAjax: false,
                    isSticky: false,
                };
            },
    
            saveNote: function saveNote$1(timelineItem) {
                var self = this;
    
                $.ajax({
                    url: erpDealsGlobal.ajaxurl,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        action: 'save_deal_note',
                        _wpnonce: erpDealsGlobal.nonce,
                        note: {
                            id: timelineItem.details.id,
                            deal_id: timelineItem.details.dealId,
                            note: this.note.content,
                            is_sticky: this.note.isSticky
                        }
                    },
                    beforeSend: function beforeSend() {
                        self.note.doingAjax = true;
    
                        self.doingAjax = true;
                        NProgress.configure({
                            parent: '#timeline-item-note-' + self._uid + timelineItem.details.id,
                        });
                        NProgress.start();
                    }
    
                }).done(function (response) {
                    if (response.success) {
                        self.$dispatch('added-deal-note');
                        self.deal.notes.$set(timelineItem.index, self.camelizedObject(response.data.note));
                        self.resetNoteForm(timelineItem);
                    }
    
                }).always(function () {
                    self.doingAjax = false;
                    NProgress.done();
                    window.setDefaultNProgressParent();
                });
            },
    
            markNoteAsSticky: function markNoteAsSticky(timelineItem) {
                var note = $.extend(true, {}, timelineItem.details);
                this.note.content = note.note;
                this.note.editing = 0;
                this.note.isSticky = 1;
    
                this.saveNote(timelineItem);
    
                this.openItemMenu = '';
            },
    
            markNoteAsNonSticky: function markNoteAsNonSticky(timelineItem) {
                var note = $.extend(true, {}, timelineItem.details);
                this.note.content = note.note;
                this.note.editing = 0;
                this.note.isSticky = 0;
    
                this.saveNote(timelineItem);
    
                this.openItemMenu = '';
            },
    
            deleteTimelineItem: function deleteTimelineItem(timelineItem) {
                switch(timelineItem.type) {
                    case 'activity':
                        this.deleteActivity(timelineItem.details);
                        break;
    
                    case 'note':
                        this.deleteNote(timelineItem.details);
                        break;
    
                    case 'attachment':
                        this.removeAttachment(timelineItem.details);
                        break;
                }
    
                this.openItemMenu = '';
            },
    
            deleteActivity: function deleteActivity$2(activity) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteActivityWarningMsg,
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
                                action: 'delete_activity',
                                _wpnonce: erpDealsGlobal.nonce,
                                id: activity.id
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                NProgress.configure({
                                    parent: '#timeline-item-activity-' + self._uid + activity.id,
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.deal.activities.$remove(activity);
                                }, 201); // 201 is the NProgress default speed settings
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            deleteNote: function deleteNote(note) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.deleteNoteWarningMsg,
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
                                action: 'delete_note',
                                _wpnonce: erpDealsGlobal.nonce,
                                id: note.id
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                NProgress.configure({
                                    parent: '#timeline-item-note-' + self._uid + note.id,
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.deal.notes.$remove(note);
                                }, 201); // 201 is the NProgress default speed settings
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            removeAttachment: function removeAttachment$1(attachment) {
                var self = this;
    
                swal({
                    title: '',
                    text: this.i18n.removeAttachmentWarningMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d54e21',
                    confirmButtonText: this.i18n.yesRemoveIt,
                }, function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: erpDealsGlobal.ajaxurl,
                            method: 'post',
                            dataType: 'json',
                            data: {
                                action: 'remove_deal_attachment',
                                _wpnonce: erpDealsGlobal.nonce,
                                attachment_id: attachment.id,
                                deal_id: self.deal.id
                            },
                            beforeSend: function beforeSend() {
                                self.doingAjax = true;
                                NProgress.configure({
                                    parent: '#timeline-item-attachment-' + self._uid + attachment.id,
                                });
                                NProgress.start();
                            }
    
                        }).done(function (response) {
                            if (response.success) {
                                setTimeout(function () {
                                    self.deal.attachments.$remove(attachment);
                                }, 201);
                            }
    
                        }).always(function () {
                            self.doingAjax = false;
                            NProgress.done();
                            window.setDefaultNProgressParent();
                        });
                    }
                });
            },
    
            getPeopleName: function getPeopleName(peopleId, email) {
                peopleId = parseInt(peopleId);
    
                // is this id belongs to deal contact?
                if (peopleId === parseInt(this.deal.contactId)) {
                    return this.deal.contact.firstName + ' ' + this.deal.contact.lastName;
                }
    
                // is this id belongs to deal company?
                if (peopleId === parseInt(this.deal.companyId)) {
                    return this.deal.company.company;
                }
    
                // is this id is in participants list?
                var participants = this.deal.participants.filter(function (pep) {
                    return parseInt(pep.id) === peopleId;
                });
    
                if (participants.length) {
                    var participant = participants[0];
    
                    if ('contact' === participant.peopleType) {
                        return participant.firstName + ' ' + participant.lastName;
                    } else if ('company' === participant.peopleType) {
                        return participant.company;
                    }
                }
    
                // return
                return email;
            },
    
            getEmailItemTitle: function getEmailItemTitle(email) {
                var self = this;
                var parentId = parseInt(email.parentId);
    
                var parentEmailSubject = '';
    
                var parentEmail = self.deal.emails.filter(function (mail) {
                    return parseInt(mail.id) === parentId;
                });
    
                if (parentEmail.length) {
                    parentEmail = parentEmail[0];
                    parentEmailSubject = self.i18n.repliedTo + ' ' +
                                            '<a href="#timeline-email-' + parentId + '">' + parentEmail.emailSubject + '</a> ';
                }
    
                // having hash means agent sent email to contact/company
                if (email.hash) {
                    var agent = self.agents.filter(function (agent) {
                        return parseInt(agent.id) === parseInt(email.createdBy);
                    });
    
                    agent = agent[0];
    
                    if (!email.parentId) {
                        return '<strong>' + agent.name + '</strong> ' +
                                self.i18n.sentAnEmailTo +
                                ' <strong>' + self.getPeopleName(email.userId, email.email) + '</strong>' +
                                ' ' + self.i18n.on + ' ' + self.getTimelineTimeFormat(email.createdAt);
                    } else {
                        return ' <strong>' + agent.name + '</strong>' +
                                ' ' + parentEmailSubject + self.i18n.on + ' ' + self.getTimelineTimeFormat(email.createdAt);
                    }
    
                // no hash means it is a reply email from contact/company
                } else {
                    return ' <strong>' + self.getPeopleName(email.userId, email.email) + '</strong>' +
                            ' ' + parentEmailSubject + self.i18n.on + ' ' + self.getTimelineTimeFormat(email.createdAt);
    
                }
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
    
    if ($('#erp-deals').length) {
        /*global dealUtils*/
    
        new Vue({
            el: '#erp-deals',
    
            mixins: [dealUtils],
    
            data: {
                i18n: erpDealsGlobal.i18n,
                pipelineId: 0,
                pipeline: [],
                currencySymbol: '',
                users: {
                    crmAgents: [],
                    currentUserId: 0
                },
                view: 'pipeline',
                filters: {
                    status: null,
                    owner: 0
                },
            },
    
            created: function created$1() {
                this.setQueriesAndFilters();
            },
    
            computed: {
                urlQueries: function urlQueries() {
                    var urlQueries = document.location.search.replace(/^\?/, '').split('&');
                    var queries = {};
                    var match = null;
    
                    for (var i in urlQueries) {
                        if (match = urlQueries[i].match(/page=(.*)/)) {
                            queries.page = match[1];
                        }
    
                        if (match = urlQueries[i].match(/section=(.*)/)) {
                            queries.section = match[1];
                        }
    
                        if (match = urlQueries[i].match(/action=(.*)/)) {
                            queries.action = match[1];
                        }
    
                        if (match = urlQueries[i].match(/id=(.*)/)) {
                            queries.id = parseInt(match[1]);
                        }
    
                        if (match = urlQueries[i].match(/pipeline=(.*)/)) {
                            queries.pipeline = parseInt(match[1]);
                        }
    
                        if (match = urlQueries[i].match(/view=(.*)/)) {
                            queries.view = match[1];
                        }
                    }
    
                    return queries;
                },
    
                urlFilters: function urlFilters() {
                    var filterStr = document.location.hash.replace(/^\#filter\//, '').split('/');
                    var filters = {};
                    var i = 0;
    
                    for(i = 0; i < filterStr.length; i++) {
                        if ('status' === filterStr[i] && filterStr[i+1]) {
                            filters.status = filterStr[i+1];
                        }
    
                        if ('owner' === filterStr[i] && filterStr[i+1]) {
                            filters.owner = filterStr[i+1];
                        }
                    }
    
                    return filters;
                },
    
                pipelineStages: function pipelineStages$1() {
                    return this.pipeline.map(function (stage) {
                        return { id: stage.id, title: stage.title };
                    });
                }
            },
    
            methods: {
                getDealsByPipeline: function getDealsByPipeline(pipeId) {
                    var this$1 = this;
    
                    var self = this;
    
                    $.ajax({
                        url: erpDealsGlobal.ajaxurl,
                        method: 'get',
                        dataType: 'json',
                        data: {
                            action: 'get_deals_by_pipeline',
                            _wpnonce: erpDealsGlobal.nonce,
                            // pipeId is null when we call this method after switching filters
                            pipeline_id: pipeId || this.pipelineId,
                            filters: this.filters
                        },
                        beforeSend: function beforeSend() {
                            NProgress.start();
                        }
    
                    }).done(function (response) {
                        NProgress.done();
    
                        if (response.success) {
                            self.pipelineId = response.data.pipelineId;
                            self.pipeline = response.data.pipeline;
                            self.currencySymbol = response.data.currencySymbol;
                            self.users = response.data.users ? this$1.camelizedObject(response.data.users) : {};
                        }
                    });
                },
    
                // set filters from url on page load
                setQueriesAndFilters: function setQueriesAndFilters() {
                    // view
                    if (this.urlQueries.action && 'view-deal' === this.urlQueries.action) {
                        this.view = 'single-deal';
                    } else {
                        this.view = 'pipeline';
                    }
    
                    // status filter
                    if (this.urlFilters.status) {
                        this.filters.status = this.urlFilters.status;
                        this.createCookie('current-filter-status', Vue.util.camelize('filter-' + this.urlFilters.status), 30);
    
                    } else {
                        this.filters.status = null;
                        this.createCookie('current-filter-status', '', -1);
                    }
    
                    // owner filter
                    if (this.urlFilters.owner) {
                        this.filters.owner = this.urlFilters.owner;
                        this.createCookie('current-filter-owner', Vue.util.camelize('filter-' + this.urlFilters.owner), 30);
    
                    } else {
                        this.filters.owner = null;
                        this.createCookie('current-filter-owner', '', -1);
                    }
                },
    
                // change url in deals main page
                historyPushState: function historyPushState() {
                    var url = erpDealsGlobal.pipelineURL;
                    var filters = [];
    
                    if (this.filters.status) {
                        filters.push('status/' + this.filters.status);
                    }
    
                    if (this.filters.owner) {
                        filters.push('owner/' + this.filters.owner);
                    }
    
                    if (filters.length) {
                        url = url + '#filter/' + filters.join('/');
                    }
    
                    history.pushState(null, null, url);
                }
            },
    
            events: {
                'switch-pipeline': function switch_pipeline(pipeId) {
                    this.$set('pipeline', []);
                    this.$set('pipelineId', pipeId);
    
                    this.getDealsByPipeline(pipeId);
                },
    
                'switch-filter-status': function switch_filter_status() {
                    this.getDealsByPipeline();
                    this.historyPushState();
                },
    
                'switch-filter-owner': function switch_filter_owner() {
                    this.getDealsByPipeline();
                    this.historyPushState();
                },
    
                'open-new-deal-modal': function open_new_deal_modal$1() {
                    this.$broadcast('open-new-deal-modal');
                },
    
                'new-deal-added': function new_deal_added(deal) {
                    var pipeStage = this.pipeline.filter(function (stage) {
                        return parseInt(stage.id) === parseInt(deal.stageId);
                    });
    
                    pipeStage[0].deals.$set(pipeStage[0].deals.length, deal);
                },
    
                'update-deal-activity-start': function update_deal_activity_start(deal) {
                    if ('pipeline' === this.view) {
                        var stage = this.pipeline.filter(function (stage) {
                            return parseInt(stage.id) === parseInt(deal.stageId);
                        });
    
                        if (stage.length) {
                            var stage_deal = stage[0].deals.filter(function (item) {
                                return parseInt(item.id) === parseInt(deal.id);
                            });
    
                            stage_deal[0].actStart = deal.actStart;
                        }
                    }
                },
    
                'open-activity-modal': function open_activity_modal$1(activity, dealId, dealNames) {
                    // send signal to open modal to activity-modal child component
                    this.$broadcast('open-activity-modal', activity, dealId, dealNames);
                },
    
                'deal-won': function deal_won$1(deal) {
                    this.$broadcast('deal-won', deal);
                },
    
                'open-lost-reason-modal': function open_lost_reason_modal$1(dealId) {
                    this.$broadcast('open-lost-reason-modal', dealId);
                },
    
                'deal-lost': function deal_lost$2(deal) {
                    this.$broadcast('deal-lost', deal);
                },
    
                'remove-deal': function remove_deal$1(deal) {
                    this.$broadcast('remove-deal', deal);
                }
            },
        });
    }
})(jQuery);
