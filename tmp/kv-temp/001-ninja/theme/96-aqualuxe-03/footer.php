<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>

	</div><!-- #content -->
	<footer id="colophon" class="site-footer bg-gray-900 text-white pt-12 pb-8">
		<div class="container mx-auto px-4">
			<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
				<!-- About Us -->
				<div>
					<h3 class="text-lg font-semibold mb-4">AquaLuxe</h3>
					<p class="text-gray-400">Immerse yourself in the aquatic world with our curated collection of premium products.</p>
				</div>
				<!-- Quick Links -->
				<div>
					<h3 class="text-lg font-semibold mb-4">Quick Links</h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_class'     => 'space-y-2',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</div>
				<!-- Social Media -->
				<div>
					<h3 class="text-lg font-semibold mb-4">Follow Us</h3>
					<div class="flex space-x-4">
						<a href="#" class="text-gray-400 hover:text-white">
							<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
						</a>
						<a href="#" class="text-gray-400 hover:text-white">
							<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c-4.013 0-4.535.017-6.122.09-1.585.073-2.682.34-3.638.743-.98.41-1.753 1.01-2.533 1.79-.78 1.753-1.38 2.533-1.79 3.638-.403.956-.67 2.053-.743 3.638C2.017 7.78 2 8.292 2 12.315c0 4.013.017 4.535.09 6.122.073 1.585.34 2.682.743 3.638.41.98 1.01 1.753 1.79 2.533.78.78 1.553 1.38 2.533 1.79.956.403 2.053.67 3.638.743 1.587.073 2.11.09 6.122.09s4.535-.017 6.122-.09c1.585-.073 2.682-.34 3.638-.743.98-.41 1.753-1.01 2.533-1.79.78-.78 1.38-1.553 1.79-2.533.403-.956.67-2.053.743-3.638.073-1.587.09-2.11.09-6.122s-.017-4.535-.09-6.122c-.073-1.585-.34-2.682-.743-3.638-.41-.98-1.01-1.753-1.79-2.533-.78-.78-1.553-1.38-2.533-1.79-.956-.403-2.053-.67-3.638-.743C16.85 2.017 16.328 2 12.315 2zm0 1.802c3.933 0 4.41.015 5.966.087 1.44.065 2.28.32 2.91.572.72.303 1.255.71 1.79 1.255.535.535.952 1.07 1.255 1.79.252.63.507 1.47.572 2.91.072 1.555.087 2.033.087 5.966s-.015 4.41-.087 5.966c-.065 1.44-.32 2.28-.572 2.91-.303.72-.71 1.255-1.255 1.79-.535.535-1.07.952-1.79 1.255-.63.252-1.47.507-2.91.572-1.555.072-2.033.087-5.966.087s-4.41-.015-5.966-.087c-1.44-.065-2.28-.32-2.91-.572-.72-.303-1.255-.71-1.79-1.255-.535-.535-.952-1.07-1.255-1.79-.252-.63-.507-1.47-.572-2.91-.072-1.555-.087-2.033-.087-5.966s.015-4.41.087-5.966c.065-1.44.32-2.28.572-2.91.303-.72.71-1.255 1.255-1.79.535-.535 1.07-.952 1.79-1.255.63-.252 1.47-.507 2.91-.572C7.905 3.817 8.383 3.802 12.315 3.802zM12 7.38a4.935 4.935 0 100 9.87 4.935 4.935 0 000-9.87zm0 8.068a3.133 3.133 0 110-6.266 3.133 3.133 0 010 6.266zm6.27-7.417a1.163 1.163 0 10-2.326 0 1.163 1.163 0 002.326 0z" clip-rule="evenodd" /></svg>
						</a>
						<a href="#" class="text-gray-400 hover:text-white">
							<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
						</a>
					</div>
				</div>
				<!-- Newsletter -->
				<div>
					<h3 class="text-lg font-semibold mb-4">Newsletter</h3>
					<p class="text-gray-400 mb-4">Subscribe to our newsletter for updates and special offers.</p>
					<form>
						<input type="email" placeholder="Your email" class="w-full p-2 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:border-blue-500">
						<button type="submit" class="w-full mt-2 px-4 py-2 bg-blue-500 rounded hover:bg-blue-700">Subscribe</button>
					</form>
				</div>
			</div>
			<div class="border-t border-gray-800 pt-8 text-center text-gray-500">
				<p>&copy; <?php echo date("Y"); ?> AquaLuxe. All Rights Reserved.</p>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
