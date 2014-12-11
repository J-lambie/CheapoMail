<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/6/2014
 * Time: 1:10 AM
 */

class User {
    private $id, $firstName, $lastName, $password, $username;

    public function __construct($id, $firstName, $lastName, $password, $username)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->username = $username;
    }
} 