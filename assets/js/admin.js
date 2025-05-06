let optionsChanged = false;
jQuery(document).ready(function($) {
	var meta_image_frame;
  function mediaUploader(uiElement, mediaTYpe) {
      var targetInputElement = $( "#" +uiElement ),
          targetImageElement = $( ".img-preview-" +uiElement );
      if (meta_image_frame) {
          meta_image_frame.open();
          return;
      }
      meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
          title : "Choose Image",
          library: {
            type: mediaTYpe, // Restricts selection to image files
          },
          multiple: false
      });
      meta_image_frame.on('select', function() {
          var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
          targetInputElement.val(media_attachment.url);
          targetImageElement.attr('src', media_attachment.url);
      });
      meta_image_frame.open();
      return true;
  }

  $(".btn-upload").click(function (e) {
    e.preventDefault();
    const mediaType = $(this).attr('data-type') ?? 'image';
    meta_image_frame = "";
    var rowInput = $(this).parent("label").find("input").attr("id");
    var changedMedia = mediaUploader(rowInput, mediaType);
    if (changedMedia) {
      $(this).parent().find(".clear-input").show();
      $(this).parent().next().find("p").show();
    }
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
    }
    optionSelected == 2 ? $('tr.'+parentFieldsetClass).addClass('tr-visible') : $('tr.'+parentFieldsetClass).removeClass('tr-visible'),$('tr.'+parentFieldsetClass).addClass('tr-hide');
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
    console.log(value);
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
        // console.log(gaInput); return false;
        if( gaInput == false ) {
          $('input.ga_tracking_id').css("border","1px solid red");
          return false;
        } 
      }
      
    }
  });
  function validateGAId(id) {
    return /^ua-\d{4,10}(-\d{1,4})?$/im.test(id);
  }

  // Update maintenance mode status in admin bar on window load
  $(window).on("load", function () {
    var wmmStatusElem = $("#enable_maintenance_mode");
    if (wmmStatusElem.is(":checked")) {
      $(".maintenance-status").text($(".maintenance-status").data("on"));
    } else {
      $(".maintenance-status").text($(".maintenance-status").data("off"));
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

