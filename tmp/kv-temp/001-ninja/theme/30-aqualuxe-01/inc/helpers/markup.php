<?php
/**
 * Markup helper functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Generate schema markup
 *
 * @param string $type The schema type.
 * @return string
 */
function aqualuxe_get_schema_markup($type)
{
    $schema = '';
    switch ($type) {
        case 'html':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/WebPage"';
            break;
        case 'header':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/WPHeader"';
            break;
        case 'nav':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement"';
            break;
        case 'title':
            $schema = 'itemprop="headline"';
            break;
        case 'sidebar':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/WPSideBar"';
            break;
        case 'footer':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/WPFooter"';
            break;
        case 'main':
            $schema = 'itemprop="mainContentOfPage"';
            break;
        case 'author':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Person" itemprop="author"';
            break;
        case 'person':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Person"';
            break;
        case 'comment':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Comment"';
            break;
        case 'post':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/BlogPosting" itemprop="blogPost"';
            break;
        case 'page':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/WebPage"';
            break;
        case 'product':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Product"';
            break;
        case 'name':
            $schema = 'itemprop="name"';
            break;
        case 'url':
            $schema = 'itemprop="url"';
            break;
        case 'image':
            $schema = 'itemprop="image"';
            break;
        case 'thumbnail':
            $schema = 'itemprop="thumbnail"';
            break;
        case 'description':
            $schema = 'itemprop="description"';
            break;
        case 'datePublished':
            $schema = 'itemprop="datePublished"';
            break;
        case 'dateModified':
            $schema = 'itemprop="dateModified"';
            break;
        case 'article':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Article"';
            break;
        case 'breadcrumb':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/BreadcrumbList"';
            break;
        case 'breadcrumbItem':
            $schema = 'itemprop="itemListElement" itemscope="itemscope" itemtype="https://schema.org/ListItem"';
            break;
        case 'breadcrumbName':
            $schema = 'itemprop="name"';
            break;
        case 'breadcrumbUrl':
            $schema = 'itemprop="item"';
            break;
        case 'breadcrumbPosition':
            $schema = 'itemprop="position"';
            break;
        case 'organization':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Organization"';
            break;
        case 'logo':
            $schema = 'itemprop="logo" itemscope="itemscope" itemtype="https://schema.org/ImageObject"';
            break;
        case 'telephone':
            $schema = 'itemprop="telephone"';
            break;
        case 'email':
            $schema = 'itemprop="email"';
            break;
        case 'address':
            $schema = 'itemprop="address" itemscope="itemscope" itemtype="https://schema.org/PostalAddress"';
            break;
        case 'streetAddress':
            $schema = 'itemprop="streetAddress"';
            break;
        case 'addressLocality':
            $schema = 'itemprop="addressLocality"';
            break;
        case 'addressRegion':
            $schema = 'itemprop="addressRegion"';
            break;
        case 'postalCode':
            $schema = 'itemprop="postalCode"';
            break;
        case 'addressCountry':
            $schema = 'itemprop="addressCountry"';
            break;
        case 'contactPoint':
            $schema = 'itemprop="contactPoint" itemscope="itemscope" itemtype="https://schema.org/ContactPoint"';
            break;
        case 'contactType':
            $schema = 'itemprop="contactType"';
            break;
        case 'review':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Review"';
            break;
        case 'reviewRating':
            $schema = 'itemprop="reviewRating" itemscope="itemscope" itemtype="https://schema.org/Rating"';
            break;
        case 'ratingValue':
            $schema = 'itemprop="ratingValue"';
            break;
        case 'bestRating':
            $schema = 'itemprop="bestRating"';
            break;
        case 'worstRating':
            $schema = 'itemprop="worstRating"';
            break;
        case 'reviewBody':
            $schema = 'itemprop="reviewBody"';
            break;
        case 'reviewAuthor':
            $schema = 'itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person"';
            break;
        case 'reviewDate':
            $schema = 'itemprop="datePublished"';
            break;
        case 'offer':
            $schema = 'itemprop="offers" itemscope="itemscope" itemtype="https://schema.org/Offer"';
            break;
        case 'price':
            $schema = 'itemprop="price"';
            break;
        case 'priceCurrency':
            $schema = 'itemprop="priceCurrency"';
            break;
        case 'availability':
            $schema = 'itemprop="availability"';
            break;
        case 'brand':
            $schema = 'itemprop="brand" itemscope="itemscope" itemtype="https://schema.org/Brand"';
            break;
        case 'sku':
            $schema = 'itemprop="sku"';
            break;
        case 'gtin':
            $schema = 'itemprop="gtin"';
            break;
        case 'mpn':
            $schema = 'itemprop="mpn"';
            break;
        case 'event':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Event"';
            break;
        case 'eventName':
            $schema = 'itemprop="name"';
            break;
        case 'eventDescription':
            $schema = 'itemprop="description"';
            break;
        case 'eventStartDate':
            $schema = 'itemprop="startDate"';
            break;
        case 'eventEndDate':
            $schema = 'itemprop="endDate"';
            break;
        case 'eventLocation':
            $schema = 'itemprop="location" itemscope="itemscope" itemtype="https://schema.org/Place"';
            break;
        case 'eventVenue':
            $schema = 'itemprop="name"';
            break;
        case 'eventAddress':
            $schema = 'itemprop="address" itemscope="itemscope" itemtype="https://schema.org/PostalAddress"';
            break;
        case 'eventOrganizer':
            $schema = 'itemprop="organizer" itemscope="itemscope" itemtype="https://schema.org/Organization"';
            break;
        case 'eventOffer':
            $schema = 'itemprop="offers" itemscope="itemscope" itemtype="https://schema.org/Offer"';
            break;
        case 'eventPerformer':
            $schema = 'itemprop="performer" itemscope="itemscope" itemtype="https://schema.org/Person"';
            break;
        case 'faq':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/FAQPage"';
            break;
        case 'faqQuestion':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Question"';
            break;
        case 'faqAnswer':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Answer"';
            break;
        case 'faqQuestionText':
            $schema = 'itemprop="name"';
            break;
        case 'faqAnswerText':
            $schema = 'itemprop="text"';
            break;
        case 'service':
            $schema = 'itemscope="itemscope" itemtype="https://schema.org/Service"';
            break;
        case 'serviceName':
            $schema = 'itemprop="name"';
            break;
        case 'serviceDescription':
            $schema = 'itemprop="description"';
            break;
        case 'serviceProvider':
            $schema = 'itemprop="provider" itemscope="itemscope" itemtype="https://schema.org/Organization"';
            break;
        case 'serviceArea':
            $schema = 'itemprop="areaServed" itemscope="itemscope" itemtype="https://schema.org/GeoShape"';
            break;
        case 'serviceOffer':
            $schema = 'itemprop="offers" itemscope="itemscope" itemtype="https://schema.org/Offer"';
            break;
    }
    return $schema;
}