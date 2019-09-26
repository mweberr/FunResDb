


<script> 
    function enterExampleSequence() {
//        var txt = document.getElementById("example_dna").value;
        var txt = "HALLLO";
        document.getElementById("example_dna").value = txt;
    }
    
    function loadSequence(id) {
            var xhttp = new XMLHttpRequest();
            var targetta = new Array(3);
            targetta[0] = 'dnaseqa';
            targetta[1] = 'dnaseqb';
            targetta[2] = 'dnaseqc';
           
            var seqfiles = new Array(3);
            seqfiles[0] = "assets/example_Seqs/Example_DNA1.seq";
            seqfiles[1] = "assets/example_Seqs/Example_DNA2.seq";
            seqfiles[2] = "assets/example_Seqs/Example_DNA3.seq";          
            
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById(targetta[id]).value = xhttp.responseText;
                }
            };
            xhttp.open("GET", seqfiles[id], true);
            xhttp.send();
        }
    
</script>

<div class='container'>
    <div  class= "col-md-9 col-md-offset-1">
                <div><h2>Searching Mutations</h2></div>
                <div id="gene"><h4>The cyp51a gene:</h4>
                <p><?php $placeholder2 ?></p>
                                <p><font color="red"><b><?=$MESSAGE?></b></font></p>
                </div>
                
                <form action = "?site=search#myAnchor" method = "POST" id="Mutations_searchform">
                    <div id="Search_by_mutations">  
                        </br>                        
                        <h3>Mutations</h3>
                        <p>Type in all mutations of the strain. (in X0Z or 0Z format (X = AS in wildtype, 0 = position in protein, Z = AS in mutant), and separated by comma).
                    <br><br></p>
<!--                        <input type="text" name="search" size="90" value ="<?php $placeholder?>"/>-->
                        <input type="text" name="search" size="60" value ="<?php $placeholder ?>"/>
                        </br>
                        </br>
                        <input type="submit" class="btn btn-default" name = "submit_search" id = "a" value="Find mutant>>"/>
                        </br>
                        </br>
                        </br>
                    </div>            
                </form>
                <form action = "?site=search#myAnchor" method = "POST" id="Sequence_searchform">
                    
                    <div id='Analyse-seq'>
                        </br>
                        <h3>Sequence</h3>
                        
                        </br>
                         <p id="long_Sentence">If you have a strain's cyp51a gene or protein Sequence(s) you can paste them here and click "Analyse Sequence":</p>
                        </br>  
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#DNA">DNA</a></li>
                            <li><a data-toggle="tab" href="#Protein">Protein</a></li>        
                        </ul>
                        <div class="tab-content">
                            <!--<div id = "DNA" class="tab-pane fade in avtive">-->
                            <div id = "DNA" class="tab-pane fade in active">
                                <div >
                                    <h4><font color="black">DNA-Sequence 1</font></h4>
                                    <textarea rows = "6" cols = "60" id="dnaseqa" name = "DNA-Sequence_A" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(0)"/>
                                    <br> <br>
                                    
                                    
                                    <!--<input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence>>"/>-->
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 2</font></h4>
                                    <textarea rows = "6" cols = "60" id="dnaseqb" name = "DNA-Sequence_B" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(1)"/>
                                    <br> 
                                    <br>                                
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 3</font></h4>
                                    <textarea rows = "6" cols = "60" id="dnaseqc" name = "DNA-Sequence_C" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(2)"/>
                                    <br> 
                                    <br>
                                    <input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence(s)>>"/>
                                </div>
                            </div>
                            <div id="Protein" class="tab-pane fade">
                                <h3>Protein</h3>
                                <textarea rows = "6" cols = "60" name = "Protein-Sequence"placeholder="Enter Protein-Sequence here"></textarea>
                                <br> 
                                <br>
                                <br>
                                <input type="submit" class="btn btn-default" name="analyse_seq_PROTEIN" value ="Analyse Sequence>>"/>
                            </div>  
                        </div>
                        </br>
                        </br>   
                    </div>                   
                </form>
                
    </div>
</DIV>
