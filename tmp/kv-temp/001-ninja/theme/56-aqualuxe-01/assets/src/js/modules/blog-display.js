/**
 * Blog Display Module
 * 
 * Handles pagination, filtering, and animations for the blog display.
 */

(function($) {
    'use strict';

    const BlogDisplay = {
        /**
         * Initialize the blog display functionality
         */
        init: function() {
            // Initialize variables
            this.blogDisplay = $('.aqualuxe-blog-display');
            
            // Exit if no blog display found
            if (!this.blogDisplay.length) {
                return;
            }
            
            // Initialize components
            this.initPagination();
            this.initLoadMore();
            this.initMasonry();
            this.initAnimations();
            
            // Trigger window resize to fix layout issues
            $(window).trigger('resize');
        },
        
        /**
         * Initialize pagination
         */
        initPagination: function() {
            const self = this;
            const paginationContainer = $('.pagination-numbers');
            
            if (!paginationContainer.length) {
                return;
            }
            
            const blogPosts = $('.blog-post');
            const postsPerPage = 6; // This should match the posts_per_page setting
            const totalPosts = blogPosts.length;
            const totalPages = Math.ceil(totalPosts / postsPerPage);
            
            // Don't show pagination if only one page
            if (totalPages <= 1) {
                paginationContainer.hide();
                return;
            }
            
            // Create pagination numbers
            const numbersContainer = $('.pagination-numbers-container');
            for (let i = 1; i <= totalPages; i++) {
                const pageLink = $('<a>', {
                    href: '#',
                    class: i === 1 ? 'px-4 py-2 bg-primary text-white rounded-md' : 'px-4 py-2 text-gray-500 bg-gray-200 rounded-md dark:bg-gray-700 dark:text-gray-300',
                    text: i,
                    'data-page': i
                });
                
                numbersContainer.append(pageLink);
            }
            
            // Hide all posts except first page
            blogPosts.hide();
            blogPosts.slice(0, postsPerPage).show();
            
            // Previous button
            const prevBtn = $('.pagination-prev');
            prevBtn.addClass('disabled opacity-50 cursor-not-allowed');
            
            // Next button
            const nextBtn = $('.pagination-next');
            if (totalPages === 1) {
                nextBtn.addClass('disabled opacity-50 cursor-not-allowed');
            }
            
            // Page click handler
            numbersContainer.on('click', 'a', function(e) {
                e.preventDefault();
                
                const $this = $(this);
                const page = parseInt($this.data('page'));
                
                // Update active class
                numbersContainer.find('a').removeClass('bg-primary text-white').addClass('text-gray-500 bg-gray-200 dark:bg-gray-700 dark:text-gray-300');
                $this.removeClass('text-gray-500 bg-gray-200 dark:bg-gray-700 dark:text-gray-300').addClass('bg-primary text-white');
                
                // Show/hide posts
                blogPosts.hide();
                blogPosts.slice((page - 1) * postsPerPage, page * postsPerPage).show();
                
                // Update prev/next buttons
                if (page === 1) {
                    prevBtn.addClass('disabled opacity-50 cursor-not-allowed');
                } else {
                    prevBtn.removeClass('disabled opacity-50 cursor-not-allowed');
                }
                
                if (page === totalPages) {
                    nextBtn.addClass('disabled opacity-50 cursor-not-allowed');
                } else {
                    nextBtn.removeClass('disabled opacity-50 cursor-not-allowed');
                }
                
                // Animate posts
                self.animateItems(blogPosts.slice((page - 1) * postsPerPage, page * postsPerPage));
                
                // Scroll to top of blog display
                $('html, body').animate({
                    scrollTop: self.blogDisplay.offset().top - 100
                }, 500);
            });
            
            // Previous button click handler
            prevBtn.on('click', function(e) {
                e.preventDefault();
                
                if ($(this).hasClass('disabled')) {
                    return;
                }
                
                const currentPage = parseInt(numbersContainer.find('a.bg-primary').data('page'));
                if (currentPage > 1) {
                    numbersContainer.find(`a[data-page="${currentPage - 1}"]`).trigger('click');
                }
            });
            
            // Next button click handler
            nextBtn.on('click', function(e) {
                e.preventDefault();
                
                if ($(this).hasClass('disabled')) {
                    return;
                }
                
                const currentPage = parseInt(numbersContainer.find('a.bg-primary').data('page'));
                if (currentPage < totalPages) {
                    numbersContainer.find(`a[data-page="${currentPage + 1}"]`).trigger('click');
                }
            });
        },
        
        /**
         * Initialize load more functionality
         */
        initLoadMore: function() {
            const self = this;
            const loadMoreBtn = $('.load-more-btn');
            
            if (!loadMoreBtn.length) {
                return;
            }
            
            const blogPosts = $('.blog-post');
            const postsPerPage = 6; // This should match the posts_per_page setting
            const totalPosts = blogPosts.length;
            
            // Hide all posts except first page
            blogPosts.hide();
            blogPosts.slice(0, postsPerPage).show();
            
            // Hide load more button if all posts fit on first page
            if (totalPosts <= postsPerPage) {
                loadMoreBtn.hide();
                return;
            }
            
            // Current page tracker
            let currentPage = 1;
            
            // Load more click handler
            loadMoreBtn.on('click', function() {
                currentPage++;
                
                const nextPosts = blogPosts.slice((currentPage - 1) * postsPerPage, currentPage * postsPerPage);
                nextPosts.show();
                
                // Animate new posts
                self.animateItems(nextPosts);
                
                // Hide button if all posts are shown
                if (currentPage * postsPerPage >= totalPosts) {
                    loadMoreBtn.hide();
                }
            });
        },
        
        /**
         * Initialize masonry layout if available
         */
        initMasonry: function() {
            if (this.blogDisplay.hasClass('layout-masonry') && typeof $.fn.masonry !== 'undefined') {
                $('.blog-grid').masonry({
                    itemSelector: '.blog-post',
                    percentPosition: true
                });
                
                // Re-layout masonry after images load
                $('.blog-post__image img').on('load', function() {
                    $('.blog-grid').masonry('layout');
                });
            }
        },
        
        /**
         * Initialize entrance animations for blog posts
         */
        initAnimations: function() {
            const self = this;
            const blogPosts = $('.blog-post:visible');
            
            // Animate each post with a delay
            blogPosts.each(function(index) {
                const $post = $(this);
                self.animateItem($post, index);
            });
        },
        
        /**
         * Animate a collection of items
         * 
         * @param {jQuery} $items - The items to animate
         */
        animateItems: function($items) {
            const self = this;
            
            $items.each(function(index) {
                const $item = $(this);
                self.animateItem($item, index);
            });
        },
        
        /**
         * Animate a single blog post
         * 
         * @param {jQuery} $post - The post to animate
         * @param {number} index - The post index for delay calculation
         */
        animateItem: function($post, index) {
            const animationType = this.blogDisplay.hasClass('animation-slide') ? 'Slide' : 
                                 this.blogDisplay.hasClass('animation-zoom') ? 'Zoom' : 'Fade';
            
            // Reset animation
            $post.css('animation', 'none');
            
            // Trigger reflow
            $post[0].offsetHeight;
            
            // Apply animation with delay based on index
            const delay = index * 0.1;
            $post.css({
                'animation': `blog${animationType} 0.5s ease-in-out ${delay}s forwards`
            });
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        BlogDisplay.init();
    });
    
})(jQuery);