<?php

/**
 * Classe para criação e manipulação de relatório
 *
 * @author marco
 */
include("Fpdf/PHPJasperXML.php");
include("Fpdf/FPDF.php");
include("Xml/Xml.php");
class Report {
    private $arquivo;
    private $debugSql;
    private $tipoSaida;
    private $jasper;
    private $xml;
    public function __construct($arquivo, $tipoSaida='I'){
        $this->arquivo = $arquivo;
        $this->tipoSaida = $tipoSaida;
        $this->debugSql = true;
        $this->jasper = new PHPJasperXML();
        $this->loadXml();
        
    }
    private function loadXml(){
        $this->xml = new Xml();
        $this->xml->loadXmlFromFile($this->arquivo);
    }
    public function setParameter(array $parametro){
        $this->jasper->arrayParameter = $parametro;
    }

    public function showRelatorio(){
        $this->jasper->xml_dismantle($this->xml);
        $this->jasper->transferDBtoArray();
        $this->jasper->outpage($this->tipoSaida);
    }
}
?>
