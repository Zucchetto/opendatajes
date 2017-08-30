<?php

include(dirname(__FILE__).'/../settings_t.php');

class getdata {

  public function get_news(){
    $html = file_get_contents('http://www.comune.lecce.it/Feeds/news');
    $html=htmlspecialchars_decode($html);
    //  $html=str_replace("http://www.comune.lecce.it/comune/albo-pretorio?DocumentId=","",$html);
    $html=preg_replace( "/\r|\n/", "", $html );
    $html=str_replace( "a10:", "atom", $html );
    //echo $html;
    if (strpos($html,'<channel>') == false) {
    $content = array('chat_id' => $chat_id, 'text' => "Non ci risultano news sui bandi",'disable_web_page_preview'=>true);
      $telegram->sendMessage($content);
    }

    $doc = new DOMDocument;
    $doc->loadHTML($html);
    //echo $doc;
    $xpa     = new DOMXPath($doc);
    $divs0   = $xpa->query('//item/title');
    $divs1   = $xpa->query('//item/title');
    $divs3   = $xpa->query('//atomcontent');
    $divs2   = $xpa->query('//item/link');

    $dival=[];
    $diva3=[];
    $diva1=[];
    $diva2=[];

    $count=0;
    foreach($divs0 as $div0) {
    $count++;
    }
    //echo "Count: ".$count."\n";

    foreach($divs1 as $div1) {

          array_push($diva1,$div1->nodeValue);
    }

    foreach($divs2 as $div2) {

          array_push($diva2,$div2->nodeValue);
    }

    foreach($divs3 as $div3) {
      //  echo "Data: ".$div3->nodeValue."\n";
        array_push($diva3,$div3->nodeValue);

    }

    //$titolo=str_replace(" ","%20",$titolo);
    //$url ="https://docs.google.com/spreadsheets/d/1bjEGyI0uXDoiwwPFJGUVmpVLzbp3P5C16t8Zdub2zis/pub?output=csv";

    $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20WHERE%20A%20IS%20NOT%20NULL%20&key=1V2WgbYbTVT2ICsZVcSxLPxsPSJ67U1qkeW2GEep38VQ"));

    $inizio=1;
    $homepage ="";
    //  echo $url;
    //$csv = array_map('str_getcsv', file($url));


    //$count=3;

    for ($i=0;$i<14;$i++){
    $alert.="\n\n";
    $alert.= "News: ".$diva1[$i]."\n";
    $alert.= substr($diva3[$i], 0, 400)."..[....]\n";
    $alert.= "Link: ".html_entity_decode($csv[$i][0])."\n";

    $alert.="\n__________________";

    }
    return $alert;
  }
    public function get_bandi(){
      $html = file_get_contents('http://www.comune.lecce.it/AlboPretorioRss.aspx?cat=Bandi%20ed%20esiti%20di%20gara');
      $html=htmlspecialchars_decode($html);
      //  $html=str_replace("http://www.comune.lecce.it/comune/albo-pretorio?DocumentId=","",$html);
      $html=preg_replace( "/\r|\n/", "", $html );
      //echo $html;
      if (strpos($html,'<channel>') == false) {
      $content = array('chat_id' => $chat_id, 'text' => "Non ci risultano news sui bandi",'disable_web_page_preview'=>true);
        $telegram->sendMessage($content);
      }

      $doc = new DOMDocument;
      $doc->loadHTML($html);
      //echo $doc;
      $xpa     = new DOMXPath($doc);
      $divs0   = $xpa->query('//item/title');
      $divs1   = $xpa->query('//item/title');
      $divs3   = $xpa->query('//channel/item/link');
      $divs2   = $xpa->query('//item/description');

      $dival=[];
      $diva3=[];
      $diva1=[];
      $diva2=[];

      $count=0;
      foreach($divs0 as $div0) {
      $count++;
      }
      //echo "Count: ".$count."\n";

      foreach($divs1 as $div1) {

            array_push($diva1,$div1->nodeValue);
      }

      foreach($divs2 as $div2) {

            array_push($diva2,$div2->nodeValue);
      }

      foreach($divs3 as $div3) {
        //  echo "Data: ".$div3->nodeValue."\n";
          array_push($diva3,$div3->nodeValue);

      }

      $titolo=str_replace(" ","%20",$titolo);
      //$url ="https://docs.google.com/spreadsheets/d/1bjEGyI0uXDoiwwPFJGUVmpVLzbp3P5C16t8Zdub2zis/pub?output=csv";

      $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20WHERE%20A%20IS%20NOT%20NULL%20&key=1bjEGyI0uXDoiwwPFJGUVmpVLzbp3P5C16t8Zdub2zis"));

      $inizio=1;
      $homepage ="";
      //  echo $url;
      //$csv = array_map('str_getcsv', file($url));


      //$count=3;

      for ($i=0;$i<14;$i++){
      $alert.="\n\n";
      $alert.= $diva1[$i]."\n";
      $alert.= html_entity_decode($diva2[$i])."\n";
      $alert.= "Link: ".$csv[0][0]."\n";
      $alert.="\n__________________";

      }
  return   $alert;
    }

    public function get_matrimonio()
        {

          $url ="https://goo.gl/zZaYbN";

          $inizio=1;
          $homepage ="";
         //  echo $url;
          $csv = array_map('str_getcsv', file($url));

          $count = 0;
          foreach($csv as $data=>$csv1){
            $count = $count+1;
          }
          if ($count == 0 || $count == 1){
            $homepage="Nessun risultato";
            return   $homepage;
          }
          if ($count > 40){
            $homepage="Troppi risultati, affina la ricerca";
            return   $homepage;
          }

        //  echo $count;
          for ($i=$inizio;$i<$count;$i++){

            $homepage .="\n";
            $homepage .="Luogo: ".$csv[$i][0]."\n";
            if ($csv[$i][1] !=NULL)$homepage .="Ubicazione: ".$csv[$i][1]."\n";
            if ($csv[$i][2] !=NULL)$homepage .="Possibilità allestimento: ".$csv[$i][2]."\n";
            if ($csv[$i][3] !=NULL)$homepage .="Gratuità: ".$csv[$i][3]."\n";
            if ($csv[$i][4] !=NULL)$homepage .="Note: ".$csv[$i][4]."\n";
            if ($csv[$i][5] !=NULL)$homepage .="Capienza: ".$csv[$i][5]."\n";
            $homepage .="____________\n";

        }
      return   $homepage;
      }

