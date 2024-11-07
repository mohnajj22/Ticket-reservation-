<?php 
include_once 'connect.php' ;
class Event {
    private $conn;
    private $table = "events";
    
    public $eventId;
    public $eventTitle;
    public $eventDate;
    public $ticketPrice;
    public $availableSeat;
    public $adminId;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getEventDetails() {
        $query = "SELECT * FROM " . $this->table . " WHERE eventId = :eventId";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":eventId", $this->eventId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function updateEvents() {
        $query = "UPDATE " . $this->table . " 
                 SET eventTitle=:eventTitle, eventDate=:eventDate, 
                 ticketPrice=:ticketPrice, availableSeat=:availableSeat 
                 WHERE eventId=:eventId";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":eventTitle", $this->eventTitle);
            $stmt->bindParam(":eventDate", $this->eventDate);
            $stmt->bindParam(":ticketPrice", $this->ticketPrice);
            $stmt->bindParam(":availableSeat", $this->availableSeat);
            $stmt->bindParam(":eventId", $this->eventId);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
}