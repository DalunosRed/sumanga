<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){


   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];

   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

   if(($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
   
      $insert_products = $conn->prepare("INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES (?,?,?,?,?)");
      $insert_products->execute([$user_id, $product_name, $product_price, $product_quantity, $product_image]);  
      $success_add[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>



<section class="products">

<div class="product-display">
<table class="product-display-table">
         
         <tr>
            <th >product image</th>
            <th>product name</th>
            <th>product category</th>
            <th>product price</th>
            <th>quantity</th>
            <th>action</th>
         </tr>
         
         <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            ?>
      
      <tr>
      <form action="" method="post">
         
         <td><img class="image" src="uploaded_img/<?= $fetch_products['image']; ?>" height="100" alt=""></td>
         <td><?= $fetch_products['name']; ?></td>
         <td><?= $fetch_products['category']; ?></td>
         <td>₱<?= $fetch_products['price']; ?></td>
         <td><input type="number" style="text-align:center;" min="1" name="product_quantity" value="1"  class="qty"></td>
         <td>
         <input type="hidden" name="product_name" value="<?= $fetch_products['name']; ?>">
          <input type="hidden" name="product_price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?= $fetch_products['image']; ?>">
         
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
        
         </td>
         </form>    
      </tr>
<?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </table>


   <!-- <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?= $fetch_products['image']; ?>" height="100" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="name"><?= $fetch_products['category']; ?></div>
      <div class="price">₱<?= $fetch_products['price']; ?></div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?= $fetch_products['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div> -->

   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'alers.php' ?>

</body>
</html>