    public function get_oc($titolo)
        {
          $titolo=str_replace(" ","%20",$titolo);
          $titolo=strtoupper($titolo);
          $url ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20F%20LIKE%20%27%25";
          $url .=$titolo;
          $url .="%25%27&key=1bnHWJAXpVWYyaf9kk4tjiNHzsJp4AEB1S-C97NFw0mQ&gid=1584224163";
          $inizio=1;
          $homepage ="";
         //  echo $url;
          $csv = array_map('str_getcsv', file($url));

          $count = 0;
          foreach($csv as $data=>$csv1){
            $count = $count+1;
          }
          if ($count == 0 || $count == 1){
            $homepage="Nessun risultato";
            return   $homepage;
          }
          if ($count > 40){
            $homepage="Troppi risultati, affina la ricerca";
            return   $homepage;
          }

        //  echo $count;
          for ($i=$inizio;$i<$count;$i++){

            $homepage .="\n";
            $homepage .="Defunto: ".$csv[$i][5]."\n";
            if ($csv[$i][7] !=NULL)$homepage .="Nato il: ".$csv[$i][7]."\n";
            if ($csv[$i][8] !=NULL)$homepage .="a ".$csv[$i][8]."\n";
            if ($csv[$i][9] !=NULL)$homepage .="Deceduto il: ".$csv[$i][9]."\n";
            if ($csv[$i][10] !=NULL)$homepage .="a ".$csv[$i][10]."\n";
            $homepage .="Ubicazione: ".$csv[$i][6]."\n";
            $homepage .="____________\n";

        }
      return   $homepage;
      }
      public function get_sedi($titolo)
          {

            $titolo=str_replace(" ","%20",$titolo);
          //  $titolo=str_replace("à","%E0",$titolo);
            $titolo=strtoupper($titolo);
            $url ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20upper(A)%20LIKE%20%27%25";
            $url .=$titolo;
            $url .="%25%27&key=1l9NE7LDK7Px6gB4zdEoFh6aY0b2fV2tAk45GMxSmc-c&gid=0";
            $inizio=1;
            $homepage ="";
           //  echo $url;
            $csv = array_map('str_getcsv', file($url));
            $count = 0;
            foreach($csv as $data=>$csv1){
              $count = $count+1;
            }
            if ($count == 0 || $count == 1){
              $homepage="Nessun risultato";
              return   $homepage;
            }
            if ($count > 40){
              $homepage="Troppi risultati, affina la ricerca";
              return   $homepage;
            }

          //  echo $count;
            for ($i=$inizio;$i<$count;$i++){

              $homepage .="\n";
              $homepage .=$csv[$i][1]."\nsito in: ".$csv[$i][2]." ".$csv[$i][3]." ".$csv[$i][4].", ".$csv[$i][5];
              if ($csv[$i][8] !=NULL)$homepage .="\nApertura al pubblico:\n".$csv[$i][8];
              if ($csv[$i][7] !=NULL)  $homepage.="\nMappa :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][6]."&mlon=".$csv[$i][7]."#map=19/".$csv[$i][6]."/".$csv[$i][7];
              $homepage .="\n____________\n";

          }
        return   $homepage;
        }
        public function get_uffici()
            {

              $url ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20&key=1l9NE7LDK7Px6gB4zdEoFh6aY0b2fV2tAk45GMxSmc-c&gid=2066323693";
              $inizio=0;
              $homepage ="";
             //  echo $url;
              $csv = array_map('str_getcsv', file($url));
              $count = 0;
              foreach($csv as $data=>$csv1){
                $count = $count+1;
              }
              if ($count == 0 || $count == 1){
                $homepage="Nessun risultato";
                return   $homepage;
              }
              if ($count > 40){
                $homepage="Troppi risultati, affina la ricerca";
                return   $homepage;
              }

            //  echo $count;
              for ($i=$inizio;$i<$count;$i++){

                $homepage .=$csv[$i][0]."\n";

            }
          return   $homepage;
          }

  public function get_libro($titolo)
  {
    $titolo=strtoupper($titolo);
    $titolo=str_replace(" ","%20",$titolo);
    $url ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20upper(C)%20LIKE%20%27%25".$titolo;
    $url .="%25%27%20OR%20upper(B)%20LIKE%20%27%25".$titolo;
    $url .="%25%27&key=1gqlrIL9qch6Ir9cXxkn0sOjyDwSA5uYM_hD7MX_q6Cs&gid=0";
    $inizio=1;
    $homepage ="";
   //  echo $url;
    $csv = array_map('str_getcsv', file($url));

    $count = 0;
    foreach($csv as $data=>$csv1){
      $count = $count+1;
    }
  //  echo $count;
    for ($i=$inizio;$i<$count;$i++){

      $homepage .="\n";
      $homepage .="Autore: ".$csv[$i][1]."\n";
      $homepage .="Titolo: ".$csv[$i][2]."\n";
      $homepage .="Casa Editrice: ".$csv[$i][3]."\n";
      $homepage .="Genere: ".$csv[$i][4]."\n";
      $homepage .="Lingua: ".$csv[$i][5]."\n";
      $homepage .="Anno/Edizione: ".$csv[$i][6]."\n";
      $homepage .="Stato: ".$csv[$i][7]."\n";
      $homepage .="Numero ID: ".$csv[$i][0]."\n";
      $homepage .="____________\n";

  }
return   $homepage;
}


