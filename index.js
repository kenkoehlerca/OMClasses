/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
  $('#itemNum').change(function() {
    if ($(this).val()=="-1") {
      alert("Please select an item");
    }
    else {
      $('#form1').submit();
      return false;
    }
  });
});



