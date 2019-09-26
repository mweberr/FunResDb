<?php

// Function format_mutations
//Takes string of comma sep mutations, checks format (maybe 0F format (0 = pos, F = as) or F0F format) 

function format_mutations($mutations_string){      
    $return_array= array();                        //returns array with mutations in F0F format     
    $mutations_string = preg_replace('/\s+/', '', $mutations_string);
    $mutations_string = strtoupper($mutations_string);
    $mutations = explode(",", $mutations_string);
    $parsed_mutations = array();
    
//    $wt = "MVPMLWLTAYMAVAVLTAILLNVVYQLFFRLWNRTEPPMVFHWVPFLGSTISYGIDPYKFFFACREKYGDIFTFILLGQKTTVYLGVQGNEFILNGKLKDVNAEEVYSPLTTPVFGSDVVYDCPNSKLMEQKKFIKYGLTQSALESHVPLIEKEVLDYLRDSPNFQGSSGRMDISAAMAEITIFTAARALQGQEVRSKLTAEFADLYHDLDKGFTPINFTLPWAPLPHNKKRDAAHARMRSIYVDIINQRRLDGDKDSQKSDMIWNLMNCTYKNGQQVPDKEIAHMMITLLMAGQHSSSSISAWIMLRLASQPKVLEELYQEQLANLGPAGPDGSLPPLQYKDLDKLPFHQHVIRETLRIHSSIHSIMRKVKSPLPVPGTPYMIPPGRVLLASPGVTALSDEHFPNAGCWDPHRWENQATKEQENDEVVDYGYGAVSKGTSSPYLPFGAGRHRCIGEKFAYVNLGVILATIVRHLRLFNVDGKKGVPETDYSSLFSGPMKPSIIGWEKRSKNTSK";
    $wt_array = str_split($wt);
    for($i =0; $i<count($mutations); $i++){     //check all strings between commas for mutation property        
        if(preg_match("/[A-Za-z]?\d+[A-Za-z]{1}/", $mutations[$i])){
           array_push($parsed_mutations, $mutations[$i]);
        }
    }
    foreach($parsed_mutations as $mut){
        if(is_numeric(substr($mut, 0, 1))){        // if mutation is in 0F format complete it with AS_wt
        $new_mut = '';
        $parsed_mutation = preg_split('[\d+]',$mut,-1,PREG_SPLIT_NO_EMPTY );
        $parsed_pos = preg_match('[\d+]',$mut,$posi_array,PREG_OFFSET_CAPTURE);        
        
        $posi_hae=$posi_array[0];
        $pos = $posi_hae[0];
        foreach($parsed_mutation as $i){
            $new_mut .= $wt_array[$pos-1];
            $new_mut .= $pos;
            $new_mut .= $parsed_mutation[0];
        }
        array_push($return_array, $new_mut);
        }
        else{                                       //mut is in f0f format
            array_push($return_array, $mut);
        }
    }    
    return $return_array;    
}


function readInputMutations($mutations_string){  

    $return_array = array();
    
//  Read the wildtype protein sequence
    $wt = BioSequence::readFastaFile("assets/fasta/AF222068_Protein.fasta");
    $wt_array = str_split($wt);
    
//  trim the string   
    $mutations_string = trim($mutations_string);
//  Remove not allowed characters
    $mutations_string = preg_replace('/[^a-z0-9;, ]/iu', '', $mutations_string);
    
    $mutations_string = preg_replace('/; |;|, |,|\s+/', ' ', $mutations_string);
    $mutations_string = strtoupper($mutations_string);
    $mutations = explode(" ", $mutations_string);
    
    foreach ($mutations as $k=>$mut) {
//      Check for each mutations whether they are in format XZY or ZY  
        if(preg_match("/^\d+\w$/",$mut)){
//          Get the wt aminoacid at pos
            $pos = intval(preg_replace("/^(\d+)\w$/","\\1",$mut));
            $mutations[$k] = $wt_array[$pos-1].$mut;
        }
    }
    return $mutations;
    
}

