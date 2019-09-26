
<?php 
    session_start();

// Read the message if available
//$fn = 'Mail_message.txt';
//$messageTxt = '';
//if(file_exists($fn)){
//    $messageTxt = file_get_contents($fn);
//}

$error_states = array("name"=>false,"email"=>false,"message"=>false);
$error_note = "\n";

if($_SERVER["REQUEST_METHOD"] == "POST") {
         
    $name = check_input($_POST["name"]);
    $email = check_input($_POST["email"]);
    $message = check_input($_POST["message"]);
    
//  Validate the input of the form
    if (empty($name)) {
        $error_states["name"] = true;
        $error_note .= "No name \n";
    }
// Check if email has been entered and is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_states["email"] = true;
        $error_note .= " No email \n";
    }
//Check if message has been entered
    if (empty($message)) {
        $error_states["message"] = true;
        $error_note .= " No message \n";
    }
    
    
    $data = array("name"=> $name,"email"=> $email,"session_id"=>session_id(),"message"=> $message,"report"=>null);
    if(!empty($_SESSION["report"])){
      $data["report"] = $_SESSION["report"];
    }
    
    $dbconnect = DBFactory::getConnection();
    $msgDao = new MessageDao($dbconnect);
    $msgDao->addRow($data);
    
    if(!$error_states["name"] & !$error_states["email"] & !$error_states["message"] ){
        $result = '<div class="alert alert-success">Thank you for your message !</div>';
    }else{
        $result = '<div class="alert alert-danger"> Error: Message was not sent. '.nl2br($error_note).'</div>';
    }
    echo $result;
}

function check_input($data){
//  Entfernt Whitespaces (oder andere Zeichen) am Anfang und Ende eines Strings  
    $data = trim($data);
//   Entfernt Maskierungszeichen aus einem String  
    $data = stripslashes($data);
//    Wandelt Sonderzeichen in HTML-Codes um
    $data = htmlspecialchars($data);
    return $data;
}


?>



<div class="container msgpane">
    <form action="?site=message" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title"> Send a message to NRZMyk </h2>
            </div>
            <div class="panel-body">    
                <div class="form-group">
                    <label for="iname">Name: </label> 
                    <input type="text" class="form-control" id="iname" name="name" placeholder=""><br>
                </div>
                <div class="form-group">
                    <label for="imail">Email: </label>
                    <input type="text" class="form-control" id="imail" name="email" placeholder="">
                </div>  
                <div class="checkbox">
                    <label><input type="checkbox" value="">Attach Alignment report</label>
                </div>
                
                <div class="form-group">
                    <label for="iarea"> Message: </label>
                    
                    <textarea class="form-control" rows="10" cols="30" id="iarea" name="message">  </textarea>
                </div>
                <button type="submit" class="btn btn-default" value="submit_mail"> Send Email </button>
                <div class="form-group">

                </div>
            </div>
        </div>   
    </form>
</div>
        