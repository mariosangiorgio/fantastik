<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
   print "Inserire qui sotto la nuova password<BR>";
   print
   "<FORM METHOD=\"POST\" ACTION=\"./changepsw.php\">
   <INPUT TYPE=\"password\" NAME=\"password\">
   </TD></TR> </TABLE>
   <INPUT TYPE=\"submit\" VALUE=\"Cambia\">
   </FORM>";
}
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>
