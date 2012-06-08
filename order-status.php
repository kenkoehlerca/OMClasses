<?php
session_start();

include_once("OrderClass.php");
if (count($_POST)>1) {
  $order = new OrderClass();
  $email = trim($_POST["email"]);
  $orderID = trim($_POST["orderid"]);
  
  $status = $order->OrderStatus($orderID, $email); //returns an array
  //keys are Status, Date, Item
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    <div>
      <form action="order-status.php" method="post">
        <table>
          <tr>
            <td>Order ID:</td>
            <td><input type="text" name="orderid" id="orderid" /></td>
          </tr>
          <tr>
            <td>Email:</td>
            <td><input type="text" name="email" id="email" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="submit" name="submit" /></td>
          </tr>
        </table>
      </form>
      <?php
        if (isset($status)) {
   
          foreach ($status as $key => $value) {
            echo "$key - $value<br />";
          }
      
        }
      ?>
    </div>
  </body>
</html>