  public function get_fermateba($lat,$lon,$r)
  {



      $json_string = file_get_contents("http://bari.opendata.planetek.it/OrariBus/v2.1/OpenDataService.svc/REST/rete/FermateVicine/".$lat."/".$lon."/".$r/10);
      $parsed_json = json_decode($json_string);
      $count = 0;
      $countl = [];
      foreach($parsed_json as $data=>$csv1){
         $count = $count+1;
      }
      $r10=$r/10;
      echo "<strong>Fermate più vicine rispetto a ".$lat."/".$lon." in raggio di ".$r10." metri con relative linee urbane ed orari arrivi</strong><br><br>\n";
  //    $count=1;
    $IdFermata="";
    //  echo $count;
  for ($i=0;$i<$count;$i++){
    foreach($parsed_json[$i]->{'ListaLinee'} as $data=>$csv1){
       $countl[$i] = $countl[$i]+1;
      }
    //echo $countl;
      $temp_c1 .="Fermata: ".$parsed_json[$i]->{'DescrizioneFermata'}."\n<br>Id Fermata: ".$parsed_json[$i]->{'IdFermata'};
      $temp_c1 .="\n<br>Visualizzala su :\nhttp://www.openstreetmap.org/?mlat=".$parsed_json[$i]->{'PosizioneFermata'}->{'Latitudine'}."&mlon=".$parsed_json[$i]->{'PosizioneFermata'}->{'Longitudine'}."#map=19/".$parsed_json[$i]->{'PosizioneFermata'}->{'Latitudine'}."/".$parsed_json[$i]->{'PosizioneFermata'}->{'Longitudine'};
      $temp_c1 .="\n<br>Linee servite :";
      for ($l=0;$l<$countl[$i];$l++)
        {


      $temp_c1 .="\n<br>Linee: ".$parsed_json[$i]->{'ListaLinee'}[$l]->{'IdLinea'}." ".$parsed_json[$i]->{'ListaLinee'}[$l]->{'Direzione'};
         }
      $temp_c1 .="";


      // inzio sotto routine per orari per linee afferenti alla fermata:

      $IdFermata=$parsed_json[$i]->{'IdFermata'};
  //    echo $IdFermata;
      $json_string1 = file_get_contents("http://bari.opendata.planetek.it/OrariBus/v2.1/OpenDataService.svc/REST/OrariPalina/".$IdFermata."/");
      $parsed_json1 = json_decode($json_string1);
    //  var_dump($parsed_json1);
    //  var_dump($parsed_json1->{'PrevisioniLinee'}[0]);
      $countf = 0 ;
      foreach($parsed_json1->{'PrevisioniLinee'} as $data123=>$csv113){
         $countf = $countf+1;
      }
  //    echo $countf;
      $h = "2";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
      $hm = $h * 60;
      $ms = $hm * 60;
      date_default_timezone_set('UTC');
      for ($f=0;$f<$countf;$f++){

        $time =$parsed_json1->{'PrevisioniLinee'}[$f]->{'OrarioArrivo'}; //registro nel DB anche il tempo unix
    //    echo "\n<br>timestamp:".$time."senza pulizia dati";
        $time =str_replace("/Date(","",$time);
        $time =str_replace("000+0200)/","",$time);
    //    $time =str_replace("T"," ",$time);
    //    $time =str_replace("Z"," ",$time);
        $time =str_replace(" ","",$time);
        $time =str_replace("\n","",$time);
        $timef=floatval($time);
        $timeff = time();
        $timec =gmdate('H:i:s d-m-Y', $timef+$ms);

      //  echo "\n<br>timestamp:".$timef."con pulizia dati";

    //    $date = date_create();
      //echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

    //  date_timestamp_set($date, $time);
    //  $orario=date_format($date, 'U = Y-m-d H:i:s') . "\n";
        $temp_c1 .="\n<br><strong>Linea: ".$parsed_json1->{'PrevisioniLinee'}[$f]->{'IdLinea'}." arrivo: ".$timec."</strong>";
    //    $temp_c1 .=" ".$time;
       }
        $temp_c1 .="\n\n<br><br>";


      // fine sub routine

  }

   return $temp_c1;

  }

  public function get_lineeba()
  {


      $json_string = file_get_contents("http://bari.opendata.planetek.it/OrariBus/v2.1/OpenDataService.svc/REST/rete/Linee");
      $parsed_json = json_decode($json_string);
      $count = 0;
      $countl = [];
      foreach($parsed_json as $data=>$csv1){
         $count = $count+1;
      }
  //    $count=1;
    $IdLinea="";
    //  echo $count;
  for ($i=0;$i<$count;$i++){
    foreach($parsed_json[$i]->{'Id Linea'} as $data=>$csv1){
       $countl[$i] = $countl[$i]+1;
      }
  $temp_c1 .="Percorso: ".$parsed_json[$i]->{'DescrizioneLinea'}."\n<br>Id Linea: ".$parsed_json[$i]->{'IdLinea'};
  $temp_c1 .="\n\n<br><br>";


  $IdLinea=$parsed_json[$i]->{'IdLinea'};
  $json_string1 = file_get_contents("http://bari.opendata.planetek.it/OrariBus/v2.1/OpenDataService.svc/REST/rete/FermateLinea/".$IdLinea);
  $parsed_json1 = json_decode($json_string1);
  for ($f=0;$f<$countl;$f++){
  $temp_c1 .="Direzione: ".$parsed_json1[$f]->{'Direzione'}."\n<br>Id Fermata: ".$parsed_json1[$f]->{'IdFermata'};
}
}
   return $temp_c1;

  }

  public function get_parcheggi()
  {


      $json_string = file_get_contents("http://bari.opendata.planetek.it/parcheggi/1.0/Parcheggi.svc/REST/parcheggi");
      $parsed_json = json_decode($json_string);
      $count = 0;
    	foreach($parsed_json as $data=>$csv1){
    	   $count = $count+1;
    	}
    //  echo $count;
for ($i=0;$i<=1;$i++){
      $temp_c1 .= "Nome parcheggio: ".$parsed_json[$i]->{'NomeParcheggio'}.",\nPosti liberi: ".$parsed_json[$i]->{'DatiVariabili'}->{'NumPostiLiberi'};
    //  var_dump($parsed_json);
$temp_c1 .="\n<br>";
/*
      $time=$parsed_json[$i]->{'DatiVariabili'}->{'OraRicezioneAggiornamento'}; //registro nel DB anche il tempo unix
      $time=str_replace("/Date(","",$time);
      $time=str_replace("+0200)/","",$time);

      $timec=gmdate("d-m-Y\TH:i:s\Z", $time+($ms));
      $timec=str_replace("T"," ",$timec);
      $timec=str_replace("Z"," ",$timec);
      */
      $lat.="\n".$parsed_json[$i]->{'PosizioneGeografica'}->{'Latitudine'}; //registro nel DB anche il tempo unix
      $lon.=$parsed_json[$i]->{'PosizioneGeografica'}->{'Longitudine'}; //registro nel DB anche il tempo unix
      $lat =str_replace(",",".",$lat);
      $lon =str_replace(",",".",$lon);
      $coordinate=$lat.",".$lon;
//      $temp_c4 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'fcttext_metric'};
//      $temp_c5 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'fcttext_metric'};
//      $temp_c6 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'fcttext_metric'};

}
   return $temp_c1.$coordinate;

  }

