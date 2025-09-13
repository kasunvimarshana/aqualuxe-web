/**
 * Animation utilities for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

export const AnimationUtils = {
  /**
   * Fade in animation
   */
  fadeIn(element, duration = 300) {
    element.style.opacity = '0';
    element.style.display = 'block';

    const start = performance.now();

    function animate(timestamp) {
      const elapsed = timestamp - start;
      const progress = Math.min(elapsed / duration, 1);

      element.style.opacity = progress.toString();

      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    }

    requestAnimationFrame(animate);
  },

  /**
   * Fade out animation
   */
  fadeOut(element, duration = 300) {
    const start = performance.now();
    const initialOpacity = parseFloat(getComputedStyle(element).opacity) || 1;

    function animate(timestamp) {
      const elapsed = timestamp - start;
      const progress = Math.min(elapsed / duration, 1);

      element.style.opacity = (initialOpacity * (1 - progress)).toString();

      if (progress < 1) {
        requestAnimationFrame(animate);
      } else {
        element.style.display = 'none';
      }
    }

    requestAnimationFrame(animate);
  },

  /**
   * Slide down animation
   */
  slideDown(element, duration = 300) {
    element.style.height = '0';
    element.style.overflow = 'hidden';
    element.style.display = 'block';

    const targetHeight = element.scrollHeight;
    const start = performance.now();

    function animate(timestamp) {
      const elapsed = timestamp - start;
      const progress = Math.min(elapsed / duration, 1);

      element.style.height = targetHeight * progress + 'px';

      if (progress < 1) {
        requestAnimationFrame(animate);
      } else {
        element.style.height = '';
        element.style.overflow = '';
      }
    }

    requestAnimationFrame(animate);
  },
};
