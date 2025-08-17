/**
 * AquaLuxe Feature Box Block
 * A custom Gutenberg block for displaying feature boxes with icons
 */

import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
    RichText, 
    InspectorControls, 
    ColorPalette,
    MediaUpload,
    BlockControls,
    AlignmentToolbar
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl,
    ToggleControl,
    RangeControl,
    Button,
    TextControl
} from '@wordpress/components';
import { more } from '@wordpress/icons';

// Register the block
registerBlockType('aqualuxe/feature-box', {
    title: __('Feature Box', 'aqualuxe'),
    description: __('Display a feature box with icon, title, and description.', 'aqualuxe'),
    category: 'aqualuxe',
    icon: 'star-filled',
    keywords: [
        __('feature', 'aqualuxe'),
        __('box', 'aqualuxe'),
        __('icon', 'aqualuxe'),
        __('aqualuxe', 'aqualuxe'),
    ],
    supports: {
        align: ['wide', 'full'],
        html: false,
    },
    attributes: {
        title: {
            type: 'string',
            source: 'html',
            selector: 'h3',
            default: __('Feature Title', 'aqualuxe'),
        },
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
            default: __('Feature description goes here. Add details about this feature.', 'aqualuxe'),
        },
        iconType: {
            type: 'string',
            default: 'dashicon',
        },
        dashicon: {
            type: 'string',
            default: 'admin-customizer',
        },
        iconUrl: {
            type: 'string',
            default: '',
        },
        iconId: {
            type: 'number',
        },
        iconSvg: {
            type: 'string',
            default: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>',
        },
        backgroundColor: {
            type: 'string',
            default: '#ffffff',
        },
        textColor: {
            type: 'string',
            default: '#333333',
        },
        accentColor: {
            type: 'string',
            default: '#0073aa',
        },
        borderRadius: {
            type: 'number',
            default: 8,
        },
        shadow: {
            type: 'boolean',
            default: true,
        },
        alignment: {
            type: 'string',
            default: 'center',
        },
        iconSize: {
            type: 'number',
            default: 48,
        },
        linkUrl: {
            type: 'string',
            default: '',
        },
        linkText: {
            type: 'string',
            default: __('Learn More', 'aqualuxe'),
        },
        showLink: {
            type: 'boolean',
            default: false,
        },
    },

    // Edit function
    edit: ({ attributes, setAttributes }) => {
        const { 
            title, 
            content, 
            iconType, 
            dashicon, 
            iconUrl, 
            iconSvg,
            backgroundColor, 
            textColor, 
            accentColor,
            borderRadius,
            shadow,
            alignment,
            iconSize,
            linkUrl,
            linkText,
            showLink
        } = attributes;

        // Available dashicons
        const dashicons = [
            'admin-customizer',
            'admin-home',
            'admin-media',
            'admin-plugins',
            'admin-tools',
            'admin-users',
            'awards',
            'businessman',
            'chart-area',
            'chart-bar',
            'chart-line',
            'chart-pie',
            'clock',
            'dashboard',
            'desktop',
            'format-gallery',
            'format-image',
            'format-video',
            'heart',
            'lightbulb',
            'location',
            'lock',
            'money',
            'phone',
            'shield',
            'star-filled',
            'thumbs-up',
            'unlock',
            'update',
            'welcome-learn-more',
        ];

        // SVG icons
        const svgIcons = [
            {
                name: 'plus',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>',
            },
            {
                name: 'check',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 11 15 16 9"></polyline></svg>',
            },
            {
                name: 'star',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
            },
            {
                name: 'heart',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
            },
            {
                name: 'alert',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
            },
            {
                name: 'settings',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
            },
        ];

        // Function to render the icon
        const renderIcon = () => {
            switch (iconType) {
                case 'dashicon':
                    return <span className={`dashicons dashicons-${dashicon}`} style={{ fontSize: iconSize, width: iconSize, height: iconSize, color: accentColor }}></span>;
                case 'image':
                    return iconUrl ? <img src={iconUrl} alt="" style={{ width: iconSize, height: iconSize }} /> : <div className="placeholder" style={{ width: iconSize, height: iconSize, backgroundColor: '#eee', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>{__('Select Image', 'aqualuxe')}</div>;
                case 'svg':
                    return <div className="svg-icon" style={{ width: iconSize, height: iconSize, color: accentColor }} dangerouslySetInnerHTML={{ __html: iconSvg }}></div>;
                default:
                    return <span className="dashicons dashicons-admin-customizer" style={{ fontSize: iconSize, width: iconSize, height: iconSize, color: accentColor }}></span>;
            }
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Icon Settings', 'aqualuxe')}>
                        <SelectControl
                            label={__('Icon Type', 'aqualuxe')}
                            value={iconType}
                            options={[
                                { label: __('Dashicon', 'aqualuxe'), value: 'dashicon' },
                                { label: __('Image', 'aqualuxe'), value: 'image' },
                                { label: __('SVG', 'aqualuxe'), value: 'svg' },
                            ]}
                            onChange={(value) => setAttributes({ iconType: value })}
                        />

                        {iconType === 'dashicon' && (
                            <SelectControl
                                label={__('Select Icon', 'aqualuxe')}
                                value={dashicon}
                                options={dashicons.map((icon) => ({ label: icon, value: icon }))}
                                onChange={(value) => setAttributes({ dashicon: value })}
                            />
                        )}

                        {iconType === 'image' && (
                            <div className="editor-post-featured-image">
                                <MediaUpload
                                    onSelect={(media) => setAttributes({ iconUrl: media.url, iconId: media.id })}
                                    type="image"
                                    value={attributes.iconId}
                                    render={({ open }) => (
                                        <Button
                                            className={!attributes.iconId ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview'}
                                            onClick={open}
                                        >
                                            {!attributes.iconId && __('Select Image', 'aqualuxe')}
                                            {attributes.iconUrl && <img src={attributes.iconUrl} alt="" />}
                                        </Button>
                                    )}
                                />
                                {attributes.iconId && (
                                    <Button
                                        isDestructive
                                        isSmall
                                        onClick={() => setAttributes({ iconUrl: '', iconId: undefined })}
                                    >
                                        {__('Remove Image', 'aqualuxe')}
                                    </Button>
                                )}
                            </div>
                        )}

                        {iconType === 'svg' && (
                            <SelectControl
                                label={__('Select SVG Icon', 'aqualuxe')}
                                value={iconSvg}
                                options={svgIcons.map((icon) => ({ label: icon.name, value: icon.svg }))}
                                onChange={(value) => setAttributes({ iconSvg: value })}
                            />
                        )}

                        <RangeControl
                            label={__('Icon Size', 'aqualuxe')}
                            value={iconSize}
                            onChange={(value) => setAttributes({ iconSize: value })}
                            min={24}
                            max={128}
                            step={4}
                        />
                    </PanelBody>

                    <PanelBody title={__('Style Settings', 'aqualuxe')}>
                        <p>{__('Background Color', 'aqualuxe')}</p>
                        <ColorPalette
                            value={backgroundColor}
                            onChange={(value) => setAttributes({ backgroundColor: value })}
                        />

                        <p>{__('Text Color', 'aqualuxe')}</p>
                        <ColorPalette
                            value={textColor}
                            onChange={(value) => setAttributes({ textColor: value })}
                        />

                        <p>{__('Accent Color', 'aqualuxe')}</p>
                        <ColorPalette
                            value={accentColor}
                            onChange={(value) => setAttributes({ accentColor: value })}
                        />

                        <RangeControl
                            label={__('Border Radius', 'aqualuxe')}
                            value={borderRadius}
                            onChange={(value) => setAttributes({ borderRadius: value })}
                            min={0}
                            max={32}
                        />

                        <ToggleControl
                            label={__('Show Shadow', 'aqualuxe')}
                            checked={shadow}
                            onChange={() => setAttributes({ shadow: !shadow })}
                        />
                    </PanelBody>

                    <PanelBody title={__('Link Settings', 'aqualuxe')}>
                        <ToggleControl
                            label={__('Show Link', 'aqualuxe')}
                            checked={showLink}
                            onChange={() => setAttributes({ showLink: !showLink })}
                        />

                        {showLink && (
                            <>
                                <TextControl
                                    label={__('Link URL', 'aqualuxe')}
                                    value={linkUrl}
                                    onChange={(value) => setAttributes({ linkUrl: value })}
                                    placeholder="https://"
                                />

                                <TextControl
                                    label={__('Link Text', 'aqualuxe')}
                                    value={linkText}
                                    onChange={(value) => setAttributes({ linkText: value })}
                                />
                            </>
                        )}
                    </PanelBody>
                </InspectorControls>

                <BlockControls>
                    <AlignmentToolbar
                        value={alignment}
                        onChange={(value) => setAttributes({ alignment: value })}
                    />
                </BlockControls>

                <div 
                    className="aqualuxe-feature-box" 
                    style={{ 
                        backgroundColor: backgroundColor,
                        color: textColor,
                        borderRadius: borderRadius + 'px',
                        boxShadow: shadow ? '0 4px 6px rgba(0, 0, 0, 0.1)' : 'none',
                        padding: '2rem',
                        textAlign: alignment,
                    }}
                >
                    <div className="aqualuxe-feature-box-icon" style={{ marginBottom: '1rem', display: 'flex', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center' }}>
                        {renderIcon()}
                    </div>

                    <RichText
                        tagName="h3"
                        value={title}
                        onChange={(value) => setAttributes({ title: value })}
                        placeholder={__('Feature Title', 'aqualuxe')}
                        style={{ 
                            color: textColor,
                            marginTop: '0',
                            marginBottom: '0.75rem',
                        }}
                    />

                    <RichText
                        tagName="p"
                        value={content}
                        onChange={(value) => setAttributes({ content: value })}
                        placeholder={__('Feature description goes here...', 'aqualuxe')}
                        style={{ 
                            color: textColor,
                            marginTop: '0',
                        }}
                    />

                    {showLink && (
                        <div className="aqualuxe-feature-box-link" style={{ marginTop: '1rem' }}>
                            <a 
                                href={linkUrl || '#'} 
                                style={{ 
                                    color: accentColor,
                                    textDecoration: 'none',
                                    fontWeight: '500',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                }}
                                onClick={(e) => e.preventDefault()}
                            >
                                {linkText}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ marginLeft: '0.25rem' }}>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    )}
                </div>
            </>
        );
    },

    // Save function
    save: ({ attributes }) => {
        const { 
            title, 
            content, 
            iconType, 
            dashicon, 
            iconUrl, 
            iconSvg,
            backgroundColor, 
            textColor, 
            accentColor,
            borderRadius,
            shadow,
            alignment,
            iconSize,
            linkUrl,
            linkText,
            showLink
        } = attributes;

        // Function to render the icon
        const renderIcon = () => {
            switch (iconType) {
                case 'dashicon':
                    return <span className={`dashicons dashicons-${dashicon}`} style={{ fontSize: iconSize, width: iconSize, height: iconSize, color: accentColor }}></span>;
                case 'image':
                    return iconUrl ? <img src={iconUrl} alt="" style={{ width: iconSize, height: iconSize }} /> : null;
                case 'svg':
                    return <div className="svg-icon" style={{ width: iconSize, height: iconSize, color: accentColor }} dangerouslySetInnerHTML={{ __html: iconSvg }}></div>;
                default:
                    return <span className="dashicons dashicons-admin-customizer" style={{ fontSize: iconSize, width: iconSize, height: iconSize, color: accentColor }}></span>;
            }
        };

        return (
            <div 
                className="aqualuxe-feature-box" 
                style={{ 
                    backgroundColor: backgroundColor,
                    color: textColor,
                    borderRadius: borderRadius + 'px',
                    boxShadow: shadow ? '0 4px 6px rgba(0, 0, 0, 0.1)' : 'none',
                    padding: '2rem',
                    textAlign: alignment,
                }}
            >
                <div className="aqualuxe-feature-box-icon" style={{ marginBottom: '1rem', display: 'flex', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center' }}>
                    {renderIcon()}
                </div>

                <RichText.Content
                    tagName="h3"
                    value={title}
                    style={{ 
                        color: textColor,
                        marginTop: '0',
                        marginBottom: '0.75rem',
                    }}
                />

                <RichText.Content
                    tagName="p"
                    value={content}
                    style={{ 
                        color: textColor,
                        marginTop: '0',
                    }}
                />

                {showLink && linkUrl && (
                    <div className="aqualuxe-feature-box-link" style={{ marginTop: '1rem' }}>
                        <a 
                            href={linkUrl} 
                            style={{ 
                                color: accentColor,
                                textDecoration: 'none',
                                fontWeight: '500',
                                display: 'inline-flex',
                                alignItems: 'center',
                            }}
                        >
                            {linkText}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ marginLeft: '0.25rem' }}>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                )}
            </div>
        );
    },
});