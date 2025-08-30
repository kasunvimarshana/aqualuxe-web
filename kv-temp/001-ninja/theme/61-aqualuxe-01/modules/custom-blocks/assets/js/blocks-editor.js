/**
 * AquaLuxe Custom Blocks - Editor Script
 *
 * @package AquaLuxe
 * @subpackage Modules/CustomBlocks
 * @since 1.0.0
 */

(function(wp) {
    'use strict';

    const { __ } = wp.i18n;
    const { registerBlockType } = wp.blocks;
    const { 
        InspectorControls, 
        MediaUpload, 
        RichText, 
        ColorPalette,
        InnerBlocks,
        BlockControls,
        AlignmentToolbar
    } = wp.blockEditor;
    const { 
        PanelBody, 
        TextControl, 
        SelectControl, 
        RangeControl, 
        ToggleControl,
        Button,
        Toolbar,
        IconButton,
        Placeholder
    } = wp.components;
    const { Fragment } = wp.element;
    const { withSelect } = wp.data;

    // Get enabled blocks from localized script
    const enabledBlocks = window.aqualuxeCustomBlocks ? window.aqualuxeCustomBlocks.enabledBlocks : {};

    /**
     * Register Call to Action Block
     */
    if (enabledBlocks.cta) {
        registerBlockType('aqualuxe/cta', {
            title: __('Call to Action', 'aqualuxe'),
            icon: 'megaphone',
            category: 'aqualuxe-blocks',
            keywords: [
                __('call to action', 'aqualuxe'),
                __('cta', 'aqualuxe'),
                __('button', 'aqualuxe')
            ],
            attributes: {
                title: {
                    type: 'string',
                    default: __('Ready to Get Started?', 'aqualuxe')
                },
                content: {
                    type: 'string',
                    default: __('Join thousands of satisfied customers using our product.', 'aqualuxe')
                },
                buttonText: {
                    type: 'string',
                    default: __('Get Started', 'aqualuxe')
                },
                buttonUrl: {
                    type: 'string',
                    default: '#'
                },
                buttonStyle: {
                    type: 'string',
                    default: 'primary'
                },
                align: {
                    type: 'string',
                    default: 'center'
                },
                backgroundColor: {
                    type: 'string',
                    default: ''
                },
                textColor: {
                    type: 'string',
                    default: ''
                },
                backgroundImage: {
                    type: 'object',
                    default: null
                }
            },
            
            edit: function(props) {
                const { attributes, setAttributes } = props;
                const {
                    title,
                    content,
                    buttonText,
                    buttonUrl,
                    buttonStyle,
                    align,
                    backgroundColor,
                    textColor,
                    backgroundImage
                } = attributes;
                
                // Update title
                function onChangeTitle(newTitle) {
                    setAttributes({ title: newTitle });
                }
                
                // Update content
                function onChangeContent(newContent) {
                    setAttributes({ content: newContent });
                }
                
                // Update button text
                function onChangeButtonText(newButtonText) {
                    setAttributes({ buttonText: newButtonText });
                }
                
                // Update button URL
                function onChangeButtonUrl(newButtonUrl) {
                    setAttributes({ buttonUrl: newButtonUrl });
                }
                
                // Update button style
                function onChangeButtonStyle(newButtonStyle) {
                    setAttributes({ buttonStyle: newButtonStyle });
                }
                
                // Update alignment
                function onChangeAlign(newAlign) {
                    setAttributes({ align: newAlign });
                }
                
                // Update background color
                function onChangeBackgroundColor(newBackgroundColor) {
                    setAttributes({ backgroundColor: newBackgroundColor });
                }
                
                // Update text color
                function onChangeTextColor(newTextColor) {
                    setAttributes({ textColor: newTextColor });
                }
                
                // Update background image
                function onSelectBackgroundImage(media) {
                    setAttributes({
                        backgroundImage: {
                            id: media.id,
                            url: media.url,
                            alt: media.alt
                        }
                    });
                }
                
                // Remove background image
                function onRemoveBackgroundImage() {
                    setAttributes({ backgroundImage: null });
                }
                
                // Set inline styles
                const blockStyle = {
                    backgroundColor: backgroundColor || undefined,
                    color: textColor || undefined,
                    backgroundImage: backgroundImage ? `url(${backgroundImage.url})` : undefined,
                    backgroundSize: backgroundImage ? 'cover' : undefined,
                    backgroundPosition: backgroundImage ? 'center' : undefined,
                    textAlign: align
                };
                
                return (
                    <Fragment>
                        <InspectorControls>
                            <PanelBody title={__('CTA Settings', 'aqualuxe')}>
                                <SelectControl
                                    label={__('Button Style', 'aqualuxe')}
                                    value={buttonStyle}
                                    options={[
                                        { label: __('Primary', 'aqualuxe'), value: 'primary' },
                                        { label: __('Secondary', 'aqualuxe'), value: 'secondary' },
                                        { label: __('Outline Primary', 'aqualuxe'), value: 'outline-primary' },
                                        { label: __('Outline Secondary', 'aqualuxe'), value: 'outline-secondary' }
                                    ]}
                                    onChange={onChangeButtonStyle}
                                />
                                
                                <SelectControl
                                    label={__('Alignment', 'aqualuxe')}
                                    value={align}
                                    options={[
                                        { label: __('Left', 'aqualuxe'), value: 'left' },
                                        { label: __('Center', 'aqualuxe'), value: 'center' },
                                        { label: __('Right', 'aqualuxe'), value: 'right' }
                                    ]}
                                    onChange={onChangeAlign}
                                />
                            </PanelBody>
                            
                            <PanelBody title={__('Colors', 'aqualuxe')}>
                                <p>{__('Background Color', 'aqualuxe')}</p>
                                <ColorPalette
                                    value={backgroundColor}
                                    onChange={onChangeBackgroundColor}
                                />
                                
                                <p>{__('Text Color', 'aqualuxe')}</p>
                                <ColorPalette
                                    value={textColor}
                                    onChange={onChangeTextColor}
                                />
                            </PanelBody>
                            
                            <PanelBody title={__('Background Image', 'aqualuxe')}>
                                {!backgroundImage && (
                                    <MediaUpload
                                        onSelect={onSelectBackgroundImage}
                                        type="image"
                                        render={({ open }) => (
                                            <Button
                                                onClick={open}
                                                className="editor-media-placeholder__button is-button is-default is-large"
                                            >
                                                {__('Select Background Image', 'aqualuxe')}
                                            </Button>
                                        )}
                                    />
                                )}
                                
                                {backgroundImage && (
                                    <Fragment>
                                        <img
                                            src={backgroundImage.url}
                                            alt={backgroundImage.alt}
                                            style={{ maxWidth: '100%', marginBottom: '10px' }}
                                        />
                                        <Button
                                            isLink
                                            isDestructive
                                            onClick={onRemoveBackgroundImage}
                                        >
                                            {__('Remove Background Image', 'aqualuxe')}
                                        </Button>
                                    </Fragment>
                                )}
                            </PanelBody>
                        </InspectorControls>
                        
                        <BlockControls>
                            <AlignmentToolbar
                                value={align}
                                onChange={onChangeAlign}
                            />
                        </BlockControls>
                        
                        <div className={`aqualuxe-block aqualuxe-cta aqualuxe-cta-align-${align}`} style={blockStyle}>
                            <div className="aqualuxe-cta-container">
                                <RichText
                                    tagName="h2"
                                    className="aqualuxe-cta-title"
                                    value={title}
                                    onChange={onChangeTitle}
                                    placeholder={__('Add title...', 'aqualuxe')}
                                />
                                
                                <RichText
                                    tagName="div"
                                    className="aqualuxe-cta-content"
                                    value={content}
                                    onChange={onChangeContent}
                                    placeholder={__('Add content...', 'aqualuxe')}
                                />
                                
                                <div className="aqualuxe-cta-button-wrap">
                                    <div className="aqualuxe-cta-button-editor">
                                        <RichText
                                            tagName="span"
                                            className={`aqualuxe-cta-button btn btn-${buttonStyle}`}
                                            value={buttonText}
                                            onChange={onChangeButtonText}
                                            placeholder={__('Add button text...', 'aqualuxe')}
                                        />
                                        
                                        <TextControl
                                            label={__('Button URL', 'aqualuxe')}
                                            value={buttonUrl}
                                            onChange={onChangeButtonUrl}
                                            className="aqualuxe-cta-button-url"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Fragment>
                );
            },
            
            save: function() {
                // Dynamic block, render through PHP
                return null;
            }
        });
    }

    /**
     * Register Features Block
     */
    if (enabledBlocks.features) {
        registerBlockType('aqualuxe/features', {
            title: __('Features', 'aqualuxe'),
            icon: 'star-filled',
            category: 'aqualuxe-blocks',
            keywords: [
                __('features', 'aqualuxe'),
                __('services', 'aqualuxe'),
                __('benefits', 'aqualuxe')
            ],
            attributes: {
                title: {
                    type: 'string',
                    default: __('Our Features', 'aqualuxe')
                },
                subtitle: {
                    type: 'string',
                    default: __('Discover what makes us different', 'aqualuxe')
                },
                columns: {
                    type: 'number',
                    default: 3
                },
                features: {
                    type: 'array',
                    default: [
                        {
                            icon: 'fas fa-rocket',
                            title: __('Fast Performance', 'aqualuxe'),
                            description: __('Lightning fast loading times for better user experience and SEO.', 'aqualuxe')
                        },
                        {
                            icon: 'fas fa-mobile-alt',
                            title: __('Responsive Design', 'aqualuxe'),
                            description: __('Looks great on all devices, from mobile phones to desktop computers.', 'aqualuxe')
                        },
                        {
                            icon: 'fas fa-lock',
                            title: __('Secure & Reliable', 'aqualuxe'),
                            description: __('Built with security in mind to keep your data safe and secure.', 'aqualuxe')
                        }
                    ]
                },
                align: {
                    type: 'string',
                    default: 'center'
                },
                iconStyle: {
                    type: 'string',
                    default: 'circle'
                }
            },
            
            edit: function(props) {
                const { attributes, setAttributes } = props;
                const {
                    title,
                    subtitle,
                    columns,
                    features,
                    align,
                    iconStyle
                } = attributes;
                
                // Update title
                function onChangeTitle(newTitle) {
                    setAttributes({ title: newTitle });
                }
                
                // Update subtitle
                function onChangeSubtitle(newSubtitle) {
                    setAttributes({ subtitle: newSubtitle });
                }
                
                // Update columns
                function onChangeColumns(newColumns) {
                    setAttributes({ columns: newColumns });
                }
                
                // Update alignment
                function onChangeAlign(newAlign) {
                    setAttributes({ align: newAlign });
                }
                
                // Update icon style
                function onChangeIconStyle(newIconStyle) {
                    setAttributes({ iconStyle: newIconStyle });
                }
                
                // Update feature
                function updateFeature(index, property, value) {
                    const newFeatures = [...features];
                    
                    if (!newFeatures[index]) {
                        newFeatures[index] = {};
                    }
                    
                    newFeatures[index][property] = value;
                    setAttributes({ features: newFeatures });
                }
                
                // Add new feature
                function addFeature() {
                    const newFeatures = [...features, {
                        icon: 'fas fa-star',
                        title: __('New Feature', 'aqualuxe'),
                        description: __('Description of the new feature.', 'aqualuxe')
                    }];
                    
                    setAttributes({ features: newFeatures });
                }
                
                // Remove feature
                function removeFeature(index) {
                    const newFeatures = [...features];
                    newFeatures.splice(index, 1);
                    setAttributes({ features: newFeatures });
                }
                
                return (
                    <Fragment>
                        <InspectorControls>
                            <PanelBody title={__('Features Settings', 'aqualuxe')}>
                                <RangeControl
                                    label={__('Columns', 'aqualuxe')}
                                    value={columns}
                                    onChange={onChangeColumns}
                                    min={1}
                                    max={4}
                                />
                                
                                <SelectControl
                                    label={__('Alignment', 'aqualuxe')}
                                    value={align}
                                    options={[
                                        { label: __('Left', 'aqualuxe'), value: 'left' },
                                        { label: __('Center', 'aqualuxe'), value: 'center' },
                                        { label: __('Right', 'aqualuxe'), value: 'right' }
                                    ]}
                                    onChange={onChangeAlign}
                                />
                                
                                <SelectControl
                                    label={__('Icon Style', 'aqualuxe')}
                                    value={iconStyle}
                                    options={[
                                        { label: __('Circle', 'aqualuxe'), value: 'circle' },
                                        { label: __('Square', 'aqualuxe'), value: 'square' },
                                        { label: __('Rounded', 'aqualuxe'), value: 'rounded' },
                                        { label: __('None', 'aqualuxe'), value: 'none' }
                                    ]}
                                    onChange={onChangeIconStyle}
                                />
                            </PanelBody>
                            
                            <PanelBody title={__('Features', 'aqualuxe')}>
                                {features.map((feature, index) => (
                                    <PanelBody
                                        key={index}
                                        title={feature.title || __('Feature', 'aqualuxe') + ' ' + (index + 1)}
                                        initialOpen={false}
                                    >
                                        <TextControl
                                            label={__('Icon Class', 'aqualuxe')}
                                            help={__('Enter Font Awesome icon class (e.g., fas fa-star)', 'aqualuxe')}
                                            value={feature.icon || ''}
                                            onChange={(value) => updateFeature(index, 'icon', value)}
                                        />
                                        
                                        <TextControl
                                            label={__('Title', 'aqualuxe')}
                                            value={feature.title || ''}
                                            onChange={(value) => updateFeature(index, 'title', value)}
                                        />
                                        
                                        <TextControl
                                            label={__('Description', 'aqualuxe')}
                                            value={feature.description || ''}
                                            onChange={(value) => updateFeature(index, 'description', value)}
                                        />
                                        
                                        <Button
                                            isLink
                                            isDestructive
                                            onClick={() => removeFeature(index)}
                                        >
                                            {__('Remove Feature', 'aqualuxe')}
                                        </Button>
                                    </PanelBody>
                                ))}
                                
                                <Button
                                    isPrimary
                                    onClick={addFeature}
                                >
                                    {__('Add Feature', 'aqualuxe')}
                                </Button>
                            </PanelBody>
                        </InspectorControls>
                        
                        <BlockControls>
                            <AlignmentToolbar
                                value={align}
                                onChange={onChangeAlign}
                            />
                        </BlockControls>
                        
                        <div className={`aqualuxe-block aqualuxe-features aqualuxe-features-align-${align} aqualuxe-features-icon-${iconStyle} aqualuxe-features-columns-${columns}`}>
                            <div className="aqualuxe-features-container">
                                <div className="aqualuxe-features-header">
                                    <RichText
                                        tagName="h2"
                                        className="aqualuxe-features-title"
                                        value={title}
                                        onChange={onChangeTitle}
                                        placeholder={__('Add title...', 'aqualuxe')}
                                    />
                                    
                                    <RichText
                                        tagName="div"
                                        className="aqualuxe-features-subtitle"
                                        value={subtitle}
                                        onChange={onChangeSubtitle}
                                        placeholder={__('Add subtitle...', 'aqualuxe')}
                                    />
                                </div>
                                
                                <div className="aqualuxe-features-grid">
                                    {features.map((feature, index) => (
                                        <div className="aqualuxe-feature" key={index}>
                                            {feature.icon && (
                                                <div className="aqualuxe-feature-icon">
                                                    <i className={feature.icon}></i>
                                                </div>
                                            )}
                                            
                                            <div className="aqualuxe-feature-content">
                                                <TextControl
                                                    className="aqualuxe-feature-title-input"
                                                    placeholder={__('Feature title...', 'aqualuxe')}
                                                    value={feature.title || ''}
                                                    onChange={(value) => updateFeature(index, 'title', value)}
                                                />
                                                
                                                <TextControl
                                                    className="aqualuxe-feature-description-input"
                                                    placeholder={__('Feature description...', 'aqualuxe')}
                                                    value={feature.description || ''}
                                                    onChange={(value) => updateFeature(index, 'description', value)}
                                                />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                
                                <Button
                                    isPrimary
                                    onClick={addFeature}
                                    className="aqualuxe-add-feature-button"
                                >
                                    {__('Add Feature', 'aqualuxe')}
                                </Button>
                            </div>
                        </div>
                    </Fragment>
                );
            },
            
            save: function() {
                // Dynamic block, render through PHP
                return null;
            }
        });
    }

    // Register Team Members Block
    if (enabledBlocks.team) {
        registerBlockType('aqualuxe/team', {
            title: __('Team Members', 'aqualuxe'),
            icon: 'groups',
            category: 'aqualuxe-blocks',
            keywords: [
                __('team', 'aqualuxe'),
                __('members', 'aqualuxe'),
                __('staff', 'aqualuxe')
            ],
            attributes: {
                title: {
                    type: 'string',
                    default: __('Our Team', 'aqualuxe')
                },
                subtitle: {
                    type: 'string',
                    default: __('Meet our talented team members', 'aqualuxe')
                },
                columns: {
                    type: 'number',
                    default: 3
                },
                members: {
                    type: 'array',
                    default: [
                        {
                            name: __('John Doe', 'aqualuxe'),
                            position: __('CEO & Founder', 'aqualuxe'),
                            bio: __('John has over 15 years of experience in the industry.', 'aqualuxe'),
                            image: null,
                            social: [
                                { icon: 'fab fa-twitter', url: '#' },
                                { icon: 'fab fa-linkedin', url: '#' }
                            ]
                        },
                        {
                            name: __('Jane Smith', 'aqualuxe'),
                            position: __('Creative Director', 'aqualuxe'),
                            bio: __('Jane leads our creative team with passion and innovation.', 'aqualuxe'),
                            image: null,
                            social: [
                                { icon: 'fab fa-twitter', url: '#' },
                                { icon: 'fab fa-linkedin', url: '#' }
                            ]
                        }
                    ]
                },
                style: {
                    type: 'string',
                    default: 'card'
                },
                showSocial: {
                    type: 'boolean',
                    default: true
                }
            },
            
            edit: function(props) {
                const { attributes, setAttributes } = props;
                const {
                    title,
                    subtitle,
                    columns,
                    members,
                    style,
                    showSocial
                } = attributes;
                
                // Update title
                function onChangeTitle(newTitle) {
                    setAttributes({ title: newTitle });
                }
                
                // Update subtitle
                function onChangeSubtitle(newSubtitle) {
                    setAttributes({ subtitle: newSubtitle });
                }
                
                // Update columns
                function onChangeColumns(newColumns) {
                    setAttributes({ columns: newColumns });
                }
                
                // Update style
                function onChangeStyle(newStyle) {
                    setAttributes({ style: newStyle });
                }
                
                // Update show social
                function onChangeShowSocial(newShowSocial) {
                    setAttributes({ showSocial: newShowSocial });
                }
                
                // Update member
                function updateMember(index, property, value) {
                    const newMembers = [...members];
                    
                    if (!newMembers[index]) {
                        newMembers[index] = {};
                    }
                    
                    newMembers[index][property] = value;
                    setAttributes({ members: newMembers });
                }
                
                // Update member social
                function updateMemberSocial(memberIndex, socialIndex, property, value) {
                    const newMembers = [...members];
                    
                    if (!newMembers[memberIndex]) {
                        newMembers[memberIndex] = {};
                    }
                    
                    if (!newMembers[memberIndex].social) {
                        newMembers[memberIndex].social = [];
                    }
                    
                    if (!newMembers[memberIndex].social[socialIndex]) {
                        newMembers[memberIndex].social[socialIndex] = {};
                    }
                    
                    newMembers[memberIndex].social[socialIndex][property] = value;
                    setAttributes({ members: newMembers });
                }
                
                // Add new social link
                function addSocialLink(memberIndex) {
                    const newMembers = [...members];
                    
                    if (!newMembers[memberIndex]) {
                        newMembers[memberIndex] = {};
                    }
                    
                    if (!newMembers[memberIndex].social) {
                        newMembers[memberIndex].social = [];
                    }
                    
                    newMembers[memberIndex].social.push({
                        icon: 'fab fa-facebook',
                        url: '#'
                    });
                    
                    setAttributes({ members: newMembers });
                }
                
                // Remove social link
                function removeSocialLink(memberIndex, socialIndex) {
                    const newMembers = [...members];
                    
                    if (newMembers[memberIndex] && newMembers[memberIndex].social) {
                        newMembers[memberIndex].social.splice(socialIndex, 1);
                        setAttributes({ members: newMembers });
                    }
                }
                
                // Add new member
                function addMember() {
                    const newMembers = [...members, {
                        name: __('New Member', 'aqualuxe'),
                        position: __('Position', 'aqualuxe'),
                        bio: __('Bio information about this team member.', 'aqualuxe'),
                        image: null,
                        social: [
                            { icon: 'fab fa-twitter', url: '#' },
                            { icon: 'fab fa-linkedin', url: '#' }
                        ]
                    }];
                    
                    setAttributes({ members: newMembers });
                }
                
                // Remove member
                function removeMember(index) {
                    const newMembers = [...members];
                    newMembers.splice(index, 1);
                    setAttributes({ members: newMembers });
                }
                
                return (
                    <Fragment>
                        <InspectorControls>
                            <PanelBody title={__('Team Settings', 'aqualuxe')}>
                                <RangeControl
                                    label={__('Columns', 'aqualuxe')}
                                    value={columns}
                                    onChange={onChangeColumns}
                                    min={1}
                                    max={4}
                                />
                                
                                <SelectControl
                                    label={__('Style', 'aqualuxe')}
                                    value={style}
                                    options={[
                                        { label: __('Card', 'aqualuxe'), value: 'card' },
                                        { label: __('Minimal', 'aqualuxe'), value: 'minimal' },
                                        { label: __('Circle', 'aqualuxe'), value: 'circle' }
                                    ]}
                                    onChange={onChangeStyle}
                                />
                                
                                <ToggleControl
                                    label={__('Show Social Links', 'aqualuxe')}
                                    checked={showSocial}
                                    onChange={onChangeShowSocial}
                                />
                            </PanelBody>
                            
                            <PanelBody title={__('Team Members', 'aqualuxe')}>
                                {members.map((member, index) => (
                                    <PanelBody
                                        key={index}
                                        title={member.name || __('Team Member', 'aqualuxe') + ' ' + (index + 1)}
                                        initialOpen={false}
                                    >
                                        <MediaUpload
                                            onSelect={(media) => {
                                                updateMember(index, 'image', {
                                                    id: media.id,
                                                    url: media.url,
                                                    alt: media.alt
                                                });
                                            }}
                                            type="image"
                                            render={({ open }) => (
                                                <div>
                                                    {!member.image && (
                                                        <Button
                                                            onClick={open}
                                                            className="editor-media-placeholder__button is-button is-default is-large"
                                                        >
                                                            {__('Select Image', 'aqualuxe')}
                                                        </Button>
                                                    )}
                                                    
                                                    {member.image && (
                                                        <div>
                                                            <img
                                                                src={member.image.url}
                                                                alt={member.image.alt}
                                                                style={{ maxWidth: '100%', marginBottom: '10px' }}
                                                            />
                                                            <div>
                                                                <Button
                                                                    isSecondary
                                                                    onClick={open}
                                                                >
                                                                    {__('Replace Image', 'aqualuxe')}
                                                                </Button>
                                                                <Button
                                                                    isLink
                                                                    isDestructive
                                                                    onClick={() => updateMember(index, 'image', null)}
                                                                >
                                                                    {__('Remove Image', 'aqualuxe')}
                                                                </Button>
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                            )}
                                        />
                                        
                                        <TextControl
                                            label={__('Name', 'aqualuxe')}
                                            value={member.name || ''}
                                            onChange={(value) => updateMember(index, 'name', value)}
                                        />
                                        
                                        <TextControl
                                            label={__('Position', 'aqualuxe')}
                                            value={member.position || ''}
                                            onChange={(value) => updateMember(index, 'position', value)}
                                        />
                                        
                                        <TextControl
                                            label={__('Bio', 'aqualuxe')}
                                            value={member.bio || ''}
                                            onChange={(value) => updateMember(index, 'bio', value)}
                                        />
                                        
                                        {showSocial && (
                                            <div>
                                                <p>{__('Social Links', 'aqualuxe')}</p>
                                                
                                                {member.social && member.social.map((social, socialIndex) => (
                                                    <div key={socialIndex} style={{ marginBottom: '10px' }}>
                                                        <TextControl
                                                            label={__('Icon Class', 'aqualuxe')}
                                                            help={__('Enter Font Awesome icon class (e.g., fab fa-twitter)', 'aqualuxe')}
                                                            value={social.icon || ''}
                                                            onChange={(value) => updateMemberSocial(index, socialIndex, 'icon', value)}
                                                        />
                                                        
                                                        <TextControl
                                                            label={__('URL', 'aqualuxe')}
                                                            value={social.url || ''}
                                                            onChange={(value) => updateMemberSocial(index, socialIndex, 'url', value)}
                                                        />
                                                        
                                                        <Button
                                                            isLink
                                                            isDestructive
                                                            onClick={() => removeSocialLink(index, socialIndex)}
                                                        >
                                                            {__('Remove Social Link', 'aqualuxe')}
                                                        </Button>
                                                    </div>
                                                ))}
                                                
                                                <Button
                                                    isSecondary
                                                    onClick={() => addSocialLink(index)}
                                                >
                                                    {__('Add Social Link', 'aqualuxe')}
                                                </Button>
                                            </div>
                                        )}
                                        
                                        <Button
                                            isLink
                                            isDestructive
                                            onClick={() => removeMember(index)}
                                        >
                                            {__('Remove Team Member', 'aqualuxe')}
                                        </Button>
                                    </PanelBody>
                                ))}
                                
                                <Button
                                    isPrimary
                                    onClick={addMember}
                                >
                                    {__('Add Team Member', 'aqualuxe')}
                                </Button>
                            </PanelBody>
                        </InspectorControls>
                        
                        <div className={`aqualuxe-block aqualuxe-team aqualuxe-team-style-${style} aqualuxe-team-columns-${columns}`}>
                            <div className="aqualuxe-team-container">
                                <div className="aqualuxe-team-header">
                                    <RichText
                                        tagName="h2"
                                        className="aqualuxe-team-title"
                                        value={title}
                                        onChange={onChangeTitle}
                                        placeholder={__('Add title...', 'aqualuxe')}
                                    />
                                    
                                    <RichText
                                        tagName="div"
                                        className="aqualuxe-team-subtitle"
                                        value={subtitle}
                                        onChange={onChangeSubtitle}
                                        placeholder={__('Add subtitle...', 'aqualuxe')}
                                    />
                                </div>
                                
                                <div className="aqualuxe-team-grid">
                                    {members.map((member, index) => (
                                        <div className="aqualuxe-team-member" key={index}>
                                            {member.image ? (
                                                <div className="aqualuxe-team-member-image">
                                                    <img
                                                        src={member.image.url}
                                                        alt={member.name}
                                                    />
                                                </div>
                                            ) : (
                                                <div className="aqualuxe-team-member-image aqualuxe-team-member-image-placeholder">
                                                    <i className="fas fa-user"></i>
                                                </div>
                                            )}
                                            
                                            <div className="aqualuxe-team-member-content">
                                                <TextControl
                                                    className="aqualuxe-team-member-name-input"
                                                    placeholder={__('Member name...', 'aqualuxe')}
                                                    value={member.name || ''}
                                                    onChange={(value) => updateMember(index, 'name', value)}
                                                />
                                                
                                                <TextControl
                                                    className="aqualuxe-team-member-position-input"
                                                    placeholder={__('Member position...', 'aqualuxe')}
                                                    value={member.position || ''}
                                                    onChange={(value) => updateMember(index, 'position', value)}
                                                />
                                                
                                                <TextControl
                                                    className="aqualuxe-team-member-bio-input"
                                                    placeholder={__('Member bio...', 'aqualuxe')}
                                                    value={member.bio || ''}
                                                    onChange={(value) => updateMember(index, 'bio', value)}
                                                />
                                                
                                                {showSocial && member.social && (
                                                    <div className="aqualuxe-team-member-social">
                                                        {member.social.map((social, socialIndex) => (
                                                            <span key={socialIndex} className="aqualuxe-team-member-social-link">
                                                                <i className={social.icon}></i>
                                                            </span>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                
                                <Button
                                    isPrimary
                                    onClick={addMember}
                                    className="aqualuxe-add-member-button"
                                >
                                    {__('Add Team Member', 'aqualuxe')}
                                </Button>
                            </div>
                        </div>
                    </Fragment>
                );
            },
            
            save: function() {
                // Dynamic block, render through PHP
                return null;
            }
        });
    }

    // Add more blocks here...

})(window.wp);