  //monitoraggio temperatura
	public function get_forecast($where)
	{

		switch ($where) {

			 //Lecce centro
			 case "Lecce":
			$json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/forecast/lang:IT/q/pws:IPUGLIAL7.json");
			$parsed_json = json_decode($json_string);
			$temp_c1 = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
			$temp_c2 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};
			$temp_c3 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[2]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[2]->{'fcttext_metric'};
			$temp_c4 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'fcttext_metric'};
			$temp_c5 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'fcttext_metric'};
			$temp_c6 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'fcttext_metric'};

		break;
		case "Lecceoggi":
	 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/forecast/lang:IT/q/pws:IPUGLIAL7.json");
	 $parsed_json = json_decode($json_string);
	 $temp_c1 = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
	 $temp_c2 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};

	break;

	}
	 return $temp_c1.$temp_c2.$temp_c3.$temp_c4.$temp_c5.$temp_c6;

	}

  public function get_farmacienow($where)
	{
    $time = date('d/m/Y');
    exec('curl -v -c db/cookies.txt "http://www.sanita.puglia.it/gestione-farmacie-di-turno" ');

    exec('curl -v -b db/cookies.txt -L "http://www.sanita.puglia.it/web/pugliasalute/gestione-farmacie-di-turno?p_p_id=farmacie_WAR_PugliaSalutePortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=searchFdt&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_pos=1&p_p_col_count=2" -H "Host: www.sanita.puglia.it" -H "X-Requested-With: XMLHttpRequest" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36" -H "Content-Type: application/json; charset=UTF-8" -H "Accept: application/json, text/javascript, */*; q=0.01" -H "Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4,cs;q=0.2" -H "Accept-Encoding: gzip, deflate, sdch, br" --compressed -H "Referer: http://www.sanita.puglia.it/gestione-farmacie-di-turno" -H "Connection: keep-alive" --data "{\""siglaProvincia\"":\""LE\"",\""codComune\"":\""E506\"",\""nomeComune\"":\""LECCE\"",\""startDate\"":\""'.$time.'\"",\""endDate\"":\""'.$time.'\"",\""checkNow\"":true,\""turni\"":[]}" > db/farmacienow.txt');

    $html = file_get_contents("db/farmacienow.txt");
    $parsed_json = json_decode($html,true);
     //var_dump(  $parsed_json); // debug
    $count = 0;

     $temp="";
     function getInnerSubstring($string,$delim){
         // "foo a foo" becomes: array(""," a ","")
         $string = explode($delim, $string, 3); // also, we only need 2 items at most
         // we check whether the 2nd is set and return it, otherwise we return an empty string
         return isset($string[1]) ? $string[1] : '';
     }
     // echo $count."<br>";
     //echo $parsed_json->{'payload'};

     //echo $time;
     $risposta=json_encode($parsed_json["payload"][0][$time]);
     //echo $risposta."</br>\n";
     $aperte=json_decode($risposta,true)[0]["tutteLeFarmacieSonoAperte"];
     if ($aperte ==1){
       $temp.="Tutte le farmacie sono aperte";
       $temp .="\n--------\n";
     }
    //var_dump($parsed_json["payload"][1]);
    foreach($parsed_json["payload"][1] as $data=>$csv1){
        $count = $count+1;
        $temp .="🏥  <b>".$csv1["nomeStruttura"]."</b>\n";
        $temp .="📞  ".$csv1["numeroDiTelefono"]."\n";
        $temp .="🏳  ".$csv1["indirizzo"]."\n";
        $coordinate=str_replace("POINT ","",$csv1["coordinate"]);
        $coordinate=str_replace(")",";",$coordinate);
        $coordinate=str_replace("(",",",$coordinate);
        $coordinate=str_replace(" ",",;",$coordinate);
        $lat=getInnerSubstring($coordinate,";");
        $lon=getInnerSubstring($coordinate,",");
        $temp .="🌐  https://www.openstreetmap.org/#map=19/".$lat."/".$lon;
        $temp .="\n--------\n";


     }
return $temp;
  }

	public function get_spesecorrenti($where)
	{
    $where=utf8_decode($where);

    $where=str_replace("?","",$where);
    $where=str_replace(" ","%20",$where);
    extract($_POST);
    $url = 'http://soldipubblici.gov.it/it/ricerca';
    $ch = curl_init();
    $file = fopen('db/spese.json', 'w+'); //da decommentare se si vuole il file locale
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8','Accept: Application/json','X-Requested-With: XMLHttpRequest','Content-Type: application/octet-stream','Content-Type: application/download','Content-Type: application/force-download','Content-Transfer-Encoding: binary '));
    curl_setopt($ch,CURLOPT_POSTFIELDS, 'codicecomparto=PRO&codiceente=000705530&chi=Comune+di+Lecce&cosa='.$where);
    curl_setopt($ch, CURLOPT_FILE, $file);
    curl_exec($ch);
    curl_close($ch);

    $json_string = file_get_contents("db/spese.json");
    $parsed_json = json_decode($json_string);
      //var_dump(  $parsed_json); // debug
    $count = 0;
    foreach($parsed_json->{'data'} as $data=>$csv1){
    	   $count = $count+1;
    	}
      $temp_c1="";
    	for ($i=0;$i<$count;$i++){
      $temp_c1 .="\n\n";
      $mese=  substr_replace($parsed_json->{'data'}[$i]->{'imp_uscite_att'}, ",", -2, 0);
      $annoprecedente14=substr_replace($parsed_json->{'data'}[$i]->{'importo_2014'}, ",", -2, 0);
      $annoprecedente15=substr_replace($parsed_json->{'data'}[$i]->{'importo_2015'}, ",", -2, 0);
      $annoincorso=substr_replace($parsed_json->{'data'}[$i]->{'importo_2016'}, ",", -2, 0);
      $temp_c1 .= "Ricerca per: ".$parsed_json->{'data'}[$i]->{'ricerca'}."\nTrovata la voce: ".$parsed_json->{'data'}[$i]->{'descrizione_codice'}."\nCodice Siope: ".$parsed_json->{'data'}[$i]->{'codice_siope'}."\nNel periodo ".$parsed_json->{'data'}[$i]->{'periodo'}."/".$parsed_json->{'data'}[$i]->{'anno'}." spesi: ".$mese."€\nNel 2014 sono stati spesi: ".$annoprecedente14."€\nNel 2015 sono stati spesi: ".$annoprecedente15."€\nIl progressivo 2016 è ".$annoincorso."€";
      $temp_c1 .="\n";

    }

	 return $temp_c1;
	}

  //scraping dal sito web della PPC Lecce
	public function get_allertameteo($where)
	{

		switch ($where) {

	case "Lecceoggi":

	$html = file_get_contents('http://ppc-lecce.3plab.it/');
	//$html = iconv('ASCII', 'UTF-8//IGNORE', $html);
$html=utf8_decode($html);

  $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
	$html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
	$html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);

	$html =str_replace("Consulta il","<!--",$html);
	$html =str_replace("Commenti disabilitati","-->",$html);
	$html =str_replace("Estratto, per la Zona di Allerta del Comune, del Messaggio di Allerta","",$html);
	$html =str_replace("larea","l&#39;area",$html);
	$html =str_replace("Articoli meno recenti","",$html);
	$html =str_replace("if(wpa2a)wpa2a.script_load();","",$html);
	$html =str_replace("←","",$html);

	$doc = new DOMDocument;
	$doc->loadHTML($html);

	$xpa    = new DOMXPath($doc);


	$divs   = $xpa->query('//div[starts-with(@id, "post")]');
	$allerta="";

	foreach($divs as $div) {
	    $allerta .= "\n".$div->nodeValue;

	}
  //$allerta .=preg_replace('/\s+?(\S+)?$/', '', substr($allerta, 0, 400))."....\n";

	break;

	}
	 return $allerta;

	}


	public function get_sosta($lat,$lon)
	{



  //  $lat=40.3550;
  //  $lon=18.1816;
$url='/usr/www/piersoft/sostalecce/index.php '.$lat.' '.$lon;

//exec ('/usr/bin/php -f /usr/www/piersoft/sostalecce/index.php?lat=40.355&lon=18.1816');

//exec ('/usr/bin/php -f '.$url);
//$url1="http://www.piersoft.it/sostalecce/index.php?lat=".$lat."&lon=".$lon;
//header("location: ".$url1);

echo ($lat." ".$lon."\n");
     $content = '';

    if ($fp = fopen("/usr/www/piersoft/sostalecce/testo.txt", "r")) {
       $content = '';
       // keep reading until there's nothing left
       while ($line = fread($fp, 1024)) {
          $content .= $line;
       }
  //  echo $content;

    } else {
      echo "errore";

    }

    return $content;

    }




