<?php
ini_set('display_errors',1);
error_reporting(-1);
// Function print_result_report 
// Writes in one text file the following information :
// Datum, Eingabesequenz, DNA-Alignment, Protein-Alignment, Gefundene Mutationen

//$alignment = array();
////$alignment['Input_ID'];
//$alignment['Seq_array'] = "ATGGTGCCGATGCTATGGCTTACGGCCTACATGGCCGTTGCGGTGCTGACAAAA------";
//$alignment['Gene_array'] = "ATGGTGCCGATGCTATGGCTTACGGCCTACATGGCCGTTGCGGTGCTGACAAAA---AA-";
//
//
//print_result_report($alignment);

//$fn = '../align/Report_'.rand(10,10000).'.txt'; 
//
//$inputseqs = array();
//$inputseqs["InputseqA"] = "asssssssdasdsaddddddddddddddddddddddddddddddddddddddddddasdsadsadasdasddddddddddddddddddddddddddddddddddddddasdsadasdsadsa";
//$inputseqs["InputseqB"] = "asssssssdasdsaddddddddddddddddddddddddddddddddddddddddddasdsadsadasdasddddddddddddddddddddddddddddddddddddddasdsadasdsadsa";
//$inputseqs["InputseqC"] = "asssssssdasdsaddddddddddddddddddddddddddddddddddddddddddasdsadsadasdasddddddddddddddddddddddddddddddddddddddasdsadasdsadsa";
//
//$flagprot = true;
//
//print_header_information($inputseqs, $flagprot, $file = 'Testseq.txt');
//
//$gene = 'ACGGATACADSDAAAAAA';
//$dnaseqs = array();
//$dnaseqs[0] = 'ACGGATAC----------';
//$dnaseqs[1] = '------ACATAAA-----';
//$dnaseqs[2] = '------ACAAAAAAAAAA';
////$alignArray['Prot_seq'];

//
//$res = integrateSequenceParts($gene, $dnaseqs);
////print_r($res);
//
//print_result_report($gene,$res,'GENEA','INPUTA');
//
//$protseq = array();
//$protein    = 'ADSAEFASFFDSFASDASDSADD';
//$protseq[0] = '-DSAEFASFFDSF----------';
//$protseq[1] = '------ASFDDSFASDAS-----';
//$protseq[2] = '--------FFDSFASDASDSADD';



//print_r($res);

//print_result_protein_report($protein,$res,"ProteinIDA","InputIDB");
//
//$muts = array('M192F','F238A','A36E');
//print_mutations($muts);
function print_report($inputseqs,$alignments,$intype,$fn){
    
    $dnaseqs = array();
    $protseq = '';
    $align_dnaseqs = array();
    $align_protseqs = array();
    $align_refer_gene = array();
    $align_refer_protein = array();
    $tandemres = $alignments[0]['tandemres'];
//    print_r($tandemres);
    $mutations = array();
            
    foreach($alignments as $align){
        array_push($align_dnaseqs,$align['Seq_array']);
        array_push($align_protseqs,$align['Prot_seq']);
        array_push($align_refer_gene,$align['Gene_array']);
        array_push($align_refer_gene,$align['Gene_array']);
        array_push($align_refer_protein,$align['Prot_gene_seq']);
        $mutations = array_merge($mutations,$align['Mutations']);
    }
//  
    
//    print_r($mutations);
    
//    $alignArray['Prot_seq'] = $prot_seq_Input;
//    $alignArray['Mutations'] = $mutations;
//    $alignArray = array('Seq_array' => $emboss['salign'],
//    'Gene_array' => $emboss['galign'],
//    'gene_Pos_array' => range(1,strlen($emboss['galign'])),
//    'seq_Pos_array' => range(1,strlen($emboss['salign'])),
//    'Input_ID' => $input_id,
//    'Mutations' => array());
    
    $filestr = "";

//    
////  Print header information  
    $filestr = print_header_information($inputseqs, $intype, $filestr);
//    
////  Print alignments
    if('DNA sequences present'){
        $input_geneseq = integrateSequenceParts($align_refer_gene[0], $align_dnaseqs);
        
        $filestr = print_result_report($align_refer_gene[0],$input_geneseq,'CYP51A_Reference','CYP51A_Input',$filestr);
    }
////  Print protein alignments  
    $input_protseq = integrateProteinSequenceParts($align_refer_protein[0], $align_protseqs);

    $filestr = print_result_protein_report($align_refer_protein[0],$input_protseq,'CYP51A_Reference','CYP51A_Input',$filestr);
//    
//  Print tandem repeats
    $filestr = print_tandemrepeats($tandemres,$filestr);
//    
//  Print mutations to the file
    $filestr = print_mutations($mutations,$filestr);

    return $filestr;
}

