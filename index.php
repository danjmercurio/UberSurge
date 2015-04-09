<?php
include('header.php');

function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
{
  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true
    )
  );
  if ($params !== null) {
    $params = http_build_query($params);
    if ($verb == 'POST') {
      $cparams['http']['content'] = $params;
    } else {
      $url .= '?' . $params;
    }
  }

  $context = stream_context_create($cparams);
  $fp = fopen($url, 'rb', false, $context);
  if (!$fp) {
    $res = false;
  } else {
    // If you're trying to troubleshoot problems, try uncommenting the
    // next two lines; it will show you the HTTP response headers across
    // all the redirects:
    // $meta = stream_get_meta_data($fp);
    // var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }

  if ($res === false) {
    throw new Exception("$verb $url failed: $php_errormsg");
  }

  switch ($format) {
    case 'json':
      $r = json_decode($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as json");
      }
      return $r;

    case 'xml':
      $r = simplexml_load_string($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as xml");
      }
      return $r;
  }
  return $res;
}

if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] != 'true') {
	header('Location: login.php');
}

?>
<script>
function refresh() {
    location.reload(true);
}
</script>
<div class="container">
	<div id="main">
		<h1>Uber Surge Data</h1>
		<p>
			<?php
				if ($_SESSION['admin'] == 'true') {
					echo "<a href=\"admin.php\">Admin</a>";
				}
			?>
			<a href="logout.php">Logout</a>
		</p>
		<p>
			<button type="button" onclick="refresh()" class="btn btn-success">Refresh <span class="glyphicon glyphicon-refresh"></span></button>
		</p>
		<?php
		$sql = "SELECT * FROM locations";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			echo "<div>";
			echo "<p><h2>{$row['name']}</h2></p>";
			echo "<p>Latitude: {$row['lat']}</p>";
			echo "<p>Longitude: {$row['long']}</p>";


			//api call starts here
			//how are we getting end lat/long from start lat/long? just casting to int. this will raise an error if the difference is over 100 miles. but i doubt that will happen.
			$server_token = 'SECRET';
			$params = array(
			      'server_token' => $server_token,
			      'start_latitude' => $row['lat'],
			      'start_longitude' => $row['long'],
			      'end_latitude' => intval($row['lat']),
			      'end_longitude' => intval($row['long'])
			    );
			
			$response = rest_helper('https://api.uber.com/v1/estimates/price', $params,'GET','json');
			//var_dump($response);
			$response = $response->prices;
			if (sizeof($response) == 0) {
				echo "<p>Uber didn't return any prices for the given start and end coordinates.</p>";
			} else {
				foreach($response as $r) {
					echo $r->localized_display_name . "<br />";
					echo "Surge Factor: <strong>" . $r->surge_multiplier . "</strong>";
					echo "<br />";
				}
			}
			echo "<hr />";
			echo "</div>";
		}

		?>

	</div>
</div>