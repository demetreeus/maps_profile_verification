var app = new Vue({
  el: '#divWpVue',
  data: {
    ajax_url: maps_ajax.url // localized by plugin
  },
  template: "#maps_verification_wizzard",
  methods: {
    openWizzard() {
      console.log('open wizzard');
      jQuery('.um-profile').addClass('hide');
      jQuery('#colophon').addClass('hide');
      jQuery('#wizzard').addClass('open');
      jQuery(".spa-page").removeClass('current');
      jQuery(".spa-page[data-page='1']").addClass('current');
    },
    closeWizzard() {
      jQuery('.um-profile').removeClass('hide');
      jQuery('#colophon').removeClass('hide');
      jQuery('#wizzard').removeClass('open');
      jQuery("input[name^='user_verified_2']").prop('checked', false).trigger('change');
    },
    nextPage() {
      var page = jQuery('.spa-page.current').attr('data-page');
      var next = parseInt(page) + 1;
      jQuery('.spa-page.current').removeClass('current');
      jQuery('.spa-page[data-page="' + next + '"]').addClass('current');
      jQuery('html,body').animate({ scrollTop: 0 }, 'slow');
    },
    previousPage(ev) {
      ev.preventDefault();
      var page = jQuery('.spa-page.current').attr('data-page');
      var previous = parseInt(page) - 1;
      if (previous === 0) {
        this.closeWizzard();
      } else {
        jQuery('.spa-page.current').removeClass('current');
        jQuery('.spa-page[data-page="' + previous + '"]').addClass('current');
      }
    },
    onInfo(ev) {
      var errors = this.validateInfo();
      if (errors.length) {
        this.onInfoFormError(errors);
      } else {
        this.onUploadInfo();
      }
    },
    onInfoFormError(errors) {
      for (var i = 0; i < errors.length; i++) {
        jQuery('input[name="' + errors[i] + '"]').closest('.form-group').addClass('warning');
      }
    },
    validateInfo() {
      // replace this with a validation lib
      var fields = [
        'maps_company_name',
        'maps_firstname',
        'maps_lastname',
        'maps_phone_number'
      ];

      var errors = [];

      for (var i = 0; i < fields.length; i++) {
        if (jQuery('input[name="' + fields[i] + '"]').val().length == 0) {
          errors.push(fields[i]);
        }
      }
      console.log(errors);
      return errors;
    },
    onUploadInfo() {
      var formData = new FormData(jQuery("#maps_verification_form")[0]);

      startUploadAnimation();
      axios.post(this.$data.ajax_url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(function (response) {
          if (response.error) {
            this.onUploadError();
            return;
          }
          console.log(response);

          if (response.status) {
            app.nextPage();
          }
        })
        .catch(function (error) {
          console.log(error)
        });
    },
    onUploadDocument() {
      if (document.getElementById("document_input").files.length == 0) {
        console.log('nothing to do');
        return false;
      }

      var formData = new FormData();
      formData.set('action', "maps_document");
      let file = document.getElementById('document_input').files[0];
      formData.append('document', file);

      startUploadAnimation();
      axios.post(this.$data.ajax_url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(function (response) {
          if (response.error) {
            //console.log('response', response.error);
            console.log(response.error);
            return;
          }
          console.log(response);
          //login com sucesso 
          if (response.status) {

            //only reload or redirect too?
            app.onDocumentUploaded(response.data.file);
          }
        })
        .catch(function (error) {
          console.log(error)
        });
    },
    onDocumentUploaded(file) {
      jQuery('#upload_button, #pay_button').toggleClass('hide');
      stopUploadAnimation();
      if (file.type.includes('image')) {
        showDocument(file.url);
      }
    },
    onUploadError() {

    },
    onSuccess() {
      jQuery('.spa-page').removeClass('current');
      jQuery('#page-thanks').addClass('current');
    },
    onFailure() {
      jQuery('.spa-page').removeClass('current');
      jQuery('#page-sorry').addClass('current');
    }
  }
});

function startUploadAnimation() {
  console.log('animation started');
}

function stopUploadAnimation() {
  console.log('animation stopped');
}

function showDocument(url) {
  jQuery("#document_placeholder").removeClass('hide');
  jQuery('#document_placeholder').find('img').attr('src', url);
}

(function ($) {
  $("input[name^='user_verified_2']").on('change', function () {
    if ($(this).is(':checked')) {
      app.openWizzard();
    }
  })

  $("#document_input").on('change', function () {
    if (document.getElementById("document_input").files.length == 0) {
      $('#upload_button').addClass('disabled');
    } else {
      $('#upload_button').removeClass('disabled');
    }
  })
})(jQuery);


// Create a Stripe client.
const stripe = Stripe('pk_test_8rnpHKKbC0tlLpPFyVPd6B5L00ho5jl80B');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', { style: style });
// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function (event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function (event) {
  event.preventDefault();

  stripe.createToken(card).then(function (result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  onPayment(form);
}

function onPayment(form) {
  var formData = new FormData(form);
  console.log(formData);
  axios.post(maps_ajax.url, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(function (response) {
      if (response.error) {
        //console.log('response', response.error);
        app.onFailure();
        return;
      }
      //login com sucesso 
      if (response.data.success) {
        app.onSuccess();
      } else {
        app.onFailure();
      }
    })
    .catch(function (error) {
      app.onFailure();
    });
}