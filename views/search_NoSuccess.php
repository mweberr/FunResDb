<div class='container'>
    <div  class= "col-md-9 col-md-offset-1">
                <div><h2>Searching Mutations</h2></div>
                <div id="gene"><h4>The cyp51a gene:</h4>
                <p><?=$placeholder2?></p>
                <p><font color="red"><b><?=$MESSAGE?></b></font></p>
                </div>
                
                <form action = "?site=search#myAnchor" method = "POST" id="Mutations_searchform">
                    <div id="Search_by_mutations">  
                        </br>                        
                        <h3>Mutations</h3>
                        <p>Type in all mutations of the strain. (in X0Z or 0Z format (X = AS in wildtype, 0 = position in protein, Z = AS in mutant), and separated by comma).
                    <br><br></p>
                        <!--<input type="text" name="search" size="90" value ="<?=$placeholder?>"/>-->
                        <input type="text" name="search" size="60" value ="<?=$placeholder?>"/>
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
                                    <textarea rows = "6" cols = "60" name = "DNA-Sequence_A">Enter DNA-Sequence here...</textarea>
                                    </br> </br>
                                    <!--<input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence>>"/>-->
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 2</font></h4>
                                    <textarea rows = "6" cols = "60" name = "DNA-Sequence_B">Enter DNA-Sequence here...</textarea>
                                    </br> </br>                                
                                </div>
                                <div >
                                    <h4><font color="black">DNA-Sequence 3</font></h4>
                                    <textarea rows = "6" cols = "60" name = "DNA-Sequence_C">Enter DNA-Sequence here...</textarea>
                                    </br> </br>
                                    <input type="submit" class="btn btn-default" name="analyse_seq_DNA" value ="Analyse Sequence(s)>>"/>
                                </div>
                            </div>
                            <div id="Protein" class="tab-pane fade">
                                <h3>Protein</h3>
                                <textarea rows = "6" cols = "60" name = "Protein-Sequence">Enter Protein-Sequence here...</textarea>
                                </br> </br>
                                <input type="submit" class="btn btn-default" name="analyse_seq_PROTEIN" value ="Analyse Sequence>>"/>
                            </div>  
                        </div>
                        
                        
                        
                        </br>
                        </br>
                        
    
                    </div>                   
                </form>
                
    </div>
</DIV>