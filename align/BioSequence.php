<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//require_once '../app/model/HelperFunctions.php';

class BioSequence {

    public $geneid;
    public $dnaSeq;
    public $proteinSeq;
    

    function __construct() {
//        $this->geneid = $gene;
    }
    
//    public static function create() {
//        $instance = new self();
//        return $instance;
//    }

    public function setDnaSequence($seq) {
        $this->dnaSeq = $seq;
    }

    public function getDnaSequence() {
        return $this->dnaSeq;
    }

    public function setProteinSequence($seq) {
        $this->proteinSeq = $seq;
    }

    public function getProteinSequence() {
        return $this->proteinSeq;
    }
    
    public function getAminoAcidAtPos($pos){
        
    }
    
     public function readDnaSequence($fn) {
        $this->dnaSeq = $this->readSequenceFile($fn);
        return($this->dnaSeq);
    }

    public function readProteinSequence($fn){
        $this->proteinSeq = $this->readSequenceFile($fn);
        
        
        return($this->proteinSeq);
        
    }

    public static function readFastaInput($input){
        //   Fasta sequence
        $lines = explode("\n", $input);
        $seqname = substr($lines[0], 1);
        array_shift($lines);
        return implode($lines);
    }
    
    public static function reverseComplement($dnaseq){
    
    $src_dna = array('/A/','/C/','/G/','/T/');
    $target_dna = array('t','g','c','a');
    $comp_seq = preg_replace($src_dna,$target_dna,$dnaseq);
    $revcomp_seq = strtoupper(strrev($comp_seq));
    
    return $revcomp_seq;
    
}
    
            
    // Takes filename of fastafile, returns fasta sequence (without header)
    public static function readFastaFile($file) {
        $file_handle = fopen($file, "r") or die("Unable to connect to $site");
        fgets($file_handle);
        $Sequence = "";
        // Store fastafile line per line into string Sequence 
        while (!feof($file_handle)) {            
            $line = fgets($file_handle);
            $line = rtrim($line, "\n");          // remove linebreak
            $Sequence .= $line;
        }
        fclose($file_handle);
        return $Sequence;
    }

    public function readSequenceFile($fn){
        $handle = fopen($fn, "r") or die("Unable to open file!");
//      Read first line  
        fgets($handle);
        $contents = fread($handle,filesize($fn));
        $contents = preg_replace('/\s+/', '', $contents);
        
//        var_dump($contents);
        fclose($handle);
        return $contents;
    }
    
    public function compareToProteinSeq($seq){
        if(strlen($this->proteinSeq) == strlen($seq)){
            $munames = getMutationNames($this->proteinSeq,$seq);
            return $munames;
        }else{
            return 0;
        }        
    }

}

?>