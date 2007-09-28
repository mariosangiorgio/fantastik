<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
  ?>
  <FORM METHOD="POST" ACTION="./post.php">
  <TABLE>
  <TR><TD>Titolo</TD><TD><INPUT TYPE="text" NAME="titolo"></TD></TR>
  <TR><TD>Testo</TD><TD><TEXTAREA NAME="testo" COLS=100 ROWS=10></TEXTAREA></TD></TR>
  </TABLE>
  <INPUT TYPE="submit" VALUE="Post">
  </FORM>
<?php
  }
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>

