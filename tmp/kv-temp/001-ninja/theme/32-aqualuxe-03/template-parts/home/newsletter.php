<?php
/**
 * Template part for displaying newsletter signup on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="newsletter py-16 bg-primary-900 text-white">
	<div class="container mx-auto px-4">
		<div class="max-w-3xl mx-auto text-center">
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4"><?php esc_html_e( 'Stay Updated', 'aqualuxe' ); ?></h2>
			<p class="text-lg text-white text-opacity-90 mb-8"><?php esc_html_e( 'Subscribe to our newsletter for exclusive offers, new arrivals, and expert aquatic care tips.', 'aqualuxe' ); ?></p>
			
			<form class="newsletter-form flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
				<input type="email" class="flex-grow px-4 py-3 rounded-md border-0 focus:ring-2 focus:ring-white focus:ring-opacity-50 bg-white bg-opacity-20 placeholder-white placeholder-opacity-70 text-white" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
				<button type="submit" class="btn bg-white text-primary-900 hover:bg-gray-100 px-6 py-3 font-medium">
					<?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
				</button>
			</form>
			
			<p class="text-sm text-white text-opacity-70 mt-4"><?php esc_html_e( 'By subscribing, you agree to our Privacy Policy and consent to receive updates from our company.', 'aqualuxe' ); ?></p>
			
			<div class="social-links flex justify-center mt-8 space-x-4">
				<a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors duration-200 p-3 rounded-full">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors duration-200 p-3 rounded-full">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors duration-200 p-3 rounded-full">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors duration-200 p-3 rounded-full">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
					</svg>
					<span class="sr-only"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></span>
				</a>
				<a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors duration-200 p-3 rounded-full">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.441 16.892c-2.102.144-6.784.144-8.883 0-2.276-.156-2.541-1.27-2.558-4.892.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0 2.277.156 2.541 1.27 2.559 4.892-.018 3.629-.285 4.736-2.559 4.892zm-6.441-7.234l4.917 2.338-4.917 2.346v-4.684z"/>
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</section>