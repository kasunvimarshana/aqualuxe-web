/**
 * Demo Content Importer Progress Indicator
 */

(function($) {
    'use strict';

    // Global variables
    var DCI_Progress = {
        importId: '',
        isRunning: false,
        updateInterval: null,
        updateFrequency: 2000, // 2 seconds
        progressData: {},
        startTime: 0
    };

    // Initialize
    $(document).ready(function() {
        initProgressIndicator();
    });

    /**
     * Initialize progress indicator.
     */
    function initProgressIndicator() {
        // Check if progress container exists
        if ($('#dci-progress-container').length === 0) {
            return;
        }

        // Set up event listeners
        $('#dci-start-import').on('click', function() {
            startProgressTracking();
        });

        $('#dci-cancel-import').on('click', function() {
            stopProgressTracking();
        });

        // Check if import is already running
        checkImportStatus();
    }

    /**
     * Check if import is already running.
     */
    function checkImportStatus() {
        // Get import ID from data attribute
        var storedImportId = $('#dci-progress-container').data('import-id');
        
        if (storedImportId) {
            DCI_Progress.importId = storedImportId;
            startProgressTracking(true); // true = resume
        }
    }

    /**
     * Start progress tracking.
     *
     * @param {boolean} resume Whether to resume tracking.
     */
    function startProgressTracking(resume) {
        // If already running, do nothing
        if (DCI_Progress.isRunning) {
            return;
        }

        // Set running state
        DCI_Progress.isRunning = true;
        
        // If not resuming, get new import ID
        if (!resume) {
            DCI_Progress.importId = $('#dci-progress-container').data('import-id');
            DCI_Progress.startTime = new Date().getTime();
        }

        // Show progress UI
        showProgressUI();

        // Start update interval
        DCI_Progress.updateInterval = setInterval(updateProgress, DCI_Progress.updateFrequency);

        // Initial update
        updateProgress();
    }

    /**
     * Stop progress tracking.
     */
    function stopProgressTracking() {
        // If not running, do nothing
        if (!DCI_Progress.isRunning) {
            return;
        }

        // Clear update interval
        clearInterval(DCI_Progress.updateInterval);

        // Reset running state
        DCI_Progress.isRunning = false;

        // Hide progress UI
        hideProgressUI();
    }

    /**
     * Update progress.
     */
    function updateProgress() {
        // If no import ID, do nothing
        if (!DCI_Progress.importId) {
            return;
        }

        // Get progress data via AJAX
        $.ajax({
            url: dciProgress.ajaxUrl,
            type: 'POST',
            data: {
                action: 'dci_get_progress',
                nonce: dciProgress.nonce,
                import_id: DCI_Progress.importId
            },
            success: function(response) {
                if (response.success) {
                    // Update progress data
                    DCI_Progress.progressData = response.data.progress;

                    // Update UI
                    updateProgressUI();

                    // Check if import is complete
                    if (DCI_Progress.progressData.status === 'completed') {
                        completeProgress();
                    }
                } else {
                    console.error('Failed to get progress data:', response.data.message);
                }
            },
            error: function() {
                console.error('AJAX error when getting progress data');
            }
        });
    }

    /**
     * Show progress UI.
     */
    function showProgressUI() {
        // Hide import options
        $('#dci-import-options').slideUp();

        // Show progress container
        $('#dci-progress-container').slideDown();

        // Update button states
        $('#dci-start-import').prop('disabled', true);
        $('#dci-cancel-import').prop('disabled', false);
    }

    /**
     * Hide progress UI.
     */
    function hideProgressUI() {
        // Show import options
        $('#dci-import-options').slideDown();

        // Hide progress container
        $('#dci-progress-container').slideUp();

        // Update button states
        $('#dci-start-import').prop('disabled', false);
        $('#dci-cancel-import').prop('disabled', true);
    }

    /**
     * Update progress UI.
     */
    function updateProgressUI() {
        var data = DCI_Progress.progressData;
        
        // If no data, do nothing
        if (!data) {
            return;
        }

        // Update overall progress
        updateOverallProgress(data);

        // Update step progress
        updateStepProgress(data);

        // Update messages
        updateMessages(data);

        // Update time estimates
        updateTimeEstimates(data);
    }

    /**
     * Update overall progress.
     *
     * @param {Object} data Progress data.
     */
    function updateOverallProgress(data) {
        // Update progress bar
        var percentage = Math.round(data.percentage);
        $('#dci-overall-progress-bar').css('width', percentage + '%');
        $('#dci-overall-progress-percentage').text(percentage + '%');

        // Update status text
        var statusText = 'Importing...';
        if (data.status === 'completed') {
            statusText = 'Import completed';
        } else if (data.status === 'failed') {
            statusText = 'Import failed';
        } else if (data.current_step) {
            var stepName = data.current_step;
            if (data.steps && data.steps[data.current_step]) {
                stepName = data.steps[data.current_step].name;
            }
            statusText = 'Processing: ' + stepName;
        }
        $('#dci-overall-progress-status').text(statusText);
    }

    /**
     * Update step progress.
     *
     * @param {Object} data Progress data.
     */
    function updateStepProgress(data) {
        // If no current step, do nothing
        if (!data.current_step) {
            return;
        }

        // Update step progress bar
        var percentage = Math.round(data.item_percentage);
        $('#dci-step-progress-bar').css('width', percentage + '%');
        $('#dci-step-progress-percentage').text(percentage + '%');

        // Update step status text
        var statusText = '';
        if (data.current_item) {
            statusText = 'Processing: ' + data.current_item;
        } else {
            statusText = 'Processing items...';
        }
        $('#dci-step-progress-status').text(statusText);

        // Update step counts
        var itemsText = data.completed_items + ' / ' + data.total_items;
        $('#dci-step-progress-items').text(itemsText);
    }

    /**
     * Update messages.
     *
     * @param {Object} data Progress data.
     */
    function updateMessages(data) {
        // If no messages, do nothing
        if (!data.messages || data.messages.length === 0) {
            return;
        }

        // Get messages container
        var $messagesContainer = $('#dci-progress-messages');

        // Clear container
        $messagesContainer.empty();

        // Add messages (most recent first, limit to 10)
        var messages = data.messages.slice().reverse().slice(0, 10);
        for (var i = 0; i < messages.length; i++) {
            var message = messages[i];
            var $message = $('<div class="dci-progress-message"></div>');
            
            // Add timestamp
            var timestamp = new Date(message.time * 1000).toLocaleTimeString();
            $message.append('<span class="dci-progress-message-time">[' + timestamp + ']</span> ');
            
            // Add message text with appropriate class
            $message.append('<span class="dci-progress-message-text dci-message-' + message.type + '">' + message.message + '</span>');
            
            // Add to container
            $messagesContainer.append($message);
        }

        // Scroll to bottom
        $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);
    }

    /**
     * Update time estimates.
     *
     * @param {Object} data Progress data.
     */
    function updateTimeEstimates(data) {
        // Calculate elapsed time
        var elapsedTime = data.elapsed_time;
        if (!elapsedTime) {
            elapsedTime = Math.floor((new Date().getTime() - DCI_Progress.startTime) / 1000);
        }
        
        // Format elapsed time
        var elapsedFormatted = formatTime(elapsedTime);
        $('#dci-elapsed-time').text(elapsedFormatted);

        // Calculate estimated time remaining
        var estimatedRemaining = data.estimated_time;
        if (estimatedRemaining) {
            // Format estimated time
            var estimatedFormatted = formatTime(estimatedRemaining);
            $('#dci-estimated-time').text(estimatedFormatted);
        } else {
            $('#dci-estimated-time').text('Calculating...');
        }
    }

    /**
     * Complete progress.
     */
    function completeProgress() {
        // Stop tracking
        stopProgressTracking();

        // Show completion message
        showCompletionMessage();
    }

    /**
     * Show completion message.
     */
    function showCompletionMessage() {
        // Create completion message
        var $completionMessage = $('<div class="dci-completion-message"></div>');
        $completionMessage.append('<h3>Import Completed Successfully!</h3>');
        $completionMessage.append('<p>All content has been imported and set up.</p>');
        
        // Add elapsed time
        var elapsedTime = DCI_Progress.progressData.elapsed_time;
        if (elapsedTime) {
            var elapsedFormatted = formatTime(elapsedTime);
            $completionMessage.append('<p>Total time: ' + elapsedFormatted + '</p>');
        }

        // Add buttons
        var $buttons = $('<div class="dci-completion-buttons"></div>');
        $buttons.append('<a href="' + dciProgress.adminUrl + '" class="button">Return to Dashboard</a>');
        $buttons.append('<a href="' + dciProgress.siteUrl + '" class="button button-primary" target="_blank">View Site</a>');
        $completionMessage.append($buttons);

        // Show completion message
        $('#dci-progress-container').html($completionMessage);
    }

    /**
     * Format time in seconds to human-readable format.
     *
     * @param {number} seconds Time in seconds.
     * @return {string} Formatted time.
     */
    function formatTime(seconds) {
        if (seconds < 60) {
            return seconds + ' seconds';
        } else if (seconds < 3600) {
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            return minutes + ' minute' + (minutes !== 1 ? 's' : '') + ' ' + remainingSeconds + ' second' + (remainingSeconds !== 1 ? 's' : '');
        } else {
            var hours = Math.floor(seconds / 3600);
            var remainingMinutes = Math.floor((seconds % 3600) / 60);
            return hours + ' hour' + (hours !== 1 ? 's' : '') + ' ' + remainingMinutes + ' minute' + (remainingMinutes !== 1 ? 's' : '');
        }
    }

})(jQuery);