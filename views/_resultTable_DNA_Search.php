
<!--<script>
    $(document).ready(function() {
        $("#report").click(function() {
            $.ajax({
                url: '../report/Report_8499.txt',
                success: function(x) {
                    alert("Success");
                },
                error: function() {
                    alert("Something error");
                }
            });
        });
    });
</script>-->



<?php
global $db;
global $MESSAGE;

ini_set('display_errors', 1);
ini_set('html_errors', 1);
error_reporting(E_ALL);



// Download the report if available
$res = $inputDao->findRecordBySession(session_id());
$_SESSION["report"] = $res["report"];
//prepareDownloadReport($res["report"],'Test.txt');
//echo $res["report"];
?>


<!--
<div>
    <hr>
</div>-->

<br>
<div class='container'>
    
    <!--<br>-->
<!--    <div class='col-md-9 col-md-offset-1'>
        <h3 class='resist'>Search mutations using DNA sequences</h3>
        <h4 class="resist">Your summary report:</h4>
        
        <p> The report contains all the relevant information which is shown on this site: alignment, mutated nucleotides and amino acids </p>
        <input type="submit" class="btn btn-default" id="report" onClick="location.href = 'database/Download.php'" name="print_report" value ="Download report file>>"/>
        <br>
        <br>
    </div>   -->

<div class='col-md-9 col-md-offset-1'>

<h3 class='resist'>Search mutations using DNA sequences</h3>

<h4 class="resist"> Your query </h4>


<p> <b> Gene: </b> <br> <?php echo '<i>A. fumigatus</i> CYP51A (reference sequence <i>A.fumigatus</i> Strain ATCC36607)'; ?> </p>
<p id='query'> <?php echo $query_for_resultpage ?> </p>

<br>
<h4 class="resist">Your summary report</h4>

<p> The report contains all the relevant information which is shown on this site: alignment, mutated nucleotides and amino acids </p>
<!--<input type="submit" class="btn btn-default" id="report" onClick="location.href = 'database/Download.php'" name="print_report" value ="Download report file>>"/>-->
<button type="submit" class="btn btn-default" id="report" onClick="location.href = 'database/Download.php'" name="print_report">
<span class="glyphicon glyphicon-download-alt"></span> Download report file </button>
<br>

<?php



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


$search_results_array = array();
$info_about_alignments = array();

//    $info_about_alignments = design_Outputstrings_for_results($Input_Alignments);
//    $info_about_alignments = 'Alignment was successful';
//    echo $info_about_alignments;   
//   Put Here Warning messages 
$frag_counter = 0;
display_ALL_DNA_Alignments($Input_Alignments);

echo "<br><br>";
display_prot_Alignment_Protein($Input_Alignments, false);
?>

<br>
<h4 class="resist"> Tandem repeat scan  </h4> 

<?php
if (!empty($Input_Alignments[0]['tandemres'])) {
    $tandemres = $Input_Alignments[0]['tandemres'];
    echo "The following tandem repeats were found in the promoter part of the input sequence:" . "<br>";

    if (count($tandemres) > 0) {

        echo '<div class="table-responsive">';
        echo "<table class='table smalltable'>";
        echo '<thead>';
        echo '<tr>';
        echo '<th> Size </th>';
        echo '<th> Count </th>';
        echo '<th> Identity </th>';
        echo '<th> Sequence </th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        for ($i = 0; $i < count($tandemres); $i++) {
            echo '<tr>';
            $tand = $tandemres[$i];
            echo '<td>' . $tand['size'] . '</td>';
            echo '<td>' . $tand['count'] . '</td>';
            echo '<td>' . $tand['ident'] . '</td>';
            echo '<td>' . $tand['seq'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
//    
//    for ($i = 0; $i < count($refseq_array); $i++) {
//    
//    foreach ($tandemres as $tandem) {
//        echo "TR" . strlen($tandem[1]) . "\t" . strtoupper($tandem[1]) . "<br>";
//    }


}else{
    
    if(AlignError::get_error('PROMOTER')){
        echo 'No tandem repeat was found in the promoter region of the input sequence.';
    }else{
        echo 'No promoter region was found in the input sequence.';
    }
    
}
?>

<br>
<br>
<h4 class="resist"> Amino acid substitutions </h4>
            


<?php
if(count($mutations) > 20){
    $msg = AlignError::print_error('HIGHMUTATE','danger');
    echo $msg;
}

//if ($msg != null) {
//    echo '<p class="warning"> ' . $msg . '</p>';
//}


// Print the found mutations
if (count($mutations) > 0 & count($mutations) < 20) {
    echo "<div><p> <b>" . implode($mutations,", ") . "</b></p></div>";
} else if(count($mutations) == 0) {
    echo "<div><p>None</p></div>";
}

// Provide mutations which were sofar unknown ///////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

//    if(count($perfect_matches)>0){
//        echo "<div ><p>The following strains contain <b>only</b> mutations found in your query:</p></div>";
//    if(count($perfect_matches)>0){
//        if(count($imperfect_matches)>0){

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
//    if(count($dup_muts) > 0){
//        $warn = AlignError::print_error('DUPGENO', 'warning');
//        echo $warn;
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

//
//    }
##########
//new mutations


//echo '</div>';




?>
<!--<br>
<div class="greybg">
<p>
<b>Azole resistance classes:</b><br>
S - Sensitive, I - Intermediate, R - Resistant <br>
<br>
<b>Column descriptions:</b><br>
<b>GenotypeID</b> - Internal id of the Genotype
<b>Mutations</b> - Amino acid substitutions to reference protein<br>
<b>ITZ</b> - Resistance value for Itraconazol (S <= 1, R > 2);
<b>VOR</b> - Resistance value for Voriconazol (S <= 1, R > 2);
<b>POS</b> - Resistance value for Posaconazol (S <= 0.125, R > 0.25);
<b>ISA</b> - Resistance value for Isavuconazole (S <= 1, R > 2);
<b> PubmedIDs</b> - Pubmed references for the presented data
</p>
</div>-->



<br>
<br>

<h4 class="resist"> Submit your sequence </h4>
<p> You can share your sequence alignment results with us by clicking the "Send feedback" button. This helps us to improve and extend the platform in the future.</p>
<button type="button" class="btn btn-default btn-sm" onClick='location.href="?site=message"'>
  <span class="glyphicon glyphicon-envelope"></span> Send feedback 
</button>

</div>
</div>


<!--<script> 
    
    $(document).ready(function(){
    $('.btn-default').click(function(){
        var clickBtnValue = $(this).val();
        var targetpage = 'test.php',
        data =  {'action': clickBtnValue};
        $.post(targetpage, data, function (response) {
            // Response div goes here.
            alert("action performed successfully");
        });
    });

});
</script>-->