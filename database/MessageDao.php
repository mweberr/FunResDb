<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MessageDao {
    
    public $conn;
    
    public function __construct($dbconnect) {
        $this->conn = $dbconnect;
    }
    
    function addRow($row) {
        try {

            $sql = "INSERT INTO Message(name,email,session_id,message,report) VALUES "
                    . "(:name,:email,:session_id,:message,:report)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
            $stmt->bindParam(':session_id', $row['session_id'], PDO::PARAM_STR);
            $stmt->bindParam(':message', $row['message'], PDO::PARAM_STR);
            $stmt->bindParam(':report', $row['report'], PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function countRows() {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM Log_Input');
        $stmt->execute();
        return $stmt->fetchColumn();
    }

}