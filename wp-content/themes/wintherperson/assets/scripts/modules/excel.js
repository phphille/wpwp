var excel = {

  init: function() {
    this.uploadExcel();
  },

  uploadExcel: function() {
    if($j('.doUploadExcel').length > 0){
      var self = this;
      $j('.doUploadExcel').click(function(e) {
        e.preventDefault();

        self.getNonce('user', 'create_user')
          .done(function(res) {
            var formData = new FormData($j('.doUploadExcel').parent()[0]);
            // formData.nonce = res.nonce;
            $j.ajax({
              url: '../api/excel/create_user_by_excel', //Server script to process data
              type: 'POST',
              data: formData,
              //Options to tell jQuery not to process data or worry about content-type.
              cache: false,
              contentType: false,
              processData: false,
            })
            .done(function(res){
              console.log(res);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR + '    ' + textStatus + '    ' + errorThrown);
            });
          });

      });
    }
    // $(':file').change(function(){
    //   var file = this.files[0];
    //   var name = file.name;
    //   var size = file.size;
    //   var type = file.type;
    //   //Your validation
    // });
  },


  getNonce: function(ctrl, method) {
    return $j.ajax({
      url: '../api/get_nonce/?controller=' + ctrl + '&method=' + method,
      type: 'GET'
    });
  },

  postData: function(formData, ctrl, method) {
    var self = this;
    // var formDataNice = {};
    // for (var variable in formData) {
    //   if (formData.hasOwnProperty(variable)) {
    //     formDataNice[formData[variable].name] = formData[variable].value;
    //   }
    // }

    if (method === 'create_user') {
      this.getNonce(ctrl, method)
        .done(function(res) {
          self.doAjax(res.nonce, formData, ctrl, method);
        });
    } else {
      self.doAjax('', formData, ctrl, method);
    }

  },

  doAjax: function(nonce, dataString, controller, method, callback) {
    $j.ajax({
        url: '../api/' + controller + '/' + method + '/?' + dataString,
        type: 'GET',
        data: nonce,
      })
      .done(function(msg) {
        console.log('done: ' + msg);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR + '    ' + textStatus + '    ' + errorThrown);
      });

  }

};
