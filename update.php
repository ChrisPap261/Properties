
<?php include('config.php');
$query = '';
$table_data = '';

?>

<?php $filename = "https://trialapi.craig.mtcdevserver.com/api/properties?api_key=2S7rhsaq9X1cnfkMCPHX64YsWYyfe1he&page%5Bnumber%5D=".$_GET['page'];  ?>
<?php //header('Location: ?page='.$i); ?><br>
<?php
/*---------------Read the JSON file & Convert JSON String into PHP Array---------------*/
$data = file_get_contents($filename);
$array = json_decode($data, true);

for ($i=0; $i <= $array['to']-$array['from']; $i++) {

  echo $uuid = addslashes($array['data'][$i]['uuid']);
  $address = addslashes($array['data'][$i]['address']);
  $country = addslashes($array['data'][$i]['country']);
  $county = addslashes($array['data'][$i]['county']);
  $description = addslashes($array['data'][$i]['description']);
  $image_full = addslashes($array['data'][$i]['image_full']);
  $image_thumbnail = addslashes($array['data'][$i]['image_thumbnail']);
  $latitude = addslashes($array['data'][$i]['latitude']);
  $longitude = addslashes($array['data'][$i]['longitude']);
  $num_bathrooms = addslashes($array['data'][$i]['num_bathrooms']);
  $num_bedrooms = addslashes($array['data'][$i]['num_bedrooms']);
  $price = addslashes($array['data'][$i]['price']);
  $property_type = addslashes($array['data'][$i]['property_type']['title']);
  $town = addslashes($array['data'][$i]['town']);
  $type = addslashes($array['data'][$i]['type']);

  /*---------------FETCH ALL PROPERTIES OF THIS PAGE---------------*/

  /*---------------INSERT PROPERTY IF NOT EXIST---------------*/
  $query = "INSERT INTO properties(uuid, address, country, county, description, image_full, image_thumbnail, latitude, longitude, num_bathrooms, num_bedrooms, price, property_type, town, type) VALUES ('$uuid', '$address', '$country', '$county', '$description', '$image_full', '$image_thumbnail', '$latitude', '$longitude', '$num_bathrooms', '$num_bedrooms', '$price', '$property_type', '$town', '$type')";

  if(mysqli_multi_query($connect, $query)) {} //Run Mutliple Insert Query

  }

  $tmp=$_GET['page']+1;
  ?>


  <script>
  var to = <?php echo $array['to'];?>;
  var total = <?php echo $array['total']?>;

    if (to <= total) {
      setTimeout(function(){ window.location.href = "update.php?page=<?php echo $tmp; ?>"; }, 4000);
     console.log(total-to);
    }
  </script>
