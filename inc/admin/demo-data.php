<?php
/**
 * Demo Content Data
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Data Class
 */
class AquaLuxe_Demo_Data
{
    /**
     * Get demo posts data
     */
    public static function get_posts_data()
    {
        return [
            [
                'title' => 'The Art of Aquascaping: Creating Underwater Masterpieces',
                'content' => '<p>Aquascaping is more than just arranging plants and rocks in an aquarium—it\'s the art of creating living underwater landscapes that captivate and inspire. This ancient practice, rooted in Japanese and Dutch traditions, has evolved into a sophisticated art form that combines biology, design, and creativity.</p>

<h3>Understanding the Fundamentals</h3>
<p>Every great aquascape begins with understanding the fundamental principles of design: balance, proportion, contrast, and focal points. The rule of thirds, borrowed from photography and painting, applies beautifully to aquascaping, helping create visually pleasing compositions.</p>

<h3>Choosing the Right Elements</h3>
<p>The hardscape—consisting of rocks, driftwood, and substrate—forms the backbone of your aquascape. Dragon stone, Ohko stone, and Seiryu stone each offer unique textures and colors. Driftwood adds natural curves and provides attachment points for plants.</p>

<h3>Plant Selection and Placement</h3>
<p>Plants breathe life into your aquascape. Foreground plants like Hemianthus callitrichoides create lush carpets, while background plants such as Vallisneria provide height and depth. Mid-ground plants bridge these elements harmoniously.</p>

<p>Remember, patience is key in aquascaping. Your underwater garden will evolve over time, requiring careful maintenance and pruning to maintain its intended design.</p>',
                'excerpt' => 'Discover the fundamental principles of aquascaping and learn how to create stunning underwater landscapes that combine biology, design, and artistry.',
                'category' => 'Aquascaping',
                'tags' => ['aquascaping', 'design', 'plants', 'tutorial'],
                'featured_image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c787?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-15 10:00:00',
                'meta' => [
                    'reading_time' => '8 minutes',
                    'difficulty_level' => 'intermediate',
                    'featured_post' => true
                ]
            ],
            [
                'title' => 'Caring for Betta Fish: A Complete Guide to Happy, Healthy Bettas',
                'content' => '<p>Betta fish, also known as Siamese fighting fish, are among the most popular aquarium fish worldwide. Their vibrant colors, flowing fins, and unique personalities make them captivating pets. However, proper care is essential for their wellbeing.</p>

<h3>Tank Requirements</h3>
<p>Contrary to popular belief, bettas need more than a small bowl. A minimum 5-gallon tank with a gentle filter and heater is ideal. Bettas are tropical fish requiring water temperatures between 76-82°F (24-28°C).</p>

<h3>Water Quality</h3>
<p>Maintaining excellent water quality is crucial. Regular water changes (25% weekly), proper filtration, and monitoring pH levels (6.5-7.5) ensure a healthy environment. Use a water conditioner to remove chlorine and chloramines.</p>

<h3>Diet and Feeding</h3>
<p>Bettas are carnivorous and require a protein-rich diet. High-quality betta pellets should form the staple diet, supplemented with frozen or live foods like bloodworms, brine shrimp, and daphnia 2-3 times per week.</p>

<h3>Tank Mates and Environment</h3>
<p>While male bettas are territorial, they can coexist with peaceful tank mates like neon tetras, corydoras, and ghost shrimp in larger tanks. Provide hiding spots with plants and decorations to reduce stress.</p>

<p>With proper care, bettas can live 3-5 years and develop strong bonds with their owners, often recognizing faces and responding to their presence.</p>',
                'excerpt' => 'Learn everything you need to know about caring for betta fish, from tank setup to feeding, ensuring your betta lives a long and healthy life.',
                'category' => 'Fish Care',
                'tags' => ['betta', 'fish care', 'beginners', 'tropical fish'],
                'featured_image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c787?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-12 14:30:00',
                'meta' => [
                    'reading_time' => '6 minutes',
                    'difficulty_level' => 'beginner',
                    'fish_species' => 'Betta splendens'
                ]
            ],
            [
                'title' => 'Marine Aquarium Setup: Your Gateway to Ocean Life',
                'content' => '<p>Setting up a marine aquarium opens the door to keeping some of the most colorful and exotic creatures on Earth. While more challenging than freshwater systems, the rewards of a thriving saltwater aquarium are immeasurable.</p>

<h3>Planning Your Marine System</h3>
<p>Success begins with planning. Consider your experience level, budget, and space. Fish-only systems are more forgiving for beginners, while reef tanks require advanced knowledge but offer stunning coral displays.</p>

<h3>Essential Equipment</h3>
<p>Marine aquariums require specialized equipment: protein skimmers for waste removal, powerheads for water circulation, quality lighting for corals, and reliable heating/cooling systems. Invest in quality equipment—it pays off long-term.</p>

<h3>The Nitrogen Cycle</h3>
<p>Understanding and establishing the nitrogen cycle is crucial. This biological process converts toxic ammonia to less harmful nitrates. Cycling typically takes 4-8 weeks and requires patience—never rush this critical phase.</p>

<h3>Water Parameters</h3>
<p>Marine organisms are sensitive to water conditions. Maintain salinity at 1.025 specific gravity, temperature between 74-78°F, and stable pH around 8.1-8.4. Regular testing and gradual adjustments are key.</p>

<h3>Livestock Selection</h3>
<p>Start with hardy species like clownfish, damsels, or gobies. Research compatibility, adult sizes, and dietary requirements. Quarantine new additions to prevent disease introduction.</p>

<p>Marine aquariums require dedication, but the beauty and diversity of ocean life in your home make every effort worthwhile.</p>',
                'excerpt' => 'Explore the fascinating world of marine aquariums and learn how to successfully set up and maintain a thriving saltwater ecosystem.',
                'category' => 'Marine',
                'tags' => ['marine', 'saltwater', 'setup', 'corals', 'fish'],
                'featured_image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-10 09:15:00',
                'meta' => [
                    'reading_time' => '12 minutes',
                    'difficulty_level' => 'advanced',
                    'system_type' => 'marine'
                ]
            ],
            [
                'title' => 'Planted Tank Fertilization: Nurturing Aquatic Gardens',
                'content' => '<p>Thriving aquatic plants are the cornerstone of beautiful planted tanks. Understanding proper fertilization ensures lush, healthy growth while maintaining water quality for fish and invertebrates.</p>

<h3>Understanding Plant Nutrition</h3>
<p>Aquatic plants require macronutrients (nitrogen, phosphorus, potassium) and micronutrients (iron, manganese, zinc). Each plays a vital role in plant health, color, and growth rate.</p>

<h3>Substrate Fertilization</h3>
<p>Nutrient-rich substrates provide long-term feeding for root-feeding plants. Products like ADA Aqua Soil or DIY root tabs supply essential nutrients directly to plant roots.</p>

<h3>Liquid Fertilizers</h3>
<p>Column feeders like stem plants benefit from liquid fertilizers added to the water column. Dose according to plant mass and lighting intensity—more light requires more nutrients.</p>

<h3>CO2 Supplementation</h3>
<p>Carbon dioxide is often the limiting factor in plant growth. Pressurized CO2 systems provide consistent levels, while liquid carbon offers a simpler alternative for low-tech tanks.</p>

<h3>Balancing the Ecosystem</h3>
<p>Proper fertilization creates balance between plant growth and algae prevention. Monitor plant health, adjust dosing gradually, and maintain consistent water change schedules.</p>

<p>Remember: healthy plants outcompete algae for nutrients, creating a natural, balanced ecosystem that benefits all tank inhabitants.</p>',
                'excerpt' => 'Master the art of planted tank fertilization to create lush aquatic gardens that thrive while maintaining perfect water quality.',
                'category' => 'Plants',
                'tags' => ['plants', 'fertilization', 'nutrients', 'co2', 'aquascaping'],
                'featured_image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-08 16:45:00',
                'meta' => [
                    'reading_time' => '10 minutes',
                    'difficulty_level' => 'intermediate',
                    'tank_type' => 'planted'
                ]
            ],
            [
                'title' => 'Rare Fish Species: Collecting Nature\'s Living Jewels',
                'content' => '<p>The aquarium hobby offers opportunities to work with some of the rarest and most beautiful fish species on Earth. These living jewels require specialized care but reward dedicated aquarists with unparalleled beauty and behavior.</p>

<h3>Understanding Rarity</h3>
<p>Fish rarity stems from various factors: limited natural distribution, challenging breeding requirements, or recent scientific discovery. Some species like the Peppermint Angelfish command premium prices due to collection difficulty.</p>

<h3>Ethical Considerations</h3>
<p>Responsible collecting supports conservation efforts. Many rare species are now captive-bred, reducing pressure on wild populations while making them more accessible to hobbyists.</p>

<h3>Specialized Care Requirements</h3>
<p>Rare species often have specific needs: precise water parameters, specialized diets, or unique social structures. Research thoroughly before acquiring any rare fish.</p>

<h3>Notable Rare Species</h3>
<ul>
<li><strong>Clarion Angelfish</strong> - Endemic to Mexico\'s Revillagigedo Islands</li>
<li><strong>Candy Basslet</strong> - Deep-water Caribbean species</li>
<li><strong>Bladefin Basslet</strong> - Stunning purple coloration</li>
<li><strong>Narcosis Angelfish</strong> - Recently discovered dwarf angelfish</li>
</ul>

<h3>Investment and Conservation</h3>
<p>Rare fish represent both financial investments and conservation opportunities. Successful breeding programs help preserve species while making them available to future generations of aquarists.</p>

<p>Working with rare species connects us directly to conservation efforts, making every aquarist a stakeholder in protecting ocean biodiversity.</p>',
                'excerpt' => 'Discover the world of rare fish species and learn about the specialized care, ethical considerations, and conservation impact of keeping nature\'s living jewels.',
                'category' => 'Rare Species',
                'tags' => ['rare fish', 'collection', 'conservation', 'breeding', 'marine'],
                'featured_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-05 11:20:00',
                'meta' => [
                    'reading_time' => '9 minutes',
                    'difficulty_level' => 'expert',
                    'price_range' => 'premium'
                ]
            ]
        ];
    }

