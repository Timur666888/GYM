<?php

require 'config.php';


function deleteclients($pdo, $id, $sql){
    $sql ="DELETE FROM clients WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($id);
}

deleteclients( $pdo, $_GET, $sql);
header ("Location: view.clients.php");




