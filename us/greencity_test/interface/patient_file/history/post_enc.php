<?php 
 require_once("../../globals.php");
 require_once("$srcdir/encounter.inc");
 $variable = $_POST['ent']; 
 setencounter($variable); 
 echo $variable;
?>