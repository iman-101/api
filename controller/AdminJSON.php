<?php

class AdminJSON{
    
    public function __construdt(){
        header('Content-type:application/json; charset=utf-8');
    }
    
    public function delete(){
        DB::get()->query("CALL restore()");
        
        $respuesta=new stdClass();
        $respuesta->status="Ok";
        $repuesta->mensaje="BDD restaurada a su estado original.";
        
        echo JSON::encode($respuesta);
    }
    
}
