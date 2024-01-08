<?php
    include "config.php";

    if(empty($_FILES['logo']['name'])){
        $file_name = $_POST['old_logo'];
    } else {
        $errors = array();

        $file_name = $_FILES['logo']['name'];
        $file_size = $_FILES['logo']['size'];
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_type = $_FILES['logo']['type'];
        $ext = explode('.', $file_name);
        $file_ext = end($ext);

        $extensions = array("jpeg", "png", "jpg");

        if(in_array($file_ext,$extensions) === false ) {
            $errors[] = "This extenshions file is not allowed, Please choose a JPG or PNG file.";
        }

        if($file_size > 2097152){
            $error[] = "File Size must be 2mb or Lower.";
        }

        if(empty($errors) == true){
            move_uploaded_file($file_tmp, "admin/ images/".$file_name);
        } else {
                print_r($errors);
                die();
        }
    }
    
    $sql = "UPDATE settings SET websitename='{$_POST["website_name"]}',logo ='{$file_name}',footerdesc='{$_POST["footer_desc"]}'";
    $result = mysqli_query($conn,$sql);
    
    if($result) {
        header("location: {$hostname}/admin/settings.php");
    } else {
        echo "Query Failed.";
    }
?>