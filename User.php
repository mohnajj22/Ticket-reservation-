<?php
include_once 'connect.php' ;
class User {
    private $conn;
    private $table = "users";
    
    public $userId;
    public $userName;
    public $userEmail;
    public $userPassword;
    public $phoneNum;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createAccount($name, $email, $pass, $phone) {
        $query = "INSERT INTO " . $this->table . " SET userName=:name, userEmail=:email, 
                 userPassword=:pass, phoneNum=:phone";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->userPassword = password_hash($this->userPassword, PASSWORD_DEFAULT);
            
            $stmt->bindParam(":userName", $this->userName);
            $stmt->bindParam(":userEmail", $this->userEmail);
            $stmt->bindParam(":userPassword", $this->userPassword);
            $stmt->bindParam(":phoneNum", $this->phoneNum);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    } 

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE userEmail = :email LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if(password_verify($password, $row['userPassword'])) {
                    return $row;
                }
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function updateProfile() {
        $query = "UPDATE " . $this->table . " 
                 SET userName=:userName, phoneNum=:phoneNum 
                 WHERE userId=:userId";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":userName", $this->userName);
            $stmt->bindParam(":phoneNum", $this->phoneNum);
            $stmt->bindParam(":userId", $this->userId);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
} $user= new User("event_ticketing_system");
if(isset($_Post["submit"])) 
{
    
    $name= $_Post['name'];
    $email= $_Post['email'];
    $pass= $_Post['password'];
    $phone= $_Post['phone'];
    
    $user->createAccount($name, $email, $pass,$phone);
    echo "okay ";
}