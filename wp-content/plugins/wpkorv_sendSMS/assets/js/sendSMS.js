(function($) {
  $(document).ready(function() {
    var adminExcel = {

      init: function() {
        this.sendSMS();
      },

      sendSMS: function() {
        if ($(".adminSendSms").length > 0) {
          var self = this;
          $(".adminSendSms").on('click', function(e) {
            e.preventDefault();
            // var r = confirm("Du kommer nu att skicka meddelandet.");
            // if (r == true) {
              var data = $(this).parent().parent().serialize();
              data+= '&action=wpkorv_sendSMS';
              $.post('/wpkorv/wp-admin/admin-ajax.php', data, function(response) {
                console.log('success');
                console.log(response);
              })
              .fail(function() {
                console.log('error');
              })
            // }

          });
        }
      },


      getNonce: function(ctrl, method) {
    		return $.ajax({
    			url: '../api/get_nonce/?controller='+ctrl+'&method='+method,
    			type: 'GET'
    		});
    	},

      postData: function(formData, ctrl, method){
        var self = this;

        this.getNonce(ctrl, method)
        .done(function(res){
          formData.push({name:'nonce', value: res.nonce});
          self.doAjax(formData, ctrl, method);
        });

      },

      doAjax: function(data, controller, method, callback){
        $.ajax({
          url: '../api/'+controller+'/'+method,
          type: 'GET',
          data: data,
        })
        .done(function(msg) {
          console.log('done:');
          console.log(msg);
          $('#adminExcelTable').remove();
          $(msg).appendTo('#wpbody-content .wrap');
        })
        .fail(function(jqXHR, textStatus, errorThrown ){
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
        });

      }

    };

    adminExcel.init();


  });

})(jQuery); // Fully reference jQuery after this point.
