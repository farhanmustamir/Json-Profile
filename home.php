<?php
session_start();

include_once 'db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    $userId = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $newName = $_POST['new_name'];
        $newUsername = $_POST['new_username'];
        $newPassword = $_POST['new_password'];
        
        if (empty($newName) || empty($newUsername) || empty($newPassword)) {
            header("Location: home.php?error=empty_fields");
            exit;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

		$updateQuery = "UPDATE users SET fname = ?, username = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$newName, $newUsername, $hashedPassword, $userId]);

        if ($_FILES['new_profile_picture']['error'] == 0) {
            $uploadDir = 'upload/';
            $uploadFile = $uploadDir . basename($_FILES['new_profile_picture']['name']);
            move_uploaded_file($_FILES['new_profile_picture']['tmp_name'], $uploadFile);

            $updateProfilePictureQuery = "UPDATE users SET pp = ? WHERE id = ?";
            $stmt = $conn->prepare($updateProfilePictureQuery);
            $stmt->execute([basename($_FILES['new_profile_picture']['name']), $userId]);
        }

        header("Location: home.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $deleteQuery = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->execute([$userId]);

        header("Location: logout.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/styleHome.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">SIGN UP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="api.php">API</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow p-3 text-center" style="width: 350px;">
            <img src="upload/<?=$_SESSION['pp']?>" class="img-fluid rounded-circle">
            <h3 class="display-4 "><?=$_SESSION['fname']?></h3>
            <a href="logout.php" class="btn btn-warning">Logout</a>

            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="new_name" class="form-label">New Name:</label>
                    <input type="text" class="form-control" name="new_name" required>
                </div>

                <div class="mb-3">
                    <label for="new_username" class="form-label">New Username:</label>
                    <input type="text" class="form-control" name="new_username" required>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>

                <div class="mb-3">
                    <label for="new_profile_picture" class="form-label">New Profile Picture:</label>
                    <input type="file" class="form-control" name="new_profile_picture">
                </div>

                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>

            <form method="post" action="" onsubmit="return confirm('Kau Yakin Kah Menghapus Nih Barang? Yang kau Lakukan Nih Enggak Bisa Di Kembaliin Loh kalau Udah Di Setujuin.');">
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofGJhuv2D9GX0eODx6P5Kl2hu5z9YUWqX"
    crossorigin="anonymous"></script>
</body>
</html>


<?php 
} else {
    header("Location: login.php");
    exit;
}
?>
