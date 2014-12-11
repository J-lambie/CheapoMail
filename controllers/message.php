<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/9/2014
 * Time: 12:40 PM
 */

include 'connect.php';

session_start(); ///initiates the globally available session

if ($_SERVER["REQUEST_METHOD"] == "POST") // Sends new email message
{
    $subject = $_POST["subject"]; // Does not have to be set
    $recipients = $_POST["recipients"];
    $body = $_POST["body"];

    if ($recipients && $body && isset($_SESSION['username']) && !empty($_SESSION['username'])) // required details checked
    {
        try
        {
            $conn = new PDO("mysql:host=$host;dbname=$db", $dbusername, $dbpassword); // establish connection
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception

            $recipientsArray = explode(';', $recipients); // separates each username using the ';' delimiter
            $recipientIds = array(); // empty array to hold ids

            // query the id of each username and save it the array of it to the message table
            foreach ($recipientsArray as $recipient)
            {
                $queryRecIds = $conn->prepare("SELECT * FROM User WHERE username=:username"); // queries each username
                $queryRecIds->bindParam(':username', $recipient); // protects against sql injections
                $queryRecIds->execute(); // executes query

                $row = $queryRecIds->fetch(); // fetch results
                array_push($recipientIds, $row['id']); // pushes user id to the name based on a given username
            }


            $total = count($recipientIds); // number of ids

            // insert each the message multiple times based on the amount of ids
            for ($i = 0; $i < $total; $i++)
            {
                $stmt = $conn->prepare("INSERT INTO Message (body, subject, user_id, recipient_ids) VALUES (:body, :subject, :user_id, :recipient)");

                // sanitize inputs
                $stmt->bindParam(':body', $body);
                $stmt->bindParam(':subject', $subject);
                $stmt->bindParam(':user_id', $_SESSION['username']); // pulls it from existing session
                $stmt->bindParam(':recipient', $recipientIds[$i]);

                $stmt->execute();
            }

            $conn = null; // close connection
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    else
    {
        echo "Message not sent";
    }
}