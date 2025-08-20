import Alpine from 'alpinejs';
import component from 'alpinejs-component'

window.Alpine = Alpine;

Alpine.plugin(component)

/*


1. User adds row
2. User selects trigger type
  - Update Opperator options
3. User enters value

// Trigger Structures

triggers = [
  {
    "name": "Page Load",
    "value": "page_load",
    "opperators": [
      "delay"
    ],
    "type": "text"
  },
  {
    "name": "Idle Time",
    "value": "idle_time",
    "opperators": [
      "delay"
    ],
    "type": "text"
  },
  {
    "name": "Click",
    "value": "click",
    "opperators": [
      "element_id",
      "element_class"
    ],
    "type": "text"
  }
]


1. User adds row
2. User selects behavior type
  - Update Opperator options
  - Update Value options
3. User selects opperator
4. User selects value

behaviors = [
  {
    "name": "Post",
    "value": "post_id",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "dynamic_select",
    "load_callback": "admin-ajax.php?action=popups_load_posttypes"
  },
]


opperators = [
  {
    "id": "delay",
    "name": "Delay",
  },
  {
    "id": "element_id",
    "name": "Element ID",
  },
  {
    "id": "element_class",
    "name": "Element Class",
  },
  {
    "id": "equals",
    "name": "Equals",
  },
  {
    "id": "not_equals",
    "name": "Not Equals",
  }
]


// Data Structures

{
  "groups": [
    {
      "rules": [
        {
          "param": "post_type",
          "opperator": "equals",
          "value": "post",
        },
        {
          "param": "post_type",
          "opperator": "equals",
          "value": "post",
        }
      ]
    },
    {
      "rules": [
        {
          "param": "post_type",
          "opperator": "equals",
          "value": "post",
        },
        {
          "param": "post_type",
          "opperator": "equals",
          "value": "post",
        }
      ]
    }
  ]
}

*/


let TRIGGER_CONFIG = [
  {
    "name": "Page Load",
    "value": "page_load",
    "opperators": [
      "delay"
    ],
    "type": "text"
  },
  {
    "name": "Exit Intent",
    "value": "exit_intent",
    "opperators": [
      "delay"
    ],
    "type": "text"
  },
  {
    "name": "Idle Time",
    "value": "idle_time",
    "opperators": [
      "delay"
    ],
    "type": "text"
  },
  {
    "name": "Click",
    "value": "click",
    "opperators": [
      "element_id",
      "element_class"
    ],
    "type": "text"
  },
  {
    "name": "Hover",
    "value": "hover",
    "opperators": [
      "element_id",
      "element_class"
    ],
    "type": "text"
  },
  {
    "name": "Element visibility",
    "value": "element_visibility",
    "opperators": [
      "element_id",
      "element_class"
    ],
    "type": "text"
  },
  {
    "name": "Scroll",
    "value": "scroll",
    "opperators": [
      "scroll"
    ],
    "type": "text"
  }
];

let BEHAVIOR_CONFIG = [
  {
    "name": "Page",
    "value": "page",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Post",
    "value": "post",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Category",
    "value": "category",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Tag",
    "value": "tag",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Post Type",
    "value": "post_type",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Browser Location",
    "value": "browser_location",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  },
  {
    "name": "Browser Language",
    "value": "browser_language",
    "opperators": [
      "equals",
      "not_equals"
    ],
    "type": "text"
  }
];

let OPPERATOR_CONFIG = [
  {
    "id": "delay",
    "name": "Delay",
    "placeholder": "0 seconds",
  },
  {
    "id": "element_id",
    "name": "Element ID",
    "placeholder": "trigger_button",
  },
  {
    "id": "element_class",
    "name": "Element Class",
    "placeholder": "trigger_button22",
  },
  {
    "id": "scroll",
    "name": "Scroll",
    "placeholder": "100px",
  },
  {
    "id": "equals",
    "name": "Equals",
    "placeholder": "id",
  },
  {
    "id": "not_equals",
    "name": "Not Equals",
    "placeholder": "id",
  }
];

