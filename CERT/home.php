<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {

 ?>
<!DOCTYPE html>
<html>
<head>
<title>Certificate Upload</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <h1>Hello, <?php echo $_SESSION['name']; ?></h1>   
    
</head>
<body>
    <div class="container">
    <h1>Certificate Upload</h1>
        <form action="home.php" method="post" enctype="multipart/form-data">
        
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            <label for="certificate">Certificate:</label>
            <input type="file" name="certificate" required>
            <input type="submit" value="Upload">
        </form>
    </div>
     <a href="logout.php">Logout</a>
</body>
</html>

<?php
// MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cert";

// Create a connection to the database
$conn = mysqli_connect('localhost','root','','cert');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $name = $_POST["name"];
     $certificateName = $_FILES["certificate"]["name"];
     $certificateTmpName = $_FILES["certificate"]["tmp_name"];
 
     // Create the upload directory if it doesn't exist
     $uploadDir = "certificates/";
     if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
     }
 
     // Move the uploaded file to a permanent location
     $certificatePath = $uploadDir . $certificateName;
     if (move_uploaded_file($certificateTmpName, $certificatePath)) {
         // Insert the certificate details into the database
         $sql = "INSERT INTO certificates (name, path) VALUES ('$name', '$certificatePath')";
         if ($conn->query($sql) === TRUE) {
             echo "Certificate uploaded successfully.";
         } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
         }
     } else {
         echo "Failed to move the uploaded file.";
     }
 }
 
 // Close the database connection
 $conn->close();
 ?>


<?php 
}else{
     header("Location: index.php");
     exit();
}
 ?>

 