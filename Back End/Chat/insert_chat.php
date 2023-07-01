<?php

//ajax page
//it recieves the to_user_id and the chat_message from index.php 
include('database_connection.php');

session_start();

$data = array(
 ':to_user_id'  => $_POST['to_user_id'],
 ':from_user_id'  => $_SESSION['user_id'],
 ':chat_message'  => $_POST['chat_message'],
 ':status'   => '1'
);

$query = "
    INSERT INTO chat_message 
    (to_user_id, from_user_id, chat_message, status) 
    VALUES (:to_user_id, :from_user_id, :chat_message, :status)
";

$statement = $connect->prepare($query);

if($statement->execute($data)) {
    //use the function from database_connection.php
    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
}
