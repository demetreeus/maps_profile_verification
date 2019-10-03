(function ($) {
  $(document).on('click', '#verify_user',function(){
      var date = new Date().toJSON().slice(0, 19).replace('T', ' ');
      $("#user_verified_at").val(date);
  })
})(jQuery);
