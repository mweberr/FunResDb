<?php

class positionSummary {
    //05.10.

    public function get_mut_Positions(){    //returns all mutated positions (not unique!)
        global $db;
        $positions = array();
        $query = $db -> query("SELECT pos FROM Mutation WHERE as_wt != 'TR'");
        while($row = $query->fetch()){
            array_push($positions, $row[0]);
        }
        return $positions;
    }
    //
    public function find_Mutants_to_Positions($positions){ //returns all mutants for each position
        global $db;        
        $return_array = array(); //contains one array for each pos containing all mutants that are mutated at that pos
        foreach ($positions as $pos){            
            //get all mutations for each pos
            $query_mutations = $db -> query("SELECT mutation_id FROM Mutation WHERE as_wt != 'TR' AND pos = ".$pos);
            $muts = array();
            while($mut = $query_mutations->fetch()){
                array_push($muts, $mut[0]);
            }
            $mutants = array();
            //get all mutants for all muts
            foreach($muts as $mut){
                $query_mutants = $db -> query("SELECT mutant_id FROM `Mutant_Mutation` WHERE mutation_id = '".$mut."'");
                while($mutant = $query_mutants->fetch()){
                    array_push($mutants, $mutant[0]);
                }                
            }
            $return_array[$pos]= $mutants;
        }        
        return $return_array;        
    }
//    public function find_pubmeds_for_mutants($mutants){
//        global $db;
//        echo gettype($mutants);
//        foreach ($mutants as $current_mutant){          //get pubmedIDs
//            $mutant_query = $db ->query("SELECT GROUP_CONCAT(DISTINCT Mutant_Referenz.referenz_id) AS reflist FROM Mutant_Referenz WHERE mutant_id = '$current_mutant' ");
//            while($record = $mutant_query -> fetch()){      
//                $refs_for_mutants[$current_mutant]= design_Pubmedlinkstring($record['reflist']);        //store all found mutants into array
//            }
//        }
//        return $refs_for_mutants;
//    }
    
