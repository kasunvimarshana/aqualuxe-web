/**
 * WordPress Customizer JavaScript
 *
 * This file handles the live preview functionality in the WordPress Customizer.
 */

(function ($) {
    "use strict";

    // Site Title
    wp.customize("blogname", function (value) {
        value.bind(function (to) {
            $(".site-title a").text(to);
        });
    });

    // Site Description
    wp.customize("blogdescription", function (value) {
        value.bind(function (to) {
            $(".site-description").text(to);
        });
    });

    // Header Text Color
    wp.customize("header_textcolor", function (value) {
        value.bind(function (to) {
            if ("blank" === to) {
                $(".site-title, .site-description").css({
                    clip: "rect(1px, 1px, 1px, 1px)",
                    position: "absolute",
                });
            } else {
                $(".site-title, .site-description").css({
                    clip: "auto",
                    position: "relative",
                    color: to,
                });
            }
        });
    });

    // Primary Color
    wp.customize("aqualuxe_primary_color", function (value) {
        value.bind(function (to) {
            updateCSSVariable("--color-primary", to);
        });
    });

    // Secondary Color
    wp.customize("aqualuxe_secondary_color", function (value) {
        value.bind(function (to) {
            updateCSSVariable("--color-secondary", to);
        });
    });

    // Accent Color
    wp.customize("aqualuxe_accent_color", function (value) {
        value.bind(function (to) {
            updateCSSVariable("--color-accent", to);
        });
    });

    // Hero Section Settings
    wp.customize("aqualuxe_hero_title", function (value) {
        value.bind(function (to) {
            $(".hero-title").text(to);
        });
    });

    wp.customize("aqualuxe_hero_subtitle", function (value) {
        value.bind(function (to) {
            $(".hero-subtitle").text(to);
        });
    });

    wp.customize("aqualuxe_hero_button_text", function (value) {
        value.bind(function (to) {
            $(".hero-button").text(to);
        });
    });

    // Footer Settings
    wp.customize("aqualuxe_footer_text", function (value) {
        value.bind(function (to) {
            $(".footer-text").text(to);
        });
    });

    // Utility Functions
    function updateCSSVariable(property, value) {
        document.documentElement.style.setProperty(property, value);
    }

    function updateSocialLink(platform, url) {
        const socialLink = $(`.social-${platform}`);

        if (url) {
            socialLink.attr("href", url).show();
        } else {
            socialLink.hide();
        }
    }

    // Real-time Style Updates
    function initializeRealTimeStyles() {
        // Create style element for dynamic CSS
        const dynamicStyles = $(
            '<style id="aqualuxe-customizer-styles"></style>'
        );
        $("head").append(dynamicStyles);

        // Function to update dynamic styles
        window.updateCustomizerStyles = function () {
            let css = "";

            // Build CSS from customizer values
            const primaryColor = wp.customize("aqualuxe_primary_color")
                ? wp.customize("aqualuxe_primary_color")()
                : null;
            const secondaryColor = wp.customize("aqualuxe_secondary_color")
                ? wp.customize("aqualuxe_secondary_color")()
                : null;
            const accentColor = wp.customize("aqualuxe_accent_color")
                ? wp.customize("aqualuxe_accent_color")()
                : null;

            if (primaryColor) {
                css += `:root { --color-primary: ${primaryColor}; }`;
            }

            if (secondaryColor) {
                css += `:root { --color-secondary: ${secondaryColor}; }`;
            }

            if (accentColor) {
                css += `:root { --color-accent: ${accentColor}; }`;
            }

            // Update the style element
            dynamicStyles.text(css);
        };
    }

    // Initialize when customizer is ready
    wp.customize.bind("ready", function () {
        initializeRealTimeStyles();

        // Update styles when any color setting changes
        if (wp.customize("aqualuxe_primary_color")) {
            wp.customize("aqualuxe_primary_color", function (value) {
                value.bind(window.updateCustomizerStyles);
            });
        }

        if (wp.customize("aqualuxe_secondary_color")) {
            wp.customize("aqualuxe_secondary_color", function (value) {
                value.bind(window.updateCustomizerStyles);
            });
        }

        if (wp.customize("aqualuxe_accent_color")) {
            wp.customize("aqualuxe_accent_color", function (value) {
                value.bind(window.updateCustomizerStyles);
            });
        }

        // Initial style update
        if (window.updateCustomizerStyles) {
            window.updateCustomizerStyles();
        }
    });
})(jQuery);
