<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/10/2014
 * Time: 12:07 PM
 */

include 'connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_SESSION['username']) && !empty($_SESSION['username']))
    {
        $conn = new PDO("mysql:host=$host;dbname=$db", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query_id = $conn->prepare("SELECT Id FROM User WHERE username=:username"); // get the user's id to search for their messages
        $query_id->bindParam(':username', $_SESSION['username']); // from the current session
        $query_id->execute(); // executes the code

        // fetches results from previous query
        if ($row = $query->fetch())
        {
            $id = $row['id']; // pulls id from the results and saves it

            // checks to ensure id was set from database
            if (isset($id) && !empty($id))
            {
                $query_messages = $conn->prepare("SELECT * FROM Message WHERE recipient_ids=:recipient_id"); // finds message for the user
                $query_messages->bindParam(':recipient_id', $id);
                $query_messages->execute();

                $emails = [];

                // set the resulting array to associative
                while ($row = $query_messages->fetch(PDO::FETCH_ASSOC))
                {
                    array_push($emails, $row); // adds all the emails for the user to the $emails array
                }

                if (sizeof($row) > 0)
                {
                    print "Content-type: text/html\n\n";

                    // stores values
                    $html = "";

                    // xml header info

                    $html .= "<ol>\n";

                    // create email list
                    foreach ($emails as $email)
                    {
                        $html .= "<ul>\n";

                        $html .= "<li> User: {$email['username']}</li>\n";
                        $html .= "<li> Subject: {$email['subject']}</li>\n";
                        $html .= "<li> Body: {$email['body']}</li>\n";

                        $html .= "</ul>\n";
                    }

                    $html .= "</ol>";

                    echo $html;
                }
                else
                {
                    echo "Lol get friends";
                }

            }
            else
            {
                $conn = null;
                echo "User not found, session deleted";
            }
        }
        else
        {
            $conn = null;
            echo "Not correctly logged in";
        }
    }
}