<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MutantDao {

    public $conn;
    
    public function __construct($dbconnect) {
        $this->conn = $dbconnect;
    }

    public function findMutants($mutations){
//      1 : Find the mutant ids from the mutations
        $mutantIds = $this->getMutantIDsFromMutationNames($mutations);
//      2 : Get the mutant table
        $resultTable = $this->getMutantTable($mutantIds);
//      3: Find known and unknown mutations
        $result = $this->findKnownMutations($mutations);
        
        $return_array = array();
        $return_array['new_mutations'] = $result['new'];
        $return_array['known_mutations'] = $result['known'];
        $return_array['perfect_matches'] = null;
        $return_array['imperfect_matches'] = $mutantIds;
        
        return $return_array;
    }

    public function getMutantTableFromMutationIDs($mutationIDs) {
        
        $ids = $mutationIDs;
//      $data = $conn->query('SELECT * FROM mutation_mutant WHERE name = ' . $conn->quote($name));
//      $ids = array('M172V','F46Y');
        $inQuery = implode(',', array_fill(0, count($ids), '?'));

//      Prepared statement (1) Find mutants which contain the specified mutations
        $stmt = $this->conn->prepare('SELECT mutant_id FROM mutation_mutant WHERE mutation_id IN (' . $inQuery . ') '
                . 'GROUP by mutant_id HAVING COUNT(mutant_id) = ' . count($ids));

        foreach ($ids as $k => $id) {
//          1-indexed position of the parameter.   
            $stmt->bindValue(($k + 1), $id);
        }
        $stmt->execute();

//      Fetch the data
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

//      Second statement for selection of Mutants   
        $inQuery = implode(',', array_fill(0, count($result), '?'));
//            echo $inQuery;

        $stmt = $conn->prepare('SELECT mutant_id, Itra, Vori, Posa, Ravuconazol, Isavuconazol  '
                . 'FROM mutants WHERE mutant_id IN (' . $inQuery . ') ');
        
        foreach ($result as $k => $id) { 
            $stmt->bindValue(($k + 1), $id);
        }

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $row) {
//            print_r($row);
            echo '<br>';
        }
    }
    
//  Function findKnownMutations
//  Input: An array of mutations
//  Output: An array of new and known mutations  
    public function findKnownMutations($mutations){
        
        $stmt = $this->conn->prepare('SELECT * FROM Mutant_Mutation WHERE mutation_id = :id');
        $new_mutations = array();
        $known_mutations = array();
        
        foreach($mutations as $mut){          //search array one by one queried to db      
            $stmt->execute(array('id' => $mut));
            $result = $stmt->fetchAll();
            if(count($result) == 0){
                array_push($new_mutations,$mut);
            }else{
                array_push($known_mutations,$mut);
            }
        }
        
        $return_array = array('new'=> $new_mutations, 'known'=>$known_mutations);
        return $return_array;
}

//  Function getMutantIDsFromMutationNames
//  Input: An array of mutation names (e.g. F46Y, M172V etc.)
//  Output: An array of mutant ids
    
    public function getMutantIDsFromMutationNames($mutationNames) {
        if(count($mutationNames) == 0){
            return null;
        }
        
        $ids = $mutationNames;
        //      $data = $conn->query('SELECT * FROM mutation_mutant WHERE name = ' . $conn->quote($name));
        //      $ids = array('M172V','F46Y');
        $inQuery = implode(',', array_fill(0, count($mutationNames), '?'));

        //      Prepared statement (1) Find mutants which contain the specified mutations
//        $stmt = $conn->prepare('SELECT mm.mutant_id '
//                . 'FROM Mutant_Mutation mm  '
//                . 'JOIN Mutation mu ON mm.mutation_id = mu.pkid '
//                . 'WHERE mu.name IN (' . $inQuery . ') '
//                . 'GROUP by mm.mutant_id '
//                . 'HAVING COUNT(mm.mutant_id) >= ' . count($mutationNames));
        $stmt = $this->conn->prepare('SELECT mm.mutant_id '
                . 'FROM Mutant_Mutation mm  '
                . 'WHERE mm.mutation_id IN (' . $inQuery . ') '
                . 'GROUP BY mm.mutant_id ');
//                . 'HAVING COUNT(mm.mutant_id) >= ' . count($mutationNames));

//        foreach ($mutationNames as $k => $id) {
//            //          1-indexed position of the parameter.   
//            $stmt->bindValue(($k + 1), $id);
//        }
        
//        var_dump($stmt);
        $stmt->execute($mutationNames);

        //      Fetch the data
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        return $result;
    }

    
