<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
  require("./db/db_login.php");
  $sql     = "SELECT * FROM giocatori WHERE fantasquadra=".$_SESSION['squadra']." ORDER BY ruolo DESC";
  $giocatori= mysql_query($sql,$conn);
  print "<FORM METHOD=\"POST\" ACTION=\"./set.php\">";
  print "<INPUT TYPE=\"hidden\" NAME=squadra VALUE=".$_SESSION['squadra'].">";
  print "<TABLE border=0 cellspacing=0 cellpadding=0>";
  $i=0;
  print "<TR><TD align=center>NOME</TD><TD align=center>Ruolo</TD><TD align=center width=50px>Titolare<TD align=center width=50px>Riserva1</TD><TD align=center width=50px>Riserva2</TD><TD align=center width=50px>Annulla</TD></TR>";
  while($giocatore=mysql_fetch_array($giocatori)){
       if($i%2==0){
          print "<TR bgcolor=ccccff><TD align=right>";}
       else{
          print "<TR bgcolor=white><TD align=right>";
       }
       print $giocatore['nome'];
       print "</TD><TD align=center width=70px>";
       print $giocatore['ruolo'];
       print "</TD><TD align=center width=70px>";
       print "<INPUT TYPE=\"radio\" NAME=$giocatore[id] VALUE=0>";
       print "</TD><TD align=center width=70px>";
       print "<INPUT TYPE=\"radio\" NAME=$giocatore[id] VALUE=1>";
       print "</TD><TD align=center width=70px>";
       print "<INPUT TYPE=\"radio\" NAME=$giocatore[id] VALUE=2>";
       print "</TD><TD align=center width=70px>";
       print "<INPUT TYPE=\"radio\" NAME=$giocatore[id] VALUE=-1>";
       print "</TD></TR>";
       $i++;
       }
  print "</TABLE>";
  print "<INPUT TYPE=\"submit\" VALUE=\"Submit\">";
}
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>
