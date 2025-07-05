<?php

session_start();

include 'dbconnect.php'; // connect the database


// Destroy the session and redirect to index.php
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>