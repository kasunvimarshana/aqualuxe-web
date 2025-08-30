/**
 * AquaLuxe Theme - Block Editor Scripts
 *
 * Scripts for the Gutenberg block editor
 */

(function(wp) {
    'use strict';

    const { __ } = wp.i18n;
    const { registerBlockType } = wp.blocks;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, RangeControl, SelectControl, ToggleControl } = wp.components;
    const { Fragment } = wp.element;
    const { withSelect } = wp.data;

    // Common attributes for all blocks
    const commonAttributes = {
        count: {
            type: 'number',
            default: 3
        },
        columns: {
            type: 'number',
            default: 3
        },
        orderby: {
            type: 'string',
            default: 'date'
        },
        order: {
            type: 'string',
            default: 'DESC'
        },
        className: {
            type: 'string',
            default: ''
        }
    };

    // Common inspector controls for all blocks
    const CommonInspectorControls = (props) => {
        const { attributes, setAttributes, categoryOptions = [] } = props;
        const { count, category, columns, orderby, order, layout } = attributes;

        return (
            <InspectorControls>
                <PanelBody title={__('Display Settings', 'aqualuxe')}>
                    <RangeControl
                        label={__('Number of Items', 'aqualuxe')}
                        value={count}
                        onChange={(value) => setAttributes({ count: value })}
                        min={-1}
                        max={20}
                        help={__('-1 shows all items', 'aqualuxe')}
                    />

                    <RangeControl
                        label={__('Columns', 'aqualuxe')}
                        value={columns}
                        onChange={(value) => setAttributes({ columns: value })}
                        min={1}
                        max={6}
                    />

                    {categoryOptions.length > 0 && (
                        <SelectControl
                            label={__('Category', 'aqualuxe')}
                            value={category}
                            options={categoryOptions}
                            onChange={(value) => setAttributes({ category: value })}
                        />
                    )}

                    <SelectControl
                        label={__('Order By', 'aqualuxe')}
                        value={orderby}
                        options={[
                            { label: __('Date', 'aqualuxe'), value: 'date' },
                            { label: __('Title', 'aqualuxe'), value: 'title' },
                            { label: __('Menu Order', 'aqualuxe'), value: 'menu_order' },
                            { label: __('Random', 'aqualuxe'), value: 'rand' }
                        ]}
                        onChange={(value) => setAttributes({ orderby: value })}
                    />

                    <SelectControl
                        label={__('Order', 'aqualuxe')}
                        value={order}
                        options={[
                            { label: __('Descending', 'aqualuxe'), value: 'DESC' },
                            { label: __('Ascending', 'aqualuxe'), value: 'ASC' }
                        ]}
                        onChange={(value) => setAttributes({ order: value })}
                    />

                    {layout && (
                        <SelectControl
                            label={__('Layout', 'aqualuxe')}
                            value={layout}
                            options={[
                                { label: __('Grid', 'aqualuxe'), value: 'grid' },
                                { label: __('List', 'aqualuxe'), value: 'list' },
                                { label: __('Carousel', 'aqualuxe'), value: 'carousel' }
                            ]}
                            onChange={(value) => setAttributes({ layout: value })}
                        />
                    )}
                </PanelBody>
            </InspectorControls>
        );
    };

    // Services Block
    registerBlockType('aqualuxe/services', {
        title: __('AquaLuxe Services', 'aqualuxe'),
        icon: 'admin-tools',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            category: {
                type: 'string',
                default: ''
            },
            layout: {
                type: 'string',
                default: 'grid'
            }
        },
        edit: withSelect((select) => {
            return {
                categoryOptions: aqualuxeBlocksEditorData.serviceCategories || []
            };
        })((props) => {
            const { className } = props.attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-admin-tools"></span>
                            </div>
                            <h3>{__('AquaLuxe Services', 'aqualuxe')}</h3>
                            <p>{__('This block displays your services. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        }),
        save: () => null // Server-side rendering
    });

    // Events Block
    registerBlockType('aqualuxe/events', {
        title: __('AquaLuxe Events', 'aqualuxe'),
        icon: 'calendar-alt',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            category: {
                type: 'string',
                default: ''
            },
            layout: {
                type: 'string',
                default: 'grid'
            },
            show_past: {
                type: 'boolean',
                default: false
            },
            meta_key: {
                type: 'string',
                default: '_event_date'
            }
        },
        edit: withSelect((select) => {
            return {
                categoryOptions: aqualuxeBlocksEditorData.eventCategories || []
            };
        })((props) => {
            const { attributes, setAttributes, className } = props;
            const { show_past } = attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <InspectorControls>
                        <PanelBody title={__('Event Settings', 'aqualuxe')}>
                            <ToggleControl
                                label={__('Show Past Events', 'aqualuxe')}
                                checked={show_past}
                                onChange={(value) => setAttributes({ show_past: value })}
                            />
                        </PanelBody>
                    </InspectorControls>
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-calendar-alt"></span>
                            </div>
                            <h3>{__('AquaLuxe Events', 'aqualuxe')}</h3>
                            <p>{__('This block displays your events. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        }),
        save: () => null // Server-side rendering
    });

    // Testimonials Block
    registerBlockType('aqualuxe/testimonials', {
        title: __('AquaLuxe Testimonials', 'aqualuxe'),
        icon: 'format-quote',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            layout: {
                type: 'string',
                default: 'grid'
            }
        },
        edit: (props) => {
            const { className } = props.attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-format-quote"></span>
                            </div>
                            <h3>{__('AquaLuxe Testimonials', 'aqualuxe')}</h3>
                            <p>{__('This block displays your testimonials. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        },
        save: () => null // Server-side rendering
    });

    // Team Block
    registerBlockType('aqualuxe/team', {
        title: __('AquaLuxe Team', 'aqualuxe'),
        icon: 'groups',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            category: {
                type: 'string',
                default: ''
            }
        },
        edit: withSelect((select) => {
            return {
                categoryOptions: aqualuxeBlocksEditorData.teamCategories || []
            };
        })((props) => {
            const { className } = props.attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-groups"></span>
                            </div>
                            <h3>{__('AquaLuxe Team', 'aqualuxe')}</h3>
                            <p>{__('This block displays your team members. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        }),
        save: () => null // Server-side rendering
    });

    // Projects Block
    registerBlockType('aqualuxe/projects', {
        title: __('AquaLuxe Projects', 'aqualuxe'),
        icon: 'portfolio',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            category: {
                type: 'string',
                default: ''
            },
            layout: {
                type: 'string',
                default: 'grid'
            }
        },
        edit: withSelect((select) => {
            return {
                categoryOptions: aqualuxeBlocksEditorData.projectCategories || []
            };
        })((props) => {
            const { className } = props.attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-portfolio"></span>
                            </div>
                            <h3>{__('AquaLuxe Projects', 'aqualuxe')}</h3>
                            <p>{__('This block displays your projects. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        }),
        save: () => null // Server-side rendering
    });

    // FAQs Block
    registerBlockType('aqualuxe/faqs', {
        title: __('AquaLuxe FAQs', 'aqualuxe'),
        icon: 'editor-help',
        category: 'aqualuxe',
        attributes: {
            ...commonAttributes,
            category: {
                type: 'string',
                default: ''
            }
        },
        edit: withSelect((select) => {
            return {
                categoryOptions: aqualuxeBlocksEditorData.faqCategories || []
            };
        })((props) => {
            const { className } = props.attributes;
            
            return (
                <Fragment>
                    <CommonInspectorControls {...props} />
                    <div className={className}>
                        <div className="aqualuxe-block-placeholder">
                            <div className="aqualuxe-block-placeholder-icon">
                                <span className="dashicons dashicons-editor-help"></span>
                            </div>
                            <h3>{__('AquaLuxe FAQs', 'aqualuxe')}</h3>
                            <p>{__('This block displays your FAQs. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                        </div>
                    </div>
                </Fragment>
            );
        }),
        save: () => null // Server-side rendering
    });

    // WooCommerce Blocks - Only register if WooCommerce is active
    if (typeof aqualuxeBlocksEditorData !== 'undefined' && aqualuxeBlocksEditorData.productCategories) {
        // Featured Products Block
        registerBlockType('aqualuxe/featured-products', {
            title: __('AquaLuxe Featured Products', 'aqualuxe'),
            icon: 'star-filled',
            category: 'aqualuxe',
            attributes: {
                ...commonAttributes,
                category: {
                    type: 'string',
                    default: ''
                }
            },
            edit: withSelect((select) => {
                return {
                    categoryOptions: aqualuxeBlocksEditorData.productCategories || []
                };
            })((props) => {
                const { className } = props.attributes;
                
                return (
                    <Fragment>
                        <CommonInspectorControls {...props} />
                        <div className={className}>
                            <div className="aqualuxe-block-placeholder">
                                <div className="aqualuxe-block-placeholder-icon">
                                    <span className="dashicons dashicons-star-filled"></span>
                                </div>
                                <h3>{__('AquaLuxe Featured Products', 'aqualuxe')}</h3>
                                <p>{__('This block displays your featured products. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                            </div>
                        </div>
                    </Fragment>
                );
            }),
            save: () => null // Server-side rendering
        });

        // Product Categories Block
        registerBlockType('aqualuxe/product-categories', {
            title: __('AquaLuxe Product Categories', 'aqualuxe'),
            icon: 'category',
            category: 'aqualuxe',
            attributes: {
                ...commonAttributes,
                hide_empty: {
                    type: 'boolean',
                    default: true
                },
                parent: {
                    type: 'string',
                    default: ''
                }
            },
            edit: (props) => {
                const { attributes, setAttributes, className } = props;
                const { hide_empty } = attributes;
                
                return (
                    <Fragment>
                        <CommonInspectorControls {...props} />
                        <InspectorControls>
                            <PanelBody title={__('Category Settings', 'aqualuxe')}>
                                <ToggleControl
                                    label={__('Hide Empty Categories', 'aqualuxe')}
                                    checked={hide_empty}
                                    onChange={(value) => setAttributes({ hide_empty: value })}
                                />
                            </PanelBody>
                        </InspectorControls>
                        <div className={className}>
                            <div className="aqualuxe-block-placeholder">
                                <div className="aqualuxe-block-placeholder-icon">
                                    <span className="dashicons dashicons-category"></span>
                                </div>
                                <h3>{__('AquaLuxe Product Categories', 'aqualuxe')}</h3>
                                <p>{__('This block displays your product categories. Configure the display options in the sidebar.', 'aqualuxe')}</p>
                            </div>
                        </div>
                    </Fragment>
                );
            },
            save: () => null // Server-side rendering
        });
    }

})(window.wp);