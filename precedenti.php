<?php
session_start();
include("./style/top.html");
if($_SESSION['loggato']=="yes"){
   require("./db/db_login.php");
   $squadra=$_SESSION['squadra'];
   for($i=1;$i<9;$i++){
        if($i!=$squadra){
          //ottengo il nome dell'avversario
          $query = "SELECT nome FROM squadre WHERE id=$i";
          $data   = mysql_query($query,$conn);
          $nome   =mysql_fetch_array($data);
          print $nome['nome'];
          print "<BR><TABLE class=sample>";
          print "<TR><TD></TD><TD>Vinte</TD><TD>Nulle</TD><TD>Perse</TD><TD>GolFatti</TD><TD>GolSubiti</TD></TR>";
          $vTot=0;
          $nTot=0;
          $pTot=0;
          $gfTot=0;
          $gsTot=0;
          //Casa
          $v=0;
          $n=0;
          $p=0;
          $gf=0;
          $gs=0;
          $query = "SELECT * from partite WHERE casa=$squadra AND fuori=$i AND giocata=1";
          $data   = mysql_query($query,$conn);
          while($partita = mysql_fetch_array($data)){
               if($partita['golCasa']>$partita['golFuori']){
                  $v++;
               }
               if($partita['golCasa']<$partita['golFuori']){
                  $p++;
               }
               if($partita['golCasa']==$partita['golFuori']){
                  $n++;
               }
               $gf+=$partita['golCasa'];
               $gs+=$partita['golFuori'];
          }
          print "<TR>";
          print "<TD>Casa</TD><TD>$v</TD><TD>$n</TD><TD>$p</TD><TD>$gf</TD><TD>$gs</TD></TR>";
          $vTot+=$v;
          $nTot+=$n;
          $pTot+=$p;
          $gfTot+=$gf;
          $gsTot+=$gs;
          //Trasferta
          $v=0;
          $n=0;
          $p=0;
          $gf=0;
          $gs=0;
          $query = "SELECT * from partite WHERE casa=$i AND fuori=$squadra AND giocata=1";
          $data   = mysql_query($query,$conn);
          while($partita = mysql_fetch_array($data)){
               if($partita['golCasa']<$partita['golFuori']){
                  $v++;
               }
               if($partita['golCasa']>$partita['golFuori']){
                  $p++;
               }
               if($partita['golCasa']==$partita['golFuori']){
                  $n++;
               }
               $gs+=$partita['golCasa'];
               $gf+=$partita['golFuori'];
          }
          print "<TD>Fuori</TD><TD>$v</TD><TD>$n</TD><TD>$p</TD><TD>$gf</TD><TD>$gs</TD></TR>";
          $vTot+=$v;
          $nTot+=$n;
          $pTot+=$p;
          $gfTot+=$gf;
          $gsTot+=$gs;
          print "<TD>Totale</TD><TD>$vTot</TD><TD>$nTot</TD><TD>$pTot</TD><TD>$gfTot<Tot/TD><TD>$gsTot</TD></TR>";
          print "</TABLE>";
        }
   }
}
?>
