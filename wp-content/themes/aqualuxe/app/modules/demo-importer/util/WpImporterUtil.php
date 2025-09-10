<?php

namespace App\Modules\DemoImporter\Util;

use WP_User;

/**
 * Class WpImporterUtil
 *
 * Utility functions for the WordPress import process.
 *
 * @package App\Modules\DemoImporter\Util
 */
class WpImporterUtil
{
    /**
     * Check if a post exists by title.
     *
     * @param string $title
     * @return bool
     */
    public static function post_exists(string $title): bool
    {
        $post = get_page_by_title($title, OBJECT, 'any');
        return $post !== null;
    }

    /**
     * Get an existing author or create a new one.
     *
     * @return WP_User
     */
    public static function get_or_create_author(): WP_User
    {
        $author_login = 'aqualuxe_admin';
        $author_email = 'admin@aqualuxe.com';

        $author = get_user_by('login', $author_login);
        if ($author) {
            return $author;
        }

        $user_id = wp_create_user($author_login, wp_generate_password(), $author_email);
        $user = get_user_by('id', $user_id);
        if (!$user) {
            // This should not happen, but as a fallback, return the current user.
            return wp_get_current_user();
        }
        $user->set_role('administrator');

        Logger::log("Created new author '{$author_login}'.");

        return $user;
    }

    /**
     * Set the featured image for a post.
     *
     * This is a placeholder. In a real scenario, this would involve
     * downloading the image and attaching it. For now, it logs the action.
     *
     * @param int $post_id
     * @param string $image_url
     */
    public static function set_featured_image(int $post_id, string $image_url)
    {
        // In a real implementation, you would use media_sideload_image()
        // For now, we just log that we would have done it.
        Logger::log("Attempting to set featured image '{$image_url}' for post ID {$post_id}. (Placeholder)");

        // Example of how it would work:
        /*
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_id = media_sideload_image($image_url, $post_id, null, 'id');

        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
            Logger::log("...Successfully set featured image for post ID {$post_id}.");
        } else {
            Logger::log("...Failed to set featured image. Error: " . $image_id->get_error_message());
        }
        */
    }
}
