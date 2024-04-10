$(document).ready(function () {
    url = "https://localhost:7178/retrieve"
    table = `<table class='table table-sm table-light'>
                <thead class='bg-primary'>
                    <tr>
                        <th>Action</th>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>School ID</th>
                    </tr>
                </thead>`
    
    AJAX(url, 'get', "", "JSON", Success, Error);

    $(document).on('click', '.retrieve-info', function (e) { 
        var id = $(this).attr('id');
        var url = "https://localhost:7178/studentinfo?id=" + id;

        AJAX(url, 'get', "", "JSON", InfoSuccess, Error);
    });

    function Success(response) {
        console.log(response.data);
        var data = JSON.parse(response.data);

        table += `<tbody>`;
        for (var i=0; i < data.length; i++) {
            table += "<tr>" 
                + `<td><a id='${data[i]['student_id']}' class='btn btn-primary rounded-lg retrieve-info' role='button'>Retrieve Student Info</a></td>`
                + `<td>${data[i]['student_id']}</td>`
                + `<td>${data[i]['first_name']}</td>`
                + `<td>${data[i]['last_name']}</td>`
                + `<td>${data[i]['school_id']}</td>`
                + `</tr>`
        }
        table += `</tbody></table>`

        $(".retrieve").html(table);
    }

    function InfoSuccess(response) {
        console.log(response.data);
        var data = JSON.parse(response.data);
        var classes = `<table class='table table-sm table-light'>
                        <thead class='bg-primary'>
                            <tr>
                                <th>Class ID</th>
                                <th>Class Desc</th>
                                <th>Days</th>
                                <th>Start Date</th>
                                <th>Instructor ID</th>
                                <th>Instructor First Name</th>
                                <th>Instructor Last Name</th>
                            </tr>
                        </thead>
                        <tbody>`
        for (var i=0; i < data.length; i++) {
            classes += "<tr>"
                + `<td>${data[i]['class_id']}</td>`
                + `<td>${data[i]['class_desc']}</td>`
                + `<td>${data[i]['days'] ?? 0}</td>`
                + `<td>${data[i]['start_date']}</td>`
                + `<td>${data[i]['instructor_id']}</td>`
                + `<td>${data[i]['first_name']}</td>`
                + `<td>${data[i]['last_name']}</td>`
                + `</tr>`
        }
        classes += `</tbody></table>`

        $('.studentinfo').html(classes);
    }

    function AJAX(url, method, data, dataType, successMethod, errorMethod) {
        let ajaxOptions = {};
        ajaxOptions['url'] = url;
        ajaxOptions['method'] = method;
        ajaxOptions['data'] = JSON.stringify(data);  // NEW for C#
        ajaxOptions['dataType'] = dataType;
        ajaxOptions['success'] = successMethod;
        ajaxOptions['error'] = errorMethod;
        ajaxOptions['contentType'] = "application/json";  // NEW for C#
    
        console.log(ajaxOptions);
        $.ajax(ajaxOptions);
    }
});