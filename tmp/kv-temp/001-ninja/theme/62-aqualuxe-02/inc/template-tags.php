<?php
/**
 * AquaLuxe Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Display site logo
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_site_logo( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'site-logo',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( has_custom_logo() ) {
        $html .= get_custom_logo();
    } else {
        $html .= '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
    }
    
    if ( ! empty( $args['container'] ) ) {
        $html = '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">' . $html . '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display site title
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_site_title( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'site-title',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
    
    if ( ! empty( $args['container'] ) ) {
        $html = '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">' . $html . '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display site description
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_site_description( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'site-description',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = esc_html( get_bloginfo( 'description' ) );
    
    if ( ! empty( $args['container'] ) ) {
        $html = '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">' . $html . '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display primary navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_primary_navigation( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'primary-navigation',
        'menu_class'     => 'primary-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_primary_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Primary navigation fallback
 *
 * @return void
 */
function aqualuxe_primary_navigation_fallback() {
    ?>
    <ul class="primary-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display secondary navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_secondary_navigation( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'secondary-navigation',
        'menu_class'     => 'secondary-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_secondary_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'secondary',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'secondary',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Secondary navigation fallback
 *
 * @return void
 */
function aqualuxe_secondary_navigation_fallback() {
    ?>
    <ul class="secondary-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display footer navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_footer_navigation( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'footer-navigation',
        'menu_class'     => 'footer-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_footer_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'footer',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'footer',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Footer navigation fallback
 *
 * @return void
 */
function aqualuxe_footer_navigation_fallback() {
    ?>
    <ul class="footer-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display social navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_social_navigation( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'social-navigation',
        'menu_class'     => 'social-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_social_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'social',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'social',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Social navigation fallback
 *
 * @return void
 */
function aqualuxe_social_navigation_fallback() {
    ?>
    <ul class="social-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display shop navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_shop_navigation( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'shop-navigation',
        'menu_class'     => 'shop-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_shop_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'shop',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'shop',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Shop navigation fallback
 *
 * @return void
 */
function aqualuxe_shop_navigation_fallback() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    ?>
    <ul class="shop-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display categories navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_categories_navigation( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'categories-navigation',
        'menu_class'     => 'categories-menu',
        'echo'           => true,
        'fallback_cb'    => 'aqualuxe_categories_navigation_fallback',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['echo'] ) {
        wp_nav_menu( array(
            'theme_location' => 'categories',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
        ) );
    } else {
        return wp_nav_menu( array(
            'theme_location' => 'categories',
            'container'      => $args['container'],
            'container_class' => $args['container_class'],
            'menu_class'     => $args['menu_class'],
            'fallback_cb'    => $args['fallback_cb'],
            'echo'           => false,
        ) );
    }
}

/**
 * Categories navigation fallback
 *
 * @return void
 */
function aqualuxe_categories_navigation_fallback() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    ?>
    <ul class="categories-menu">
        <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Add a menu', 'aqualuxe' ); ?></a></li>
    </ul>
    <?php
}

/**
 * Display breadcrumbs
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_breadcrumbs( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'breadcrumbs',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if ( empty( $breadcrumbs ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
    }
    
    $html .= '<ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    foreach ( $breadcrumbs as $key => $breadcrumb ) {
        $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        if ( ! empty( $breadcrumb['url'] ) ) {
            $html .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item"><span itemprop="name">' . esc_html( $breadcrumb['text'] ) . '</span></a>';
        } else {
            $html .= '<span itemprop="name">' . esc_html( $breadcrumb['text'] ) . '</span>';
        }
        
        $html .= '<meta itemprop="position" content="' . esc_attr( $key + 1 ) . '" />';
        $html .= '</li>';
        
        if ( $key < count( $breadcrumbs ) - 1 ) {
            $html .= '<li class="breadcrumbs-separator">/</li>';
        }
    }
    
    $html .= '</ol>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display page title
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_page_title( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'page-title',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $title = aqualuxe_get_page_title();
    
    if ( empty( $title ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h1 class="page-title-heading">' . esc_html( $title ) . '</h1>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display post thumbnail
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_post_thumbnail( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'post-thumbnail',
        'size'           => 'large',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    if ( is_singular() ) {
        $html .= get_the_post_thumbnail( null, $args['size'] );
    } else {
        $html .= '<a href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';
        $html .= get_the_post_thumbnail( null, $args['size'] );
        $html .= '</a>';
    }
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display post meta
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_post_meta( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'post-meta',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $meta = aqualuxe_get_post_meta();
    
    if ( empty( $meta ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="post-meta-list">';
    
    if ( isset( $meta['author'] ) ) {
        $html .= '<li class="post-meta-author">';
        $html .= '<span class="post-meta-label">' . esc_html__( 'By', 'aqualuxe' ) . '</span> ';
        $html .= '<a href="' . esc_url( $meta['author']['url'] ) . '">' . esc_html( $meta['author']['text'] ) . '</a>';
        $html .= '</li>';
    }
    
    if ( isset( $meta['date'] ) ) {
        $html .= '<li class="post-meta-date">';
        $html .= '<span class="post-meta-label">' . esc_html__( 'On', 'aqualuxe' ) . '</span> ';
        $html .= '<a href="' . esc_url( $meta['date']['url'] ) . '">' . esc_html( $meta['date']['text'] ) . '</a>';
        $html .= '</li>';
    }
    
    if ( isset( $meta['category'] ) ) {
        $html .= '<li class="post-meta-category">';
        $html .= '<span class="post-meta-label">' . esc_html__( 'In', 'aqualuxe' ) . '</span> ';
        $html .= '<a href="' . esc_url( $meta['category']['url'] ) . '">' . esc_html( $meta['category']['text'] ) . '</a>';
        $html .= '</li>';
    }
    
    if ( isset( $meta['comments'] ) ) {
        $html .= '<li class="post-meta-comments">';
        $html .= '<a href="' . esc_url( $meta['comments']['url'] ) . '">' . esc_html( $meta['comments']['text'] ) . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display post tags
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_post_tags( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'post-tags',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $tags = aqualuxe_get_post_tags();
    
    if ( empty( $tags ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<span class="post-tags-label">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span> ';
    
    $html .= '<ul class="post-tags-list">';
    
    foreach ( $tags as $tag ) {
        $html .= '<li class="post-tags-item">';
        $html .= '<a href="' . esc_url( $tag['url'] ) . '">' . esc_html( $tag['name'] ) . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display post categories
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_post_categories( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'post-categories',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $categories = aqualuxe_get_post_categories();
    
    if ( empty( $categories ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<span class="post-categories-label">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span> ';
    
    $html .= '<ul class="post-categories-list">';
    
    foreach ( $categories as $category ) {
        $html .= '<li class="post-categories-item">';
        $html .= '<a href="' . esc_url( $category['url'] ) . '">' . esc_html( $category['name'] ) . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display post navigation
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_post_navigation( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'post-navigation',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $navigation = aqualuxe_get_post_navigation();
    
    if ( empty( $navigation ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Post navigation', 'aqualuxe' ) . '">';
    }
    
    $html .= '<div class="post-navigation-links">';
    
    if ( isset( $navigation['prev'] ) ) {
        $html .= '<div class="post-navigation-prev">';
        $html .= '<a href="' . esc_url( $navigation['prev']['url'] ) . '" rel="prev">';
        $html .= '<span class="post-navigation-label">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span>';
        $html .= '<span class="post-navigation-title">' . esc_html( $navigation['prev']['title'] ) . '</span>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    if ( isset( $navigation['next'] ) ) {
        $html .= '<div class="post-navigation-next">';
        $html .= '<a href="' . esc_url( $navigation['next']['url'] ) . '" rel="next">';
        $html .= '<span class="post-navigation-label">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span>';
        $html .= '<span class="post-navigation-title">' . esc_html( $navigation['next']['title'] ) . '</span>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display related posts
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_related_posts( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'related-posts',
        'count'          => 3,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $related_posts = aqualuxe_get_related_posts( null, $args['count'] );
    
    if ( empty( $related_posts ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="related-posts-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="related-posts-list">';
    
    foreach ( $related_posts as $related_post ) {
        $html .= '<article class="related-posts-item">';
        
        if ( has_post_thumbnail( $related_post->ID ) ) {
            $html .= '<div class="related-posts-thumbnail">';
            $html .= '<a href="' . esc_url( get_permalink( $related_post->ID ) ) . '">';
            $html .= get_the_post_thumbnail( $related_post->ID, 'medium' );
            $html .= '</a>';
            $html .= '</div>';
        }
        
        $html .= '<div class="related-posts-content">';
        $html .= '<h3 class="related-posts-title"><a href="' . esc_url( get_permalink( $related_post->ID ) ) . '">' . esc_html( get_the_title( $related_post->ID ) ) . '</a></h3>';
        $html .= '<div class="related-posts-excerpt">' . wp_trim_words( get_the_excerpt( $related_post->ID ), 20 ) . '</div>';
        $html .= '</div>';
        
        $html .= '</article>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display author box
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_author_box( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'author-box',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $author_id = get_the_author_meta( 'ID' );
    
    if ( ! $author_id ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<div class="author-box-avatar">';
    $html .= get_avatar( $author_id, 100 );
    $html .= '</div>';
    
    $html .= '<div class="author-box-content">';
    $html .= '<h3 class="author-box-title">' . esc_html__( 'About', 'aqualuxe' ) . ' ' . esc_html( get_the_author_meta( 'display_name' ) ) . '</h3>';
    $html .= '<div class="author-box-description">' . wp_kses_post( get_the_author_meta( 'description' ) ) . '</div>';
    $html .= '<a class="author-box-link" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>';
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display pagination
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_pagination( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'pagination',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    global $wp_query;
    
    $total = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
    $current = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    
    if ( $total <= 1 ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Posts navigation', 'aqualuxe' ) . '">';
    }
    
    $html .= paginate_links( array(
        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'       => '?paged=%#%',
        'current'      => max( 1, $current ),
        'total'        => $total,
        'prev_text'    => '&larr; ' . esc_html__( 'Previous', 'aqualuxe' ),
        'next_text'    => esc_html__( 'Next', 'aqualuxe' ) . ' &rarr;',
        'type'         => 'list',
        'end_size'     => 3,
        'mid_size'     => 3,
    ) );
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display comments pagination
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_comments_pagination( $args = array() ) {
    $defaults = array(
        'container'      => 'nav',
        'container_class' => 'comments-pagination',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Comments navigation', 'aqualuxe' ) . '">';
    }
    
    $html .= paginate_comments_links( array(
        'prev_text' => '&larr; ' . esc_html__( 'Previous', 'aqualuxe' ),
        'next_text' => esc_html__( 'Next', 'aqualuxe' ) . ' &rarr;',
        'type'      => 'list',
        'echo'      => false,
    ) );
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display social links
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_social_links( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'social-links',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $social_links = aqualuxe_get_social_links();
    
    if ( empty( $social_links ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="social-links-list">';
    
    foreach ( $social_links as $network => $url ) {
        $html .= '<li class="social-links-item social-links-' . esc_attr( $network ) . '">';
        $html .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
        $html .= '<span class="screen-reader-text">' . esc_html( ucfirst( $network ) ) . '</span>';
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display contact info
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_contact_info( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'contact-info',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $contact_info = aqualuxe_get_contact_info();
    
    if ( empty( $contact_info ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="contact-info-list">';
    
    if ( isset( $contact_info['phone'] ) ) {
        $html .= '<li class="contact-info-item contact-info-phone">';
        $html .= '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $contact_info['phone'] ) ) . '">';
        $html .= esc_html( $contact_info['phone'] );
        $html .= '</a>';
        $html .= '</li>';
    }
    
    if ( isset( $contact_info['email'] ) ) {
        $html .= '<li class="contact-info-item contact-info-email">';
        $html .= '<a href="mailto:' . esc_attr( $contact_info['email'] ) . '">';
        $html .= esc_html( $contact_info['email'] );
        $html .= '</a>';
        $html .= '</li>';
    }
    
    if ( isset( $contact_info['address'] ) ) {
        $html .= '<li class="contact-info-item contact-info-address">';
        $html .= esc_html( $contact_info['address'] );
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display copyright text
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_copyright_text( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'copyright-text',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $copyright_text = aqualuxe_get_copyright_text();
    
    if ( empty( $copyright_text ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= wp_kses_post( $copyright_text );
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display dark mode toggle
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_dark_mode_toggle( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'dark-mode-toggle',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<button class="dark-mode-toggle-button" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '">';
    $html .= '<span class="dark-mode-toggle-icon"></span>';
    $html .= '<span class="dark-mode-toggle-text">' . esc_html__( 'Dark Mode', 'aqualuxe' ) . '</span>';
    $html .= '</button>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display search form
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_search_form( $args = array() ) {
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'search-form-container',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= get_search_form( array( 'echo' => false ) );
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display cart icon
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_cart_icon( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'cart-icon',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $cart_count = aqualuxe_get_cart_count();
    $cart_url = aqualuxe_get_cart_url();
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<a href="' . esc_url( $cart_url ) . '" class="cart-icon-link">';
    $html .= '<span class="cart-icon-icon"></span>';
    $html .= '<span class="cart-icon-count">' . esc_html( $cart_count ) . '</span>';
    $html .= '</a>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display account icon
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_account_icon( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'account-icon',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $account_url = aqualuxe_get_account_url();
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<a href="' . esc_url( $account_url ) . '" class="account-icon-link">';
    $html .= '<span class="account-icon-icon"></span>';
    $html .= '<span class="account-icon-text">' . esc_html__( 'My Account', 'aqualuxe' ) . '</span>';
    $html .= '</a>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display mini cart
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_mini_cart( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'mini-cart',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_mini_cart();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product categories
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_categories( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-categories',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $categories = aqualuxe_get_product_categories();
    
    if ( empty( $categories ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<span class="product-categories-label">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span> ';
    
    $html .= '<ul class="product-categories-list">';
    
    foreach ( $categories as $category ) {
        $html .= '<li class="product-categories-item">';
        $html .= '<a href="' . esc_url( $category['url'] ) . '">' . esc_html( $category['name'] ) . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product tags
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_tags( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-tags',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $tags = aqualuxe_get_product_tags();
    
    if ( empty( $tags ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<span class="product-tags-label">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span> ';
    
    $html .= '<ul class="product-tags-list">';
    
    foreach ( $tags as $tag ) {
        $html .= '<li class="product-tags-item">';
        $html .= '<a href="' . esc_url( $tag['url'] ) . '">' . esc_html( $tag['name'] ) . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product attributes
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_attributes( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-attributes',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $attributes = aqualuxe_get_product_attributes();
    
    if ( empty( $attributes ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h3 class="product-attributes-title">' . esc_html__( 'Additional Information', 'aqualuxe' ) . '</h3>';
    
    $html .= '<table class="product-attributes-table">';
    
    foreach ( $attributes as $name => $value ) {
        $html .= '<tr class="product-attributes-row">';
        $html .= '<th class="product-attributes-name">' . esc_html( $name ) . '</th>';
        $html .= '<td class="product-attributes-value">' . wp_kses_post( $value ) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</table>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product gallery
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_gallery( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-gallery',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $gallery_images = aqualuxe_get_product_gallery_images();
    
    if ( empty( $gallery_images ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<div class="product-gallery-main">';
    $html .= '<img src="' . esc_url( $gallery_images[0]['url'] ) . '" alt="' . esc_attr( $gallery_images[0]['alt'] ) . '" class="product-gallery-main-image">';
    $html .= '</div>';
    
    if ( count( $gallery_images ) > 1 ) {
        $html .= '<div class="product-gallery-thumbnails">';
        
        foreach ( $gallery_images as $gallery_image ) {
            $html .= '<div class="product-gallery-thumbnail">';
            $html .= '<img src="' . esc_url( $gallery_image['thumbnail'] ) . '" alt="' . esc_attr( $gallery_image['alt'] ) . '" data-full-url="' . esc_url( $gallery_image['url'] ) . '">';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product price
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_price( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-price',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $price_html = aqualuxe_get_product_price_html();
    
    if ( empty( $price_html ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= $price_html;
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product rating
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_rating( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-rating',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $rating_html = aqualuxe_get_product_rating_html();
    
    if ( empty( $rating_html ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= $rating_html;
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product stock
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_stock( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-stock',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $stock_status = aqualuxe_get_product_stock_status();
    
    if ( empty( $stock_status ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . ' product-stock-' . esc_attr( $stock_status ) . '">';
    }
    
    switch ( $stock_status ) {
        case 'instock':
            $html .= '<span class="product-stock-status">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
            break;
        case 'outofstock':
            $html .= '<span class="product-stock-status">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
            break;
        case 'onbackorder':
            $html .= '<span class="product-stock-status">' . esc_html__( 'On Backorder', 'aqualuxe' ) . '</span>';
            break;
    }
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product add to cart
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_add_to_cart( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-add-to-cart',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_template_single_add_to_cart();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product meta
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_meta( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-meta',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_template_single_meta();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product tabs
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_tabs( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-tabs',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_output_product_data_tabs();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display related products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_related_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'related-products',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_output_related_products();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display upsell products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_upsell_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'upsell-products',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_upsell_display();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display cross sell products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_cross_sell_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'cross-sell-products',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    woocommerce_cross_sell_display();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product reviews
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_reviews( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-reviews',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    ob_start();
    comments_template();
    $html .= ob_get_clean();
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product categories list
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_categories_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-categories-list',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $categories = aqualuxe_get_product_categories_list();
    
    if ( empty( $categories ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="product-categories-items">';
    
    foreach ( $categories as $category ) {
        $html .= '<li class="product-categories-item">';
        $html .= '<a href="' . esc_url( get_term_link( $category ) ) . '">';
        $html .= esc_html( $category->name );
        $html .= '<span class="product-categories-count">(' . esc_html( $category->count ) . ')</span>';
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product tags list
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_tags_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-tags-list',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $tags = aqualuxe_get_product_tags_list();
    
    if ( empty( $tags ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="product-tags-items">';
    
    foreach ( $tags as $tag ) {
        $html .= '<li class="product-tags-item">';
        $html .= '<a href="' . esc_url( get_term_link( $tag ) ) . '">';
        $html .= esc_html( $tag->name );
        $html .= '<span class="product-tags-count">(' . esc_html( $tag->count ) . ')</span>';
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product attributes list
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_attributes_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-attributes-list',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $attributes = aqualuxe_get_product_attributes_list();
    
    if ( empty( $attributes ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="product-attributes-items">';
    
    foreach ( $attributes as $attribute ) {
        $html .= '<li class="product-attributes-item">';
        $html .= '<a href="' . esc_url( admin_url( 'edit.php?post_type=product&page=product_attributes&edit=' . $attribute->attribute_id ) ) . '">';
        $html .= esc_html( $attribute->attribute_label );
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product brands list
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_brands_list( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-brands-list',
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $brands = aqualuxe_get_product_brands();
    
    if ( empty( $brands ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<ul class="product-brands-items">';
    
    foreach ( $brands as $brand ) {
        $html .= '<li class="product-brands-item">';
        $html .= '<a href="' . esc_url( get_term_link( $brand ) ) . '">';
        $html .= esc_html( $brand->name );
        $html .= '<span class="product-brands-count">(' . esc_html( $brand->count ) . ')</span>';
        $html .= '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display featured products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_featured_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'featured-products',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $featured_products = aqualuxe_get_featured_products( $args['count'] );
    
    if ( empty( $featured_products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="featured-products-title">' . esc_html__( 'Featured Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="featured-products-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $featured_products as $featured_product ) {
        $product = wc_get_product( $featured_product );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="featured-products-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $featured_product ) ) . '" class="featured-products-link">';
        
        $html .= '<div class="featured-products-image">';
        $html .= get_the_post_thumbnail( $featured_product, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="featured-products-title">' . esc_html( get_the_title( $featured_product ) ) . '</h3>';
        
        $html .= '<div class="featured-products-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display sale products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_sale_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'sale-products',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $sale_products = aqualuxe_get_sale_products( $args['count'] );
    
    if ( empty( $sale_products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="sale-products-title">' . esc_html__( 'Sale Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="sale-products-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $sale_products as $sale_product ) {
        $product = wc_get_product( $sale_product );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="sale-products-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $sale_product ) ) . '" class="sale-products-link">';
        
        $html .= '<div class="sale-products-image">';
        $html .= get_the_post_thumbnail( $sale_product, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="sale-products-title">' . esc_html( get_the_title( $sale_product ) ) . '</h3>';
        
        $html .= '<div class="sale-products-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display best selling products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_best_selling_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'best-selling-products',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $best_selling_products = aqualuxe_get_best_selling_products( $args['count'] );
    
    if ( empty( $best_selling_products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="best-selling-products-title">' . esc_html__( 'Best Selling Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="best-selling-products-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $best_selling_products as $best_selling_product ) {
        $product = wc_get_product( $best_selling_product );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="best-selling-products-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $best_selling_product ) ) . '" class="best-selling-products-link">';
        
        $html .= '<div class="best-selling-products-image">';
        $html .= get_the_post_thumbnail( $best_selling_product, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="best-selling-products-title">' . esc_html( get_the_title( $best_selling_product ) ) . '</h3>';
        
        $html .= '<div class="best-selling-products-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display top rated products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_top_rated_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'top-rated-products',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $top_rated_products = aqualuxe_get_top_rated_products( $args['count'] );
    
    if ( empty( $top_rated_products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="top-rated-products-title">' . esc_html__( 'Top Rated Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="top-rated-products-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $top_rated_products as $top_rated_product ) {
        $product = wc_get_product( $top_rated_product );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="top-rated-products-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $top_rated_product ) ) . '" class="top-rated-products-link">';
        
        $html .= '<div class="top-rated-products-image">';
        $html .= get_the_post_thumbnail( $top_rated_product, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="top-rated-products-title">' . esc_html( get_the_title( $top_rated_product ) ) . '</h3>';
        
        $html .= '<div class="top-rated-products-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display recent products
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_recent_products( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'recent-products',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $recent_products = aqualuxe_get_recent_products( $args['count'] );
    
    if ( empty( $recent_products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="recent-products-title">' . esc_html__( 'Recent Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="recent-products-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $recent_products as $recent_product ) {
        $product = wc_get_product( $recent_product );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="recent-products-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $recent_product ) ) . '" class="recent-products-link">';
        
        $html .= '<div class="recent-products-image">';
        $html .= get_the_post_thumbnail( $recent_product, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="recent-products-title">' . esc_html( get_the_title( $recent_product ) ) . '</h3>';
        
        $html .= '<div class="recent-products-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by category
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_category( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-category',
        'category_id'    => 0,
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['category_id'] ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_category( $args['category_id'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $category = get_term( $args['category_id'], 'product_cat' );
    
    if ( ! $category ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-category-title">' . esc_html( $category->name ) . '</h2>';
    
    $html .= '<div class="products-by-category-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-category-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-category-link">';
        
        $html .= '<div class="products-by-category-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-category-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-category-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by tag
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_tag( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-tag',
        'tag_id'         => 0,
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['tag_id'] ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_tag( $args['tag_id'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $tag = get_term( $args['tag_id'], 'product_tag' );
    
    if ( ! $tag ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-tag-title">' . esc_html( $tag->name ) . '</h2>';
    
    $html .= '<div class="products-by-tag-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-tag-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-tag-link">';
        
        $html .= '<div class="products-by-tag-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-tag-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-tag-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by attribute
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_attribute( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-attribute',
        'attribute'      => '',
        'value'          => '',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( empty( $args['attribute'] ) || empty( $args['value'] ) ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_attribute( $args['attribute'], $args['value'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-attribute-title">' . esc_html( $args['attribute'] ) . ': ' . esc_html( $args['value'] ) . '</h2>';
    
    $html .= '<div class="products-by-attribute-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-attribute-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-attribute-link">';
        
        $html .= '<div class="products-by-attribute-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-attribute-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-attribute-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by brand
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_brand( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-brand',
        'brand_id'       => 0,
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['brand_id'] ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_brand( $args['brand_id'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $brand = get_term( $args['brand_id'], 'product_brand' );
    
    if ( ! $brand ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-brand-title">' . esc_html( $brand->name ) . '</h2>';
    
    $html .= '<div class="products-by-brand-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-brand-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-brand-link">';
        
        $html .= '<div class="products-by-brand-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-brand-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-brand-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by price
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_price( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-price',
        'min_price'      => 0,
        'max_price'      => 0,
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['min_price'] && ! $args['max_price'] ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_price( $args['min_price'], $args['max_price'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-price-title">' . esc_html__( 'Products by Price', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="products-by-price-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-price-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-price-link">';
        
        $html .= '<div class="products-by-price-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-price-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-price-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by search
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_search( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-search',
        'search'         => '',
        'count'          => 4,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( empty( $args['search'] ) ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_search( $args['search'], $args['count'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-search-title">' . esc_html__( 'Search Results for:', 'aqualuxe' ) . ' ' . esc_html( $args['search'] ) . '</h2>';
    
    $html .= '<div class="products-by-search-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-search-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-search-link">';
        
        $html .= '<div class="products-by-search-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-search-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-search-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display products by IDs
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_products_by_ids( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'products-by-ids',
        'ids'            => array(),
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( empty( $args['ids'] ) ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_ids( $args['ids'] );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="products-by-ids-title">' . esc_html__( 'Products', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="products-by-ids-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="products-by-ids-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="products-by-ids-link">';
        
        $html .= '<div class="products-by-ids-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="products-by-ids-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="products-by-ids-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product cross sells
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_cross_sells( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-cross-sells',
        'product_id'     => 0,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['product_id'] ) {
        $args['product_id'] = get_the_ID();
    }
    
    $cross_sells = aqualuxe_get_product_cross_sells( $args['product_id'] );
    
    if ( empty( $cross_sells ) ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_ids( $cross_sells );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="product-cross-sells-title">' . esc_html__( 'You may also like', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="product-cross-sells-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="product-cross-sells-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="product-cross-sells-link">';
        
        $html .= '<div class="product-cross-sells-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="product-cross-sells-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="product-cross-sells-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Display product up sells
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_product_up_sells( $args = array() ) {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return;
    }
    
    $defaults = array(
        'container'      => 'div',
        'container_class' => 'product-up-sells',
        'product_id'     => 0,
        'columns'        => 4,
        'echo'           => true,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( ! $args['product_id'] ) {
        $args['product_id'] = get_the_ID();
    }
    
    $up_sells = aqualuxe_get_product_up_sells( $args['product_id'] );
    
    if ( empty( $up_sells ) ) {
        return;
    }
    
    $products = aqualuxe_get_products_by_ids( $up_sells );
    
    if ( empty( $products ) ) {
        return;
    }
    
    $html = '';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
    }
    
    $html .= '<h2 class="product-up-sells-title">' . esc_html__( 'You may also like', 'aqualuxe' ) . '</h2>';
    
    $html .= '<div class="product-up-sells-list columns-' . esc_attr( $args['columns'] ) . '">';
    
    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post );
        
        if ( ! $product ) {
            continue;
        }
        
        $html .= '<div class="product-up-sells-item">';
        
        $html .= '<a href="' . esc_url( get_permalink( $product_post ) ) . '" class="product-up-sells-link">';
        
        $html .= '<div class="product-up-sells-image">';
        $html .= get_the_post_thumbnail( $product_post, 'woocommerce_thumbnail' );
        $html .= '</div>';
        
        $html .= '<h3 class="product-up-sells-title">' . esc_html( get_the_title( $product_post ) ) . '</h3>';
        
        $html .= '<div class="product-up-sells-price">';
        $html .= $product->get_price_html();
        $html .= '</div>';
        
        $html .= '</a>';
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    if ( ! empty( $args['container'] ) ) {
        $html .= '</' . tag_escape( $args['container'] ) . '>';
    }
    
    if ( $args['echo'] ) {
        echo $html;
    } else {
        return $html;
    }
}