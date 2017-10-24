<html>
<head>
<title>Lab4_sarziv</title>
<style>
#top {
  position: fixed;
  bottom: 20;
  right: 10;
  z-index: 999;
  width: 30%;
}
.alert {
    padding: 15px;
    background-color: #fafafa;
    border-left: 6px solid #7f7f84;
    margin-bottom: 10px;
    -webkit-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
       -moz-box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
            box-shadow: 0 5px 8px -6px rgba(0,0,0,.2);
}
.alert-sm {
    padding: 10px;
    font-size: 80%;
}
.alert-lg {
    padding: 35px;
    font-size: large;
}
.alert-success {
    border-color: #80D651;
}
.alert-success>strong {
    color: #80D651;
}
.alert-info {
    border-color: #45ABCD;
}
.alert-info>strong {
    color: #45ABCD;
}
.alert-warning {
    border-color: #FEAF20;
}
.alert-warning>strong {
    color: #FEAF20;
}
.alert-danger {
    border-color: #d73814;
}
.alert-danger>strong {
    color: #d73814;
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>

<?php
session_start();
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
/*Database LIST*/
 $conn    = Connect();
 $query   = "SELECT * FROM `sarunas_zivila_lab`";
 
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
			  <th>Vardas</th>
              <th>E-Pastas</th>
              <th>Laikas/IP</th>
			  <th>Zinute</th>
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
} else {
/*
echo '<script language="javascript">';
echo 'alert("Failed! or 0 Rezults in Database!")';
echo '</script>';
*/
} 
$conn->close();
/*
echo '<script language="javascript">';
echo 'alert("Fetched data successfully\n")';
echo '</script>';
*/
?>
	</tbody>
</table>

<form action="index.php" method="post">
  <div class="form-group">
    <label for="vardas"><strong>Vardas:</strong></label>
    <input class="form-control" id="vardas" name="vardas" required></input>
  </div>
  <div class="form-group">
    <label for="epastas"><strong>E-Pastas:</strong></label>
    <input type="email" class="form-control" id="epastas"  name="epastas" required></input>
  </div>
  <div class="form-group">
    <label for="zinute"><strong>Tekstas:</strong></label>
    <textarea class="form-control" id="zinute" name="zinute" required></textarea>
  </div>
  <button type="submit" id="info" name="submit" class="btn btn-info">Send</button>
</form>
	
<?php  


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

 if ($resultMail->num_rows >= 1) {
echo '    <div class="alert alert-danger" id="top">
    <strong>Error!</strong> This email already in use!</div>';
unset($_POST);
}else{
$queryInfo   = "INSERT INTO `sarunas_zivila_lab`(`date`,`ip`,`vardas`, `epastas`, `zinute`) VALUES (CURRENT_TIMESTAMP(),'$ip','$vardas','$epastas','$zinute')";
$successInfo = $conn->query($queryInfo);
echo '    <div class="alert alert-success" id="top">
    <strong>Record added!</strong>List have been updated!</div>';
if (!$successInfo) {
    die("Couldn't enter data: ".$conn->error);
}
$conn->close();
unset($_POST);
session_destroy();
 }
}

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