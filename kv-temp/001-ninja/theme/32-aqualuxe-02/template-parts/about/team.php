<?php
/**
 * Template part for displaying the team section on the About page
 *
 * @package AquaLuxe
 */

?>

<section id="team" class="about-team py-16 bg-white dark:bg-dark-800">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 rounded-full text-sm font-medium mb-4">
				<?php esc_html_e( 'Our Team', 'aqualuxe' ); ?>
			</span>
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4">
				<?php esc_html_e( 'Meet the Experts', 'aqualuxe' ); ?>
			</h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto">
				<?php esc_html_e( 'Our team of passionate aquatic specialists brings together decades of experience in marine biology, aquarium design, and customer service.', 'aqualuxe' ); ?>
			</p>
		</div>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<!-- Team Member 1 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-1.jpg' ); ?>" alt="<?php esc_attr_e( 'Dr. Elena Marino', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'Dr. Elena Marino', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Founder & CEO', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'Marine biologist with over 20 years of experience in aquatic ecosystems. Elena founded AquaLuxe with a vision to bring the beauty of the ocean into homes and spaces worldwide.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Marine Biology', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
			
			<!-- Team Member 2 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-2.jpg' ); ?>" alt="<?php esc_attr_e( 'Marcus Chen', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'Marcus Chen', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Chief Design Officer', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'Award-winning aquascape designer who combines artistic vision with technical expertise. Marcus leads our design team in creating stunning aquatic environments that serve as living art.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Aquascape Design', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
			
			<!-- Team Member 3 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-3.jpg' ); ?>" alt="<?php esc_attr_e( 'Dr. Sophia Rodriguez', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'Dr. Sophia Rodriguez', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Head of Conservation', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'Conservation biologist specializing in coral reef ecosystems. Sophia leads our sustainability initiatives and ensures our business practices align with our commitment to environmental stewardship.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Conservation Biology', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
			
			<!-- Team Member 4 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-4.jpg' ); ?>" alt="<?php esc_attr_e( 'James Wilson', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'James Wilson', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Technical Director', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'Aquarium systems engineer with expertise in advanced filtration, lighting, and automation. James ensures that our installations incorporate the latest technology while maintaining optimal conditions for aquatic life.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Aquarium Engineering', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
			
			<!-- Team Member 5 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-5.jpg' ); ?>" alt="<?php esc_attr_e( 'Olivia Nakamura', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'Olivia Nakamura', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Client Relations Director', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'With a background in luxury hospitality and a passion for aquatics, Olivia ensures that every client receives personalized attention and exceptional service throughout their journey with AquaLuxe.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Client Experience', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
			
			<!-- Team Member 6 -->
			<div class="team-member bg-gray-50 dark:bg-dark-750 rounded-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 shadow-soft">
				<div class="team-member-image relative">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-6.jpg' ); ?>" alt="<?php esc_attr_e( 'Dr. Michael Thompson', 'aqualuxe' ); ?>" class="w-full h-auto">
					<div class="team-member-social absolute bottom-0 left-0 right-0 bg-gradient-to-t from-dark-900 to-transparent p-4 flex justify-center space-x-3 opacity-0 hover:opacity-100 transition-opacity duration-300">
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
						<a href="#" class="text-white hover:text-primary-400 transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
								<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
							</svg>
						</a>
					</div>
				</div>
				<div class="team-member-info p-6">
					<h3 class="text-xl font-medium mb-1"><?php esc_html_e( 'Dr. Michael Thompson', 'aqualuxe' ); ?></h3>
					<p class="text-primary-600 dark:text-primary-400 mb-3"><?php esc_html_e( 'Research Director', 'aqualuxe' ); ?></p>
					<p class="text-dark-600 dark:text-dark-300 mb-4">
						<?php esc_html_e( 'Leading our research initiatives, Michael focuses on advancing aquaculture techniques, disease prevention, and ecosystem stability. His work ensures that AquaLuxe remains at the forefront of aquatic science.', 'aqualuxe' ); ?>
					</p>
					<div class="team-member-expertise flex items-center">
						<span class="text-sm text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Expertise:', 'aqualuxe' ); ?></span>
						<span class="text-sm bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full">
							<?php esc_html_e( 'Aquatic Research', 'aqualuxe' ); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="text-center mt-12">
			<a href="<?php echo esc_url( home_url( '/careers' ) ); ?>" class="btn btn-outline">
				<?php esc_html_e( 'Join Our Team', 'aqualuxe' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
				</svg>
			</a>
		</div>
	</div>
</section>