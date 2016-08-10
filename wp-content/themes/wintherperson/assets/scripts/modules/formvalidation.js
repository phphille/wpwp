// preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ0-9.,?:\-/()&+– ]{1,})$/", $query['company_name']) &&
// // checkOrgNbr($query['company_org-nbr']) &&
// preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ0-9.,?:\-/()& ]{1,})$/", $query['company_address']) &&
// preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ \-]{1,})$/", $query['company_city']) &&
// preg_match("/^[0-9]{3}[ ]?[0-9]{2}$/", $query['company_postalcode']) &&
// is_email($query['company_contactMail']) &&
// preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_contactFirstname']) &&
// preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_contactLastname']) &&
// preg_match("/^[0-9+ \-]{1,}$/", $query['company_contactPhone']) &&
// preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_deliveryFirstname']) &&
// preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_deliveryLastname']) &&
// preg_match("/^[0-9+ \-]{1,}$/", $query['company_deliveryPhone']) &&
var formvalidation = {
  init: function(){

    if($j('form input[name*="postalcode"]').length > 0){
      $j('form input[name*="postalcode"]').each(function(){
        $j(this).on('blur', function() {
          if(/^[0-9]{3}[ ]?[0-9]{2}$/.test(this.value)){
            console.log('postalcode OK');
          }
          else {
            console.log('postalcode FEL');
          }
        });
      });
    }

    if($j('form input[name*="email"]').length > 0){
      $j('form input[name*="email"]').each(function(){
        $j(this).on('blur', function() {
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value)){
            console.log('email OK');
          }
          else {
            console.log('email FEL');
          }
        });
      });
    }

    if($j('form input[name*="name"]:not([name="company_name"])').length > 0){
      $j('form input[name*="name"]:not([name="company_name"])').each(function(){
        $j(this).on('blur', function() {
          if(/[¤¢£¥¦§©ª«¬®¯°±²³µ¶¸¹º»¼½¾¿Þßþƒ†‡•…‰€™☺○♀♂♪♫◙↨☼►◄↕‼▬↑↓→←∟↔▲▼☻♥♦♣♠◘╚╔╩╦╠═╬¡░▒▓│┤╣║╗╝┐└┴┬├─┼▄█┌┘ı▀シ⌂~!@#_\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\<\>\?\/0-9]+/.test(this.value)){
            console.log('name(not company_name) OK');
          }
          else {
            console.log('name(not company_name) FEL');
          }
        });
      });
    }

    if($j('form input[name*="address"]').length > 0){
      $j('form input[name*="address"]').each(function(){
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,:\-/()& ]{1,})$/.test(this.value)){
            console.log('adderess OK');
          }
          else {
            console.log('address FEL');
          }
        });
      });
    }

    if($j('form input[name*="city"]').length > 0){
      $j('form input[name*="city"]').each(function(){
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9\- ]{1,})$/.test(this.value)){
            console.log('city OK');
          }
          else {
            console.log('city FEL');
          }
        });
      });
    }

    if($j('form input[name*="phone"]').length > 0){
      if($j('form input[name*="phone"]').length > 0){
        $j('form input[name*="phone"]').each(function(){
          $j(this).on('blur', function() {
            if(/^[0-9+ \-]{1,}$/.test(this.value)){
              console.log('phone OK');
            }
            else {
              console.log('phone FEL');
            }
          });
        });
      }
    }

    if($j('form input[name="password2"]').length > 0){
      $j('form input[name="password2"]').each(function(){
        $j(this).on('blur', function() {
          if(this.value === $j('form input[name="password"]').val()){
            console.log('passwordsssss OK');
          }
          else {
            console.log('passwordsssss FEL');
          }
        });
      });
    }

    if($j('form input[name="passwordCurrent"]').length > 0){
      $j('form input[name="passwordCurrent"]').each(function() {
        $j(this).on('blur', function() {
          if(this.value.trim() !== '' ){
            console.log('passwordCurrent OK');
          }
          else {
            console.log('passwordCurrent FEL');
          }
        });
      });
    }

    if($j('form input[name="company_name"]').length > 0){
      $j('form input[name="company_name"]').each(function() {
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,?:\-/()&+– ]{1,})$/.test(this.value)){
            console.log('company_name');
          }
          else {
            console.log('company_name');
          }
        });
      });
    }
  }
};
