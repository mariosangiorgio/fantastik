<?php
session_start();
include("./style/top.html");
require("./db/db_login.php");
$array=$_POST;
//ottengo la prima giornata da calcolare
$sql   = "SELECT giornata,UNIX_TIMESTAMP(deadline) as time FROM deadline WHERE corrente=1";
$data  = mysql_query($sql,$conn);
$deadline = mysql_fetch_array($data);
if($_SESSION['squadra']==10){
   $giornata=(int)$array['giornata'];
   unset($array['giornata']);
}
else{
  $giornata = $deadline['giornata'];
}
//controllo se si è ancora in tempo ad inserire la formazione
if(($deadline['time']-time())<0 && $_SESSION['squadra']!=10){
  print "Non è possibile inserire la formazione<BR>
         il limite per questa giornata era fissato per il";
  print date("d/m/Y H:i",$deadline['time']);
  print "<BR>in caso di problemi inviare una mail con la formazione a fantastik@netsons.org";
}
else{
//controllo se per la giornata nn è già impostata una formazione
$squadra=$array['squadra'];
unset($array['squadra']);
$sql   = "SELECT * FROM formazioni WHERE giornata=$giornata AND squadra=$squadra";
$data  = mysql_query($sql,$conn);
$formazione = mysql_fetch_array($data);
if($formazione)
{
   print "E' già stata inserita una formazione per la giornata $giornata<BR>";
   print "Prima di inserire una nuova formazione deve essere calcolata la precedente";
}
else{
$size=count($array);
//ottengo i dati
$j=0;
$tit['tot']=0;
$ris['tot']=0;
$tit['P']=0;
$tit['D']=0;
$tit['C']=0;
$tit['A']=0;
$ris['P']=0;
$ris['D']=0;
$ris['C']=0;
$ris['A']=0;
for($i=0;$i<$size;$i++){
   $gioc[$j]['id']=key($array);
   $stato=current($array);
   if($stato!=-1){
   if($stato==0){
        $gioc[$j]['tit']=1;
        $gioc[$j]['prima']=0;
   }
   if($stato==1){
        $gioc[$j]['tit']=0;
        $gioc[$j]['prima']=1;
   }
   if($stato==2){
        $gioc[$j]['tit']=0;
        $gioc[$j]['prima']=0;
   }
   $id=$gioc[$j]['id'];
   $sql   = "SELECT ruolo FROM giocatori WHERE id=$id";
   $data  = mysql_query($sql,$conn);
   $ruolo = mysql_fetch_array($data);
   $gioc[$j]['ruolo'] = $ruolo['ruolo'];
   if($stato==0){
      $tit[$gioc[$j]['ruolo']]++;
      $tit['tot']++;}
   else{
      $ris[$gioc[$j]['ruolo']]++;
      $ris['tot']++;}
   $j++;}
   next($array);
}
//controllo la correttezza della formazione
$ok=0;
if($tit['tot']==11 && $ris['tot']==7){//controllo numerico
   if($tit['P']== 1 && $tit['D']>2 && $tit['C']>2 && $tit['A']>0 && $tit['A']<4){//controllo sui titolari
      if($ris['P']== 1 && $ris['D']==2 && $ris['C']==2 && $ris['A']==2){//controllo sui panchinari
         //controllo che i panchinari siano un primo e un secondo
         for($i=0;$i<18;$i++){
            for($j=0;$j<18;$j++){
              if($i!=$j && $gioc[$i]['tit']==0 && $gioc[$j]['tit']==0 && $gioc[$i]['ruolo']==$gioc[$j]['ruolo']){
                  //sono entrambi riserve dello stesso ruolo
                  if(($gioc[$i]['prima']==0 && $gioc[$j]['prima']==1) || ($gioc[$i]['prima']==1 && $gioc[$j]['prima']==0)){
                   $ok++;}
              }
            }
         }
      }
   }
}
if($ok==6){
   for($i=0;$i<18;$i++){
      $id=$gioc[$i]['id'];
      $ruolo=$gioc[$i]['ruolo'];
      $tit=$gioc[$i]['tit'];
      $prima=$gioc[$i]['prima'];
      $sql = "INSERT INTO formazioni (squadra,giornata,giocatore,ruolo,titolare,primaRiserva) VALUES ($squadra,$giornata,$id,'$ruolo',$tit,  $prima)";
      $data  = mysql_query($sql,$conn);
   }
   print "Formazione inserita correttamente";
}
else{
   print "Errore, devi inserire 11 titolari e 7 riserve<BR>";
   print "Non puoi mettere meno di 3 difensori<BR>";
   print "I centrocampisti devono essere più di 3<BR>";
   print "Gli attacccanti non possono essere più di 3<BR>";
   print "I panchinari devono essere 1 portiere e un primo panchinaro e un secondo panchinaro per gli altri ruoli";
}
}//else formazione già inserita
}//else tempo scaduto
include("./style/bottom.html");
?>
