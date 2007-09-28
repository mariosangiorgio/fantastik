<?php
include("./style/top.html");
require("./db/db_login.php");
require("./funzioni.php");
require("./getData.php");
$giornata = (int) $_GET['giornata'];
$query = "SELECT DISTINCT
                     partite.giornata,
                     partite.giocata,
                     partite.casa,
                     partite.fuori,
                     (
                     SELECT tipopartite.tipo
                     FROM tipopartite
                     WHERE tipopartite.id = partite.tipo
                     )AS tipo,
                     (
                     SELECT squadre.nome
                     FROM squadre
                     WHERE squadre.id = partite.casa
                     )AS nomecasa,
                     (
                    SELECT squadre.nome
                    FROM squadre
                    WHERE squadre.id = partite.fuori
                    ) AS nomefuori
                    FROM partite
                    WHERE partite.giornata =$giornata";
$data      = mysql_query($query,$conn);
$sql = "SELECT * FROM voti WHERE giornata=$giornata LIMIT 1";
$info = mysql_query($sql,$conn);
if(mysql_fetch_array($info))
    $dati=1;
else
    $dati=getData($giornata,$conn);
if($dati==0)
   print "Voti non disponibili, provare più tardi";
while($partita = mysql_fetch_array($data)){
     $Casa=calcolaPunteggio($giornata,$partita['casa'],$conn);
     $Fuori=calcolaPunteggio($giornata,$partita['fuori'],$conn);
     //stampo il tabellino
     print "<TABLE cellspacing=0 cellpadding=0>";
     print "<TR>";
     print "<TD width=300px>";
     print "<B>";
     print $partita['nomecasa'];
     print "</B>";
     if($Casa['data']==0){
        print "<B>*</B>";}
     print "</TD><TD width=60px></TD><TD width=60px></TD><TD width=300px>";
     print "<B>";
     print $partita['nomefuori'];
     print "</B>";
   if($Fuori['data']==0){
        print "<B>*</B>";}
     print "</TD><TD width=60px></TD><TD width=60px></TD></TR>";
     for($i=0;$i<11;$i++){
        if($i%2==1)
           print "<TR>";
        else
           print "<TR bgcolor=ccccff>";
        print "<TD>";
        print $Casa[$i]['nome'];
        print "</TD><TD>";
        print $Casa[$i]['voto'];
        print "</TD><TD>";
        print $Casa[$i]['fanta'];
        print "</TD><TD>";
        print $Fuori[$i]['nome'];
        print "</TD><TD>";
        print $Fuori[$i]['voto'];
        print "</TD><TD>";
        print $Fuori[$i]['fanta'];
        print "</TD></TR>";
     }
     print "<TR><TD>Panchina</TD><TD></TD><TD></TD><TD>Panchina</TD><TD></TD><TD></TD></TR>";
     for($i=11;$i<18;$i++){
        print "<TR>";
        print "<TD>";
        print $Casa[$i]['nome'];
        print "</TD><TD>";
        print $Casa[$i]['voto'];
        print "</TD><TD>";
        print $Casa[$i]['fanta'];
        print "</TD><TD>";
        print $Fuori[$i]['nome'];
        print "</TD><TD>";
        print $Fuori[$i]['voto'];
        print "</TD><TD>";
        print $Fuori[$i]['fanta'];
        print "</TD></TR>";
     }
     $ptiFuori=$Fuori['pti'];
     $ptiCasa =$Casa['pti'];
     $Fuori['pti'] +=difesa($Casa['difesa']);
     $Casa['pti'] +=difesa($Fuori['difesa']);
     $centrocampo = centrocampo($Casa['centr'],$Fuori['centr']);
     $Casa['pti'] += $centrocampo['casa'];
     $Fuori['pti']+= $centrocampo['fuori'];
     //fattore campo
     $Casa['pti']+=2;
     $golCasa = gol($Casa['pti']);
     $golFuori = gol($Fuori['pti']);
     print "<TR><TD align=right>Gol </TD><TD><B>$golCasa</B></TD><TD>Punti:$Casa[pti]</TD><TD align=right>Gol </TD><TD><B>$golFuori</B></TD><TD>Punti:$Fuori[pti]</TD></TR>";
     print "</TABLE>";
     if($dati!=0){
        $sql = "UPDATE `partite` SET giocata = 1, `golCasa` = $golCasa, golFuori=$golFuori, ptiCasa=$ptiCasa, ptiFuori=$ptiFuori WHERE `giornata` = $giornata AND `casa` = $partita[casa] AND `fuori` = $partita[fuori]";
        mysql_query($sql,$conn);
     }
}
if($dati!=0){
   $sql  = "SELECT giornata FROM deadline WHERE corrente =1";
   $data = mysql_query($sql,$conn);
   $dati = mysql_fetch_array($data);
   if($dati['giornata']==$giornata){
     $g    = $dati['giornata']+1;
     $sql  = "UPDATE deadline SET corrente = 0 WHERE corrente=1";
     $data = mysql_query($sql,$conn);
     $sql  = "UPDATE deadline SET corrente = 1 WHERE giornata=$g";
     $data = mysql_query($sql,$conn);
   }  
   }
  include("./style/bottom.html");
?>
