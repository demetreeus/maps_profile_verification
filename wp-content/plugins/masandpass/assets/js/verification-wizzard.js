var app = new Vue({
    el: '#divWpVue',
    data: {
      ajax_url: maps_ajax.url // localized by plugin
    },
    template: "#maps_verification_wizzard",
    methods: {
      openWizzard () {
        console.log('open wizzard');
        jQuery('.um-profile').addClass('hide');
        jQuery('#colophon').addClass('hide');
        jQuery('#wizzard').addClass('open');
      },
      closeWizzard () {
        jQuery('.um-profile').removeClass('hide');
        jQuery('#colophon').removeClass('hide');
        jQuery('#wizzard').removeClass('open');
        jQuery("input[name^='user_verified_2']").prop('checked', false).trigger('change');
      },
      nextPage (ev) {
        ev.preventDefault();
        var page = jQuery('.spa-page.current').attr('data-page');
        var next = parseInt(page) + 1;
        console.log(next);
        jQuery('.spa-page.current').removeClass('current');
        jQuery('.spa-page[data-page="'+next+'"]').addClass('current');
      },
      previousPage (ev) {
        ev.preventDefault();
        var page = jQuery('.spa-page.current').attr('data-page');
        var previous = parseInt(page) - 1;
        if(previous === 0){
          this.closeWizzard();
        } else {
          jQuery('.spa-page.current').removeClass('current');
          jQuery('.spa-page[data-page="'+previous+'"]').addClass('current');
        }
      },
      onUploadDocument() {
        if (document.getElementById("document_input").files.length == 0){
          console.log('nothing to do');
          return false;
        }
      
        var formData = new FormData();
        formData.set('action', "maps_document");
        let file = document.getElementById('document_input').files[0];
        formData.append('document',file);
        
        startUploadAnimation();
        axios.post(this.$data.ajax_url, formData, {
              headers: {
                'Content-Type': 'multipart/form-data'
              }})
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

        stopUploadAnimation();
        if(file.type.includes('image')){
          showDocument(file.url);
        }
      }
    }
  });

  function startUploadAnimation(){
    console.log('animation started');
  }

  function stopUploadAnimation(){
    console.log('animation stopped');
  }

  function showDocument(url) {
    jQuery("#document_placeholder").removeClass('hide');
    jQuery('#document_placeholder').find('img').attr('src', url);
  }

  (function($){
    $("input[name^='user_verified_2']").on('change', function(){
      if($(this).is(':checked')){
        app.openWizzard();
      }
    })
    
    $("#document_input").on('change', function(){
      if(document.getElementById("document_input").files.length == 0){
        $('#upload_button').addClass('disabled');
      } else {
        $('#upload_button').removeClass('disabled');
      }
    })
  })(jQuery);