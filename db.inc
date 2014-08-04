<?php
 $mysqli = new mysqli("localhost","root", "root", "webDevFinal");

   if ($mysqli->connect_errno) {
     echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . 
       $mysqli->connnect_error;
   }

   function showerror()
   {
      die("Error " . mysql_errno() . " : " . mysql_error());
   }

   
?>
