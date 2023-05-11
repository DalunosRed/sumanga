<?php

if(isset($success_msg)){
   foreach($success_msg as $success_msg){
      echo '<script>swal("'.$success_msg.'", "", "success");</script>';
   }
}

if(isset($success_login)){
   foreach($success_login as $success_login){
      echo '<script>swal("'.$success_login.'", "", "success").then(function(){window.location = "index.php"});</script>';
   }
}

if(isset($success_reg)){
   foreach($success_reg as $success_reg){
      echo '<script>swal("'.$success_reg.'", "", "success").then(function(){window.location = "login.php"});</script>';
   }
}

if(isset($success_admin)){
   foreach($success_admin as $success_admin){
      echo '<script>swal("'.$success_admin.'", "", "success").then(function(){window.location = "admin_page.php"});</script>';
   }
}

if(isset($warning_msg)){
   foreach($warning_msg as $warning_msg){
      echo '<script>swal("'.$warning_msg.'", "", "warning");</script>';
   }
}

if(isset($info_msg)){
   foreach($info_msg as $info_msg){
      echo '<script>swal("'.$info_msg.'", "", "info");</script>';
   }
}

if(isset($error_msg)){
   foreach($error_msg as $error_msg){
      echo '<script>swal("'.$error_msg.'", "", "error");</script>';
   }
}

?>
