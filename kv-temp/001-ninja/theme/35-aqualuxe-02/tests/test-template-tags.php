<?php
/**
 * Class TestTemplateTags
 *
 * @package AquaLuxe
 */

/**
 * Template tags test case.
 */
class TestTemplateTags extends WP_UnitTestCase {

    /**
     * Test posted on function.
     */
    public function test_aqualuxe_posted_on() {
        // Create a test post.
        $post_id = $this->factory->post->create( array(
            'post_title'   => 'Test Post',
            'post_content' => 'Test content.',
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        // Set the global post to our test post.
        global $post;
        $post = get_post( $post_id );

        // Start output buffering.
        ob_start();
        aqualuxe_posted_on();
        $output = ob_get_clean();

        // Test if the output contains the post date.
        $this->assertStringContainsString( 'Posted on', $output );
        $this->assertStringContainsString( get_the_date(), $output );
    }

    /**
     * Test posted by function.
     */
    public function test_aqualuxe_posted_by() {
        // Create a test post.
        $post_id = $this->factory->post->create( array(
            'post_title'   => 'Test Post',
            'post_content' => 'Test content.',
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        // Set the global post to our test post.
        global $post;
        $post = get_post( $post_id );

        // Start output buffering.
        ob_start();
        aqualuxe_posted_by();
        $output = ob_get_clean();

        // Test if the output contains the author name.
        $this->assertStringContainsString( 'by', $output );
        $this->assertStringContainsString( get_the_author(), $output );
    }

    /**
     * Test entry footer function.
     */
    public function test_aqualuxe_entry_footer() {
        // Create a test post.
        $post_id = $this->factory->post->create( array(
            'post_title'   => 'Test Post',
            'post_content' => 'Test content.',
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        // Add categories and tags to the post.
        wp_set_object_terms( $post_id, array( 'Category 1', 'Category 2' ), 'category' );
        wp_set_object_terms( $post_id, array( 'Tag 1', 'Tag 2' ), 'post_tag' );

        // Set the global post to our test post.
        global $post;
        $post = get_post( $post_id );

        // Start output buffering.
        ob_start();
        aqualuxe_entry_footer();
        $output = ob_get_clean();

        // Test if the output contains the categories and tags.
        $this->assertStringContainsString( 'Posted in', $output );
        $this->assertStringContainsString( 'Category 1', $output );
        $this->assertStringContainsString( 'Category 2', $output );
        $this->assertStringContainsString( 'Tagged', $output );
        $this->assertStringContainsString( 'Tag 1', $output );
        $this->assertStringContainsString( 'Tag 2', $output );
    }

    /**
     * Test post thumbnail function.
     */
    public function test_aqualuxe_post_thumbnail() {
        // Create a test post.
        $post_id = $this->factory->post->create( array(
            'post_title'   => 'Test Post',
            'post_content' => 'Test content.',
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        // Set the global post to our test post.
        global $post;
        $post = get_post( $post_id );

        // Start output buffering.
        ob_start();
        aqualuxe_post_thumbnail();
        $output = ob_get_clean();

        // Test if the output is empty when there is no thumbnail.
        $this->assertEmpty( $output );

        // Create an attachment.
        $attachment_id = $this->factory->attachment->create_upload_object( __DIR__ . '/assets/test-image.jpg', $post_id );

        // Set the attachment as the post thumbnail.
        set_post_thumbnail( $post_id, $attachment_id );

        // Start output buffering again.
        ob_start();
        aqualuxe_post_thumbnail();
        $output = ob_get_clean();

        // Test if the output contains the thumbnail.
        $this->assertStringContainsString( 'post-thumbnail', $output );
        $this->assertStringContainsString( wp_get_attachment_image( $attachment_id, 'post-thumbnail' ), $output );
    }

    /**
     * Test comments link function.
     */
    public function test_aqualuxe_comments_link() {
        // Create a test post.
        $post_id = $this->factory->post->create( array(
            'post_title'   => 'Test Post',
            'post_content' => 'Test content.',
            'post_status'  => 'publish',
            'post_author'  => 1,
        ) );

        // Set the global post to our test post.
        global $post;
        $post = get_post( $post_id );

        // Start output buffering.
        ob_start();
        aqualuxe_comments_link();
        $output = ob_get_clean();

        // Test if the output contains the comments link.
        $this->assertStringContainsString( 'Leave a comment', $output );

        // Add a comment to the post.
        $comment_id = $this->factory->comment->create( array(
            'comment_post_ID' => $post_id,
            'comment_content' => 'Test comment.',
            'comment_approved' => '1',
        ) );

        // Start output buffering again.
        ob_start();
        aqualuxe_comments_link();
        $output = ob_get_clean();

        // Test if the output contains the comment count.
        $this->assertStringContainsString( '1 Comment', $output );

        // Add another comment to the post.
        $comment_id = $this->factory->comment->create( array(
            'comment_post_ID' => $post_id,
            'comment_content' => 'Another test comment.',
            'comment_approved' => '1',
        ) );

        // Start output buffering again.
        ob_start();
        aqualuxe_comments_link();
        $output = ob_get_clean();

        // Test if the output contains the comment count.
        $this->assertStringContainsString( '2 Comments', $output );
    }
}