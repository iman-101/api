<?php
class JSON{
    
    public static function encode($objectos):string{
        
        return json_encode(
            $objectos,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES |
            JSON_NUMERIC_CHECK);
    }
    
    
    public static function decode(string $json, string $class='stdClass'):array{
        
        
        $lista = json_decode($json);
        
        if(json_last_error())
            throw  new Exception("Error: ".json_last_error_msg());
        
        
        
        if(!is_array($lista))
            $lista =  [$lista];
        
            
        if($class == 'stdClass')
           return $lista;
            
       $nuevaLista=[];
       
       foreach ($lista as $objeto){
           $nuevoObjeto = new $class();
           
           foreach ($objeto as  $propiedad => $valor)
               $nuevoObjeto->$propiedad = $valor;
           
               $nuevaLista[] = $nuevoObjeto;
       }
       
       return $nuevaLista;
    }
    
}