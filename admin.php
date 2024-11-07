<?php 
include_once 'connect.php' ;
class Admin {

    
    private $conn;
    private $table = "admins";
    
    public $adminId;
    public $adminName;
    public $adminEmail;
    public $adminPassword;
    public $adminAddress;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addEvents($eventData) {
        $query = "INSERT INTO events SET 
                 eventId=:eventId, eventTitle=:eventTitle, eventDate=:eventDate,
                 ticketPrice=:ticketPrice, availableSeat=:availableSeat, adminId=:adminId";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":eventId", $eventData['eventId']);
            $stmt->bindParam(":eventTitle", $eventData['eventTitle']);
            $stmt->bindParam(":eventDate", $eventData['eventDate']);
            $stmt->bindParam(":ticketPrice", $eventData['ticketPrice']);
            $stmt->bindParam(":availableSeat", $eventData['availableSeat']);
            $stmt->bindParam(":adminId", $this->adminId);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function viewEventSummary() {
        $query = "SELECT e.*, COUNT(t.ticketId) as ticketsSold, 
                 SUM(t.ticketPrice) as totalRevenue 
                 FROM events e 
                 LEFT JOIN tickets t ON e.eventId = t.eventId 
                 WHERE e.adminId = :adminId 
                 GROUP BY e.eventId";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":adminId", $this->adminId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
}