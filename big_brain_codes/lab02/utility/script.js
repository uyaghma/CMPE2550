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
            CallAjax('../utility/ws.php', data, 'POST', 'JSON', RegisterSuccess, Error)
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
        var role = $('#roles-add').val();

        data = {
            password: password,
            username: username,
            role: role,
            action: 'add-user'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', AddSuccess, Error)
    });

    $('.delete').click(function (e) { 
        var userid = $(this).attr('id');
        var role = $(this).attr('rid');
        console.log(role);

        data = {
            id: userid,
            role: role,
            action: 'delete'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', DeleteSuccess, Error);
    });

    $('.update').click(function (e) { 
        var userid = $(this).attr('id');
        var role = $('#roles').val();

        console.log(userid);
        console.log(role);

        data = {
            id: userid,
            role: role,
            action: 'update'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', UpdateSuccess, Error);
    });

    $('#add-role').click(function (e) { 
        var roleName = $('#roleName').val();
        var desc = $('#roleDesc').val();

        data = {
            roleName: roleName,
            desc: desc,
            action: 'add-role'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', AddRoleSuccess, Error);
    });

    $('#logout').click(function (e) { 
        data = { action: 'logout' }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', LogoutSuccess, Error);
    });

    function LogoutSuccess(response)
    {
        window.location.replace(response.redirect);
    }

    function AddRoleSuccess(response) 
    {
        if (!response.error)
        {
            $('.table-container').html(response.output);
            $('.status').css('display', 'none');
        }
        else 
        {
            $("#status").html(response.error);
            $(".status").removeAttr('style');
        }
    }

    function DeleteSuccess(response)
    {
        $('.table-container').html(response.output);
    }

    function UpdateSuccess(response)
    {
        $('.table-container').html(response.output);
    }

    function AddSuccess(response)
    {
        if (!response.error)
        {
            $('.table-container').html(response.output);
            $('.status').css('display', 'none');
        }
        else 
        {
            $("#status").html(response.error);
            $(".status").removeAttr('style');
        }
    }

    function RegisterSuccess(response)
    {
        $('#username-register').val('');
        $('#password-register').val('');
        $('.pass').css('display', 'none');
        console.log(response.status);
        console.log(response.error);
    }

    function LoginSuccess(response) 
    {
        if (response.redirect)
        {
            window.location.replace(response.redirect);
        }

        if (!response.error && !response.dne)
        {
            $(".output").html(`Welcome, ${response.username}`);
        }
        else
        {
            $('.pass').removeAttr('style');
        }

        if (response.dne)
        {
            $('.user-valid').removeAttr('style');
        }
    }

    function Error(response)
    {
        console.log(response.error);
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

    $('#username-login').focus(function (e) { 
        $('.user-valid').css('display', 'none');
        $('.pass').css('display', 'none');
    });

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
});