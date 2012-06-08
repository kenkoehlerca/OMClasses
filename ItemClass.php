<?php

  include_once("BaseClass.php");
  

  class ItemClass extends BaseClass {

    public function __construct() {
      parent::__construct();
    }
    
    public function GetItemCategories() {
      //returns an array  key is integer value is category
      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('query',"categorylist");
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_CategoryList;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $ItemCategories = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $ItemCategories[] = $node->getAttribute('itemCategory');
        }    
      }
      return $ItemCategories;        
    }
    public function GetItemListName() {
      //returns an array/list of item number and name
      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_ItemListName;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $ItemListName = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $ItemListName[$node->getAttribute("itemNum")] = $node->getAttribute('itemName');
        }    
      }
      return $ItemListName;      
    }    
    public function GetPartNameList() {
      //returns an array/list of item number and name

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_PartNameList;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $PartNames = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $PartNames[] = $node->getAttribute('partName');
        }    
      }
      return $PartNames;      
    }
    public function GetItems() {
      //returns an array/list of item number and name

      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_ItemListName;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $Items = array();
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $Items[$node->getAttribute('itemNum')] = $node->getAttribute('itemName');
        }    
      }
      return $Items;
      /*
       * sample xml response from host
        <response errorCount="0" resultcount="34" pagecount="0"><item itemNum="0143-1787-00-90-TP" itemName="Generic Fioricet (Butalbital Acetaminophen Caffeine combination) 50/325/40 90 tabl"/><item itemNum="0591-5513-10-120-TP" itemName="Carisoprodol (Dan)Watson Pharma 350 mg x 120 tabl"/><item itemNum="0603-2582-32-120-TP" itemName="Carisoprodol 350 mg x 120 tabl (generic Soma)"/><item itemNum="46672-0053-50-90-BD" itemName="Butalbital APAP comb. (generic Fioricet) 90 tabs"/><item itemNum="5215-CarWatson-05-WP" itemName="Carisoprodol Watson 350mg 120 tab"/><item itemNum="52544--FIOR-90-TP" itemName="FIORICET  (branded Butalbital Acetaminophen Caffeine comb.) 50/325/40 90 tabl"/><item itemNum="52544-957-05-FIOR-TP" itemName="FIORICET  (branded Butalbital Acetaminophen Caffeine comb.) 50/325/40 120 tabl"/><item itemNum="65162-627-11-Gen-TP" itemName="Tramadol 50mg 180tabl (Generic Ultram)"/><item itemNum="AU3562-ZITHRO-V" itemName="Zithromax 500mg 2 tabl"/><item itemNum="CIA-20-4-EU" itemName="Cialis 20mg 4tabl"/><item itemNum="CN3004 - V" itemName="Inderal 40mg 100 tabs"/><item itemNum="CYCLO-10-60-BD" itemName="Cyclobenzaprine 10 mg x 60 tabl ( generic Flexeril )"/><item itemNum="EU3561 - V" itemName="Xenical 120mg 84 tab"/><item itemNum="EU4833-V" itemName="Flomax 0,4mg 30"/><item itemNum="FIO-50/325/40-60-W" itemName="FIORICET (brand Butalbital Acetaminophen Caffeine combination) 50/325/40 90 tabl"/><item itemNum="FLEX-10-90-EU" itemName="Flexeril 10 mg x 90 tabl"/><item itemNum="FLEX-5-90-EU" itemName="Flexeril 5mg x 90 tabl"/><item itemNum="Gabap-300-100-FN" itemName="Gabapentin (generic Neurontin) 300mg 100 caps"/><item itemNum="IN4473-AZITHRO-V" itemName="Azithromycin (Generic	Zithromax) 250mg 60 tab"/><item itemNum="PRO-1-30-EU" itemName="Propecia 1mg 30tabl"/><item itemNum="soma-wa-350-90-TP" itemName="Carisoprodol Watson 350mg 90 tab"/><item itemNum="TRA-APAP-37/325-90-B" itemName="Tramadol APAP (generic Ultracet) 37.5/325mg 90 tablets"/><item itemNum="TRA-APAP-37/325-90-W" itemName="Tramadol APAP (generic Ultracet) 37.5/325mg 90 tablets"/><item itemNum="TRACET-37.5/325-100" itemName="Ultracet 37.5/325mg 100 tablets"/><item itemNum="Trama-50-180-BD" itemName="Tramadol (generic Ultram) 50mg 180 tablets"/><item itemNum="trama-50-180-W" itemName="Tramadol (generic Ultram) 50mg 180 tablets"/><item itemNum="ULTRAM-50-90-BD" itemName="Ultram (tramadol) 50mg 90 tablets"/><item itemNum="ULTRAM-50-90-TP" itemName="Ultram 50mg 90 tablets"/><item itemNum="V-CN3136 Furo80mg100" itemName="Furosemide80MG100"/><item itemNum="V-IN002171" itemName="Vardenafil 20mg 30tabl (Generic Levitra)"/><item itemNum="V-IN2633" itemName="Valacyclovir 1000mg 3tabl (Generic  Valtrex)"/><item itemNum="V-IN2634" itemName="Valacyclovir 500mg 30 tabl (Generic Valtrex)"/><item itemNum="VAL-500-90-EU" itemName="Valtrex 500mg 90tabl"/><item itemNum="00603-CarGen-120-WP" itemName="CARISOPRODOL 350MG TABS, 120 tabl"/></response>
       */
    }
    public function GetItemInfo($itemNumber) {
      /*returns an array
        the keys are:
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
      if (strlen($itemNumber)==0) {return null;}
      
      $doc = new DomDocument('1.0');
      $root = $doc->createElement('request');
      $root = $doc->appendChild($root);
      $root->setAttribute('username',$this->usrGateway_UserName);
      $root->setAttribute('password',$this->usrGateway_Password);
      $root->setAttribute('itemNum',$itemNumber);
      $xml_string= $doc->saveXML();
      $url = $this->usrPostLoc_ItemInfo;
      $data = $this->GetData($url,$xml_string);
      
      $doc = new DomDocument('1.0');
      $doc->loadXML($data);
      $allnodes = $doc->getElementsByTagName('item');
      
      $Item = array();
      $Item["itemNumber"]= $itemNumber;
      
      if ($allnodes->length > 0) {        
        foreach ($allnodes as $node) {
          $Item["itemName"] = $node->getAttribute('itemName');
          $Item["itemPrice"] = $node->getAttribute('itemPrice');
          $Item["itemInfo"] = $node->getAttribute('itemInfo');
          $Item["partName"] = $node->getAttribute('partName');
          $Item["itemID"] = $node->getAttribute('itemID');
          $Item["flagBMI"] = $node->getAttribute('flagBMI');
          $Item["flagSex"] = $node->getAttribute('flagSex');
          $Item["flagAge"] = $node->getAttribute('flagAge');
          $Item["weight"] = $node->getAttribute('weight');
          $Item["weightUnit"] = $node->getAttribute('weightUnit');
        }    
      }
      return $Item;      
    }
  }
?>