//  Function getMutantTable
//  Input: Primary keys of mutants
//  Output: Table columns for mutants, pubmedids, mutations, ITZ, VOR, POS resistance values
    
    public function getMutantTable($mutantIDs) {

        //      $data = $conn->query('SELECT * FROM mutation_mutant WHERE name = ' . $conn->quote($name));
        //      $ids = array('M172V','F46Y');
        
        if(count($mutantIDs) == 0){
            return NULL;
        }
        
        $inQuery = implode(',', array_fill(0, count($mutantIDs), '?'));

        //      Prepared statement (1) Find mutants which contain the specified mutations
        
//        $stmt = $conn->prepare('SELECT  inne.StrainID, inne.Mutations, inne.ITZ, inne.VOR, inne.POS, inne.ISA, GROUP_CONCAT(DISTINCT ref.pubmedid SEPARATOR \' \') PubmedIDs '
//                . 'FROM Mutant_Referenz mr '
//                . ' JOIN ( '
//                . ' SELECT mut.pkid StrainID, itra ITZ,vori VOR, posa POS, isa ISA, GROUP_CONCAT(DISTINCT b.name ORDER BY b.pkid ASC SEPARATOR \' \') Mutations '
//                . ' FROM Mutant mut '
//                . ' INNER JOIN Mutant_Mutation mm ON mut.pkid = mm.mutant_id '
//                . ' INNER JOIN Mutation b ON b.pkid = mm.mutation_id '
//                . ' WHERE mut.pkid IN ('. $inQuery .') '
//                . ' GROUP BY mm.mutant_id '
//                . ' ) AS inne '
//                . ' ON inne.StrainID = mr.mutant_id '
//                . ' JOIN Referenz ref ON mr.referenz_id = ref.pkid '
//                . ' GROUP BY inne.Mutations '
//                . ' ORDER BY inne.StrainID ');
        
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

        foreach ($mutantIDs as $k => $id) {
//          1-indexed position of the parameter.
            $stmt->bindValue(($k + 1), $id);
        }
        $stmt->execute();
//
//      Fetch the data
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        foreach($result as $row){
//            print_r($row);
//        }
        return $result;
    }
    
//  Function getMutantTableFromPos
//  Input: An array of integer positions
//  Output: Table columns for mutants, pubmedids, mutations, ITZ, VOR, POS resistance values    
    
    public function getMutantTableFromPos($pos) {

//        $conn = DBFactory::getConnection();
        //      $data = $conn->query('SELECT * FROM mutation_mutant WHERE name = ' . $conn->quote($name));
        //      $ids = array('M172V','F46Y');

        //      Prepared statement (1) Find mutants which contain the specified mutations
        $inQuery = implode(',', array_fill(0, count($pos), '?'));
        
//        $conn = DBFactory::getConnection();
        
        $stmt = $this->conn->prepare('SELECT m.pkid StrainID, inne.Mutations Mutations, itz ITZ, vor VOR, pos POS, isa ISA, ' 
                . 'GROUP_CONCAT(CONVERT(mr.referenz_id,CHAR) SEPARATOR ',') PubmedIDs'
                . 'FROM Mutant m'
                . 'JOIN'
                . '(SELECT mm.mutant_id StrainID, GROUP_CONCAT(DISTINCT mm.mutation_id ORDER BY mu.pkid ASC SEPARATOR ', ') Mutations'
                . 'FROM Mutant_Mutation mm'
                . 'INNER JOIN Mutation mu ON mu.mutation_id = mm.mutation_id'
                . 'WHERE mu.pos IN '.$inQuery.' AND mu.as_wt != "TR"'
                . 'GROUP BY mm.mutant_id'
                . ')AS inne'
                . 'ON inne.StrainID = m.pkid'
                . 'INNER JOIN Mutant_Referenz mr ON m.pkid = mr.mutant_id'
                . 'GROUP BY m.pkid');
        
  
        foreach ($pos as $k => $id) {
//          1-indexed position of the parameter.   
            $stmt->bindValue(($k + 1), $id);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        foreach($result as $row){
//            print_r($row);
//        }
        return $result;
    }
    
    public function getMutantMutationTable(){
        
        $stmt = $this->conn->prepare('SELECT *'
               .' FROM Mutant mu '
               .' JOIN Mutant_Mutation mm ON mm.mutant_id = mu.pkid '
               .' JOIN Mutation muta ON mm.mutation_id = muta.mutation_id ');
        
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}

?>