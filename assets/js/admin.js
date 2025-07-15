let optionsChanged = false;
jQuery(document).ready(function($) {
	var meta_image_frame;
  function mediaUploader(
    uiElement,
    mediaType,
    mediaTitle = "Choose Image",
    onSelectCallback
  ) {
    const targetInputElement = $("#" + uiElement);
    const targetImageElement = $(".img-preview-" + uiElement);

    if (meta_image_frame) {
      meta_image_frame.open();
      return;
    }

    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
      title: mediaTitle, // ✅ Use dynamic title here
      library: {
        type: mediaType,
      },
      multiple: false,
    });

    meta_image_frame.on("select", function () {
      const media_attachment = meta_image_frame
        .state()
        .get("selection")
        .first()
        .toJSON();
      targetInputElement.val(media_attachment.url);
      targetImageElement.attr("src", media_attachment.url);

      if (typeof onSelectCallback === "function") {
        onSelectCallback();
      }
    });

    meta_image_frame.open();
  }


  $(".btn-upload").click(function (e) {
    e.preventDefault();

    const mediaType = $(this).attr("data-type") ?? "image";
    const $input = $(this).parent("label").find("input");
    const inputId = $input.attr("id");
    const $clearBtn = $(this).parent().find(".clear-input");
    const $hintText = $(this).parent().next().find("p");
    
    const mediaTitle = $(this).attr("data-title") ?? "Choose Image"; // Read dynamic title

    meta_image_frame = ""; // reset global frame

    mediaUploader(inputId, mediaType, mediaTitle, function () {
      // This callback runs only after an image is selected
      if ($input.val()) {
        $clearBtn.show();
        $hintText.text($input.val()).show();
      }
    });
  });

  // Add clear button for image uploader fields
  $(".clear-input").on("click", function () {
    $(this).parent().find("input").val("").trigger("change");
    $(this).parent().next().find("img").attr("src", "");
    $(this).parent().next().find("p").hide();
    $(this).hide();
  });


  //hide show on select, input
  $('select.option-show-hide, input.option-show-hide').on( 'change',function(e) {
    e.preventDefault();
    var parentFieldsetClass = $(this).closest('fieldset').attr('class'),
        optionSelected = '';
    if( $(this).is('input:checkbox') ) {
      optionSelected = $(this).prop('checked') ? $(this).val() : '';
    } else{
      optionSelected = $(this).val();
      var backgroundOptionSelected = $( 'select[name="background_option"]' ).val();
      if (backgroundOptionSelected == 1) {
        $(".bg-image").addClass("background-option-visible");
      } 
    }
    optionSelected == 2 ? $('tr.'+parentFieldsetClass).addClass('tr-visible') : $('tr.'+parentFieldsetClass).removeClass('tr-visible'),
    $('tr.'+parentFieldsetClass).addClass('tr-hide');
  });

  $('input#display_logo').on('change', function(e) {
    e.preventDefault();
    var parentFieldsetClass = $(this).closest('fieldset').attr('class'),
        value = '0';
    if( $(this).is(':checked') ) {
      value = $(this).val();
    } else {
      value = '0';
    }
    value == 1 ? $('tr.'+parentFieldsetClass).addClass('tr-visible') : $('tr.'+parentFieldsetClass).removeClass('tr-visible'),$('tr.'+parentFieldsetClass).addClass('tr-hide');
  });


  //color selection
  const colorFields = $(
    ".background-color, .border_color, .content_color, .icon_color"
  );

  colorFields.each(function () {
    const $input = $(this);
    // Initialize the color picker
    $input.wpColorPicker({
      change: function (event, ui) {
        optionsChanged = true;
      },
    });
  });


  // Handle template selection change event
  $('.template-option select').on('change', function(){
    $optionSelected = $(this).val();
    $optionSelected == 2 ? $('.bg-color').addClass('background-option-visible') : $('.bg-color').removeClass('background-option-visible'), $('.bg-color').addClass('background-option');
    $optionSelected == 1 ? $('.bg-image').addClass('background-option-visible') : $('.bg-image').removeClass('background-option-visible'), $('.bg-image').addClass('background-option') ;
    $optionSelected == 3 ? $(".bg-video").addClass("background-option-visible") : $(".bg-video").removeClass("background-option-visible"), $(".bg-video").addClass("background-option");
  });

  // handle ga tracking submission
  $("form").on('submit',function(e) {
    if( $('#enable_gtracking').prop("checked") ) {
      var gaInput = $('input.ga_tracking_id').val();
      if(gaInput == '' ) {
         $('input.ga_tracking_id').css("border","2px solid red");
         return false;
      } else {
        gaInput = validateGAId(gaInput);
        if( gaInput == false ) {
          $('input.ga_tracking_id').css("border","1px solid red");
          return false;
        } 
      }
      
    }
  });
  function validateGAId(id) {
    // return /^ua-\d{4,10}(-\d{1,4})?$/im.test(id);
    const ga3Pattern = /^UA-\d{4,10}(-\d{1,4})?$/i;
    const ga4Pattern = /^G-[A-Z0-9]{10,}$/i;
    return ga3Pattern.test(id) || ga4Pattern.test(id);
  }

  // Update maintenance mode status in admin bar on window load
  $(window).on("load", function () {
    var wmmStatusElem = $("#enable_maintenance_mode");
    if (wmmStatusElem.length) {
      if (wmmStatusElem.is(":checked")) {
        $(".maintenance-status").text($(".maintenance-status").data("on"));
      } else {
        $(".maintenance-status").text($(".maintenance-status").data("off"));
      }
    }

  });


  // Track changes made in tinyMce editor
  if (typeof tinymce !== "undefined") {
    const editorId = "wmm_content";

    const setupEditorChangeListener = function () {
      const editor = tinymce.get(editorId);

      if (editor) {
        editor.on("change keyup", function () {
          optionsChanged = true;
        });
      } else {
        // Retry later if editor isn't ready yet
        setTimeout(setupEditorChangeListener, 200);
      }
    };

    setupEditorChangeListener();
  }

  // Set options Changed flag to true on changing the input and select fields
  $("input, select").on("change", function () {
    optionsChanged = true;
  });

  // Show alert box before switching the current tab
  $(".wmm-maintenance a.nav-tab ").on("click", function (e) {
    if (optionsChanged) {
      var confirmationText = "Do you want to continue without saving?";
      if (!confirm(confirmationText)) {
        e.preventDefault();
        return false;
      }
    }
  });

});

