!function(a){var b=function(a,b){this.init("tooltip",a,b)};
	b.prototype={constructor:b,init:function(b,c,d){
		var e,f;this.type=b,this.$element=a(c),
		this.options=this.getOptions(d),
		this.enabled=!0,this.options.trigger=="click"?this.$element.on(
			"click."+this.type,this.options.selector,a.proxy(this.toggle,this)
			):this.options.trigger!="manual"&&(e=this.options.trigger=="hover"?"mouseenter":"focus",
		f=this.options.trigger=="hover"?"mouseleave":"blur",
		this.$element.on(e+"."+this.type,this.options.selector,a.proxy(this.enter,this)),
		this.$element.on(f+"."+this.type,this.options.selector,a.proxy(this.leave,this))),
		this.options.selector?this._options=a.extend({},
			this.options,{trigger:"manual",selector:""}
			):this.fixTitle()},
		getOptions:function(b){return b=a.extend({},a.fn[this.type].defaults,b,
		this.$element.data()),
		b.delay&&typeof b.delay=="number"&&(b.delay={show:b.delay,hide:b.delay}),b},
		enter:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);
		if(!c.options.delay||!c.options.delay.show)return c.show();clearTimeout(this.timeout),
		c.hoverState="in",this.timeout=setTimeout(function(){c.hoverState=="in"&&c.show()},c.options.delay.show)},
		leave:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);
		this.timeout&&clearTimeout(this.timeout);if(!c.options.delay||!c.options.delay.hide)return c.hide();
		c.hoverState="out",this.timeout=setTimeout(function(){c.hoverState=="out"&&c.hide()},c.options.delay.hide)},
		show:function(){var a,b,c,d,e,f,g;if(this.hasContent()&&this.enabled){a=this.tip(),
		this.setContent(),this.options.animation&&a.addClass("fade"),
		f=typeof this.options.placement=="function"?this.options.placement.call(this,a[0],this.$element[0]):this.options.placement,
		b=/in/.test(f),a.detach().css({top:100,left:0,display:"block"}).insertAfter(this.$element),c=this.getPosition(b),
		d=a[0].offsetWidth,e=a[0].offsetHeight;switch(b?f.split(" ")[1]:f){case"bottom":g={top:c.top+c.height,left:c.left+c.width/2-d/2};break;
		case"top":g={top:c.top-e,left:c.left+c.width/2-d/2};break;case"left":g={top:c.top+c.height/2-e/2,left:c.left-d};
		break;case"right":g={top:c.top+c.height/2-e/2,left:c.left+c.width}}a.offset(g).addClass(f).addClass("in")}},
		setContent:function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),
		a.removeClass("fade in top bottom left right")},
		hide:function(){function d(){var b=setTimeout(function(){c.off(a.support.transition.end).detach()},500);
		c.one(a.support.transition.end,function(){clearTimeout(b),c.detach()})}var b=this,c=this.tip();return c.removeClass("in"),
		a.support.transition&&this.$tip.hasClass("fade")?d():c.detach(),this},
		fixTitle:function(){var a=this.$element;
		(a.attr("title")||typeof a.attr("data-original-title")!="string")&&a.attr("data-original-title",a.attr("title")||"").removeAttr("title")},
		hasContent:function(){return this.getTitle()},getPosition:function(b){return a.extend({},b?{top:0,left:0}:this.$element.offset(),
		{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight})},
		getTitle:function(){var a,b=this.$element,c=this.options;
		return a=b.attr("data-original-title")||(typeof c.title=="function"?c.title.call(b[0]):c.title),a},
		tip:function(){return this.$tip=this.$tip||a(this.options.template)},validate:function(){this.$element[0].parentNode||(this.hide(),this.$element=null,
		this.options=null)},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled},
		toggle:function(b){var c=a(b.currentTarget)[this.type](this._options).data(this.type);c[c.tip().hasClass("in")?"hide":"show"]()},
		destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}},
		a.fn.tooltip=function(c){return this.each(function(){var d=a(this),e=d.data("tooltip"),f=typeof c=="object"&&c;e||d.data("tooltip",e=new b(this,f)),
		typeof c=="string"&&e[c]()})},a.fn.tooltip.Constructor=b,a.fn.tooltip.defaults={animation:!0,placement:"top",
		selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
		trigger:"hover",title:"",delay:0,html:!1}}(window.jQuery)

jQuery(document).ready(function($) {
    $('.bbcode').tooltip();
    $('.prompt').tooltip();
    $('.bar-container').tooltip();
    $('.user_bar_option').tooltip();
    $('.user_mInfo_group').tooltip();
    $('.hint').tooltip();
});