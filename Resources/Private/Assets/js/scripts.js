(function ($) {
  $('.dpnglossary.details .description h3').on('click', function () {
    if (!$(this).parents('.description').hasClass('open')) {
      $('.dpnglossary.details .description.open .text').slideUp('slow');
      $('.dpnglossary.details .description.open').removeClass('open');
      $(this).parents('.description').addClass('open');
      $(this).parents('.description').find('.text').slideDown('slow');
    } else {
      $('.dpnglossary.details .description.open .text').slideUp('slow');
      $('.dpnglossary.details .description.open').removeClass('open');
    }
  });
})(jQuery);
