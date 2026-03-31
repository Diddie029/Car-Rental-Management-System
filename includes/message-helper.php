<?php
// Message notification helper for header
// This file shows unread message count in header

if(strlen($_SESSION['login']) > 0) {
    $useremail = $_SESSION['login'];
    $sql = "SELECT COUNT(*) as unreadCount FROM tblmessages WHERE userEmail = :email AND status = 0 AND isArchived = 0";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $useremail, PDO::PARAM_STR);
    $query->execute();
    $messageResult = $query->fetch(PDO::FETCH_OBJ);
    $unreadMessages = $messageResult->unreadCount;
} else {
    $unreadMessages = 0;
}
?>
