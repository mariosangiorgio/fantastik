<?php
include("./style/top.html");
require("./db/db_login.php");
require("./db/data.php");
require("./funzioni.php");
$query    = "SELECT * FROM partite WHERE tipo = 1 AND giocata = 1";
for($i=0;$i<$squadre;$i++){
    $id=$i+1;
    $sql= "SELECT * from squadre WHERE id =$id";
    $data   = mysql_query($sql,$conn);
    $squadra=mysql_fetch_array($data);
    $classifica[$i]['nome']=$squadra['nome'];
    $classifica[$i]['id']=$id;
    $classifica[$i]['giocate']=0;
    $classifica[$i]['punti']=0;
    $classifica[$i]['vinte']=0;
    $classifica[$i]['nulle']=0;
    $classifica[$i]['perse']=0;
    $classifica[$i]['golFatti']=0;
    $classifica[$i]['golSubiti']=0;
    $classifica[$i]['ptiFatti']=0;
    $classifica[$i]['ptiSubiti']=0;}
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

stampaclassifica($classifica,8);
include("./style/bottom.html");
?>

