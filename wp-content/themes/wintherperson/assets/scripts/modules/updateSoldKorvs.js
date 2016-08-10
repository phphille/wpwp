var updateSoldKorvs = {

  init: function() {
    this.updateKorvs();
    this.checkRemoveOfproduct();
    this.addNewCompanyKorvlada();
    this.removeNewCompanyKorvlada();
    this.saveCompany();
    this.updateCompany();
  },

  updateKorvs: function() {
    if($j('.private-customer-form .updateSoldKorv').length > 0){
      var self = this;
      $j('.private-customer-form .updateSoldKorv').click(function(e) {
        e.preventDefault();
        if(this.form.querySelector('select').value !== '' && this.form.querySelector('input[name="nbrProducts"]').value > 0){
          data = self.getNiceData($j(this).parent().serializeArray());
          data.add = this.name === 'add' ? true : false;
          self.postData(data, 'korvlador', 'update_sold_korvs', self.updatePrivateCustomerList);
          $j(this).parent().removeClass('has-error');
        }
        else {
          $j(this).parent().addClass('has-error');
        }
      });
    }
  },




  checkRemoveOfproduct: function(){
    if($j('.private-customer-form select[name="korvlada"]').length > 0){
      var self = this;
      $j('.private-customer-form select[name="korvlada"]').on('change', function(e) {
        $j('.private-customer-form .updateSoldKorv[name="remove"]')[0].disabled = true;
        $j('.private-customer-form .updateSoldKorv[name="add"]')[0].disabled = false;
        $j('.private-customer-form input[name="nbrProducts"]')[0].disabled = false;
        self.postData(self.getNiceData($j(this).parent().serializeArray()), 'korvlador', 'check_user_can_remove_sold_item', self.disableUndisableRemoveProduct);
      });
    }
  },




  disableUndisableRemoveProduct: function (canRemove){
    if (canRemove) {
      $j('.private-customer-form .updateSoldKorv[name="remove"]')[0].disabled = false;
    } else {
      $j('.private-customer-form .updateSoldKorv[name="remove"]')[0].disabled = true;
    }
  },




  updatePrivateCustomerList: function (item){
    item = JSON.parse(item);
    if($j('#private-'+item.id).length > 0){
      if (item.quantity) {
        $j('#private-'+item.id+' span:last-of-type').text(item.quantity+' st.');
      } else {
        $j('#private-'+item.id).remove();
      }
    }
    else {
      $j('<li id="private-'+item.id+'"><span>'+item.title+'</span> <span>'+item.quantity+' st.</span></li>').appendTo('.private-korvlade-list');
    }

    if(item.quantity === 0){
      updateSoldKorvs.disableUndisableRemoveProduct(false);
    }
    else {
      updateSoldKorvs.disableUndisableRemoveProduct(true);
    }
  },




  addNewCompanyKorvlada: function(){
    if($j('.company-customer-form button[name="add"]').length > 0){
      $j('.company-customer-form button[name="add"]').on('click', function(e){
        var selectElement = this.form.querySelector('select');
        var amountElement = this.form.querySelector('input[name="company-korvlada-amount"]');
        if(selectElement.value !== '' && amountElement.value > 0 && !this.form.querySelector('input[name="company-korvlada-'+selectElement.value+'"]')){
          e.preventDefault();
          // console.dir(this.form.querySelector('select'));
          var html = '<div class="panel panel-default">'+
                        '<div class="panel-body">'+
                          '<div class="input-group">'+
                          '<input class="" type="text" disabled name="" value="'+selectElement.options[selectElement.selectedIndex].text+'">'+
                          '<input class="company-korvlada-amount" type="number" min="1" name="company-korvlada-nbr-'+(selectElement.value)+'" value="'+amountElement.value+'"> st.'+
                          '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
                          '<span>Får bara innehålla siffror och vara ett positivt värde</span>'+
                          '</div>'+
                        '</div>'+
                      '</div>';
          $j(html).appendTo('.new-company-korvlade-list');
          selectElement.options[selectElement.selectedIndex].disabled = true;
          selectElement.options[0].selected = true;
          $j('.company-customer-form button[name="add"]')[0].disabled = true;
        }
      });

      $j('.company-customer-form select[name="company-korvlada"]').on('change', function(e) {
        if(parseInt(this.form.querySelector('input[name=company-korvlada-amount]').value, 10) > 0){
          $j('.company-customer-form button[name="add"]')[0].disabled = false;
        }
        else {
          $j('.company-customer-form button[name="add"]')[0].disabled = true;
        }
      });

      $j('.company-customer-form input[name=company-korvlada-amount]').on('input', function(e) {
        if(parseInt(this.value, 10) > 0 && $j('.company-customer-form select[name="company-korvlada"]')[0].value !== ''){
          $j('.company-customer-form button[name="add"]')[0].disabled = false;
        }
        else {
          $j('.company-customer-form button[name="add"]')[0].disabled = true;
        }
      });
    }
  },



  saveCompany: function(){
    if($j('.company-customer-form input[name="do-company"]').length > 0){
      var self = this;
      $j('.company-customer-form input[name="do-company"]').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        if(!formvalidation.formIsValid()){
          return false;
        }
        else {
          data = self.getNiceData($j(this).parent().serializeArray());
          self.postData(data, 'korvlador', 'save_company', self.checkSaveCompany);
        }
      });
    }
  },

  checkSaveCompany: function(res) {
    if(res > 0){
      console.log('redirect');
      location.reload();
    }
    else {
      $j('.company-customer-form input[name="do-company"]').next().removeClass('hide');
      console.log('något gick fel');
    }
  },



  removeNewCompanyKorvlada: function(){
    $j('.new-company-korvlade-list').on('click','.glyphicon-remove', function(e){
      e.preventDefault();

      var optionVal = $j('input:first-of-type',$j(this).parent())[0].name.split("-");
      var options = this.previousElementSibling.form.querySelector('select').children;
      for (var option in options) {
        if (options.hasOwnProperty(option)) {
          if(options[option].value === optionVal[optionVal.length - 1]){
            options[option].disabled = false;
          }
        }
      }
      $j(this).parent().parent().remove();
    });
  },

  updateCompany: function () {
    if($j('#salesperson-company-container .edit-company').length > 0){
      var self = this;
      $j('#salesperson-company-container .edit-company').on('click', function(e) {
        e.preventDefault();
        var companyId = $j(this).attr('href').substring(1);
        self.postData({id:companyId}, 'korvlador', 'get_company', self.applyDataToCompanyForm);
      });
    }

    if($j('#salesperson-company-container .glyphicon').length > 0){
      $j('#salesperson-company-container .glyphicon').on('click', function(e) {
        if($j(this).hasClass('glyphicon-chevron-down')){
          $j(this).removeClass('glyphicon-chevron-down');
          $j(this).addClass('glyphicon-chevron-up');
        }
        else {
          $j(this).removeClass('glyphicon-chevron-up');
          $j(this).addClass('glyphicon-chevron-down');
        }
        $j(this).parent().next().toggle();
      });
    }
  },



  applyDataToCompanyForm: function(res) {
    if(res){
      var company = JSON.parse(res);
      var optionValues = {};

      $j('select[name="company-korvlada"] option').each(function() {
        this.selected = this.value === '' ? true : false;
        optionValues[this.value] = this.text;
      });
      $j('.new-company-korvlade-list').empty();

      for (var key in company) {
        if (company.hasOwnProperty(key)) {
          if( /korvlada-/.test( key ) ){
            var korvladaId = key.split('-')[1];
            if(Object.keys(optionValues).indexOf(korvladaId) !== -1){
              var html = '<div class="panel panel-default">'+
                            '<div class="panel-body">'+
                              '<input class="" type="text" disabled name="" value="'+optionValues[korvladaId]+'">'+
                              '<input class="company-korvlada-amount" type="number" name="company-korvlada-nbr-'+korvladaId+'" value="'+company[key]+'"> st.'+
                              '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'+
                            '</div>'+
                          '</div>';
              $j(html).appendTo('.new-company-korvlade-list');
              $j('.company-customer-form select[name="company-korvlada"] option[value="'+korvladaId+'"]').prop('disabled', true);
            }
          }
          else {
            var words = key.replace(/([A-Z])/g, ' $1').split(' ');
            var name = words[0] + '_';
            words.splice(0,1);
            words[0] = words[0].toLowerCase();
            name += words.join('');

            $j('.company-customer-form input[name="'+name+'"]').val(company[key]);
          }
        }
      }
    }
  },



  getNiceData: function (formData) {
    var formDataNice = {};
    for (var variable in formData) {
      if (formData.hasOwnProperty(variable)) {
        formDataNice[formData[variable].name] = formData[variable].value;
      }
    }

    return formDataNice;
  },


  getNonce: function(ctrl, method) {
    return $j.ajax({
      url: '../api/get_nonce/?controller=' + ctrl + '&method=' + method,
      type: 'GET'
    });
  },

  postData: function(formData, ctrl, method, callback) {
    var self = this;

    this.getNonce(ctrl, method)
      .done(function(res) {
        formData.nonce = res.nonce;
        self.doAjax(formData, ctrl, method, callback);
      });

  },

  doAjax: function(data, controller, method, callback) {
    var self = this;
    $j.ajax({
        url: '../api/' + controller + '/' + method,
        type: 'GET',
        data: data,
      })
      .done(function(msg) {
        console.log('done:');
        console.log(msg);
        console.log('');
        if(callback){
          console.log('callback');
          callback(msg);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR + '    ' + textStatus + '    ' + errorThrown);
      });

  }

};
