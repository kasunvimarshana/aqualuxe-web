<?php
/**
 * About Page Stats Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_about_stats_title', __('AquaLuxe by the Numbers', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_about_stats_subtitle', __('Our global impact and growth', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_about_stats_show', true);
$background_image = get_theme_mod('aqualuxe_about_stats_bg', get_template_directory_uri() . '/assets/dist/images/stats-bg.jpg');
$overlay_opacity = get_theme_mod('aqualuxe_about_stats_overlay', 0.8);
$columns = get_theme_mod('aqualuxe_about_stats_columns', 4);

// Get stats from customizer
$stats = get_theme_mod('aqualuxe_about_stats', [
    [
        'number' => '50+',
        'label' => __('Stores Worldwide', 'aqualuxe'),
        'icon' => 'store',
    ],
    [
        'number' => '15',
        'label' => __('Years of Excellence', 'aqualuxe'),
        'icon' => 'calendar',
    ],
    [
        'number' => '100K+',
        'label' => __('Happy Customers', 'aqualuxe'),
        'icon' => 'users',
    ],
    [
        'number' => '5M+',
        'label' => __('Products Sold', 'aqualuxe'),
        'icon' => 'shopping',
    ],
    [
        'number' => '25+',
        'label' => __('Countries Served', 'aqualuxe'),
        'icon' => 'globe',
    ],
    [
        'number' => '500+',
        'label' => __('Team Members', 'aqualuxe'),
        'icon' => 'team',
    ],
    [
        'number' => '$2M+',
        'label' => __('Donated to Conservation', 'aqualuxe'),
        'icon' => 'donation',
    ],
    [
        'number' => '98%',
        'label' => __('Customer Satisfaction', 'aqualuxe'),
        'icon' => 'satisfaction',
    ],
]);

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set column class
$column_class = '';
switch ($columns) {
    case 1:
        $column_class = 'grid-cols-1';
        break;
    case 2:
        $column_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $column_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case 4:
    default:
        $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
        break;
}

// Set background style
$bg_style = '';
if ($background_image) {
    $bg_style = 'background-image: url(' . esc_url($background_image) . ');';
}

// Set overlay style
$overlay_style = '';
if ($overlay_opacity) {
    $overlay_style = 'background-color: rgba(0, 0, 0, ' . esc_attr($overlay_opacity) . ');';
}

/**
 * Helper function to display stat icons
 */
function aqualuxe_stat_icon($icon, $class = 'h-12 w-12') {
    switch ($icon) {
        case 'store':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>';
        case 'calendar':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>';
        case 'users':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>';
        case 'shopping':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>';
        case 'globe':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
        case 'team':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>';
        case 'donation':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
        case 'satisfaction':
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
        default:
            return '<svg xmlns="http://www.w3.org/2000/svg" class="' . esc_attr($class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>';
    }
}
?>

<section class="aqualuxe-stats py-16 bg-cover bg-center relative" style="<?php echo esc_attr($bg_style); ?>">
    <div class="absolute inset-0" style="<?php echo esc_attr($overlay_style); ?>"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-12 text-white">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg text-gray-200"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($stats)) : ?>
            <div class="grid <?php echo esc_attr($column_class); ?> gap-8">
                <?php foreach ($stats as $stat) : ?>
                    <div class="stat-card bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center text-white transition-transform duration-300 hover:-translate-y-1">
                        <?php if (!empty($stat['icon'])) : ?>
                            <div class="text-primary-400 mb-4 flex justify-center">
                                <?php echo aqualuxe_stat_icon($stat['icon']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($stat['number'])) : ?>
                            <div class="text-4xl font-bold mb-2 stat-number"><?php echo esc_html($stat['number']); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($stat['label'])) : ?>
                            <div class="text-lg text-gray-200"><?php echo esc_html($stat['label']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple animation for stats numbers
        const statElements = document.querySelectorAll('.stat-number');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const value = el.textContent;
                    
                    // Check if it's a number that can be animated
                    if (/^\d+$/.test(value.replace(/,/g, ''))) {
                        const num = parseInt(value.replace(/,/g, ''), 10);
                        animateValue(el, 0, num, 1500);
                    }
                    
                    // Unobserve after animation
                    observer.unobserve(el);
                }
            });
        }, {
            threshold: 0.1
        });
        
        statElements.forEach(stat => {
            observer.observe(stat);
        });
        
        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start).toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    });
</script>