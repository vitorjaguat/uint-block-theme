wp.blocks.registerBlockType('ourblocktheme/footer', {
  title: 'Uint University Footer',
  description: 'A footer with links and social media',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Footer Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
