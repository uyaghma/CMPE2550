<?php 
    error_log("Inside Server file");
    
    // Validation
    if(isset($_GET['action']))
    {
        //sanitize input data
        $action= strip_tags( trim( $_GET['action'])); // cleaning the data

        if($action=="string")
        {
            if(isset($_GET['name']))
            {
                $name = strip_tags (trim( $_GET['name'] ));  // cleaning the data
            }
            else
            {
                $name= "Simran";
            }

            echo "Successfull in handling first Ajax call ". $name;
        }

        if($action=="array")
        {
            $userName= array();

            $userName['User1']= strip_tags (trim( $_GET['name'] ));  // cleaning the data
            $userName['User2']= "Smith";

            // json_encode()- is used to encode any value to JSON format
            $myJSON = json_encode($userName);

            echo $myJSON;
        }

        if($action=="newGame")
        {
            // score
            $score= 1;

            // Player Names
            $userName= array();

            $userName['User1']= strip_tags (trim( $_GET['name'] ));  // cleaning the data
            $userName['User2']= "Smith";

            // froming an object with other properties

            $data =(object) [
                'response' => "NewGame",
                'score' => $score,
                'PlayerNames'=> $userName
            ]; 
            
            // json_encode()- is used to encode any value to JSON format
            $myJSON = json_encode($data);

            echo $myJSON;
        }

        
    }
    else
    {
        $data =(object) [
            'response' => "Wrongcall",
            'errMessage'=> "Who are you ? Not a valid call"
        ]; 
        
        // json_encode()- is used to encode any value to JSON format
        $myJSON = json_encode($data);

        echo $myJSON;
    }
  

?>
