<?php
/* Clementime Mashile u18139508 */
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
$server = "localhost";
$username = "root";
$password = "";
$database = "dbUser";
$mysqli = mysqli_connect($server, $username, $password, $database);
$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
$QueryString = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
$userOutput = mysqli_query($mysqli, $QueryString);
if ($row = mysqli_fetch_array($userOutput))
{
    $user_id = $row['user_id'];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="Clementime Mashile">
		<title>IMY 220 - Assignment 2</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<meta charset="utf-8" />
		</head>
	<body>
		<div class="container">
			<?php
if ($email && $pass)
{
    $query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
    $res = $mysqli->query($query);
    if ($row = mysqli_fetch_array($res))
    {
        echo "<table class='table table-bordered mt-3'>
									<tr>
										<td>Name</td>
										<td>" . $row['name'] . "</td>
									<tr>
									<tr>
										<td>Surname</td>
										<td>" . $row['surname'] . "</td>
									<tr>
									<tr>
										<td>Email Address</td>
										<td>" . $row['email'] . "</td>
									<tr>
									<tr>
										<td>Birthday</td>
										<td>" . $row['birthday'] . "</td>
									<tr>
								</table>";

        echo "<form action = 'login.php' method = 'post' enctype = 'multipart/form-data'>
									<div class='form-group'>
										<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
										<input type='hidden'  id='loginPass' name='loginPass' value = '$pass'/> 
										<input type='hidden' id='loginEmail' name='loginEmail' value = '$email'/> 
										<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									</div>
								</form>";
    }
    else
    {
        echo '<div class="alert alert-danger mt-3" role="alert">
									You are not registered on this site!
								</div>';
    }
}
else
{
    echo '<div class="alert alert-danger mt-3" role="alert">
								Could not log you in
							</div>';
}

if (isset($_POST["submit"]))
{
    $target_dir = "Gallery/";
    $uploadFile = $_FILES["picToUpload"];
    $target_file = $target_dir . basename($uploadFile["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    if ((($uploadFile["size"] < 1048576) && ($uploadFile["size"] > 0)) && (($uploadFile["type"] == "image/jpeg") || ($uploadFile["type"] == "image/jpg")))
    {
        $check = getimagesize($uploadFile["tmp_name"]);
        if ($check !== false)
        {
            if (move_uploaded_file($uploadFile["tmp_name"], $target_file))
            {
                $name = $uploadFile['name'];
                $query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$user_id', '$name');";
                $res = mysqli_query($mysqli, $query) == true;
            }
            else
            {
                echo "Error uploading file.";
            }
        }
        else
        {
            echo "Incorrect format";
        }
    }
    else echo "invalid file";
}
?>
		</div>
		<div class = "container">
			<h1>Image Gallery </h1>
			<div class = "row imageGallery">
				<?php
					$request = "SELECT filename FROM tbgallery WHERE user_id = '$user_id'";
					$result = mysqli_query($mysqli, $request);
					while ($row = mysqli_fetch_array($result))
					{
						echo "<div class= 'col-3' style = 'background-image: url(images/" . $row["filename"] . ")'> </div>";
					}
				?>
			</div>
		</div>
	</body>
</html>
