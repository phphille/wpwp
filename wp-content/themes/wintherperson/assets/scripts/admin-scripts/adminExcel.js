(function($) {
  $(document).ready(function() {
    var adminExcel = {

      init: function() {
        this.createUser();
      },

      createUser: function() {
        if ($(".admin-select-export-team").length > 0) {
          var self = this;
          $(".admin-select-export-team").on('change', function(e) {
            e.preventDefault();
            self.postData($(this).parent().serializeArray(), 'excel', 'get_table_for_excel_admin');
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
