var user = {

  init: function() {
    this.createUser();
    this.updateUser();
    this.deleteUser();
    this.updateLoggedInUser();
    this.updateSalesmansSales();
  },

  createUser: function() {
    if ($j('.doCreateUser').length > 0) {
      var self = this;
      $j('.doCreateUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serializeArray(), 'user', 'create_user');
      });
    }
  },

  updateUser: function(){
    if ($j('.doUpdateUser').length > 0) {
      var self = this;
      $j('.doUpdateUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serializeArray(), 'user', 'update_user');

      });
    }
  },

  updateLoggedInUser: function(){
    if ($j('.doUpdateLoggedInUser').length > 0) {
      var self = this;
      $j('.doUpdateLoggedInUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serializeArray(), 'user', 'update_loggedin_user');

      });
    }
  },

  deleteUser: function(){
    if ($j('.doDeleteUser').length > 0) {
      var self = this;
      $j('.doDeleteUser').on('click', function(e){
        e.preventDefault();
        // console.log($j(this).parent().serializeArray());
        self.postData($j(this).parent().serializeArray(), 'user', 'delete_user');

      });
    }
  },


  updateSalesmansSales: function(){
    if ($j('.doUpdateSales').length > 0) {
      var self = this;
      $j('.doUpdateSales').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serializeArray(), 'user', 'update_salesmans_sales_status');

      });
    }
  },


  getNonce: function(ctrl, method) {
		return $j.ajax({
			url: '../api/get_nonce/?controller='+ctrl+'&method='+method,
			type: 'GET'
		});
	},

  postData: function(formData, ctrl, method){
    var self = this;
    // var formDataNice = {};
    // for (var variable in formData) {
    //   if (formData.hasOwnProperty(variable)) {
    //     formDataNice[formData[variable].name] = formData[variable].value;
    //   }
    // }
    this.getNonce(ctrl, method)
    .done(function(res){
      formData.push({name:'nonce', value: res.nonce});
      self.doAjax(formData, ctrl, method);
    });

  },

  doAjax: function(data, controller, method, callback){
    $j.ajax({
      url: '../api/'+controller+'/'+method,
      type: 'GET',
      data: data,
    })
    .done(function(msg) {
      console.log('done:');
      console.log(msg);
      console.log('');
    })
    .fail(function(jqXHR, textStatus, errorThrown ){
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    });

  }

};
