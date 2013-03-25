$(function() {
    $('pre.submission').each(addCodeButtons);
});

function addCodeButtons() {
    var content = $(this).text();
    $(this).text('');
    var $selectAll = $('<div class="selectAll">').text('Select All');
    var $expand = $('<div class="toggleExpansion">').text('Expand/Contract');
    var $wrap = $('<div class="selectTarget">');
    $wrap.text(content);
    $buttonDiv = $('<div></div>').css('float', 'right');

    $buttonDiv.append($selectAll);
    $buttonDiv.append($expand);
    $(this).append($buttonDiv);
    $(this).append($wrap);

    $selectAll.click(function () {
        select_text($wrap.get(0));
    });
    $expand.click(function () {
        toggle_expansion($wrap.parent().get(0));
    });
}

function toggle_expansion(e) {
    console.log($(e).css('max-height'));
    if($(e).css('max-height') != '400px') {
        $(e).css('max-height', '400px');
        $(e).css('overflow-y', 'auto');
    } else {
        $(e).css('max-height', 'none');
        $(e).css('overflow-y', 'visible');
    }
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
