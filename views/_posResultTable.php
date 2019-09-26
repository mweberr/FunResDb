

<!--<br>-->
<div>
<hr>
</div>
<div class="container" id="myAnchor">
    <div class="col-md-9 col-md-offset-1">
    
    <h3 class="resist">Search results</h3>
    <h4 class="resist"> Your queried positions: </h4>
    <p> <?php echo implode($chosen_positions,","); ?> </p>
    
<br>
<br>

<?php
//Exact matches

$mutations = array();

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
//    echo '<p>The following strains contain the queried mutations : </p>';
//    
//    if(count($dup_muts) > 0){
//        $warning = '<div class="alert alert-warning"> WARNING: Some genotypes have ambiguous resistance phenotype.</div>';
//        echo $warning;
//    }
//    
//    echo '<div class="table-responsive">';
//    echo '<table cellpadding="10" cellspacing="5" class="table table-striped">';
//    echo '<tr>'
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
//
//        if(in_array($record['Mutations'],$dup_muts)){
//            echo '<td class="warn">' . $record['StrainID'] . ' </td>';
//        }else{
//            echo '<td >' . $record['StrainID'] . ' </td>';
//        }
//        
//        echo '<td>' . $record['Mutations'] . '</td>';
//        
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


?>


<br>
<!--<div class="greybg">
<p>
<b>Azole resistance classes:</b><br>
S - Sensitive, I - Intermediate, R - Resistant <br>
<br>
<b>Column descriptions:</b><br>
<b>GenotypeID</b> - Internal id of the Genotype
<b>Mutations</b> - Amino acid substitutions to reference protein<br>
<b>ITZ</b> - Resistance value for Itraconazole (S <= 1, R > 2);
<b>VOR</b> - Resistance value for Voriconazole (S <= 1, R > 2);
<b>POS</b> - Resistance value for Posaconazole (S <= 0.125, R > 0.25);
<b>ISA</b> - Resistance value for Isavuconazole (S <= 1, R > 2);
<b> PubmedIDs</b> - Pubmed references for the presented data
</p>
</div>-->

</div>
</div>