wp.blocks.registerBlockType('ourblocktheme/singlepost', {
  title: 'Uint University Single Post',
  description: 'Single post template with title, breadcrumb and post content',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Single Post Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
