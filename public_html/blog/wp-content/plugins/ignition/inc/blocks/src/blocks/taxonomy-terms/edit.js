import { Fragment } from 'wp.element';
import { __ } from 'wp.i18n';
import { useSelect } from 'wp.data';
import { SelectControl, RangeControl, PanelBody, Notice } from 'wp.components';
import { InspectorControls } from 'wp.blockEditor';
import ServerSideRender from 'wp.serverSideRender';

import useUniqueId from '../../hooks/useUniqueId';
import EntitySelect from '../../components/EntitySelect';

const TaxonomyTermsEdit = ({ attributes, setAttributes, clientId }) => {
  const { columns, taxonomySlug, includedTermSlugs = [] } = attributes;

  useUniqueId({ attributes, setAttributes, clientId });

  const { taxonomies = [] } = useSelect(select => {
    const { getTaxonomies } = select('core');
    const taxonomies = getTaxonomies();
    return {
      taxonomies,
    };
  });

  return (
    <Fragment>
      {!taxonomySlug || !(includedTermSlugs || []).length ? (
        <Notice status="info" isDismissible={false}>
          {__('Select a taxonomy and terms.')}
        </Notice>
      ) : (
        <ServerSideRender
          block="ignition/taxonomy-terms"
          attributes={attributes}
        />
      )}

      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <SelectControl
            label={__('Taxonomy')}
            value={taxonomySlug}
            options={[
              {
                label: __('Select a taxonomy'),
                value: '',
              },
              ...(taxonomies || []).map(taxonomy => ({
                label: taxonomy.name,
                value: taxonomy.slug,
              })),
            ]}
            onChange={value => setAttributes({ taxonomySlug: value })}
          />

          {taxonomySlug && (
            <EntitySelect
              label={__('Terms')}
              entityRecordsParams={['taxonomy', taxonomySlug]}
              onChange={value => setAttributes({ includedTermSlugs: value })}
              searchLabel={__('Search terms')}
              values={includedTermSlugs}
            />
          )}

          <RangeControl
            label={__('Columns')}
            value={columns}
            min={1}
            max={4}
            onChange={value => setAttributes({ columns: value })}
          />
        </PanelBody>
      </InspectorControls>
    </Fragment>
  );
};

export default TaxonomyTermsEdit;
