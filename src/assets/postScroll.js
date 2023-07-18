$(document).ready(function () {
    setTimeout(() => {
        let urlParams = new URLSearchParams(window.location.search);
        let post_id = urlParams.get('post_id');
        if (post_id !== null) {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#post-" + post_id).offset().top - 10
            }, 250);
            setTimeout(() => {
                $("#post-" + post_id).addClass('shadow-pulse');
            }, 1000);

            // remove post_id from URl
            let deleteRegex = new RegExp('post_id=');
            let params = location.search.slice(1).split('&')
            let search = []
            for (let i = 0; i < params.length; i++) if (deleteRegex.test(params[i]) === false) search.push(params[i])
            window.history.replaceState({}, document.title, location.pathname + (search.length ? '?' + search.join('&') : '') + location.hash)
        }
    }, 500);
});