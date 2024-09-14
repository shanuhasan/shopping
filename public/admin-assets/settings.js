$(document).ready(function (params) {
    $("#settings_form").validate({
        errorPlacement: function errorPlacement(error, element) {
            console.log(element)
            element.after(error);
        },
        errorElement: 'p',
        rules: {
            "site_settings[site_name]": {
                required: true,
            },
            "site_settings[site_title]": {
                required: true,
            },
            "site_settings[email]": {
                required: true,
            },
            "site_settings[phone]": {
                required: true,
            },
            "site_settings[line_1]": {
                required: true,
            },
            "site_settings[country]": {
                required: true,
            },
            "site_settings[header_logo]": {
                required: true,
            },
            "site_settings[footer_logo]": {
                required: true,
            }
        },
        messages: {
            "site_settings[site_name]": {
                required: '<i class="fa fa-exclamation-triangle"></i> Please enter site name',
          },
          "site_settings[site_title]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter site title',
          },
          "site_settings[email]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter email address',
          },
          "site_settings[phone]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter phone',
          },
          "site_settings[line_1]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter address',
          },
          "site_settings[country]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter Country',
          },
          "site_settings[header_logo]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter header logo',
          },
          "site_settings[footer_logo]": {
              required:  '<i class="fa fa-exclamation-triangle"></i> Please enter footer logo',
          }
        }
    }); 
 })