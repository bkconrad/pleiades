$(function() {
    $('pre').each(addSelectAll);
});

function addSelectAll() {
    var $el = $('<div class="selectAll">').text('Select All');
    $el.click(function () {
        var range = document.createRange();
        range.selectNode($(this).parent().get()[0]);
        window.getSelection().addRange(range);
    });
    $(this).prepend($el);
}
