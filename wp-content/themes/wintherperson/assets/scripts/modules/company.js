var company = {

  init: function() {
    this.addCompany();
  },

  addCompany: function() {
    if($j('.doAddCompany').length > 0){
      var self = this;
      $j('.doAddCompany').click(function(e) {
        e.preventDefault();
        data = self.getNiceData($j(this).parent().serializeArray());
        console.log(data);
        var formData = {
					type: 'companies',
					status: 'pending',
					title: data['company-name'],
				};
        self.postData($j(this).parent().serialize(), formData, 'posts', 'create_post');
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

  postData: function(query, formData, ctrl, method) {
    var self = this;

    this.getNonce(ctrl, method)
      .done(function(res) {
        formData.nonce = res.nonce;
        self.doAjax(query, formData, ctrl, method);
      });

  },

  doAjax: function(query, data, controller, method, callback) {
    $j.ajax({
        url: '../api/' + controller + '/' + method + '/?'+ query,
        type: 'GET',
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