    /**
     * Get demo pages data
     */
    public static function get_pages_data()
    {
        return [
            [
                'title' => 'Home',
                'content' => '<div class="hero-section">
                    <h1>Bringing Elegance to Aquatic Life – Globally</h1>
                    <p class="lead">Discover the world\'s finest aquatic species, premium equipment, and expert services for creating extraordinary underwater environments.</p>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="number">50,000+</span>
                            <span class="label">Happy Customers</span>
                        </div>
                        <div class="stat">
                            <span class="number">2,500+</span>
                            <span class="label">Species Available</span>
                        </div>
                        <div class="stat">
                            <span class="number">25+</span>
                            <span class="label">Years Experience</span>
                        </div>
                    </div>
                </div>

                <div class="featured-categories">
                    <h2>Explore Our Collections</h2>
                    <div class="category-grid">
                        <div class="category-card">
                            <h3>Rare Marine Fish</h3>
                            <p>Exceptional species from pristine coral reefs worldwide</p>
                        </div>
                        <div class="category-card">
                            <h3>Aquatic Plants</h3>
                            <p>Stunning flora for every aquascaping vision</p>
                        </div>
                        <div class="category-card">
                            <h3>Premium Equipment</h3>
                            <p>Professional-grade systems for optimal performance</p>
                        </div>
                        <div class="category-card">
                            <h3>Expert Services</h3>
                            <p>Custom design, installation, and maintenance</p>
                        </div>
                    </div>
                </div>',
                'slug' => 'home',
                'template' => 'front-page.php',
                'is_homepage' => true,
                'featured_image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'meta' => [
                    'hero_video_url' => 'https://player.vimeo.com/video/example',
                    'featured_products' => 'true',
                    'show_testimonials' => 'true',
                    'newsletter_signup' => 'true'
                ]
            ],
            [
                'title' => 'About Us',
                'content' => '<div class="about-intro">
                    <h2>Our Story</h2>
                    <p class="lead">Founded in 1999, AquaLuxe began as a small specialty shop with a big vision: to bring the wonder and beauty of aquatic life to enthusiasts worldwide while promoting sustainable practices and conservation.</p>
                </div>

                <div class="mission-vision">
                    <div class="mission">
                        <h3>Our Mission</h3>
                        <p>To provide the finest aquatic products and services while educating and inspiring the next generation of aquatic enthusiasts, always with respect for marine ecosystems and environmental responsibility.</p>
                    </div>
                    
                    <div class="vision">
                        <h3>Our Vision</h3>
                        <p>A world where every aquarium tells a story of conservation, where hobbyists become ambassadors for ocean health, and where technology and nature work in perfect harmony.</p>
                    </div>
                </div>

                <div class="values">
                    <h3>Our Values</h3>
                    <ul>
                        <li><strong>Sustainability:</strong> Supporting captive breeding and responsible collection practices</li>
                        <li><strong>Education:</strong> Sharing knowledge to promote successful aquarium keeping</li>
                        <li><strong>Innovation:</strong> Embracing new technologies for better animal welfare</li>
                        <li><strong>Community:</strong> Building connections among aquatic enthusiasts globally</li>
                        <li><strong>Excellence:</strong> Maintaining the highest standards in products and services</li>
                    </ul>
                </div>

                <div class="team-intro">
                    <h3>Meet Our Team</h3>
                    <p>Our passionate team of marine biologists, aquaculture specialists, and aquascaping artists work together to ensure every customer receives expert guidance and support.</p>
                </div>',
                'slug' => 'about',
                'featured_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'show_team_members' => 'true',
                    'show_timeline' => 'true',
                    'certifications' => 'OATA,MAC,CORAL'
                ]
            ],
            [
                'title' => 'Contact Us',
                'content' => '<div class="contact-intro">
                    <h2>Get in Touch</h2>
                    <p class="lead">We\'re here to help you create and maintain extraordinary aquatic environments. Reach out for consultations, custom orders, or expert advice.</p>
                </div>

                <div class="contact-methods">
                    <div class="contact-info">
                        <h3>Visit Our Facility</h3>
                        <address>
                            <strong>AquaLuxe Headquarters</strong><br>
                            123 Ocean Boulevard<br>
                            Marine District, Aquatica 12345<br>
                            United States
                        </address>
                        
                        <div class="contact-details">
                            <p><strong>Phone:</strong> +1 (555) 123-AQUA</p>
                            <p><strong>Email:</strong> info@aqualuxe.com</p>
                            <p><strong>Hours:</strong> Mon-Sat 9AM-7PM, Sun 11AM-5PM</p>
                        </div>
                    </div>
                    
                    <div class="departments">
                        <h3>Specialized Departments</h3>
                        <ul>
                            <li><strong>Rare Species:</strong> rare@aqualuxe.com</li>
                            <li><strong>Custom Design:</strong> design@aqualuxe.com</li>
                            <li><strong>Maintenance:</strong> service@aqualuxe.com</li>
                            <li><strong>Wholesale:</strong> wholesale@aqualuxe.com</li>
                            <li><strong>International:</strong> global@aqualuxe.com</li>
                        </ul>
                    </div>
                </div>

                <div class="emergency-contact">
                    <h4>Emergency Support</h4>
                    <p>For urgent aquarium emergencies: <strong>+1 (555) 911-HELP</strong><br>
                    <em>Available 24/7 for existing maintenance clients</em></p>
                </div>',
                'slug' => 'contact',
                'featured_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'show_contact_form' => 'true',
                    'show_map' => 'true',
                    'map_location' => '40.7128,-74.0060',
                    'show_business_hours' => 'true'
                ]
            ],
            [
                'title' => 'Privacy Policy',
                'content' => '<h2>Privacy Policy</h2>
                <p><em>Last updated: January 1, 2024</em></p>

                <h3>Information We Collect</h3>
                <p>AquaLuxe ("we," "our," or "us") collects information you provide directly to us, such as when you:</p>
                <ul>
                    <li>Create an account or make a purchase</li>
                    <li>Subscribe to our newsletter or educational content</li>
                    <li>Contact us for support or consultation</li>
                    <li>Participate in surveys or promotions</li>
                </ul>

                <h3>How We Use Your Information</h3>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Process transactions and provide customer service</li>
                    <li>Send educational content and care guides</li>
                    <li>Improve our products and services</li>
                    <li>Comply with legal obligations</li>
                </ul>

                <h3>Information Sharing</h3>
                <p>We do not sell, trade, or rent your personal information to third parties. We may share information only in these circumstances:</p>
                <ul>
                    <li>With your explicit consent</li>
                    <li>To comply with legal requirements</li>
                    <li>With trusted service providers who assist our operations</li>
                </ul>

                <h3>Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                <h3>Your Rights</h3>
                <p>You have the right to access, update, or delete your personal information. Contact us at privacy@aqualuxe.com for any privacy-related requests.</p>

                <h3>Contact Us</h3>
                <p>If you have questions about this Privacy Policy, please contact us at privacy@aqualuxe.com or at our physical address listed on our Contact page.</p>',
                'slug' => 'privacy-policy',
                'meta' => [
                    'legal_document' => 'true',
                    'last_updated' => '2024-01-01'
                ]
            ]
        ];
    }

    /**
     * Get demo products data (WooCommerce)
     */
    public static function get_products_data()
    {
        return [
            [
                'name' => 'Mandarin Fish (Synchiropus splendidus)',
                'description' => '<p>The Mandarin Fish, also known as the Mandarin Dragonet, is one of the most stunning and sought-after marine fish in the aquarium trade. Native to the Pacific Ocean, these fish are known for their incredible psychedelic coloration and peaceful temperament.</p>

                <h3>Key Features:</h3>
                <ul>
                    <li>Vibrant blue, orange, and green coloration</li>
                    <li>Peaceful temperament, reef-safe</li>
                    <li>Maximum size: 3 inches</li>
                    <li>Captive-bred specimens available</li>
                </ul>

                <h3>Care Requirements:</h3>
                <ul>
                    <li>Minimum tank size: 30 gallons</li>
                    <li>Temperature: 72-78°F</li>
                    <li>pH: 8.1-8.4</li>
                    <li>Specialized diet required</li>
                </ul>

                <p><strong>Note:</strong> Mandarin fish require established tanks with abundant microfauna. We provide detailed care guides with every purchase.</p>',
                'short_description' => 'Stunning psychedelic coloration makes this peaceful marine fish a true centerpiece for established reef aquariums.',
                'price' => '299.99',
                'sale_price' => '249.99',
                'sku' => 'FISH-MAND-001',
                'categories' => ['Marine Fish', 'Rare Species', 'Reef Safe'],
                'tags' => ['mandarin', 'dragonet', 'colorful', 'peaceful', 'reef-safe'],
                'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                ],
                'attributes' => [
                    'size' => 'Small (2-3 inches)',
                    'care_level' => 'Expert',
                    'temperament' => 'Peaceful',
                    'origin' => 'Pacific Ocean'
                ],
                'meta' => [
                    'care_guide_included' => 'true',
                    'quarantine_period' => '14 days',
                    'feeding_guide' => 'specialized',
                    'compatibility_chart' => 'included'
                ]
            ],
            [
                'name' => 'Anubias Barteri var. Nana - Premium Grade',
                'description' => '<p>Anubias Barteri var. Nana is one of the most popular and versatile aquatic plants in the hobby. This hardy West African species thrives in a wide range of conditions, making it perfect for beginners and experts alike.</p>

                <h3>Plant Characteristics:</h3>
                <ul>
                    <li>Slow-growing, extremely hardy</li>
                    <li>Dark green, waxy leaves</li>
                    <li>Rhizome-based growth pattern</li>
                    <li>Low light requirements</li>
                </ul>

                <h3>Growing Requirements:</h3>
                <ul>
                    <li>Light: Low to moderate</li>
                    <li>CO2: Not required (beneficial)</li>
                    <li>pH: 6.0-7.5</li>
                    <li>Temperature: 72-82°F</li>
                </ul>

                <h3>Placement Tips:</h3>
                <ul>
                    <li>Attach to driftwood or rocks</li>
                    <li>Do not bury the rhizome</li>
                    <li>Perfect for foreground and midground</li>
                    <li>Compatible with most fish species</li>
                </ul>

                <p>Our premium grade Anubias feature mature, well-established rhizomes with 8-12 healthy leaves per portion.</p>',
                'short_description' => 'Hardy, low-maintenance aquatic plant perfect for any aquarium setup. Premium grade with mature rhizomes.',
                'price' => '24.99',
                'sku' => 'PLANT-ANUB-001',
                'categories' => ['Aquatic Plants', 'Low Light Plants', 'Beginner Friendly'],
                'tags' => ['anubias', 'hardy', 'low-light', 'rhizome', 'beginner'],
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'attributes' => [
                    'size' => 'Medium (6-8 inches)',
                    'light_requirement' => 'Low',
                    'growth_rate' => 'Slow',
                    'placement' => 'Foreground/Midground'
                ],
                'meta' => [
                    'care_difficulty' => 'easy',
                    'co2_required' => 'false',
                    'portion_size' => '1 rhizome with 8-12 leaves'
                ]
            ],
            [
                'name' => 'AquaLuxe Pro Series LED System - 48"',
                'description' => '<p>The AquaLuxe Pro Series represents the pinnacle of aquarium lighting technology. Designed in collaboration with leading aquaculture researchers, this system delivers optimal spectrum distribution for both fish display and plant/coral growth.</p>

                <h3>Advanced Features:</h3>
                <ul>
                    <li>Full spectrum LEDs (380-780nm)</li>
                    <li>Wireless smartphone control</li>
                    <li>Programmable sunrise/sunset simulation</li>
                    <li>Storm and lunar cycle effects</li>
                    <li>Energy efficient - 150W power consumption</li>
                </ul>

                <h3>Technical Specifications:</h3>
                <ul>
                    <li>Dimensions: 48" x 8" x 2"</li>
                    <li>Suitable for tanks: 48-54 inches</li>
                    <li>PAR output: 200-300 μmol at 18" depth</li>
                    <li>Color temperature: 6500K-20000K adjustable</li>
                    <li>Lifespan: 50,000+ hours</li>
                </ul>

                <h3>Included Components:</h3>
                <ul>
                    <li>LED fixture with mounting legs</li>
                    <li>External power supply</li>
                    <li>Wireless controller</li>
                    <li>Smartphone app access</li>
                    <li>Pre-programmed light recipes</li>
                </ul>

                <p>Backed by our 3-year warranty and expert technical support team.</p>',
                'short_description' => 'Professional-grade LED lighting system with smartphone control and advanced spectrum customization for optimal aquatic growth.',
                'price' => '799.99',
                'sale_price' => '699.99',
                'sku' => 'EQUIP-LED-48-PRO',
                'categories' => ['Equipment', 'Lighting', 'Professional Grade'],
                'tags' => ['led', 'lighting', 'pro-series', 'programmable', 'wireless'],
                'image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'attributes' => [
                    'length' => '48 inches',
                    'power' => '150W',
                    'control' => 'Wireless',
                    'warranty' => '3 years'
                ],
                'meta' => [
                    'free_shipping' => 'true',
                    'installation_available' => 'true',
                    'par_chart_included' => 'true'
                ]
            ]
        ];
    }

    /**
     * Get demo services data
     */
    public static function get_services_data()
    {
        return [
            [
                'title' => 'Custom Aquarium Design & Installation',
                'content' => '<p>Transform your space with a custom aquarium designed specifically for your environment, preferences, and lifestyle. Our team of expert designers and marine biologists work together to create stunning aquatic displays that serve as living art pieces.</p>

                <h3>What\'s Included:</h3>
                <ul>
                    <li>Comprehensive site survey and consultation</li>
                    <li>3D rendering and design visualization</li>
                    <li>Custom tank fabrication (if needed)</li>
                    <li>Professional equipment selection and installation</li>
                    <li>Initial biological setup and cycling</li>
                    <li>Livestock introduction and acclimatization</li>
                    <li>Comprehensive care guide and training</li>
                </ul>

                <h3>Popular Applications:</h3>
                <ul>
                    <li>Corporate lobbies and reception areas</li>
                    <li>Residential living spaces and bedrooms</li>
                    <li>Restaurants and hospitality venues</li>
                    <li>Medical and dental offices</li>
                    <li>Educational institutions</li>
                </ul>

                <p>Every project includes a 6-month warranty on equipment and unlimited consultation during the establishment period.</p>',
                'excerpt' => 'Complete custom aquarium solutions from concept to installation, creating stunning aquatic displays tailored to your space.',
                'price' => 'Starting at $3,500',
                'duration' => '2-6 weeks',
                'categories' => ['Design Services', 'Installation'],
                'featured_image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'booking_required' => 'true',
                    'consultation_fee' => '$150 (credited toward project)',
                    'project_timeline' => '2-6 weeks',
                    'warranty_period' => '6 months',
                    'maintenance_available' => 'true'
                ]
            ],
            [
                'title' => 'Professional Aquarium Maintenance',
                'content' => '<p>Keep your aquarium in pristine condition with our professional maintenance services. Our certified aquarists provide comprehensive care to ensure optimal water quality, fish health, and visual appeal.</p>

                <h3>Service Options:</h3>
                <ul>
                    <li><strong>Weekly Service:</strong> Complete water changes, testing, and equipment maintenance</li>
                    <li><strong>Bi-weekly Service:</strong> Essential maintenance for established systems</li>
                    <li><strong>Monthly Service:</strong> Basic care for low-maintenance setups</li>
                    <li><strong>Emergency Service:</strong> 24/7 support for urgent issues</li>
                </ul>

                <h3>Standard Maintenance Includes:</h3>
                <ul>
                    <li>Water quality testing and adjustment</li>
                    <li>Partial water changes with premium salt/conditioners</li>
                    <li>Filter media cleaning and replacement</li>
                    <li>Algae removal and glass cleaning</li>
                    <li>Equipment inspection and calibration</li>
                    <li>Fish health assessment</li>
                    <li>Feeding schedule optimization</li>
                    <li>Detailed service reports</li>
                </ul>

                <p>All maintenance packages include free emergency support and priority scheduling for additional services.</p>',
                'excerpt' => 'Expert aquarium maintenance services to keep your aquatic environment healthy, beautiful, and thriving year-round.',
                'price' => 'Starting at $85/visit',
                'duration' => '1-2 hours per visit',
                'categories' => ['Maintenance', 'Ongoing Care'],
                'featured_image' => 'https://images.unsplash.com/photo-1520637736862-4d197d17c787?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'service_areas' => 'Within 50 miles of facility',
                    'emergency_support' => '24/7 for maintenance clients',
                    'contract_options' => 'monthly, quarterly, annual',
                    'discount_annual' => '15%'
                ]
            ]
        ];
    }

    /**
     * Get demo events data
     */
    public static function get_events_data()
    {
        return [
            [
                'title' => 'Advanced Aquascaping Workshop with Takashi Yamamoto',
                'content' => '<p>Join world-renowned aquascaper Takashi Yamamoto for an intensive hands-on workshop exploring advanced aquascaping techniques. This exclusive masterclass covers composition theory, plant selection, and the artistry behind creating underwater landscapes.</p>

                <h3>Workshop Highlights:</h3>
                <ul>
                    <li>Live demonstration of professional aquascaping techniques</li>
                    <li>Hands-on creation of your own 20-gallon aquascape</li>
                    <li>Plant selection and placement strategies</li>
                    <li>Hardscape composition principles</li>
                    <li>Long-term maintenance planning</li>
                    <li>Q&A session with the master</li>
                </ul>

                <h3>What\'s Included:</h3>
                <ul>
                    <li>All materials for your personal aquascape project</li>
                    <li>Premium plants and hardscape materials</li>
                    <li>Professional tools and equipment</li>
                    <li>Comprehensive workshop manual</li>
                    <li>Light refreshments and lunch</li>
                    <li>Take-home care guide</li>
                </ul>

                <p><strong>Limited to 12 participants</strong> to ensure personalized attention and instruction.</p>',
                'excerpt' => 'Master the art of aquascaping with world-renowned expert Takashi Yamamoto in this exclusive hands-on workshop.',
                'date' => '2024-03-15',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'location' => 'AquaLuxe Education Center',
                'price' => '$450 per person',
                'capacity' => 12,
                'categories' => ['Workshops', 'Education', 'Aquascaping'],
                'featured_image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'instructor' => 'Takashi Yamamoto',
                    'skill_level' => 'Intermediate to Advanced',
                    'materials_included' => 'true',
                    'take_home_project' => 'true',
                    'prerequisite' => 'Basic aquarium experience recommended'
                ]
            ],
            [
                'title' => 'Marine Fish Breeding Seminar',
                'content' => '<p>Discover the fascinating world of marine fish breeding with leading experts in aquaculture science. This comprehensive seminar covers both natural breeding behaviors and cutting-edge captive breeding techniques.</p>

                <h3>Topics Covered:</h3>
                <ul>
                    <li>Marine fish reproductive biology</li>
                    <li>Breeding system setup and design</li>
                    <li>Broodstock selection and conditioning</li>
                    <li>Larval rearing techniques</li>
                    <li>Live food cultures and nutrition</li>
                    <li>Common challenges and solutions</li>
                </ul>

                <h3>Featured Species:</h3>
                <ul>
                    <li>Clownfish breeding success stories</li>
                    <li>Angelfish propagation techniques</li>
                    <li>Tang breeding breakthroughs</li>
                    <li>Goby and blenny reproduction</li>
                </ul>

                <p>Attendees receive exclusive access to our breeding resource library and ongoing support from our aquaculture team.</p>',
                'excerpt' => 'Learn cutting-edge marine fish breeding techniques from leading aquaculture experts in this comprehensive educational seminar.',
                'date' => '2024-04-20',
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'location' => 'AquaLuxe Research Facility',
                'price' => '$125 per person',
                'capacity' => 30,
                'categories' => ['Seminars', 'Breeding', 'Marine'],
                'featured_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'meta' => [
                    'ceu_credits' => '6 hours',
                    'certificate_provided' => 'true',
                    'facility_tour' => 'included',
                    'networking_lunch' => 'included'
                ]
            ]
        ];
    }

    /**
     * Get demo media data
     */
    public static function get_media_data()
    {
        return [
            [
                'url' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'title' => 'Tropical Marine Aquarium',
                'alt' => 'Beautiful tropical marine aquarium with colorful fish and coral',
                'description' => 'Stunning marine aquarium showcasing vibrant tropical fish and live coral reef'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1520637736862-4d197d17c787?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'title' => 'Aquascaped Planted Tank',
                'alt' => 'Professional aquascaped planted aquarium with lush greenery',
                'description' => 'Expertly designed planted aquarium featuring diverse aquatic plants and natural hardscape'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'title' => 'Mandarin Fish Close-up',
                'alt' => 'Close-up of colorful mandarin fish swimming near coral',
                'description' => 'Detailed view of a mandarin fish displaying its spectacular coloration'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'title' => 'Aquatic Plants Collection',
                'alt' => 'Variety of healthy aquatic plants in display tanks',
                'description' => 'Collection of premium aquatic plants ready for aquascaping projects'
            ]
        ];
    }

    /**
     * Get customizer settings data
     */
    public static function get_customizer_data()
    {
        return [
            'site_logo' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80',
            'primary_color' => '#0ea5e9',
            'secondary_color' => '#14b8a6',
            'accent_color' => '#eab308',
            'header_layout' => 'center',
            'enable_dark_mode' => true,
            'show_search_in_header' => true,
            'newsletter_signup_text' => 'Stay updated with the latest aquatic trends and exclusive offers',
            'footer_text' => 'Bringing elegance to aquatic life – globally',
            'social_facebook' => 'https://facebook.com/aqualuxe',
            'social_instagram' => 'https://instagram.com/aqualuxe',
            'social_youtube' => 'https://youtube.com/aqualuxe'
        ];
    }
}