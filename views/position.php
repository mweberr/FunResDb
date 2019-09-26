 <div class="container"> 
    <div id="main" class= "col-md-9 col-md-offset-1" >
        <div>
            <h2>Position Summaries</h2>
            <h4>The cyp51a gene:</h4>
            <p id="gene"><?=ReadFasta("assets/fasta/Cyp51aProt.fa");?></p>
        </div>
        <div>   
            <p><font color="red"><b><?php  $MESSAGE ?></b></font></p>
        <?php
        $possum = new positionSummary();
        //echo table to choose position 
        echo '<div class="row">';
        echo '<div class="col-md-12">';
        echo"<form action = '?site=positions#myAnchor' method = 'POST'>";
        echo '<div class="table-responsive">';
        echo "<div><table class='table-bordered'>";
        echo "<tr><th  colspan=515>ALL mutated Positions</th></tr>";
        echo "<tbody>";
        echo "<tr>";
        $positions = array_unique($possum->get_mut_Positions());
        sort($positions, SORT_NUMERIC );        
        foreach ($positions as $pos){                        
            echo "<td class='colored-cell'>".$pos."</td>";                                                
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($positions as $pos){
            echo "<td class='colored-cell'><input type='checkbox' name = 'check_list[]' value = '$pos'></td>";                        
        }
        echo "</tr>";
        echo "<tbody>";
        echo "</table></div>";
        echo '</div>';
        echo "<br>";
        echo "<input type=submit class='btn btn-default' name=submitALL515 value = 'Show Summary >>'>";
        echo"</form>";
        echo "<br>";
        echo "<br>";
         echo '<p><font color="red"><b>'.$MESSAGE.'</b></font></p>';
        echo '</div>';
        echo '</div>';
        ?>
        </div>
    </div>
</div>