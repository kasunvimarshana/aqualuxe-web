/**
 * Team Members Module
 * 
 * Handles team members functionality including carousel and animations
 */

const TeamMembers = {
    /**
     * Initialize the team members module
     */
    init() {
        this.initCarousel();
        this.initAnimations();
        this.initOverlayEffects();
    },

    /**
     * Initialize carousel for team members
     */
    initCarousel() {
        const carousels = document.querySelectorAll('.team-members--carousel .team-members__carousel');
        
        if (!carousels.length || typeof Swiper === 'undefined') {
            return;
        }

        carousels.forEach(carousel => {
            // Get the parent container to determine columns
            const teamContainer = carousel.closest('.team-members');
            const columnsClass = teamContainer ? teamContainer.className.match(/team-members--cols-(\d+)/) : null;
            const columns = columnsClass ? parseInt(columnsClass[1]) : 4;
            
            new Swiper(carousel, {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: '.team-members__pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.team-members__button-next',
                    prevEl: '.team-members__button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: Math.min(2, columns),
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: Math.min(2, columns),
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: Math.min(3, columns),
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: columns,
                        spaceBetween: 30,
                    },
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
            });
        });
    },

    /**
     * Initialize animations for team members
     */
    initAnimations() {
        const teamSections = document.querySelectorAll('.team-members');
        
        if (!teamSections.length) {
            return;
        }

        // Setup Intersection Observer to trigger animations when section is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        teamSections.forEach(section => {
            if (!section.classList.contains('team-members--animate-none')) {
                observer.observe(section);
            }
        });
    },

    /**
     * Initialize overlay effects for team members
     */
    initOverlayEffects() {
        const overlayMembers = document.querySelectorAll('.team-members__member--overlay');
        
        if (!overlayMembers.length) {
            return;
        }

        overlayMembers.forEach(member => {
            // Add hover effect for touch devices
            member.addEventListener('touchstart', function() {
                this.classList.add('is-touched');
            }, { passive: true });
            
            // Remove hover effect when touching elsewhere
            document.addEventListener('touchstart', function(e) {
                if (!member.contains(e.target)) {
                    member.classList.remove('is-touched');
                }
            }, { passive: true });
        });
    },

    /**
     * Update carousel when window resizes
     */
    updateCarousel() {
        const carousels = document.querySelectorAll('.team-members--carousel .team-members__carousel');
        
        if (!carousels.length || typeof Swiper === 'undefined') {
            return;
        }

        carousels.forEach(carousel => {
            if (carousel.swiper) {
                carousel.swiper.update();
            }
        });
    }
};

// Initialize the team members module when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    TeamMembers.init();
    
    // Update carousel on window resize
    window.addEventListener('resize', () => {
        TeamMembers.updateCarousel();
    });
});

export default TeamMembers;