wp.blocks.registerBlockType('ourblocktheme/genericheading', {
  title: 'Generic Heading',
  edit: EditComponent,
  save: SaveComponent,
});

function EditComponent() {
  return <div className=''>Hello</div>;
}

function SaveComponent() {
  return <div className=''>This is our heading block</div>;
}
