wp.blocks.registerBlockType('ourblocktheme/programarchive', {
  title: 'Uint University Program Archive',
  description: 'The archive page for all programs',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Program Archive Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
