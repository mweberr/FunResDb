<?php


// Generates a new session id

session_start();

session_regenerate_id();


class SearchController {
    
    
    public function readInputMutations($mutations_string) {

        $return_array = array();

//  Read the wildtype protein sequence
        $wt = BioSequence::readFastaFile("assets/fasta/AF222068_Protein.fasta");
        $wt_array = str_split($wt);

//  trim the string   
        $mutations_string = trim($mutations_string);
        
//        if(preg_match('/[^a-z0-9;, ]/iu', $mutations_string)){
//            $warn = AlignError::print_error('MUTAFORMAT', 'danger');
//            echo $warn;
//        }
        
//      Remove not allowed characters
        $mutations_string = preg_replace('#[^a-z0-9;,/ ]#iu', '', $mutations_string);
//      Substitute all separators with white space  
        $mutations_string = preg_replace('#; |;|, |,|/|\s+#', ' ', $mutations_string);
//      To upper characters  
        $mutations_string = strtoupper($mutations_string);
//      Split the stirng  
        $mutations = explode(" ", $mutations_string);
        
//      Check if mutations match the required  pattern
        $real_mutations = array();
        $tandem_reps = array();
        foreach($mutations as $mut){
            if(preg_match('/[a-z]\d+[a-z]/i',$mut)){
                array_push($real_mutations, $mut);
            }elseif(preg_match('/\d+[a-z]/i',$mut)){
                array_push($real_mutations, $mut);
            }elseif(preg_match('/^TR[0-9]+$/i',$mut)){
                array_push($real_mutations, $mut);
            } 
        }
//      Send a warning
        if(count($mutations) != count($real_mutations)){
            
            AlignError::set_error('MUTAFORMAT');
            if(count($mutations) == 0){
                $warn = AlignError::print_error('MUTAFORMAT', 'danger');
                echo $warn;
            }
        }
        
//      Check for each mutations whether they are in format XZY or ZY       
        if (count($real_mutations) > 0) {
            foreach ($real_mutations as $k => $mut) {

                if (preg_match("/^\d+\w$/", $mut)) {
//          Get the wt aminoacid at pos
                    $pos = intval(preg_replace("/^(\d+)\w$/", "\\1", $mut));
                    if ($pos >= 0 & $pos < count($wt_array)) {
                        $mn = $wt_array[$pos - 1] . $mut;
                        $real_mutations[$k] = $wt_array[$pos - 1] . $mut;
                    }
                }
            }
        }
        
        
        return $real_mutations;
    }

    public function readInputSequence($inputseq){
        
        if (strlen($inputseq) > 5000) {
            $warn = AlignError::print_error('LONG', 'danger');
            echo $warn;
            $inputseq = '';
            
        } else {

            $inputseq = strtoupper($inputseq);

//                  Read the FASTA file
            if ($inputseq[0] == '>') {
                $inputseq = BioSequence::readFastaInput($inputseq);
            }

//      Replace whitespaces  
            $inputseq = preg_replace('/\s+/', '', $inputseq);

//          Check whether non DNA characters are present
            if (preg_match('/[^acgt]/iu', $inputseq)) {
                $warn = AlignError::print_error('DNABASE', 'danger');
                echo $warn;
            }

//          Replace non DNA characters
            $inputseq = preg_replace('/[^acgt]/iu', '', $inputseq);

            if (strlen($inputseq) < 45) {
//           $MESSAGE .= "<br>DNA-Sequence 1 is too short (".strlen($temp_seq).")!";
                $warn = AlignError::print_error('SHORT', 'danger');
                echo $warn;
            }
        }

        return $inputseq;
    }
    
