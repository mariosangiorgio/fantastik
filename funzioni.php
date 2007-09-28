<?php
function calcolaPunteggio($giornata, $squadra,$conn) {
     $query ="SELECT * FROM formazioni WHERE giornata = $giornata AND squadra = $squadra ORDER BY `ruolo` DESC";
     $giocatori = mysql_query($query,$conn);
     //compilo la formazione
     $g=$giornata;
     $tit=0;
     $ris=0;
     $formazione['data']=1;
     //separo totolari da riserve
     while(!$giocatore=mysql_fetch_array($giocatori)){
            $formazione['data']=0;
            $g--;
            $query ="SELECT * FROM formazioni WHERE giornata = $g AND squadra = $squadra ORDER BY `ruolo` DESC";
            $giocatori = mysql_query($query,$conn);
     }
     do{
       if($giocatore['titolare']==1){
          $titolari[$tit]['id']= $giocatore['giocatore'];
          $titolari[$tit]['ruolo']= $giocatore['ruolo'];
          $tit=$tit+1;}
       if($giocatore['titolare']==0){
          $riserve[$ris]['id']= $giocatore['giocatore'];
          $riserve[$ris]['ruolo']= $giocatore['ruolo'];
          $riserve[$ris]['prima']= $giocatore['primaRiserva'];
          $riserve[$ris]['entrato']=0;
          $ris=$ris+1;}
     }while($giocatore=mysql_fetch_array($giocatori));
     foreach ($riserve as $key => $row) {
     $ruolo[$key]  = $row['ruolo'];
     $id[$key]     = $row['id'];
     $prima[$key]  = $row['prima'];
     $entrato[$key]= $row['entrato'];
     }
     array_multisort($ruolo,SORT_DESC,$prima,SORT_DESC,$riserve);
     //Calcolo i punteggi
     $cambi=0;
     $sost['P']=0;
     $sost['D']=0;
     $sost['C']=0;
     $sost['A']=0;
     $formazione['pti']            = 0;
     $formazione['difesa']['pti']  = 0;
     $formazione['difesa']['gioc'] = 0;
     $formazione['centr']['pti']   = 0;
     $formazione['centr']['gioc']  = 0;
     //struttura formazione ha i giocatori da 0 a 17 + in pti e i punti in difesa/centrocampo[gioc] i giocatori difesa/centrocampo[pti] i punti
     for($i=0;$i<11;$i++){
         $id      = $titolari[$i]['id'];
         $query   = "SELECT * FROM voti WHERE giornata = $giornata AND id = $id";
         $voti    = mysql_query($query,$conn);
         $voto    = mysql_fetch_array($voti);
         $query   = "SELECT * FROM giocatori WHERE id = $id";
         $players = mysql_query($query,$conn);
         $player  = mysql_fetch_array($players);
         $formazione[$i]['nome']   =  $player['nome'];
         if($voto['fantapreso']==0){
               $formazione[$i]['voto'] = '-';
               $formazione[$i]['fanta']= '-';
               $cambi++;
               $sost[$player['ruolo']]++;}
         else{
               $formazione[$i]['voto'] = $voto['voto'];
               $formazione[$i]['fanta']= $voto['fanta'];
               if($titolari[$i]['ruolo']=='D'){
                  if($voto['votopreso'])
                      $formazione['difesa']['pti']  +=$voto['voto'];
                  else
                      $formazione['difesa']['pti']  +=6; 
                  $formazione['difesa']['gioc'] ++;
               }
               if($titolari[$i]['ruolo']=='C'){
                  if($voto['votopreso'])
                          $formazione['centr']['pti']  +=$voto['voto'];
                      else
                          $formazione['centr']['pti']  +=6;
                  $formazione['centr']['gioc'] ++;
               }
               $formazione['pti']+=$voto['fanta'];}
     }
     //titolari ok, controllo le riserve
     if($cambi>0){

         if($cambi>3)         //al max 3 cambi
          $cambi=3;
          $role="PDCA";      //scorro i ruoli
          for($k=0;$k<4;$k++){
           $r=$role[$k];
           while($sost[$r]>0 && $cambi>0){
              for($j=0;$j<7;$j++){       //ricerca del la prima riserva libera
                    if($riserve[$j]['ruolo']==$r && $riserve[$j]['entrato']==0 && $riserve[$j]['prima']==1){
                       break;}}
              if($j==7){//non ho la prima riserva, cerco la seconda
                    for($j=0;$j<7;$j++){       //ricerca della prima riserva libera
                      if($riserve[$j]['ruolo']==$r && $riserve[$j]['entrato']==0){
                         break;}}}
              if($j==7){
                    //nessuna riserva disponibile
                    $sost[$r]=0;
                    break;}
              $id=$riserve[$j]['id'];
              $query    = "SELECT * FROM voti WHERE giornata = $giornata AND id = $id";
              $voti    = mysql_query($query,$conn);
              $voto    = mysql_fetch_array($voti);
              $query   = "SELECT * FROM giocatori WHERE id = $id";
              $players = mysql_query($query,$conn);
              $player  = mysql_fetch_array($players);
              $formazione[11+$j]['nome']   =  $player['nome'];
              $riserve[$j]['entrato']=1;
              if($voto['fantapreso']==0){
                  $formazione[11+$j]['voto'] = '-';
                  $formazione[11+$j]['fanta']= '-';}
              else{
                   $formazione[11+$j]['voto'] = $voto['voto'];
                   $formazione[11+$j]['fanta']= $voto['fanta'];
                   if($titolari[$i]['ruolo']=='D'){
                      if($voto['votopreso'])
                          $formazione['difesa']['pti']  +=$voto['voto'];
                      else
                          $formazione['difesa']['pti']  +=6;
                      $formazione['difesa']['gioc'] ++;
                   }
                   if($titolari[$i]['ruolo']=='C'){ 
                      if($voto['votopreso'])
                          $formazione['centr']['pti']  +=$voto['voto'];
                      else
                          $formazione['centr']['pti']  +=6;
                      $formazione['centr']['gioc'] ++;
                   }
                   $formazione['pti']+=$voto['fanta'];
                   $sost[$r]--;
                   $cambi--;}
             if($sost[$r]>0){
                   $count=0;
                   for($j=0;$j<7;$j++){
                       if($riserve[$j]['ruolo']==$r && $riserve[$j]['entrato']==0)
                          $count++;}
                   if($count==0){
                      $sost[$r]=0;}}
           }//while cambi disponibili
         }//for ruoli
     }//if
     //stampo anche i panchinari non entrati in campo
     for($i=0;$i<7;$i++){
        if($riserve[$i]['entrato']==0){
           $id=$riserve[$i]['id'];
           $query   = "SELECT * FROM giocatori WHERE id = $id";
           $players = mysql_query($query,$conn);
           $player  = mysql_fetch_array($players);
           $formazione[11+$i]['nome'] = $player['nome'];
           $formazione[11+$i]['voto'] = '-';
           $formazione[11+$i]['fanta']= '-';}
     }
     return $formazione;
}
function gol($totale){
     if($totale<66){
        $gol=0;}
     else{
        $gol= (int)(($totale-66)/6+1);
        }
return $gol;
}
function difesa($avversario){
         if($avversario['gioc']==0)
            return 4;
         $media = ((float)$avversario['pti'])/$avversario['gioc'];
         $media += (($avversario['gioc']-4)*0.25);
         $pti =(int) (-($media-6)*4);
         if($pti>4)
            return 4;
         if($pti<-5)
            return -5;
         return $pti;
}
function centrocampo($casa, $trasferta){
        $Casa  = $casa['pti'];
        $Fuori = $trasferta['pti']+5*($casa['gioc']-$trasferta['gioc']);
        $delta = abs($Casa -$Fuori);
        if($delta<2){
             $mod['casa']=0;
             $mod['fuori']=0;
             return $mod;    }
        $pti=(int)($delta/2);
        if($pti>4)
          $pti=4;
        $mod['casa']=abs($Casa-$Fuori)/($Casa-$Fuori)*$pti;
        $mod['fuori']=-abs($Casa-$Fuori)/($Casa-$Fuori)*$pti;
        return $mod;
}

