<?php
include_once 'connect.php' ;
class Ticket {
    private $conn;
    private $table = "tickets";
    
    public $ticketId;
    public $eventId;
    public $userId;
    public $ticketPrice;
    public $ticketStatus;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateTicket() {
        $this->ticketId = uniqid('TCKT');
        $query = "INSERT INTO " . $this->table . " 
                 SET ticketId=:ticketId, eventId=:eventId, userId=:userId, 
                 ticketPrice=:ticketPrice, ticketStatus=:ticketStatus";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":ticketId", $this->ticketId);
            $stmt->bindParam(":eventId", $this->eventId);
            $stmt->bindParam(":userId", $this->userId);
            $stmt->bindParam(":ticketPrice", $this->ticketPrice);
            $stmt->bindParam(":ticketStatus", $this->ticketStatus);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function refundTicket() {
        $query = "UPDATE " . $this->table . " 
                 SET ticketStatus='REFUNDED' 
                 WHERE ticketId=:ticketId";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":ticketId", $this->ticketId);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
}