Alpine.store('popups', {
    triggerGroups: [],
    behaviorGroups: [],
    // Todo store for options?
    init: function() {
      console.log('Init: PopUps Store');
      
      console.log(window.PopUpsData);

      if (typeof window.PopUpsData !== 'undefined') {

        if (typeof window.PopUpsData.triggerGroups !== 'undefined') {
          this.triggerGroups = window.PopUpsData.triggerGroups;
          console.log(this.triggerGroups[0]);
        }

        if (typeof window.PopUpsData.behaviorGroups !== 'undefined') {
          this.behaviorGroups = window.PopUpsData.behaviorGroups;
          console.log(this.behaviorGroups);
        }
      }

      this.triggerOptions = TRIGGER_CONFIG;
      this.behaviorOptions = BEHAVIOR_CONFIG;
      this.opperatorOptions = OPPERATOR_CONFIG;
    },
});



window.popupsMetaBox = function(data) {

  function init() {
    console.log('Popups Meta Box: Init');
  }
  
  function finalData() {
    return JSON.stringify({
      triggerGroups: this.$store.popups.triggerGroups,
      behaviorGroups: this.$store.popups.behaviorGroups,
    });
  }

  function onTabActivated(e) {
    console.log('Popups Meta Box: Tab Activated', e.detail, e);
  }

  return {
    init: init,
    finalData: finalData,
    //
    onTabActivated: onTabActivated,
    //
    color: data.color,
    trigger: data.trigger,
  };
};

window.popupsTabs = function(data) {

  function setTab(tab) {
    this.activeTab = tab;

    this.$dispatch('tab-activated', this.activeTab);
  }

  function isActiveTab(tab) {
    return this.activeTab == tab;
  }

  return {
    activeTab: data.activeTab,
    setTab: setTab,
    isActiveTab: isActiveTab,
  }
};


window.ruleController = function(data) {
  let parentGroup = data.groupTab;
  let groupName = parentGroup.substring(0,2) + 'Groups';
  let optionsName = parentGroup + 'Options';

  function init() {
    console.log('Init: Rule Controller');

    this.trGroups = this.$store.popups.triggerGroups;
    this.beGroups = this.$store.popups.behaviorGroups;

    this.$watch('$store.popups.triggerGroups', (val) => {
      this.trGroups = this.$store.popups.triggerGroups;
    });
    this.$watch('$store.popups.behaviorGroups', (val) => {
      this.beGroups = this.$store.popups.behaviorGroups;
    });
  }

  function getBaseRule(groupName) {
    return {
      id: 'id' + (new Date()).getTime(),
      parent: groupName[0].rules[0].parent,
      type: groupName[0].rules[0].type,
      opperator: groupName[0].rules[0].opperator,
      value: groupName[0].rules[0].value,
    };
  }

  function createGroup() {
    this[groupName].push({
      id: 'id' + (new Date()).getTime(),
      rules: [
        getBaseRule(this[groupName]),
      ],
    });
  }

  function createRule(gIndex, rIndex) {
    this[groupName][gIndex].rules.splice(rIndex, 0, getBaseRule(this[groupName]));

    console.log(optionsName);
  }

  function removeRule(gIndex, rIndex) {
    this[groupName][gIndex].rules.splice(rIndex, 1);

    if (this[groupName][gIndex].rules.length == 0) {
      this[groupName].splice(gIndex, 1);
    }
  }

  function updateRuleType(rule) {
    let selectedTrigger = _.find(this.$store.popups[rule.parent + 'Options'], (trigger) => {
      return trigger.value == rule.type;
    });

    if (selectedTrigger.opperators.includes(rule.opperator)) {
      console.log(rule.opperator)
    } else {
      rule.opperator = selectedTrigger.opperators[0];
    }
  }

  function getOpperatorOptions(rule) {
    let selectedTrigger = _.find(this.$store.popups[rule.parent + 'Options'], (trigger) => {
      return trigger.value == rule.type;
    });

    return _.filter(this.$store.popups.opperatorOptions, (opperator) => {
      return selectedTrigger.opperators.indexOf(opperator.id) > -1;
    });
  }

  return {
    trGroups: [],
    beGroups: [],
    //
    init: init,
    createGroup: createGroup,
    createRule: createRule,
    removeRule: removeRule,

    updateRuleType: updateRuleType,
    getOpperatorOptions: getOpperatorOptions,

    groupName: groupName,
  };
}

