<?php
session_start();
$content= $_SESSION['content'];
$title= $_POST['heading'];
echo $content;
if(isset($_POST['word'])){
header('Content-type: application/vnd.ms-word');
header('Content-Disposition: attachment;Filename='.$title.'.doc');    
echo $content;
}
?>