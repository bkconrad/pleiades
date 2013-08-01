$(function() {
    $('pre.submission').each(addCodeButtons);
});

function addCodeButtons() {
    var $selectAll = $('<a href="javascript:return false;" class="codeButton">').text('Select All');
    var $expand = $('<a href="javascript:return false;" class="codeButton">').text('Shrink');
    var $wrapper = $(this).parent();
    $buttonDiv = $('<div class="buttonDiv"></div>');

    $buttonDiv.append($selectAll);
    $buttonDiv.append($expand);
    $wrapper.prepend($buttonDiv);

    $selectAll.click(function () {
        select_text($wrapper.find('pre').get(0));
    });

    $expand.click(function () {
        if(toggle_expansion($wrapper.find('pre'))) {
            $(this).text('Shrink');
        } else {
            $(this).text('Expand');
        }
    });
}

// returns true when the element's state is expanded
function toggle_expansion(e) {
    console.log($(e).css('max-height'));
    if($(e).css('max-height') != '400px') {
        $(e).css('max-height', '400px');
        return false;
    } else {
        $(e).css('max-height', 'none');
        return true;
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
