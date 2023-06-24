wp.blocks.registerBlockType('ourblocktheme/eventsandblogs', {
  title: 'Events and Blogs',
  description: 'Lists the upcoming events and latest blog posts',
  edit: function () {
    return wp.element.createElement(
      'div',
      { className: 'our-placeholder-block' },
      'Events and Blogs Placeholder'
    );
  },
  save: function () {
    return null;
  },
});
