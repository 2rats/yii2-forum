$('.image-checkbox').each(function () {
  if ($(this).find('input[type=\"checkbox\"]').first().attr('checked')) {
    $(this).addClass('image-checkbox-checked');
  } else {
    $(this).removeClass('image-checkbox-checked');
  }
});
$('.image-checkbox').on('click', function (e) {
  $('.image-checkbox').removeClass('image-checkbox-checked');
  $('.image-checkbox input[type=\"checkbox\"]').prop('checked', false);

  $(this).toggleClass('image-checkbox-checked');
  var checkbox = $(this).find('input[type=\"checkbox\"]');
  checkbox.prop('checked', !checkbox.prop('checked'))
  e.preventDefault();
});