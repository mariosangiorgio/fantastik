<?php
include("./style/top.html");
require("./db/db_login.php");
print "
      <style type=\"text/css\">
      table.sample {
      	border-width: 1px;
      	border-spacing: 0px;
      	border-style: outset;
      	border-color: #808080;
      	border-collapse: collapse;
      	background-color: #ffffff;
      }
      table.sample td {
      	border-width: 1px;
      	border-spacing: 0px;
      	border-style: outset;
      	border-color: #808080;
      	border-collapse: collapse;
      	font-size:      11px;
      }
      </style>";
print "Semifinali<BR>";
$query = "SELECT DISTINCT
                     partite.giocata,
                     partite.casa AS IDcasa,
                     partite.fuori AS IDfuori,
                     partite.golCasa,
                     partite.golFuori,
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
                    WHERE tipo=3";
$data   = mysql_query($query,$conn);
$partita = mysql_fetch_array($data);
$i=0;
print "<TABLE class=sample>";
    do{
        $partite[$i]=$partita;
        $i++;
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
    
//Calcolo le finaliste
//Controllo che siano state giocate tutte le partite
$query = "SELECT * FROM partite WHERE tipo=3 AND giocata=0";
$data = mysql_query($query,$conn);
$ris = mysql_fetch_array($data);
if(!$ris){
          //Tutte le partite sono state giocate
          //Posso procedere
//Semi1
          $andata = $partite[0];
          $i=1;
          while($partite[$i]['IDfuori']!=$andata['IDcasa']){
              $i++;                                    }
          $ritorno = $partite[$i];
          //calcolo l'aggregate
          $golA = $andata['golCasa']+$ritorno['golFuori'];
          $golB = $andata['golFuori']+$ritorno['golCasa'];
          if($golA>$golB){
             $queryA = "UPDATE `partite`
                        SET `casa` = $andata[IDcasa]
                        WHERE `giornata` =30
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
              $queryR = "UPDATE `partite`
                        SET `fuori` = $andata[IDcasa]
                        WHERE `giornata` =33
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
          }
          if($golA<$golB){
               $queryA = "UPDATE `partite`
                        SET `casa` = $andata[IDfuori]
                        WHERE `giornata` =30
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
              $queryR = "UPDATE `partite`
                        SET `fuori` = $andata[IDfuori]
                        WHERE `giornata` =33
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
          }
          if($golA==$golB){
                           if($ritorno['golFuori']<$andata['golFuori']){
                              $queryA = "UPDATE `partite`
                                        SET `casa` = $andata[IDfuori]
                                        WHERE `giornata` =30
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";
                              $queryR = "UPDATE `partite`
                                        SET `fuori` = $andata[IDfuori]
                                        WHERE `giornata` =33
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";}
                           if($ritorno['golFuori']>$andata['golFuori']){
                              $queryA = "UPDATE `partite`
                                        SET `casa` = $andata[IDcasa]
                                        WHERE `giornata` =30
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";
                              $queryR = "UPDATE `partite`
                                        SET `fuori` = $andata[IDcasa]
                                        WHERE `giornata` =33
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";}
                           if($ritorno['golFuori']==$andata['golFuori']){                                      	
                                //calcolo i supplementari
                                //calcolo i rigori
                           }
          }
//aggiorno il database
$data   = mysql_query($queryA,$conn);
$data   = mysql_query($queryR,$conn);

//semi2
$andata = $partite[1];
          $i=1;
          while($partite[$i]['IDfuori']!=$andata['IDcasa']){
              $i++;                                    }
          $ritorno = $partite[$i];
          //calcolo l'aggregate
          $golA = $andata['golCasa']+$ritorno['golFuori'];
          $golB = $andata['golFuori']+$ritorno['golCasa'];
          if($golA>$golB){
             $queryA = "UPDATE `partite`
                        SET `fuori` = $andata[IDcasa]
                        WHERE `giornata` =30
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
              $queryR = "UPDATE `partite`
                        SET `casa` = $andata[IDcasa]
                        WHERE `giornata` =33
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
          }
          if($golA<$golB){
               $queryA = "UPDATE `partite`
                        SET `fuori` = $andata[IDfuori]
                        WHERE `giornata` =30
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
              $queryR = "UPDATE `partite`
                        SET `casa` = $andata[IDfuori]
                        WHERE `giornata` =33
                        AND `tipo` =4
                        AND `giocata`=0
                        LIMIT 1";
          }
          if($golA==$golB){
                           if($ritorno['golFuori']<$andata['golFuori']){
                              $queryA = "UPDATE `partite`
                                        SET `fuori` = $andata[IDfuori]
                                        WHERE `giornata` =30
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";
                              $queryR = "UPDATE `partite`
                                        SET `casa` = $andata[IDfuori]
                                        WHERE `giornata` =33
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";}
                           if($ritorno['golFuori']>$andata['golFuori']){
                              $queryA = "UPDATE `partite`
                                        SET `fuori` = $andata[IDcasa]
                                        WHERE `giornata` =30
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";
                              $queryR = "UPDATE `partite`
                                        SET `casa` = $andata[IDcasa]
                                        WHERE `giornata` =33
                                        AND `tipo` =4
                                        AND `giocata`=0
                                        LIMIT 1";}
                           if($ritorno['golFuori']==$andata['golFuori']){
                                //calcolo i supplementari
                                //calcolo i rigori
                           }
          }
//aggiorno il database
$data   = mysql_query($queryA,$conn);
$data   = mysql_query($queryR,$conn);
}//if sul giocate

print "Finali<BR>";
$query = "SELECT DISTINCT
                     partite.giocata,
                     partite.golCasa,
                     partite.golFuori,
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
                    WHERE tipo=4";
$data   = mysql_query($query,$conn);
$partita = mysql_fetch_array($data);
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
?>