function print_header_information($inputseqs, $intype, $filestr) {
     $intro = "## Protokoll generated on http://www.nrz-myk.de/ \n".
             "## Alignment of input sequences to cyp51A reference sequence \n";
//   Print the date 
     
    $datestr = date('Y-m-d H:i:s');

    if ($intype == 'protein') {
        $typetxt = "## Sequence type: Protein";
    } else {
        $typetxt = "## Sequence type: DNA";
    }

    $inputstr = "";
    foreach ($inputseqs as $seq) {
        if (strlen($seq[1]) != 0) {
            $inputstr .= '>' . $seq[0] . "\n";
            $seq_chunks = preg_split("/\n/", chunk_split($seq[1], 50));
            foreach ($seq_chunks as $chunk) {
                $inputstr .= $chunk . "\n";
            }
            $inputstr .= "\n\n";
        }
    }

//   Write to file  
    $result_txt = $intro."## Date: ".$datestr."\n".$typetxt."\n\n"."Input sequences: \n".$inputstr;
    $filestr .= $result_txt;
//    file_put_contents($file, $result_txt, FILE_APPEND);
    return $filestr;
}



function print_tandemrepeats($tandem_array,$filestr){
    
    $header = "Tandem repeats in DNA promoter sequence " . PHP_EOL . str_repeat("=", 100);
    
    if($tandem_array == NULL || count($tandem_array) == 0){
        $tandem_txt = "No tandem repeats found.";

    }else{
        $tandem_txt = "";
        foreach($tandem_array as $tand){
            $tandem_txt = $tandem_txt.implode($tand,"  ").PHP_EOL;
            $tandem_txt = strtoupper($tandem_txt);
        }
    }
        
    $result_txt = $header . PHP_EOL . $tandem_txt . PHP_EOL.PHP_EOL.PHP_EOL;
    $filestr .= $result_txt;
//    file_put_contents($file, $result_txt, FILE_APPEND);
    return $filestr;

}


function print_mutations($mutations,$filestr){
    $header = "Mutations in protein sequence " . "\n" . str_repeat("=", 100);
    if ($mutations == NULL || count($mutations) == 0) {
        $mutrow = "No mutations found.";
        
    }else{

        $mutrow = implode($mutations, ', ');
    }
    
    $result_txt = $header . "\n" . $mutrow . "\n";
    $filestr .= $result_txt;
//    file_put_contents($file, $result_txt, FILE_APPEND);
    return $filestr;
}

// Function integrate Sequence Parts
// Generate one sequence from three sequence parts
function integrateSequenceParts($gene, $dnaseqs) {
    $poscount_array = array();

//  Check if all sequences have the same length  
    for ($i = 0; $i < count($dnaseqs); $i++) {
//                    echo 'DNASequence '.$i.' : '.strlen($dnaseqs[$i]);
//            echo '<br>';
//            echo $dnaseqs[$i];
        if (strlen($dnaseqs[$i]) != strlen($gene)) {

            throw new Exception('Length of input sequence and gene sequence are not equal I1');
        }
    }
    # Filter Function

    function filterfun($x) {
        return $x !== 0;
    }

    $consseq = '';
    for ($i = 0; $i < strlen($gene); $i++) {
        $ntds = array('A' => 0, 'C' => 0, 'G' => 0, 'T' => 0, '-' => 0);
        foreach ($dnaseqs as $seq) {
            $char = substr($seq, $i, 1);
            if (in_array($char, array('A', 'G', 'C', 'T', '-'))) {
                $ntds[$char] = $ntds[$char] + 1;
            }else{
                
            }
        }
        
        $nuctids = implode(array_keys(array_filter($ntds, "filterfun")));
        $res = iupac_translate($nuctids);        
        $consseq = $consseq . $res;
    }

    return $consseq;
}

function print_result_report($gene,$input,$gene_id,$input_id,$filestr){
    
    if(strlen($input) != strlen($gene)){
        throw new Exception('Length of input sequence and gene sequence are not equal');
    }
    
//  Generate number columns
//  Create connection row
    $connect_seq = "";
    for ($i = 0; $i < strlen($gene); $i++) {
        if (substr($gene, $i, 1) == '-' || substr($input, $i, 1) == '-') {
            $connect_seq = $connect_seq . " ";
        } else if (substr($gene, $i, 1) == substr($input, $i, 1)) {
            $connect_seq = $connect_seq . "|";
        } else {
            $connect_seq = $connect_seq . ".";
        }
    }

    $input_chunks = preg_split("/\n/",chunk_split($input, 50));
    array_pop($input_chunks);
    $gene_chunks = preg_split("/\n/",chunk_split($gene, 50));
    array_pop($gene_chunks);
    $connect_chunks = preg_split("/\n/",chunk_split($connect_seq, 50));
    array_pop($connect_chunks);

    $firstcol_size = max(strlen($input_id), strlen($gene_id)) + 5;
    $connect_id = str_repeat(" ", $firstcol_size);
    $result_txt = "";
    for ($i = 0; $i < count($input_chunks); $i++) {

        $ic = $input_chunks[$i];
        $gc = $gene_chunks[$i];
        $cc = $connect_chunks[$i];

        $input_id = $input_id . str_repeat(" ", $firstcol_size - strlen($input_id));
        $gene_id = $gene_id . str_repeat(" ", $firstcol_size - strlen($gene_id));
        $txt = $gene_id . $gc . PHP_EOL . $connect_id . $cc . PHP_EOL . $input_id . $ic;
        $result_txt = $result_txt . PHP_EOL . $txt. PHP_EOL;
    }
    
    
    $header = "DNA-Alignment of input sequence ".PHP_EOL.str_repeat("=",100);
    $result_txt = $header.$result_txt.PHP_EOL.PHP_EOL.PHP_EOL;
//    file_put_contents($file, $result_txt,FILE_APPEND);
    $filestr .= $result_txt;
    return $filestr;
    
}

