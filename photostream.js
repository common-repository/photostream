window.addEvent('domready', function() {			

	function visible(c) { $$('#photostream a img').each(function(e) { (e.hasClass(c)) ?  e.addClass('visible') : e.removeClass('visible'); }); }
	$$('#photostream a img').each(function(el) {el.addEvent('mouseenter', function() {visible(el.get('class'));} );	});

	// first album visible by default
	visible($$('#photostream a img')[0].get('class')) ;
});