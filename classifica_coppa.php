<?php
include("./style/top.html");
require("./db/db_login.php");
require("./db/data.php");
require("./funzioni.php");
$query    = "SELECT * FROM partite WHERE tipo = 2 AND giocata = 1";
for($i=0;$i<8;$i++){
    $id=$i+1;
    $sql= "SELECT * from squadre WHERE id =$id";
    $data   = mysql_query($sql,$conn);
    $squadra= mysql_fetch_array($data);
    $classifica[$i]['nome']=$squadra['nome'];
    $classifica[$i]['id']=$id;
    $classifica[$i]['giocate']=0;
    $classifica[$i]['punti']=0;
    $classifica[$i]['vinte']=0;
    $classifica[$i]['nulle']=0;
    $classifica[$i]['perse']=0;
    $classifica[$i]['golFatti']=0;
    $classifica[$i]['golSubiti']=0;}
$data   = mysql_query($query,$conn);
while($partita = mysql_fetch_array($data)){
        $classifica[$partita['casa']-1]['giocate']++;
        $classifica[$partita['fuori']-1]['giocate']++;
        if($partita['golCasa']>$partita['golFuori']){
                $classifica[$partita['casa']-1]['punti']+=3;
                $classifica[$partita['casa']-1]['vinte']++;
                $classifica[$partita['fuori']-1]['perse']++;}
        if($partita['golCasa']==$partita['golFuori']){
                $classifica[$partita['casa']-1]['punti']++;
                $classifica[$partita['fuori']-1]['punti']++;
                $classifica[$partita['casa']-1]['nulle']++;
                $classifica[$partita['fuori']-1]['nulle']++;}
        if($partita['golCasa']<$partita['golFuori']){
                $classifica[$partita['fuori']-1]['punti']+=3;
                $classifica[$partita['casa']-1]['perse']++;
                $classifica[$partita['fuori']-1]['vinte']++;}
        $classifica[$partita['casa']-1]['golFatti']+=$partita['golCasa'];
        $classifica[$partita['fuori']-1]['golFatti']+=$partita['golFuori'];
        $classifica[$partita['casa']-1]['golSubiti']+=$partita['golFuori'];
        $classifica[$partita['fuori']-1]['golSubiti']+=$partita['golCasa'];
        $classifica[$partita['casa']-1]['ptiFatti']+=$partita['ptiCasa'];
        $classifica[$partita['fuori']-1]['ptiFatti']+=$partita['ptiFuori'];
        $classifica[$partita['casa']-1]['ptiSubiti']+=$partita['ptiFuori'];
        $classifica[$partita['fuori']-1]['ptiSubiti']+=$partita['ptiCasa'];
}
//$classifica è la classifica completa
$A=$classifica;
for($i=0;$i<$squadre;$i+=2)
   unset($A[$i]);
print "<BR>Girone A<BR>";
$qualificateA = stampaclassifica($A,4);
$B=$classifica;
for($i=1;$i<$squadre;$i+=2)
   unset($B[$i]);
print "<BR>Girone B<BR>";
$qualificateB = stampaclassifica($B,4);
if($A[0]['giocate']==6){
   $primaA =$qualificateA[0];
   $primaB =$qualificateB[0];
   $secondaA =$qualificateA[1];
   $secondaB =$qualificateB[1];
   //devo settare le semifinaliste
   $sql = "UPDATE partite SET casa=$secondaB, fuori=$primaA WHERE giornata =23 AND casa =9 AND fuori =9";
   $data   = mysql_query($query,$conn);
   $sql = "UPDATE partite SET casa=$secondaA, fuori=$primaB WHERE giornata =23 AND casa =9 AND fuori =9";
   $data   = mysql_query($query,$conn);
   $sql = "UPDATE partite SET casa=$primaA, fuori=$secondaB WHERE giornata =26 AND casa =9 AND fuori =9";
   $data   = mysql_query($query,$conn);
   $sql = "UPDATE partite SET casa=$primaB, fuori=$secondaA WHERE giornata =26 AND casa =9 AND fuori =9";
   $data   = mysql_query($query,$conn);
}
include("./style/bottom.html");
?>

