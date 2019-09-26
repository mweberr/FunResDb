<?php

## The workflow is as follows
## (1) Run EMBOSS Water to produce alignment files
## (2) Read the alignment files
## (3) Translate into protein sequence
## (4) Create the output

## Function getSubstitutionPositions
## Input 
##  $seqa: Sequence A
##  $seqb: Sequence B
## Return values: Mismatching positions in sequence a and b, which are no gap (hyphen)



function getSubstitutionPositions($seqa,$seqb){
    if(strlen($seqa) != strlen($seqb)){
        return NULL;
    }
    $pos = array();
    for($i=0; $i<strlen($seqa); $i++){
        $chara = substr($seqa, $i, 1);
        $charb = substr($seqb, $i, 1);
        if($chara != $charb && $chara != '-' && $charb != '-'){
            array_push($pos, $i);
        }
    }
    return $pos;
}



## Function callEmboss 
// Input: $seq_str: a substring of gene cyp51A
// Output: Alignment array gpos,galign,spos,salign

function callEmboss($seq_str){
     $parts = str_split($seq_str,80);
     $out = implode(PHP_EOL, $parts);
     $out = ">CYP51APART".PHP_EOL.$out;
//   Write the FASTA sequence seq_str in a file
     file_put_contents("align/searchseq.fasta", $out);
//   Remove old file   
     if(file_exists('align/cyp51a.needle')){
         unlink('align/cyp51a.needle');
     }
     
//     system('rm align/cyp51a.needle');
//   Call needle
     $call = $GLOBALS['needle'].' align/searchseq.fasta align/AF222068_Exon12.gcg -gapopen 20 -gapextend 1.0 -outfile align/cyp51a.needle -aformat markx10';

     system($call);
//   Read the embossfile and return positions
     $alignArray = readEmbossFile('align/cyp51a.needle');
     return $alignArray;
}



function callEmboss_protein($seq_str){
     $parts = str_split($seq_str,80);
     $out = implode(PHP_EOL, $parts);
     $out = ">CYP51APART".PHP_EOL.$out;
//   Write the FASTA sequence seq_str in a file
     file_put_contents("align/searchseq.fasta", $out);
//   Remove old file  
     if(file_exists('align/cyp51a.prot')){
         unlink('align/cyp51a.prot');
     }
//     system('rm align/cyp51a.prot');
//   Call needle
      $call = $GLOBALS['needle'].' align/searchseq.fasta align/AF222068_Protein.gcg -gapopen 10 -gapextend 1.0 -outfile align/cyp51a.prot -aformat markx10';
     system($call);
//   Read the embossfile and return positions
     $alignArray = readEmbossFile('align/cyp51a.prot');
     return $alignArray;
}


## Function readEmbossFile
## Input 
##  $fn: Resulting file from EMBOSS tool water in the format markx10
##  Water call: 
##  water fasta::Search.fasta gene_cyp51a.gcg -gapopen 10 -gapextend 1.0 -outfile cyp51.water -aformat markx10

## Return values: Array of arrays galign, gpos, spos, salign

function readEmbossFile($fn) {
    
    $content = file_get_contents($fn);
    if($content === false){
        return false;
    }
    $rows = explode("\n", $content);
    array_shift($rows);
//echo substr($rows[0],0,1);
## Extract the alignment of the gene reference sequence
    $geneAlignPart = "";
    $geneAlignPos = array(0, 0, 0);
    $m = preg_grep("/^>CYP51AGENE/", $rows);
    $ridx = key($m);
# Alignment vector
# 
# Sequence length
    $geneAlignLen = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 1]);
//# Alignment start
    $geneAlignStart = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 3]);
//# Alignment stop
    $geneAlignEnd = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 4]);
    
    $geneAlignPos = array($geneAlignLen,$geneAlignStart,$geneAlignEnd);
    
    $i = $ridx + 6;
    while (!preg_match("/^[>|\#|\s]/", $rows[$i])) {
        $geneAlignPart .= $rows[$i];
        $i++;
    }

## Extract the alignment of the partial search sequence        
    $searchAlignPart = "";
//    $searchAlignPos = array(0, 0, 0);
    $m = preg_grep("/^>CYP51APART/", $rows);
    $ridx = key($m);


# Sequence length
    $searchAlignLen = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 1]);
# Alignment start
    $searchAlignStart = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 3]);
# Alignment stop
    $searchAlignStop = preg_replace('/.+\s([0-9]+)/', '\1', $rows[$ridx + 4]);
    
    $searchAlignPos = array($searchAlignLen,$searchAlignStart,$searchAlignStop);
    
    $i = $ridx + 6;
    while (!preg_match("/^[>|\#|\s]/", $rows[$i])) {
        $searchAlignPart .= $rows[$i];
        $i++;
    }
    
//  Scan for promoter region
    if (preg_match('/^[-]/', $geneAlignPart)) {
        $promoterPart = preg_replace('/(^[-]+).*$/', '$1', $geneAlignPart);        
        $promoterSeq = substr($searchAlignPart,0,strlen($promoterPart));
        $tandem_res = callEtandem($promoterSeq);
        
    } else {
//   No promoter found 
        $promoterSeq = '';
        $tandem_res = null;
    }
    
