<?php
function getData($giornata,$conn){
  if($giornata<10){
       $sorgenti = "http://magiccup.gazzetta.it/statiche/campionato/2007/magic_card/MCC0$giornata.zip";}
  else{
      	$sorgenti = "http://magiccup.gazzetta.it/statiche/campionato/2007/magic_card/MCC$giornata.zip";
  }
  $data=file_get_contents( $sorgenti );
  if($data == FALSE){
     return 0;       }
  else{
    //copio il file zippato
    $fh = fopen("temp.zip", 'w');
    fwrite($fh,$data);
    fclose($fh);
    //decomprimo il file
    $zip = zip_open(getcwd() . "/temp.zip");
    $zip_entry = zip_read($zip);
    zip_entry_open($zip, $zip_entry, "r");
    $buffer = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
    zip_entry_close($zip_entry);
    //separol le linee
    $regex = "#(.*)\n#";
    preg_match_all($regex,$buffer,$giocatori);
    $giocatori=$giocatori[0];
    $max=count($giocatori);
    for($i=0;$i< $max;$i++){
      $valori= split ('[|]', $giocatori[$i]);
      //0 Code
      //2 Nome
      //3 Squadra
      //27 Costo
      $num=count($valori);
      $sql = "SELECT * FROM giocatori WHERE id=$valori[0]";
      $data = mysql_query($sql,$conn);
      /*
      if(!(mysql_fetch_array($data))){
        //Se il giocatore nn è mai stato inerito lo aggiungo ora
        $query    = "INSERT INTO `giocatori` (`id`,`nome`,`fantasquadra`) VALUES ($valori[0],$valori[2],0)";
        $data      = mysql_query($query,$conn);}
      if($valori[){
         $query = "INSERT INTO voti (id,giornata,voto,fanta) VALUES ($valori[0],$giornata,//voto,//fanta)";
         $data      = mysql_query($query,$conn);}
      */
      //$valori[23]è giocato
     if($valori[23] or $valori[7]){
                    $query = "INSERT INTO voti (id,giornata,votopreso,voto,fantapreso,fanta,golFatti,golSubiti,golVittoria,golPareggio,assist,ammonizione,espulsione,rigoreTirato,rigoreSubito,rigoreParato,rigoreSbagliato,autogol,giocato,titolare,casa) VALUES ($valori[0],$valori[1],$valori[9],$valori[10],$valori[6],$valori[7],$valori[11],$valori[12],$valori[13],$valori[14],$valori[15],$valori[16],$valori[17],$valori[18],$valori[19],$valori[20],$valori[21],$valori[22],$valori[23],$valori[24],$valori[26])";
                    $data      = mysql_query($query,$conn);}
    }
return 1;}
}
?>
