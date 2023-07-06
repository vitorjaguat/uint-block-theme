wp.blocks.registerBlockType('ourblocktheme/pastevents', {
  title: 'Uint University Past Events Archive',
  description: 'The archive page for all past events',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Past Events Archive Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
