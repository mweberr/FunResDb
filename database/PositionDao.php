<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("DBFactory.php");

class PositionDao{
    
    public $conn;
    
    public function __construct($dbconnect) {
        $this->conn = $dbconnect;
    }

    public function getResistantPositions() {

        $stmt = $this->conn->prepare('SELECT pos '
               .' FROM Mutation muta '
//               .' JOIN Mutant_Mutation mm ON mm.mutant_id = mu.pkid '
//               .' JOIN Mutation muta ON mm.mutation_id = muta.mutation_id '
                . 'WHERE muta.as_wt != \'TR\' ');
        
        $stmt->execute();
        
        $positions = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
        $uni_positions = array_unique($positions);
        
        sort($uni_positions);
        return $uni_positions;
    }
    
    
    
// Input: Get the Mutant IDs from position numbers
    
    public function getMutantIDsFromPos($pos){
        $pos_str = implode($pos,",");
        
                $stmt = $this->conn->prepare('SELECT mm.mutant_id '
                        . 'FROM Mutation mut'
                        .' JOIN Mutant_Mutation mm ON mut.mutation_id = mm.mutation_id'
                        .' WHERE mut.pos IN ('.$pos_str.') AND mut.as_wt != \'TR\'');
                
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
                return $result;
        
    }

    public function getMutantTable($mutantIDs) {

        //      $data = $conn->query('SELECT * FROM mutation_mutant WHERE name = ' . $conn->quote($name));
        //      $ids = array('M172V','F46Y');
        
        if(count($mutantIDs) == 0){
            return NULL;
        }
        
//        $inQuery = implode(',',$mutantIDs);
        $inQuery = implode(',',array_fill(0,count($mutantIDs),'?'));

        //      Prepared statement (1) Find mutants which contain the specified mutations
        
        $stmt = $this->conn->prepare('SELECT m.pkid StrainID, inne.Mutations Mutations, itz ITZ, vor VOR, pos POS, isa ISA, ' 
                . 'GROUP_CONCAT(CONVERT(mr.referenz_id,CHAR) SEPARATOR ",") PubmedIDs '
                . 'FROM Mutant m '
                . 'JOIN '
                . '(SELECT mm.mutant_id StrainId, GROUP_CONCAT(mutation_id SEPARATOR \', \') Mutations '
                . 'FROM Mutant_Mutation mm '
                . 'WHERE mm.mutant_id IN ('. $inQuery .') '
                . 'GROUP BY mm.mutant_id '
                . ') AS inne '
                . 'ON inne.StrainId = m.pkid '
                . 'INNER JOIN Mutant_Referenz mr ON m.pkid = mr.mutant_id '
                . 'GROUP BY m.pkid ');

 
        $stmt->execute($mutantIDs);
//
//      Fetch the data
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        foreach($result as $row){
//            print_r($row);
//        }
        return $result;
    }
    


}