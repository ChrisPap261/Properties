<?php include('config.php'); ?>

<?php
echo $uuid = addslashes($_POST['uuid']);
echo $address = addslashes($_POST['address']);
echo $country = addslashes($_POST['country']);
echo $county = addslashes($_POST['county']);
echo $description = addslashes($_POST['description']);
echo $image_full = $_FILES['image'];
echo $num_bathrooms = addslashes($_POST['num_bathrooms']);
echo $num_bedrooms = addslashes($_POST['num_bedrooms']);
echo $price = addslashes($_POST['price']);
echo $property_type = addslashes($_POST['property_type']);
echo $town = addslashes($_POST['town']);
echo $type = addslashes($_POST['type']);


   if(isset($_FILES['image'])){
     $errors= array();
     $temp = rand(00000, 99999);
     $file_name = $temp.$_FILES['image']['name'];
     $file_size =$_FILES['image']['size'];
     $file_tmp =$_FILES['image']['tmp_name'];
     $file_type=$_FILES['image']['type'];
     $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

     $extensions= array("jpeg","jpg","png");

     if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
     }

     if(empty($errors)==true){
        move_uploaded_file($file_tmp,"images/".$file_name);
        echo "Success";
     }

     $image_full = "images/".$file_name;

      /*************** Resize Image for Thumbnail /***************/

      // Get image dimensions
      list($width, $height) = getimagesize($image_full);
      $newwidth = 100;
      $newheight = 100;

      // Get the image
      $thumb = imagecreatetruecolor($newwidth, $newheight);
      $source = imagecreatefromjpeg($image_full);

      // Resize the image
      imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

      // Output the image
      header('Content-Type: image/jpeg');
      $image_thumbnail = 'images/100x100-'.$file_name.'.jpg';
      imagejpeg($thumb, $image_thumbnail);

      /**********************************************************/

      $query = "UPDATE properties SET uuid='$uuid', address='$address', country='$country', county='$county', description='$description', image_full='$image_full', image_thumbnail='$image_thumbnail', num_bathrooms='$num_bathrooms', num_bedrooms='$num_bedrooms', price='$price', property_type='$property_type', town='$town', type='$type' WHERE uuid = '$uuid'";

   } else {

     $query = "UPDATE properties SET uuid='$uuid', address='$address', country='$country', county='$county', description='$description', num_bathrooms='$num_bathrooms', num_bedrooms='$num_bedrooms', price='$price', property_type='$property_type', town='$town', type='$type' WHERE uuid = '$uuid'";

   }




if(mysqli_multi_query($connect, $query)) { }

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>