Alpine.start();





// (function($, wp) {

//   var $document = $(document),
//       $attachment,
//       $image,
//       $actions,
//       $info,
//       $select,
//       $marker,
//       $preview,
//       loading = false,
//       imageID,
//       focalPoint,
//       allSizes,
//       imageBounds,
//       translations,
//       smartCache = false,
//       currentPage = 0;

//   $document.ready(function() {
//     if (typeof POPBLOCKS_TRANSLATION_DATA !== 'undefined') {
//       translations = POPBLOCKS_TRANSLATION_DATA;

//       $("body").on("click.popblocks", ".popblocks-set-focal-point", onSetFocalPointClick);
//       $("body").on("click.popblocks", ".popblocks-save-focal-point", onSaveFocalPointClick);
//       $("body").on("change.popblocks", ".popblocks-select-focal-point-preview", onSelectFocalPointPreview);

//       $("body").on("click.popblocks", ".popblocks-clear-cache", onClearCache);
//       // $("body").on("click.popblocks", ".popblocks-regenerate-cache", onRegenerateCache);

//       checkForImage();
//       setInterval(checkForImage, 500);
//     }
//   });

//   function checkForImage() {
//     if (!loading) {
//       try {
//         addFocalPoint();
//       } catch (e) {
//         loading = false;
//         console.log(e);
//       }
//     }
//   }

//   function addFocalPoint() {
//     $attachment = $(".attachment-details, .wp_attachment_image");
//     $image = $attachment.eq(0).find("img").eq(0);

//     if (!$image.length || $(".popblocks-focal-point-image").length) {
//       // console.log('return 1');
//       return;
//     }

//     $actions = $(".attachment-actions, .media-sidebar .details");
//     $info = $(".attachment-info .actions");

//     imageID = jQuery('input[name*="[popblocks_id]"]').val();

//     if (!imageID || imageID == '') {
//       // console.log('return 2');
//       return;
//     }

//     loading = true;

//     var data = {
//       action: "popblocks_get_focal_point",
//       image: imageID,
//     };

//     $.ajax({
//       type: 'GET',
//       url: ajaxurl,
//       data: data,
//       dataType: 'json',
//       success: onFocalPointLoaded
//     });

//     // console.log('loaded');
//   }

//   function onFocalPointLoaded(data) {
//     loading = false;
//     focalPoint = data.focalpoint;
//     allSizes = data.sizes;
//     smartCache = data.smart_cache;

//     var imagehtml = '',
//         actionhtml = '';

//     imagehtml += '<span class="popblocks-focal-point-marker"><span>' + translations.drag_focalpoint + '</span></span>';
//     imagehtml += '<span class="popblocks-focal-point-preview"></span>';

//     actionhtml += '<button type="button" class="button popblocks-set-focal-point popblocks-active" data-id="' + imageID + '">' + translations.set_focalpoint + '</button>';
//     actionhtml += '<button type="button" class="button button-primary popblocks-save-focal-point">' + translations.save_focalpoint + '</button>';

//     actionhtml += '<br><select class="popblocks-select-focal-point-preview">';
//     $.each(allSizes, function(key, item) {
//       if (item.crop) {
//         actionhtml += '<option value="' + key + '">' + item.name + '</option>';
//       }
//     });
//     actionhtml += '</select>';

