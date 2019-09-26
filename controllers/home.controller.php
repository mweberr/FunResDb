<?php

session_start();


class HomeController {
    public function renderPage(){
	
            include "views/_header.php";
            include "views/home.php";
            include "views/_footer.php";
	
    }
}