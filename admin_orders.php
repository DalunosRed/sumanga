<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_status = $conn->prepare ("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$update_payment,$order_update_id]);
   $success_msg[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   $success_msg[] = 'Order has been deleted!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?= include 'admin_header.php'; ?>

<section class="orders">

   <div class="product-display">
      <table class="product-display-table">
         <tr>
            <th>User id</th>
            <th>Placed on</th>
            <th>Name</th>
            <th>Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>Total product</th>
            <th>Total price</th>
            <th>Paument method</th>
            <th>status</th>
            <th>action</th>
            
         </tr>
<?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
         </thead>
         <tbody>
         <tr>
         <td><?= $fetch_orders['user_id']; ?></td>
         <td><?= $fetch_orders['placed_on']; ?></td>
         <td><?= $fetch_orders['name']; ?></td>
         <td><?= $fetch_orders['number']; ?></td>
         <td><?= $fetch_orders['email']; ?></td>
         <td><?= $fetch_orders['address']; ?></td>
         <td><?= $fetch_orders['total_products']; ?></td>
         <td><?= $fetch_orders['total_price']; ?></td>
         <td><?= $fetch_orders['method']; ?></td>
         <form action="" method="post">
         <td><input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
         </td>
         <td>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
         </td>
         </form>
         </tr>
      <?php
         }
      }else{
         '<p class="empty">no orders placed yet!</p>';
      }
      ?>
         </tbody>
      </table>
         </div>

      </div>


</section>










<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?= include 'alers.php' ?>

</body>
</html>