//     $image.wrap('<div class="popblocks-focal-point-container"><div class="popblocks-focal-point-image"></div></div>').after(imagehtml);
//     $actions.append(actionhtml);

//     if (data.smart_cache) {
//       $info.prepend('<a href="#" class="popblocks-clear-cache" data-id="' + imageID + '">' + translations.delete_cache + '</a> | ');
//     } else {
//       // $info.prepend('<a href="#" class="popblocks-regenerate-cache" data-id="' + imageID + '">' + translations.regenerate_cache + '</a> | ');
//     }

//     $marker = $(".popblocks-focal-point-marker");
//     $preview = $(".popblocks-focal-point-preview");
//     $select = $(".popblocks-select-focal-point-preview");

//     updateMarkerPosition(focalPoint);
//   }

//   function removeFocalPoint() {

//   }

//   function onSetFocalPointClick() {
//     updateMarkerPosition(focalPoint);

//     $(".popblocks-set-focal-point").removeClass("popblocks-active");
//     $(".popblocks-save-focal-point").addClass("popblocks-active");

//     $marker.addClass("popblocks-active");
//     $preview.addClass("popblocks-active");
//     $select.addClass("popblocks-active");

//     $marker.on("mousedown.popblocks", onMarkerDown);
//   }

//   function onSelectFocalPointPreview() {
//     updateMarkerPosition(focalPoint);
//   }

//   function onSaveFocalPointClick() {
//     var data = {
//       action: "popblocks_set_focal_point",
//       image: imageID,
//       top: focalPoint.top,
//       left: focalPoint.left
//     };

//     $(".popblocks-save-focal-point").prop("disabled", true);

//     $.ajax({
//       type: 'GET',
//       url: ajaxurl,
//       data: data,
//       success: onFocalPointSaved
//     });
//   }

//   function onFocalPointSaved() {
//     if (smartCache) {
//       resetFocalPoint();
//     } else {
//       doRegenerateThumbnails();
//     }
//   }

//   function resetFocalPoint() {
//     $(".popblocks-set-focal-point").addClass("popblocks-active");
//     $(".popblocks-save-focal-point").removeClass("popblocks-active");

//     $marker.removeClass("popblocks-active");
//     $preview.removeClass("popblocks-active");
//     $select.removeClass("popblocks-active");

//     $(".popblocks-save-focal-point").prop("disabled", false);

//     $marker.off("mousedown.popblocks");
//   }

//   //

//   function onClearCache(e) {
//     e.preventDefault();
//     e.stopPropagation();

//     $link = $(this);

//     if ($link.hasClass("loading")) {
//       return false;
//     }

//     $link.addClass("loading");

//     $.ajax({
//       type: 'GET',
//       url: ajaxurl,
//       data: {
//         action: "popblocks_clear_image_cache",
//         image: $link.data("id")
//       },
//       success: function() {
//         $link.removeClass("loading");
//       }
//     });
//   }

//   // function onRegenerateCache(e) {
//   //   e.preventDefault();
//   //   e.stopPropagation();

//   //   $link = $(this);

//   //   if ($link.hasClass("loading")) {
//   //     return false;
//   //   }

//   //   $link.addClass("loading");

//   //   $.ajax({
//   //     type: 'GET',
//   //     url: ajaxurl,
//   //     data: {
//   //       action: "popblocks_regenerate_image_cache",
//   //       image: $link.data("id")
//   //     },
//   //     success: function() {
//   //       $link.removeClass("loading");
//   //     }
//   //   });
//   // }

//   //

//   function doRegenerateThumbnails() {
//     currentPage++;

//     console.log('Regenerate Page ' + currentPage);

//     $.ajax({
//       type: 'GET',
//       url: ajaxurl,
//       data: {
//         action: 'popblocks_regenerate_thumbnails',
//         image: imageID,
//         page: currentPage,
//       },
//       success: function(data) {
//         console.log('Success', data);

