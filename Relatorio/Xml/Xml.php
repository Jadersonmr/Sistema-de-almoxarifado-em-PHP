<?php
class Xml{
    private $xml;

    public function __contruct($xml = null){
        $this->xml = $xml;
    }
    public function loadXmlFromFile($file){
        if(file_exists($file)){
            $this->xml = simplexml_load_file($file);
        }else{
            trigger_error("Arquivo inexistente",E_USER_WARNING);
        }
    }
    public function getXml($show = false){
        if($show){
            echo $this->xml;
        }else{
            return $this->xml;
        }
    }
}

?>
