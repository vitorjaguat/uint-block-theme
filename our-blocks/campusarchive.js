wp.blocks.registerBlockType('ourblocktheme/campusarchive', {
  title: 'Uint University Campus Archive',
  description: 'The archive page for all campuses',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Campus Archive Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
