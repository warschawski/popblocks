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

  function handleIdleTime(id, group) {
    let idleTime = 0;
    let adminIdleTime = group.rules[0].value;
    let idleInterval;

    $(document).ready(function () {
      // Increment the idle time counter every second.
      idleInterval = setInterval(timerIncrement, 1000);

      $(this).mousemove(function (e) {
          idleTime = 0;
      });
      $(this).keypress(function (e) {
          idleTime = 0;
      });
    });

    function timerIncrement() {
      idleTime++;
      
      if (idleTime > adminIdleTime) {
        PopBlocks.open(id);
        clearInterval(idleInterval);
      }
    }
  }

  function handleExitIntent(id) {
    let $window = $(window);
    let $body = $('body');
    let mouseY;
  
    $body.on('mouseleave', (e) => {
      mouseY = e.clientY;
  
      if (mouseY < 0) {
        console.log('Exit Intent');
        $window.trigger('exitintent');
      }
    });
  
    $window.on('exitintent', () => {
      PopBlocks.open(id);
    });
  }
  
  // 
    
  $(document).ready(function() {
    
    // TODO: Check firing rules  
    // TODO: Check cookies?
    
    let $triggers = $('.popblocks-trigger');
    
    $triggers.modal({
      // TODO: plugin settings as defaults?
      returnFocus: false,
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

        if (group.rules[0].type == 'idle_time') {
          handleIdleTime(id, group);
        }
        
        if (group.rules[0].type == 'exit_intent') {
          handleExitIntent(id);
        }
        
      });
    });
    
  });
    
})(jQuery, window);