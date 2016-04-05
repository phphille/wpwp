var user = {

  init: function() {
    this.createUser();
    this.updateUser();
    this.deleteUser();
    this.updateLoggedInUser();
  },

  createUser: function() {
    if ($j('.doCreateUser').length > 0) {
      var self = this;
      $j('.doCreateUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serialize(), 'user', 'create_user');
      });
    }
  },

  updateUser: function(){
    if ($j('.doUpdateUser').length > 0) {
      var self = this;
      $j('.doUpdateUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serialize(), 'user', 'update_user');

      });
    }
  },

  updateLoggedInUser: function(){
    if ($j('.doUpdateLoggedInUser').length > 0) {
      var self = this;
      $j('.doUpdateLoggedInUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serialize(), 'user', 'update_loggedin_user');

      });
    }
  },

  deleteUser: function(){
    if ($j('.doDeleteUser').length > 0) {
      var self = this;
      $j('.doDeleteUser').on('click', function(e){
        e.preventDefault();
        self.postData($j(this).parent().serialize(), 'user', 'delete_user');

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

    if(method === 'create_user'){
      this.getNonce(ctrl, method)
      .done(function(res){
        self.doAjax(res.nonce, formData, ctrl, method);
      });
    }
    else {
      self.doAjax('', formData, ctrl, method);
    }

  },

  doAjax: function(nonce, dataString, controller, method, callback){
    $j.ajax({
      url: '../api/'+controller+'/'+method+'/?'+dataString,
      type: 'GET',
      data: nonce,
    })
    .done(function(msg) {
      console.log('done: ' +msg);
    })
    .fail(function(jqXHR, textStatus, errorThrown ){
      console.log(jqXHR + '    ' + textStatus + '    ' + errorThrown);
    });

  }

};
