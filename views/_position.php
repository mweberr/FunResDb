 


<?php

$fn = "assets/fasta/AF222068_Protein.fasta";
$cypseq = new BioSequence('cyp51a');
$protseq = $cypseq->readProteinSequence($fn);

//$possum = new positionSummary();
$posdao = new PositionDao(DBFactory::getConnection());
?>

<div class="container"> 
    <div class="row">
    <div class= "col-md-9 col-md-offset-1" >
        <h2>Explore positions </h2>
        <div class="start">
                    <p> The explore function provides an overview about resistance mutation position for a specific protein and allows to retrieve the associated literature data. </p>
                </div>  
        
        <div class="select_species">
            <h3>Select gene</h3>
            <!--<p> Choose the gene you are interested in:</p>-->
            <!--<div class="form-group">-->
            <div class="row">
                <div class="col-md-6">
                    <!--<label for="sel1"> Select Gene: </label>-->
                    <select class="form-control" id="sel1">
                        <option> 
                            A. fumigatus CYP51A (reference sequence A.fumigatus Strain ATCC36607)
                        </option>  
                    </select>
                </div>
            </div>
        </div>
        
        
        <h4> Protein sequence</h4>
        <p id="gene"><?php echo $protseq; ?></p>
         
            <p><font color="red"><b><?php $MESSAGE ?></b></font></p>
                    <form action = '?site=positions#myAnchor' method = 'POST'>
                        
                        
                        <div class="table-responsive">
                            <table class='table-bordered'>
                                    <tr><th  colspan=515> Protein sequence positions associated with drug resistance </th></tr>
                                    <tbody>
                                        <tr>

                                        <?php
                                        $positions = $posdao->getResistantPositions();
  
                                        foreach ($positions as $pos) {
                                            echo "<td class='colored-cell'>" . $pos . "</td>";
                                        }
                                        echo "</tr>";
                                        echo "<tr>";
                                        foreach ($positions as $pos) {
                                            echo "<td class='colored-cell'><input type='checkbox' name = 'check_list[]' value = '$pos'></td>";
                                        }
                                        echo "</tr>";
                                        ?>
                                    </tbody>
                                </table>
                        </div>
                        <br>
                        <input type=submit class='btn btn-default' value = 'Show Summary >>'>
                    </form>
                     <!--'<p><font color="red"><b>'.$MESSAGE.'</b></font></p>';-->
            </div>
      </div>
</div>

