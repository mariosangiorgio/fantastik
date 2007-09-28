<?php
include("./style/top.html");
require("./db/db_login.php");
for($i=1;$i<39;$i++){
    $query = "SELECT DISTINCT
                     partite.giornata,
                     partite.giocata,
                     partite.golCasa,
                     partite.golFuori,
                     (
                     SELECT tipopartite.tipo
                     FROM tipopartite
                     WHERE tipopartite.id = partite.tipo
                     )AS tipo,
                     (
                     SELECT squadre.nome
                     FROM squadre
                     WHERE squadre.id = partite.casa
                     )AS casa,
                     (
                    SELECT squadre.nome
                    FROM squadre
                    WHERE squadre.id = partite.fuori
                    ) AS fuori
                    FROM partite
                    WHERE partite.giornata =$i";
    $data   = mysql_query($query,$conn);
    $partita = mysql_fetch_array($data);
    print "<A HREF=./risultati.php?giornata=$i><B>Giornata ";
    print $i;
    print " ";
    print $partita['tipo'];
    print "</B></A><BR>";
  print "<TABLE class=sample>";
    do{
        print "<TR>";
        print "<TD width=100px>";
        print $partita['casa'];
        print "</TD><TD  width=100px>";
        print $partita['fuori'];
        print "</TD><TD>";
        if($partita['giocata']==0){
           print "-</TD><TD>-</TD>";}
        else{
           print $partita['golCasa'];
           print "</td><td>";
           print $partita['golFuori'];
           print "</TD>";
        }
        print "</TR>";
    }
    while($partita = mysql_fetch_array($data));
    print "</TABLE>";
    }
include("./style/bottom.html");
?>

