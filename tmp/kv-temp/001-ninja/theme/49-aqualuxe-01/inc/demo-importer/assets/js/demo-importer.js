/**
 * AquaLuxe Demo Importer JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // FAQ accordion
        $('.aqualuxe-demo-faq-item h3').on('click', function() {
            var $item = $(this).parent();
            
            if ($item.hasClass('active')) {
                $item.removeClass('active');
                $item.find('.aqualuxe-demo-faq-content').slideUp(200);
            } else {
                $('.aqualuxe-demo-faq-item').removeClass('active');
                $('.aqualuxe-demo-faq-content').slideUp(200);
                
                $item.addClass('active');
                $item.find('.aqualuxe-demo-faq-content').slideDown(200);
            }
        });
        
        // Generate mock data
        $('#aqualuxe-generate-mock-data').on('click', function() {
            var $button = $(this);
            var $result = $('#aqualuxe-mock-data-result');
            
            // Disable button and show loading state
            $button.prop('disabled', true).text(aqualuxeDemoImporter.generating);
            $result.html('').hide();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeDemoImporter.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_generate_mock_data',
                    nonce: aqualuxeDemoImporter.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $button.text(aqualuxeDemoImporter.generated);
                        
                        // Display stats
                        var stats = response.data.stats;
                        var statsHtml = '<h4>Generated Content:</h4><ul>';
                        
                        if (stats.pages > 0) {
                            statsHtml += '<li>' + stats.pages + ' pages</li>';
                        }
                        
                        if (stats.posts > 0) {
                            statsHtml += '<li>' + stats.posts + ' blog posts</li>';
                        }
                        
                        if (stats.products > 0) {
                            statsHtml += '<li>' + stats.products + ' products</li>';
                        }
                        
                        if (stats.categories > 0) {
                            statsHtml += '<li>' + stats.categories + ' categories</li>';
                        }
                        
                        if (stats.tags > 0) {
                            statsHtml += '<li>' + stats.tags + ' tags</li>';
                        }
                        
                        if (stats.images > 0) {
                            statsHtml += '<li>' + stats.images + ' images</li>';
                        }
                        
                        if (stats.menus > 0) {
                            statsHtml += '<li>' + stats.menus + ' menus</li>';
                        }
                        
                        if (stats.widgets > 0) {
                            statsHtml += '<li>' + stats.widgets + ' widgets</li>';
                        }
                        
                        statsHtml += '</ul><p>Mock data generated successfully! Refresh the page to see the changes.</p>';
                        
                        $result.html(statsHtml).slideDown(200);
                        
                        // Re-enable button after 2 seconds
                        setTimeout(function() {
                            $button.prop('disabled', false).text('Generate Mock Data');
                        }, 2000);
                    } else {
                        // Show error message
                        $button.text(aqualuxeDemoImporter.error);
                        $result.html('<p>Error: ' + response.data.message + '</p>').slideDown(200);
                        
                        // Re-enable button
                        setTimeout(function() {
                            $button.prop('disabled', false).text('Generate Mock Data');
                        }, 2000);
                    }
                },
                error: function() {
                    // Show error message
                    $button.text(aqualuxeDemoImporter.error);
                    $result.html('<p>An error occurred. Please try again.</p>').slideDown(200);
                    
                    // Re-enable button
                    setTimeout(function() {
                        $button.prop('disabled', false).text('Generate Mock Data');
                    }, 2000);
                }
            });
        });
        
        // Reset demo content
        $('#aqualuxe-reset-demo-content').on('click', function() {
            if (confirm('Are you sure you want to reset all demo content? This action cannot be undone.')) {
                alert('This feature is not yet implemented. Please use a WordPress reset plugin to reset your site.');
            }
        });
    });
})(jQuery);