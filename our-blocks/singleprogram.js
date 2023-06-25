wp.blocks.registerBlockType('ourblocktheme/singleprogram', {
  title: 'Uint University Single Program',
  description:
    'Single program template with title, breadcrumb, program content and related professors, events and campuses',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Single Program Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
