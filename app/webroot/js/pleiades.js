$(function() {
    $('pre.submission').each(addCodeButtons);
    $('.submission.levelcode').each(colorTeamNames);
});

function getColoredSpan(name, r, g, b) {
    console.log(arguments);

    r = parseFloat(r);
    g = parseFloat(g);
    b = parseFloat(b);

    var average = (r + g + b) / 3;
    var background = "#" +
        ('00' + Math.floor(r * 255).toString(16)).slice(-2) +
        ('00' + Math.floor(g * 255).toString(16)).slice(-2) + 
        ('00' + Math.floor(b * 255).toString(16)).slice(-2);
    var color = average > .5 ? '#000000' : '#FFFFFF';

    return '<span class="team-color" style="background: ' + background + '; border: 1px solid ' + color + ';">&nbsp;</span> ' + name;
}

function colorTeamNames() {
    var teamPattern = /Team\s+(\w+)\s+([0-9.]+)\s+([0-9.]+)\s+([0-9.]+)/;
    var text = $(this).text();
    var newText;
    var teamMatch = teamPattern.exec(text);
    var replacement;
    var teamName;
    var parts;
    var map = [];
    var k;
    var r, g, b;
    var colorCode;
    while(teamMatch) {
        console.log(teamMatch);

        teamName = teamMatch[1];
        r = teamMatch[2];
        g = teamMatch[3];
        b = teamMatch[4];
        span = getColoredSpan(teamName, r, g, b);

        replacement = ['Team',span, r, g, b].join(' ');
        map.push([teamMatch[0], replacement]);

        text = text.replace(teamMatch[0], '', 1);
        teamMatch = teamPattern.exec(text);
    }

    newText = $(this).text();
    for (k in map) {
        newText = newText.replace(map[k][0], map[k][1]);
    }
    $(this).html(newText);
}

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
