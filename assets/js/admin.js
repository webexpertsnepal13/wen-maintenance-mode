jQuery(document).ready(function($) {

	var meta_image_frame;
  function mediaUploader(uiElement) {
      var targetInputElement = $( "#" +uiElement ),
          targetImageElement = $( ".img-preview-" +uiElement );
      if (meta_image_frame) {
          meta_image_frame.open();
          return;
      }
      meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
          title : "Choose Image",
          multiple: false
      });
      meta_image_frame.on('select', function() {
          var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
          targetInputElement.val(media_attachment.url);
          targetImageElement.attr('src', media_attachment.url);
      });
      meta_image_frame.open();
  }
  $('.btn-upload').click(function(e) {
      e.preventDefault();
      meta_image_frame = '';
      var rowInput = $(this).parent('label').find('input').attr('id');
      mediaUploader(rowInput);
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
  $('.background-color, .border_color, .content_color, .icon_color').wpColorPicker();

  $('.template-option select').on('change', function(){
    $optionSelected = $(this).val();
    $optionSelected == 2 ? $('.bg-color').addClass('background-option-visible') : $('.bg-color').removeClass('background-option-visible'), $('.bg-color').addClass('background-option');
    $optionSelected == 1 ? $('.bg-image').addClass('background-option-visible') : $('.bg-image').removeClass('background-option-visible'),$('.bg-image').addClass('background-option') ;
  });

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

});