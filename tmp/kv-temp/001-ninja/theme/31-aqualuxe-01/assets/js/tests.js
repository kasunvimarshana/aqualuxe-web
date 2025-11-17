/**
 * Demo Content Importer Tests Scripts
 */

(function($) {
    'use strict';

    // Initialize
    $(document).ready(function() {
        initTestsPage();
    });

    /**
     * Initialize tests page.
     */
    function initTestsPage() {
        // Category checkbox toggle
        $('.category-checkbox').on('change', function() {
            const category = $(this).data('category');
            const checked = $(this).prop('checked');
            
            $(this).closest('.dci-test-category')
                .find('.test-checkbox')
                .prop('checked', checked);
        });

        // Test checkbox toggle
        $('.test-checkbox').on('change', function() {
            updateCategoryCheckboxes();
        });

        // Run all tests button
        $('#run-all-tests').on('click', function() {
            $('.test-checkbox').prop('checked', true);
            updateCategoryCheckboxes();
            runTests();
        });

        // Run selected tests button
        $('#run-selected-tests').on('click', function() {
            runTests();
        });
    }

    /**
     * Update category checkboxes based on test checkboxes.
     */
    function updateCategoryCheckboxes() {
        $('.category-checkbox').each(function() {
            const category = $(this).data('category');
            const $testCheckboxes = $(this).closest('.dci-test-category').find('.test-checkbox');
            const allChecked = $testCheckboxes.length === $testCheckboxes.filter(':checked').length;
            
            $(this).prop('checked', allChecked);
        });
    }

    /**
     * Run tests.
     */
    function runTests() {
        // Get selected tests
        const selectedTests = [];
        $('.test-checkbox:checked').each(function() {
            selectedTests.push($(this).data('test'));
        });

        // If no tests selected, show message
        if (selectedTests.length === 0) {
            alert('Please select at least one test to run.');
            return;
        }

        // Show running indicator
        const $resultsContainer = $('#dci-test-results-container');
        $resultsContainer.html(
            '<div class="dci-test-running">' +
            '<span class="spinner"></span>' +
            '<span>Running tests...</span>' +
            '</div>'
        );

        // Run tests via AJAX
        $.ajax({
            url: dciTests.ajaxUrl,
            type: 'POST',
            data: {
                action: 'run_dci_tests',
                nonce: dciTests.nonce,
                tests: selectedTests
            },
            success: function(response) {
                if (response.success) {
                    displayTestResults(response.data.results);
                } else {
                    $resultsContainer.html(
                        '<div class="notice notice-error">' +
                        '<p>' + response.data.message + '</p>' +
                        '</div>'
                    );
                }
            },
            error: function() {
                $resultsContainer.html(
                    '<div class="notice notice-error">' +
                    '<p>An error occurred while running tests.</p>' +
                    '</div>'
                );
            }
        });
    }

    /**
     * Display test results.
     *
     * @param {Object} results Test results.
     */
    function displayTestResults(results) {
        const $resultsContainer = $('#dci-test-results-container');
        let html = '';

        // Count results by status
        const stats = {
            pass: 0,
            fail: 0,
            warning: 0,
            error: 0
        };

        // Generate HTML for each test result
        for (const [testId, result] of Object.entries(results)) {
            // Update stats
            stats[result.status]++;

            // Get test name
            const testName = getTestName(testId);

            // Get status icon
            const statusIcon = getStatusIcon(result.status);

            // Generate HTML
            html += '<div class="dci-test-result ' + result.status + '">';
            html += '<h3>' + statusIcon + testName + '</h3>';
            html += '<p>' + result.message + '</p>';

            // Add details if available
            if (result.details && result.details.length > 0) {
                html += '<div class="dci-test-details">';
                for (const detail of result.details) {
                    html += '<div class="dci-test-detail">';
                    html += '<div class="dci-test-detail-name">' + detail.name + ':</div>';
                    html += '<div class="dci-test-detail-value">' + detail.value + '</div>';
                    html += '</div>';
                }
                html += '</div>';
            }

            html += '</div>';
        }

        // Add summary
        html += '<div class="dci-test-summary">';
        html += '<h3>Test Summary</h3>';
        html += '<div class="dci-test-stats">';
        html += '<div class="dci-test-stat pass"><span class="dci-test-stat-count">' + stats.pass + '</span><span class="dci-test-stat-label">Passed</span></div>';
        html += '<div class="dci-test-stat fail"><span class="dci-test-stat-count">' + stats.fail + '</span><span class="dci-test-stat-label">Failed</span></div>';
        html += '<div class="dci-test-stat warning"><span class="dci-test-stat-count">' + stats.warning + '</span><span class="dci-test-stat-label">Warnings</span></div>';
        html += '<div class="dci-test-stat error"><span class="dci-test-stat-count">' + stats.error + '</span><span class="dci-test-stat-label">Errors</span></div>';
        html += '</div>';
        html += '</div>';

        // Update results container
        $resultsContainer.html(html);
    }

    /**
     * Get test name from test ID.
     *
     * @param {string} testId Test ID.
     * @return {string} Test name.
     */
    function getTestName(testId) {
        const testNames = {
            core_initialization: 'Core Initialization',
            core_hooks: 'Core Hooks',
            core_admin_pages: 'Admin Pages',
            import_demo_packages: 'Demo Packages',
            import_content: 'Content Import',
            import_customizer: 'Customizer Import',
            import_widgets: 'Widgets Import',
            import_options: 'Options Import',
            theme_integration: 'Theme Integration',
            theme_aqualuxe: 'AquaLuxe Integration',
            backup_create: 'Create Backup',
            backup_restore: 'Restore Backup',
            reset_site: 'Reset Site',
            performance_memory: 'Memory Usage',
            performance_time: 'Execution Time',
            performance_queries: 'Database Queries'
        };

        return testNames[testId] || testId;
    }

    /**
     * Get status icon.
     *
     * @param {string} status Status.
     * @return {string} Status icon HTML.
     */
    function getStatusIcon(status) {
        const icons = {
            pass: 'dashicons-yes',
            fail: 'dashicons-no',
            warning: 'dashicons-warning',
            error: 'dashicons-no'
        };

        return '<span class="dashicons ' + icons[status] + '"></span>';
    }

})(jQuery);