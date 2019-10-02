var app = new Vue({
    el: '#divWpVue',
    data: {
    },
    template: "#maps_verification_wizzard",
    methods: {
      nextPage (btn) {
        var page = jQuery(btn).closest('.spa-page').attr('data-page');
        jQuery('.spa-page.current').removeClass('.current');
      }
    }
  });