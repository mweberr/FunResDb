<?php


class LogInputDao {

    public $conn;
    
    public function __construct($dbconnect){
        $this->conn = $dbconnect;
        
    }
    
    public function addRow($row){
        $sql = "INSERT INTO Log_Input(ip_address,session_id,mutations,dnaseq1,dnaseq2,dnaseq3,protseq,report) VALUES "
                . "(:ipaddress,:session_id,:mutations,:dnaseq1,:dnaseq2,:dnaseq3,:protseq,:report)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':ipaddress', $row['ipaddress'], PDO::PARAM_INT);
        $stmt->bindParam(':session_id', $row['session_id'], PDO::PARAM_STR);
        $stmt->bindParam(':mutations', $row['mutations'], PDO::PARAM_STR);
        $stmt->bindParam(':dnaseq1', $row['dnaseq1'], PDO::PARAM_STR);
        $stmt->bindParam(':dnaseq2', $row['dnaseq2'], PDO::PARAM_STR);
        $stmt->bindParam(':dnaseq3', $row['dnaseq3'], PDO::PARAM_STR);
        $stmt->bindParam(':protseq', $row['protseq'], PDO::PARAM_STR);
        $stmt->bindParam(':protseq', $row['protseq'], PDO::PARAM_STR);
        $stmt->bindParam(':report', $row['report'], PDO::PARAM_STR);
        
        $stmt->execute();
        
    }
    
    public function countRows(){
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM Log_Input');
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function findRecordBySession($session){
        
        $stmt = $this->conn->prepare('SELECT * FROM Log_Input WHERE session_id = :sid');
        $stmt->bindParam(':sid',$session);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if(empty($result)){
            return null;
        }else{
            return $result[0];
        }        
    }
    
    public function findRecords() {
        $stmt = $this->conn->prepare('SELECT * FROM Log_Input');
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result;
    }
    
    public function storeRecords(){
        $stmt = $this->conn->prepare('SELECT * FROM Log_Input');
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        foreach($result as $row){
            $dirname  = $row["ip_address"];
            if(!file_exists($dirname)){
                mkdir($dirname);
            }
            
            $pkid = $row["pkid"];
            $fn_dna = $dirname.'/'.$pkid.'_DNAseqs.txt';
            $fn_report = $dirname.'/'.$pkid.'_Report.txt';
            
            if(file_exists($fn_dna)){
                unlink($fn_dna);
            }
            
            if(!empty($row["dnaseq1"])){
                $ln = strlen($row["dnaseq1"]);
                echo $fn_dna;
                file_put_contents($fn_dna, '>DNA_SEQ1 ('.$ln.')'.PHP_EOL.$row["dnaseq1"].PHP_EOL,FILE_APPEND);
                
            }
            
            if(!empty($row["dnaseq2"])){
                $ln = strlen($row["dnaseq2"]);
                file_put_contents($fn_dna, '>DNA_SEQ2 ('.$ln.')'.PHP_EOL.$row["dnaseq2"].PHP_EOL,FILE_APPEND);
            }
            
            if(!empty($row["dnaseq3"])){
                $ln = strlen($row["dnaseq3"]);
                file_put_contents($fn_dna, '>DNA_SEQ3 ('.$ln.')'.PHP_EOL.$row["dnaseq3"].PHP_EOL,FILE_APPEND);
            }
            
            file_put_contents($fn_report, $row["report"]);
            
        }
        
        
        
    }
   
}
