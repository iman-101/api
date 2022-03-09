<?php
    
    header("Access-Control-Allow-origin: *");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
    
    
    include '../config/config.php';
    include '../libraries/autoload.php';
    
    (new FrontController())->main();