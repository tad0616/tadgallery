/*
 * jQuery Shuffle Plugin
 * Examples and documentation at: http://
 *
 * @author: Benjamin Sterling
 * @version: 1.0
 * @requires jQuery v1.1.3.1 or later
 *
 * @headsTip: 	My thanks to M. Alsup for taking what I created and making it cleaner
 *				which in turn I "borrowed" to make jqShuffle into a better plugin that
 *				is more flexible
 */
(function($) {
	$.fn.jqShuffle = function(options){
		return this.each(function(){
			var $this = $(this), $els = $this.children().css({left:0,top:0}), els = $els.get();
			if (els.length < 2) return; // don't bother
			var opts = $.extend({}, $.fn.jqShuffle.defaults, options);

			// allow shorthand overrides of width, height and timeout
			var cls = this.className;
			var w = parseInt((cls.match(/w:(\d+)/)||[])[1]) || opts.width
			var h = parseInt((cls.match(/h:(\d+)/)||[])[1]) || opts.height;
			if ($this.css('position') == 'static') $this.css('position', 'relative');
			if (w) $this.width(w);
			if (h && h != 'auto') $this.height(h);
			opts.timeout = parseInt((cls.match(/t:(\d+)/)||[])[1]) || opts.timeout;
			$els.each(function(i){$(this).css('z-index', els.length-i);}).css('position','absolute');

            opts.coords = opts.coords || {left:-$this.width(), top:15};
            opts.els = [];
            for (var i=0; i < els.length; i++)
                opts.els.push(els[i]);

			opts.curr = opts.random ? (Math.floor(Math.random() * (els.length-1)))+1 : 1;
			opts.last = 0;
			$els.each(function(i){
				$(this).click(function(){
					$.fn.jqShuffle.go(els, opts);
				});
			});
			
			if(opts.auto)
				this.cycleTimeout = setTimeout(function(){$.fn.jqShuffle.go(els, opts)}, opts.timeout + (opts.delay||0));
		});
	};
	
	$.fn.jqShuffle.go = function(els, opts){
		var last = els[opts.last], curr = els[opts.curr];
		$.fn.jqShuffle[opts.fx](last,curr,opts);
		var roll = (opts.curr + 1) == els.length;
		opts.curr = roll ? 0 : opts.curr+1;
		opts.last = roll ? els.length-1 : opts.curr-1;
		if(opts.auto)
			setTimeout(function() { $.fn.jqShuffle.go(els, opts) }, opts.timeout);
	};

	$.fn.jqShuffle.shuffleLite = function(last, curr, opts, cb) {
		var $el = $(last);
		$el.animate(opts.coords, opts.speed, opts.easing, function() {
			opts.els.push(opts.els.shift());
			for (var i=0, len=opts.els.length; i < len; i++)
				$(opts.els[i]).css('z-index', len-i);
			$el.animate({left:0, top:0}, opts.speedOut, opts.easeOut, cb);
		});
	};

	$.fn.jqShuffle.defaults = {
		fx		: 'shuffleLite',
		speed	: 1000,
		auto	: 0,	// true for automated, false for not
		random	: 0,		// true for random, false for sequence
		easing	: null,
		coords	: null,
		width	: 208,
		delay	: 0,     // additional delay (in ms) for first transition (hint: can be negative)
		timeout	: 4000  // ms duration for each slide
	};
})(jQuery);
