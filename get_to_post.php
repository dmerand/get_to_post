<?php

//probably should put auth tokens into another file off-site :)
$auth_tokens = array("1qaz2wsx3edc4rfv5tgb6yhn" => "Proof");
$token = $_GET['token'];

if (array_key_exists($token, $auth_tokens)) {
  get_to_post();
} else {
  not_found();
}

//return a "NOT FOUND" header
function not_found() { header("HTTP/1.0 404 Not Found"); }

//convert a GET request to a POST request
function get_to_post() {

  header('Content-Type: text/plain');

  if ($_SERVER['REQUEST_METHOD'] == "GET" ) {
    //GET requets get converted to POST requests
    $get_data = $_GET;
    $post_data = array();
    //if no url parameter is sent, default to localhost
    $url = "http://localhost/get_to_post.php";

    foreach ($get_data as $key => $value ) {
      if ($key == "url") {
        $url = $value;
      } elseif ($key == "token") {
        //eat the token parameter
      } else {
        $post_data[$key] = $value;
      }
    }

    // Submit those variables to the server
    $result = post_request($url, $post_data);
     
    if ($result['status'] == 'ok'){
        // Print headers 
        echo $result['header'];
        // print the result of the whole request:
        echo $result['content'];
    }
    else {
        echo 'A error occured: ' . $result['error']; 
    }
  } else {
    //just dump anything that's not GET
    $server_data = $_SERVER;
    var_dump($server_data);
  } 
}

//actually run a POST request
//stolen from http://www.jonasjohn.de/snippets/php/post-request.htm
function post_request($url, $data, $referer='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http' && $url['scheme'] != 'https') { 
        die('Error: Only HTTP(S) request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

?>
