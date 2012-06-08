<?php

session_start();
include_once("ItemClass.php");

$itemObj = new ItemClass();
$itemListName = $itemObj->GetItemListName(); //array key is item number value is item name
$itemCategories = $itemObj->GetItemCategories();

?>
<!DOCTYPE html>
<html>
  <head>
    <title>test site</title>
    <link rel="stylesheet" type="text/css" href="index.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script> 
    <script type="text/javascript" src="index.js"></script>
  </head>
  <body>
    <div id="container">
      <div id="topBanner">
        Your Website Banner
      </div>
      <div id="left">
        Left
      </div>
      <div id="right">
        Quick Buy:<br />
        <form action="order.php" method="post" id="form1">
          <select name="itemNum" id="itemNum">
            <option value="-1">Please Select</option>
            <?php
              foreach ($itemListName as $key => $value) {
            ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php
              }
            ?>
          </select>
        </form>
        <br />
        <br />
        Item Categories:<br />
        <?php
          foreach ($itemCategories as $i => $category) {

            echo $category."<br />";
        
          }
        ?>
      </div>
    </div>
  </body>
</html>
