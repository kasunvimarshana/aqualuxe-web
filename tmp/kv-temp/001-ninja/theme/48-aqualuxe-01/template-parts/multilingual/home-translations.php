<?php
/**
 * Template part for homepage multilingual content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Demo multilingual content for the homepage
 * In a real implementation, this would be handled by a translation plugin
 * like WPML or Polylang, but for demo purposes we're using a simple array
 */
$translations = array(
    'en' => array(
        'hero_title' => 'Premium Aquarium Solutions',
        'hero_subtitle' => 'Transforming Spaces with Stunning Aquatic Environments',
        'hero_button' => 'Explore Our Products',
        'about_title' => 'About AquaLuxe',
        'about_text' => 'AquaLuxe is a premium aquarium company specializing in high-quality products, custom installations, and professional maintenance services for both hobbyists and commercial clients.',
        'about_button' => 'Learn More',
        'products_title' => 'Featured Products',
        'products_subtitle' => 'Discover our premium selection of aquarium products',
        'products_button' => 'View All Products',
        'services_title' => 'Our Services',
        'services_subtitle' => 'Professional aquarium services for every need',
        'services_button' => 'Explore Services',
        'testimonials_title' => 'What Our Clients Say',
        'testimonials_subtitle' => 'Hear from our satisfied customers',
        'cta_title' => 'Ready to Transform Your Space?',
        'cta_text' => 'Contact us today to discuss your aquarium needs',
        'cta_button' => 'Get in Touch',
    ),
    'es' => array(
        'hero_title' => 'Soluciones Premium para Acuarios',
        'hero_subtitle' => 'Transformando Espacios con Impresionantes Ambientes Acuáticos',
        'hero_button' => 'Explorar Nuestros Productos',
        'about_title' => 'Sobre AquaLuxe',
        'about_text' => 'AquaLuxe es una empresa premium de acuarios especializada en productos de alta calidad, instalaciones personalizadas y servicios de mantenimiento profesional tanto para aficionados como para clientes comerciales.',
        'about_button' => 'Más Información',
        'products_title' => 'Productos Destacados',
        'products_subtitle' => 'Descubra nuestra selección premium de productos para acuarios',
        'products_button' => 'Ver Todos los Productos',
        'services_title' => 'Nuestros Servicios',
        'services_subtitle' => 'Servicios profesionales de acuarios para cada necesidad',
        'services_button' => 'Explorar Servicios',
        'testimonials_title' => 'Lo Que Dicen Nuestros Clientes',
        'testimonials_subtitle' => 'Escuche a nuestros clientes satisfechos',
        'cta_title' => '¿Listo para Transformar su Espacio?',
        'cta_text' => 'Contáctenos hoy para discutir sus necesidades de acuario',
        'cta_button' => 'Póngase en Contacto',
    ),
    'fr' => array(
        'hero_title' => 'Solutions d\'Aquarium Premium',
        'hero_subtitle' => 'Transformer les Espaces avec des Environnements Aquatiques Époustouflants',
        'hero_button' => 'Découvrir Nos Produits',
        'about_title' => 'À Propos d\'AquaLuxe',
        'about_text' => 'AquaLuxe est une entreprise d\'aquariums haut de gamme spécialisée dans les produits de qualité, les installations personnalisées et les services de maintenance professionnels pour les amateurs et les clients commerciaux.',
        'about_button' => 'En Savoir Plus',
        'products_title' => 'Produits Vedettes',
        'products_subtitle' => 'Découvrez notre sélection premium de produits pour aquariums',
        'products_button' => 'Voir Tous les Produits',
        'services_title' => 'Nos Services',
        'services_subtitle' => 'Services d\'aquarium professionnels pour tous les besoins',
        'services_button' => 'Explorer les Services',
        'testimonials_title' => 'Ce Que Disent Nos Clients',
        'testimonials_subtitle' => 'Écoutez nos clients satisfaits',
        'cta_title' => 'Prêt à Transformer Votre Espace?',
        'cta_text' => 'Contactez-nous aujourd\'hui pour discuter de vos besoins en aquarium',
        'cta_button' => 'Nous Contacter',
    ),
    'de' => array(
        'hero_title' => 'Premium-Aquarienlösungen',
        'hero_subtitle' => 'Räume mit atemberaubenden Wasserumgebungen verwandeln',
        'hero_button' => 'Entdecken Sie Unsere Produkte',
        'about_title' => 'Über AquaLuxe',
        'about_text' => 'AquaLuxe ist ein Premium-Aquarienunternehmen, das sich auf hochwertige Produkte, maßgeschneiderte Installationen und professionelle Wartungsdienste für Hobbyisten und gewerbliche Kunden spezialisiert hat.',
        'about_button' => 'Mehr Erfahren',
        'products_title' => 'Ausgewählte Produkte',
        'products_subtitle' => 'Entdecken Sie unsere Premium-Auswahl an Aquarienprodukten',
        'products_button' => 'Alle Produkte Anzeigen',
        'services_title' => 'Unsere Dienstleistungen',
        'services_subtitle' => 'Professionelle Aquariendienstleistungen für jeden Bedarf',
        'services_button' => 'Dienstleistungen Erkunden',
        'testimonials_title' => 'Was Unsere Kunden Sagen',
        'testimonials_subtitle' => 'Hören Sie von unseren zufriedenen Kunden',
        'cta_title' => 'Bereit, Ihren Raum zu Verwandeln?',
        'cta_text' => 'Kontaktieren Sie uns noch heute, um Ihre Aquarienbedürfnisse zu besprechen',
        'cta_button' => 'Kontakt Aufnehmen',
    ),
    'zh' => array(
        'hero_title' => '高级水族箱解决方案',
        'hero_subtitle' => '用令人惊叹的水生环境改变空间',
        'hero_button' => '探索我们的产品',
        'about_title' => '关于 AquaLuxe',
        'about_text' => 'AquaLuxe 是一家高级水族箱公司，专门为爱好者和商业客户提供高质量的产品、定制安装和专业维护服务。',
        'about_button' => '了解更多',
        'products_title' => '精选产品',
        'products_subtitle' => '探索我们精选的高级水族箱产品',
        'products_button' => '查看所有产品',
        'services_title' => '我们的服务',
        'services_subtitle' => '满足各种需求的专业水族箱服务',
        'services_button' => '探索服务',
        'testimonials_title' => '客户评价',
        'testimonials_subtitle' => '听听我们满意客户的声音',
        'cta_title' => '准备好改变您的空间了吗？',
        'cta_text' => '立即联系我们，讨论您的水族箱需求',
        'cta_button' => '联系我们',
    ),
);

/**
 * Function to get translation
 * 
 * @param string $key The translation key
 * @param string $language The language code
 * @return string The translated text
 */
function aqualuxe_get_translation($key, $language = 'en') {
    global $translations;
    
    if (isset($translations[$language][$key])) {
        return $translations[$language][$key];
    }
    
    // Fallback to English
    if (isset($translations['en'][$key])) {
        return $translations['en'][$key];
    }
    
    return $key;
}