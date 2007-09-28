<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
        print "<A HREF=./formazioni.php>Inserisci formazione</A><BR>";
        print "<A HREF=./message.php>Lascia un messaggio</A><BR>";
        print "<A HREF=./stats.php>Visualizza Statistiche Giocatori</A><BR>";
        print "<A HREF=./precedenti.php>Visualizza Partite Precedenti</A><BR>";
        print "<A HREF=./password.php>Cambia Password</A><BR>";
        print "<A HREF=./logout.php>Logout</A><BR>";
}
else{
     ?>
<FORM METHOD="POST" ACTION="./autentication.php">
<TABLE>
<TR>
<TD>
Squadra
</TD>
<TD><SELECT NAME="id">
<?php
    require("./db/db_login.php");
    $query   = "SELECT id,nome FROM squadre";
    $data    = mysql_query($query,$conn);
    while($dato=mysql_fetch_array($data)){
     print "<OPTION VALUE=";
     print $dato['id'];
     print ">";
     print $dato['nome'];
     }
?>
<OPTION SELECTED>
</SELECT></TD></TR><TR><TD>
Password                   </TD><TD>
<INPUT TYPE="password" NAME="password">
</TD></TR> </TABLE>
<INPUT TYPE="submit" VALUE="Login">
</FORM>
<?php
}
include("./style/bottom.html");
?>
