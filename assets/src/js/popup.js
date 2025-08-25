// import Mediaquery from 'formstone/jquery/mediaquery';
import Modal from 'formstone/jquery/modal';

(function($, window) {
  
  window.PopBlocks = {
    
    // PopBlocks.open(id);
    open: function(id) {
      $('.popblocks-trigger[data-popblocks-id="' + id + '"]').modal('open');
    },
    
    // PopBlocks.close();
    close: function() {
      $('.popblocks-trigger').modal('close');
    },
    
  };
  
  // 
  
  function handlePageLoad($el, id, data, group) {
    let time = parseInt(group.rules[0].value, 10);
          
    console.log('Page Load: ', group.rules[0].value, time);
    
    setTimeout(function() { PopBlocks.open(id); }, time * 1000);
  }
  
  // 
    
  $(document).ready(function() {
    
    // TODO: Check firing rules  
    // TODO: Check cookies?
    
    let $triggers = $('.popblocks-trigger');
    
    $triggers.modal({
      // TODO: plugin settings as defaults?
    });
    
    $triggers.each((i, el) => {
      let $el = $(el);
      let id = $el.data('popblocks-id');
      let data = $el.data('popblocks-data');
      
      console.log(id, data);
      
      $.each(data.triggerGroups, (ti, group) => {
        
        // Page Load
        if (group.rules[0].type == 'page_load') {
          handlePageLoad($el, id, data, group);
        }
        
      });
    });
    
  });
    
})(jQuery, window);