//  Search for insertions in input sequence
    $scangapgene = $geneAlignPart;
    $scangapgene = preg_replace('/^[-]+/','',$scangapgene);
    $scangapgene = preg_replace('/[-]+$/','',$scangapgene);
    preg_match_all('/[-]+/', $scangapgene,$matches,PREG_OFFSET_CAPTURE);
    $gaparray = $matches[0];
    
    if (count($gaparray) > 0) {
        foreach ($gaparray as $gap) {
            if (strlen($gap[0]) < 50) {
//           Potential insertion in the entry sequence
                AlignError::set_error('GAPS');
            }
        }
    }
    
//  Search for deletions in input sequence
    $scangapgene = $searchAlignPart;
    $scangapgene = preg_replace('/^[-]+/', '', $scangapgene);
    $scangapgene = preg_replace('/[-]+$/', '', $scangapgene);
    preg_match_all('/[-]+/', $scangapgene, $matches, PREG_OFFSET_CAPTURE);
    $gaparray = $matches[0];

    if (count($gaparray) > 0) {
        foreach ($gaparray as $gap) {
            if (strlen($gap[0]) < 50) {
//           Potential deletion in the entry sequence
                AlignError::set_error('GAPS');
            }
        }
    }
    
    



//  Analyse seq_array for gap stretches
//    preg_match_all('/[-]+/', $searchAlignPart,$matches,PREG_OFFSET_CAPTURE);
//    $startgap = $searchAlignPart[0] == '-';
//    $endgap = $searchAlignPart[strlen($promoterSeq)] == '-';
    
    
    
    $seq_array = str_split($searchAlignPart);
    $gene_array = str_split($geneAlignPart);
    $new_seq = array();
    $new_gene = array();
//   Remove positions in alignment which show gaps in gene sequence
        for($i = 0; $i < count($seq_array); $i++ ){
//        if(substr($salign,$i,1) != '-' && substr($galign,$i,1) != '-' ){
//         $newSalign .= substr($salign,$i,1);
//            $newGalign .= substr($galign,$i,1);
        if($gene_array[$i] != '-'){
//          Update alignment  
            array_push($new_seq,$seq_array[$i]);
            array_push($new_gene,$gene_array[$i]);
        }else{
//            $testerror = AlignError::getInstance();
//            $testerror->set_error('GAP');
        }    
    }

//    $new_seq = $seq_array;
//    $new_gene = $gene_array;
//   return array('gpos'=>$geneAlignPos,'galign'=>$geneAlignPart,'spos'=>$searchAlignPos,'salign'=>$searchAlignPart);
     return array('gpos'=>$geneAlignPos,'galign'=>implode($new_gene),
                        'spos'=>$searchAlignPos,'salign'=>implode($new_seq),'tandemres'=>$tandem_res);
}


function callEtandem($seq_str){
     $parts = str_split($seq_str,80);
     $out = implode(PHP_EOL, $parts);
     $out = ">CYP51APART".PHP_EOL.$out;
//   Write the FASTA sequence seq_str in a file
     file_put_contents("align/searchseq.fasta", $out);
//   Remove old file   
     if(file_exists('align/cyp51a.etandem')){
         unlink('align/cyp51a.etandem');
     }
     
     //etandem search.fasta -minrepeat 20 -maxrepeat 100 -outfile etandemres
//     system('rm align/cyp51a.needle');
//   Call needle
     $call = $GLOBALS['etandem'].' align/searchseq.fasta -mismatch false -minrepeat 20 -maxrepeat 100 -rformat gff -outfile align/cyp51a.etandem';

     system($call);
//   Read the embossfile and return positions
     # Etandem array contains repeat length and sequence
     $tandem_array = readEtandemFile('align/cyp51a.etandem');
     return $tandem_array;
}

## Read Emboss etandemfile
## result_array consists of 
function readEtandemFile($fn) {
    $fn = 'align/cyp51a.etandem';
    
    $content = file_get_contents($fn);
    $contentrows = explode("\n", $content);
    $result_array = array();

    foreach ($contentrows as $row) {
        if (substr($row, 0, 1) != '#') {
            $line = explode("\t", $row);
            if (count($line) > 1) {

                if (count($line) > 1) {
                    $split = preg_split("/ /", $line[8]);
                    $seq = array_pop($split);
                }
                array_push($result_array, array($line[5], $seq));
            }
        }
    }
    return $result_array;
}





//function format_alignment ($alignment,$patseq,$referseq, $input_id){     //takes trace array, $pattern sequence, $reference sequence, $mi (last aligned pos in pattern seq), $mj (last aligned pos in reference seq)
//
//
//    
//    $referseq_array = str_split($referseq);
//    $gene_array = array_slice($referseq, $first+1, $last-$first);
//    
//    
//
//
//
//
//    return $return_array;
//}
