<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/9/2014
 * Time: 11:29 AM
 */

include 'connect.php';

session_start();

if (isset($_SESSION['username']))
{
    echo true;
}
else
{
    echo "Logged out";
}