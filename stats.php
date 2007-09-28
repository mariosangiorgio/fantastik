<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
  require("./db/db_login.php");
  $squadra=$_SESSION['squadra'];
  if($squadra==10){
                  $sql     = "SELECT * FROM giocatori ORDER BY ruolo DESC";
  }
  else{
                  $sql     = "SELECT * FROM giocatori WHERE fantasquadra=$squadra ORDER BY ruolo DESC";
  }
  $giocatori= mysql_query($sql,$conn);
  $i=0;
  while($giocatore=mysql_fetch_array($giocatori)){
       $gioc[$i]['nome'] = $giocatore['nome'];
       $gioc[$i]['ruolo'] = $giocatore['ruolo'];
       $voti = 0;
       $fanta= 0;
       $votopreso=0;
       $fantapreso=0;
       $gioc[$i]['gioc'] = 0;
       $gioc[$i]['golFatti'] = 0;
       $gioc[$i]['golSubiti'] = 0;
       $gioc[$i]['golVittoria']= 0;
       $gioc[$i]['golPareggio']= 0;
       $gioc[$i]['assist']= 0;
       $gioc[$i]['ammonizione']= 0;
       $gioc[$i]['espulsione']= 0;
       $gioc[$i]['rigoreTirato']= 0;
       $gioc[$i]['rigoreSubito']= 0;
       $gioc[$i]['rigoreParato']= 0;
       $gioc[$i]['rigoreSbagliato']= 0;
       $gioc[$i]['giocato']= 0;
       $gioc[$i]['titolare']= 0;
       $gioc[$i]['casa']= 0;
       $gioc[$i]['autogol']= 0;
       $sql  = "SELECT * FROM voti WHERE id=$giocatore[id]";
       $data = mysql_query($sql,$conn);
       while($giornata=mysql_fetch_array($data)){
          $gioc[$i]['gioc']++;
          $voti      +=$giornata['voto'];
          $fanta     +=$giornata['fanta'];
          $votopreso +=$giornata['votopreso'];
          $fantapreso+=$giornata['fantapreso'];
          $gioc[$i]['golFatti']  +=$giornata['golFatti'];
          $gioc[$i]['golSubiti'] +=$giornata['golSubiti'];
          $gioc[$i]['golVittoria']+=$giornata['golVittoria'];
          $gioc[$i]['golPareggio']+=$giornata['golPareggio'];
          $gioc[$i]['assist']+=$giornata['assist'];
          $gioc[$i]['ammonizione']+=$giornata['ammonizione'];
          $gioc[$i]['espulsione']+=$giornata['espulsione'];
          $gioc[$i]['rigoreTirato']+=$giornata['rigoreTirato'];
          $gioc[$i]['rigoreSubito']+=$giornata['rigoreSubito'];
          $gioc[$i]['rigoreParato']+=$giornata['rigoreParato'];
          $gioc[$i]['rigoreSbagliato']+=$giornata['rigoreSbagliato'];
          $gioc[$i]['giocato']+=$giornata['giocato'];
          $gioc[$i]['titolare']+=$giornata['titolare'];
          $gioc[$i]['casa']+=$giornata['casa'];
          $gioc[$i]['autogol']+=$giornata['autogol'];
  
       }
       if($votopreso==0){
          $gioc[$i]['voti']=0;
       }
       else{
          $gioc[$i]['voti']=round(((float)$voti)/$votopreso,2);
       }
       if($fantapreso==0){
          $gioc[$i]['fanta']=0;
       }
       else{
          $gioc[$i]['fanta']=round(((float)$fanta)/$fantapreso,2);
       }
       $i++;
  }
  foreach ($gioc as $key => $row) {
     $nome[$key]  = $row['nome'];
     $ruolo[$key] = $row['ruolo'];
     $totvoti[$key]  = $row['voti'];
     $totfanta[$key] = $row['fanta'];
     $giocate[$key]  = $row['gioc'];
     $golFatti[$key] = $row['golFatti'];
     $golSubiti[$key] = $row['golSubiti'];
     $golVittoria[$key] = $row['golVittoria'];
     $golPareggio[$key] = $row['golPareggio'];
     $assist[$key] = $row['assist'];
     $ammonizioni[$key] = $row['ammonizione'];
     $espulsioni[$key] = $row['espulsione'];
     $autogol[$key] = $row['autogol'];
     $rigoreTirato[$key]=$row['rigoreTirato'];
     $rigoreSubito[$key]=$row['rigoreSubito'];
     $rigoreParato[$key]=$row['rigoreParato'];
     $rigoreSbagliato[$key]=$row['rigoreSbagliato'];
     $giocato[$key]=$row['giocato'];
     $titolare[$key]=$row['titolare'];
     $casa[$key]=$row['casa'];
  }
  array_multisort($ruolo,SORT_DESC,$totfanta,SORT_DESC,$totvoti,SORT_DESC,$giocate,SORT_DESC,$golFatti,$golSubiti,$golVittoria,$golPareggio,$assist,$ammonizioni,$espulsioni,$autogol,$rigoreTirato,$rigoreSubito,$rigoreParato,$rigoreSbagliato,$giocato,$titolare,$casa,$gioc);
  print "<TABLE class=sample>";
  print "<TR align=right>";
  print "<TD>NOME</TD><TD width=15px>R</TD><TD width=40px>FM</TD><TD width=40px>MV</TD><TD width=15px>P</TD>";
  print "<TD width=20px>GF</TD>";
  print "<TD width=20px>GS</TD>";
  print "<TD width=20px>As</TD>";
  print "<TD width=20px>Am</TD>";
  print "<TD width=20px>Es</TD>";
  print "<TD width=20px>AG</TD>";
  print "<TD width=20px>RT</TD>";
  print "<TD width=20px>RSu</TD>";
  print "<TD width=20px>RP</TD>";
  print "<TD width=20px>RSb</TD>";
  print "<TD width=25px>Pres</TD>";
  print "<TD width=25>Tit</TD>";
  print "<TD width=25>Casa</TD>";
  print "<TD width=25x>Fuori</TD>";
  print "</TR>";
  for($j=0;$j<$i;$j++){
         if($j%2==0)
                     print "<TR align=right><TD>";
         else
                     print "<TR  align=right bgcolor=ccccff><TD>";
         print $gioc[$j]['nome'];
         print "</TD><TD>";
         print $gioc[$j]['ruolo'];
         print "</TD><TD>";
         print number_format($gioc[$j]['fanta'],2);
         print "</TD><TD>";
         print number_format($gioc[$j]['voti'],2);
         print "</TD><TD>";
         print $gioc[$j]['gioc'];
         print "</TD><TD>";
         print $gioc[$j]['golFatti'];
         print "</TD><TD>";
         print $gioc[$j]['golSubiti'];
         print "</TD><TD>";
         print $gioc[$j]['assist'];
         print "</TD><TD>";
         print $gioc[$j]['ammonizione'];
         print "</TD><TD>";
         print $gioc[$j]['espulsione'];
         print "</TD><TD>";
         print $gioc[$j]['autogol'];
         print "</TD><TD>";
         print $gioc[$j]['rigoreTirato'];
         print "</TD><TD>";
         print $gioc[$j]['rigoreSubito'];
         print "</TD><TD>";
         print $gioc[$j]['rigoreParato'];
         print "</TD><TD>";
         print $gioc[$j]['rigoreSbagliato'];
         print "</TD><TD>";
         print $gioc[$j]['giocato'];
         print "</TD><TD>";
         print $gioc[$j]['titolare'];
         print "</TD><TD>";
         print $gioc[$j]['giocato']-$gioc[$j]['casa'];
         print "</TD><TD>";
         print $gioc[$j]['casa'];
         print "</TD></TR>";
  }
  print "</TABLE>";
}
else      {
    print "<A HREF=./login.php>Devi prima loggarti</a>";
}
include("./style/bottom.html");
?>