// Function design_Pubmedlinkstring
// Input $reflist: Array of PubmedIds
// Output: String of <a> Hyperlinks

//function design_Pubmedlinkstring($reflist){    
//    global $db;   
//    
//    if (is_array($reflist)){
//        $reflist = $reflist[0];
//    }
//    $ref_array = explode(",",$reflist);
//    
//    $number_of_pmids = count($ref_array);
//    $linkstring = '';
//    $ref_strings = array();
//    
//    foreach ($ref_array as $ref){
//        $j = 0;
//        $query = $db -> query("SELECT first_Author, year, title FROM Referenz WHERE pubmedid = '$ref' ORDER BY year") or die("could not search!".mysql_error());
//        
//        while($row = $query ->fetch()){
////        $row = $query ->fetch();
//               $first_Author = $row['first_Author'];
//               $year = $row['year'];
//               $title = $row['title'];               
//           }
//           $first_Author = preg_replace("/,.*/", "", $first_Author); //takes sirname of first author
//           
////           $first_title_chars = substr($title, 0, 2);           
////           $ref_string = $first_Author."(".$year.")_".$first_title_chars;
//           $ref_string = $first_Author."(".$year.")";
//           foreach ($ref_strings as $refstring){    //check if other pmid has same string
//               if ($refstring == $ref){
//                   $j ++;
//               }               
//           }
//           if ($j>0){
//               $ref_string = $first_Author."(".$year.")_".$j;
//           }
//           array_push($ref_strings, $ref_string);
//    }
//    
//    for($i =0; $i<($number_of_pmids-1); $i++){
//        $linkstring .= '<a href="http://www.ncbi.nlm.nih.gov/pubmed/?term='.$ref_array[$i].'" target="_blank">'.$ref_strings[$i].'</a>, ';    
//    }
//    
//    $linkstring .= '<a href="http://www.ncbi.nlm.nih.gov/pubmed/?term='.$ref_array[$number_of_pmids-1].'" target="_blank">'.$ref_strings[$number_of_pmids-1].'</a>'; //add 
////    last pmid(without comma)
//
//return $linkstring;
//}


function design_Pubmedlinkstring($idstring) {
    global $db;

    $ref_array = explode(',',$idstring);
    $linkstring = '';
    $ref_strings = array();

    $inQuery = implode(',', array_fill(0, count($ref_array), '?'));
    
    $stmt = $db->prepare(''
            . 'SELECT pubmedid,first_Author, year, title '
            . 'FROM Referenz '
            . 'WHERE pubmedid IN (' . $inQuery . ') '
            . 'ORDER BY year ');

    $stmt->execute($ref_array);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
//  Extract the row information  
    $pubmedids = array();
    foreach ($result as $row) {
        array_push($pubmedids,$row['pubmedid']);
        $first_Author = $row['first_Author'];
        $year = $row['year'];
        $title = $row['title'];
        
        $first_Author = preg_replace("/,.*/", "", $first_Author); //takes sirname of first author
//      Put first_author in brackets  
        $ref = $first_Author . "(" . $year . ")";
//      Need to check for duplicates of Authors  
//      
//                  foreach ($ref_strings as $refstring) {    //check if other pmid has same string
//if ($refstring == $ref) {
//$j ++;
//}
//}
//if ($j > 0) {
//$ref_string = $first_Author . "(" . $year . ")_" . $j;
        array_push($ref_strings, $ref);
}
//      Add the referenz to the array
        

    $linkstring = '';
    foreach(range(0,count($ref_strings)-1) as $i){
        $pmurl = '<a href="http://www.ncbi.nlm.nih.gov/pubmed/?term=';
        $linkstring .=  $pmurl.$pubmedids[$i] .'" target="_blank">'. $ref_strings[$i]. '</a>, ';
    }
    $linkstring = rtrim($linkstring, ", ");

     return $linkstring;
}

// Function display_ALL_DNA_Alignments
// Input: $Input_Alignments: Alignment arrays
// Output: Echo the HTML Code for DNA alignment table

