import { __ } from 'wp.i18n';
import { registerBlockType } from 'wp.blocks';

import TaxonomyTermsEdit from './edit';
import BlockIcon from './block-icon';

registerBlockType('ignition/taxonomy-terms', {
  title: __('Taxonomy Terms'),
  description: __('Display a list of terms from any taxonomy'),
  icon: BlockIcon,
  category: 'ignition',
  keywords: [__('taxonomy'), __('categories'), __('terms'), __('ignition')],
  attributes: {
    uniqueId: {
      type: 'string',
    },
    className: {
      type: 'string',
    },
    taxonomySlug: {
      type: 'string',
      default: '',
    },
    includedTermSlugs: {
      type: 'array',
      default: [],
    },
    columns: {
      type: 'number',
      default: 3,
    },
  },
  edit: TaxonomyTermsEdit,
  save: () => 'hello',
});
