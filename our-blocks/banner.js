import apiFetch from '@wordpress/api-fetch';
import { Button, PanelBody, PanelRow } from '@wordpress/components';
import {
  InnerBlocks,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
} from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { useEffect, useState } from '@wordpress/element';

registerBlockType('ourblocktheme/banner', {
  title: 'Banner',
  supports: {
    align: ['full'],
  },
  attributes: {
    align: { type: 'string', default: 'full' },
    imgID: { type: 'number' },
    imgURL: { type: 'string', default: banner.fallbackimage },
  },
  edit: EditComponent,
  save: SaveComponent,
});

function EditComponent(props) {
  useEffect(() => {
    if (props.attributes.imgID) {
      async function go() {
        const response = await apiFetch({
          path: `/wp/v2/media/${props.attributes.imgID}`,
          method: 'GET',
        });
        props.setAttributes({
          imgURL: response.media_details.sizes.pageBanner.source_url,
        });
      }
      go();
    }
  }, [props.attributes.imgID]);

  function onFileSelect(x) {
    props.setAttributes({ imgID: x.id });
  }

  return (
    <>
      {/* change BG image from the sidebar in editor */}
      <InspectorControls>
        <PanelBody title='Background' initialOpen={true}>
          <PanelRow>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={onFileSelect}
                value={props.attributes.imgID}
                render={({ open }) => {
                  return <Button onClick={open}>Choose Image</Button>;
                }}
              />
            </MediaUploadCheck>
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <div className='page-banner'>
        <div
          className='page-banner__bg-image'
          style={{
            backgroundImage: `url('${
              props.attributes.imgURL
                ? props.attributes.imgURL
                : '/images/library-hero.jpg'
            }')`,
          }}
        ></div>
        <div className='page-banner__content container t-center c-white'>
          <InnerBlocks
            allowedBlocks={[
              'ourblocktheme/genericheading',
              'ourblocktheme/genericbutton',
            ]}
          />
        </div>
      </div>
    </>
  );
}

//rendering directly from PHP (changes in the banner.php html will be directly rendered, on the fly, dynamically):
function SaveComponent() {
  return <InnerBlocks.Content />;
}

//rendering from js: this way, the html will be stored in the database, so when we want change the banner html, we will have to manually update each post/template in which we used this block. an alternative is to render from php (above)
// function SaveComponent(props) {
//   const imgURL_fallback =
//     '/wp-content/themes/uint-block-theme/images/library-hero.jpg';

//   return (
//     <div className='page-banner'>
//       <div
//         className='page-banner__bg-image'
//         style={{
//           backgroundImage: `url('${
//             props.attributes.imgURL ? props.attributes.imgURL : imgURL_fallback
//           }')`,
//         }}
//       ></div>
//       <div className='page-banner__content container t-center c-white'>
//         <InnerBlocks.Content />
//       </div>
//     </div>
//   );
// }
