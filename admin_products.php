<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $price = $_POST['price'];
   $image = $name.'_img1.' . pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $category = $_POST['category'];

   $allowed = array('png', 'jpg', 'jpeg', 'webp', 'JPG');
   $ext = pathinfo($image, PATHINFO_EXTENSION);

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($image != $name.'_img1.'){
      if (!in_array($ext, $allowed)) {
         $warning_msg[] = 'Only png, jpg, jpeg and webp are allowed!';
      }else{
         if(($select_products) > 0){
         $warning_msg[] = 'product name already added';
      }else{
            if( $_FILES['image']['size'] > 2000000){
               $error_msg[] = 'image size is too large';
            }else{
      $insert_products = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
      $insert_products->execute([$name, $category, $price, $image]);               
      move_uploaded_file($image_tmp_name, $image_folder);
               $success_msg[] = 'product added successfully!';
            }}

         }
   }
}


if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);

   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   header('location:admin_products.php');
}

if(isset($_POST['update'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_name = filter_var($update_name, FILTER_SANITIZE_STRING);
   $update_cat = $_POST['update_cat'];
   $update_cat = filter_var($update_cat, FILTER_SANITIZE_STRING);
   $update_price = $_POST['update_price'];
   $update_price = filter_var($update_price, FILTER_SANITIZE_STRING);


   $update_product = $conn->prepare ("UPDATE `products` SET category = ?, name = ?, price = ? WHERE id = ?");
   $update_product->execute([$update_cat, $update_name, $update_price, $update_p_id]);
   
   $success_msg[] = ' Product has been updated!';



   $old_image = $_POST['update_old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $update_p_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
        
      }
   }

   
  


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Add product
</button>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
   <h1 class="title">shop products</h1>

<form action="" method="post" enctype="multipart/form-data">
   <h3>add product</h3>
   <input type="text" name="name" class="box" placeholder="enter product name" required>
   <input type="text" name="category" class="box" placeholder="enter category name" required>
   <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
   <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
   <input type="submit" value="add product" name="add_product" class="btn"> 
   
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">
   <div class="product-display">
      <table class="product-display-table">
         
         <tr>
            <th>product image</th>
            <th>product name</th>
            <th>product category</th>
            <th>product price</th>
            <th>action</th>
         </tr>
         
         <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            ?>
      
      <tr>
         <td><img src="uploaded_img/<?= $fetch_products['image']; ?>" height="100" alt=""></td>
         <td><?php echo $fetch_products['name']; ?></td>
         <td><?php echo $fetch_products['category']; ?></td>
         <td>₱<?php echo $fetch_products['price']; ?></td>
         <td>
           <a href="admin_updateproduct.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
           <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
         </td>
      </tr>
     
<?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </table>
</div>


</section>





<section class="add-products">


<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
   <h1 class="title">shop products</h1>



   <form action="" method="post" enctype="multipart/form-data">
      
      <input type="hidden" name="update_p_id" value="<?= $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?= $fetch_update['image']; ?>">
      <img src="uploaded_img/<?= $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name"  class="box" required placeholder="enter product name">
      <input type="text" name="update_cat"  class="box" required placeholder="enter product category">
      <input type="number" name="update_price"  min="0" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update" class="btn">

   </form>
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

   


</section>





<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'alers.php' ?>

</body>
</html>
