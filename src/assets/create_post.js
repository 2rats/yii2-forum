$('.reply-button').on('click', function (e) {
    e.preventDefault();

    $('.post-add .reply').fadeIn();

    let post_id = $(this).data('post');
    let post = $(this).closest('.post');
    $('#postform-fk_parent').val(post_id);
    $('.post-add .reply .author').text(post.find('.author').text());
    $('.post-add .reply .content').html(null);
    post.find('.content').children().each(function(i){
        if(i == 4){
            $('.post-add .reply .content').append('...');
            return false;
        }
        $(this).clone().appendTo('.post-add .reply .content');
    })

    $([document.documentElement, document.body]).animate({
        scrollTop: $(".post-add").offset().top - 10
    }, 250);
});

$('.reply-remove').on('click', function (e) {
    e.preventDefault();

    $('.post-add .reply').fadeOut();

    $('#postform-fk_parent').val(null);
});