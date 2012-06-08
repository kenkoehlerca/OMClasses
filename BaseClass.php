<?php


class BaseClass {


  protected $usrGateway_UserName;
  protected $usrGateway_Password;
  protected $usrGateway_DomainStr;
  protected $usrPostLoc_OrderForm;

  protected $backend;

  protected $usrPostLoc_Gateway;
  protected $usrPostLoc_PriceList;
  protected $usrPostLoc_ItemListName;
  protected $usrPostLoc_ItemInfo;
  protected $usrPostLoc_ItemInfoName;
  protected $usrPostLoc_Questions;
  protected $usrPostLoc_Shipping;
  protected $usrPostLoc_OrderStatus;
  protected $usrPostLoc_CustomerInfo;
  protected $usrPostLoc_Tracking;
  protected $usrPostLoc_Password;
  protected $usrPostLoc_CategoryList;
  protected $usrPostLoc_SubCategoryList;
  protected $usrPostLoc_PriceList_Search;
  protected $usrPostLoc_CreditCards;
  protected $usrPostLoc_PaymentUpdate;
  protected $usrPostLoc_AddressUpdate;
  protected $usrPostLoc_PartNameList;
  protected $usrPostLoc_OrderSearch;
  protected $usrPostLoc_CampaignSearch;
  protected $usrPostLoc_Settings;
  protected $usrPostLoc_ShipRegions;
  protected $usrPostLoc_MailingList;
  protected $usrPostLoc_ImageView;
  protected $usrPostLoc_PriceList_Image;
  protected $usrPostLoc_ItemInfo_Image;
  protected $usrPostLoc_RetailerAdd;
  protected $usrPostLoc_RTrack;
  protected $usrPostLoc_Countries;
  protected $usrPostLoc_ItemCategoryTreeList;
  protected $usrPostLoc_PriceList_Categories;
  protected $usrPostLoc_Contact;
  protected $usrPostLoc_PaymentTypes;
  
   function __construct() {
    
    //contact your support rep for the appropriate values to connect to the host
    $this->usrGateway_UserName			= "";
    $this->usrGateway_Password			= "";
    $this->usrGateway_DomainStr			= "";
    $this->backend                      = "";
    
    $this->usrPostLoc_OrderForm			= "order.php";


         
    $this->usrPostLoc_Gateway		= $this->backend."/gateway.receive.asp";
    $this->usrPostLoc_PriceList		= $this->backend."/services/itemlist.asp";
    $this->usrPostLoc_ItemListName		= $this->backend."/services/itemlist_name.asp";
    $this->usrPostLoc_ItemInfo		= $this->backend."/services/iteminfo.asp";
    $this->usrPostLoc_ItemInfoName	= $this->backend."/services/iteminfo_name.asp";
    $this->usrPostLoc_Questions		= $this->backend."/services/item_questions.asp";
    $this->usrPostLoc_Shipping		= $this->backend."/services/shipping.asp";
    $this->usrPostLoc_OrderStatus		= $this->backend."/services/orderStatusResults.asp";
    $this->usrPostLoc_CustomerInfo	= $this->backend."/services/customerInfoResults.asp";
    $this->usrPostLoc_Tracking		= $this->backend."/services/trackCampaigns.asp";
    $this->usrPostLoc_Password		= $this->backend."/services/password.asp";
    $this->usrPostLoc_CategoryList	= $this->backend."/services/categorylist.asp";
    $this->usrPostLoc_SubCategoryList	= $this->backend."/services/subcategorylist.asp";
    $this->usrPostLoc_PriceList_Search 	= $this->backend."/services/itemlist_search.asp";
    $this->usrPostLoc_CreditCards		= $this->backend."/services/creditcards.asp";
    $this->usrPostLoc_PaymentUpdate	= $this->backend."/services/orderPaymentUpdate.asp";
    $this->usrPostLoc_AddressUpdate	= $this->backend."/services/orderAddressUpdate.asp";
    $this->usrPostLoc_PartNameList	= $this->backend."/services/partnamelist.asp";
    $this->usrPostLoc_OrderSearch		= $this->backend."/services/retailerSearch.asp";
    $this->usrPostLoc_CampaignSearch	= $this->backend."/services/campaignSearch.asp";
    $this->usrPostLoc_Settings		= $this->backend."/services/orderformsettings.asp";
    $this->usrPostLoc_ShipRegions		= $this->backend."/services/shipregions.asp";
    $this->usrPostLoc_MailingList		= $this->backend."/services/mailingList.asp";
    $this->usrPostLoc_ImageView		= $this->backend."/services/imageView.asp";
    $this->usrPostLoc_PriceList_Image	= $this->backend."/services/itemlist_image.asp";
    $this->usrPostLoc_ItemInfo_Image	= $this->backend."/services/iteminfo_image.asp";
    $this->usrPostLoc_RetailerAdd		= $this->backend."/services/retailerAdd.asp";
    $this->usrPostLoc_RTrack		= $this->backend."/services/rtrack.asp";
    $this->usrPostLoc_Countries		= $this->backend."/services/countries.asp";
    $this->usrPostLoc_ItemCategoryTreeList	= $this->backend."/services/itemCategoryTreeList.asp";
    $this->usrPostLoc_PriceList_Categories	= $this->backend."/services/itemlist_categories.asp";
    $this->usrPostLoc_Contact		= $this->backend."/services/csupportreceive.asp";
    $this->usrPostLoc_PaymentTypes	= $this->backend."/services/payment_type.asp";
         
   }
  
  protected function GetData($url,$xml_string) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_string");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $errCode = curl_errno($ch);
    $errStr  = curl_error($ch);
    
    if ($errCode != 0) {
      $output = "";
    }
    curl_close($ch);
    return $output;
  }
  protected function LogFile($logentry) {
    $today = date("Ymd");
    $time = date("H:i:s");
    $file = '/tmp/test_'.$today.".log";
    $logentry = $time." ".$logentry."\n";
    error_log($logentry, 3, $file);
  }
}


?>
