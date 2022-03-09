<?php
class Model{
    
    
    public static function get():array{
        
        $tabla = strtolower(get_called_class()).'s';
        
        $consulta = "SELECT * from $tabla";
        
        return DB::selectAll($consulta,get_called_class());
        
        
    }
    
    
    public static function getById(int $id){
        
        $tabla = strtolower(get_called_class()).'s';
        
        $consulta = "SELECT * from $tabla where id = $id";
        
        return DB::select($consulta,get_called_class());
    }
    
    
    public  function guardar(){
        
        $tabla = strtolower(get_called_class()).'s';
        
        $consulta = "insert into $tabla(";
            
          foreach ($this as $propiedad=>$valor)
                   $consulta  .= "$propiedad, ";
          
         $consulta = rtrim($consulta, ', ');
         $consulta .= ")VALUES (";
         
         foreach ($this as $valor)
             switch (gettype($valor)){
                case "string" : $consulta .= "'$valor', ";break;
                case "NULL" : $consulta .= "NULL, ";break;
                default: $consulta .= "$valor, ";
             }
         $consulta = rtrim($consulta, ', ');
         $consulta .= ")";
      
         
        $this->id = DB::insert($consulta);
        
        return $this->id;
    }
    
    public  function actualizar(){
        
        $tabla = strtolower(get_called_class()).'s';
        
        $consulta = "UPDATE   $tabla SET ";
        
        
        foreach ($this as  $propiedad=>$valor)
            
            
            switch(gettype($valor)){
                case "string" : $consulta .= "$propiedad = '$valor', ";break;
                case "NULL" : $consulta .= "$propiedad = NULL, ";break;
              
                default     : $consulta .= "$propiedad = $valor, " ;
            }
        
        $consulta = rtrim($consulta, ', ');
        $consulta .=" WHERE id=$this->id";
       
        return DB::update($consulta);
        
       
    }
    
    public static function getFiltred(string $campo='titulo',string $valor='',
        string $orden = 'id',string $sentido='ASC'):array{
            
            $tabla = strtolower(get_called_class()).'s';
            
            $consulta = "SELECT * FROM $tabla
             WHERE $campo LIKE '%$valor%' ORDER BY $orden $sentido";
          
            return DB::selectAll($consulta, get_called_class());
            
    }
    
    
    public static function borrar(int $id){
        
        $tabla = strtolower(get_called_class()).'s';
        
        $consulta = "DELETE FROM $tabla WHERE id = $id";

        return DB::delete($consulta);
        
    }
   
    
    public function __toString():string {
        $texto ='';
        
        foreach($this as $propiedad=>$valor)
            $texto .= "$propiedad: <b>$valor, </b>";
        
        
        
        return rtrim($texto, ', ');
        
        
    }
    
    
    
}