(function ($) {
    "use strict";

    $(function () {
        const importBtn = $("#aqualuxe-import-btn");
        const flushBtn = $("#aqualuxe-flush-btn");
        const messagesContainer = $("#aqualuxe-importer-messages");
        const progressContainer = $("#aqualuxe-importer-progress");
        const logContent = $("#aqualuxe-importer-log-content");

        const apiSettings = {
            nonce: aqualuxeImporter.nonce,
            importUrl: aqualuxeImporter.root + "aqualuxe/v1/import",
            flushUrl: aqualuxeImporter.root + "aqualuxe/v1/flush",
        };

        importBtn.on("click", function () {
            if (!confirm("Are you sure you want to import the demo content?")) {
                return;
            }
            runRequest("import");
        });

        flushBtn.on("click", function () {
            if (
                !confirm(
                    "Are you sure you want to remove all demo content? This action cannot be undone."
                )
            ) {
                return;
            }
            runRequest("flush");
        });

        function runRequest(type) {
            const url =
                type === "import"
                    ? apiSettings.importUrl
                    : apiSettings.flushUrl;
            const btn = type === "import" ? importBtn : flushBtn;

            btn.prop("disabled", true).html("Processing...");
            messagesContainer.html("");
            progressContainer.removeClass("hidden");
            logContent.html("Starting process...");

            $.ajax({
                url: url,
                method: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("X-WP-Nonce", apiSettings.nonce);
                },
                contentType: "application/json",
                data: JSON.stringify({}), // Empty body for now, can be extended
            })
                .done(function (response) {
                    handleSuccess(response, btn, type);
                })
                .fail(function (jqXHR) {
                    handleError(jqXHR, btn, type);
                });
        }

        function handleSuccess(response, btn, type) {
            const message =
                type === "import"
                    ? "Import completed successfully."
                    : "Flush completed successfully.";
            showMessage("success", message);
            updateLog(response.log);
            resetButton(btn, type);
        }

        function handleError(jqXHR, btn, type) {
            const errorMessage =
                jqXHR.responseJSON && jqXHR.responseJSON.message
                    ? jqXHR.responseJSON.message
                    : "An unknown error occurred.";

            showMessage("error", `Error: ${errorMessage}`);

            if (jqXHR.responseJSON && jqXHR.responseJSON.log) {
                updateLog(jqXHR.responseJSON.log);
            } else {
                logContent.append("\n\n" + jqXHR.responseText);
            }

            resetButton(btn, type);
        }

        function showMessage(type, text) {
            const noticeClass =
                type === "success" ? "notice-success" : "notice-error";
            messagesContainer.html(
                `<div class="notice ${noticeClass} is-dismissible"><p>${text}</p></div>`
            );
        }

        function updateLog(log) {
            if (Array.isArray(log)) {
                logContent.text(log.join("\n"));
            } else {
                logContent.text("Log data is not in the expected format.");
            }
            // Scroll to the bottom of the log
            logContent.scrollTop(logContent[0].scrollHeight);
        }

        function resetButton(btn, type) {
            const text =
                type === "import"
                    ? "Import Demo Content"
                    : "Remove Demo Content";
            btn.prop("disabled", false).html(text);
        }
    });
})(jQuery);
