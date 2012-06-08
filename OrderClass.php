<?php

  include_once("BaseClass.php");
  
  class Question {
    private $qID;
    private $qType;
    private $qText;
    private $qXMLType;
    private $qXMLName;
    public function getQID() {
      return $this->qID;
    }

    public function setQID($qID) {
      $this->qID = $qID;
    }

    public function getQType() {
      return $this->qType;
    }

    public function setQType($qType) {
      $this->qType = $qType;
    }

    public function getQText() {
      return $this->qText;
    }

    public function setQText($qText) {
      $this->qText = $qText;
    }

    public function getQXMLType() {
      return $this->qXMLType;
    }

    public function setQXMLType($qXMLType) {
      $this->qXMLType = $qXMLType;
    }

    public function getQXMLName() {
      return $this->qXMLName;
    }

    public function setQXMLName($qXMLName) {
      $this->qXMLName = $qXMLName;
    }
  }
  
  class ShippingMethod {
    private $shipmethod;
    private $description;
    private $price;
    public function getShipmethod() {
      return $this->shipmethod;
    }

    public function setShipmethod($shipmethod) {
      $this->shipmethod = $shipmethod;
    }

    public function getDescription() {
      return $this->description;
    }

    public function setDescription($description) {
      $this->description = $description;
    }

    public function getPrice() {
      return $this->price;
    }

    public function setPrice($price) {
      $this->price = $price;
    }


  }
  class OrderClass extends BaseClass {
    
    public function __construct() {
      parent::__construct();
    }
    public function GetQuestions($itemNumber) {
      
      if (strlen($itemNumber)==0) {return null;}
      
      //returns an an array of questions
      //key is an integer
      //value is an object of type Question 
      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query','questions');
      $root->setAttribute('itemNum',trim($itemNumber));
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_Questions;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $Questions = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $question = new Question();
          $question->setQID($node->getAttribute('qID'));
          $question->setQType($node->getAttribute('qType'));
          $question->setQXMLType($node->getAttribute('qXMLType'));
          $question->setQXMLName($node->getAttribute('qXMLName'));
          $question->setQText($node->getAttribute('qText'));
          $Questions[]= $question;
        }    
      }
      return $Questions;
    }
    
    public function GetPaymentTypes() {
      
      //returns an array/list of cards with the ccType as the key and ccDescription as the value

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query','creditcards');
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_CreditCards;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('shipping');
      
      $Cards = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $Cards[$node->getAttribute('ccType')] = $node->getAttribute('ccDescription');          
        }    
      }
      return $Cards;    
    }
    public function GetShippingMethods() {
      
      //returns an array of object type ShippingMethod

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query','shipping');
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_Shipping;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('shipping');
      
      $ShippingMethods = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $s = new ShippingMethod();
          $s->setShipmethod($node->getAttribute('shipmethod'));
          $s->setDescription($node->getAttribute('description'));
          $s->setPrice($node->getAttribute('price'));
          $ShippingMethods[] = $s;
        }    
      }
      return $ShippingMethods;    
    }    
    
    public function GetShippingRegions() {
      
      //returns an array 
      //key is an integer
      //value is a state (AK, CA, etc)

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query','shipregions');
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_ShipRegions;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('region');
      
      $ShippingRegions = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $ShippingRegions[] = $node->getAttribute('name');
        }    
      }
      return $ShippingRegions;    
    } 
    public function GetCountries() {
      
      //returns an array 
      //key is an integer
      //value is a country (US, etc)

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query','countries');
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_Countries;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('country');
      
      $Countries = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $data = $node->getAttribute('name');
          $parts= explode("-",$data);
          $Countries[$parts[0]] = $parts[1];
        }    
      }
      return $Countries;    
    }
    public function OrderStatus($orderID="",$email="") {

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      
      $order = $doc->createElement('order');
      $node = $root->appendChild($order);
      
      $node->setAttribute('id',$orderID);
      $node->setAttribute('email',$email);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_OrderStatus;
      $data = $this->GetData($url,$xml_string);
 
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      
      $Status = array();
      
      $allnodes = $doc->getElementsByTagName('order');
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $statusStr = $node->getAttribute('statusStr');
          $orderDT = $node->getAttribute('orderDT');
          $Status["Status"] = $statusStr;
          $Status["Date"] = $orderDT;

        }    
      }
      
      $allnodes = $doc->getElementsByTagName('item');
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $itemName = $node->getAttribute('name');
          $Status["Item"] = $itemName;
        }    
      }
      return $Status;      
    }
    
    public function PostOrder() {
      
      $firstName = $_POST["firstname"];
      $lastName = $_POST["lastname"];
      $email = $_POST["email"];
      $shippingMethod = $_POST["shippingmethod"];
      $address1 = $_POST["address1"];
      $address2 = $_POST["address2"];
      $city = $_POST["city"];
      $state = $_POST["state"];
      $zip = $_POST["zip"];
      $country = $_POST["country"];
      $phone  = $_POST["phone"];
      $cardtype = $_POST["cardtype"];
      $cardname = $_POST["cardname"];
      $cardnumber = $_POST["cardnumber"];
      $cvv = $_POST["cvv"];
      $expmonth = $_POST["expmonth"];
      $expyear = $_POST["expyear"];
      $billingaddress1 = $_POST["billingaddress1"]; if (strlen($billingaddress1)==0) {$billingaddress1=$address1;}
      $billingaddress2 = $_POST["billingaddress2"]; if (strlen($billingaddress2)==0) {$billingaddress1=$address2;}
      $billingcity = $_POST["billingcity"]; if (strlen($billingcity)==0) {$billingcity=$city;}
      $billingstate = $_POST["billingstate"]; if (strlen($billingstate)==0) {$billingstate=$state;}
      $billingzip = $_POST["billingzip"]; if (strlen($billingzip)==0) {$billingzip=$zip;}
      $billingcountry = $_POST["billingcountry"]; if (strlen($billingcountry)==0) {$billingcountry=$country;}
      $billingphone = $_POST["billingphone"]; if (strlen($billingphone)==0) {$billingphone=$phone;}
      $DOBMonth = $_POST["DOBMonth"];
      $DOBDay = $_POST["DOBDay"];
      $DOBYear = $_POST["DOBYear"];
      $gender = $_POST["gender"];
      $height = $_POST["height"];
      $weight = $_POST["weight"];
      $BMI = $_POST["BMI"];
      
      $shippingMethod = $_POST["shippingmethod"];
      foreach ($_SESSION["shippingmethods"] as $i => $method) {
        if (strcmp($method->getShipMethod(),$shippingMethod)==0) {
          $shippingMethod = $method; //setting variable to the shipping method object
          break;
        }
      }
      

      $item = $_SESSION["item"]; //array
            
      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('url',$this->usrPostLoc_Gateway);
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $root->setAttribute('domainStr',$_SERVER['SERVER_NAME']);
      
      $order = $doc->createElement("order");
      $newnode = $root->appendChild($order);
      $newnode->setAttribute('subTotal',number_format($item["itemPrice"],2));
      $newnode->setAttribute('shipTotal',number_format($shippingMethod->getPrice(),2));
      $newnode->setAttribute('taxTotal',0);
      $newnode->setAttribute('adjustmentTotal',0);
      $newnode->setAttribute('orderTotal',number_format($item["itemPrice"]+$shippingMethod->getPrice(),2));
      $newnode->setAttribute('shipMethod',$shippingMethod->getShipMethod());
      $newnode->setAttribute('CampaignGroupID','');
      $newnode->setAttribute('CampaignID','');
      
      $customer = $doc->createElement("customer");
      $newnode = $order->appendChild($customer);
      $newnode->setAttribute("companyName","");
      $newnode->setAttribute("contactFName",$firstName);
      $newnode->setAttribute("contactLName",$lastName);
      $newnode->setAttribute("email",$email);
      
      $billaddress = $doc->createElement("address");
      $newnode = $customer->appendChild($billaddress);
      $newnode->setAttribute("type","0");
      $newnode->setAttribute("street",$billingaddress1);
      $newnode->setAttribute("street2",$billingaddress2);
      $newnode->setAttribute("city",$billingcity);
      $newnode->setAttribute("region",$billingstate);
      $newnode->setAttribute("postalCode",$billingzip);
      $newnode->setAttribute("country",$billingcountry);
      $newnode->setAttribute("phone",$billingphone);
      
      $shipaddress = $doc->createElement("address");
      $newnode = $customer->appendChild($shipaddress);
      $newnode->setAttribute("type","1");
      $newnode->setAttribute("street",$address1);
      $newnode->setAttribute("street2",$address2);
      $newnode->setAttribute("city",$city);
      $newnode->setAttribute("region",$state);
      $newnode->setAttribute("postalCode",$zip);
      $newnode->setAttribute("country",$country);
      $newnode->setAttribute("phone",$phone);     
      
      $options = $doc->createElement("options");
      $newnode = $customer->appendChild($options);
      if (isset($_SESSION["questions"])) {
        foreach ($_SESSION["questions"] as $i => $q) {
          if ($q->getQType()==4) {
            $option = $doc->createElement("option");
            $newnode = $options->appendChild($option);     
            $newnode->setAttribute("name",$q->getQXMLName());
            
            $answer = $_POST["q_".$q->getQID()];
            if (strcmp(strtolower($answer),"none")==0) {
              $newnode->nodeValue = $answer;
            }
            else {
              $textResponse = $_POST["qs_".$q->getQID()];
              $newnode->nodeValue = $answer." : ".$textResponse;
            }
          }
        }
      }
      
      $items = $doc->createElement("items");
      $newnode = $order->appendChild($items);
      $itemxml = $doc->createElement("item");
      $newnode = $items->appendChild($itemxml);
      $newnode->setAttribute("number",$item["itemNumber"]);
      $newnode->setAttribute("name",$item["itemName"]);
      $newnode->setAttribute("qty","1");//hardcoded to 1 item
      $newnode->setAttribute("unitPrice",$item["itemPrice"]);
      
      $options = $doc->createElement("options");
      $newnode = $itemxml->appendChild($options);
      
      if (strlen($DOBMonth)>0) {
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_Birth_day");
        $newnode->nodeValue = $DOBDay;
        
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_Birth_month");
        $newnode->nodeValue = $DOBMonth;
       
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_Birth_year");
        $newnode->nodeValue = $DOBYear;        
      }

      if (strlen($gender)>0) {
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_sex");
        $newnode->nodeValue = $gender;        
      }
      
      if ($height>0) {
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_height");
        $newnode->nodeValue = $height;        
      }
      if ($weight>0) {
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_weight");
        $newnode->nodeValue = $weight;        
      } 
      if ($BMI>0) {
        $option = $doc->createElement("option");
        $newnode = $options->appendChild($option);
        $newnode->setAttribute("name","_BMI");
        $newnode->nodeValue = $BMI;        
      }      
      if (isset($_SESSION["questions"])) {
        foreach ($_SESSION["questions"] as $i => $q) {
          if ($q->getQType()==2) {
            $option = $doc->createElement("option");
            $newnode = $options->appendChild($option);     
            $newnode->setAttribute("name",$q->getQText());
            
            $answer = $_POST["q_".$q->getQID()];
            if (strcmp(strtolower($answer),"I agree")==0) {
              $newnode->nodeValue = $answer;
            }
            else {
              $textResponse = $_POST["qs_".$q->getQID()];
              $newnode->nodeValue = $answer." : ".$textResponse;
            }
          }
          if ($q->getQType()==3) {
            $option = $doc->createElement("option");
            $newnode = $options->appendChild($option);     
            $newnode->setAttribute("name",$q->getQText());
            $textResponse = $_POST["qs_".$q->getQID()];
            $newnode->nodeValue = $textResponse;
          }          
        }
      }      
      
      $payment = $doc->createElement("payment");
      $newnode = $order->appendChild($payment);
      $newnode->setAttribute("type","0"); //hard coded to credit card
      $newnode->setAttribute("amount",number_format($item["itemPrice"]+$shippingMethod->getPrice(),2));
      $newnode->setAttribute("name",$cardname);
      
      $options = $doc->createElement("options");
      $newnode = $payment->appendChild($options);
      
      $option = $doc->createElement("option");
      $newnode = $options->appendChild($option);
      $newnode->setAttribute("name","cardType");
      $newnode->nodeValue = $cardtype;
      
      $option = $doc->createElement("option");
      $newnode = $options->appendChild($option);
      $newnode->setAttribute("name","cardNumber");
      $newnode->nodeValue = $cardnumber; 
      
      $option = $doc->createElement("option");
      $newnode = $options->appendChild($option);
      $newnode->setAttribute("name","cardExp");
      $newnode->nodeValue = str_pad($expmonth,2,"0").str_pad(substr($expyear,-2),2,"0");      

      $option = $doc->createElement("option");
      $newnode = $options->appendChild($option);
      $newnode->setAttribute("name","cvv2");
      $newnode->nodeValue = $cvv;       
      
      $options = $doc->createElement("options");
      $newnode = $order->appendChild($options);
      $xml_string= $doc->saveXML();

      $log = 0;
      if ($log==1) {
        $this->LogFile($xml_string);
      }
      $url = $this->usrPostLoc_Gateway;

      $data = $this->GetData($url,$xml_string);
      if ($log==1) {
        $this->LogFile($data);
      }
      $response = "";
      if (strlen($data)>0) {
        $doc = new DomDocument('1.0');
        try {
          $doc->loadXML($data);
          $allnodes = $doc->getElementsByTagName('success');
          if ($allnodes->length > 0) {        
            foreach ($allnodes as $node) {
              $response->orderID = $node->getAttribute('orderID');
              $response->customerID = $node->getAttribute('customerID');
              $response->paymentID =$node->getAttribute('paymentID');
            }   
            $_SESSION["response"] = $response;
          }   
        }
        catch (Exception $e) {
          $response = null;
        }
      }
      return $response;
    }

  }
  
  
?>
