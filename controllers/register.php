<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/7/2014
 * Time: 12:16 AM
 */

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")  // POST is used to create new users
{
    // add checks to these
    $fname = $_POST["firstname"];
    $lname = $_POST["lastname"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($fname && $lname && $username && $password) // ensures these variables are not null
    {
        try // attempts to add these variables to the database
        {
            $conn = new PDO("mysql:host=$host;dbname=$db", $dbusername, $dbpassword); // establish connection
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception

            $stmt = $conn->prepare("INSERT INTO User (Firstname, Lastname, Username, Password) VALUES (:newFirstname, :newLastname, :newUsername, :newPassword)");
            $stmt->bindParam(':newFirstname', $fname);
            $stmt->bindParam(':newLastname', $lname);
            $stmt->bindParam(':newUsername', $username);
            $stmt->bindParam(':newPassword', $password);

            $stmt->execute();

            $conn = null; // close connection

            echo "User Added";

        }
        catch (PDOException $e) // catches any exception thrown
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    else
    {
        echo "User was not registered";
    }
}