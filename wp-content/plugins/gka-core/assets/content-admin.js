/* Media Library picker for the image fields on the GKA Konten screen. */
jQuery(function ($) {
  $('.gka-pick').each(function () {
    const box = $(this);
    const multiple = box.data('multiple') === 1;
    const input = box.find('input[type=hidden]');
    const preview = box.find('.gka-pick-preview');
    const clear = box.find('.gka-pick-clear');
    let frame = null;

    const render = (items) => {
      preview.empty();
      items.forEach((a) => {
        const size = (a.sizes && (a.sizes.thumbnail || a.sizes.medium)) || a;
        $('<img alt="">').attr('src', size.url).appendTo(preview);
      });
      input.val(items.map((a) => a.id).join(','));
      clear.prop('hidden', !items.length);
    };

    box.on('click', '.gka-pick-open', (e) => {
      e.preventDefault();
      if (!frame) {
        frame = wp.media({ title: multiple ? 'Pilih logo' : 'Pilih foto', library: { type: 'image' }, multiple: multiple ? 'add' : false, button: { text: 'Gunakan' } });
        frame.on('open', () => {
          const sel = frame.state().get('selection');
          sel.reset();
          String(input.val()).split(',').filter(Boolean).forEach((id) => { const a = wp.media.attachment(id); a.fetch(); sel.add(a); });
        });
        frame.on('select', () => render(frame.state().get('selection').toJSON()));
      }
      frame.open();
    });

    box.on('click', '.gka-pick-clear', (e) => {
      e.preventDefault();
      render([]);
    });
  });
});