public function get_events()
    {

	$eventi="";

	date_default_timezone_set('Europe/Rome');
	date_default_timezone_set("UTC");
	$today=time();
	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	$csv = array_map('str_getcsv', file("https://docs.google.com/spreadsheets/d/14Bvk3Pc37xg-1ijTFvs_3qwLhsrbDVuikEqlXnxlwE8/pub?gid=70729341&single=true&output=csv"));
//	$i=1;

	$count = 0;
	foreach($csv as $data=>$csv1){
	   $count = $count+1;
	}

	for ($i=0;$i<$count-2;$i++){
//echo $csv[$i][7]."/n".$csv[$i][8];
	$html =str_replace("/","-",$csv[$i][7]);
	$html =str_replace(",",".",$csv[$i][6]);
		$html =str_replace("|",".",$csv[$i][6]);

	$from = strtotime($html);
	$html1 =str_replace("/","-",$csv[$i][8]);
	$to = strtotime($html1);


	if ($today >= $from && $today <= $to) {
	$eventi .="\n";
	$eventi .="Titolo: ".$csv[$i][4]."\n";
	$eventi .="Tipologia: ".$csv[$i][5]."\n";
	$eventi .="Organizzatore: ".$csv[$i][3]."\n";
	$eventi .="Email contatto: ".$csv[$i][2]."\n";
	//$eventi .="Dettagli: ".$csv[$i][6]."\n";
	$eventi .="Dettagli: ".preg_replace('/\s+?(\S+)?$/', '', substr($csv[$i][6], 0, 400))."....\n";
	$eventi .="Luogo: ".$csv[$i][10]."\n";
	$eventi .="Pagamento: ".$csv[$i][9]."\n";
	$eventi .="Inizio: ".$csv[$i][7]."\n";
	$eventi .="Fine: ".$csv[$i][8]."\n";
	if (strpos($csv[$i][18],'.') !== false) {

    $longUrl = "http://www.openstreetmap.org/?mlat=".$csv[$i][18]."&mlon=".$csv[$i][19]."#map=19/".$csv[$i][18]."/".$csv[$i][19];

    $apiKey = APIT;

    $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
    $jsonData = json_encode($postData);

    $curlObj = curl_init();

    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($curlObj);

    // Change the response json string to object
    $json = json_decode($response);

    curl_close($curlObj);
    //  $reply="Puoi visualizzarlo su :\n".$json->id;
    $shortLink = get_object_vars($json);
    //return $json->id;
    $eventi .="Per visualizzarlo su mappa:\n".$shortLink['id'];

}

  $eventi .="\n";
	}
	}


/*
$i=3; // test
$eventi .="Titolo: ".$csv[$i][4]."\n";
$eventi .="Tipologia: ".$csv[$i][5]."\n";
$eventi .="Organizzatore: ".$csv[$i][3]."\n";
$eventi .="Email contatto: ".$csv[$i][2]."\n";
$eventi .="Dettagli: ".$csv[$i][6]."\n";
$eventi .="Luogo: ".$csv[$i][10]."\n";
$eventi .="Pagamento: ".$csv[$i][9]."\n";
$eventi .="Inizio: ".$csv[$i][7]."\n";
$eventi .="Fine: ".$csv[$i][8]."\n";
if ($csv[$i][18] !="") $eventi .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][18]."&mlon=".$csv[$i][19]."#map=19/".$csv[$i][18]."/".$csv[$i][19];
$eventi .="\n";

*/
	//	echo $eventi;
	 return $eventi;

	}



	public function get_dae($where)
	{
	$homepage="";


	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	$csv = array_map('str_getcsv', file("https://docs.google.com/spreadsheets/d/1dAPW1JSr3bQMFNBM3TF7kFGa95KZT5oY-72QjKipbeQ/export?format=csv&gid=1704313359&single=true"));
//	$homepage  =$csv[0][0];
//	$homepage .="\n";

	$count = 0;
	foreach($csv as $data=>$csv1){
		 $count = $count+1;
	}
	for ($i=1;$i<$count;$i++){

	$homepage .="\n";
	$homepage .=$csv[$i][3]."\n";
	$homepage .= $csv[$i][4]." ".$csv[$i][5]." ".$csv[$i][6]."\n";
//	$homepage = "Descrizione: ".utf8_encode($csv[$i][5])."\n";

$longUrl = "http://www.openstreetmap.org/?mlat=".$csv[$i][1]."&mlon=".$csv[$i][2]."#map=19/".$csv[$i][1]."/".$csv[$i][2];

$apiKey = APIT;

$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
$jsonData = json_encode($postData);

$curlObj = curl_init();

curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlObj, CURLOPT_HEADER, 0);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
curl_setopt($curlObj, CURLOPT_POST, 1);
curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($curlObj);

// Change the response json string to object
$json = json_decode($response);

curl_close($curlObj);
//  $reply="Puoi visualizzarlo su :\n".$json->id;
$shortLink = get_object_vars($json);
//return $json->id;




	$homepage .="Puoi visualizzarlo su: ".$shortLink['id'];
	$homepage .="\n";
//	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

	}

	if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente il comando";


	 echo $homepage;

	 return $homepage;

	}



