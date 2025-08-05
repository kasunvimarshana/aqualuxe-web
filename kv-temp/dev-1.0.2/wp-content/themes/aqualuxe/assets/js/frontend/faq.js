/**
 * AquaLuxe FAQ Accordion
 */
(function () {
  'use strict';

  // Document ready
  document.addEventListener('DOMContentLoaded', function () {
    // FAQ Accordion
    var faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(function (item) {
      var question = item.querySelector('.faq-question');

      question.addEventListener('click', function () {
        // Toggle active class
        item.classList.toggle('active');

        // Close other items
        faqItems.forEach(function (otherItem) {
          if (otherItem !== item && otherItem.classList.contains('active')) {
            otherItem.classList.remove('active');
          }
        });
      });
    });
  });
})();
