<?php
error_reporting(E_ALL);

include_once("ItemClass.php");

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Quick Buy</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
      div {
        font-family: Verdana, Arial;
        font-size: 7pt;
      }
      .item {
        width: 100%;
        text-align: left;
      }
      .itemName {
        width: 500px;
        float: left;
      }
      .itemAmount {
        width: 100px;
        float: left;
      }
      .itemNumber {
        width: 200px;
        float: left;
      }      
      .buyNow {
        width: 200px;
        float: left;
      }
    </style>
  </head>
  <body>
    <div>
      <?php
        $itemData = new ItemClass();
        $items = $itemData->GetItems(); //array 
        foreach ($items as $itemNumber => $itemName) {
          /*
           $item is an array and the keys are:
            itemNumber
            itemName
            itemPrice
            itemInfo
            partName
            itemID
            flagBMI
            flagSex
            flagAge
            weight
            weightUnit
           */
          $item = $itemData->GetItemInfo($itemNumber);
          //$findme = "tramadol"; //used to narrow the output just to tramadol
          $findme = "";
          if (strlen($findme)>0) {
            $pos = stripos($item["itemName"], $findme);
            if ($pos!==false) {
      ?>
            <div class="item">
              <div class="itemNumber"><?php echo $item["itemNumber"]; ?></div>
              <div class="itemName"><?php echo $item["itemName"]; ?></div>
              <div class="itemAmount"><?php echo money_format('$%i', $item["itemPrice"]); ?></div>
              <div class="buyNow"><a href="order.php?itemNum=<?php echo $itemNumber; ?>"><img src="buynow.jpg" style="border: none" /></a></div>
            </div>
      <?php
           
            }
          }
          else {
      ?>
            <div class="item">
              <div class="itemNumber"><?php echo $item["itemNumber"]; ?></div>
              <div class="itemName"><?php echo $item["itemName"]; ?></div>
              <div class="itemAmount"><?php echo money_format('$%i', $item["itemPrice"]); ?></div>
              <div class="buyNow"><a href="order.php?itemNum=<?php echo $itemNumber; ?>"><img src="buynow.jpg" style="border: none" /></a></div>
            </div>          
      <?php
          }
        }
      ?>
    </div>
  </body>
</html>
