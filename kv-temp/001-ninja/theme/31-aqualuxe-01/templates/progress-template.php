<?php
/**
 * Demo Content Importer Progress Template
 *
 * @package DemoContentImporter
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Get progress instance
$progress = Demo_Content_Importer_Progress::get_instance();
$import_id = $progress->get_import_id();
?>

<div id="dci-progress-container" class="dci-progress-container" data-import-id="<?php echo esc_attr($import_id); ?>">
    <!-- Overall Progress -->
    <div class="dci-progress-section">
        <div class="dci-progress-section-title">
            <span>Overall Progress</span>
            <span id="dci-overall-progress-percentage">0%</span>
        </div>
        <div class="dci-progress-bar-container">
            <div id="dci-overall-progress-bar" class="dci-progress-bar">
                <div class="dci-progress-bar-stripes"></div>
            </div>
        </div>
        <div class="dci-progress-status">
            <span id="dci-overall-progress-status">Preparing import...</span>
        </div>
    </div>

    <!-- Current Step Progress -->
    <div class="dci-progress-section">
        <div class="dci-progress-section-title">
            <span>Current Step</span>
            <span id="dci-step-progress-items" class="dci-progress-count">0 / 0</span>
        </div>
        <div class="dci-progress-bar-container">
            <div id="dci-step-progress-bar" class="dci-progress-bar">
                <div class="dci-progress-bar-stripes"></div>
            </div>
        </div>
        <div class="dci-progress-status">
            <span id="dci-step-progress-status">Waiting to start...</span>
            <span id="dci-step-progress-percentage">0%</span>
        </div>
    </div>

    <!-- Time Estimates -->
    <div class="dci-time-estimates">
        <div class="dci-time-estimate">
            <span class="dci-time-estimate-label">Elapsed Time</span>
            <span id="dci-elapsed-time" class="dci-time-estimate-value">0 seconds</span>
        </div>
        <div class="dci-time-estimate">
            <span class="dci-time-estimate-label">Estimated Time Remaining</span>
            <span id="dci-estimated-time" class="dci-time-estimate-value">Calculating...</span>
        </div>
    </div>

    <!-- Progress Messages -->
    <div class="dci-progress-messages-container">
        <div class="dci-progress-section-title">
            <span>Progress Log</span>
        </div>
        <div id="dci-progress-messages" class="dci-progress-messages">
            <div class="dci-progress-message">
                <span class="dci-progress-message-time">[<?php echo esc_html(date('H:i:s')); ?>]</span>
                <span class="dci-progress-message-text dci-message-info">Initializing import process...</span>
            </div>
        </div>
    </div>
</div>

<!-- Steps List Template (Hidden, will be populated by JavaScript) -->
<script type="text/template" id="dci-steps-template">
    <div class="dci-steps-list">
        <div class="dci-step-item {{status}}" data-step="{{step_id}}">
            <div class="dci-step-status {{status}}">
                {{status_icon}}
            </div>
            <div class="dci-step-name">{{name}}</div>
            <div class="dci-step-time">{{time}}</div>
        </div>
    </div>
</script>