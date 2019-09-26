<?php

require_once "main.php";

try {
    if (empty($_GET)) {
        require_once "controllers/home.controller.php";
        $c = new HomeController();
        
    } elseif ($_GET['site'] == 'search') {
        require_once "controllers/search.controller.php";
        $c = new SearchController();
        
    } elseif ($_GET['site'] == 'QnA') {

        require_once "controllers/QnA.controller.php";
        $c = new QnAController();
        
    } elseif ($_GET['site'] == 'positions') {
        
        require_once "controllers/position.controller.php";
        
        $c = new PositionController();
        
    } elseif ($_GET['site'] == 'message') {
        
        require_once "controllers/message.controller.php";
        
        $c = new MessageController();
        
    } else
        throw new Exception('Wrong page!');

    $c->renderPage();
    
} catch (Exception $e) {
    // Display the error page using the "render()" helper function:
//	render('error',array('message'=>$e->getMessage()));
    echo $e->getMessage();
}
?>