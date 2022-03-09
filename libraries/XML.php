<?php

class XML{
    // convierte listas de cualquier tipo de objeto
    public static function encode(
        array $lista = [],
        string $root = 'root',
        string $name = null,
        string $namespace = 'http://ejemplo.xml.robertsallent.cat'
        ):string{
            
            // crea el documento XML
            $xml = new DOMDocument("1.0", "utf-8");
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            
            $raiz = $xml->createElement($root);
            $raiz->setAttribute('xmlns', $namespace);
            
            foreach($lista as $objeto){
                
                $nombre = $name ?? strtolower(get_class($objeto));
                $elemento = $xml->createElement($nombre);
                
                foreach ($objeto as $campo=>$valor)
                    $elemento->appendChild($xml->createElement($campo, $valor));
                    
                    //añade el nuevo elemento el elemento raíz
                    $raiz->appendChild($elemento);
            }
            $xml->appendChild($raiz);
            
            return $xml->saveXML();
    }
    // método que recupera objetos desde un XML
    public static function decode(
        string $origen,
        string $clase = 'stdClass',
        bool $fichero = true
        ):array{
            
            // cargamos el xml
            $xml = $fichero?
            simplexml_load_file($origen):
            simplexml_load_string($origen);
            
            $lista = []; // lista de objetos
            
            
            // para cada objeto
            foreach($xml as $objetoXML){
                $objeto = new $clase();
                
                // mapea los datos que encontremos en el  XML
                foreach($objetoXML as $campo=>$valor)
                    $objeto->$campo = (string) $valor;
                    
                    $lista[]=$objeto;
                    
            }
            return $lista;
    }
    
    
    
    public static function validateWithSchema(String $xml, string $esquema){
        
        $dom = new DOMDocument();
        
        if(!@$dom->loadXML($xml))
            throw new Exception("XML mal formado");
        
        return @$dom->schemaValidate($esquema);
    }
    
    
    
    
}