function display_ALL_DNA_Alignments($Input_Alignments){
//    $gene_pos = range(1, 1548);
    $gene_seq = ReadFasta('assets/fasta/AF222068_Exon12.fasta');
    $gene_seq_array = str_split($gene_seq);
    
    
//     create input arrays: each pos: "-" or aligned input positions, one array for each input dna seq
    $input_array = array();
    foreach($Input_Alignments as $alignments){
        
        $input_id = $alignments['Input_ID'];
        $input_seq = str_split($alignments['Seq_array']);
        $input_array[$input_id] = $input_seq;  
    }
    
//  Scan if there are problems with different nucleotides in the overlap  
    $warn_overlap = false;
    for($i = 0; $i < count($gene_seq_array); $i++){
        $current_nts = array();

        foreach($input_array as $inseq){
            $nt = $inseq[$i];
//            $nt = preg_replace('/\W/','',$nt);
            if($nt != '-'){
              array_push($current_nts,$nt);  
            }   
        }
        $uni_nts = array_unique($current_nts);
        
        if(count($uni_nts) > 1){
            
//             print_r($uni_nts);
            $warn_overlap = true;
        }
    }

    
//  Determine number of matched positions
    $match_pos = array();
    foreach($input_array as $input_seq){
        $matches = findMatchPositions($input_seq);
        $match_pos = array_merge($match_pos,$matches);
    }
    $match_pos_uni = array_unique($match_pos);
    $cover_value = floor(100 * (count($match_pos_uni)/count($gene_seq_array)));
    
    echo '<br><br><h4 class="resist">The DNA-Alignment</h4>';
  
    if($warn_overlap){
//        $text = AlignError::print_error('OVERLAP','danger');
//        echo $text;
    }
    
//   Error message if less than 50 % of the reference sequence is covered the input sequence
    if($cover_value <= 50){
        $text = AlignError::print_error('ALIGN','danger');
        echo $text;
//        echo '<p class="warning"> WARNING: Input sequences cover less than 50% of the reference gene sequences. </p>';
    }
//   
//   Error message if gaps where found in the alignment 
     if (AlignError::get_error('GAPS')) {

        $text = AlignError::print_error('GAPS','danger');
        if ($text != null) {
            echo $text;
        }
    }
    
    //   Error message if reverse complement was used for the alignment.
     if (AlignError::get_error('REVCOMP')) {
        $text = AlignError::print_error('REVCOMP','warning');
        echo $text;
    }




//    echo "<h5>Alignment coverage:</h5>";
    echo " <b> <u>Coverage:</u> ".$cover_value." % </b> of the reference gene sequence is covered by the aligned input sequences.";
    echo '<br>';
    echo '<br>';
//    echo count(array_unique($match_pos))  . '<br>';
//    echo count($match_pos);

//    if (count($input_array) > 1) {
//        $overlap =  floor(100*(count($match_pos) - count(array_unique($match_pos))) / count(array_unique($match_pos)));
//        echo "<b>  <u>Overlap: </u> " . $overlap . " % </b> overlap of the aligned input sequences.";
//        echo "<br><br>";
//    }
    
    echo '<div class="table-responsive">';
        echo '<table>';
           echo '<tr>';
           echo '<td class = "headcol"></td>';
               for($i=0; $i < count($gene_seq_array); $i++){
                       echo '<td class="bodycol">'. ($i + 1) .'</td>';
               }
           echo '</tr>';
           echo '<tr>';
           echo'<td class = "headcol">GENE:</td>';
//           echo"<td>GENE:</td>";
               for($i = 0; $i < count($gene_seq_array); $i++){
                       echo '<td class="bodycol">'.$gene_seq_array[$i].'</td>';
               }

               echo "</tr>";
//                $input_counter =0;
                foreach($input_array as $key => $input_seq){  
                    //create Input name
//                    $input_name = preg_replace(array("(A)","(B)","(C)"),array("DNA_1","DNA_2","DNA_3"),$key);
                    $input_name = $key;
//                    $input_counter ++;
                    echo "<tr>";
                    echo "<td class = 'headcol'>". $input_name.":"."</td>";
//                    echo "<td>". $input_name.":"."</td>";
                    for($i = 0; $i < count($input_seq); $i++){
//                        $input_name = "DNA".$input_counter;
//                        echo "<td class = 'headcol'>".$input_name."</td>";
                        if(( $gene_seq[$i] != $input_seq[$i]) && ($input_seq[$i] != '-')){
//                            echo "<td><font color='red'><b>".$input_seq[$i]."</b></font></td>";
                            echo '<td class="bodycol" bgcolor="red"><b>'.$input_seq[$i].'</b></td>';
                        }else{
                            echo '<td class="bodycol">'.$input_seq[$i].'</td>';
                        }                   
                       
                   }
                    echo "</tr>";
               }
           echo "</table>";
       echo '</div>';
}