public function get_hotspot($where)
{
$homepage="";


// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
$csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%20WHERE%20D%20IS%20NOT%20NULL&key=1s6dr0RRIak4zL31qxgebz_Wkni_rLwDyoegSjbjU9NQ"));
//	$homepage  =$csv[0][0];
//	$homepage .="\n";

$count = 0;
foreach($csv as $data=>$csv1){
   $count = $count+1;
}
for ($i=1;$i<$count;$i++){

$homepage .="\n";
$homepage .="Sede: ".$csv[$i][0]."\n";
$homepage .="Numero HS: ".$csv[$i][2]."\n";
//	$homepage = "Descrizione: ".utf8_encode($csv[$i][5])."\n";

$longUrl = "http://www.openstreetmap.org/?mlat=".$csv[$i][3]."&mlon=".$csv[$i][4]."#map=19/".$csv[$i][3]."/".$csv[$i][4];

$apiKey = APIT;

$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
$jsonData = json_encode($postData);

$curlObj = curl_init();

curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlObj, CURLOPT_HEADER, 0);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
curl_setopt($curlObj, CURLOPT_POST, 1);
curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($curlObj);

// Change the response json string to object
$json = json_decode($response);

curl_close($curlObj);
//  $reply="Puoi visualizzarlo su :\n".$json->id;
$shortLink = get_object_vars($json);
//return $json->id;




$homepage .="Puoi visualizzarlo su: ".$shortLink['id'];
$homepage .="\n";
//	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

}

