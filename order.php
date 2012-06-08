<?php
session_start();
error_reporting(0);
include_once('ItemClass.php');
include_once('OrderClass.php');

$itemNumber = $_REQUEST["itemNum"];
if (strlen($itemNumber)==0) {
  echo "Invalid Item";
  die();
}

$i = new ItemClass();
$item = $i->GetItemInfo($itemNumber); //item is an array with strings for the index
if (count($item)==0) {
  echo "Invalid Item";
  die();
}
else {
  $_SESSION["item"] = $item;
}

$order = new OrderClass();
$questions = $order->GetQuestions($itemNumber); //array of objects of type Question
$shippingMethods = $order->GetShippingMethods(); //array of objects of type ShippingMethod
$shippingRegions = $order->GetShippingRegions(); //int based array
$countries = $order->GetCountries();

if (count($questions)>0) {$_SESSION["questions"]= $questions;}
$_SESSION["shippingmethods"] = $shippingMethods;

$stateArray = array ("AK","AL","AR","AZ","CA","CO","CT","DC","DE","FL","GA","HI","IA","ID","IL","IN","KS","KY","LA","MA","MD","ME","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NY","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VA","VT","WA","WI","WV","WY");

if (count($_POST)>2) {
  //new order has been posted. 
  //Go to host and redirect after getting response
  $response = $order->PostOrder();
  if (is_object($response)) {
    header("Location: orderconfirmation.php");
    die();
  }
  else {
    echo "<h3>A system error has occurred. Please try again later.</h3>";
    die();
  }
}
//initialize some variables
$firstname=$lastname=$address1=$address2=$city=$state=$zip=$country=$cardnumber=$cvv=$expmonth=$expyear="";
$usrAgeLimit=$phone=$email=$billingaddres1=$billingaddress2=$billingcity=$billingstate=$billingzip=$billingcountry="";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Order Request Form</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="order.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="order.js"></script>
  </head>
  <body>
    <form name="paymentform" id="paymentform" onsubmit="return ValidateForm();" method="post" action="order.php?itemNum=<?php echo $itemNumber; ?>">    
      <input type="hidden" name="itemNum" value="<?php echo $itemNumber; ?>" />
    <table  width="750" border="0">
        <tr>
            <td colspan="2"><h2>Order Request Form</h2></td>
        </tr>
        <tr>
            <td >Information will be treated confidentially and will not be released to third parties. For additional information on how we protect your privacy, please review our <a href="privacy.php" target="_blank">privacy statement</a>.<br>
            <br>
            <span><b>All information requested must be completed.</b></span><br></td>
        </tr>
    </table>
    <table>
      <tr>
        <td class="section">Section 1:</td>
        <td class="heading">customer account information</td>
      </tr>
    </table>
    <table>
      <tr>
        <td class="field">First Name:</td>
        <td><input  type="text" name="firstname" id="firstname" /><span class="heading2">(Complete First Name - no initials)</span></td>
      </tr>
      <tr>
        <td class="field">Last Name:</td>
        <td><input  type="text" name="lastname" id="lastname" /></td>
      </tr>
      <tr>
        <td class="field">Email:</td>
        <td><input type="text" name="email" id="email" /><span class="heading2">(such as blah@aol.com)</span></td>
      </tr>      
    </table>
    <table>
      <tr>
        <td class="section">Section 2:</td>
        <td class="heading">shipping/contact information</td>
      </tr>
    </table>
    <table>
      <tr>
        <td class="field">Shipping Method:</td>
        <td>
          <select id="shippingmethod" name="shippingmethod" onchange="ShippingMethod();">
            <?php
              foreach ($shippingMethods as $i => $obj) {  
                if (strcmp($obj->getShipMethod(),"Priority")==0) {
                  $s = 'selected="selected"';
                  $defaultShippingMethod = $obj->getDescription();
                  $defaultShippingPrice = $obj->getPrice();
                } 
                else {$s = '';}
            ?>
                <option value="<?php echo $obj->getShipmethod(); ?>" <?php echo $s; ?>><?php echo $obj->getDescription(); ?></option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td class="field">Address Line 1:</td>
        <td><input  type="text" name="address1" id="address1" /></td>
      </tr>
      <tr>
        <td class="field">Address Line 2:</td>
        <td><input  type="text" name="address2" id="address2" /><span class="heading2">(apt #, etc)</span></td>
      </tr>   
      <tr>
        <td class="field">City:</td>
        <td><input  type="text" name="city" id="city" /></td>
      </tr>    
      <tr>
        <td class="field">State:</td>
        <td>
          <select name="state" id="state">
            <option value="">--Select--</option>
            <?php
              foreach ($shippingRegions as $i => $region) {
            ?>
                <option value="<?php echo $region;?>"><?php echo $region; ?></option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr>  
      <tr>
        <td class="field">Zip/Postal Code:</td>
        <td><input  type="text" name="zip" id="zip" /></td>
      </tr>    
      <tr>
        <td class="field">Country:</td>
        <td>
          <select name="country" id="country">
            <?php
              foreach ($countries as $abbr => $fullname) {
            ?>
              <option value="<?php echo $abbr; ?>" <?php if (strcmp($abbr,"US")==0) {echo 'selected="selected"'; }?>><?php echo $fullname; ?></option>
            <?php
              }
            ?>
          </select>          
        </td>
      </tr>      
      <tr>
        <td class="field">Phone Number:</td>
        <td><input type="text" name="phone" id="phone" /></td>
      </tr>      
    </table>
    <table>
      <tr>
        <td class="section">Section 3:</td>
        <td class="heading">credit card</td>
      </tr>  
    </table>
    <table>
      <tr>
        <td class="field">Card Type:</td>
        <td>
          <select name="cardtype">
            <?php
              $paymentType = $order->GetPaymentTypes();
              foreach ($paymentType as $type=>$description) {
            ?>
                <option value="<?php echo $type; ?>"><?php echo $description; ?></option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr> 
      <tr>
        <td class="field">Name on Credit Card:</td>
        <td><input  type="text" name="cardname" id="cardname" /></td>
      </tr> 
      <tr>
        <td class="field">Credit Card Number:</td>
        <td><input  type="text" name="cardnumber" id="cardnumber" /></td>
      </tr>
      <tr>
        <td class="field">CVV2:</td>
        <td><input type="text" name="cvv" id="cvv" /></td>
      </tr>  
      <tr>
        <td class="field">Expiration Date:</td>
        <td>
          <select name="expmonth">
            <option value="-1">-Month-</option>
            <?php
            for ($i = 1; $i <= 12; $i++ ) {
               echo "<option value=\"" . str_pad($i,2,"0",STR_PAD_LEFT) . "\"";
               echo ">" . str_pad($i,2,"0",STR_PAD_LEFT) . "</option>" . "\n";
            }
            ?>
          </select>
          <select name="expyear">
            <option value="-1">-Year-</option>
              <?php
              for ($i = date("Y"); $i <= (date("Y") + 10); $i++) {
                 echo "<option value=\"" . $i . "\"";
                 echo ">" . $i . "</option>" . "\n";
              }
              ?>
          </select>         
        </td>
      </tr>   
      <tr>
        <td class="field">Billing Address:</td>
        <td><input type="checkbox" name="sameaddress" id="sameaddress" onclick="CopyShipping();">Same as shipping address</td>
      </tr>
      <tr>
        <td class="field">Billing Address 1:</td>
        <td><input type="text" name="billingaddress1" id="billingaddress1" /></td>
      </tr>
      <tr>
        <td class="field">Billing Address 2:</td>
        <td><input type="text" name="billingaddress2" id="billingaddress2" /></td>
      </tr>
      <tr>
        <td class="field">Billing City:</td>
        <td><input type="text" name="billingcity" id="billingcity" /></td>
      </tr>    
      <tr>
        <td class="field">Billing State:</td>
        <td>          
          <select name="billingstate" id="billingstate">
            <option value="">--Select--</option>
            <?php
            foreach ( $stateArray as $stateArr ) {
               echo "<option value=\"" . $stateArr . "\"";
               if ( $billingstate == $stateArr ) { echo " selected"; }
               echo ">" . $stateArr . "</option>" . "\n";
            }
             ?>            
          </select>
        </td>
      </tr>
      <tr>
        <td class="field">Billing Zip:</td>
        <td><input type="text" name="billingzip" id="billingzip" /></td>
      </tr>
      <tr>
        <td class="field">Billing Country:</td>
        <td>          
          <select name="billingcountry" id="billingcountry">
            <?php
              foreach ($countries as $abbr => $fullname) {
            ?>
              <option value="<?php echo $abbr; ?>" <?php if (strcmp($abbr,"US")==0) {echo 'selected="selected"'; }?>><?php echo $fullname; ?></option>
            <?php
              }
            ?>
          </select>
        </td>
      </tr>  
      <tr>
        <td class="field">Billing Phone:</td>
        <td><input type="text" name="billingphone" id="billingphone" /></td>
      </tr>      
    </table>
    <table>
      <tr>
        <td class="section">Section 4:</td>
        <td class="heading">your item selection</td>
      </tr>
    </table> 
    <table>
      <tr>
        <td class="field"><?php echo $item["itemName"]; ?></td>
        <td style="font-weight: bold; padding-left: 10px" align="right"><?php echo money_format('$%i', $item["itemPrice"]); ?></td>
      </tr>
      <tr>
        <td class="field">Shipping</td>
        <td style="font-weight: bold; padding-left: 10px" align="right"><div name="defaultShippingPrice" id="defaultShippingPrice"><?php echo money_format('$%i', $defaultShippingPrice); ?></div></td>
      </tr>
      <tr>
        <td class="field">Total Amount</td>
        <td style="font-weight: bold; padding-left: 10px" align="right"><div name="totalAmount" id="totalAmount"><?php echo money_format('$%i', $defaultShippingPrice+$item["itemPrice"]); ?></div></td>
      </tr>      
    </table>  
    <table>
      <tr>
        <td class="section">Section 5:</td>
        <td class="heading">personal questionnaire</td>
      </tr>
    </table>
    <table>
      <tr>
        <td class="field">Date of Birth:</td>
        <td>
         <select id="DOBMonth" name="DOBMonth">
          <option value="">Month</option>
          <?php
          $dobMonth = $dobDay = $dobYear = 0;
          for ($i = 1; $i <= 12; $i++) {
             echo "<option value=\"" . str_pad($i,2,"0",STR_PAD_LEFT) . "\"";
             if ( $dobMonth == $i ) { echo " selected"; }
             echo ">" . $i . "</option>" . "\n";
          }
          ?>
         </select> /
         <select id="DOBDay" name="DOBDay">
            <option value="">Day</option>
            <?php
            for ($i = 1; $i <= 31; $i++) {
               echo "<option value=\"" . str_pad($i,2,"0",STR_PAD_LEFT) . "\"";
               if ( $dobDay == $i ) { echo " selected"; }
               echo ">" . $i . "</option>" . "\n";
            }
            ?>
         </select> /
         <select id="DOBYear" name="DOBYear">
          <option value="">Year</option>
              <?php
              $usrAgeLimit = 18;
              $yearList = date("Y") - $usrAgeLimit;
              for ($i = $yearList; $i >= 1902; $i--) {
                 echo "<option value=\"" . $i . "\"";
                 if ( $dobYear == $i ) { echo " selected"; }
                 echo ">" . $i . "</option>" . "\n";
              }
              ?>
            </select>          
        </td>
      </tr>  
      <tr>
        <td class="field">Gender:</td>
        <td>
          <select name="gender">
            <option value="">-Select-</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </td>
      </tr> 
      <tr>
        <td class="field">Please Select Your Height:</td>
        <td>
            <select id="height" name="height" onBlur="CheckBMI();">
               <option value="">Height</option>
               <option value="">ft'-in&quot;</option>
                <?php
                $height = 0;
                for ($i = 48; $i <= 95; $i++) {
                   echo "<option value=\"" . $i . "\"";
                   if ( $height == $i ) { echo " selected"; }
                   echo ">" . floor($i / 12) . "' " . $i % 12 . "\"". "</option>" . "\n";
                }
                ?>
            </select>          
        </td>
      </tr>   
      <tr>
        <td class="field">Please Enter Your Weight:</td>
        <td><input type="text" id="weight" name="weight" size="3" maxlength="4" onBlur="CheckBMI();"> Lbs.</td>
      </tr>
      <tr>
          <td class="field">
             Calculated Body Mass Index (BMI):
          </td>
          <td>
             <input type="text" id="dBMI" name="dBMI" size="5" disabled="TRUE">
             <input type="hidden" id="BMI" name="BMI">
          </td>
      </tr>      
    </table>
    <?php

      if (count($questions)>0) {
        echo '<table>'."\n";
        $i = 1;
        foreach ($questions as $k=>$q) {
          $qID   = $q->getQID();
          $qType = $q->getQType();
          $qText = $q->getQText();
          if ($qType != 5) {
    ?>
            <tr>
              <td valign="top" class="questionnumber" align="right"><?php echo $i;?>.</td>
              <td valign="top" class="question" width="700" ><?php echo $qText;?><input type="hidden" id="qtext_<?php echo $qID; ?>" name="qtext_<?php echo $qID; ?>" value=""></td>
              <td>&nbsp;</td>
            </tr> 
            
    <?php
          } 
          $q1 = $q2 = $msg2 = "";
          switch( $qType ) {
          case 1 :
            $value1 = "Yes";
            $value2 = "No";
            $msg2 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; if No, please explain your reason for requesting this medication.";
            $textbox = $value2;
            break;
          case 2 :
            $value1 = "I Agree";
            $value2 = "I Disagree";
            $textbox = $value2;
            break;
          case 3 :
            $value1 = false;
            $textbox = true;
            break;
          case 4 :
            $value1 = "None";
            $value2 = "I will specify";
            $textbox = $value2;
            break;
          case 5 :
            $value1 = false;
            $textbox = false;
          }

          $checked1 = ($q1 == $value1) ? "checked" : "";
          $checked2 = ($q1 == $value2) ? "checked" : "";
          $disabled = (($textbox === true) || ($q1 == $textbox)) ? "" : "disabled";

          if ( $value1 )
          {
          ?>
             <tr>
               <td width="20">&nbsp;</td>
               <td valign="top" align="left">
                  <input type="radio" id="q_<?php echo $qID; ?>_1" name="q_<?php echo $qID; ?>" value="<?php echo $value1; ?>" onClick="qsSet(this);" <?php echo $checked1; ?>><?php echo $value1; ?>&nbsp;<input type="radio" id="q_<?php echo $qID; ?>_2" name="q_<?php echo $qID; ?>" value="<?php echo $value2; ?>" onClick="qsSet(this);" <?php echo $checked2; ?>><?php echo $value2." ".$msg2; ?></td>
               <td>&nbsp;</td>
             </tr>
          <?php
          }
          else
          {
            echo "<input type=\"hidden\" name=\"q_$qID\" value=\"1\">";
          }
          if ( $textbox )
          {
          ?>
             <tr>
               <td></td>
               <td valign="top" align="left">
                 <textarea id="qs_<?php echo $qID; ?>" name="qs_<?php echo $qID; ?>" rows="2" cols="45" style="display: <?php if ($textbox==1) {echo "inline";} else {echo "none";}?>"><?php echo $q2;?></textarea>
               </td>
               <td>&nbsp;</td>
             </tr>
          <?php
          }          
          $i++;
        }
        echo '</table>'."\n";
      }  
          
    ?>
    <table>
      <tr>
        <td class="section" style="width: 90px;">&nbsp</td>
        <td align="left"><input type="submit" value="Submit" class="submit" /></td>
      </tr>
    </table>             
    </form>
  </body>

      <script type="text/javascript">
        var shippingMethods=new Array();
  <?php
    foreach ($shippingMethods as $i => $obj) {
  ?>
        shippingMethods[<?php echo $i; ?>]=<?php echo $obj->getPrice(); ?>;
  <?php
    }
  ?>
        var itemPrice =<?php echo $item["itemPrice"]; ?> 
      </script>

</html>
