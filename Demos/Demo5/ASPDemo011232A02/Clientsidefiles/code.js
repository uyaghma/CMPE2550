$().ready(()=>{

    console.log("On Load");
    $("#postSubmit").click(PostSubmit);

});

function PostSubmit()
{
    console.log("In side PostSubmit");

    let url="https://localhost:7092/registerPOST";

    let data ={};

    data.postFirst= $("#postFirst").val();
    data.postColor=$("#postColor").val();
    data.postAge=$("#postAge").val();

    console.log(data);

    // make AJAX call here
    // Uncomment the following to test it for HTML 
    //AJAX(url,"post", data, "html", ProcessSucccess, ProcessError);

    // Uncomment the following to test it for JSON
    AJAX(url,"post", data, "JSON", ProcessSucccess, ProcessError);
}

function AJAX (url, method, data, dataType, successMethod, errorMethod)
{
    let ajaxOptions={};
    ajaxOptions['url']=url;
    ajaxOptions['method']= method;
    ajaxOptions['data']= JSON.stringify(data);  // NEW for C#
    ajaxOptions['dataType']= dataType;
    ajaxOptions['success']=successMethod;
    ajaxOptions['error']=errorMethod;
    ajaxOptions['contentType']="application/json";  // NEW for C#

    console.log(ajaxOptions);
    $.ajax(ajaxOptions);
}
function ProcessSucccess(returnedData, status)
{
    console.log("Successful AJAX call");
    console.log(returnedData);
    console.log(status);
}
function ProcessError(request, error, messasge)
{
    console.log("AJAX call Error");
    console.log(error);
    console.log(messasge);
} 