// Function display_prot_Alignment_Protein
// Input: $Input_Alignments: Alignment arrays
//        $print_coverage: Boolean value 
// Output: Echo the HTML Code for protein alignment table
function display_prot_Alignment_Protein($Input_Alignments,$print_coverage) {               //need function to show: 1st line: ref seq position, 2nd line: ref seq as, 3rd line: seq 1 aligned parts, 4th line: seq 2 aligned parts...
    $refseq = ReadFasta('assets/fasta/AF222068_Protein.fasta');
    $refseq_array = str_split($refseq);
//    $input_seq_array = array();

    $input_array = array();
    foreach ($Input_Alignments as $alignments) {
        $input_id = $alignments['Input_ID'];
//        $input_seq = str_split($alignments['Seq_array']);
//        $gene_seq = str_split($alignments['Gene_array']);

        $input_array[$input_id] = str_split($alignments['Prot_seq']);
    }

//  Determine number of matched positions i.e. positions which have no gap symbol
    echo '<h4 class="resist">The Protein-Alignment</h4>';
    
    if ($print_coverage) {
        $match_pos = array();
        foreach ($input_array as $input_seq) {
            $matches = findMatchPositions($input_seq);
            $match_pos = array_merge($match_pos, $matches);
        }
        $match_pos = array_unique($match_pos);
        $cover_value = floor(100 * (count($match_pos) / count($refseq_array)));

        
//      echo "<h5>Alignment coverage</h5>";
       
        echo "<b> <u>Coverage:</u> " . $cover_value . "% </b> of the reference cyp51a protein sequence is covered by the aligned input sequences.";
        echo '<br><br>';
    }

    echo '<div class="table-responsive">';
    echo "<table>";
    echo "<tr>";
    echo '<td class = "headcol"></td>';
    for ($i = 0; $i < count($refseq_array); $i++) {
        echo '<td class="bodycol">' . ($i + 1) . '</td>';
    }
    echo "</tr>";
    echo "<tr>";
    echo '<td class = "headcol">CYP51A: </td>';
    for ($i = 0; $i < count($refseq_array); $i++) {

        echo '<td class="bodycol">' . $refseq_array[$i] . '</td>';
    }
    echo '</tr>';
    
    foreach ($input_array as $key => $input_seq) {
//      Create inputName  
//        $input_name = preg_replace(array("(A)","(B)","(C)"),array("Prot_1","Prot_2","Prot_3"),$key);
        $input_name = $key;
        echo '<tr>';
        echo '<td class = "headcol">'. $input_name.":".'</td>';
        for ($i = 0; $i < count($input_seq); $i++) {
            if ($input_seq[$i] == 'X' || $input_seq[$i] == '-') {
                echo '<td class="bodycol">' . '-' . '</td>';
            } else if ($input_seq[$i] != $refseq[$i]) {
//                echo "<td><font color='red'>" . $input_seq[$i] . "</font><td>";
                  echo '<td class="bodycol" bgcolor="red"><b>' . $input_seq[$i] . '</b></td>';
            }else{
                echo '<td class="bodycol">' . $input_seq[$i] . '</td>';
            }
        }

        echo "</tr>";
    }

    echo "</table>";
    echo '</div>';
    echo '<br>';
}

