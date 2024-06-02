<?php

class DbConnection {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db_name = "db_GetYourGuide";
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name) or die("Error: ". mysqli_error($this->conn));
    }

    public function getConnection() {
        return $this->conn;
    }

    public function freeResult($result) {
        mysqli_free_result($result);
    }
    public function __destruct() {
        mysqli_close($this->conn);
    }
}

?>
