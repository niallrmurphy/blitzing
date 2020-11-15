<?

require '../init.env';

if (isset($_POST['search'])) {
  if ($_POST['engineChoice'] == "Bing") {
    do_bing_search($_POST['query']);
  } elseif ($_POST['engineChoice'] == "Google") {
    do_google_search($_POST['query']);
  } else {
    // Unsupported engine
    echo "Unsupported search engine choice.";
  }
} else {
  echo "Search not requested.";
}

function do_bing_search($term) {
  $endpoint = 'https://api.cognitive.microsoft.com/bingcustomsearch/v7.0/search';
  $headers = "Ocp-Apim-Subscription-Key: $BING_KEY\r\n";
  $options = array ('http' => array (
    'header' => $headers,
    'method' => 'GET'));
  $context = stream_context_create($options);
  $result = file_get_contents($url . "?q=" . urlencode($term) .
    "&customconfig=$BING_CUSTOMCONFIG&responseFilter=Webpages" .
    "&mkt=en‌​-IE&safesearch=Moder‌​ate", false, $context);
  print "\nJSON Response:\n\n";
  echo json_encode(json_decode($json), JSON_PRETTY_PRINT);
}

function do_google_search ($term) {

}


?>
