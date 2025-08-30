<?php
/**
 * Enhanced Social Links Widget for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!class_exists('AquaLuxe_Social_Links_Widget_Enhanced')) {
    /**
     * Enhanced Social Links Widget Class
     */
    class AquaLuxe_Social_Links_Widget_Enhanced extends WP_Widget {
        /**
         * Social networks configuration
         *
         * @var array
         */
        protected $social_networks = array();

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_social_links',
                __('AquaLuxe Social Links', 'aqualuxe'),
                array(
                    'description' => __('Displays social media links with enhanced options.', 'aqualuxe'),
                    'classname'   => 'aqualuxe-social-links',
                )
            );

            // Initialize social networks
            $this->init_social_networks();
        }

        /**
         * Initialize social networks
         */
        private function init_social_networks() {
            $this->social_networks = array(
                'facebook' => array(
                    'name'  => __('Facebook', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                    'color' => '#1877f2',
                ),
                'twitter' => array(
                    'name'  => __('Twitter', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
                    'color' => '#1da1f2',
                ),
                'instagram' => array(
                    'name'  => __('Instagram', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>',
                    'color' => '#e4405f',
                ),
                'linkedin' => array(
                    'name'  => __('LinkedIn', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                    'color' => '#0077b5',
                ),
                'youtube' => array(
                    'name'  => __('YouTube', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                    'color' => '#ff0000',
                ),
                'pinterest' => array(
                    'name'  => __('Pinterest', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>',
                    'color' => '#bd081c',
                ),
                'tiktok' => array(
                    'name'  => __('TikTok', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
                    'color' => '#00f2ea',
                ),
                'snapchat' => array(
                    'name'  => __('Snapchat', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12 1.033-.301.165-.088.344-.104.464-.104.182 0 .359.029.509.09.45.149.734.479.734.838.015.449-.39.839-1.213 1.168-.089.029-.209.075-.344.119-.45.135-1.139.36-1.333.81-.09.224-.061.524.12.868l.015.015c.06.136 1.526 3.475 4.791 4.014.255.044.435.27.42.509 0 .075-.015.149-.045.225-.24.359-1.275.975-3.225 1.125-.046.195-.194.615-.404.885-.224.285-.48.436-.795.436-.239 0-.56-.089-.856-.19-.556-.164-1.266-.314-2.307-.314-.958 0-1.669.15-2.205.315-.333.09-.629.194-.886.194-.314 0-.569-.149-.795-.439-.209-.275-.344-.645-.39-.87-1.455-.074-2.54-.45-3.201-1.124-.074-.091-.12-.194-.135-.3-.014-.24.18-.465.42-.509 3.29-.54 4.74-3.879 4.8-4.014l.016-.029c.18-.345.224-.645.119-.869-.195-.434-.884-.658-1.332-.809-.136-.029-.255-.074-.346-.119-.809-.344-1.214-.734-1.214-1.183 0-.389.299-.719.734-.923.15-.03.329-.059.494-.059.12 0 .271.015.435.09.36.18.72.285 1.094.3.194 0 .315-.045.405-.09-.012-.18-.03-.36-.03-.539-.09-1.604-.215-3.639.3-4.832 1.53-3.51 4.86-3.825 5.875-3.825l.449-.015z"/></svg>',
                    'color' => '#fffc00',
                ),
                'telegram' => array(
                    'name'  => __('Telegram', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>',
                    'color' => '#0088cc',
                ),
                'whatsapp' => array(
                    'name'  => __('WhatsApp', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
                    'color' => '#25d366',
                ),
                'github' => array(
                    'name'  => __('GitHub', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>',
                    'color' => '#333333',
                ),
                'dribbble' => array(
                    'name'  => __('Dribbble', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12 24C5.385 24 0 18.615 0 12S5.385 0 12 0s12 5.385 12 12-5.385 12-12 12zm10.12-10.358c-.35-.11-3.17-.953-6.384-.438 1.34 3.684 1.887 6.684 1.992 7.308 2.3-1.555 3.936-4.02 4.395-6.87zm-6.115 7.808c-.153-.9-.75-4.032-2.19-7.77l-.066.02c-5.79 2.015-7.86 6.025-8.04 6.4 1.73 1.358 3.92 2.166 6.29 2.166 1.42 0 2.77-.29 4-.814zm-11.62-2.58c.232-.4 3.045-5.055 8.332-6.765.135-.045.27-.084.405-.12-.26-.585-.54-1.167-.832-1.74C7.17 11.775 2.206 11.71 1.756 11.7l-.004.312c0 2.633.998 5.037 2.634 6.855zm-2.42-8.955c.46.008 4.683.026 9.477-1.248-1.698-3.018-3.53-5.558-3.8-5.928-2.868 1.35-5.01 3.99-5.676 7.17zM9.6 2.052c.282.38 2.145 2.914 3.822 6 3.645-1.365 5.19-3.44 5.373-3.702-1.81-1.61-4.19-2.586-6.795-2.586-.825 0-1.63.1-2.4.285zm10.335 3.483c-.218.29-1.935 2.493-5.724 4.04.24.49.47.985.68 1.486.08.18.15.36.22.53 3.41-.43 6.8.26 7.14.33-.02-2.42-.88-4.64-2.31-6.38z"/></svg>',
                    'color' => '#ea4c89',
                ),
                'vimeo' => array(
                    'name'  => __('Vimeo', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M23.977 6.416c-.105 2.338-1.739 5.543-4.894 9.609-3.268 4.247-6.026 6.37-8.29 6.37-1.409 0-2.578-1.294-3.553-3.881L5.322 11.4C4.603 8.816 3.834 7.522 3.01 7.522c-.179 0-.806.378-1.881 1.132L0 7.197a315.065 315.065 0 003.501-3.128C5.08 2.701 6.266 1.984 7.055 1.91c1.867-.18 3.016 1.1 3.447 3.838.465 2.953.789 4.789.971 5.507.539 2.45 1.131 3.674 1.776 3.674.502 0 1.256-.796 2.265-2.385 1.004-1.589 1.54-2.797 1.612-3.628.144-1.371-.395-2.061-1.614-2.061-.574 0-1.167.121-1.777.391 1.186-3.868 3.434-5.757 6.762-5.637 2.473.06 3.628 1.664 3.493 4.797l-.013.01z"/></svg>',
                    'color' => '#1ab7ea',
                ),
                'email' => array(
                    'name'  => __('Email', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>',
                    'color' => '#333333',
                ),
            );
        }

        /**
         * Widget Front End
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Follow Us', 'aqualuxe');
            $show_labels = isset($instance['show_labels']) ? (bool) $instance['show_labels'] : false;
            $icon_style = !empty($instance['icon_style']) ? esc_attr($instance['icon_style']) : 'filled';
            $icon_size = !empty($instance['icon_size']) ? esc_attr($instance['icon_size']) : 'medium';
            $alignment = !empty($instance['alignment']) ? esc_attr($instance['alignment']) : 'center';
            $open_new_tab = isset($instance['open_new_tab']) ? (bool) $instance['open_new_tab'] : true;
            $nofollow = isset($instance['nofollow']) ? (bool) $instance['nofollow'] : true;
            $show_tooltips = isset($instance['show_tooltips']) ? (bool) $instance['show_tooltips'] : true;
            $animation = !empty($instance['animation']) ? esc_attr($instance['animation']) : 'none';
            $border_radius = !empty($instance['border_radius']) ? esc_attr($instance['border_radius']) : 'rounded';
            $custom_colors = isset($instance['custom_colors']) ? (bool) $instance['custom_colors'] : false;
            $icon_color = !empty($instance['icon_color']) ? esc_attr($instance['icon_color']) : '#ffffff';
            $background_color = !empty($instance['background_color']) ? esc_attr($instance['background_color']) : '#333333';
            $hover_effect = !empty($instance['hover_effect']) ? esc_attr($instance['hover_effect']) : 'none';

            echo $args['before_widget'];

            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            // Add classes based on settings
            $classes = array(
                'aqualuxe-social-links-list',
                'icon-style-' . $icon_style,
                'icon-size-' . $icon_size,
                'align-' . $alignment,
                'border-' . $border_radius,
                'animation-' . $animation,
                'hover-' . $hover_effect,
            );

            if ($custom_colors) {
                $classes[] = 'custom-colors';
            }

            echo '<ul class="' . esc_attr(implode(' ', $classes)) . '">';
            
            foreach ($this->social_networks as $network => $data) {
                $url = !empty($instance[$network]) ? esc_url($instance[$network]) : '';
                
                if (!empty($url)) {
                    // Prepare attributes
                    $link_attrs = array(
                        'href' => $url,
                        'aria-label' => esc_attr($data['name']),
                        'class' => 'aqualuxe-social-link',
                    );
                    
                    if ($open_new_tab) {
                        $link_attrs['target'] = '_blank';
                    }
                    
                    if ($nofollow) {
                        $link_attrs['rel'] = 'nofollow noopener';
                    } elseif ($open_new_tab) {
                        $link_attrs['rel'] = 'noopener';
                    }
                    
                    if ($show_tooltips) {
                        $link_attrs['title'] = esc_attr($data['name']);
                    }
                    
                    // Prepare inline styles for custom colors
                    $style = '';
                    if ($custom_colors) {
                        if ($icon_style === 'filled') {
                            $style = 'background-color: ' . esc_attr($background_color) . '; color: ' . esc_attr($icon_color) . ';';
                        } else {
                            $style = 'color: ' . esc_attr($background_color) . '; border-color: ' . esc_attr($background_color) . ';';
                        }
                    } else {
                        // Use network color
                        if ($icon_style === 'filled') {
                            $style = 'background-color: ' . esc_attr($data['color']) . '; color: #ffffff;';
                        } else {
                            $style = 'color: ' . esc_attr($data['color']) . '; border-color: ' . esc_attr($data['color']) . ';';
                        }
                    }
                    
                    // Build attributes string
                    $attributes = '';
                    foreach ($link_attrs as $attr => $value) {
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                    
                    echo '<li class="aqualuxe-social-link-item aqualuxe-social-link-' . esc_attr($network) . '">';
                    echo '<a' . $attributes . ' style="' . $style . '">';
                    echo $data['icon'];
                    if ($show_labels) {
                        echo '<span class="aqualuxe-social-link-label">' . esc_html($data['name']) . '</span>';
                    }
                    echo '</a>';
                    echo '</li>';
                }
            }
            
            echo '</ul>';

            echo $args['after_widget'];
        }

        /**
         * Widget Backend
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Follow Us', 'aqualuxe');
            $show_labels = isset($instance['show_labels']) ? (bool) $instance['show_labels'] : false;
            $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'filled';
            $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : 'medium';
            $alignment = !empty($instance['alignment']) ? $instance['alignment'] : 'center';
            $open_new_tab = isset($instance['open_new_tab']) ? (bool) $instance['open_new_tab'] : true;
            $nofollow = isset($instance['nofollow']) ? (bool) $instance['nofollow'] : true;
            $show_tooltips = isset($instance['show_tooltips']) ? (bool) $instance['show_tooltips'] : true;
            $animation = !empty($instance['animation']) ? $instance['animation'] : 'none';
            $border_radius = !empty($instance['border_radius']) ? $instance['border_radius'] : 'rounded';
            $custom_colors = isset($instance['custom_colors']) ? (bool) $instance['custom_colors'] : false;
            $icon_color = !empty($instance['icon_color']) ? $instance['icon_color'] : '#ffffff';
            $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '#333333';
            $hover_effect = !empty($instance['hover_effect']) ? $instance['hover_effect'] : 'none';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            
            <p>
                <label><?php esc_html_e('Social Networks:', 'aqualuxe'); ?></label>
            </p>
            
            <?php foreach ($this->social_networks as $network => $data) : 
                $value = !empty($instance[$network]) ? $instance[$network] : '';
            ?>
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id($network)); ?>"><?php echo esc_html($data['name'] . ' URL:'); ?></label>
                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id($network)); ?>" name="<?php echo esc_attr($this->get_field_name($network)); ?>" type="url" value="<?php echo esc_url($value); ?>">
                </p>
            <?php endforeach; ?>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_labels); ?> id="<?php echo esc_attr($this->get_field_id('show_labels')); ?>" name="<?php echo esc_attr($this->get_field_name('show_labels')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_labels')); ?>"><?php esc_html_e('Display labels?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icon_style')); ?>"><?php esc_html_e('Icon Style:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_style')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_style')); ?>">
                    <option value="filled" <?php selected($icon_style, 'filled'); ?>><?php esc_html_e('Filled', 'aqualuxe'); ?></option>
                    <option value="outline" <?php selected($icon_style, 'outline'); ?>><?php esc_html_e('Outline', 'aqualuxe'); ?></option>
                    <option value="minimal" <?php selected($icon_style, 'minimal'); ?>><?php esc_html_e('Minimal', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icon_size')); ?>"><?php esc_html_e('Icon Size:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_size')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_size')); ?>">
                    <option value="small" <?php selected($icon_size, 'small'); ?>><?php esc_html_e('Small', 'aqualuxe'); ?></option>
                    <option value="medium" <?php selected($icon_size, 'medium'); ?>><?php esc_html_e('Medium', 'aqualuxe'); ?></option>
                    <option value="large" <?php selected($icon_size, 'large'); ?>><?php esc_html_e('Large', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('alignment')); ?>"><?php esc_html_e('Alignment:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('alignment')); ?>" name="<?php echo esc_attr($this->get_field_name('alignment')); ?>">
                    <option value="left" <?php selected($alignment, 'left'); ?>><?php esc_html_e('Left', 'aqualuxe'); ?></option>
                    <option value="center" <?php selected($alignment, 'center'); ?>><?php esc_html_e('Center', 'aqualuxe'); ?></option>
                    <option value="right" <?php selected($alignment, 'right'); ?>><?php esc_html_e('Right', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('border_radius')); ?>"><?php esc_html_e('Border Radius:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('border_radius')); ?>" name="<?php echo esc_attr($this->get_field_name('border_radius')); ?>">
                    <option value="none" <?php selected($border_radius, 'none'); ?>><?php esc_html_e('None', 'aqualuxe'); ?></option>
                    <option value="rounded" <?php selected($border_radius, 'rounded'); ?>><?php esc_html_e('Rounded', 'aqualuxe'); ?></option>
                    <option value="circle" <?php selected($border_radius, 'circle'); ?>><?php esc_html_e('Circle', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('animation')); ?>"><?php esc_html_e('Animation:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('animation')); ?>" name="<?php echo esc_attr($this->get_field_name('animation')); ?>">
                    <option value="none" <?php selected($animation, 'none'); ?>><?php esc_html_e('None', 'aqualuxe'); ?></option>
                    <option value="fade" <?php selected($animation, 'fade'); ?>><?php esc_html_e('Fade In', 'aqualuxe'); ?></option>
                    <option value="slide" <?php selected($animation, 'slide'); ?>><?php esc_html_e('Slide In', 'aqualuxe'); ?></option>
                    <option value="bounce" <?php selected($animation, 'bounce'); ?>><?php esc_html_e('Bounce', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('hover_effect')); ?>"><?php esc_html_e('Hover Effect:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('hover_effect')); ?>" name="<?php echo esc_attr($this->get_field_name('hover_effect')); ?>">
                    <option value="none" <?php selected($hover_effect, 'none'); ?>><?php esc_html_e('None', 'aqualuxe'); ?></option>
                    <option value="grow" <?php selected($hover_effect, 'grow'); ?>><?php esc_html_e('Grow', 'aqualuxe'); ?></option>
                    <option value="shrink" <?php selected($hover_effect, 'shrink'); ?>><?php esc_html_e('Shrink', 'aqualuxe'); ?></option>
                    <option value="pulse" <?php selected($hover_effect, 'pulse'); ?>><?php esc_html_e('Pulse', 'aqualuxe'); ?></option>
                    <option value="rotate" <?php selected($hover_effect, 'rotate'); ?>><?php esc_html_e('Rotate', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($open_new_tab); ?> id="<?php echo esc_attr($this->get_field_id('open_new_tab')); ?>" name="<?php echo esc_attr($this->get_field_name('open_new_tab')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('open_new_tab')); ?>"><?php esc_html_e('Open links in new tab?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($nofollow); ?> id="<?php echo esc_attr($this->get_field_id('nofollow')); ?>" name="<?php echo esc_attr($this->get_field_name('nofollow')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('nofollow')); ?>"><?php esc_html_e('Add nofollow to links?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_tooltips); ?> id="<?php echo esc_attr($this->get_field_id('show_tooltips')); ?>" name="<?php echo esc_attr($this->get_field_name('show_tooltips')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_tooltips')); ?>"><?php esc_html_e('Show tooltips on hover?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($custom_colors); ?> id="<?php echo esc_attr($this->get_field_id('custom_colors')); ?>" name="<?php echo esc_attr($this->get_field_name('custom_colors')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('custom_colors')); ?>"><?php esc_html_e('Use custom colors?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icon_color')); ?>"><?php esc_html_e('Icon Color:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_color')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_color')); ?>" type="color" value="<?php echo esc_attr($icon_color); ?>">
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('background_color')); ?>"><?php esc_html_e('Background Color:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('background_color')); ?>" name="<?php echo esc_attr($this->get_field_name('background_color')); ?>" type="color" value="<?php echo esc_attr($background_color); ?>">
            </p>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['show_labels'] = isset($new_instance['show_labels']) ? (bool) $new_instance['show_labels'] : false;
            $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? sanitize_text_field($new_instance['icon_style']) : 'filled';
            $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? sanitize_text_field($new_instance['icon_size']) : 'medium';
            $instance['alignment'] = (!empty($new_instance['alignment'])) ? sanitize_text_field($new_instance['alignment']) : 'center';
            $instance['open_new_tab'] = isset($new_instance['open_new_tab']) ? (bool) $new_instance['open_new_tab'] : true;
            $instance['nofollow'] = isset($new_instance['nofollow']) ? (bool) $new_instance['nofollow'] : true;
            $instance['show_tooltips'] = isset($new_instance['show_tooltips']) ? (bool) $new_instance['show_tooltips'] : true;
            $instance['animation'] = (!empty($new_instance['animation'])) ? sanitize_text_field($new_instance['animation']) : 'none';
            $instance['border_radius'] = (!empty($new_instance['border_radius'])) ? sanitize_text_field($new_instance['border_radius']) : 'rounded';
            $instance['custom_colors'] = isset($new_instance['custom_colors']) ? (bool) $new_instance['custom_colors'] : false;
            $instance['icon_color'] = (!empty($new_instance['icon_color'])) ? sanitize_hex_color($new_instance['icon_color']) : '#ffffff';
            $instance['background_color'] = (!empty($new_instance['background_color'])) ? sanitize_hex_color($new_instance['background_color']) : '#333333';
            $instance['hover_effect'] = (!empty($new_instance['hover_effect'])) ? sanitize_text_field($new_instance['hover_effect']) : 'none';

            // Save social network URLs
            foreach ($this->social_networks as $network => $data) {
                $instance[$network] = (!empty($new_instance[$network])) ? esc_url_raw($new_instance[$network]) : '';
            }

            return $instance;
        }
    }
}

/**
 * Register the Enhanced Social Links Widget
 */
function aqualuxe_register_social_links_widget_enhanced() {
    register_widget('AquaLuxe_Social_Links_Widget_Enhanced');
}
add_action('widgets_init', 'aqualuxe_register_social_links_widget_enhanced');

/**
 * Add widget styles
 */
function aqualuxe_social_links_widget_styles() {
    wp_enqueue_style(
        'aqualuxe-social-links-widget-style',
        AQUALUXE_URI . '/assets/css/widgets/social-links.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_social_links_widget_styles');