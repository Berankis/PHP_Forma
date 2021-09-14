<?php

include "mysqli_con.php";
$comment_id = null;
$name = null;
$email = null;
$text = null;
$date = null;

if(isset($_POST['c_id'])){$comment_id=mysqli_real_escape_string($handle,$_POST['c_id']);}
if(isset($_POST['name'])){$name=mysqli_real_escape_string($handle,$_POST['name']);}
if(isset($_POST['email'])){$email=mysqli_real_escape_string($handle,$_POST['email']);}
if(isset($_POST['text'])){$text=mysqli_real_escape_string($handle,$_POST['text']);}
if(isset($_POST['date'])){$date=mysqli_real_escape_string($handle,$_POST['date']);}

$ok = true;
if($comment_id == null || $name == null || $email == null || $text == null || $date == null){
	$ok = false;
}
if($ok==false)echo "blogai";

if($ok==true){
	$query = mysqli_query($handle,"INSERT INTO replies(comment_id,email,name,text,date) VALUES('$comment_id','$email','$name','$text','$date')");
	if(mysqli_errno($handle)==0)echo "ok";
}

?>