    public function renderPage(){
//        $placeholder2 = ReadFasta("assets/fasta/AF222068_Protein.fasta");
//        global $MESSAGE;
        
        $mutations = null;
        
//      Flag for all input fields  
        $flag_input = false;  
        


//      Submit mutation names  
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        if(isset($_POST['submit_search'])){
            
            if ($_POST['geneselect'] == "cyp51a") {
                $geneselect = 'A. fumigatus CYP51A (A. fumigatus Strain ATCC36607)';
            }

////***Mutations Search
            $mutations = $this->readInputMutations($_POST['search']);
            
            if(count($mutations) > 0){
                
                    $flag_input = true;
                    $mutantDao = new MutantDao(DBFactory::getConnection());
                
//                  Search results is an array of 'new_mutations', 'known_mutations', 'perfect_matches', 'imperfect_matches'
                    $search_results = $mutantDao->findMutants($mutations);
//                    print_r($search_results['known_mutations']);
                    
//                  Search Mutants from Mutations
                    $strain_ids = $mutantDao->getMutantIDsFromMutationNames($search_results['known_mutations']);
                    $search_table = $mutantDao->getMutantTable($strain_ids);                  

                }else{
//                    $MESSAGE .= "<br>Warning: NO MUTATIONS FOUND!";
                    $search_results = array();
                }              
            include "views/_header.php";
            
            if ($flag_input) {
                include "views/_resultTable.php";
            } else {
                include "views/_search.php";
            }
            include "views/_footer.php";
            
//      Submit DNA Sequences
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        }elseif(isset($_POST['analyse_seq_DNA'])){   ////***DNA Search    
            
            
            
            $query_for_resultpage = '';

            //Get inputs from all 3 textfields
            $Input_Seqs = array('Seq1'=>null, 'Seq2'=>null, 'Seq3'=>null);
            $flag_input = false;
            
//          Input textarea DNA-Sequence A  
            if($_POST['DNA-Sequence_A'] != ''){
                
                $inputseq = $_POST['DNA-Sequence_A'];
                $inputseq_filtered = $this->readInputSequence($inputseq);
                $query_for_resultpage .= "<br>DNA-Sequence 1:<br>" . $inputseq_filtered . "<br><br>";
                
                if(strlen($inputseq_filtered) > 45){
                    $Input_Seqs['Seq1'] = array('Input_1', $inputseq_filtered);
                    $flag_input = true;
                }
                
            }
            
//          Input textarea DNA-Sequence B  
            if($_POST['DNA-Sequence_B'] != ''){
                
                $inputseq = $_POST['DNA-Sequence_B'];
                
                $inputseq_filtered = $this->readInputSequence($inputseq);
                $query_for_resultpage .= "<br>DNA-Sequence 2:<br>" . $inputseq_filtered . "<br><br>";
                
                if(strlen($inputseq_filtered) > 45){
                    $Input_Seqs['Seq2'] = array('Input_2', $inputseq_filtered);
                    $flag_input = true;
                }
                
            }
//          Input textarea DNA-Sequence C 
            if($_POST['DNA-Sequence_C'] != ''){
                
                $inputseq = $_POST['DNA-Sequence_C'];   
                
                $inputseq_filtered = $this->readInputSequence($inputseq);
                $query_for_resultpage .= "<br>DNA-Sequence 3:<br>" . $inputseq_filtered . "<br><br>";
                
                if(strlen($inputseq_filtered) > 45){
                    $Input_Seqs['Seq3'] = array('Input_3', $inputseq_filtered);
                    $flag_input = true;
                }
            }
            
            
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //check if Seq is empty            
            //no sequence was entered -> reload page and give warning     
            if (!$flag_input){                        
//                $MESSAGE .= "<br>WARNING: NO INPUT FOUND!";
            }else{
                
//                if ($_POST['geneselect'] == "cyp51a") {
//                    $geneselect = 'cyp51a (Aspergillus fumigatus Strain ATCC36607)';
//                }

                $mutantDao = new MutantDao(DBFactory::getConnection());      
                ////new search mutant object -> search model
                //create Alignmet_array with alignmets (start Al gene, End AL gene, start AL seq, end AL seq, translated Prot-seq of aligned fragment)
                $Input_Alignments = array();
                
                if (isset($Input_Seqs['Seq1'])){                   
                    
                    $seqname =  $Input_Seqs['Seq1'][0];
                    $Sequence = $Input_Seqs['Seq1'][1];  
                    
                    $alignment = process_DNA_Input($Sequence, $seqname);                    
                    
                    array_push($Input_Alignments, $alignment);                    
                }
                if (isset($Input_Seqs['Seq2'])){
                    
                    $seqname =  $Input_Seqs['Seq2'][0];
                    $Sequence = $Input_Seqs['Seq2'][1];
                    
                    $alignment = process_DNA_Input($Sequence, $seqname);
                    
                    array_push($Input_Alignments, $alignment);
                }
                if (isset($Input_Seqs['Seq3'])){
                    
                    $seqname =  $Input_Seqs['Seq3'][0];
                    $Sequence = $Input_Seqs['Seq3'][1];  
                    
                    $alignment = process_DNA_Input($Sequence, $seqname);
                    array_push($Input_Alignments, $alignment);
                }  
                
//              Check if the alignment was successful
                if(AlignError::get_error('EMBOSS')){
                    include "views/_header.php";
                    $text = AlignError::print_error('EMBOSS','danger');
                    if ($text != null) {
                        echo $text;
                    }
                    include "views/_footer.php";
                }else{
                    
               
                $mutations = array();
                $search_results = array();
                $search_table = array();
                //One Alignments array for each input seq, containing
                foreach ($Input_Alignments as $alignment){                  
//                        $DNA = $al['Seq_array'];                  
//                        echo 'Alignment count' . strlen($alignment['Gene_array']);
                        
                        $matches = findMatchPositions(str_split($alignment['Seq_array']));
                        $mutations = array_merge($mutations, $alignment['Mutations']); //collect all mutations from all alignment 
                }
                //  make mutations unique
                $mutations = array_unique($mutations);
                //  $mutations = format_mutations($mutations); 
//              Check the size of the mutation vector
                if(count($mutations) > 20){
                    
                    AlignError::set_error('HIGHMUTATE');
                }
                    
                    
                
//              Start the database search for mutations  
                if(count($mutations)>0){
                    $search_results = $mutantDao->findMutants($mutations);
//                  Search Mutants from Mutations
                    if (count($search_results['known_mutations']) > 0) {
                        $strain_ids = $mutantDao->getMutantIDsFromMutationNames($search_results['known_mutations']);
                        $search_table = $mutantDao->getMutantTable($strain_ids);
                    }
                }else{
//                  No database match for detected mutations  
                }
                
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//              Print the report
                //  Report file name  
                
                
//                $reportfn = 'report/Report_'. date('Y-m-d')."_". rand(10, 1000).'.txt';
//                $reportfn = 'report/Report_'. session_id().'.txt';
//                
                $reportdata = "";
//                  if (file_exists($reportfn)) {
//                    unlink($reportfn);
//                }
              
                $reportdata = print_report($Input_Seqs,$Input_Alignments,'dna',$reportdata);
//                if(file_exists($reportfn)){
//                    
//                }
//                $reportdata = file_get_contents($reportfn);
                
                
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/////////////// Store queries in the database
//              Get the IP Address
                $ip = getIpAddress();

//              Get the session id  
                $sid = session_id();
                
//              Save mutations, dna sequences, protein sequences and report in database
                $data = array('ipaddress'=>$ip,'session_id'=> $sid, 'mutations'=>null,'dnaseq1'=>$Input_Seqs['Seq1'][1],
                    'dnaseq2'=>$Input_Seqs['Seq2'][1],'dnaseq3'=>$Input_Seqs['Seq3'][1],'report'=>$reportdata);
                
                $inputDao = new LogInputDao(DBFactory::getConnection());
                $inputDao->addRow($data);
                
                
//              Write the report file  
//                $outfn = 'report/Report_'.$sid.'.txt';
//                file_put_contents($outfn, $reportdata);
                
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////       
                
                //          Display the result  
                    include "views/_header.php";
                    if ($flag_input) {
                        include "views/_resultTable_DNA_Search.php";
                    } else {
                        include "views/_search.php";
                    }
                    include "views/_footer.php";
                }

            
        } 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
// Analyse protein sequence
        }elseif(isset($_POST['analyse_seq_PROTEIN'])){ 
            
            $Sequence = strtoupper($_POST['Protein-Sequence']);
//          Read the FASTA file

            if ($Sequence[0] == '>') {
//                      Fasta sequence
                $lines = explode("\n", $Sequence);
                $seqname = substr($lines[0], 1);
                array_shift($lines);
                $Sequence = implode($lines);
            }


            $Sequence = preg_replace('/\s+/', '', $Sequence);
            $query_for_resultpage = $Sequence;
//            $Sequence = str_split($Sequence);

//            
            //check input length
            $prot_valid_flag = false;        
            if (strpos($Sequence, "Protein-Sequence")){
                $MESSAGE .= "<br>Warning: NO INPUT FOUND!";
            }elseif(strlen($Sequence)<15){
                $MESSAGE .= "<br>Warning: Protein Input too short!(".strlen($Sequence).")";
            }elseif(strlen($Sequence)<5000){
                $prot_valid_flag = true;
            }else{
                $MESSAGE .= "<br>Warning: Protein Input too long!(".strlen($Sequence).")";
            }
            
            
            if($prot_valid_flag){
                
                $alignment = process_Protein_Input($Sequence,"Input");
//                print_r($alignment);
                
                $mutations = $alignment['Mutations'];
                $search_table = array();
                $Alignments = array($alignment);
                
//              Search for mutations  
                if(count($mutations)>0){
                    $mutantDao = new MutantDao(DBFactory::getConnection());
                        $search_results = $mutantDao->findMutants($mutations);  
                        
//                      Get the result table for the mutants
                        $mutantIds = $mutantDao->getMutantIDsFromMutationNames($mutations);  
                        $search_table = $mutantDao->getMutantTable($mutantIds);
                        
                    }else{
                        $search_results = array();
                    }        
                include "views/_header.php";
//                include "views/_search.php";            
                include "views/_resultTable_Protein_Search.php";
                include "views/_footer.php";
            }else{
                
                include "views/_header.php";                
                include "views/_search.php";
                include "views/_footer.php";
            }
        }else{
            include "views/_header.php";
            include "views/_search.php";
            include "views/_footer.php";
    }
}
}

