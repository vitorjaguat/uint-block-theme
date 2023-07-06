wp.blocks.registerBlockType('ourblocktheme/eventarchive', {
  title: 'Uint University Event Archive',
  description: 'The archive page for all events',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Event Archive Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