//         if (data.trim() == 'Complete') {
//           resetFocalPoint();
//         } else {
//           doRegenerateThumbnails();
//         }
//       },
//       error: function() {
//         console.log('Error');
//       }
//     });
//   }

//   //

//   function onMarkerDown(e) {
//     e.preventDefault();
//     e.stopPropagation();

//     imageBounds = $image.offset();

//     imageBounds.width = $image.width();
//     imageBounds.height = $image.height();

//     $(window).on("mousemove.popblocks", onMarkerMove);
//     $(window).on("mouseup.popblocks", onMarkerUp);

//     onMarkerMove(e);
//   }

//   function onMarkerMove(e) {
//     e.preventDefault();
//     e.stopPropagation();

//     var top = (e.pageY - imageBounds.top);
//     var left = (e.pageX - imageBounds.left);

//     if (top < 0) {
//       top = 0;
//     }
//     if (top > imageBounds.height) {
//       top = imageBounds.height;
//     }
//     if (left < 0) {
//       left = 0;
//     }
//     if (left > imageBounds.width) {
//       left = imageBounds.width;
//     }

//     focalPoint = {
//       top: top / imageBounds.height,
//       left: left / imageBounds.width
//     };

//     updateMarkerPosition(focalPoint);
//   }

//   function onMarkerUp(e) {
//     e.preventDefault();
//     e.stopPropagation();

//     $(window).off("mousemove.popblocks");
//     $(window).off("mouseup.popblocks");
//   }

//   function updateMarkerPosition(point) {
//     $marker.css({
//       top: (point.top * 100) + '%',
//       left: (point.left * 100) + '%'
//     });

//     if ($image.length && $preview.length) {
//       var key = $select.val();
//       var size = allSizes[ key ] ? allSizes[ key ] : false;
//       var originalWidth = $image.width();
//       var originalHeight = $image.height();
//       var previewWidth = originalWidth;
//       var previewHeight = originalHeight;
//       var previewTop = 0;
//       var previewLeft = 0;

//       if ( size && size.width > 0 && size.height > 0 ) {
//         var image_x = 0;
//         var image_y = 0;
//         var ratio = 1;

//         ratio = originalHeight / originalWidth;

//         imageWidth = size.width;
//         imageHeight = size.width * ratio;

//         if ( imageHeight < size.height ) {
//           ratio = originalWidth / originalHeight;

//           imageHeight = size.height;
//           imageWidth = size.height * ratio;
//         }

//         var x_ratio = size.width / originalWidth;
//         var y_ratio = size.height / originalHeight;

//         image_x = ( imageWidth * parseFloat(point.left) ) - ( originalWidth / 2 * x_ratio );
//         image_y = ( imageHeight * parseFloat(point.top) ) - ( originalHeight / 2 * y_ratio );

//         if ( image_x < 0 ) {
//           image_x = 0;
//         }
//         if ( image_x + size.width > imageWidth ) {
//           image_x -= ( image_x + size.width - imageWidth );
//         }

//         if ( image_y < 0 ) {
//           image_y = 0;
//         }
//         if ( image_y + size.height > imageHeight ) {
//           image_y -= ( image_y + size.height - imageHeight );
//         }

//         previewLeft = image_x * ( originalWidth / imageWidth );
//         previewTop = image_y * ( originalHeight / imageHeight );

//         previewWidth = size.width * ( originalWidth / imageWidth );
//         previewHeight = size.height * ( originalHeight / imageHeight );
//       }

//       $preview.css({
//         width: ((previewWidth / originalWidth) * 100) + "%",
//         height: ((previewHeight / originalHeight) * 100) + "%",
//         left: ((previewLeft / originalWidth) * 100) + "%",
//         top: ((previewTop / originalHeight) * 100) + "%"
//       });
//     }
//   }


//   $.extend(wp.Uploader.prototype, {
//     success: function(data) {

//       // TODO generate images via ajax?
//       var id = data.id;

//     },
//   });

// })(jQuery, wp);
