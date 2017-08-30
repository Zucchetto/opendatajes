<?php


class getshorturl {

  public function shorturl($longUrl){
    // Get API key from : http://code.google.com/apis/console/
    $apiKey = "AIzaSyABhW2DAsHzlYAyLrJxLLgCt0e6J735eYw";

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
    return $json->id;
  //  return $shortLink['id'];

}


}

?>
