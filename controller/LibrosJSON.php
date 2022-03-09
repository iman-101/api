<?php
class LibrosJSON{
    
    
    public function __construct(){
        header('Content-type:application/json; charset=utf-8');
    }
    
    public function get(int $id=0){
        
        if(empty($id)){
            
            
            $c =empty($_GET['c'])? 'titulo' : DB::escape($_GET['c']);
            
            $v =empty($_GET['v'])? '%' : DB::escape($_GET['v']);
            
            $libros = Libro::getFiltred($c,$v);
        }else{
            
          $libros =[];
          $libro=Libro::getById($id);
          
          if($libro){
              $libros[] = $libro;
          }else{
              http_response_code(404);
              throw new Exception("No se encontro el libro $id");
          }
        }
        
        echo JSON::encode($libros);
      
        
    }
    
    
    
    public function delete(int $id=0){
        
        if(empty($id))
            throw  new Exception("No se indico el libro a borrar.");
        
        if(!Libro::borrar($id))    
            throw new Exception("No se pudo borrar el libro $id");
            
        $respuesta= new stdClass();
        $respuesta->status="Ok";
        $respuesta->mensaje ="Borrado del libro $id correcto";
        
        echo JSON::encode($respuesta);
    }
    
    
    public function post(){
        $json =file_get_contents('php://input');
        
        if(empty($json))
            throw new Exception('No se indicaron libros a insertar');
       
        $libros =JSON::decode($json, 'Libro');
        
        if($libros == NULL)
            throw new Exception('El JSON recibido está mal formado');
        
        $respuesta = new stdClass();
        $respuesta->status="Ok";
        $respuesta->mensajes =[];
        
        foreach($libros as $libro){
            
            $errores =$libro->errorDeValidacion();
            if(sizeof($errores)){
                $respuesta->status="WARNING";
                $respuesta->mensajes[]="$libro->titulo contiene errores: "
                         .join(' ',$errores);
            }else{
                if($libro->guardar()){
                    
                    $respuesta->mensajes[]="$libro->titulo se guardado correctamente";
                    http_response_code(201);
                }else{
                    $respuesta->mensajes[] = "$libro->titulo nos pudo guardar (puede que ya existe)";
                }
            }
            
            echo JSON::encode($respuesta);
        }
        
    }
    
    
    
    public function put(){
        $json =file_get_contents('php://input');
        
        if(empty($json))
            throw new Exception('No se recibió el JSON con los libros a actualizar');
            
            $libros =JSON::decode($json, 'Libro');
        
        if($libros==NULL)
            throw  new Exception('El JSON recibido está mal formado');
            
            $respuesta = new stdClass();
            $respuesta->status="Ok";
            $respuesta->mensajes =[];
            
            foreach($libros as $libro){
                
                $errores =$libro->errorDeValidacion();
                if(sizeof($errores)){
                    $respuesta->status="WARNING";
                    $respuesta->mensajes[]="$libro->titulo tiene errores: "
                    .join(' ',$errores);
                }else{
                    if(empty($libro->id)){
                        $respuesta->status="WARNING";
                        $respuesta->mensaje[]="$libro->titulo no tiene ID";
                        
                    }else{
                        $respuesta->mensajes[] = $libro->actualizar() !== false?
                        "$libro->titulo actualizado correctamente":
                        "$libro->titulo no se pudo actualizar.";
                    }
                }
           
        }
        echo JSON::encode($respuesta);
        
    }
    
    
}
