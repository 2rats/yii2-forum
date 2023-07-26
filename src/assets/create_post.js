$('.reply-button').on('click', function (e) {
    e.preventDefault();

    $('.post-add .reply').fadeIn();

    let post_id = $(this).data('post');
    let post = $(this).closest('.post');
    $('#postform-fk_parent').val(post_id);
    $('.post-add .reply .author').text(post.find('.author').text());
    $('.post-add .reply .content').text(post.find('.content').text());

    $([document.documentElement, document.body]).animate({
        scrollTop: $(".post-add").offset().top - 10
    }, 250);
});

$('.reply-remove').on('click', function (e) {
    e.preventDefault();

    $('.post-add .reply').fadeOut();

    $('#postform-fk_parent').val(null);
});