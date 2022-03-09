<?php 

class Socio extends Model{
    
    
    public $dni = '',$nombre = '' ,
    $apellidos = '',$nacimiento = '',$email = '' , $direccion = '' , $poblacion = '',
    $provincia = '',$telefono = '',$cp='',$imagen=null;
    
    
    
     public function getPrestamos():array{
        
        $consulta = "SELECT * FROM prestamos WHERE idsocio= $this->id";
        
        return DB::selectAll($consulta,'Prestamo');
        
    }
    
    
    
    
    
}