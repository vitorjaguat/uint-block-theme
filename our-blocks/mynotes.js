wp.blocks.registerBlockType('ourblocktheme/mynotes', {
  title: 'My Notes',
  description:
    'Personal student notes page with notes archive and form to add new notes',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'My Notes Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
