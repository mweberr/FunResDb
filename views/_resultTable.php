


<br>
<!--<div>
<hr>
</div>-->
<div class='container' id='myAnchor'>
<div class='col-md-9 col-md-offset-1'>
<!--<div id='results'>"-->
<h3 class='resist'>Search mutations using mutation names</h3>
 
<h4 class="resist">Your Query:</h4>
<!--<p> <b> Gene: </b> <?php echo $geneselect; ?> </p>-->
<p> <b> Gene: </b> <?php echo '<i>A. fumigatus</i> CYP51A (reference sequence <i>A.fumigatus</i> Strain ATCC36607)'; ?> </p>

<p> <?php echo filter_input(INPUT_POST, 'search'); ?> </p>
<h4 class="resist">Mutations found in your Query:</h4>
<p> 
<?php echo implode($mutations,", "); 
       if(AlignError::get_error('MUTAFORMAT')){
           $warn = AlignError::print_error('MUTAFORMAT', 'danger');
           echo $warn;
       }

?> 
 </p>


<?php

//print_r($mutations);
//$known_mutations = $search_results['known_mutations'];
//$new_mutations = $search_results['new_mutations'];
//$perfect_matches = $search_results['perfect_matches'];
//$imperfect_matches = $search_results['imperfect_matches'];
//$search_results_array = array();
//$search_results_subsets = array();
//global $db;
//print_r($search_results);
if (count($search_results) > 0) {
    $known_mutations = $search_results['known_mutations'];
    $new_mutations = $search_results['new_mutations'];
    $perfect_matches = $search_results['perfect_matches'];
    $imperfect_matches = $search_results['imperfect_matches'];
//        print_r($perfect_matches);
//        print_r($imperfect_matches);
//        print_r($new_mutations);
} else {
//    echo 'NO DNA search results';
    $known_mutations = array();
    $new_mutations = array();
    $perfect_matches = array();
    $imperfect_matches = array();
}


//echo $MESSAGE;

//echo "<h3 id='result_h3'>Search results:</h3>";  
//echo '<h4 class="resist">Your Query:</h4></div>'; 
//echo "<p>".filter_input(INPUT_POST, 'search')."</p></div>";             
//echo '<h4 class="resist">Mutations found in your Query:</h4>'; 
//echo "<p>".implode($mutations,", ")."</p>"; 
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
echo '<br>';
 
include '_result_MutationTable.php';

//if(count($search_table)>0){
//                
//    echo "<div ><p>The following strains contain the mutations you were looking for:</p></div>";
//    echo '<div class="table-responsive">';
//    echo '<table cellpadding="10" cellspacing="5" class="table table-striped">';
//    echo "<tr>"
//    . "<th>GenotypeID</th>"
//    . "<th>Mutations</th>"
//    . "<th>ITZ</th>"
//    . "<th>VOR</th>"
//    . "<th>POS</th>"
//    . "<th>ISA</th>"    
//    . "<th>PubmedIDs</th>"
//    . "</tr>";
//
//    foreach ($search_table as $record){
////        while($row = $record->fetch()){ 
////            $row['mutation_list'] = sort_by_pos($row['mutation_list'],"string");
//            $record['Pubmedlinks'] = design_Pubmedlinkstring($record['PubmedIDs']);
//            
//            echo "<tr>";
//            echo "<td>" . $record['StrainID'] . " </td>";                        
//            echo "<td>" . $record['Mutations'] . "</td>";
//            echo "<td>" . $record['ITZ'] . " </td>";                                                 
//            echo "<td>" . $record['VOR'] . " </td>";                         
//            echo "<td>" . $record['POS'] . " </td>";                                                 
//            echo "<td>" . $record['ISA'] . " </td>";         
//            echo "<td>" . $record['Pubmedlinks'] . "</td>";
//            echo "</tr>";                            
////        }
//    }
//    echo "</table>"; 
//    echo "</div>";
//}
            ##########
            //imperfect matches
########
//new mutations

//    if(count($new_mutations)>0){   
//        echo "</br><p>The following mutations are new to FungalResDb:</p>";
//        $new_mut_string = '';
//        foreach($new_mutations as $new_mutation){
//            $new_mut_string .= $new_mutation.", ";
//        }
//        $new_mut_string =  rtrim($new_mut_string, ", ");
//        echo $new_mut_string;
////        echo "<br>Please ";
////        echo '<a href="mailto:michael.weber@hki-jena.de?Subject=New mutations found: '.$new_mut_string.'" target="_top">Send us an eMail</a>!';
//        echo  '<a href="?site=message"> Please send us an email</a>';
//
//    }
//echo"</div>"; #result - CONTAINER ENDE
//
//echo "</br>";
//echo "</br>";
//echo "</br>";
//
//            echo '<div class="greybg">';
//            echo '<p>';
//            echo '<b>Azole resistance classes:</b><br>';
//            echo 'S - Sensitive, I - Intermediate, R - Resistant <br>';
//            echo '<br>';
//            echo '<b>Column descriptions:</b><br>';
//            echo '<b>GenotypeID</b> - Internal id of the Genotype; ';
//            echo '<b>Mutations</b> - Amino acid substitutions to reference protein; <br> ';
//            echo '<b>ITZ</b> - Resistance value for Itraconazol (S <= 1, R > 2); ';
//            echo '<b>VOR</b> - Resistance value for Voriconazol (S <= 1, R > 2); ';
//            echo '<b>POS</b> - Resistance value for Posaconazol (S <= 0.125, R > 0.25); ';
//            echo '<b>ISA</b> - Resistance value for Isavuconazole (S <= 1, R > 2);';
//            echo '<b> PubmedIDs</b> - Pubmed references for the presented data';
//            echo '</p>';
//            echo '</div>';
//
//
//echo "</div>";
//
//echo "</div>";
//
//<br>

?>

<br>

<h4 class="resist"> Feedback and comments </h4>
<p class="aqua"> Please send us a short feedback about your results.</p> <p>We are interested about your experience because we want to improve and extend the platform in the future.</p>
<button type="button" class="btn btn-default btn-sm" onClick='location.href="?site=message"'>
  <span class="glyphicon glyphicon-envelope"></span> Send feedback 
</button>

</div>
</div>
