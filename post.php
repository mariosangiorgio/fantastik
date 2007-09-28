<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
  require("./db/db_login.php");
  $query   = "INSERT INTO `blog` ( `stamp` , `utente` , `titolo` , `testo` ) 
              VALUES (CURRENT_TIMESTAMP,$_SESSION[squadra],'$_POST[titolo]','$_POST[testo]')";
  $data    = mysql_query($query,$conn);
  print "Messaggio inserito correttamente";
  }
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>
