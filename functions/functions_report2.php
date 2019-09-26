<?php

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


// Function print_result_report
// Prints each of the 1-3 three aligned sequences in one line and omits the separation / connection line

function print_result_report($alignments){
    
    if(strlen($input) != strlen($gene)){
        throw new Exception('Length of input sequence and gene sequence are not equal');
    }
    
//  Generate number columns
//  Create connection row
    
//  Create an array of 50bp chunks
    
    
    foreach($alignments as $alignment){
        $alignment['Seq_array'];
        $alignment['Gene_array'];
        $alignment['Input_ID'];
        
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
        $txt = $input_id . $ic . "\n" . $connect_id . $cc . "\n" . $gene_id . $gc;
        $result_txt = $result_txt . "\n\n" . $txt;
    }
    
    $file = "Testseq.txt";
    $header = "DNA-Alignment of input sequence "."\n".str_repeat("=",$firstcol_size+50);
    $result_txt = $header."\n\n".$result_txt;
    file_put_contents($file, $result_txt);
    
}



// Function integrate Sequence Parts
// Generate one sequence from three sequence parts
function integrateSequenceParts($gene, $dnaseqs) {
    $poscount_array = array();

//  Check if all sequences have the same length  
    for ($i = 0; $i < count($dnaseqs); $i++) {
        if (strlen($dnaseqs[$i]) != strlen($gene)) {
            throw new Exception('Length of input sequence and gene sequence are not equal');
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
            }
        }
        $nuctids = implode(array_keys(array_filter($ntds, "filterfun")));
        $res = iupac_translate($nuctids);

        $consseq = $consseq . $res;
    }

    return $consseq;
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
