<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function ReadFasta($file) {                         //takes filename of fastafile, returns fasta sequence (without header)
    $file_handle = fopen($file, "r") or die("Unable to connect to $site");
    fgets($file_handle);
    $Sequence = "";
    while (!feof($file_handle)) {           //store fastafile line per line into string Sequence  
        $line = fgets($file_handle);
        $line = rtrim($line, "\n");          // remove linebreak
        $Sequence .= $line;
    }
    fclose($file_handle);
    return $Sequence;
}

function DNA_pos_to_Prot_pos($DNA_pos) {                         //takes DNA position, computes position (NOT INDEX!!!)in protein and returns it
    $prot_pos = $DNA_pos / 3;
    $return_pos = floor($prot_pos);

    if (is_decimal($prot_pos)) {
        $prot_pos_decimals = $prot_pos - floor($prot_pos);
        $prot_pos_decimals = strval($prot_pos_decimals);        //turn number into string
        $prot_pos_decimals = str_split($prot_pos_decimals);     //turn string into array

        if ($prot_pos_decimals[2] == 3) {
            $return_pos += 1;

            return $return_pos;
        } elseif ($prot_pos_decimals[2] == 6) {
            $return_pos += 2;

            return $return_pos;
        }
    } else {

        return $return_pos + 1;
    }
}

function is_decimal($val) {
    return is_numeric($val) && floor($val) != $val;
}

function orf_trim_dnaseq($seq, $orf_Start) {            //trims dna seq according to orf : first pos is 1
    switch ($orf_Start):
        case 1:
            return $seq;
        case 3:
            $seq = substr($seq, 1);
            return $seq;
        case 2:
            $seq = substr($seq, 2);
            return $seq;
    endswitch;
}

### Jonas Function
//Input: DNA-seq (string)
//Output: Alignment arrays(each alignment at least  45 long)

function process_DNA_Input($DNA_input, $input_id) {
//    $exon1 = ReadFasta("assets/fasta/AF222068_Exon1.fasta");
//    $exon2 = ReadFasta("assets/fasta/AF222068_Exon2.fasta");
//    $referseq = $exon1.$exon2;
//    $referseq_array = str_split($referseq);
    
//  Load input to array  
    $DNA_input_array = str_split($DNA_input);

    $alignArray = array();
    if (count($DNA_input_array) > 45) {
        
//      Call the Emboss aligner  
        $emboss = callEmboss($DNA_input,session_id());
        
        $alignArray = array('Seq_array' => $emboss['salign'],
            'Gene_array' => $emboss['galign'],
            'tandemres' => $emboss['tandemres'],
            'gene_Pos_array' => range(1,strlen($emboss['galign'])),
            'seq_Pos_array' => range(1,strlen($emboss['salign'])),
            'Input_ID' => $input_id,
            'Mutations' => array());
        
//        echo 'AlignArray : '.PHP_EOL;
//        print_r($emboss['tandemres']);

//        echo $alignArray['Gene_array'];
//      Substitute the gaps in the reference sequence
//        $nogapAlignment = removeGaps($alignArray);
        $nogapAlignment = $alignArray;
                
//      Find mismatched position in the alignment
//        getSubstitutionPositions($seqa,$seqb);

//      Translate to protein sequence  
        $prot_seq_Input = translate_DNA_to_protein($nogapAlignment['Seq_array']);
        $prot_seq_Gene = translate_DNA_to_protein($nogapAlignment['Gene_array']);

//      Search for entandem  
        
//      Design mutations  
        $mutations = design_mutations($prot_seq_Input, $prot_seq_Gene);

//      $prot_seq_Input contains X for non-translated triplets  
        $alignArray['Prot_seq'] = $prot_seq_Input;
        $alignArray['Prot_gene_seq'] = $prot_seq_Gene;
        $alignArray['Mutations'] = $mutations;
    }            

    return($alignArray);
}

//Input: $Protein_input: protein sequence-seq (string)
//Output: Alignment arrays(each alignment at least  45 long)

function process_Protein_Input($Protein_input,$input_id) {

    //    $exon1 = ReadFasta("assets/fasta/AF222068_Exon1.fasta");
//    $exon2 = ReadFasta("assets/fasta/AF222068_Exon2.fasta");
//    $referseq = $exon1.$exon2;
//    $referseq_array = str_split($referseq);
//  Load input to array  

    $align_array = array();
    if (strlen($Protein_input) < 15) {
        return null;
    } else {
        //      Call the Emboss aligner  
        $emboss = callEmboss_protein($Protein_input,session_id());

        $align_array = array(
            'Input_ID' => $input_id,
            'Prot_seq' => $emboss['salign'],
            'protein_str' => $emboss['galign'],
            'Mutations' => array());
//        echo strlen($emboss['salign']);
//        echo strlen($align_array['protein_str']);
//      Design mutations  
        $mutations = design_mutations($align_array['Prot_seq'], $align_array['protein_str']);
//      $prot_seq_Input contains X for non-translated triplets  
        $align_array['Mutations'] = $mutations;

        return($align_array);
    }
}

