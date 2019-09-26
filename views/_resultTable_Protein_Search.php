
<div class='container'>
<div class='col-md-9 col-md-offset-1'>

<!--echo $MESSAGE;-->

<br>
<h3 class='resist'> Search mutations using protein sequence </h3>
<h4 class='resist'> Your Query </h4>
<p id='query'> <?php echo $query_for_resultpage; ?> </p>
    
<?php

    if(count($search_results)>0){
        $known_mutations = $search_results['known_mutations'];
        $new_mutations = $search_results['new_mutations'];
        $perfect_matches = $search_results['perfect_matches'];
        $imperfect_matches = $search_results['imperfect_matches'];
    }else{
        $known_mutations = array();
        $new_mutations = array();
        $perfect_matches = array();
        $imperfect_matches = array();
    }

    $search_results_array = array();
    $search_results_subsets = array();    
    $covered_positions = array();
//    $info_about_alignments = design_Outputstrings_for_results_Protein($Alignments);
   
//    usort($Alignments, 'compare_pos_of_alignments_Protein');
   
//    echo $info_about_alignments;   
//    foreach($Alignments as $al){
//        $covered_positions = array_merge($covered_positions, $al['Ref_Pos_array']);        
//    } 
//    $covered_positions = array_unique($covered_positions);
//    $cov_vs_uncov = floor(100*count($covered_positions)/515);
//    echo "<br>";
//    echo "<b>".$cov_vs_uncov." % </b>of the Reference cyp51a Protein Sequence was covered by the aligned Fragments.";
//    echo "<br>";
    
//  Display the protein alignments  
    display_prot_Alignment_Protein($Alignments,true);
    
    echo '<br>';
    echo '<br>';
    echo '<h4 class="resist"> Amino acid substitutions </h4>';

    
    if(count($mutations)>0){
     echo "<p>".implode($mutations,", ")."</p>";
    }else{
        echo "<p>None</p>";
    }
   echo '<br>';
   
       //new mutations
    if (count($new_mutations) > 0 && count($new_mutations) < 20) {
        echo "</br><p>The following mutations are <b>not documented in FunResDb</b>:</p>";
        $new_mut_string = implode($new_mutations,", ");
        echo $new_mut_string;

        $msg = 'Dear NrzMYK team, I found the following mutations within the attached sequences: ' . $new_mut_string;
        $_SESSION['mutate_message'] = $msg;
    //        MessageFactory::init($messageTxt);
    //         file_put_contents('Mail_message.txt',$messageTxt);

        echo '<br>';
        echo '<p><a href="?site=message"> Please send us a short notification about your results.</a></p>';
    }
    echo '<br>';

    include '_result_MutationTable.php';
//$found_mutations = array();
//foreach($search_table as $record){
//    array_push($found_mutations,$record['Mutations']);
//}
//
//$dup_muts = array_diff_key($found_mutations,array_unique($found_mutations));
//
//
//if (count($search_table) > 0 & count($mutations) < 20) {
//
//    echo '<h4 class="resist"> Resistance data table</h4>';
//    echo '<p>The following strains contain the detected mutations : </p>';
//    $warning = '<div class="alert alert-warning"> WARNING: Some genotypes have ambiguous resistance phenotype.</div>';
//    echo $warning;
//    echo '<div class="table-responsive">';
//    echo '<table cellpadding="10" cellspacing="5" class="table table-striped">';
//    echo '<tr>'
//    . "<th> </th>"    
//    . "<th>GenotypeID</th>"
//    . "<th>Mutations</th>"
//    . "<th>ITZ</th>"
//    . "<th>VOR</th>"
//    . "<th>POS</th>"
//    . "<th>ISA</th>"
//    . "<th>PubmedIDs</th>"
//    . "</tr>";
//
////  Function echoTableLine: Print the table line with div colored box  
//    function echoTableLine($letter) {
//
//        switch($letter){
//            case 'S':
//                return '<td> <div class="sens">' . $letter . '</div> </td>';
//                break;
//            case 'R':
//                return '<td> <div class="resist">' . $letter . '</div> </td>';
//                break;
//            case 'I':
//                return '<td> <div class="inter">' . $letter . '</div> </td>';
//                break;
//             default:
//                 return '<td> </td>' ;
//                 break;
//                 
//        }
//    }
//
//    foreach ($search_table as $record) {
////        while($row = $record->fetch()){ 
////            $row['mutation_list'] = sort_by_pos($row['mutation_list'],"string");
//        $record['Pubmedlinks'] = design_Pubmedlinkstring($record['PubmedIDs']);
//
//        echo '<tr>';
//        if(in_array($record['Mutations'],$dup_muts)){
//            echo '<td class="warn">' . " ". ' </td>';
//        }else{
//            echo '<td>' . " ". ' </td>';
//        }
//        
//        echo '<td>' . $record['StrainID'] . ' </td>';
//        echo '<td>' . $record['Mutations'] . '</td>';
//        
////        echo '<td>' . $record['ITZ'] . ' </td>';
////        echo '<td>' . $record['VOR'] . ' </td>';
////        echo '<td>' . $record['POS'] . ' </td>';
////        echo '<td>' . $record['ISA'] . ' </td>';
//        echo echoTableLine($record['ITZ']);
//        echo echoTableLine($record['VOR']);
//        echo echoTableLine($record['POS']);
//        echo echoTableLine($record['ISA']);
//        echo '<td>' . $record['Pubmedlinks'] . '</td>';
//        echo '</tr>';
////        }
//    }
//    echo "</table>";
//    echo "</div>";
////  Include the caption   
//    include('_resultTable_caption.html');
//}

##########


//echo '</div>';

?>
<!--<br>
<div class="greybg">
<p>
<b>Azole resistance classes:</b><br>
<div class='senslgd'><p style='text-align:center;'>S</p></div> <p class="pad">  - Sensitive </p>
<div class='interlgd'><p style='text-align:center;'>I</p></div> <p class="pad">  - Intermediate </p>
<div class='resistlgd'><p style='text-align:center;'>R</p></div> <p class="pad"> - Resistant </p>
<br>
<b>Column descriptions:</b><br>
<b>GenotypeID</b> - Internal id of the Genotype <br>
<b>Mutations</b> - Amino acid substitutions to reference protein<br>
<b>ITZ</b> - Resistance value for Itraconazol (S <= 1, R > 2) <br>
<b>VOR</b> - Resistance value for Voriconazol (S <= 1, R > 2) <br>
<b>POS</b> - Resistance value for Posaconazol (S <= 0.125, R > 0.25) <br>
<b>ISA</b> - Resistance value for Isavuconazole (S <= 1, R > 2) <br>
<b> PubmedIDs</b> - Pubmed references for the presented data
</p>
</div>-->



<br>
<br>

<h4 class="resist"> Feedback and comments </h4>
<p class="aqua"> Please send us a short feedback about your results.</p> <p>We are interested about your experience because we want to improve and extend the platform in the future.</p>
<button type="button" class="btn btn-default btn-sm" onClick='location.href="?site=message"'>
  <span class="glyphicon glyphicon-envelope"></span> Send feedback 
</button>

</div>
</div>