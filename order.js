
function CheckBMI()
   {
   if (document.paymentform.weight.value != '' && document.paymentform.height.value != '')
      {
      CalculateBMI();
      }
   else
      {
      document.paymentform.dBMI.value = '';
      document.paymentform.BMI.value = '';
      }
   }
function CalculateBMI()
   {
   if (isNaN(document.paymentform.weight.value) || eval(document.paymentform.weight.value)==0 || document.paymentform.height.selectedIndex==0)
      {
      document.paymentform.dBMI.value = '';
      document.paymentform.BMI.value = '';
      }
   else
      {
      var Height = eval(document.paymentform.height.options[document.paymentform.height.selectedIndex].value)/39.36;
      var Weight = eval(document.paymentform.weight.value)/2.2;
      document.paymentform.dBMI.value = Math.round(Weight/(Height*Height));
      document.paymentform.BMI.value = document.paymentform.dBMI.value;
      }
}
function qsSet(obj) {
  var r = obj.name;
  if (obj.value=="I Disagree" || obj.value=="I will specify" || obj.value=="No") {
    parts= r.split("_");
    n = parts[1];
    t = "qs_"+n;
    t=document.getElementById(t);
    t.style.display="inline";
  }
  else {
    parts= r.split("_");
    n = parts[1];
    t = "qs_"+n;
    t=document.getElementById(t);
    t.style.display="none";    
  }
  
}
function ShippingMethod() {
  s = document.paymentform.shippingmethod.selectedIndex;
  $("#defaultShippingPrice").html('$'+shippingMethods[s].toFixed(2));
  $("#totalAmount").html('$'+(shippingMethods[s]+itemPrice).toFixed(2));
}

function CopyShipping() {
  s = document.paymentform.sameaddress.checked;
  if (s==true) {
    $("#billingaddress1").val($("#address1").val());
    $("#billingaddress2").val($("#address2").val());
    $("#billingcity").val($("#city").val());
    $("#billingzip").val($("#zip").val());
    $("#billingphone").val($("#phone").val());
    
    $("#billingstate").val($("#state").val());
    $("#billingcountry").val($("#country").val());
  }
  else {
    $("#billingaddress1").val("");
    $("#billingaddress2").val("");
    $("#billingcity").val("");
    $("#billingzip").val("");
    $("#billingphone").val("");    
    $("#billingstate").val("");
    $("#billingcountry").val("US");
  }
}

function ValidateForm() {
  values = $('#paymentform :input');
  questionReminder = "Please answer all questions\n";
  questionReminderFlag = 0;
  

  str = "";
  cc = checkCC();
  if (cc==false) {
    str = "Invalid Credit Card Entered\n";
  }
  
  cvv = checkCVV();
  if (cvv == false) {
    str += "Invalid Card cvv value entered.\n";
  }
  
  phone = validatePhone();
  if (phone == false) {
    str += "Invalid phone entered.\n";
  }  
  
  email = validateEmail();
  if (email == false) {
    str += "Invalid email entered.\n";
  }   
  for (x = 0; x < values.length; x++) {
    fieldname =  $.trim(values[x].name);
    fieldvalue = $.trim(values[x].value);
    if (fieldname=="Submit" || fieldname=="address2" || 
        fieldname=="billingaddress2" || fieldname=="cardnumber" ||
        fieldname=="cvv" || fieldname.substring(0,3)=="qs_" ||
        fieldname=="sameaddress" || fieldname=="itemNum" || fieldname=="email" ||
        fieldname=="BMI" || fieldname=="dBMI" || fieldname=="phone" || fieldname=="billingphone"
      ) {
      continue;
    }
    else if (fieldname.substring(0,5)=="qtext") {
      qtext = document.getElementById(fieldname);
      if (qtext.style.display=="inline" && qtext.length < 2) {
        if (questionReminder==0) {
          str +=questionReminder;
          questionReminderFlag = 1;          
        }
        qtext.style.backgroundColor = "#FFE6E6";
      }
    }
    else if (fieldname.substring(0,2)=="q_") {
      var textboxStr = "qs_"+fieldname.substring(2);
      textbox = document.getElementById(textboxStr);
      r1 = document.getElementById(fieldname+"_1");
      r2 = document.getElementById(fieldname+"_2");
      if (r1===null && r2 === null) {
        //textbox without radio buttons
        if (textbox !== null) {
          if (textbox.value.length < 2) {
            if (questionReminderFlag == 0) {
              str +=questionReminder;
              questionReminderFlag = 1;            
            }
            textbox.style.backgroundColor="#FFE6E6";
          }
        }
      }
      else if (r1.checked == false && r2.checked== false) {
        //neither radio button is checked
        if (questionReminderFlag == 0) {
          str +=questionReminder;
          questionReminderFlag = 1;
          r1.style.backgroundColor = "#FFE6E6";
          r2.style.backgroundColor = "#FFE6E6";
        }
        else {
          r1.style.backgroundColor = "#FFE6E6";
          r2.style.backgroundColor = "#FFE6E6";          
        }
      }
      else if (r2.checked) {
        //negative answer radio button checked
        if (textbox.value.length<2) {
          if (questionReminderFlag == 0) {
            str +=questionReminder;
            questionReminderFlag = 1;            
          }
          textbox.style.display="inline";
          textbox.style.backgroundColor = "#FFE6E6";
        }
      }
    }
    else {
      if (fieldvalue.length< 2) {
        //generic field such as name, address1, etc
        $("#"+fieldname).css("background-color","#FFE6E6");
        str += fieldname+" is required\n";
      }
      
    }
    
  }
  if (str.length>0) {
    alert(str);
    return false;
  }
  else {
    return true;
  }
}

