<?php
class Libro extends Model{
    
    
    public $id = 0,$isbn = '',$editorial ='',
    $idioma ='',$autor ='',$ediciones=0, $edadrecomendada = 0;
    
    
    
   public function getEjemplares():array{
        $consulta = "SELECT * FROM ejemplares WHERE idlibro= $this->id";
        return DB::selectAll($consulta,'Ejemplare');
    }
    
    public function errorDeValidacion():array{
        $errores =[];
        
        if(!preg_match("/^[\d\-]{13,17}$/", $this->isbn)){
            $errores[] = "Error en el formato del ISBN";
        }
        
        
        if(strlen($this->titulo)<1 || strlen($this->titulo) >64){
            $errores[] = "Error en la longitud del titulo";
        }
        
        if($this->ediciones <0){
            $errores[] = "Error en el número de ediciones.";
        }
        
        if($this->edadrecomendada <0 || $this->edadrecomendada>120){
            $errores[] = "Error en la edad recomendada.";
        }
        
        return $errores;
    }
    
    
 
    
}