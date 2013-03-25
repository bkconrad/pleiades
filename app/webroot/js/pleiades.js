$(function() {
    $('pre').each(addSelectAll);
});

function addSelectAll() {
    var content = $(this).text();
    $(this).text('');
    var $el = $('<div class="selectAll">').text('Select All');
    var $wrap = $('<div class="selectTarget">');
    $wrap.text(content);
    $el.css('float', 'right');
    $(this).append($el);
    $(this).append($wrap);
    $el.click(function () {
        select_text($wrap.get(0));
    });
}

function select_text(e)
{
	// Not IE
	if (window.getSelection)
	{
		var s = window.getSelection();
		// Safari
		if (s.setBaseAndExtent)
		{
			s.setBaseAndExtent(e, 0, e, e.innerText.length - 1);
		}
		// Firefox and Opera
		else
		{
			var r = document.createRange();
			r.selectNodeContents(e);
			s.removeAllRanges();
			s.addRange(r);
		}
	}
	// Some older browsers
	else if (document.getSelection)
	{
		var s = document.getSelection();
		var r = document.createRange();
		r.selectNodeContents(e);
		s.removeAllRanges();
		s.addRange(r);
	}
	// IE
	else if (document.selection)
	{
		var r = document.body.createTextRange();
		r.moveToElementText(e);
		r.select();
	}
}
