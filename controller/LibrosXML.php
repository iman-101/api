<?php
class LibrosXML{
    
    public function __contruct(){
        
        header('Content-type:text/html; chartset=usf-8');
        
    }
    
    public function get(int $id = 0){
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
        
        echo XML::encode($libros,'libros','libro','http://biblioteca.robertsallent.com/xml/libros');
    }
    
    public function delete(int $id = 0){
        
        if(empty($id))
            throw  new Exception('No se indico el libro a borrar');
        
        if(!Libro::borrar($id))
            throw new Exception("No se pudo borrar el libro $id");
        
            
       echo "<respuesta>\n\t<status>OK</status>\n\t<mensaje>Borrado del libro $id OK </mensaje>\n</respuesta>";
    }

    public function  post() {
        $xml = file_get_contents('php://input');
        
        if(empty($xml))
            throw  new Exception('No se recibio el XML con los libros a insertar');
     
         if(!XML::validateWithSchema($xml,'schema/libros.xsd'))
             throw  new Exception("Error de validacion  (hay datos no validos).");
         
        $libros =XML::decode($xml,'Libro', false);
        
        $mensaje= "<respuesta>\n";
        $mensaje .="\t<status>OK</status>";
        
        foreach ($libros as $l){
            
          $mensaje .="\t<mensaje>";
          $mensaje .=$l->titulo;
          $mensaje .=$l->guardar()?
                 " guardar correctamente" : "no se pudo guardar (puede que ya existe)";
          $mensaje.="</mensaje>\n";
          
          http_response_code(201);
          
          echo $mensaje."</respuesta>";
          
        }
        
        
        
    }
    
    public function put() {
        $xml = file_get_contents('php://input');
        
        if(empty($xml))
            throw  new Exception('No se recibio el XML con los libros a actualizar');
        
        if(!@XML::validateWithSchema($xml, 'schema/libros.xsd'))
            throw  new Exception("Error de validacion (hay datos no válidos)");
        
       $libros =XML::decode($xml,'Libro', false);
       
       $mensaje = "<respuesta>\n";
       $mensaje .="\t<status>OK</status>";
       
       foreach ($libros as $l){
           $mensaje .="\t<mensaje>";
           $mensaje .=$l->titulo;
     
           $mensaje .=$l->actualizar() !==false? " actualizar OK": 
                       "No se pudo actualizar(comprueba los campos únicos)";
           $mensaje .="</mensaje>\n";
       }
       
       echo $mensaje."</respuesta>";
    }
    
    
    
}