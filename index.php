<?php
include("./style/top.html");
require("./db/db_login.php");
$query = "SELECT DISTINCT
          blog.titolo,
          blog.testo,
          UNIX_TIMESTAMP(blog.stamp) AS stamp,
          (Select squadre.nome
          FROM squadre
          where squadre.id=blog.utente)
          as utente
          from blog
          ORDER BY blog.stamp DESC
          LIMIT 0,5";
$data   = mysql_query($query,$conn);
while($articolo = mysql_fetch_array($data)){
  print "<DIV id =articolo><DIV id=titolo>";
  print $articolo['titolo'];
  print " (di ";
  print $articolo['utente'];
  print " ";
  print date("l d M Y",$articolo['stamp']);
  print ")</DIV><DIV id=testo>";
  print $articolo['testo'];
  print "</DIV></DIV>";
}
include("./style/bottom.html");
?>
