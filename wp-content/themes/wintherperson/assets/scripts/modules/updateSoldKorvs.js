var updateSoldKorvs = {

  init: function() {
    this.updateKorvs();
  },

  updateKorvs: function() {
    if($j('.updateSoldKorv').length > 0){
      var self = this;
      $j('.updateSoldKorv').click(function(e) {
        e.preventDefault();
        data = self.getNiceData($j(this).parent().serializeArray());
        console.log(data);
        self.postData(data, 'user', 'update_sold_korvs');
      });
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

  postData: function(formData, ctrl, method) {
    var self = this;

    this.getNonce(ctrl, method)
      .done(function(res) {
        formData.nonce = res.nonce;
        self.doAjax(formData, ctrl, method);
      });

  },

  doAjax: function(data, controller, method) {
    $j.ajax({
        url: '../api/' + controller + '/' + method,
        type: 'POST',
        data: data,
      })
      .done(function(msg) {
        console.log('done:');
        console.log(msg);
        console.log('');
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR + '    ' + textStatus + '    ' + errorThrown);
      });

  }

};