function validateEmail() {
    email = document.paymentform.email;
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (reg.test(email.value) == false) {
            return false;
        }
    return true;
}
		
function validatePhone() {
  var digits = "";
  var x = 0;
  var c = "";
  phone = document.paymentform.phone.value;
  if (phone.substring(0,1)==1) {
    phone = phone.substring(1,phone.length);
  }
  while (x < phone.length) {
    c = phone.substring(x,x+1);
    if (parseInt(c)>=0 && parseInt(c)<=9) {
      digits = digits + c;
    }
    x = x+ 1;
  }
  if (digits.length==10) {
    return true;
  }
  else {
    return false;
  }
}	


function checkCC()
   {
   var sCardText = new String();
   sCardText = document.paymentform.cardnumber.value;
   var regexp;
   regexp = / /g;
   sCardText = sCardText.replace(regexp,"");
   
	if (sCardText.substring(0,1)=="3") {
		return false;
	}	 
	 
	 if(sCardText.length<=0)
      {
      document.paymentform.cardnumber.focus();
      return false;
      }
   if(sCardText.length>16)
      {
      document.paymentform.cardnumber.focus();
      return false;
      }			
   if (CheckNum(document.paymentform.cardnumber.value) == 0)
      {
      return false;
      }
   return true;
   }
/////////////////////////////////////////////////////////////////
function CheckNum(cardnum)
   {
   if (cardnum == '') return 0;
   if (isNaN(cardnum)) return 0;
   if (cardnum=='4111111111111111') return 0;
   if (cardnum=='4242424242424242') return 0;
   if (cardnum=='5454545454545454') return 0;
   if (!CheckLUHN(cardnum)) return 0;
   return 1;
   }
  /////////////////////////////////////////////////////////////////
function CheckLUHN(cardnum)
   {
   var RevNum = new String(cardnum);
   RevNum = Reverse(RevNum);

   var total = new Number(0);
   for ( var i = 0; i < RevNum.length; i += 1 )
      {
      var temp = 0;
      if (i % 2)
         {
         temp = RevNum.substr(i, 1) * 2;
         if (temp >= 10)
            {
            var splitstring = new String(temp);
            temp = parseInt(splitstring.substr(0, 1)) + parseInt(splitstring.substr(1, 1));
            }
         }
      else
         {
         temp = RevNum.substr(i, 1);
         }
      total += parseInt(temp);
      }
   // if there's no remainder, we return 1 (true)
   return (total % 10) ? 0 : 1;
   }
/////////////////////////////////////////////////////////////////
function Reverse(strToReverse)
   {
   var strRev = new String;
   var i = strToReverse.length;

   while (i--)
      {
      strRev += strToReverse.charAt(i);
      }
   return strRev;
   }
/////////////////////////////////////////////////////////////////
function checkExpDate()
{
   var d = new Date;
   var curYear = d.getFullYear();
   var curMonth = d.getMonth()+1;
   var expmonth = document.paymentform.expmonth.options[document.paymentform.expmonth.selectedIndex].value;
   var expyear = document.paymentform.expyear.options[document.paymentform.expyear.selectedIndex].value;

       if ( (curYear > expyear) || ((curYear == expyear) && (curMonth > expmonth))) return false;

   return true;
}
function checkCVV()
{
  var c = new String();
  c = document.paymentform.cvv.value;
      card = document.paymentform.cardnumber.value;

      if (card.substr(0,1) == "3") {
          if (c.length == 4 && IsNumeric(c)==true) {
              return true;
          }
      }
      else {
          if (c.length == 3 && IsNumeric(c)==true) {
              return true;
          }			
      }

  return false;

}

function IsNumeric(sText) {
var ValidChars = "0123456789.";
var IsNumber=true;
var Char;

for (i = 0; i < sText.length && IsNumber == true; i++) { 
  Char = sText.charAt(i); 
  if (ValidChars.indexOf(Char) == -1) {
    IsNumber = false;
  }
}
return IsNumber;
  
}