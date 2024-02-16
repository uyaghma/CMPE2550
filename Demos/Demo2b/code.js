$(document).ready(()=>
{
   console.log("On page load");
    // On page load stuff will go here

    // Onclick event for button with id b1
    $("#b1").on("click", ()=>{
        console.log("Button 1 has been clicked");
        let data={};

        data['action']= "string";
        CallAjax("ws.php", data, "GET", "HTML", Success, Error);
    });

    $("#b2").on("click", ()=>{
        console.log("Button 2 has been clicked");

        let data={};

        data['action']= "string";
        data['name']="Harsimranjot";

        CallAjax("ws.php", data, "GET", "HTML", Success, Error);
    });

    $("#b3").on("click", ()=>{
        console.log("Button 3 has been clicked");

        let data={};

        data['action']= "array";
        data['name']="Harsimranjot";

        CallAjax("ws.php", data, "GET", "JSON", Success, Error);
    });

    $("#b4").on("click", ()=>{
        console.log("Button 4 has been clicked");

        let data={};

        data['action']= "newGame";
        data['name']="Harsimranjot";

        CallAjax("ws.php", data, "GET", "JSON", Success, Error);
    });

    $("#b5").on("click", ()=>{
        console.log("Button 5 has been clicked");

        let data={};

        data['action1']= "newGame";
        data['name']="Harsimranjot";

        CallAjax("ws.php", data, "GET", "JSON", Success, Error);
    });
    
    

    
}
);
function Success(ajaxData, responseStatus)
{
    console.log(ajaxData);
    $("section").html(ajaxData);
    
    if(ajaxData['User1'] && ajaxData['User2'])
    {
        $("section").html("UserName 1 : "+ ajaxData['User1'] );
    }
    
}
function Error(ajaxReq, ajaxStatus, errorThrown)
{
    console.log(ajaxReq +" : "+ ajaxStatus +" : "+ errorThrown);
}

function CallAjax(url, reqData, type, dataType, fxnSuccess, fxnError)
{
    let ajaxOptions={};

    ajaxOptions['url']=url;
    ajaxOptions['data']= reqData;
    ajaxOptions['type']= type;
    ajaxOptions['dataType']= dataType;
    
    //ajaxOptions['success']= fxnSuccess;
    //ajaxOptions['error']=fxnError;
    
    // Making Ajax call
    //$.ajax(ajaxOptions);

    //Alternative 1

    let con= $.ajax(ajaxOptions);

    con.done(fxnSuccess);
    con.fail(fxnError);
    //con.always();  // Include your function which you want to call everytime
}

