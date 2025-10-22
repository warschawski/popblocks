import Modal from 'formstone/jquery/modal';
import Cookie from 'formstone/jquery/cookie';

(function($, window) {

  let userLanguage = navigator.language.split('-')[0];

  //

  if (!navigator.geolocation) {
    return;
  }

  let location = null;
  let cached = window.sessionStorage.getItem('user-community-spotlight');
  let geo = $.cookie('get', 'user-geo');

  try {
    cached = JSON.parse(window.atob(cached));
  } catch(e) {}

  try {
    location = JSON.parse(geo);
  } catch(e) {}

  if (location) {
     console.log(location);
  } else {
    console.log('Popblocks: Get Geo');

    navigator.geolocation.getCurrentPosition(onGetPosition);
  }

  function onGetPosition(pos) {
    console.log(pos);

    let location = {
      lat: pos.coords.latitude,
      lng: pos.coords.longitude,
    };

    $.cookie('set', 'user-geo', JSON.stringify(location), {
      path: '/',
      expires: (1000 * 60 * 60 * 24), // 24 hours
    });
  }

  //
  
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
  
  let scrollPercent = 0;
  let scrollCollection = [];
  let visibilityCollection = [];
  
  // 

  function getSelector(selector, operator) {
    if (operator === 'element_class') {
      selector = '.' + selector;
    } else if (operator === 'element_id') {
      selector = '#' + selector;
    }
  
    return $(selector);
  }

  //

  function verifyBehaviorAndCookie(id) {
    let data = $(`[data-popblocks-id="${id}"]`).data('popblocks-data');

    // 1. Check cookie
    let cookieActive = data.options.active;

    if (cookieActive) {
      let cookieName = data.options.name;
      let cookie = $.cookie('get', cookieName);

      if (cookie) {
        return false; // User has seen the popup
      }
    }

    // 2. Verify Url and Behavior 
    let pass = false;

    for (group of data.behaviors) {
      let groupPass = true;

      for (rule of group.rules) {
        let rulePass = false;

        if (rule.type === 'browser_language') {
          //console.log(userLanguage.toLowerCase(), rule.value.toLowerCase());

          if (rule.operator === 'equals') {
            rulePass = ( userLanguage.toLowerCase() === rule.value.toLowerCase() );
          } else {
            rulePass = ( userLanguage.toLowerCase() !== rule.value.toLowerCase() );
          }

        } else if (rule.type === 'url') {
          let url = window.location.href;
          
          if (rule.operator === 'contains') {
            rulePass = url.includes( rule.value.toLowerCase() );
          } else {
            rulePass = !url.includes( rule.value.toLowerCase() );
          }
          
        } else {
          rulePass = true;
        }

        console.log('rule', rule, rulePass);

        if (!rulePass) {
          groupPass = false;
          break;
        }
      }

      console.log('group', group, groupPass);

      if (groupPass) {
        pass = true;
        break;
      }
    }

    console.log('pass', pass);

    return pass; // Cookie is not active on current popup
  }

  //

  function handlePageLoad(id, group) {
    let time = parseInt(group.rules[0].value, 10);
          
    console.log('Page Load: ', group.rules[0].value, time);
    
    setTimeout(function() { 
      if (verifyBehaviorAndCookie(id)) {
        PopBlocks.open(id);
      } 
    }, time * 1000);
  }

  function handleIdleTime(id, group) {
    let idleTime = 0;
    let adminIdleTime = group.rules[0].value;
    let idleInterval;

    console.log(group.rules[0])

    $(document).ready(function () {
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
        if (verifyBehaviorAndCookie(id)) {
          PopBlocks.open(id);
        }

        clearInterval(idleInterval);
      }
    }
  }

  function handleExitIntent(id, group) {
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
      if (verifyBehaviorAndCookie(id)) {
        PopBlocks.open(id);
      }
    });
  }

  function handleClick(id, group) {
    let selector = group.rules[0].value.trim();
    let operator = group.rules[0].operator;

    let $selector = getSelector(selector, operator);
    
    $selector.on('click', (e) => {
      if (verifyBehaviorAndCookie(id)) {
        PopBlocks.open(id);
      }
    });
  }
  
  function handleHover(id, group) {
    let selector = group.rules[0].value.trim();
    let operator = group.rules[0].operator;

    let $selector = getSelector(selector, operator);
    
    $selector.on('mouseover', (e) => {
      if (verifyBehaviorAndCookie(id)) {
        PopBlocks.open(id);
      }
    });
  }

  function handleElementVisibility(id, group) {
    visibilityCollection.push({
      id: id, 
      selector: group.rules[0].value.trim(),
      operator: group.rules[0].operator,
      opened: false,
    });
    window.requestAnimationFrame(onRAF);
  }

  function handleScroll(id, group) {
    scrollCollection.push({
      id: id, 
      percent: parseInt(group.rules[0].value, 10),
      opened: false,
    });
    window.requestAnimationFrame(onRAF);
  }

  //

  function onRAF() {
    let doRAF = false;

    // Check Scroll 

    $.each(scrollCollection, (index, item) => {
      if (!item.opened && scrollPercent >= item.percent) {
        if (verifyBehaviorAndCookie(id)) {
          PopBlocks.open(item.id);
        }

        item.opened = true;
      } else {
        doRAF = true;
      }
    });

    // Check Visibilty
    $.each(visibilityCollection, (index, item) => {
      if (!item.opened) {
        let $targets = getSelector(item.selector, item.operator);

        if (isElementInViewport($targets[0])) {
          if (verifyBehaviorAndCookie(id)) {
            PopBlocks.open(item.id);
          }

          item.opened = true;
        } else {
          doRAF = true;
        }
      } else {
        doRAF = true;
      }
    });

    if (doRAF) {
      window.requestAnimationFrame(onRAF);
    }
  }

  function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();

    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function convertDurationToHours(value, unit) {
    const map = { months: 730, weeks: 168, days: 24, hours: 1 };
    return value * (map[unit] || 1);
  }
  
  // 
    
  $(document).ready(function() {

    const triggerHandlers = {
      page_load: handlePageLoad,
      idle_time: handleIdleTime,
      exit_intent: handleExitIntent,
      click: handleClick,
      hover: handleHover,
      element_visibility: handleElementVisibility,
      scroll: handleScroll,
    };
    
    let $triggersHandles = $('.popblocks-trigger');

    console.log($triggersHandles)
    
    $triggersHandles.modal({
      // TODO: plugin settings as defaults?
      returnFocus: false,
    });
    
    $triggersHandles.each((i, el) => {
      let $el = $(el);
      let id = $el.data('popblocks-id');
      let data = $el.data('popblocks-data');

      const cookieActive = data.options.active;
      const cookieName = data.options.name;

      initTriggers(id, data.triggers);
    });

    function initTriggers(id, triggers) {
      $.each(triggers, (ti, group) => {
        let type = group.rules?.[0]?.type;
        let handler = triggerHandlers[type];
        if (handler) handler(id, group);
      });
    }
    
  });

  // 

  $(window).on('scroll', function() {
    const scrollTop = $(window).scrollTop();
    const docHeight = $(document).height() - window.innerHeight;
    
    scrollPercent = (scrollTop / docHeight) * 100;
  });

  // 

  $(window).on('modal:open', (e) => {
    // cookie checker
    let data = $(e.originalEvent.detail.el).data('popblocks-data');
    console.log('open', data);
    
    const cookieActive = data.options.active;

    if (cookieActive) {
      const cookieName = data.options.name;
      const cookieDuration = data.options.duration;
      const cookieDurationHours = convertDurationToHours(cookieDuration.value, cookieDuration.unit);

      $.cookie('set', cookieName, true, {
        path: '/',
        expires: (1000 * 60 * 60 * cookieDurationHours),
      });

      console.log(cookieActive, $.cookie('get', cookieName));
    }
  });
    
})(jQuery, window);