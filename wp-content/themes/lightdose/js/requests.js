jQuery(document).ready(function($) {

    $('form#login').on('submit', function(e) {
        var login = $('form#login #user_login').val(), password = $('form#login #user_pass').val();
        if (login == '' || password == '') {
            return false;
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax.url,
            data: {
                'action': 'ajaxlogin',
                'username': login,
                'password': password,
                'security': $('form#login #security').val()
            },
            success: function(response) {
                if (response.success == true) {
                    document.location.href = ajax.referer;
                } else {
                    $('.notification').html(response.error);
                }
            }
        });
        e.preventDefault();
    });

    $('form#register').on('submit', function(e) {
        var user_name = $('form#register #user_name').val(), user_email = $('form#register #user_email').val();
        if (user_name == '' || user_email == '') {
            return false;
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax.url,
            data: {
                'action': 'registration',
                'user_name': user_name,
                'user_email': user_email
            },
            success: function(response) {
                $('.notification').html(response.message);
            }
        });
        e.preventDefault();
    });

    $('.blog .load-more button').click(function() {
        var timestamp = [], language = {}, args = {
            action: 'get_last_post'
        };
        Array.min = function(array) {
            return Math.min.apply(Math, array);
        };
        if (typeof icl_vars !== 'undefined') {
            language = {
                current_language: icl_vars.current_language
            };
        }
        $('body article').each(function(index) {
            var ts = $(this).attr('id').split('-');
            timestamp.push(ts[2]);
        });

        $.post(ajax.url, $.extend(args, {timestamp: timestamp}, language), function(response) {
            if (Array.min(timestamp) == response.ts) {
                return false;
            }
            if (response.success == true) {
                var post = $(response.html);
                $('.col-xs-12 .post:last').after(post);
                post.hide().slideDown();
            }
        }, "json");
    });

    $('#feedback').submit(function() {
        $.post(ajax.url, $(this).serialize(), function(data) {
            if (data.success === false) {
                $('.notification').html(data.message).css({'color': '#fa4c3a'});
            } else {
                $('.notification').html(data.message).animate({opacity: 1}, 500, function() {
                    $("#feedback").trigger('reset');
                }).css({'color': '#fff'});
            }
        }, "json");
        return false;
    });

});