    public function findResistantPositions(){        
        global $db;
        $return_array = array();                    //contains 4 times ()for each azol): count(# of res strains) and positions of mutations of those resistant strains
        //////////////////////////get all mutations that make fungus resistant agains IITRA
        $return_array_itra = array();   
        
        $query = $db -> query("SELECT Mutant_Mutation.mutation_id FROM Mutant INNER JOIN Mutant_Mutation ON Mutant.mutant_id=Mutant_Mutation.mutant_id WHERE itra='R'");
       
        
        
        $count = $query->rowCount();
        
        $positions = array(); 
        
        while($row = $query->fetch()){
            if(stristr($row[0], "TR")===FALSE){                     //remove tandem repeats "TR" mutations
                array_push($positions, $row[0]);                  
            }                    
        }        
        $positions = array_unique($positions);
        $positions = sort_by_pos($positions, "array");
        $numbers = array();
        foreach($positions as $mut){
            preg_match('[\d+]',$mut,$posi_array,PREG_OFFSET_CAPTURE);
            $posi_hae=$posi_array[0];
            $pos = $posi_hae[0];
            array_push($numbers,$pos);
        }
        $return_array_itra[0]=$count;
        $return_array_itra[1]=$numbers;
        $return_array[0] = $return_array_itra;        
        //////////////////////////////////////
         //////////////////////////get all mutations that make fungus resistant agains VORI
        $return_array_vori = array();   
        $query = $db -> query("SELECT Mutant_Mutation.mutation_id FROM Mutant INNER JOIN Mutant_Mutation ON Mutant.mutant_id=Mutant_Mutation.mutant_id WHERE vori='R'");
        $count = $query->rowCount();
        $positions = array();                           
        while($row = $query->fetch()){
            if(stristr($row[0], "TR")===FALSE){                     //remove tandem repeats "TR" mutations
                array_push($positions, $row[0]);                  
            }                    
        }
        $positions = array_unique($positions);
        $positions = sort_by_pos($positions, "array");
        $numbers = array();
        foreach($positions as $mut){
            preg_match('[\d+]',$mut,$posi_array,PREG_OFFSET_CAPTURE);
            $posi_hae=$posi_array[0];
            $pos = $posi_hae[0];
            array_push($numbers,$pos);
        }
        $return_array_vori[0]=$count;
        $return_array_vori[1]=$numbers;
        $return_array[1] = $return_array_vori;
        /////////////////////////////////////////
         //////////////////////////get all mutations that make fungus resistant agains IITRA
        $return_array_posa = array();   
        $query = $db -> query("SELECT Mutant_Mutation.mutation_id FROM Mutant INNER JOIN Mutant_Mutation ON Mutant.mutant_id=Mutant_Mutation.mutant_id WHERE posa='R'");
        $count = $query->rowCount();
        $positions = array();                           
        while($row = $query->fetch()){
            if(stristr($row[0], "TR")===FALSE){                     //remove tandem repeats "TR" mutations
                array_push($positions, $row[0]);                  
            }                    
        }
        $positions = array_unique($positions);
        $positions = sort_by_pos($positions, "array");
        $numbers = array();
        foreach($positions as $mut){
            preg_match('[\d+]',$mut,$posi_array,PREG_OFFSET_CAPTURE);
            $posi_hae=$posi_array[0];
            $pos = $posi_hae[0];
            array_push($numbers,$pos);
        }
        $return_array_posa[0]=$count;
        $return_array_posa[1]=$numbers;
        $return_array[2] = $return_array_posa;   
        ///////////////////////////////////////////
         //////////////////////////get all mutations that make fungus resistant agains IITRA
        $return_array_isa = array();   
        $query = $db -> query("SELECT Mutant_Mutation.mutation_id FROM Mutant INNER JOIN Mutant_Mutation ON Mutant.mutant_id=Mutant_Mutation.mutant_id WHERE isa='R'");
        $count = $query->rowCount();
        $positions = array();                           
        while($row = $query->fetch()){
            if(stristr($row[0], "TR")===FALSE){                     //remove tandem repeats "TR" mutations
                array_push($positions, $row[0]);                  
            }                    
        }
        $positions = array_unique($positions);
        $positions = sort_by_pos($positions, "array");
        $numbers = array();
        foreach($positions as $mut){
            preg_match('[\d+]',$mut,$posi_array,PREG_OFFSET_CAPTURE);
            $posi_hae=$posi_array[0];
            $pos = $posi_hae[0];
            array_push($numbers,$pos);
        }
        $return_array_isa[0]=$count;
        $return_array_isa[1]=$numbers;
        $return_array[3] = $return_array_isa;   
        
        
        
        /////////////////////////////////////
        /////////////////////////////////////
        return $return_array;
    }
//    public function getResPosData($pos){ //takes position and returns data from database
//        global $db;
//         $query = $db ->query("SELECT Mutation.mutation_id,Mutation.pos,Mutation.as_mut,Mutant_Mutation.mutant_id,Itra,Vori,Posa,Isa FROM Mutation INNER JOIN Mutant_Mutation ON Mutation.mutation_id=Mutant_Mutation.mutation_id INNER JOIN Mutant ON Mutant_Mutation.mutant_id = Mutant.mutant_id WHERE pos='$pos'"); 
//         return $query;
//    }
//    function find_Pubmeds_for_querymutants_old($query){  //takes position query and returns assoziative array with pumeds-string for all mutants found with the query        
//        global $db;
//        $mutants = array();
//        $refs_for_mutants = array();
//        while($record = $query -> fetch()){      
//             array_push($mutants, $record['mutant_id']);        //store all found mutants into array
//        }
//        
//        foreach ($mutants as $current_mutant){          //get pubmedIDs
//            $mutant_query = $db ->query("SELECT GROUP_CONCAT(DISTINCT Mutant_Referenz.referenz_id) AS reflist FROM Mutant_Referenz WHERE mutant_id = '$current_mutant' ");
//            while($record = $mutant_query -> fetch()){      
//                $refs_for_mutants[$current_mutant]= design_Pubmedlinkstring($record['reflist']);        //store all found mutants into array
//            }
//        }
//        return $refs_for_mutants;
//    }
    
    public function find_Pubmeds_for_mutant($mutant){  //takes position query and returns assoziative array with pumeds-string for all mutants found with the query        
        global $db;        
        $refs_for_mutant = array();                
        $mutant_query = $db ->query("SELECT GROUP_CONCAT(DISTINCT Mutant_Referenz.referenz_id) AS reflist FROM Mutant_Referenz WHERE mutant_id = '$mutant' ");
        while($record = $mutant_query -> fetch()){      
            //$refs_for_mutants[$current_mutant]= design_Pubmedlinkstring($record['reflist']);        //store all found mutants into array
            array_push($refs_for_mutant,$record['reflist']);
        }
        
        return $refs_for_mutant;
    }
    public function find_info_for_mutant($mutant){  //takes position query and returns assoziative array with pumeds-string for all mutants found with the query        
        global $db;        
        $mutant_info = array();                
        $mutant_query = $db ->query("SELECT itra, vori, posa, isa, date_create FROM Mutant WHERE mutant_id = '".$mutant."'");
        while($record = $mutant_query -> fetch()){      
            //$refs_for_mutants[$current_mutant]= design_Pubmedlinkstring($record['reflist']);        //store all found mutants into array
            array_push($mutant_info,$record['itra']);
            array_push($mutant_info,$record['vori']);
            array_push($mutant_info,$record['posa']);
            array_push($mutant_info,$record['isa']);
            array_push($mutant_info,$record['date_create']);
        }
        return $mutant_info;
    }
}
