wp.blocks.registerBlockType('ourblocktheme/blogindex', {
  title: 'Uint University Blog Index',
  description: 'The index file for all blog posts',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Blog Index Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
