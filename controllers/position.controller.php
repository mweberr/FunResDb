<?php

class PositionController {    
    public function renderPage(){
        global $MESSAGE;
        
//        $possum = new positionSummary;
        $posdao = new PositionDao(DBFactory::getConnection());
        
        if(isset($_POST['check_list'])){
            ///////////////////////////////get all info about selected pos: mutations, mutants with that mutations
            $chosen_positions=($_POST['check_list']);
            
            if(count($chosen_positions)==0){
                $MESSAGE .="<br>Warning: NO POSITION SELECTED!"; 
            }            
            
//          Retrieve all positions from the database  
            
//            $pos_mutants = $possum->find_Mutants_to_Positions($chosen_positions);
//            $pos_mutants = $posdao->getResistantPositions();
            
            
//          Search the positions  
            $mutantids = $posdao->getMutantIDsFromPos($chosen_positions);
//          Build the table  
            $search_table = $posdao->getMutantTable($mutantids);
            
//          Get the database table for all positions


////            $num_of_chosen_positions = count($chosen_positions);
//            $mutant_occurences = array();
//            foreach($pos_mutants as $pos){ //check num off occurences for all mutants, if a mutant appears for every chosen pos-> exact match
//                foreach($pos as $mutant){
//                    $occurences = 1;
//                    foreach ($pos_mutants as $pos_other){
//                        if($pos == $pos_other){
//                            continue;
//                        }else{
//                            foreach($pos_other as $mutant_other){
//                                if($mutant == $mutant_other){
//                                    $occurences++;
//                                }
//                            }
//                        }
//                    }
//                    $mutant_occurences[$mutant]=$occurences;                    
//                }
//            }
            
           
//            $exact_matches = array(); //all mutants that appear for every chosen pos
//            $subsets = array(); //all mutants that appear for less than every chosen pos
//            foreach ($mutant_occurences as $mutant_key => $occ ){
//                if ($occ == count($chosen_positions)){
//                    array_push($exact_matches, $mutant_key);
//                }else{
//                    array_push($subsets, $mutant_key);
//                }
//                
//            }
          
//            $ALL_exact_matches_info = array();
//            foreach ($exact_matches as $mutant){
//                $exact_matches_info = $possum -> find_info_for_mutant($mutant); //gets all info from db mutant table 
//                $pubmed_string = design_Pubmedlinkstring($possum -> find_Pubmeds_for_mutant($mutant));
//                array_push($exact_matches_info,$pubmed_string);
//                $mutations = mut_array_to_mutstring(get_mutations($mutant));
//                array_push($exact_matches_info,$mutations);
//               
//               
//                $ALL_exact_matches_info[$mutant]= $exact_matches_info;
//                
//            }
          
//            $ALL_subsets_info = array();
//            foreach ($subsets as $mutant){
//                $subsets_info = $possum -> find_info_for_mutant($mutant); //gets all info from db mutant table 
//                $pubmed_string = design_Pubmedlinkstring($possum -> find_Pubmeds_for_mutant($mutant));
//                array_push($subsets_info,$pubmed_string);
//                $mutations = mut_array_to_mutstring(get_mutations($mutant));
//                array_push($subsets_info,$mutations);
//              
//                $ALL_subsets_info[$mutant]= $subsets_info;                
//            }
          
            include "views/_header.php";
            include "views/_position.php";
            include "views/_posResultTable.php";
            include "views/_footer.php";
        }else{            
            include "views/_header.php";
            include "views/_position.php";
            include "views/_footer.php";
        }
    }
}