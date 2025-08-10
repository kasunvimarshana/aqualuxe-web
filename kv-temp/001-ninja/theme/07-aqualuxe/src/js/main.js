/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Dark Mode Functionality
document.addEventListener('DOMContentLoaded', () => {
  // Dark mode toggle
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  
  if (darkModeToggle) {
    // Check for saved theme preference or respect OS preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const hasUserPreference = localStorage.getItem('color-theme');
    
    // Set initial state based on localStorage or system preference
    if (hasUserPreference === 'dark' || (!hasUserPreference && darkModeMediaQuery.matches)) {
      document.documentElement.classList.add('dark');
      darkModeToggle.setAttribute('aria-checked', 'true');
    } else {
      document.documentElement.classList.remove('dark');
      darkModeToggle.setAttribute('aria-checked', 'false');
    }
    
    // Toggle dark mode on click
    darkModeToggle.addEventListener('click', () => {
      const isDarkMode = document.documentElement.classList.contains('dark');
      
      if (isDarkMode) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('color-theme', 'light');
        darkModeToggle.setAttribute('aria-checked', 'false');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
        darkModeToggle.setAttribute('aria-checked', 'true');
      }
    });
  }
  
  // Mobile menu toggle
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  
  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => {
      const expanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
      mobileMenuButton.setAttribute('aria-expanded', !expanded);
      mobileMenu.classList.toggle('hidden');
    });
  }
  
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      if (href !== '#') {
        e.preventDefault();
        
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth'
          });
          
          // Update URL without page reload
          history.pushState(null, null, href);
        }
      }
    });
  });
  
  // Intersection Observer for animations
  const animatedElements = document.querySelectorAll('.animate-on-scroll');
  
  if (animatedElements.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1
    });
    
    animatedElements.forEach(element => {
      observer.observe(element);
    });
  }
});

// Header scroll behavior
window.addEventListener('scroll', () => {
  const header = document.querySelector('.site-header');
  
  if (header) {
    if (window.scrollY > 50) {
      header.classList.add('header-scrolled');
    } else {
      header.classList.remove('header-scrolled');
    }
  }
});

// Custom cursor for luxury feel (optional)
const createCustomCursor = () => {
  const cursor = document.createElement('div');
  const follower = document.createElement('div');
  
  cursor.className = 'custom-cursor';
  follower.className = 'custom-cursor-follower';
  
  document.body.appendChild(cursor);
  document.body.appendChild(follower);
  
  let cursorX = 0;
  let cursorY = 0;
  let followerX = 0;
  let followerY = 0;
  
  document.addEventListener('mousemove', (e) => {
    cursorX = e.clientX;
    cursorY = e.clientY;
    
    cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;
  });
  
  function animate() {
    followerX += (cursorX - followerX) / 10;
    followerY += (cursorY - followerY) / 10;
    
    follower.style.transform = `translate(${followerX}px, ${followerY}px)`;
    
    requestAnimationFrame(animate);
  }
  
  animate();
  
  // Add/remove classes for links and buttons
  const links = document.querySelectorAll('a, button, input[type="submit"], .clickable');
  
  links.forEach(link => {
    link.addEventListener('mouseenter', () => {
      cursor.classList.add('cursor-hover');
      follower.classList.add('follower-hover');
    });
    
    link.addEventListener('mouseleave', () => {
      cursor.classList.remove('cursor-hover');
      follower.classList.remove('follower-hover');
    });
  });
};

// Only enable custom cursor on non-touch devices
if (!('ontouchstart' in window)) {
  // Uncomment to enable custom cursor
  // createCustomCursor();
}

// Export any functions that might be needed elsewhere
export {
  // Add exportable functions here
};