function stampaclassifica($classifica,$squadre){
foreach ($classifica as $key => $row) {
   $giocate[$key]  = $row['giocate'];
   $punti[$key] = $row['punti'];
   $vinte[$key]  = $row['vinte'];
   $nulle[$key] = $row['nulle'];
   $perse[$key]  = $row['perse'];
   $golFatti[$key] = $row['golFatti'];
   $golSubiti[$key] = $row['golSubiti'];
   $ptiFatti[$key] = $row['ptiFatti'];
   $ptiSubiti[$key] = $row['ptiSubiti'];
}
array_multisort($punti,SORT_DESC,$giocate,SORT_ASC,$ptiFatti,SORT_DESC,$golFatti,SORT_DESC,$vinte,$nulle,$perse,$golSubiti,$ptiSubiti,$classifica);
print "<TABLE border=0 cellpadding=0 cellspacing=0>";
print "<TR><TD align=center>Squadra</TD><TD  align=center width=60px>Punti</td><td align=center width=60px>Giocate</td><td align=center width=60px>vinte</td><td align=center width=60px>nulle</td><td align=center width=60px>perse</td><td align=center width=60px>golFatti</td><td align=center width=60px>golSubiti</td><td align=center width=60px>Punti Fatti</td><td align=center width=60px>Punti Subiti</td></tr>";
$l=0;
for($i=0;$i<$squadre;$i++){
   if($l%2==0){
        print "<TR bgcolor=ccccff>";}
   else{
        print "<TR bgcolor=white>";
     }
   print "<TD align= right>";
   print $classifica[$i]['nome'];
   print "</TD><TD align= right>";
   print $classifica[$i]['punti'];
   print "</TD><TD align= right>";
   print $classifica[$i]['giocate'];
   print "</TD><TD align= right>";
   print $classifica[$i]['vinte'];
   print "</TD><TD align= right>";
   print $classifica[$i]['nulle'];
   print "</TD><TD align= right>";
   print $classifica[$i]['perse'];
   print "</TD><TD align= right>";
   print $classifica[$i]['golFatti'];
   print "</TD><TD align= right>";
   print $classifica[$i]['golSubiti'];
   print "</TD><TD align= right>";
   print number_format($classifica[$i]['ptiFatti']/$classifica[$i]['giocate'],2);
   print "</TD><TD align= right>";
   print number_format($classifica[$i]['ptiSubiti']/$classifica[$i]['giocate'],2);
   print "</TD></TR>";
   $l++;
}
print "</TABLE>";
for($i=0;$i<2;$i++)
                   $qualificate[0] = $classifica[0]['id'];
return $qualificate;}
?>
