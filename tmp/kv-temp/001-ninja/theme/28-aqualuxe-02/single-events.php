<?php
/**
 * The template for displaying single event
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Get event meta
            $event_date = get_post_meta( get_the_ID(), 'event_date', true );
            $event_time = get_post_meta( get_the_ID(), 'event_time', true );
            $event_end_time = get_post_meta( get_the_ID(), 'event_end_time', true );
            $event_location = get_post_meta( get_the_ID(), 'event_location', true );
            $event_address = get_post_meta( get_the_ID(), 'event_address', true );
            $event_is_virtual = get_post_meta( get_the_ID(), 'event_is_virtual', true );
            $event_virtual_link = get_post_meta( get_the_ID(), 'event_virtual_link', true );
            $event_cost = get_post_meta( get_the_ID(), 'event_cost', true );
            $event_registration_link = get_post_meta( get_the_ID(), 'event_registration_link', true );
            $event_speakers = get_post_meta( get_the_ID(), 'event_speakers', true );
            $event_sponsors = get_post_meta( get_the_ID(), 'event_sponsors', true );
            $event_capacity = get_post_meta( get_the_ID(), 'event_capacity', true );
            $event_schedule = get_post_meta( get_the_ID(), 'event_schedule', true );
            
            // Format date if available
            $formatted_date = '';
            if ( $event_date ) {
                $date_object = DateTime::createFromFormat( 'Y-m-d', $event_date );
                if ( $date_object ) {
                    $formatted_date = $date_object->format( get_option( 'date_format' ) );
                } else {
                    $formatted_date = $event_date;
                }
            }
            
            // Check if event is past
            $is_past_event = false;
            if ( $event_date ) {
                $today = new DateTime( 'today' );
                $event_date_obj = DateTime::createFromFormat( 'Y-m-d', $event_date );
                if ( $event_date_obj && $event_date_obj < $today ) {
                    $is_past_event = true;
                }
            }
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-12 text-center">
                    <?php the_title( '<h1 class="entry-title text-4xl md:text-5xl lg:text-6xl mb-4">', '</h1>' ); ?>
                    
                    <?php if ( $is_past_event ) : ?>
                        <div class="event-status inline-block bg-dark-800 text-white px-4 py-2 rounded-full text-lg font-medium mb-6">
                            <?php esc_html_e( 'Past Event', 'aqualuxe' ); ?>
                        </div>
                    <?php else : ?>
                        <div class="event-status inline-block bg-primary-600 text-white px-4 py-2 rounded-full text-lg font-medium mb-6">
                            <?php esc_html_e( 'Upcoming Event', 'aqualuxe' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-meta flex flex-wrap justify-center gap-6 text-lg mb-6">
                        <?php if ( $formatted_date || $event_time ) : ?>
                            <div class="event-datetime flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    <?php 
                                    if ( $formatted_date ) {
                                        echo esc_html( $formatted_date );
                                    }
                                    if ( $event_time ) {
                                        echo $formatted_date ? ' ' . esc_html__( 'at', 'aqualuxe' ) . ' ' . esc_html( $event_time ) : esc_html( $event_time );
                                        
                                        if ( $event_end_time ) {
                                            echo ' - ' . esc_html( $event_end_time );
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $event_location || $event_is_virtual ) : ?>
                            <div class="event-location flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    <?php 
                                    if ( $event_is_virtual ) {
                                        esc_html_e( 'Virtual Event', 'aqualuxe' );
                                    } elseif ( $event_location ) {
                                        echo esc_html( $event_location );
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $event_cost ) : ?>
                            <div class="event-cost flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95a1 1 0 001.715 1.029zM6 12a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm7 0a1 1 0 011-1h.01a1 1 0 110 2H14a1 1 0 01-1-1zm-.867-1a1 1 0 00-1 1v.932l-.366.138A1 1 0 009.4 14.932L10 15.5l.6-.568a1 1 0 00-.366-1.862l-.366-.138V12a1 1 0 00-1-1h-.134z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo esc_html( $event_cost ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Display event categories
                    $event_categories = get_the_terms( get_the_ID(), 'event_category' );
                    if ( $event_categories && ! is_wp_error( $event_categories ) ) : ?>
                        <div class="event-categories flex flex-wrap justify-center gap-2 mb-6">
                            <?php foreach ( $event_categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( !$is_past_event && $event_registration_link ) : ?>
                        <div class="event-registration mt-6">
                            <a href="<?php echo esc_url( $event_registration_link ); ?>" class="btn-primary text-lg px-8 py-3" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e( 'Register Now', 'aqualuxe' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="entry-content">
                    <div class="event-layout grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <div class="event-main lg:col-span-2">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="event-featured-image mb-8 rounded-lg overflow-hidden shadow-medium">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-description prose max-w-none dark:prose-invert">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ( $event_schedule && is_array( $event_schedule ) && !empty( $event_schedule ) ) : ?>
                                <div class="event-schedule mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Event Schedule', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="schedule-timeline space-y-6">
                                        <?php foreach ( $event_schedule as $index => $item ) : 
                                            if ( empty( $item['time'] ) && empty( $item['title'] ) ) continue;
                                            ?>
                                            <div class="schedule-item flex">
                                                <div class="schedule-time w-32 flex-shrink-0 font-medium text-primary-600 dark:text-primary-400">
                                                    <?php echo esc_html( $item['time'] ?? '' ); ?>
                                                </div>
                                                <div class="schedule-content pl-6 border-l-2 border-primary-200 dark:border-primary-800">
                                                    <h3 class="text-xl font-bold mb-2"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
                                                    <?php if ( !empty( $item['description'] ) ) : ?>
                                                        <div class="text-dark-600 dark:text-dark-300">
                                                            <?php echo wp_kses_post( $item['description'] ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ( !empty( $item['speaker'] ) ) : ?>
                                                        <div class="schedule-speaker mt-2 text-sm font-medium">
                                                            <?php echo esc_html__( 'Speaker: ', 'aqualuxe' ) . esc_html( $item['speaker'] ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $event_speakers && is_array( $event_speakers ) && !empty( $event_speakers ) ) : ?>
                                <div class="event-speakers mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Speakers', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="speakers-grid grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <?php foreach ( $event_speakers as $speaker ) : 
                                            if ( empty( $speaker['name'] ) ) continue;
                                            ?>
                                            <div class="speaker-card card p-6">
                                                <div class="flex items-center">
                                                    <?php if ( !empty( $speaker['image'] ) ) : ?>
                                                        <div class="speaker-image w-20 h-20 rounded-full overflow-hidden mr-4">
                                                            <img src="<?php echo esc_url( $speaker['image'] ); ?>" alt="<?php echo esc_attr( $speaker['name'] ); ?>" class="w-full h-full object-cover">
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="speaker-info">
                                                        <h3 class="text-xl font-bold"><?php echo esc_html( $speaker['name'] ); ?></h3>
                                                        <?php if ( !empty( $speaker['title'] ) ) : ?>
                                                            <div class="speaker-title text-primary-600 dark:text-primary-400">
                                                                <?php echo esc_html( $speaker['title'] ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if ( !empty( $speaker['bio'] ) ) : ?>
                                                    <div class="speaker-bio mt-4 text-sm">
                                                        <?php echo wp_kses_post( $speaker['bio'] ); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $event_sponsors && is_array( $event_sponsors ) && !empty( $event_sponsors ) ) : ?>
                                <div class="event-sponsors mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Sponsors', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="sponsors-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                        <?php foreach ( $event_sponsors as $sponsor ) : 
                                            if ( empty( $sponsor['name'] ) ) continue;
                                            ?>
                                            <div class="sponsor-card card p-4 flex flex-col items-center justify-center text-center">
                                                <?php if ( !empty( $sponsor['logo'] ) ) : ?>
                                                    <div class="sponsor-logo mb-3 h-16 flex items-center justify-center">
                                                        <img src="<?php echo esc_url( $sponsor['logo'] ); ?>" alt="<?php echo esc_attr( $sponsor['name'] ); ?>" class="max-h-full max-w-full">
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <h3 class="text-lg font-bold"><?php echo esc_html( $sponsor['name'] ); ?></h3>
                                                
                                                <?php if ( !empty( $sponsor['level'] ) ) : ?>
                                                    <div class="sponsor-level text-sm text-primary-600 dark:text-primary-400">
                                                        <?php echo esc_html( $sponsor['level'] ); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                            ?>
                        </div>
                        
                        <div class="event-sidebar">
                            <div class="event-details card p-6 sticky top-32">
                                <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Event Details', 'aqualuxe' ); ?></h3>
                                
                                <ul class="event-details-list space-y-4">
                                    <?php if ( $formatted_date ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $formatted_date ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $event_time ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></div>
                                                <div>
                                                    <?php 
                                                    echo esc_html( $event_time );
                                                    if ( $event_end_time ) {
                                                        echo ' - ' . esc_html( $event_end_time );
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $event_location || $event_is_virtual ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></div>
                                                <div>
                                                    <?php 
                                                    if ( $event_is_virtual ) {
                                                        esc_html_e( 'Virtual Event', 'aqualuxe' );
                                                        
                                                        if ( $event_virtual_link && !$is_past_event ) {
                                                            echo '<div class="mt-1"><a href="' . esc_url( $event_virtual_link ) . '" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Join Online', 'aqualuxe' ) . '</a></div>';
                                                        }
                                                    } elseif ( $event_location ) {
                                                        echo esc_html( $event_location );
                                                        
                                                        if ( $event_address ) {
                                                            echo '<div class="mt-1">' . esc_html( $event_address ) . '</div>';
                                                            
                                                            // Add Google Maps link
                                                            $map_url = 'https://www.google.com/maps/search/' . urlencode( $event_address );
                                                            echo '<div class="mt-1"><a href="' . esc_url( $map_url ) . '" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View Map', 'aqualuxe' ) . '</a></div>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $event_cost ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95a1 1 0 001.715 1.029zM6 12a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm7 0a1 1 0 011-1h.01a1 1 0 110 2H14a1 1 0 01-1-1zm-.867-1a1 1 0 00-1 1v.932l-.366.138A1 1 0 009.4 14.932L10 15.5l.6-.568a1 1 0 00-.366-1.862l-.366-.138V12a1 1 0 00-1-1h-.134z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $event_cost ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $event_capacity ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $event_capacity ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                
                                <?php if ( !$is_past_event && $event_registration_link ) : ?>
                                    <div class="event-registration-sidebar mt-6">
                                        <a href="<?php echo esc_url( $event_registration_link ); ?>" class="btn-primary w-full text-center" target="_blank" rel="noopener noreferrer">
                                            <?php esc_html_e( 'Register Now', 'aqualuxe' ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="event-share mt-6">
                                    <h4 class="text-lg font-medium mb-3"><?php esc_html_e( 'Share This Event', 'aqualuxe' ); ?></h4>
                                    
                                    <div class="flex space-x-4">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            // Related events based on categories
                            $related_args = array(
                                'post_type' => 'events',
                                'posts_per_page' => 3,
                                'post__not_in' => array( get_the_ID() ),
                                'orderby' => 'meta_value',
                                'meta_key' => 'event_date',
                                'order' => 'ASC',
                                'meta_query' => array(
                                    array(
                                        'key' => 'event_date',
                                        'value' => date('Y-m-d'),
                                        'compare' => '>=',
                                        'type' => 'DATE'
                                    )
                                )
                            );
                            
                            if ( $event_categories ) {
                                $category_ids = array();
                                foreach ( $event_categories as $category ) {
                                    $category_ids[] = $category->term_id;
                                }
                                $related_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'event_category',
                                        'field' => 'term_id',
                                        'terms' => $category_ids,
                                    ),
                                );
                            }
                            
                            $related_events = new WP_Query( $related_args );
                            
                            if ( $related_events->have_posts() ) : ?>
                                <div class="related-events mt-8">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Upcoming Events', 'aqualuxe' ); ?></h3>
                                    
                                    <div class="related-events-list space-y-4">
                                        <?php while ( $related_events->have_posts() ) : $related_events->the_post(); 
                                            $rel_event_date = get_post_meta( get_the_ID(), 'event_date', true );
                                            $rel_formatted_date = '';
                                            
                                            if ( $rel_event_date ) {
                                                $date_object = DateTime::createFromFormat( 'Y-m-d', $rel_event_date );
                                                if ( $date_object ) {
                                                    $rel_formatted_date = $date_object->format( get_option( 'date_format' ) );
                                                } else {
                                                    $rel_formatted_date = $rel_event_date;
                                                }
                                            }
                                            ?>
                                            <a href="<?php the_permalink(); ?>" class="related-event-item block card p-4 hover:shadow-medium transition-shadow">
                                                <div class="flex items-center">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <div class="related-event-image w-16 h-16 mr-4">
                                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded' ) ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="related-event-content">
                                                        <h4 class="text-base font-medium"><?php the_title(); ?></h4>
                                                        <?php if ( $rel_formatted_date ) : ?>
                                                            <div class="text-sm text-primary-600 dark:text-primary-400">
                                                                <?php echo esc_html( $rel_formatted_date ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();