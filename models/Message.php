<?php
/**
 * Created by PhpStorm.
 * User: Don Shane
 * Date: 12/6/2014
 * Time: 1:10 AM
 */

class Message
{
    private $id, $body, $subject, $user_id, $recipient_id, $isRead;

    public function __construct($id, $body, $subject, $user_id, $recipient_id, $isRead)
    {
        $this->$id = $id;
        $this->$body = $body;
        $this->$subject = $subject;
        $this->$user_id = $user_id;
        $this->$recipient_id = $recipient_id;
        $this->$isRead = $isRead;
    }

    
}