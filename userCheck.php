<?php
class User {
    private $email;
    private $password;

    public function __construct($email=null, $password=null) {
        $this->email = $email;
        $this->password = $password;
    }

    public function validateEmail() {
        // Utilizza la funzione filter_var per controllare il formato dell'email
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function validatePassword() {
        // Controlla che la password soddisfi i criteri richiesti
        if (strlen($this->password) < 8 || strlen($this->password) > 30) {
            return false;
        }
        if (!preg_match('/[0-9]/', $this->password)) {
            return false;
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $this->password)) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $this->password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $this->password)) {
            return false;
        }
        if (preg_match('/\s/', $this->password)) {
            return false;
        }
    
        return true;
    }
}