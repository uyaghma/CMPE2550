$(document).ready(function () {
    var currentUser = $('#currentUser').html();

    GetUserInfo(currentUser);
    let roleID;

    Retrieve("");

    $(document).on('click', '#add-filter', function () {
        var filter = $('#filter-add').val();
        console.log(filter);
        Retrieve(filter);
    });

    $(document).on('click', '#add-message', function () {
        var message = $('#message-add').val();
        console.log(message);
        if (message != "") 
        {
            AddMessage(message, currentUser);
        }
        else
        {
            $('#mess-status').html("You must enter a valid message to add!");
            $('.mess-status').removeAttr('style');
            $('.mess-status').effect('shake');
        }
    });

    $('#message-add').focus(function (e) {
        $('.mess-status').css('display', "none");
    });

    $(document).on('click', '.delete-message', function () {
        var id = $(this).attr('id');
        Delete(id);
    });

    $(document).on('click', '.edit-message', function () {
        var id = $(this).attr('id');
        console.log("Edit button clicked. ID:", id);
        var messageCell = $(`#${id}.message-cell`);
        console.log("Message cell:", messageCell);
        var message = messageCell.text();
        console.log("Message content:", message);
        messageCell.html(`<div class='form-group' id="${id}">
                            <div class='input-group' style="padding-bottom: 5px; vertical-align: middle;">
                                <input type='text' name='message' id='${id}' class='form-control message-edit' value='${message}' style="width: 50%;">
                                <div class='input-group-append'>
                                    <a type='submit' class='btn btn-primary-outline update-message' id='${id}'>Update</a>
                                </div>
                            </div>
                            <div class="update-status" id="${id}" style="display: none; padding-bottom: 0px;">
                                <small id="update-status"></small><br>
                            </div>
                        </div>`);
    });

    $(document).on('click', '.update-message', function () {
        var id = $(this).attr('id');
        var messageInput = $(`#${id}.message-edit`);
        console.log(messageInput);
        var messageCell = $(`#${id}.message-cell`);
        console.log(messageCell);
        var message = messageInput.val();
        console.log(message);
        if (message != "") {
            messageCell.html(`${message}`);
            Update(id, message);
        }
        else
        {
            var updateStatus = $(`#${id}.update-status`);
            updateStatus.html("Your updated message must be longer than 1 character!");
            updateStatus.removeAttr('style');
            $(`#${id}.message-edit`).focus(function () {
                $('.update-status').css('display', "none");
            });
        }
    });

    function GetUserInfo(currentUser) {
        var data = {};
        data.username = currentUser;
        data.action = 'getinfo';

        var options = {};
        options["method"] = "POST";
        options["url"] = "../utility/ws.php";
        options["dataType"] = "json";
        options["data"] = data;
        options["success"] = InfoSuccess;
        options["error"] = Error;
        $.ajax(options);
    }

    function Retrieve(filter) {
        var data = {};
        data.role = roleID;

        var options = {};
        options["method"] = "GET";
        options["url"] = "../utility/FetchMessages/" + filter;
        options["dataType"] = "json";
        options["data"] = data;
        options["success"] = Success;
        options["error"] = Error;
        $.ajax(options);
    }

    function AddMessage(message, currentUser) {
        var data = {};
        data.message = message;
        data.username = currentUser;

        var options = {};
        options["method"] = "POST";
        options["url"] = "../utility/FetchMessages";
        options["dataType"] = "json";
        options["data"] = data;
        options["success"] = Success;
        options["error"] = Error;
        $.ajax(options);
    }

    function Delete(id) {
        var data = {};
        data.username = currentUser;

        var options = {};
        options["method"] = "DELETE";
        options["url"] = "../utility/FetchMessages/delete/" + id;
        options["dataType"] = "json";
        options["data"] = data;
        options["success"] = Success;
        options["error"] = Error;
        $.ajax(options);
    }

    function Update(id, message) {
        var data = {};
        data.message = message;
        data.username = currentUser;

        var options = {};
        options["method"] = "PUT";
        options["url"] = "../utility/FetchMessages/update/" + id;
        options["dataType"] = "json";
        options["data"] = data;
        options["success"] = Success;
        options["error"] = Error;
        $.ajax(options);
    }

    function InfoSuccess(response)
    {
        roleID = response.role;
    }

    function Success(response) {
        var message = $('#message-add');
        message.val("");
        console.log(response);
        rawData = JSON.parse(response);
        console.log(rawData);
        data = rawData.data;
        console.log(data);
        var table = "<table class='table'>" +
            "<thead>" +
            "<tr>" +
            "<th>Action</th>" +
            "<th>MessageID</th>" +
            "<th>User</th>" +
            "<th>Message</th>" +
            "<th>Timestamp</th>" +
            "</tr>" +
            "</thead>" +
            "<tbody>";
        for (var i = 0; i < data.length; i++) {
            table += "<tr>";
            if (data[i]['role'] >= roleID) {
                table += `<td class='action-cell' id='${data[i]['message_id']}'><a id='${data[i]['message_id']}' class='btn btn-primary rounded-pill delete-message' role='button'>Delete</a>` +
                `<a id='${data[i]['message_id']}' class='btn btn-primary rounded-pill edit-message' role='button'>Edit</a></td>`;
            }
            else {
                table += `<td class='action-cell' id='${data[i]['message_id']}'>N/A</td>`
            }
            table += `<td class='mid-cell' id='${data[i]['message_id']}'>${data[i]['message_id']}</td>` +
                `<td class='name-cell' id='${data[i]['message_id']}'>${data[i]['name']}</td>` +
                `<td class='message-cell' id='${data[i]['message_id']}'>${data[i]['message']}</td>` +
                `<td class='timestamp-cell' id='${data[i]['message_id']}'>${data[i]['timestamp']}</td>` +
                "</tr>";
        }
        table += `</tbody></table>`;

        $('.table-container').html(table);
        $('.status-out').html(rawData.status);
    }

    function Error(response) {

    }
});