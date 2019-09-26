
<?php

class AlignError {

    
    private static $instance;
    private static $error_array;

    private function initialize() {
        self::$error_array = array(
            'EMBOSS' => false,
            'MUTAFORMAT' => false,
            'ALIGN' => false,   
            'GAPS' => false,
            'HIGHMUTATE' => false,
            'GENEGAPS' => false,
            'INPUTGAPS' => false,
            'INPUTGAPS' => false,
            'REVCOMP' => false,
            'PROMOTER' => false
            );
    }

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new AlignError();
            self::$instance->initialize();
        }
        return self::$instance;
    }
    

    public static function set_error($error_type){
            self::$error_array[$error_type] = true;
    }
    
    public static function get_error($error_type){
            return self::$error_array[$error_type];
    }
    
    public static function unset_error($error_type) {
        return self::$error_array[$error_type] = false;
    }

    public static function print_error($name,$type) {

        switch ($name) {
            
            case 'EMBOSS':
                $text = 'Alignment procedure caused an error.';
                break;
            case 'DUPGENO':
                $text = 'WARNING: Some genotypes have ambiguous resistance phenotype';
                break;
             case 'MUTAFORMAT':
                 $text = "WARNING: Some mutations are not in the right format ([WT][POS][MUT] or [POS][MUT]).";
                 break;
             case 'DNABASE':
                $text = "WARNING: Non-DNA bases (A,C,G,T) have been removed from the input sequences.";
                break;
            case 'SHORT':
                $text = "WARNING: All input sequences must contain at least 45 characters.";
                break;
             case 'LONG':
                $text = "WARNING: All input sequences must not contain more than 4000 characters.";
                break;
            case 'OVERLAP':
                $text = "WARNING: Overlapping regions of input sequences show different mutations in their sequence.";
                break;
            case 'ALIGN':
                $text = 'WARNING: Input sequences cover less than 50% of the reference gene sequence.';
                break;
            case 'GAPS':
                $text = 'WARNING: Nucleotide insertions / deletions in input sequence detected, ignored for protein alignment.';
                $text = null;
                break;
            case 'HIGHMUTATE':
                $text = 'WARNING: Unexpected high number of amino acid substituions in the protein alignment. Please check the quality of your sequences and species identification.';
                break;
            case 'GENEGAPS':
                $text = 'WARNING: Nucleotide insertions in input sequence detected, ignored for protein alignment.';
                $text = null;
                break;
            case 'INPUTGAPS':    
                $text = 'WARNING: Nucleotide deletions in input sequence detected, ignored for protein alignment.';
                $text = null;
                break;
            case 'REVCOMP':    
                $text = 'WARNING: Reverse complement of the input sequences is used.';
                break;
            default:
                $text = null;
                break;
        }
        
        $warn = null;
        if(!empty($text)){
            if($type == "danger"){
                $warn = '<div class="alert alert-danger"> ' .$text. ' </div>' ;
            }elseif($type == "warning" ){
                $warn = '<div class="alert alert-warning"> ' .$text. ' </div>' ;
            } 
            
        }

       return $warn;
//        if(self::$error_array[$error_type]){
//            
//        }else{
//            return null;
//        }
    }

}
?>