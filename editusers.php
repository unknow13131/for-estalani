<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bookstore");


$user_id = $_SESSION['id']; 
$result = $conn->query("SELECT * FROM customer WHERE UserID = '$user_id'");
$user = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newContact = $_POST['contact'];
    $newAddress = $_POST['address'];

    $sql = "UPDATE customer SET 
            CustomerName='$newName', 
            CustomerEmail='$newEmail', 
            CustomerPhone='$newContact', 
            CustomerAddress='$newAddress' 
            WHERE UserID='$user_id'";

    if ($conn->query($sql)) {
        echo "<script>alert('Profile Updated!'); window.location='index.php';</script>";
    }
}
?>

<html>
<head><link rel="stylesheet" href="style.css"></head>
<body class="library-bg"> <div class="container">
        <h2>Edit Profile</h2>
        <form method="post">
            Full Name: <input type="text" name="name" value="<?php echo $user['CustomerName']; ?>"><br>
            Email: <input type="email" name="email" value="<?php echo $user['CustomerEmail']; ?>"><br>
            Contact: <input type="text" name="contact" value="<?php echo $user['CustomerPhone']; ?>"><br>
            Address: <textarea name="address"><?php echo $user['CustomerAddress']; ?></textarea><br><br>
            <input type="submit" class="button" value="Save Changes">
            <a href="index.php" class="button" style="background:grey;">Cancel</a>
        </form>
    </div>
</body>
</html>