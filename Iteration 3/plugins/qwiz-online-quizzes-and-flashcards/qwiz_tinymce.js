(function() {
   var debug = false;
   tinymce.create ('tinymce.plugins.qwiz_tinymce', {
      init : function (ed, url) {
         if (debug) {
            console.log ('[qwiz_tinymce > init] ed:', ed);
         }
         if (ed.id == 'content'
                      || jQuery (ed.targetElm).hasClass ('wp-editor-area')
                      || jQuery (ed.targetElm).hasClass ('wp-block-freeform')) {
            run_qwizzled (ed);
            ed.addButton ('button_q', {
               title:   'Qwizcards - show/restart editing menu',
               image:   qwizzled_params.url + 'images/icon_qwiz.png',
               onclick: function () {
                  if (typeof (qwizzled) == 'undefined') {
                     pre_qwizzled.load_qwizzled_if_needed (ed, true);
                  } else {
                     qwizzled.show_main_menu (ed, true);
                  }
               }
            });
         }
      },
      createControl : function (n, cm) {
         return null;
      },
   });
   tinymce.PluginManager.add ( 'qwizzled_button_script', tinymce.plugins.qwiz_tinymce );
   function run_qwizzled (ed) {
      function run_pre_qwizzled () {
         if (debug) {
            var msec = new Date ().getTime ();
            console.log ('qwiz_tinymce.js [run_pre_qwizzled] msec', msec);
         }
         if (typeof (pre_qwizzled) == 'undefined') {
            setTimeout (run_pre_qwizzled, 10);
         } else {
            pre_qwizzled.load_qwizzled_if_needed (ed, false);
         }
      }
      setTimeout (run_pre_qwizzled, 10);
   }
})();
