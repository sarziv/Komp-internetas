<html>
<head>
<title>Lab4_sarziv</title>
<style>
#top {
  position: fixed;
  bottom: 20;
  right: 10;
  z-index: 999;
  width: 15%;
}
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>

<?php

/*Database CONNECT*/
function Connect()
{
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "root";
 $dbname = "stud";
 $port = "8082";
 // Create connection
 $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname,$port) or die($conn->connect_error);
 return $conn;
}
?>
 

<?php
	/*Database Order*/
	$conn    = Connect();

	$orderBy = "id";
	$order = "asc";
	
	if(!empty($_GET["orderby"])) {
		$orderBy = $_GET["orderby"];
	}
	if(!empty($_GET["order"])) {
		$order = $_GET["order"];
	}
	
	$vardasNextOrder = "asc";
	$epastasNextOrder = "asc";
	$dateNextOrder = "asc";
	$ipNextOrder = "asc";
	$zinuteNextOrder = "asc";

	if($orderBy == "vardas" and $order == "asc") {
		$vardasNextOrder = "desc";
	}
	if($orderBy == "epastas" and $order == "asc") {
		$epastasNextOrder = "desc";
	}
	if($orderBy == "date" and $order == "asc") {
		$dateNextOrder = "desc";
	}
	if($orderBy == "ip" and $order == "asc") {
		$ipNextOrder = "desc";
	}
	if($orderBy == "zinute" and $order == "asc") {
		$zinuteNextOrder = "desc";
	}
?>

<?php
/*Database List*/
 $conn    = Connect();
 $query   = "SELECT * from `sarunas_zivila_lab` ORDER BY " . $orderBy . " " . $order;
 
 $success = $conn->query($query);
if (!$success) {
    die("Couldn't enter data: ".$conn->error);
}
	$result = $conn->query($query);
?>

<body>
<div class="container">
<table class="table table-striped">                     
        <thead>
            <tr>
			  <th><span><a href="?orderby=vardas&order=<?php echo $vardasNextOrder; ?>">Vardas</a></span></th>
              <th><span><a href="?orderby=epastas&order=<?php echo $epastasNextOrder; ?>">E-Pastas</a></span></th>
					<th>
			  <span>
					<a href="?orderby=date&order=<?php echo $dateNextOrder; ?>">Laikas </a>
			  </span>
			  /
			  <span>
					<a href="?orderby=ip&order=<?php echo $ipNextOrder; ?>">IP</a>
			  </span>
				   </th>
			  <th><span><a href="?orderby=zinute&order=<?php echo $zinuteNextOrder; ?>">Zinute</a></span></th>
            </tr>
        </thead>
    <tbody>
<?php
/*Database DISPLAY*/
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<tr>
                  <td scope="row">' . $row["vardas"] . '</td> 
				  <td> ' .$row["epastas"] .'</td>
                  <td> ' .$row["date"] . "<br />\n" . '(' .$row["ip"] . ')' . '</td>
				  <td> ' .$row["zinute"] .'</td>
                </tr>';
    }
}else {
$conn->close();
echo '    
<div class="alert alert-danger" id="top">
<strong>0 Record!</strong></div>';
} 
?>
	</tbody>
</table>
	<!-- FORM -->
<form action="index.php" method="post">
  <div class="form-group">
    <label for="vardas"><strong>Vardas:</strong></label>
    <input class="form-control" id="vardas" maxlength="50" name="vardas" required></input>
  </div>
  <div class="form-group">
    <label for="epastas"><strong>E-Pastas:</strong></label>
    <input type="email" class="form-control" id="epastas" maxlength="50"  name="epastas" required></input>
  </div>
  <div class="form-group">
    <label for="zinute"><strong>Tekstas:</strong></label>
    <textarea class="form-control" id="zinute" maxlength="255" name="zinute" required></textarea>
  </div>
  <button type="submit" id="info" name="submit" class="btn btn-info">Send</button>
</form>
	
<?php  /*Backend + Email validation*/
if (!empty($_POST['vardas']) && ($_POST['epastas']) && ($_POST['zinute'])) {
	
$conn    =    Connect();
$ip =		  $_SERVER['REMOTE_ADDR'];
$vardas    =  $conn->real_escape_string($_POST['vardas']);
$epastas    = $conn->real_escape_string($_POST['epastas']);
$zinute    = $conn->real_escape_string($_POST['zinute']);

$queryMail   = "SELECT epastas FROM sarunas_zivila_lab where epastas='".$epastas."'";
$resultMail = $conn->query($queryMail);

if (!$resultMail) {
    die("Couldn't enter data: ".$conn->error);
}
/*Email valication*/
if ($resultMail->num_rows >= 1) { 
echo '    
<div class="alert alert-danger" id="top">
<strong>Error! Email already in use!</strong></div>';
unset($_POST);
}else{
$queryInfo   = "INSERT INTO `sarunas_zivila_lab`(`date`,`ip`,`vardas`, `epastas`, `zinute`) VALUES (CURRENT_TIMESTAMP(),'$ip','$vardas','$epastas','$zinute')";
$successInfo = $conn->query($queryInfo);
echo '
<div class="alert alert-success" id="top">
<strong>Record added!</strong>List have been updated!</div>';
unset($_POST);
if (!$successInfo) {
    die("Couldn't enter data: ".$conn->error);
}

$conn->close();
 }
}
/* IP validation
$queryip   = "SELECT ip FROM sarunas_zivila_lab where ip='".$ip."'";
$resultip = $conn->query($queryip);
if ($resultip->num_rows < 1) {
else{
echo '    
<div class="alert alert-danger" id="top">
<strong>You IP is already used!</strong></div>';
}*/
?>
</div>
<?php 
echo "<script>
setTimeout(function () {
  $('.alert').alert('close')
}, 3000)
</script>";
?>
</body>
</html>