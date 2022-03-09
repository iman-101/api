<?php


class FrontController{
    
    private $entidad='';
    private $formato='';
    
    public function main(){
        
        try{
            
            if(empty($_GET['f']))
                throw  new Exception('No se indico el formato (XML o JSON)en la url');
                
            if(empty($_GET['e']))
                throw  new Exception('No se indico la entidad en la url');
            
            $this->formato=strtoupper(DB::escape($_GET['f'])); 
            
            $this->entidad=ucfirst(DB::escape($_GET['e']));
            
            $controlador="$this->entidad$this->formato";
            
            if(!is_readable("../controller/$controlador.php"))
                throw  new Exception("No existe ENDPOINT para $this->entidad en $this->formato");
            
            $id=empty($_GET['id'])? 0: intval($_GET['id']);
            
            $controlador = new $controlador();
            
            $metodo =strtolower($_SERVER['REQUEST_METHOD']);
            
            if(!is_callable([$controlador, $metodo]))
                throw  new Exception("No existe ENDPOINT para $this->entidad en $this->formato");
            
                $controlador->$metodo($id);
        }catch(Throwable $t){
            
            switch($this->formato){
                case 'XML' : header('Content-type:text/xml; charset=utf-8');
                             echo "<respusta>\n\t<status>ERROR</status>\n
                                   \t<mensaje>".htmlspecialchars($t->getMessage()).
                             "</mensaje>";
                             break;
                case 'JSON' : header('Content-type:application/json; charset=utf-8');
                             $respuesta=new stdClass();
                             $respuesta->status="ERROR";
                             $respuesta->mensaje=$t->getMessage();
                             echo JSON::encode($respuesta);
                         
                             break;
                default: header('Contento-type:text/plain; charset=utf-8');
                         echo $t->getMessage();
            }
            
        }
    }
    
    
}