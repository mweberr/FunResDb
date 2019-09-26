


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
            targetta[3] = 'proteinseq';
           
            var seqfiles = new Array(3);
            seqfiles[0] = "assets/example_Seqs/Example_DNA1.seq";
            seqfiles[1] = "assets/example_Seqs/Example_DNA2.seq";
            seqfiles[2] = "assets/example_Seqs/Example_DNA3.seq"; 
            seqfiles[3] = "assets/example_Seqs/Example_Protein.seq";
            
                    
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
    <div class="row">
    <div class= "col-md-9 col-md-offset-1">
                <h2>Search the database</h2>
                <div class="start">
                    <p> The search function allows you to collate information on defined mutations in resistance associated genes or analyse your own DNA/protein sequences for resistance mutations.</p>
                </div>           
                    <form action = "?site=search#myAnchor" method = "post" id="Mutations_searchform">  
                    <div id="accordion">    
                    <div class="select_species">
                        <h3>Select gene</h3>
                        <!--<p> Choose the gene you are interested in:</p>-->
                        <!--<div class="form-group">-->
                        <div class="row">
                            <div class="col-md-8">
                            <!--<label for="sel1"> Select Gene: </label>-->
                            <select class="form-control" name="geneselect" id="geneselect">
                                <option value="cyp51a" id="cypOption" selected="selected"> 
                                    A. fumigatus CYP51A (wild-type sequence A.fumigatus strain AF338659) 
                                </option>  
                             </select>
                            <p id="here"><p>
                            </div>
                        </div>
                    </div>
    
                    <div id="Search_by_mutations">  
                        <br>                       
                        <h3>Analyse Mutations</h3>
                        <div>
                        <p> Type in a defined mutation or series of mutations to find published information: <br>
                            <b>Format:</b>  [WT][POS][MUT] or [POS][MUT] <br>
                            ([WT] = AS in wildtype, [POS] = position in protein, [MUT] = AS in mutant)) <br>
                            <b>Example:</b> F46Y M172V
                        </p>

                    <div class="row">
                       <div class="col-md-8">
                        <input type="text" class="form-control" name="search" value =""/>
                        <br>
                        <input type="submit" class="btn btn-default" name = "submit_search" id = "a" value="Find mutation(s) >>"/>
                        <br>
                        <br>
                        <br>
                        </div>
                        </div>
                    </div>   
                  </div>
                </form>
                

                
                <form action = "?site=search#myAnchor" method = "POST" id="Sequence_searchform">
                    
                    <div id='Analyse-seq'>
                        </br>
                        <h3>Scan sequence</h3>
                        <br>
                        <h4>Instructions</h4>
                         <p>Using this function you can analyze your own DNA or protein sequence. The CYP51A Gene can be amplified with 3 primer-pairs (i.e. Cyp1-L/Cyp1-R, Cyp2-L/Cyp2-R, Cyp3-L/Cyp3-R (<a href="http://www.ncbi.nlm.nih.gov/pubmed/15563516" target="_blank">Chen et al. 2005</a>), Cyp1-L and Cyp3-R being located outside the coordinated region. The amplification is carried out as described by (<a href="http://www.ncbi.nlm.nih.gov/pubmed/15563516" target="_blank">Chen et al. 2005</a>) with an elevated number of cycles : 95°C 5min, 36 cycles 95°C 30sec, 58°C 30sec, 72°C 1min; one cycle 72°C 10min in a PCR approach using commercial ingredients. Sequencing of the 3 products is done with both primers each. This will result in three sequences covering the entire CYP51A gene, which can be pasted in as sequence 1,2 and 3. The algorithm will automatically extract relevant sequence information. Therefore any sequence obtained by other methods, partially or completely covering A. fumigatus CYP51A can also be entered and analysed using our algorithm. Our algorithm does also provide a tool for analyzing sequence repeats in the CYP51A promoter region.  For sequencing the promoter region, i.e. the primer-pair A5/A7 (<a href="http://www.ncbi.nlm.nih.gov/pubmed/11427550" target="_blank">Mellado et al. 2001</a>) can be used. </p>
                        </br>  
                       
                        
                          <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#DNA">DNA</a></li>
                            <li><a data-toggle="tab" href="#Protein">Protein</a></li>        
                        </ul>
                        
                        <div class="tab-content">
                            <div id ="DNA" class="tab-pane fade in active">
                                <div>
                                    <h4><font color="black">DNA-Sequence 1</font></h4>
                                    <textarea class="form-control" rows = "6" id="dnaseqa" name = "DNA-Sequence_A" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(0)"/>
                                    <br> <br>                                    
                                    <!--<input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence>>"/>-->
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 2</font></h4>
                                    <textarea class="form-control" rows = "6" id="dnaseqb" name = "DNA-Sequence_B" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(1)"/>
                                    <br> 
                                    <br>                                
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 3</font></h4>
                                    <textarea class="form-control" rows = "6" id="dnaseqc" name = "DNA-Sequence_C" placeholder="Enter DNA-Sequence here..."></textarea>
                                    <br>
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(2)"/>
                                    <br> 
                                    <br>
                                    <input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence(s)>>"/>
                                </div>
                            </div>
                            <div id="Protein" class="tab-pane fade">
                                <h3>Protein</h3>
                                <textarea class="form-control" rows = "6" cols = "60"  id="proteinseq" name = "Protein-Sequence" placeholder="Enter Protein-Sequence here"></textarea>
                                <br> 
                                    <input type="button" class="btn btn-sm" name="example_DNA_bt" value ="Use example sequence" onclick="loadSequence(3)"/>
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
    </div>
</div>
</div>
