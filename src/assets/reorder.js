$(function() {
    $('.category-sortable').sortable({
        animation: 150,
        fallbackOnBody: true,
        fallbackTolerance: 3,
        swapThreshold: 0.65,
    })

    $('.forum-sortable').each(function() {
        $(this).sortable({
            group: 'shared',
            multiDrag: true,
            animation: 150,
            fallbackTolerance: 3,
            fallbackOnBody: true,
            swapThreshold: 0.65,
        })
    })

    $('.show-button, .hide-button').on('click', function() {
        const $this = $(this)

        $this.toggle()
        $this.siblings().toggle()
        $this.closest('.row').find('.forum-sortable').toggle()
    })

    $('#save').click(function() {
        const sorted = []
        const cats = $('.category-sortable').sortable('toArray')

        $('.forum-sortable').each(function(index) {
            sorted.push({
                category: cats[index],
                forums: $(this).sortable('toArray'),
            })
        })
        const csrf = $('meta[name="csrf-token"]').attr('content')

        $.ajax({
            type: 'POST',
            url: window.location.href, // Same URL as the current page
            data: { data: JSON.stringify(sorted) },
            headers: {
                'X-CSRF-Token': csrf, // Include CSRF token in headers
            },
            success: function(response) {
                $('.alert-success').fadeIn()
                setTimeout(function() {
                    $('.alert-success').fadeOut('fast')
                }, 2000)
            },
            error: function(error) {
                $('.alert-danger').fadeIn()
                setTimeout(function() {
                    $('.alert-danger').fadeOut('fast')
                }, 2000)
            },
        })
    })
})