if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente il comando";


 //echo $homepage;

 return $homepage;

}




  public function get_mensa($day,$where,$s)
	{
    $homepage="";
    $url ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20B%20LIKE%20%27%25".$day;
$scuola="Infanzia";
if ($where=="Primaria_Media_Primavera" || $where=="Primaria_Media_Aut_Inverno") $scuola="Primaria";
$inizio=1;
if ($where=="Infanzia_Aut_Inverno")           $url .="%25%27%20AND%20A%20LIKE%20%27%25".$s."%25%27&key=1suKIY8FJdmzRsAk0zjyVVqguV4aN8Y5m1r9Jq1Kzxo0";
elseif ($where=="Infanzia_Primavera")         $url .="%25%27%20AND%20A%20LIKE%20%27%25".$s."%25%27&key=1VT1obAyy-6z0aoBqHoKaFkEf6qB_XZP0L0yqAVYxgNA";
elseif ($where=="Primaria_Media_Primavera")  {
  $url .="%25%27%20AND%20A%20LIKE%20%27%25".$s."%25%27&key=1LPEDTnDUmW2gNTtMQGIidBgQojT9pYJ9PZrhz1q-V-Y";
  $inizio=0;
} elseif ($where=="Primaria_Media_Aut_Inverno")  {
  $url .="%25%27%20AND%20A%20LIKE%20%27%25".$s."%25%27&key=1L-da7CSdv92Bcrfxfle76dFw9BxGEZNJYabIBP3uc1I";
$inizio=0;
} //  echo $url;

  //  $url="https://docs.google.com/spreadsheets/d/1r-A2a47HKuy7dUx4YreSmJxI4KQ-fc4v97J-xt5qqqU/gviz/tq?tqx=out:csv&tq=SELECT+*+WHERE+B+LIKE+%27%25VENERD%25%27+AND+A+LIKE+%27%251%25%27";
    $csv = array_map('str_getcsv', file($url));

    $count = 0;
    foreach($csv as $data=>$csv1){
      $count = $count+1;
    }
  //  echo $count;
    for ($i=$inizio;$i<$count;$i++){

      $homepage .="\nMensa scolastica per la scuola: ".$scuola.". Menu di oggi :\n\n";
    //  $homepage .="Settimana: ".$csv[$i][0]."\n";
      $homepage .="Giorno: ".$csv[$i][1]."\n";
      $homepage .="Primo: ".$csv[$i][2]."\n";
      $homepage .="Secondo: ".$csv[$i][3]."\n";
      $homepage .="Contorno: ".$csv[$i][4]."\n";
      $homepage .="Pane grammi: ".$csv[$i][5]."\n";
      $homepage .="Frutta: ".$csv[$i][6]."\n";
      $homepage .="\n";

  }

  //  if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";

   return $homepage;

 }

  public function get_orariscuole($where)
	{
	   $homepage="";
     switch ($where) {

    case "nido":


    // un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
    $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20C%20LIKE%20%27%25NIDO%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
  //	$homepage  =$csv[0][0];
  //	$homepage .="\n";

    $count = 0;
    foreach($csv as $data=>$csv1){
       $count = $count+1;
    }
    for ($i=1;$i<$count;$i++){

      $homepage .="\n";
      $homepage .=$csv[$i][1]."\n";
      $homepage .="Tipol.: ".$csv[$i][2]."\n";
      //      $homepage .="Categoria: ".$csv[$i][3]."\n";
      $homepage .="Indir.: ".$csv[$i][4]."\n";
          $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
          $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
          $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
          $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
          $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
          $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";

      //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
    $homepage .="\n";
  //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

    }

    if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


  //   echo $homepage;
     break;



  case "infanziastatale":

	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	$csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20D%20LIKE%20%27%25STATALE%25%27%20AND%20C%20LIKE%20%27%25INFANZIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
//	$homepage  =$csv[0][0];
//	$homepage .="\n";

	$count = 0;
	foreach($csv as $data=>$csv1){
		 $count = $count+1;
	}
  echo $count;
	for ($i=1;$i<$count;$i++){

    $homepage .="\n";
    $homepage .=$csv[$i][1]."\n";
    $homepage .="Tipol.: ".$csv[$i][2]."\n";
    //      $homepage .="Categoria: ".$csv[$i][3]."\n";
    $homepage .="Indir.: ".$csv[$i][4]."\n";
        $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
        $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
        $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
        $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
        $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
        $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";

//	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
	$homepage .="\n";
//	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

	}

	if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


	// echo $homepage;
   break;

   case "infanziacomunale":

 	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
 	$csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20D%20LIKE%20%27%25COMUNALE%25%27%20AND%20C%20LIKE%20%27%25INFANZIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
 //	$homepage  =$csv[0][0];
 //	$homepage .="\n";

 	$count = 0;
 	foreach($csv as $data=>$csv1){
 		 $count = $count+1;
 	}
   echo $count;
 	for ($i=1;$i<$count;$i++){

     $homepage .="\n";
     $homepage .=$csv[$i][1]."\n";
     $homepage .="Tipol.: ".$csv[$i][2]."\n";
     $homepage .="Categoria: ".$csv[$i][3]."\n";
     $homepage .="Indir.: ".$csv[$i][4]."\n";
         $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
         $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
         $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
         $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
         $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
         $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";

 //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
 	$homepage .="\n";
 //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

 	}

 	if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


 	// echo $homepage;
    break;
   case "infanziaparitaria":


   // un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
   $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20D%20LIKE%20%27%25PARITARIA%25%27%20AND%20C%20LIKE%20%27%25INFANZIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
   //	$homepage  =$csv[0][0];
   //	$homepage .="\n";

   $count = 0;
   foreach($csv as $data=>$csv1){
      $count = $count+1;
   }
   echo $count;
   for ($i=1;$i<$count;$i++){

     $homepage .="\n";
     $homepage .=$csv[$i][1]."\n";
     $homepage .="Tipol.: ".$csv[$i][2]."\n";
     $homepage .="Categoria: ".$csv[$i][3]."\n";
     $homepage .="Indir.: ".$csv[$i][4]."\n";
         $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
         $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
         $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
         $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
         $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
         $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";

   //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
   $homepage .="\n";
   //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

   }

   if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


   // echo $homepage;
    break;


   case "primariaparitaria":


  // un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
  $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20D%20LIKE%20%27%25PARITARIA%25%27%20AND%20C%20LIKE%20%27%25PRIMARIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
 //	$homepage  =$csv[0][0];
 //	$homepage .="\n";

  $count = 0;
  foreach($csv as $data=>$csv1){
     $count = $count+1;
  }

  echo $count;
  for ($i=1;$i<$count;$i++){
//    for ($i=1;$i<18;$i++){


$homepage .="\n";
$homepage .=$csv[$i][1]."\n";
$homepage .="Tipol.: ".$csv[$i][2]."\n";
$homepage .="Categoria: ".$csv[$i][3]."\n";
$homepage .="Indir.: ".$csv[$i][4]."\n";
    $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
    $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
    $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
    $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
    $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
    $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";


    //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
  $homepage .="\n";
 //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

  }

  if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


   //echo $homepage;
    break;
    case "primaria":


    // un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
    $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20D%20LIKE%20%27%25STATALE%25%27%20AND%20C%20LIKE%20%27%25PRIMARIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
    //	$homepage  =$csv[0][0];
    //	$homepage .="\n";

    $count = 0;
    foreach($csv as $data=>$csv1){
      $count = $count+1;
    }

    echo $count;
    for ($i=1;$i<$count;$i++){
    //    for ($i=1;$i<18;$i++){


    $homepage .="\n";
    $homepage .=$csv[$i][1]."\n";
    $homepage .="Tipol.: ".$csv[$i][2]."\n";
    $homepage .="Categoria: ".$csv[$i][3]."\n";
    $homepage .="Indir.: ".$csv[$i][4]."\n";
     $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
     $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
     $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
     $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
     $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
     $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";


     //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
    $homepage .="\n";
    //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

    }

    if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


    //echo $homepage;
     break;


    case "secondaria_primogrado":


    // un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
    $csv = array_map('str_getcsv', file("https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%2CB%2CC%2CD%2CE%2CF%2CG%2CH%2CI%2CJ%2CK%2CL%2CM%2CN%2CO%2CP%2CQ%2CR%2CS%20WHERE%20C%20LIKE%20%27%25SECONDARIA%25%27&key=1EJb0cq6a5C5NzgBZiP-KBAs4C_TIgi_b9vmffROp0QU"));
  //	$homepage  =$csv[0][0];
  //	$homepage .="\n";

    $count = 0;
    foreach($csv as $data=>$csv1){
       $count = $count+1;
    }
    for ($i=1;$i<$count;$i++){

      $homepage .="\n";
      $homepage .=$csv[$i][1]."\n";
      $homepage .="Tipol.: ".$csv[$i][2]."\n";
      $homepage .="Categoria: ".$csv[$i][3]."\n";
      $homepage .="Indir.: ".$csv[$i][4]."\n";
          $homepage .="Lun. ".$csv[$i][5]."/".$csv[$i][6]."\n";
          $homepage .="Mar. ".$csv[$i][7]."/".$csv[$i][8]."\n";
          $homepage .="Merc.".$csv[$i][9]."/".$csv[$i][10]."\n";
          $homepage .="Giov.".$csv[$i][11]."/".$csv[$i][12]."\n";
          $homepage .="Ven. ".$csv[$i][13]."/".$csv[$i][14]."\n";
          $homepage .="Sab. ".$csv[$i][15]."/".$csv[$i][16]."\n";

      //	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][17]."&mlon=".$csv[$i][18]."#map=19/".$csv[$i][17]."/".$csv[$i][18];
    $homepage .="\n";
  //	$homepage .="Per vedere tutti i luoghi dove è presente un defribillatore clicca qui: http://u.osmfr.org/m/54531/"

    }

    if (empty($csv[1][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente gli orari";


  //   echo $homepage;
     break;

	}
	  return $homepage;

	}

	public function get_aria($where)
	{
	$homepage="";

		switch ($where) {

	case "lecce":
	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	$csv = array_map('str_getcsv', file("https://docs.google.com/spreadsheets/d/1It2A_VDqWFP01Z7UguDDPDrKGY6xD94AdCl7dWgt5YA/export?format=csv&gid=1088545279&single=true"));
	$homepage  =$csv[0][0];
	$homepage .="\n";

	$count = 0;
	foreach($csv as $data=>$csv1){
		 $count = $count+1;
	}
	for ($i=2;$i<$count;$i++){

	$homepage .="\n";
	$homepage .="Nome Centralina: ".$csv[$i][0]."\n";
	$homepage .="Valore_Pm10: ".$csv[$i][1]." µg/m³\n";
	$homepage .="Valore_Benzene: ".$csv[$i][2]." µg/m³\n";
	$homepage .="Valore_CO: ".$csv[$i][3]." mg/m³\n";
	$homepage .="Valore_SO2: ".$csv[$i][4]." µg/m³\n";
	$homepage .="Valore_PM_2.5: ".$csv[$i][5]." µg/m³\n";
	$homepage .="Valore_O3: ".$csv[$i][6]." µg/m³\n";
	$homepage .="Valore_NO2: ".$csv[$i][7]." µg/m³\n";
	$homepage .="Superati: ".$csv[$i][8]."\n";


	}

 if (empty($csv[2][0])) $homepage="Errore generico, ti preghiamo di selezionare nuovamente il comando";


	break;

		}
	// echo $homepage;

	 return $homepage;

	}

	public function get_traffico($where)
	{
	$homepage="";

		switch ($where) {

	case "lecce":
	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	// servizio sperimentale e Demo.
	$csv = array_map('str_getcsv', file("https://docs.google.com/spreadsheets/d/1IfmPLAFr7Ce0Iyd0fj_LQu1EPR0-vJMY5kaWS7IuRAA/pub?gid=0&single=true&output=csv"));
	//$homepage  =$csv[0][0];
	$homepage .="\n";
	$count = 0;
	foreach($csv as $data=>$csv1){
	   $count = $count+1;
	}
	for ($i=1;$i<$count;$i++){

	$homepage .="\n";
	$homepage .="Tipologia: ".$csv[$i][1]."\n";
	$homepage .="Descrizione: ".$csv[$i][2]."\n";
	$homepage .="Data: ".$csv[$i][3]."\n";
	$homepage .="Luogo: ".$csv[$i][4]."\n";
  if ($csv[$i][7] !=NULL) $homepage .="Nota: ".$csv[$i][7]."\n";
	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][5]."&mlon=".$csv[$i][6]."#map=19/".$csv[$i][5]."/".$csv[$i][6];

	//$homepage .="Mappa: http://www.openstreetmap.org/#map=19/".$csv[$i][4]."/".$csv[$i][5];
	$homepage .="\n";


	}

	break;

		}

	 return $homepage;

	}

  public function get_monumenti($where)
  {

    $csv = array_map('str_getcsv', file("https://goo.gl/ZoQo3S"));
    //	$i=1;

    $count = 0;
    foreach($csv as $data=>$csv1){
       $count = $count+1;
    }
    //var_dump($csv);

    for ($i=1;$i<$count;$i++){
      $homepage .="\n";
      $homepage .="\nNome: ".$csv[$i][0]."\n";
      $homepage .="Indirizzo: ".$csv[$i][1]."\n";
      if ($csv[$i][4] != NULL) $homepage .="Wikipedia: ".$csv[$i][4]."\n";
      $longUrl = "http://www.openstreetmap.org/?mlat=".$csv[$i][2]."&mlon=".$csv[$i][3]."#map=19/".$csv[$i][2]."/".$csv[$i][3];

       $apiKey = APIT;

       $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
       $jsonData = json_encode($postData);

       $curlObj = curl_init();

       curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
       curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($curlObj, CURLOPT_HEADER, 0);
       curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
       curl_setopt($curlObj, CURLOPT_POST, 1);
       curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

       $response = curl_exec($curlObj);

       // Change the response json string to object
       $json = json_decode($response);

       curl_close($curlObj);
       //  $reply="Puoi visualizzarlo su :\n".$json->id;
       $shortLink = get_object_vars($json);
       //return $json->id;
       $homepage  .="Per visualizzarlo su mappa:".$shortLink['id']."\n";

    //   echo $homepage.$homepage1;
    }
return $homepage;
}

//monitoraggio temperatura
public function get_temperature($where)
{
	switch ($where) {

		 //Lecce centro
		 case "Lecce centro":
		 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/conditions/q/pws:IPUGLIAL7.json");
		 $parsed_json = json_decode($json_string);
		 $location = $parsed_json->{'location'}->{'city'};
		 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
		 break;

		 //Lequile
		 case "Lequile":
		 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/conditions/q/pws:IPUGLIAL3.json");
		 $parsed_json = json_decode($json_string);
		 $location = $parsed_json->{'location'}->{'city'};
		 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
		 break;

		 //Galatina
		 case "Galatina":
		 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/conditions/q/pws:IPUGLIAG14.json");
		 $parsed_json = json_decode($json_string);
		 $location = $parsed_json->{'location'}->{'city'};
		 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
		 break;

		 //Nardò
		 case "Nardò":
		 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/conditions/q/pws:IPUGLIAN2.json");
		 $parsed_json = json_decode($json_string);
		 $location = $parsed_json->{'location'}->{'city'};
		 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
		 break;

	}
	return $temp_c;
}

//definisci il path dell'immagine
public function get_image_path($image)
{
	return "data/". $image. ".jpg";
}

//preleva ultima allerta del feed protezione civile di Prato o in locale o in remoto e ritorna titolo e data.
public function load_prot($islocal)
{
	date_default_timezone_set('UTC');

	$logfile=(dirname(__FILE__).'/logs/storedata.log');

	if($islocal)
	{
		//carico dati salvati in locale per confrontarli con quelli remoti
		$prot_civ=dirname(__FILE__)."/data/prot.xml";
		echo "carico dati in locale";
		print_r($prot_civ);
	}
	else
	{
		//carico dati salvati in remoto
		$prot_civ=PROT_CIV;
		echo "carico dati da remoto";
		print_r($prot_civ);

	}

	$xml_file=simplexml_load_file($prot_civ);

	if ($xml_file==false)
		{
			print("Errore nella ricerca del file relativo alla protezione civile");
		}

		//ritorna il primo elemento del feed rss
		$data[0]=$xml_file->channel->item->title;
		//print_r($data[0]);
		$data[1]=$xml_file->channel->item->pubDate;
		//print_r($data[1]);
		return $data;
}

public function update_prot($data)
{
	$prot_civ=dirname(__FILE__)."/data/prot.xml";

	// load the document
	$info = simplexml_load_file($prot_civ);

	// update
	$info->channel->item->title = $data[0];
	$info->channel->item->pubDate = $data[1];

	// save the updated document
	$info->asXML($prot_civ);

}


}
//Fonti
//http://www.lamma.rete.toscana.it/…/comuni_web/dati/prato.xml
//http://data.biometeo.it/BIOMETEO.xml
//http://data.biometeo.it/PRATO/PRATO_ITA.xml
//http://www.sir.toscana.it/supports/xml/risks_395/".$today.".xml"
//http://www.wunderground.com/weather/api/
//https://github.com/alfcrisci/WU_weather_list/blob/master/WU_stations.csv
 ?>