// Function iupac_translate
// Takes a nucleotide sequence from position weight matrix and translates to iupac letter

function iupac_translate($nucleotides) {

    #Replace - with ""
    if ($nucleotides == '-') {
        $res = '-';
    } else {
        $nucleotides = str_replace('-', '', $nucleotides);

        $ntds = array('/^AG$/', '/^CT$/', '/^CG$/', '/^AT$/', '/^GT$/', '/^AC$/', '/^CGT$/', '/^AGT$/', '/^ACT$/', '/^ACG$/');
        $code = array('R', 'Y', 'S', 'W', 'K', 'M', 'B', 'D', 'H', 'V');
        $res = preg_replace($ntds, $code, $nucleotides);
    }
    return $res;
}
// Integrate the protein sequence parts
function integrateProteinSequenceParts($protein, $protseqs) {
    $poscount_array = array();

//  Check if all sequences have the same length  
    for ($i = 0; $i < count($protseqs); $i++) {
        if (strlen($protseqs[$i]) != strlen($protein)) {
            throw new Exception('Length of input sequence and gene sequence are not equal');
        }
    }
    # Filter Function

    function filterAAfun($x) {
        return $x !== 0;
    }

    $consseq = '';
    for ($i = 0; $i < strlen($protein); $i++) {
        $aas = array();
        foreach ($protseqs as $seq) {
            $char = substr($seq, $i, 1);
            if($char != '-' & $char != 'X'){
                array_push($aas,$char);
            }
        }
        $aasuni = array_unique($aas);
        # Remove -
        if(count($aasuni) == 0 || in_array('X', $aasuni)){
            $consseq = $consseq . '-';
        }else if(count($aasuni) > 1){
            $consseq = $consseq . 'X';
        }else{
            $consseq = $consseq . $aasuni[0];
        } 
    }

    return $consseq;
}

function print_result_protein_report($protein,$input,$protein_id,$input_id,$filestr){
    
    if(strlen($input) != strlen($protein)){
      
        throw new Exception('Length of input sequence and protein sequence are not equal');
    }
    
//  Generate number columns
//  Create connection row
    $connect_seq = "";
    for ($i = 0; $i < strlen($protein); $i++) {
        if (substr($protein, $i, 1) == '-' || substr($input, $i, 1) == '-') {
            $connect_seq = $connect_seq . " ";
        } else if (substr($protein, $i, 1) == substr($input, $i, 1)) {
            $connect_seq = $connect_seq . "|";
        } else {
            $connect_seq = $connect_seq . ".";
        }
    }

    $input_chunks = preg_split("/\n/",chunk_split($input, 50));
    array_pop($input_chunks);
    $protein_chunks = preg_split("/\n/",chunk_split($protein, 50));
    array_pop($protein_chunks);
    $connect_chunks = preg_split("/\n/",chunk_split($connect_seq, 50));
    array_pop($connect_chunks);

    $firstcol_size = max(strlen($input_id), strlen($protein_id)) + 5;
    $connect_id = str_repeat(" ", $firstcol_size);
    $result_txt = "";
    for ($i = 0; $i < count($input_chunks); $i++) {

        $ic = $input_chunks[$i];
        $gc = $protein_chunks[$i];
        $cc = $connect_chunks[$i];

        $input_id = $input_id . str_repeat(" ", $firstcol_size - strlen($input_id));
        $protein_id = $protein_id . str_repeat(" ", $firstcol_size - strlen($protein_id));
        $txt = $protein_id . $gc . PHP_EOL . $connect_id . $cc . PHP_EOL. $input_id . $ic;
        $result_txt = $result_txt . PHP_EOL . $txt. PHP_EOL;
    }
    
//   Translated input sequence 
    $header = "Protein-Alignment of input sequence ".PHP_EOL.str_repeat("=",100);
    $result_txt = $header.PHP_EOL.$result_txt.PHP_EOL.PHP_EOL.PHP_EOL;
    $filestr .= $result_txt;
//    file_put_contents($file, $result_txt,FILE_APPEND);
    return $filestr;
    
}