<?

require '../init.env';

if (isset($_REQUEST['search'])) {
  if ($_REQUEST['engineChoice'] == "Bing") {
    do_bing_redirect($_REQUEST['searchquery']);
  } elseif ($_REQUEST['engineChoice'] == "Google") {
    do_google_redirect($_REQUEST['searchquery']);
  } else {
    // Unsupported engine
    echo "<p>Unsupported search engine choice.</p>";
  }
} else {
  echo "<p>Search not requested.</p>";
}

function do_bing_redirect($term) {
  global $BING_CUSTOMCONFIG;
  $hostedui_url = "https://ui.customsearch.ai/hosted-page?customconfig=$BING_CUSTOMCONFIG" .
    "&version=latest&market=en-IE&q=$term&safesearch=Off";
  // $total_redir = "<meta http-equiv=\"refresh\" content=\"0;url=\"$hostedui_url\">";
  header("Location: $hostedui_url");
  die();
}

function do_bing_search($term) {
  global $BING_KEY, $BING_CUSTOMCONFIG;
  $endpoint = 'https://api.bing.microsoft.com/v7.0/custom/search';
  $headersub = "Ocp-Apim-Subscription-Key: $BING_KEY\r\n";
  $header_array = array ('http' => array (
    'header' => $headersub,
    'method' => 'GET'));
  $complete_url = $endpoint . "?q=" . urlencode($term) .
    "&customconfig=$BING_CUSTOMCONFIG&responseFilter=Webpages" .
    "&mkt=en-IE&safesearch=off";
  echo("<code>");
  var_dump($complete_url);
  echo("</code>");
  $result = file_get_contents_curl($complete_url, $header_array['http']);
}

function do_google_search ($term) {

}

function do_google_redirect ($term) {
  global $GOOGLE_CX;
  //$boilerplate = "<!DOCTYPE html>" .
  //  "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
  //$gcx_url = "https://cse.google.com/cse?cs=$GOOGLE_CX";
  //echo("$boilerplate");
  header("Location: goog.html?q=$term");
  //echo("<script async src=\"$gcx_url\"></script>" .
  //  "<div class=\"gcse-searchresults-only\"></div>");
  //echo("</html>");
  die();
}

function file_get_contents_curl($url, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($retcode == 200) {
        return $data;
    } else {
        echo ("RETURN CODE $retcode");
        echo ("DATA $data");
        return null;
    }
}

?>
