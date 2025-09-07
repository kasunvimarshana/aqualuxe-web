<?php

class Core_Test extends WP_UnitTestCase {

    public function test_theme_setup() {
        $this->assertTrue( function_exists( 'aqualuxe_setup' ) );
    }

    public function test_cpt_and_taxonomies_are_registered() {
        // Test a few CPTs and Taxonomies to ensure they are registered
        $this->assertTrue( post_type_exists( 'service' ) );
        $this->assertTrue( post_type_exists( 'project' ) );
        $this->assertTrue( taxonomy_exists( 'service_category' ) );
        $this.assertTrue( taxonomy_exists( 'project_category' ) );
    }
}
