$(document).ready(function () {
    var flag = false;
    var valid = 0;

    $(".register").hide();
    
    $('#register').click(function () { 
        var username = $('#username-register').val();

        if (username.length > 15 || username.length < 8)
        {
            $('.user').removeAttr('style');
        }
        else
        {
            $('.user').css('display', 'none');
            var password = $('#password-register').val();

            data = {
                password: password,
                username: username,
                action: 'register'
            }
            console.log(data);
            CallAjax('ws.php', data, 'POST', 'JSON', RegisterSuccess, Error)
        }
    });

    $("#login").click(function (e) { 
        var username = $('#username-login').val();
        var password = $('#password-login').val();

        data = {
            password: password,
            username: username,
            action: 'login'
        }
        
        CallAjax('../utility/ws.php', data, 'POST', 'JSON', LoginSuccess, Error)
    });

    $("#add-user").click(function () { 
        var username = $('#username-add').val();
        var password = $('#password-add').val();
        var role = $('#roles').val();

        data = {
            password: password,
            username: username,
            role: role,
            action: 'add-user'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', AddSuccess, Error)
    });

    function RegisterSuccess(response)
    {
        console.log(response.status);
        console.log(response.error);
    }

    function LoginSuccess(response) 
    {
        if (response.redirect)
        {
            window.location.replace(response.redirect);
        }

        if (!response.error)
        {
            $(".display-1").html(`Welcome, ${response.username}`);
        }
    }

    function AddSuccess(response)
    {
        console.log(response.status);
    }

    function Error(response)
    {
        console.log(response.error);
    }

    // $('#password-register').blur(function (e) { 
    //     e.preventDefault();
    //     $('.pass').css('display', 'none');
    // });

    $('#password-register').focus(function (e) { 
        e.preventDefault();
        if (flag) {
            $('.pass').removeAttr('style');
        }
    });

    $('#password-register').keyup(function () { 
        flag = true;
        var password = $(this).val();
        var numbers = /[0-9]/g;
        var upperCaseLetters = /[A-Z]/g;
        var lowerCaseLetters = /[a-z]/g;
        var special = /[>>!@^*#$%&?()~"<<]/g;

        if (valid == 5)
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
            valid++;
        }
        else
        {
            $('#pass-special').css('color', 'red');
        }

        if (password.match(upperCaseLetters))
        {
            $('#pass-upper').css('color', 'green');
            valid++;
        }
        else
        {
            $('#pass-upper').css('color', 'red');
        }

        if (password.match(lowerCaseLetters))
        {
            $('#pass-lower').css('color', 'green');
            valid++;
        }
        else
        {
            $('#pass-lower').css('color', 'red');
        }

        if (password.match(numbers))
        {
            $('#pass-number').css('color', 'green');
            valid++;
        }
        else
        {
            $('#pass-number').css('color', 'red');
        }

        if (password.length < 8)
        {
            $('#pass-length').css('color', 'red');
        }
        else
        {
            $('#pass-length').css('color', 'green');
            valid++;
        }
    });

    $('#show-pass-login').click(function (e) { 
        e.preventDefault();
        if ($('#password-login').attr('type') == 'text')
        {
            $('#password-login').attr('type', 'password');
            $('i').addClass( "fa-eye-slash" );
            $('i').removeClass( "fa-eye" );
        }
        else if ($('#password-login').attr('type') == 'password')
        {
            $('#password-login').attr('type', 'text');
            $('i').removeClass( "fa-eye-slash");
            $('i').addClass( "fa-eye" );
        }
    });

    $('#show-pass-add').click(function (e) { 
        e.preventDefault();
        if ($('#password-add').attr('type') == 'text')
        {
            $('#password-add').attr('type', 'password');
            $('i').addClass( "fa-eye-slash" );
            $('i').removeClass( "fa-eye" );
        }
        else if ($('#password-add').attr('type') == 'password')
        {
            $('#password-add').attr('type', 'text');
            $('i').removeClass( "fa-eye-slash");
            $('i').addClass( "fa-eye" );
        }
    });

    $('#show-pass-register').click(function (e) { 
        e.preventDefault();
        if ($('#password-register').attr('type') == 'text')
        {
            $('#password-register').attr('type', 'password');
            $('i').addClass( "fa-eye-slash" );
            $('i').removeClass( "fa-eye" );
        }
        else if ($('#password-register').attr('type') == 'password')
        {
            $('#password-register').attr('type', 'text');
            $('i').removeClass( "fa-eye-slash");
            $('i').addClass( "fa-eye" );
        }
    });

    $('#register-btn').click(function (e) { 
        $(".register").show();
        $(".login").hide();
    });

    $('#login-btn').click(function (e) { 
        $(".register").hide();
        $(".login").show();
    });

    function RetrieveError(xhr, textStatus, errorThrown) 
    {
        console.error("AJAX error:", textStatus, errorThrown);
    }

    function CallAjax(url, reqData, type, dataType, fxnSuccess, fxnError) {
        let ajaxOptions = {
            url: url,
            data: reqData,
            type: type,
            dataType: dataType
        };

        // Initiate the AJAX call
        let con = $.ajax(ajaxOptions);

        // Handle AJAX success and failure
        con.done(fxnSuccess);
        con.fail(fxnError);
    }
});