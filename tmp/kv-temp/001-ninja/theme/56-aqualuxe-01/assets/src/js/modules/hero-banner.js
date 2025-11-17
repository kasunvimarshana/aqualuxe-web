/**
 * Hero Banner Module
 * 
 * Handles hero banner functionality including video backgrounds and animations
 */

const HeroBanner = {
    /**
     * Initialize the hero banner module
     */
    init() {
        this.setupYouTubeVideos();
        this.setupAnimations();
    },

    /**
     * Setup YouTube video backgrounds
     */
    setupYouTubeVideos() {
        const youtubeVideos = document.querySelectorAll('.hero-banner__youtube-video');
        
        if (!youtubeVideos.length || typeof YT === 'undefined') {
            return;
        }

        // Load YouTube API if not already loaded
        if (!window.YT) {
            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            
            window.onYouTubeIframeAPIReady = () => {
                this.createYouTubePlayers(youtubeVideos);
            };
        } else {
            this.createYouTubePlayers(youtubeVideos);
        }
    },

    /**
     * Create YouTube players for video backgrounds
     * 
     * @param {NodeList} elements YouTube video elements
     */
    createYouTubePlayers(elements) {
        elements.forEach(element => {
            const videoId = element.dataset.videoId;
            
            if (!videoId) {
                return;
            }
            
            new YT.Player(element, {
                videoId: videoId,
                playerVars: {
                    autoplay: 1,
                    controls: 0,
                    disablekb: 1,
                    enablejsapi: 1,
                    iv_load_policy: 3,
                    loop: 1,
                    modestbranding: 1,
                    playsinline: 1,
                    rel: 0,
                    showinfo: 0,
                    mute: 1
                },
                events: {
                    onReady: (event) => {
                        event.target.mute();
                        event.target.playVideo();
                    },
                    onStateChange: (event) => {
                        // Loop the video when it ends
                        if (event.data === YT.PlayerState.ENDED) {
                            event.target.playVideo();
                        }
                    }
                }
            });
        });
    },

    /**
     * Setup animations for hero banner elements
     */
    setupAnimations() {
        const heroBanners = document.querySelectorAll('.hero-banner');
        
        if (!heroBanners.length) {
            return;
        }

        // Setup Intersection Observer to trigger animations when banner is in view
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

        heroBanners.forEach(banner => {
            observer.observe(banner);
        });
    },

    /**
     * Resize video backgrounds to maintain aspect ratio
     */
    resizeVideoBackgrounds() {
        const videos = document.querySelectorAll('.hero-banner__video');
        
        if (!videos.length) {
            return;
        }

        videos.forEach(video => {
            const container = video.closest('.hero-banner__video-container');
            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;
            const containerAspectRatio = containerWidth / containerHeight;
            const videoAspectRatio = 16 / 9; // Assuming 16:9 aspect ratio for videos
            
            let width, height;
            
            if (containerAspectRatio > videoAspectRatio) {
                // Container is wider than video
                width = containerWidth;
                height = containerWidth / videoAspectRatio;
            } else {
                // Container is taller than video
                width = containerHeight * videoAspectRatio;
                height = containerHeight;
            }
            
            video.style.width = `${width}px`;
            video.style.height = `${height}px`;
            video.style.left = `${(containerWidth - width) / 2}px`;
            video.style.top = `${(containerHeight - height) / 2}px`;
        });
    }
};

// Initialize the hero banner module when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    HeroBanner.init();
    
    // Resize video backgrounds on window resize
    window.addEventListener('resize', () => {
        HeroBanner.resizeVideoBackgrounds();
    });
    
    // Initial resize
    HeroBanner.resizeVideoBackgrounds();
});

export default HeroBanner;