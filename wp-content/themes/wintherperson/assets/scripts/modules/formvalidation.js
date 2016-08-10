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
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name*="email"]').length > 0){
      $j('form input[name*="email"]').each(function(){
        $j(this).on('blur', function() {
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value)){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name*="name"]:not([name="companyname"])').length > 0){
      $j('form input[name*="name"]:not([name="companyname"])').each(function(){
        $j(this).on('blur', function() {
          if(!/[¤¢£¥¦§©ª«¬®¯°±²³µ¶¸¹º»¼½¾¿Þßþƒ†‡•…‰€™☺○♀♂♪♫◙↨☼►◄↕‼▬↑↓→←∟↔▲▼☻♥♦♣♠◘╚╔╩╦╠═╬¡░▒▓│┤╣║╗╝┐└┴┬├─┼▄█┌┘ı▀シ⌂~!@#_\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\<\>\?\/0-9]+/.test(this.value) && this.value.trim() !== ''){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name="user_login"]').length > 0){
      $j('form input[name="user_login"]').each(function(){
        $j(this).on('blur', function() {
          if(!/[¤¢£¥¦§©ª«¬®¯°±²³µ¶¸¹º»¼½¾¿Þßþƒ†‡•…‰€™☺○♀♂♪♫◙↨☼►◄↕‼▬↑↓→←∟↔▲▼☻♥♦♣♠◘╚╔╩╦╠═╬¡░▒▓│┤╣║╗╝┐└┴┬├─┼▄█┌┘ı▀シ⌂~!@#_\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\<\>\?\/]+/.test(this.value) && this.value.trim() !== ''){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name*="address"]').length > 0){
      $j('form input[name*="address"]').each(function(){
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,:\-/()& ]{1,})$/.test(this.value)){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name*="city"]').length > 0){
      $j('form input[name*="city"]').each(function(){
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9\- ]{1,})$/.test(this.value)){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name*="phone"]').length > 0){
      if($j('form input[name*="phone"]').length > 0){
        $j('form input[name*="phone"]').each(function(){
          $j(this).on('blur', function() {
            if(/^[0-9+ \-]{1,}$/.test(this.value)){
              $j(this).parent().removeClass('has-error');
            }
            else {
              $j(this).parent().addClass('has-error');
            }
          });
        });
      }
    }

    if($j('form input[name="password2"]').length > 0){
      $j('form input[name="password2"]').each(function(){
        $j(this).on('blur', function() {
          if(this.value === $j('form input[name="password"]').val()){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name="passwordCurrent"]').length > 0){
      $j('form input[name="passwordCurrent"]').each(function() {
        $j(this).on('blur', function() {
          if(this.value.trim() !== '' ){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }

    if($j('form input[name="companyname"]').length > 0){
      $j('form input[name="companyname"]').each(function() {
        $j(this).on('blur', function() {
          if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,?:\-/()&+– ]{1,})$/.test(this.value)){
            $j(this).parent().removeClass('has-error');
          }
          else {
            $j(this).parent().addClass('has-error');
          }
        });
      });
    }
  },

  formIsValid: function(){
    var valid = true;
    var topValues = [];

    if($j('form input[name*="postalcode"]').length > 0){
      $j('form input[name*="postalcode"]').each(function(){
        if(/^[0-9]{3}[ ]?[0-9]{2}$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('postal');
        }
      });
    }

    if($j('form input[name*="email"]').length > 0){
      $j('form input[name*="email"]').each(function(){
        if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('email');
        }
      });
    }

    if($j('form input[name*="name"]:not([name="companyname"])').length > 0){
      $j('form input[name*="name"]:not([name="companyname"])').each(function(){
        if(!/[¤¢£¥¦§©ª«¬®¯°±²³µ¶¸¹º»¼½¾¿Þßþƒ†‡•…‰€™☺○♀♂♪♫◙↨☼►◄↕‼▬↑↓→←∟↔▲▼☻♥♦♣♠◘╚╔╩╦╠═╬¡░▒▓│┤╣║╗╝┐└┴┬├─┼▄█┌┘ı▀シ⌂~!@#_\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\<\>\?\/0-9]+/.test(this.value) && this.value.trim() !== ''){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('name');
        }
      });
    }

    if($j('form input[name*="address"]').length > 0){
      $j('form input[name*="address"]').each(function(){
        if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,:\-/()& ]{1,})$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('address');
        }
      });
    }

    if($j('form input[name*="city"]').length > 0){
      $j('form input[name*="city"]').each(function(){
        if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9\- ]{1,})$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('city');
        }
      });
    }

    if($j('form input[name*="phone"]').length > 0){
      $j('form input[name*="phone"]').each(function(){
        if(/^[0-9+ \-]{1,}$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('phone');
        }
      });
    }

    if($j('form input[name="password2"]').length > 0){
      $j('form input[name="password2"]').each(function(){
        if(this.value === $j('form input[name="password"]').val()){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('password2');
        }
      });
    }

    if($j('form input[name="passwordCurrent"]').length > 0){
      $j('form input[name="passwordCurrent"]').each(function() {
        if(this.value.trim() !== '' ){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('passwordCurrent');
        }
      });
    }

    if($j('form input[name="companyname"]').length > 0){
      $j('form input[name="companyname"]').each(function() {
        if(/^([a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9]{1}[a-zåäöüàáâãäåæçèéêëìíîïñòóôõöøùúûüÿA-ZÅÄÖÜÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜß0-9.,?:\-/()&+– ]{1,})$/.test(this.value)){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('companyname');
        }
      });
    }

    if($j('form input[name*="company-korvlada-nbr"]').length > 0){
      $j('form input[name*="company-korvlada-nbr"]').each(function() {
        if(/^[0-9]{1,10000}$/.test(this.value) && this.value > 0){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
          console.log('company-korvlada');
        }
      });
    }

    if($j('form input[name="user_login"]').length > 0){
      $j('form input[name="user_login"]').each(function(){
        if(!/[¤¢£¥¦§©ª«¬®¯°±²³µ¶¸¹º»¼½¾¿Þßþƒ†‡•…‰€™☺○♀♂♪♫◙↨☼►◄↕‼▬↑↓→←∟↔▲▼☻♥♦♣♠◘╚╔╩╦╠═╬¡░▒▓│┤╣║╗╝┐└┴┬├─┼▄█┌┘ı▀シ⌂~!@#_\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\<\>\?\/]+/.test(this.value) && this.value.trim() !== ''){
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
          valid = false;
          topValues.push($j(this).parent().offset().top);
        }
      });
    }

    if(!valid && topValues){
      console.log(topValues);
      console.log(Math.min.apply(Math, topValues));
      $j("html, body").animate({ scrollTop: (Math.min.apply(Math, topValues) - 20) }, "fast");
    }


    return valid;
  }
};
