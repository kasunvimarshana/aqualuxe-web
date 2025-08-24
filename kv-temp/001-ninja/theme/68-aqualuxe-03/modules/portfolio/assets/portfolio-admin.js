// Portfolio admin JS for gallery meta box
jQuery(document).ready(function($) {
  var frame;
  var $metabox = $('#aqualuxe-portfolio-gallery-metabox');
  var $input = $('#portfolio_gallery_input');
  var $preview = $metabox.find('.portfolio-gallery-preview');

  $metabox.on('click', '.add-portfolio-gallery-image', function(e) {
    e.preventDefault();
    if (frame) {
      frame.open();
      return;
    }
    frame = wp.media({
      title: 'Select Images',
      button: { text: 'Add to Gallery' },
      multiple: true
    });
    frame.on('select', function() {
      var selection = frame.state().get('selection');
      var ids = $input.val() ? $input.val().split(',').filter(Boolean) : [];
      selection.each(function(attachment) {
        attachment = attachment.toJSON();
        if (ids.indexOf(String(attachment.id)) === -1) {
          ids.push(attachment.id);
          $preview.append('<div class="portfolio-gallery-thumb" data-img-id="'+attachment.id+'"><img src="'+attachment.sizes.thumbnail.url+'" /><span class="remove-gallery-image">&times;</span></div>');
        }
      });
      $input.val(ids.join(','));
    });
    frame.open();
  });

  $metabox.on('click', '.remove-gallery-image', function(e) {
    e.preventDefault();
    var $thumb = $(this).closest('.portfolio-gallery-thumb');
    var id = $thumb.data('img-id');
    $thumb.remove();
    var ids = $input.val().split(',').filter(function(val) { return val && val != id; });
    $input.val(ids.join(','));
  });
});
