$(document).ready(function (params) {
   $("#customer_form").validate({
    errorPlacement: function errorPlacement(error, element) {
        element.after(error);
    },
    errorElement: 'p',
    rules: {
        email: {
            required: true,
            email:true,
            remote: {
                url: site_url+'registerExistingEmail',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                type: 'post',
                data: {
                    email: function () {
                        return $('#email').val();
                    },
                    id: function () {
                        return $('#id').val();
                    }
                }
            }
        },
        password: {
            required: true,
            // password_check: true,
            minlength: 3,
        },
        /*confirm: {
            required:true,
            equalTo: "#password"
        },*/
        first_name:{
            required:true,
            minlength: 2
        },
        last_name:{
            required:false,
        },

        line_1:{
            required:true,
        },
        country:{
            required:true,
        },
        
    },
    messages: {
      email: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter email',
          email: '<i class="fa fa-exclamation-triangle"></i>  Please enter valid email',
      },
      password: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter Password',
          minlength: '<i class="fa fa-exclamation-triangle"></i> Please enter minimum 2 digit password',
          //password_check: '<i class="fa fa-exclamation-triangle"></i> '
      },
      /*confirm: {
        required:'<i class="fa fa-exclamation-triangle"></i> ',
        equalTo: '<i class="fa fa-exclamation-triangle"></i> '
    },*/
    first_name: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter First name',
          minlength: '<i class="fa fa-exclamation-triangle"></i> Please enter minimum 3 character'
      },
      last_name: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter last name',
      },
      line_1:{
            required:'<i class="fa fa-exclamation-triangle"></i> Please enter Address',
      },
        country:{
            required:'<i class="fa fa-exclamation-triangle"></i> Please enter country',
        },
      /*mobile: {
          required: '<i class="fa fa-exclamation-triangle"></i> ',
          number: '<i class="fa fa-exclamation-triangle"></i> ',
          minlength: '<i class="fa fa-exclamation-triangle"></i> ',
          maxlength: '<i class="fa fa-exclamation-triangle"></i> ',
          mobileCheck: '<i class="fa fa-exclamation-triangle"></i> ',
      },
      dob: {
          required: '<i class="fa fa-exclamation-triangle"></i> ',
      },*/
  }
});

$("#customer_edit").validate({
    errorPlacement: function errorPlacement(error, element) {
        element.after(error);
    },
    errorElement: 'p',
    rules: {
        email: {
            required: true,
            email:true,
            remote: {
                url: site_url+'registerExistingEmail',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                type: 'post',
                data: {
                    email: function () {
                        return $('#email').val();
                    },
                    id: function () {
                        return $('#id').val();
                    }
                }
            }
        },
       /* password: {
            required: true,
            // password_check: true,
            minlength: 3,
        },
        confirm: {
            required:true,
            equalTo: "#password"
        },*/
        first_name:{
            required:true,
            minlength: 2
        },
        last_name:{
            required:false,
        },

        line_1:{
            required:true,
        },
        country:{
            required:true,
        },
        
    },
    messages: {
      email: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter email',
          email: '<i class="fa fa-exclamation-triangle"></i>  Please enter valid email',
      },
      password: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter Password',
          minlength: '<i class="fa fa-exclamation-triangle"></i> Please enter minimum 2 digit password',
          //password_check: '<i class="fa fa-exclamation-triangle"></i> '
      },
      /*confirm: {
        required:'<i class="fa fa-exclamation-triangle"></i> ',
        equalTo: '<i class="fa fa-exclamation-triangle"></i> '
    },*/
    first_name: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter First name',
          minlength: '<i class="fa fa-exclamation-triangle"></i> Please enter minimum 3 character'
      },
      last_name: {
          required: '<i class="fa fa-exclamation-triangle"></i> Please enter last name',
      },
      line_1:{
            required:'<i class="fa fa-exclamation-triangle"></i> Please enter Address',
      },
        country:{
            required:'<i class="fa fa-exclamation-triangle"></i> Please enter country',
        },
      /*mobile: {
          required: '<i class="fa fa-exclamation-triangle"></i> ',
          number: '<i class="fa fa-exclamation-triangle"></i> ',
          minlength: '<i class="fa fa-exclamation-triangle"></i> ',
          maxlength: '<i class="fa fa-exclamation-triangle"></i> ',
          mobileCheck: '<i class="fa fa-exclamation-triangle"></i> ',
      },
      dob: {
          required: '<i class="fa fa-exclamation-triangle"></i> ',
      },*/
  }
});

})