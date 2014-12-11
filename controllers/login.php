<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/7/2014
 * Time: 8:44 PM
 */

include 'connect.php';

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $username = $_POST["username"];
    $password = $_POST["password"];

    $output = "";

    if ($username && $password) {

        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $dbusername, $dbpassword); // establish connection
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception

            $query = $conn->prepare("SELECT * FROM User WHERE username=:username AND password=:password");
            $query->bindParam(':username', $username);
            $query->bindParam(':password', $password);
            $query->execute();

            if ($row = $query->fetch())
            {
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                echo true;
            }
            else
            {
                echo "Failed to find user";
            }

            $conn = null; // close connection

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (isset($_SESSION['sign-out']) && !empty($_SESSION['sign-out']))
    {
        session_unset();
        session_destroy();
    }
}