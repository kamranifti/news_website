<?php

include "config.php";

if (isset($_FILES['fileToUpload'])) {

    $errors = array();

    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
    echo $file_name;
    $file_ext = strtolower(end(explode('.', $file_name)));
    echo $file_ext;
    $extension = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extension) === false) {
        $errors[] = "This extension file is not allowed, plz choose a jpg or pngimage";
    }

    if ($file_size > 2097152) {
        $errors[] = "the file must be less then 2MBs";
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}

session_start();
$title = mysqli_real_escape_string($conn, $_POST['post_title']);
$description = mysqli_real_escape_string($conn, $_POST['postdesc']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$date = date("d M, Y");
$author = $_SESSION['user_id'];

$sql = "INSERT INTO post(title,description,category,post_date,author,post_img)
    VALUES('{$title}','{$description}',{$category},'{$date}',{$author},'{$file_name}');";

$sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category}";

if (mysqli_multi_query($conn, $sql)) {
    header("location: {$hostname}/admin/post.php");
} else {
    echo "<div class='alert alert_danger'>Query Failed</div>";
}
