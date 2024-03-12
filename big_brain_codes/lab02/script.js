$(document).ready(function () {
    var flag = false;
    var valid = false;
    
    $('#login').click(function () { 
        var username = $('#username').val();

        if (username.length > 15 || username.length < 8)
        {
            $('.user').removeAttr('style');
        }
        else
        {
            $('.user').css('display', 'none');
        }
    });

    $('#password').blur(function (e) { 
        e.preventDefault();
        $('.pass').css('display', 'none');
    });

    $('#password').focus(function (e) { 
        e.preventDefault();
        if (flag) {
            $('.pass').removeAttr('style');
        }
    });

    $('#password').keyup(function () { 
        flag = true;
        var password = $(this).val();
        var numbers = /[0-9]/g;
        var upperCaseLetters = /[A-Z]/g;
        var lowerCaseLetters = /[a-z]/g;
        var special = /[>>!@^*#$%&?()~"<<]/g;

        if (valid)
        {
            $('.pass').css('display', 'none');
        }
        else
        {
            $('.pass').removeAttr('style');
        }

        if (password.match(special))
        {
            $('#pass-special').css('color', 'green');
            valid = true;
        }
        else
        {
            $('#pass-special').css('color', 'red');
            valid = false;
        }

        if (password.match(upperCaseLetters))
        {
            $('#pass-upper').css('color', 'green');
            valid = true;
        }
        else
        {
            $('#pass-upper').css('color', 'red');
            valid = false;
        }

        if (password.match(lowerCaseLetters))
        {
            $('#pass-lower').css('color', 'green');
            valid = true;
        }
        else
        {
            $('#pass-lower').css('color', 'red');
            valid = false;
        }

        if (password.match(numbers))
        {
            $('#pass-number').css('color', 'green');
            valid = true;
        }
        else
        {
            $('#pass-number').css('color', 'red');
            valid = false;
        }

        if (password.length < 8)
        {
            $('#pass-length').css('color', 'red');
            valid = false;
        }
        else
        {
            $('#pass-length').css('color', 'green');
            valid = true;
        }
    });

    $('#show-pass').click(function (e) { 
        e.preventDefault();
        if ($('#password').attr('type') == 'text')
        {
            $('#password').attr('type', 'password');
            $('i').addClass( "fa-eye-slash" );
            $('i').removeClass( "fa-eye" );
        }
        else if ($('#password').attr('type') == 'password')
        {
            $('#password').attr('type', 'text');
            $('i').removeClass( "fa-eye-slash");
            $('i').addClass( "fa-eye" );
        }
    });
});