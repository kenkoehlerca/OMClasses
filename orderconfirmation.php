<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Test Confirmation Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    <div>
      <h3>Thank you for your order</h3>
      Order ID: <?php echo $_SESSION["response"]->orderID; ?>
      Customer ID: <?php echo $_SESSION["response"]->customerID; ?>
      
    </div>
  </body>
</html>
