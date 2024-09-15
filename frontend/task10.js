$(document).ready(function () {
    $('.btn .badge').each(function () {
        $(this).text((Math.random() * 100).toFixed(0));
    });

    $('.btn').click(function () {
        let badge = $(this).find('.badge');
        badge.text(+badge.text() + 1);
    });
});