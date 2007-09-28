<?php
session_start();
include("./style/top.html");
require("./db/db_login.php");
$id      =(int)$_POST['id'];
$pass    =$_POST['password'];
$query   = "SELECT password FROM squadre WHERE id=$id";
$data    = mysql_query($query,$conn);
$dato=mysql_fetch_array($data);
if(sha1($pass)==$dato['password']){
   $_SESSION['loggato'] = "yes";
   $_SESSION['squadra'] = $id;
   if($id==10){
      $query   = "SELECT id,nome FROM squadre";
      $data    = mysql_query($query,$conn);
      while($dato=mysql_fetch_array($data)){
        print "<A HREF=./formazionisu.php?squadra=$dato[id]>$dato[nome]</A><BR>";
      }
   }
   else{
        print "<A HREF=./formazioni.php>Inserisci formazione</A><BR>";
        print "<A HREF=./message.php>Lascia un messaggio</A><BR>";
        print "<A HREF=./stats.php>Visualizza Statistiche Giocatori</A><BR>";
        print "<A HREF=./precedenti.php>Visualizza Partite Precedenti</A><BR>";
        print "<A HREF=./password.php>Cambia Password</A><BR>";
        print "<A HREF=./logout.php>Logout</A><BR>";
   }
}
else{
  print "Impossibile accedere";
}
include("./style/bottom.html");
?>