// findMatchPositions
// Input Array
// Output String positions where gap was found, starts sequence counting from 1
function findMatchPositions($seq_array){
    $matches = array();
//    $seq_array = str_split($seq);
    for($i=0; $i < count($seq_array); $i++){
        if($seq_array[$i] != '-' && $seq_array[$i] != 'X'){
            array_push($matches, ($i + 1));
        }
    }
    return $matches;
}



// Function removeGaps
// Input $salign = Aligned input sequence
// Input $galign = Aligned gene sequence
// Output:  Aligned sequences of same length without gaps
function removeGaps($alignment){
    
    $seq_array = str_split($alignment['Seq_array']);
    $gene_array = str_split($alignment['Gene_array']);
        
    
    if(count($seq_array) != count($gene_array) ){
        throw new Exception('RemoveGaps: Length of Sequence and Gene is not equal.');
    }
    
    $new_seq = array();
    $new_gene = array();

    for($i = 0; $i < count($seq_array); $i++ ){
//        if(substr($salign,$i,1) != '-' && substr($galign,$i,1) != '-' ){
//         $newSalign .= substr($salign,$i,1);
//            $newGalign .= substr($galign,$i,1);
        if($gene_array[$i] != '-'){
//          Update alignment  
            array_push($new_seq,$seq_array[$i]);
            array_push($new_gene,$gene_array[$i]);
        }    
    }
    $alignment['Seq_array'] = implode($new_seq);
    $alignment['Gene_array'] = implode($new_gene);
    
    return $alignment;
    
}

//function translate_DNA_Fragment($fragment){
//    $peptide = translate_DNA_to_protein($fragment);
////    $peptide = rtrim($peptide, "X");
//    return $peptide;
//}
//
//Takes sequence of mutated gene and returns array with mutations
function design_mutations($input_transseq,$gene_transseq){         //takes sequence of mutated gene and returns array with mutations

    if (strlen($input_transseq) != strlen($gene_transseq)) {
        throw new Exception('design_mutations_Prot: Length of Sequence and Gene is not equal.');
    }

    $return_array = array();
    
    for($i = 0; $i < strlen($gene_transseq); $i++){
        $input_base = substr($input_transseq, $i, 1);
        $gene_base = substr($gene_transseq, $i, 1);

        if($input_base != 'X' && $input_base != '-' &&  $gene_base != 'X'){
            if($input_base != $gene_base){
                $mutation = $gene_base. ($i + 1) .$input_base;
                array_push($return_array, $mutation);
            }
        }
    }
    
    return $return_array;
}



//function translate_DNA_Fragment($fragment, $AL_Start_DNA){          //takes string dna fragment and Pos of first bas ein gene (+1, as not in array)
//    $orf_array = array();
//    foreach (range(0, 1547) as $i) {
//        array_push($orf_array, ($i % 3) + 1);
//    }
//
////  correction for array index, AL START DNA comes from gene_Pos_array 
//    $ORF_Pos = $orf_array[$AL_Start_DNA-1 ];
//    $fragment = orf_trim_dnaseq ($fragment, $ORF_Pos);
//    $peptide = translate_DNA_to_protein($fragment);
//    //trim X at end of peptide
//    $peptide = rtrim($peptide, "X");
//    return $peptide;
//}

/////
//FROM BIO PHP:
function translate_DNA_to_protein($seq){    //translates DNA to Protein
        
        // $aminoacids is the array of aminoacids
        $aminoacids=array("F","L","I","M","V","S","P","T","A","Y","*","H","Q","N","K","D","E","C","W","R","G","X");

        // $triplets is the array containning the genetic codes
        // Info has been extracted from http://www.ncbi.nlm.nih.gov/Taxonomy/Utils/wprintgc.cgi?mode

        // Standard genetic code
        //$triplets[1]=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
        $genetic_code=array("(TTT |TTC )","(TTA |TTG |CT. )","(ATT |ATC |ATA )","(ATG )","(GT. )","(TC. |AGT |AGC )",
                        "(CC. )","(AC. )","(GC. )","(TAT |TAC )","(TAA |TAG |TGA )","(CAT |CAC )",
                        "(CAA |CAG )","(AAT |AAC )","(AAA |AAG )","(GAT |GAC )","(GAA |GAG )","(TGT |TGC )",
                        "(TGG )","(CG. |AGA |AGG )","(GG. )","(\S\S\S )");
        
        

        // place a space after each triplete in the sequence
        $temp = chunk_split($seq,3,' ');
        
        // replace triplets by corresponding amnoacid

        $peptide = preg_replace ($genetic_code, $aminoacids, $temp);
       // echo $peptide;
        // return peptide sequence
//        $peptide = rtrim($peptide, '*');
        return $peptide;
}