<?

require '../init.env';

if (isset($_REQUEST['search'])) {
  echo ("inside search<p>");
  if ($_REQUEST['engineChoice'] == "Bing") {
    echo ("chose bing<p>");
    do_bing_search($_REQUEST['searchquery']);
  } elseif ($_REQUEST['engineChoice'] == "Google") {
    echo ("chose google<p>");
    do_google_search($_REQUEST['searchquery']);
  } else {
    // Unsupported engine
    echo "Unsupported search engine choice.";
  }
} else {
  echo "<p>Search not requested.</p>";
}

function do_bing_search($term) {
  global $BING_KEY, $BING_CUSTOMCONFIG;
  echo ("do bing for '$term'<p>");
  $endpoint = 'https://api.bing.microsoft.com/v7.0/custom/search';
  $headers = "Ocp-Apim-Subscription-Key: $BING_KEY\r\n";
  $options = array ('http' => array (
    'header' => $headers,
    'method' => 'GET'));
  $context = stream_context_create($options);
  $complete_url = $endpoint . "?q=" . urlencode($term) .
    "&customconfig=$BING_CUSTOMCONFIG&responseFilter=Webpages" .
    "&mkt=en-IE&safesearch=off";
  echo("<code>");
  var_dump($complete_url);
  echo("</code>");
  $result = file_get_contents($complete_url, false, $context);
  var_dump($result);
  print "\nJSON Response:\n\n";
  echo json_encode(json_decode($result), JSON_PRETTY_PRINT);
}

function do_google_search ($term) {

}


?>
