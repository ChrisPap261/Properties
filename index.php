<?php include('config.php'); ?>

<html>
  <head>
     <title>Properties CRUD</title>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

     <style media="screen">
       body {  background-color: #f8f8f8;  }

       div.scrollable {
       height: 85vh;
       overflow: auto;
       }

       .form-control { margin: 10px 0px; }

       .page-link { color: #505050; }

       .btn-sm {
       border-radius: 0;
       padding: 2px 5px;
       }

       .btn {
       border-radius: 0;
       }

       .btn-light {  background-color: #e2e6ea;  border-color: #e2e6ea; }

       .modal-content, .form-control {border-radius: 0px;}
     </style>
</head>
<body>

    <?php
    // Report all errors except E_NOTICE
    error_reporting(E_ALL ^ E_NOTICE);

    /*---------------FETCH ALL PROPERTIES OF THIS PAGE---------------*/
    if (isset($_GET['name']) || isset($_GET['bedrooms']) || isset($_GET['ptype']) || isset($_GET['type']) || isset($_GET['price'])) {

      $name = addslashes($_GET['name']);
      $bedrooms = addslashes($_GET['bedrooms']);
      $price = addslashes($_GET['price']);
      $ptype = addslashes($_GET['ptype']);
      $type = addslashes($_GET['type']);

      $sql = "SELECT * FROM properties WHERE county LIKE '%$name%' AND num_bedrooms LIKE '%$bedrooms%' AND price LIKE '%$price%' AND property_type LIKE '%$ptype%'AND type LIKE '%$type%'";
    } else {
      if (isset($_GET['page'])) {

        if ($_GET['page'] <= 0) {
          header('Location: ?page=1');
        }
        $page = $_GET['page'];
        $page_tmp = ($_GET['page']-1) * 30;
        $sql = "SELECT * FROM properties LIMIT 30 OFFSET $page_tmp";
        $sql1 = "";
      } else {
        $page = 1;
        $sql = "SELECT * FROM properties LIMIT 30";
      }

    }

    $result = $connect->query($sql);

    ?>

    <div class="container-fluid">
    	<div class="row mt-3">

        <div class="col-md-4">

          <h3>Filters</h3>
          <form method="get" action="<?=$_SERVER['PHP_SELF'];?>">
            <input class="form-control" type="text" name="name" placeholder="Search by Name.." autocomplete="off" value="<?php if(isset($_GET['name'])){ echo $_GET['name']; } ?>">
            <input class="form-control" type="number" name="bedrooms" placeholder="Search by Bedrooms.." autocomplete="off" value="<?php if(isset($_GET['bedrooms'])){ echo $_GET['bedrooms']; } ?>">
            <input class="form-control" type="text" name="price" placeholder="Search by Price.." autocomplete="off" value="<?php if(isset($_GET['price'])){ echo $_GET['price']; } ?>">
            <input class="form-control" type="text" name="ptype" placeholder="Search by Property Type.." autocomplete="off" value="<?php if(isset($_GET['ptype'])){ echo $_GET['ptype']; } ?>">
            <input class="form-control" type="text" name="type" placeholder="Search by Sale/Rent.." autocomplete="off" value="<?php if(isset($_GET['type'])){ echo $_GET['type']; } ?>">
            <input class="btn btn-dark" type="submit" name=""/> <a class="btn btn-light" autocomplete="off" href="index.php">Clear Filters</a>
          </form>

        </div>
    		<div class="col-md-8">


<h3>Properties</h3>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>">Previous Page</a></li>
              <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>">Next Page</a></li>
              <li style="margin-left: auto;"><a class="btn btn-light" data-toggle="modal" data-target="#staticBackdrop">Add New</a></li>
            </ul>
          </nav>

          <div class="scrollable">
        <?php if ($result->num_rows > 0) { ?>

              <!---TABLE---->
              <table class="table table-borderless table-striped" id="myTable">

                <tr class="header">
                  <!--<th scope="col">#</th>-->
                  <!--<th scope="col">UUID</th>-->
                  <th scope="col">Thumbnail</th>
                  <th scope="col">Name</th>
                  <th scope="col">Number of Bedrooms</th>
                  <th scope="col">Price</th>
                  <th scope="col">Property Type</th>
                  <th scope="col">For Sale / For Rent</th>
                  <th scope="col"></th>
                </tr>

              <?php $i = 0 ?>
              <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                  <!--<th scope="row"><?php echo $i++; ?></th>-->
                  <!--<td><?php echo $row['uuid']; ?></td>-->
                  <td><img src="<?php echo $row['image_thumbnail']; ?>"/></td>
                  <td><?php echo $row['county']; ?></td>
                  <td><?php echo $row['num_bedrooms']; ?></td>
                  <td><?php echo '£'.$row['price']; ?></td>
                  <td><?php echo $row['property_type']; ?></td>
                  <td><?php echo $row['type']; ?></td>
                  <td style="display: -webkit-inline-box;"><button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#EditModal<?php echo $row['uuid']; ?>">Edit</button>
                  <form method="post" action="delete.php"><input type="hidden" name="uuid" value="<?php echo $row['uuid']; ?>"/><input type="submit" class="btn btn-sm btn-light" name="Delete" onclick="return confirm('Are you sure you want to delete this item?')" value="Delete"/></form></td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="EditModal<?php echo $row['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $row['county']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="edit.php">


                        <div class="row mt-3">
                          <div class="col-md-6">

                            <label>UUID</label><input class="form-control" type="text" name="uuid" placeholder="" autocomplete="off" value="<?php if(isset($row['uuid'])){ echo $row['uuid']; } ?>">
                            <label>country</label><input class="form-control" type="text" name="country" placeholder="" autocomplete="off" value="<?php if(isset($row['country'])){ echo $row['country']; } ?>">
                            <label>county</label><input class="form-control" type="text" name="county" placeholder="" autocomplete="off" value="<?php if(isset($row['county'])){ echo $row['county']; } ?>">
                            <label>description</label><input class="form-control" type="text" name="description" placeholder="" autocomplete="off" value="<?php if(isset($row['description'])){ echo $row['description']; } ?>">
                            <label>address</label><input class="form-control" type="text" name="address" placeholder="" autocomplete="off" value="<?php if(isset($row['address'])){ echo $row['address']; } ?>">
                            <label>image_full</label><input class="form-control" type="file" name="image" placeholder="" autocomplete="off" value="">
                          </div>
                          <div class="col-md-6">
                            <label>town</label><input class="form-control" type="text" name="town" placeholder="" autocomplete="off" value="<?php if(isset($row['town'])){ echo $row['town']; } ?>">

                            <label>price</label><input class="form-control" type="text" name="price" placeholder="" autocomplete="off" value="<?php if(isset($row['price'])){ echo $row['price']; } ?>">

                            <label>num_bathrooms</label><select class="custom-select mt-2 mb-3" id="num_bathrooms" name="num_bathrooms">
                              <option selected>Choose...</option>
                              <option value="1" <?php if($row['num_bathrooms'] == '1'){ echo "selected"; } ?>>1</option>
                              <option value="2" <?php if($row['num_bathrooms'] == '2'){ echo "selected"; } ?>>2</option>
                              <option value="3" <?php if($row['num_bathrooms'] == '3'){ echo "selected"; } ?>>3</option>
                              <option value="4" <?php if($row['num_bathrooms'] == '4'){ echo "selected"; } ?>>4</option>
                            </select>

                            <label>num_bedrooms</label><select class="custom-select mt-2 mb-3" id="num_bedrooms" name="num_bedrooms">
                              <option selected>Choose...</option>
                              <option value="1" <?php if($row['num_bedrooms'] == '1'){ echo "selected"; } ?>>1</option>
                              <option value="2" <?php if($row['num_bedrooms'] == '2'){ echo "selected"; } ?>>2</option>
                              <option value="3" <?php if($row['num_bedrooms'] == '3'){ echo "selected"; } ?>>3</option>
                              <option value="4" <?php if($row['num_bedrooms'] == '4'){ echo "selected"; } ?>>4</option>
                              <option value="5" <?php if($row['num_bedrooms'] == '5'){ echo "selected"; } ?>>5</option>
                              <option value="6" <?php if($row['num_bedrooms'] == '6'){ echo "selected"; } ?>>6</option>
                              <option value="7" <?php if($row['num_bedrooms'] == '7'){ echo "selected"; } ?>>7</option>
                              <option value="8" <?php if($row['num_bedrooms'] == '8'){ echo "selected"; } ?>>8</option>
                              <option value="9" <?php if($row['num_bedrooms'] == '9'){ echo "selected"; } ?>>9</option>
                              <option value="10" <?php if($row['num_bedrooms'] == '10'){ echo "selected"; } ?>>10</option>
                              <option value="11" <?php if($row['num_bedrooms'] == '11'){ echo "selected"; } ?>>11</option>
                              <option value="12" <?php if($row['num_bedrooms'] == '12'){ echo "selected"; } ?>>12</option>
                            </select>

                            <label class="" for="inlineFormCustomSelect">property_type</label>
                            <select class="custom-select mt-2 mb-3" id="property_type" name="property_type">
                              <option selected>Choose...</option>
                              <option value="Terraced" <?php if($row['property_type'] == 'Terraced'){ echo "selected"; } ?>>Terraced</option>
                              <option value="Flat" <?php if($row['property_type'] == 'Flat'){ echo "selected"; } ?>>Flat</option>
                              <option value="Semi-detached" <?php if($row['property_type'] == 'Semi-detached'){ echo "selected"; } ?>>Semi-detached</option>
                              <option value="Cottage" <?php if($row['property_type'] == 'Cottage'){ echo "selected"; } ?>>Cottage</option>
                              <option value="Bungalow" <?php if($row['property_type'] == 'Bungalow'){ echo "selected"; } ?>>Bungalow</option>
                              <option value="End of Terrace" <?php if($row['property_type'] == 'End of Terrace'){ echo "selected"; } ?>>End of Terrace</option>
                              <option value="Detatched" <?php if($row['property_type'] == 'Detatched'){ echo "selected"; } ?>>Detatched</option>
                            </select>

                            <label>type</label><div class="form-check">
                              <input class="form-check-input" type="radio" name="type" id="exampleRadios1" value="sale" <?php if($row['type'] == 'sale'){ echo "checked"; } ?>>
                              <label class="form-check-label" for="exampleRadios2">Sale</label><br>
                              <input class="form-check-input" type="radio" name="type" id="exampleRadios2" value="rent" <?php if($row['type'] == 'rent'){ echo "checked"; } ?>>
                              <label class="form-check-label" for="exampleRadios2">Rent</label>
                          </div>
                          </div>
                        </div>

                      </div>
                      <div class="modal-footer">
                          <input class="btn btn-dark" type="submit" name="submit"/>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                      </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>

            </table>


        <?php } else { ?>

          <div class="jumbotron">
            <h1 class="display-4">No results found :(</h1>
          </div>

        <?php } ?>
    		</div>

        </div>
    	</div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Property</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="add.php" enctype="multipart/form-data">


            <div class="row mt-3">
              <div class="col-md-6">

                <label>UUID</label><input class="form-control" type="text" name="uuid" placeholder="" autocomplete="off">
                <label>country</label><input class="form-control" type="text" name="country" placeholder="" autocomplete="off">
                <label>county</label><input class="form-control" type="text" name="county" placeholder="" autocomplete="off">
                <label>description</label><input class="form-control" type="text" name="description" placeholder="" autocomplete="off">
                <label>address</label><input class="form-control" type="text" name="address" placeholder="" autocomplete="off">
                <label>image_full</label><input class="form-control" type="file" name="image" placeholder="" autocomplete="off">
              </div>
              <div class="col-md-6">
                <label>town</label><input class="form-control" type="text" name="town" placeholder="" autocomplete="off">
                <label>price</label><input class="form-control" type="text" name="price" placeholder="" autocomplete="off">

                <label>num_bathrooms</label><select class="custom-select mt-2 mb-3" id="num_bathrooms" name="num_bathrooms">
                  <option selected>Choose...</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>

                <label>num_bedrooms</label><select class="custom-select mt-2 mb-3" id="num_bedrooms" name="num_bedrooms">
                  <option selected>Choose...</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>

                <label class="" for="inlineFormCustomSelect">property_type</label>
                <select class="custom-select mt-2 mb-3" id="property_type" name="property_type">
                  <option selected>Choose...</option>
                  <option value="Terraced">Terraced</option>
                  <option value="Flat">Flat</option>
                  <option value="Semi-detached">Semi-detached</option>
                  <option value="Cottage">Cottage</option>
                  <option value="Bungalow">Bungalow</option>
                  <option value="End of Terrace">End of Terrace</option>
                  <option value="Detatched">Detatched</option>
                </select>

                <label>type</label><div class="form-check">
                  <input class="form-check-input" type="radio" name="type" id="exampleRadios1" value="sale">
                  <label class="form-check-label" for="exampleRadios2">Sale</label><br>
                  <input class="form-check-input" type="radio" name="type" id="exampleRadios2" value="rent">
                  <label class="form-check-label" for="exampleRadios2">Rent</label>
              </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <input class="btn btn-dark" type="submit" name="submit"/>
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </form>
          </div>
        </div>
      </div>
    </div>


      </body>
 </html>
