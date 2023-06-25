wp.blocks.registerBlockType('ourblocktheme/singleprofessor', {
  title: 'Uint University Single Professor',
  description:
    'Single professor template with title, breadcrumb, professor bio and related programs and posts',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Single Professor Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
