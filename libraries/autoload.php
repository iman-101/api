<?php
function load(string $clase){
    global $classmap;
    
    foreach ($classmap as $directorio){
        
        $ruta = "$directorio/$clase.php";
        
        if(is_readable($ruta)){
            require_once $ruta;
            break;
        }
    }
}

spl_autoload_register("load");