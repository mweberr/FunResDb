<?php



require_once "align/BioSequence.php";

require_once "config.php";
require_once "database/DBFactory.php";
require_once "database/MessageFactory.php";
// Establish database connection
DBFactory::init($db_ids);

//require_once "Functions_PDO.php";
require_once "align/functions_align.php";
require_once "align/functions_emboss.php";
require_once 'align/AlignError.php';
$align = AlignError::getInstance();
require_once "connect.php";
//require_once "controllers/search.controller.php";
//require_once "controllers/home.controller.php";
//require_once "controllers/QnA.controller.php";
//require_once "controllers/position.controller.php";
//require_once "controllers/message.controller.php";

//require_once "models/position.model.php";
//require_once "models/search.model.php";
require_once "functions/functions_report.php";
require_once "functions/functions_misc.php";
require_once "functions/functions_format.php";
require_once "database/LogInputDao.php";

// Database access objects
require_once "database/PositionDao.php";
require_once "database/MessageDao.php";
require_once "database/MutantDao.php";


?>