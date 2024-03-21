$(document).ready(function () {
    var flag = false;
    var valid = false;

    $(".register").hide();
    
    $('#register').click(function () { 
        var username = $('#username-register').val();

        if (username.length > 15 || username.length < 8)
        {
            $('.user').removeAttr('style');
        }
        else if (valid)
        {
            $('.user').css('display', 'none');
            var password = $('#password-register').val();

            data = {
                password: password,
                username: username,
                action: 'register'
            }
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

    $(document).on('click', '.delete', function (e) { 
        var userid = $(this).attr('id');
        var username = $(`#${userid}.username-cell`).html();
        var role = $(this).attr('rid');
        var rolename = $(`#${role}.role-name`).html();

        data = {
            user: username,
            rolename: rolename,
            id: userid,
            role: role,
            action: 'delete'
        }

        CallAjax('../utility/ws.php', data, 'POST', 'JSON', DeleteSuccess, Error);
    });

    $('#goto-index').click(function (e) { 
        window.location.replace('https://thor.cnt.sast.ca/~uyaghma1/CMPE2550_Projects/big_brain_codes/lab02/pages/index.php');
    });

    $(document).on('click', '.update', function (e) { 
        var userid = $(this).attr('id');
        var username = $(`#${userid}.username-cell`).html();
        var role = $(`#${userid}.select-cell`).val();

        data = {
            user: username,
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
            $('#roleName').val('');
            $('#roleDesc').val('');
            $(".status-out").html(response.status);
        }
        else 
        {
            $('#roleName').val('');
            $('#roleDesc').val('');
            $(".status-out").html(response.error);
        }
    }

    function DeleteSuccess(response)
    {
        $('.table-container').html(response.output);
        $('.status-out').html(response.status);
    }

    function UpdateSuccess(response)
    {
        $('.table-container').html(response.output);
        $('.status-out').html(response.status);
    }

    function AddSuccess(response)
    {
        if (!response.error && !response.roleerror && !response.usererror)
        {
            $('.table-container').html(response.output);
            $('.status').css('display', 'none');
            $('.user').css('display', 'none');
            $('.role-status').css('display', 'none');
            $('#username-add').val('');
            $('#password-add').val('');
            $('#roles-add').val('');
            $('.status-out').html(response.status);
        }
        else if (response.usererror)
        {
            $('.status-out').html(response.usererror);
            $('#username-add').val('');
            $('#password-add').val('');
            $('#roles-add').val('default');
        }
        else if (response.roleerror)
        {
            $("#role-status").html(response.roleerror);
            $(".role-status").removeAttr('style');
            $('#username-add').val('');
            $('#password-add').val('');
            $('#roles-add').val('default');
        }
        else if (response.error) {
            $('.status-out').html(response.error);
            $('#username-add').val('');
            $('#password-add').val('');
            $('#roles-add').val('default');
        }
    }

    function RegisterSuccess(response)
    {
        $('#username-register').val('');
        $('#password-register').val('');

        if (response.error) {
            $('.status-out').html(response.error);
        }
        if (response.status) {
            $(".status-out").html(response.status);
            $(".register").hide();
            $(".login").show();
        }
    }

    function LoginSuccess(response) 
    {
        if (response.redirect)
        {
            window.location.replace(response.redirect);
            $(".output").html(`Welcome, ${response.username}`);
        }

        if (response.error)
        {
            $('.status-out').html(response.error);
        }
    }

    function Error(response)
    {
        console.log(response);
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

    $('#username-register').focus(function (e) { 
        $('.user').css('display', 'none');
    });

    $('#password-register').keyup(function () {
        var password = $(this).val();
        var passwordValidity = validatePassword(password);
    
        $('.pass').css('display', 'none');
    
        if (passwordValidity.numValid && passwordValidity.upperValid && passwordValidity.lowerValid && passwordValidity.specialValid && passwordValidity.lengthValid) {
            valid = true;
        } else {
            $('.pass').removeAttr('style');
            valid = false;
        }
    
        $('#pass-special').css('color', passwordValidity.specialValid ? 'green' : 'red');
        $('#pass-upper').css('color', passwordValidity.upperValid ? 'green' : 'red');
        $('#pass-lower').css('color', passwordValidity.lowerValid ? 'green' : 'red');
        $('#pass-number').css('color', passwordValidity.numValid ? 'green' : 'red');
        $('#pass-length').css('color', passwordValidity.lengthValid ? 'green' : 'red');
    });

    $('#password-add').keyup(function () {
        var password = $(this).val();
        var passwordValidity = validatePassword(password);
    
        $('.pass').css('display', 'none');
    
        if (passwordValidity.numValid && passwordValidity.upperValid && passwordValidity.lowerValid && passwordValidity.specialValid && passwordValidity.lengthValid) {
            valid = true;
        } else {
            $('.pass').removeAttr('style');
            valid = false;
        }
    
        $('#pass-special').css('color', passwordValidity.specialValid ? 'green' : 'red');
        $('#pass-upper').css('color', passwordValidity.upperValid ? 'green' : 'red');
        $('#pass-lower').css('color', passwordValidity.lowerValid ? 'green' : 'red');
        $('#pass-number').css('color', passwordValidity.numValid ? 'green' : 'red');
        $('#pass-length').css('color', passwordValidity.lengthValid ? 'green' : 'red');
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
        $('.status-out').html('');
    });

    $('#login-btn').click(function (e) { 
        $(".register").hide();
        $(".login").show();
        $('.status-out').html('');
    });
});

function validatePassword(password) {
    var numValid = /[0-9]/.test(password);
    var upperValid = /[A-Z]/.test(password);
    var lowerValid = /[a-z]/.test(password);
    var specialValid = /[!@^*#$%&?()~]/.test(password); // Update special characters as needed
    var lengthValid = password.length >= 8;

    return {
        numValid: numValid,
        upperValid: upperValid,
        lowerValid: lowerValid,
        specialValid: specialValid,
        lengthValid: lengthValid
    };
}