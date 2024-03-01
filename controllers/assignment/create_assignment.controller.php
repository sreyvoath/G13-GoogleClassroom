<?php

session_start();
require_once '../../database/database.php';
require_once('../../models/assignments/assignment.model.php');
if ( $_SERVER['REQUEST_METHOD']=='POST'){
    if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['document']) && isset($_POST['score']) && isset($_POST['end_date'])){
        $title = htmlspecialchars($_POST['title']) ;
        $content = htmlspecialchars($_POST['content']) ;
        $document =htmlspecialchars($_POST['document']) ;
        $score = htmlspecialchars($_POST['score']);
        $class_id = $_SESSION['class_id'];
        
        // get datetime
        $end_date = htmlspecialchars($_POST['end_date']);
        $end_time = htmlspecialchars($_POST['end_time']);

       
        if(createAssign($title, $content, $document, $score, $end_date, $end_time, $class_id)){
            header("Location:/classwork");
        }
        else{
            header("Location:/create-work");
        }
    }
    
    
}

?>