<?php
/** Dark mode module */
if (!defined('ABSPATH')) { exit; }

add_action('wp_footer', function(){
    echo '<button class="ax-dark-toggle fixed bottom-4 right-4 z-50 p-3 rounded-full bg-cyan-600 text-white shadow-lg" aria-pressed="false" aria-label="Toggle dark mode">\n<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="10" cy="10" r="9" fill="currentColor"/></svg></button>';
});

add_action('wp_enqueue_scripts', function(){
    wp_add_inline_script('aqualuxe-app', "(function(){try{var k='ax:dark',q=localStorage.getItem(k);if(q==='1'){document.documentElement.classList.add('dark')}document.addEventListener('click',function(e){var b=e.target.closest('.ax-dark-toggle');if(!b)return;var d=document.documentElement.classList.toggle('dark');localStorage.setItem(k,d?'1':'0');b.setAttribute('aria-pressed',d?'true':'false');});}catch(e){}})();");
}, 20);
