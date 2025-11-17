jQuery(document).ready(function($) {
    $('#dark-mode-toggle').on('click', function() {
        $('body').toggleClass('dark-mode');
        
        // Save preference in local storage
        if ($('body').hasClass('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    // Check for saved preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        $('body').addClass('dark-mode');
    }
});
