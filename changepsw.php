<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
  require("./db/db_login.php");
  $pass      =$_POST['password'];
  $pass      =sha1($pass);
  $query     ="UPDATE squadre SET password='$pass' WHERE id =$_SESSION[squadra]";
  $data      = mysql_query($query,$conn);
  print "Password modificata<BR>";
  print "<A HREF=Torna alla gestione della squadra";
}
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>
