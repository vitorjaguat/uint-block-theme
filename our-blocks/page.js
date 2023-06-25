wp.blocks.registerBlockType('ourblocktheme/page', {
  title: 'Uint University Single Page',
  description: 'Single page template with title, breadcrumb and post content',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Single Page Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
