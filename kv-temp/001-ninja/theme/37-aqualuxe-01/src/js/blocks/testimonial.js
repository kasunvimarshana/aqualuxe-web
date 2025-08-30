/**
 * AquaLuxe Testimonial Block
 * A custom Gutenberg block for displaying testimonials
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
registerBlockType('aqualuxe/testimonial', {
    title: __('Testimonial', 'aqualuxe'),
    description: __('Display a customer testimonial with quote, author, and optional image.', 'aqualuxe'),
    category: 'aqualuxe',
    icon: 'format-quote',
    keywords: [
        __('testimonial', 'aqualuxe'),
        __('quote', 'aqualuxe'),
        __('review', 'aqualuxe'),
        __('aqualuxe', 'aqualuxe'),
    ],
    supports: {
        align: ['wide', 'full'],
        html: false,
    },
    attributes: {
        quote: {
            type: 'string',
            source: 'html',
            selector: '.testimonial-quote',
            default: __('The AquaLuxe experience was absolutely transformative. I left feeling completely rejuvenated and pampered. The staff was attentive and the facilities were immaculate.', 'aqualuxe'),
        },
        author: {
            type: 'string',
            source: 'html',
            selector: '.testimonial-author',
            default: __('Jane Smith', 'aqualuxe'),
        },
        role: {
            type: 'string',
            source: 'html',
            selector: '.testimonial-role',
            default: __('Valued Customer', 'aqualuxe'),
        },
        showImage: {
            type: 'boolean',
            default: true,
        },
        imageUrl: {
            type: 'string',
            default: '',
        },
        imageId: {
            type: 'number',
        },
        imageShape: {
            type: 'string',
            default: 'circle',
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
            default: 'left',
        },
        rating: {
            type: 'number',
            default: 5,
        },
        showRating: {
            type: 'boolean',
            default: true,
        },
        style: {
            type: 'string',
            default: 'modern',
        },
    },

    // Edit function
    edit: ({ attributes, setAttributes }) => {
        const { 
            quote, 
            author, 
            role,
            showImage,
            imageUrl,
            imageShape,
            backgroundColor, 
            textColor, 
            accentColor,
            borderRadius,
            shadow,
            alignment,
            rating,
            showRating,
            style
        } = attributes;

        // Function to render the rating stars
        const renderRating = (rating) => {
            const stars = [];
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars.push(
                        <span key={i} className="star star-filled" style={{ color: accentColor }}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </span>
                    );
                } else {
                    stars.push(
                        <span key={i} className="star star-empty" style={{ color: '#ccc' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </span>
                    );
                }
            }
            return stars;
        };

        // Get the appropriate classes based on the style
        const getStyleClasses = () => {
            switch (style) {
                case 'modern':
                    return 'testimonial-modern';
                case 'classic':
                    return 'testimonial-classic';
                case 'minimal':
                    return 'testimonial-minimal';
                case 'card':
                    return 'testimonial-card';
                default:
                    return 'testimonial-modern';
            }
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Testimonial Settings', 'aqualuxe')}>
                        <SelectControl
                            label={__('Style', 'aqualuxe')}
                            value={style}
                            options={[
                                { label: __('Modern', 'aqualuxe'), value: 'modern' },
                                { label: __('Classic', 'aqualuxe'), value: 'classic' },
                                { label: __('Minimal', 'aqualuxe'), value: 'minimal' },
                                { label: __('Card', 'aqualuxe'), value: 'card' },
                            ]}
                            onChange={(value) => setAttributes({ style: value })}
                        />

                        <ToggleControl
                            label={__('Show Author Image', 'aqualuxe')}
                            checked={showImage}
                            onChange={() => setAttributes({ showImage: !showImage })}
                        />

                        {showImage && (
                            <>
                                <div className="editor-post-featured-image">
                                    <MediaUpload
                                        onSelect={(media) => setAttributes({ imageUrl: media.url, imageId: media.id })}
                                        type="image"
                                        value={attributes.imageId}
                                        render={({ open }) => (
                                            <Button
                                                className={!attributes.imageId ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview'}
                                                onClick={open}
                                            >
                                                {!attributes.imageId && __('Select Image', 'aqualuxe')}
                                                {attributes.imageUrl && <img src={attributes.imageUrl} alt="" />}
                                            </Button>
                                        )}
                                    />
                                    {attributes.imageId && (
                                        <Button
                                            isDestructive
                                            isSmall
                                            onClick={() => setAttributes({ imageUrl: '', imageId: undefined })}
                                        >
                                            {__('Remove Image', 'aqualuxe')}
                                        </Button>
                                    )}
                                </div>

                                <SelectControl
                                    label={__('Image Shape', 'aqualuxe')}
                                    value={imageShape}
                                    options={[
                                        { label: __('Circle', 'aqualuxe'), value: 'circle' },
                                        { label: __('Square', 'aqualuxe'), value: 'square' },
                                        { label: __('Rounded', 'aqualuxe'), value: 'rounded' },
                                    ]}
                                    onChange={(value) => setAttributes({ imageShape: value })}
                                />
                            </>
                        )}

                        <ToggleControl
                            label={__('Show Rating', 'aqualuxe')}
                            checked={showRating}
                            onChange={() => setAttributes({ showRating: !showRating })}
                        />

                        {showRating && (
                            <RangeControl
                                label={__('Rating', 'aqualuxe')}
                                value={rating}
                                onChange={(value) => setAttributes({ rating: value })}
                                min={1}
                                max={5}
                                step={1}
                            />
                        )}
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
                </InspectorControls>

                <BlockControls>
                    <AlignmentToolbar
                        value={alignment}
                        onChange={(value) => setAttributes({ alignment: value })}
                    />
                </BlockControls>

                <div 
                    className={`aqualuxe-testimonial ${getStyleClasses()}`}
                    style={{ 
                        backgroundColor: backgroundColor,
                        color: textColor,
                        borderRadius: borderRadius + 'px',
                        boxShadow: shadow ? '0 4px 6px rgba(0, 0, 0, 0.1)' : 'none',
                        padding: '2rem',
                        textAlign: alignment,
                    }}
                >
                    {style === 'modern' && (
                        <div className="testimonial-quote-icon" style={{ color: accentColor, marginBottom: '1rem', opacity: 0.3 }}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                                <path d="M6.5 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L9.758 4.03c0 0-.218.052-.597.144C8.97 4.222 8.737 4.278 8.472 4.345c-.271.05-.56.187-.882.312C7.272 4.799 6.904 4.895 6.562 5.123c-.344.218-.741.4-1.091.692-.339.301-.748.562-1.05.945-.33.358-.656.734-.909 1.162C3.249 8.343 3.05 8.77 2.9 9.202c-.145.435-.26.88-.328 1.344-.075.469-.075.979-.075 1.468.001.498.101.967.254 1.414.159.443.401.837.709 1.153.305.325.688.574 1.138.731.466.155.939.249 1.466.283.352.021.698.021 1.023.021.002 0 .005 0 .008 0 .319 0 .658.001 1.01-.021.534-.033 1.061-.129 1.549-.31.495-.18.923-.437 1.252-.779.328-.343.602-.758.702-1.222.104-.47.138-.956.138-1.468C12.746 11.663 12.75 10 6.5 10zM18 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L21.258 4.03c0 0-.218.052-.597.144-.191.048-.424.104-.689.171-.271.05-.56.187-.882.312-.317.143-.686.238-1.028.467-.344.218-.741.4-1.091.692-.339.301-.748.562-1.05.945-.33.358-.656.734-.909 1.162-.351.404-.55.832-.7 1.263-.145.435-.26.88-.328 1.344-.075.469-.075.979-.075 1.468.001.498.101.967.254 1.414.159.443.401.837.709 1.153.305.325.688.574 1.138.731.466.155.939.249 1.466.283.352.021.698.021 1.023.021.002 0 .005 0 .008 0 .319 0 .658.001 1.01-.021.534-.033 1.061-.129 1.549-.31.495-.18.923-.437 1.252-.779.328-.343.602-.758.702-1.222.104-.47.138-.956.138-1.468C24.246 11.663 24.25 10 18 10z" />
                            </svg>
                        </div>
                    )}

                    <RichText
                        tagName="div"
                        className="testimonial-quote"
                        value={quote}
                        onChange={(value) => setAttributes({ quote: value })}
                        placeholder={__('Enter testimonial text here...', 'aqualuxe')}
                        style={{ 
                            color: textColor,
                            marginBottom: '1.5rem',
                            fontSize: '1.125rem',
                            fontStyle: 'italic',
                            position: 'relative',
                        }}
                    />

                    {showRating && (
                        <div className="testimonial-rating" style={{ marginBottom: '1rem', display: 'flex', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center', gap: '0.25rem' }}>
                            {renderRating(rating)}
                        </div>
                    )}

                    <div className="testimonial-author-container" style={{ display: 'flex', alignItems: 'center', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center', gap: '1rem' }}>
                        {showImage && (
                            <div className="testimonial-image">
                                {imageUrl ? (
                                    <img 
                                        src={imageUrl} 
                                        alt="" 
                                        style={{ 
                                            width: '64px', 
                                            height: '64px', 
                                            objectFit: 'cover',
                                            borderRadius: imageShape === 'circle' ? '50%' : imageShape === 'rounded' ? '8px' : '0',
                                            border: `2px solid ${accentColor}`
                                        }} 
                                    />
                                ) : (
                                    <div 
                                        style={{ 
                                            width: '64px', 
                                            height: '64px', 
                                            backgroundColor: '#eee',
                                            borderRadius: imageShape === 'circle' ? '50%' : imageShape === 'rounded' ? '8px' : '0',
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            border: `2px solid ${accentColor}`
                                        }}
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                )}
                            </div>
                        )}

                        <div className="testimonial-author-info">
                            <RichText
                                tagName="div"
                                className="testimonial-author"
                                value={author}
                                onChange={(value) => setAttributes({ author: value })}
                                placeholder={__('Author Name', 'aqualuxe')}
                                style={{ 
                                    color: textColor,
                                    fontWeight: 'bold',
                                    fontSize: '1rem',
                                }}
                            />

                            <RichText
                                tagName="div"
                                className="testimonial-role"
                                value={role}
                                onChange={(value) => setAttributes({ role: value })}
                                placeholder={__('Author Role', 'aqualuxe')}
                                style={{ 
                                    color: textColor,
                                    opacity: 0.8,
                                    fontSize: '0.875rem',
                                }}
                            />
                        </div>
                    </div>
                </div>
            </>
        );
    },

    // Save function
    save: ({ attributes }) => {
        const { 
            quote, 
            author, 
            role,
            showImage,
            imageUrl,
            imageShape,
            backgroundColor, 
            textColor, 
            accentColor,
            borderRadius,
            shadow,
            alignment,
            rating,
            showRating,
            style
        } = attributes;

        // Function to render the rating stars
        const renderRating = (rating) => {
            const stars = [];
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars.push(
                        <span key={i} className="star star-filled" style={{ color: accentColor }}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </span>
                    );
                } else {
                    stars.push(
                        <span key={i} className="star star-empty" style={{ color: '#ccc' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </span>
                    );
                }
            }
            return stars;
        };

        // Get the appropriate classes based on the style
        const getStyleClasses = () => {
            switch (style) {
                case 'modern':
                    return 'testimonial-modern';
                case 'classic':
                    return 'testimonial-classic';
                case 'minimal':
                    return 'testimonial-minimal';
                case 'card':
                    return 'testimonial-card';
                default:
                    return 'testimonial-modern';
            }
        };

        return (
            <div 
                className={`aqualuxe-testimonial ${getStyleClasses()}`}
                style={{ 
                    backgroundColor: backgroundColor,
                    color: textColor,
                    borderRadius: borderRadius + 'px',
                    boxShadow: shadow ? '0 4px 6px rgba(0, 0, 0, 0.1)' : 'none',
                    padding: '2rem',
                    textAlign: alignment,
                }}
            >
                {style === 'modern' && (
                    <div className="testimonial-quote-icon" style={{ color: accentColor, marginBottom: '1rem', opacity: 0.3 }}>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" fill="currentColor">
                            <path d="M6.5 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L9.758 4.03c0 0-.218.052-.597.144C8.97 4.222 8.737 4.278 8.472 4.345c-.271.05-.56.187-.882.312C7.272 4.799 6.904 4.895 6.562 5.123c-.344.218-.741.4-1.091.692-.339.301-.748.562-1.05.945-.33.358-.656.734-.909 1.162C3.249 8.343 3.05 8.77 2.9 9.202c-.145.435-.26.88-.328 1.344-.075.469-.075.979-.075 1.468.001.498.101.967.254 1.414.159.443.401.837.709 1.153.305.325.688.574 1.138.731.466.155.939.249 1.466.283.352.021.698.021 1.023.021.002 0 .005 0 .008 0 .319 0 .658.001 1.01-.021.534-.033 1.061-.129 1.549-.31.495-.18.923-.437 1.252-.779.328-.343.602-.758.702-1.222.104-.47.138-.956.138-1.468C12.746 11.663 12.75 10 6.5 10zM18 10c-.223 0-.437.034-.65.065.069-.232.14-.468.254-.68.114-.308.292-.575.469-.844.148-.291.409-.488.601-.737.201-.242.475-.403.692-.604.213-.21.492-.315.714-.463.232-.133.434-.28.65-.35.208-.086.39-.16.539-.222.302-.125.474-.197.474-.197L21.258 4.03c0 0-.218.052-.597.144-.191.048-.424.104-.689.171-.271.05-.56.187-.882.312-.317.143-.686.238-1.028.467-.344.218-.741.4-1.091.692-.339.301-.748.562-1.05.945-.33.358-.656.734-.909 1.162-.351.404-.55.832-.7 1.263-.145.435-.26.88-.328 1.344-.075.469-.075.979-.075 1.468.001.498.101.967.254 1.414.159.443.401.837.709 1.153.305.325.688.574 1.138.731.466.155.939.249 1.466.283.352.021.698.021 1.023.021.002 0 .005 0 .008 0 .319 0 .658.001 1.01-.021.534-.033 1.061-.129 1.549-.31.495-.18.923-.437 1.252-.779.328-.343.602-.758.702-1.222.104-.47.138-.956.138-1.468C24.246 11.663 24.25 10 18 10z" />
                        </svg>
                    </div>
                )}

                <RichText.Content
                    tagName="div"
                    className="testimonial-quote"
                    value={quote}
                    style={{ 
                        color: textColor,
                        marginBottom: '1.5rem',
                        fontSize: '1.125rem',
                        fontStyle: 'italic',
                        position: 'relative',
                    }}
                />

                {showRating && (
                    <div className="testimonial-rating" style={{ marginBottom: '1rem', display: 'flex', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center', gap: '0.25rem' }}>
                        {renderRating(rating)}
                    </div>
                )}

                <div className="testimonial-author-container" style={{ display: 'flex', alignItems: 'center', justifyContent: alignment === 'left' ? 'flex-start' : alignment === 'right' ? 'flex-end' : 'center', gap: '1rem' }}>
                    {showImage && imageUrl && (
                        <div className="testimonial-image">
                            <img 
                                src={imageUrl} 
                                alt="" 
                                style={{ 
                                    width: '64px', 
                                    height: '64px', 
                                    objectFit: 'cover',
                                    borderRadius: imageShape === 'circle' ? '50%' : imageShape === 'rounded' ? '8px' : '0',
                                    border: `2px solid ${accentColor}`
                                }} 
                            />
                        </div>
                    )}

                    <div className="testimonial-author-info">
                        <RichText.Content
                            tagName="div"
                            className="testimonial-author"
                            value={author}
                            style={{ 
                                color: textColor,
                                fontWeight: 'bold',
                                fontSize: '1rem',
                            }}
                        />

                        <RichText.Content
                            tagName="div"
                            className="testimonial-role"
                            value={role}
                            style={{ 
                                color: textColor,
                                opacity: 0.8,
                                fontSize: '0.875rem',
                            }}
                        />
                    </div>
                </div>
            </div>
        );
    },
});