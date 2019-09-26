<?php

//if (count($new_mutations) > 0 && count($new_mutations) < 20) {
//    echo "</br><p>The following mutations are <b>not documented in FunResDb</b>:</p>";
//    $new_mut_string = implode($new_mutations, ", ");
//    echo $new_mut_string;
//
//    $msg = 'Dear NrzMYK team, I found the following mutations within the attached sequences: ' . $new_mut_string;
//    $_SESSION['mutate_message'] = $msg;
////        MessageFactory::init($messageTxt);
////         file_put_contents('Mail_message.txt',$messageTxt);
//
//    echo '<br>';
//    echo '<p><a href="?site=message"> Please send us a short notification about your results.</a></p>';
//    echo '<br>';
//    echo '<br>';
//    
//}
//    if(count($perfect_matches)>0){
//        echo "<div ><p>The following strains contain <b>only</b> mutations found in your query:</p></div>";
//    if(count($perfect_matches)>0){
//        if(count($imperfect_matches)>0){

//echo 'SearchTable size: '.count($search_table);



if (count($search_table) > 0 & count($mutations) < 20) {
    
    $found_mutations = array();
    foreach ($search_table as $record) {
        array_push($found_mutations, $record['Mutations']);
    }

    $dup_muts = array_diff_key($found_mutations, array_unique($found_mutations));

    echo '<h4 class="resist"> Resistance data table</h4>';
    echo '<p>The following strains contain the detected mutations : </p>';
    if (count($dup_muts) > 0) {
        $warn = AlignError::print_error('DUPGENO', 'warning');
        echo $warn;
    }

    echo '<div class="table-responsive">';
    echo '<table cellpadding="10" cellspacing="5" class="table table-striped">';
    echo '<tr>'
    . "<th>GenotypeID</th>"
    . "<th>Mutations</th>"
    . "<th>ITZ</th>"
    . "<th>VOR</th>"
    . "<th>POS</th>"
    . "<th>ISA</th>"
    . "<th>PubmedIDs</th>"
    . "</tr>";

//  Function echoTableLine: Print the table line with div colored box  
    function echoTableLine($letter) {

        switch ($letter) {
            case 'S':
                return '<td> <div class="sens">' . $letter . '</div> </td>';
                break;
            case 'R':
                return '<td> <div class="resist">' . $letter . '</div> </td>';
                break;
            case 'I':
                return '<td> <div class="inter">' . $letter . '</div> </td>';
                break;
            default:
                return '<td> </td>';
                break;
        }
    }

    foreach ($search_table as $record) {
//        while($row = $record->fetch()){ 
//            $row['mutation_list'] = sort_by_pos($row['mutation_list'],"string");
        $record['Pubmedlinks'] = design_Pubmedlinkstring($record['PubmedIDs']);

        echo '<tr>';
        
                
        if (in_array($record['Mutations'], $dup_muts)) {
            echo '<td class="warn">' . $record['StrainID'] . ' </td>';
        } else {
            echo '<td >' . $record['StrainID'] . ' </td>';
        }

        echo '<td>' . $record['Mutations'] . '</td>';

        echo echoTableLine($record['ITZ']);
        echo echoTableLine($record['VOR']);
        echo echoTableLine($record['POS']);
        echo echoTableLine($record['ISA']);
        echo '<td> ' . $record['Pubmedlinks'] . ' </td>';
        echo '</tr>';
//        }
    }
    echo "</table>";
    echo "</div>";
//  Include the caption   
    
   
    include('_resultTable_caption.html');
}