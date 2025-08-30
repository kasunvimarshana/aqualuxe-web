<?php
/**
 * Size Guide Modal
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product || !$product->is_type('variable')) {
    return;
}

// Get module
$module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];

// Check if size guide is enabled
if (!$module->get_option('size_guide', true)) {
    return;
}

// Get product attributes
$attributes = $product->get_variation_attributes();

// Check if product has size attribute
$has_size_attribute = false;
$size_attribute_name = '';
$size_attribute_key = '';
$size_options = [];

foreach ($attributes as $attribute_name => $options) {
    $taxonomy = str_replace('pa_', '', $attribute_name);
    
    if (in_array($taxonomy, ['size', 'sizes', 'shoe-size', 'clothing-size'])) {
        $has_size_attribute = true;
        $size_attribute_name = wc_attribute_label($attribute_name);
        $size_attribute_key = $attribute_name;
        $size_options = $options;
        break;
    }
}

// Only show size guide modal if product has size attribute
if (!$has_size_attribute) {
    return;
}

// Get product categories
$product_categories = get_the_terms($product->get_id(), 'product_cat');
$category_names = [];

if ($product_categories && !is_wp_error($product_categories)) {
    foreach ($product_categories as $category) {
        $category_names[] = $category->name;
    }
}

// Determine product type for size guide
$product_type = '';

if (in_array('Men', $category_names) || in_array('Mens', $category_names)) {
    $product_type = 'men';
} elseif (in_array('Women', $category_names) || in_array('Womens', $category_names)) {
    $product_type = 'women';
} elseif (in_array('Kids', $category_names) || in_array('Children', $category_names)) {
    $product_type = 'kids';
}
?>

<div id="size-guide-modal" class="size-guide-modal" x-data="{ isOpen: false }" x-show="isOpen" x-cloak @click.away="isOpen = false">
    <div class="size-guide-modal-container">
        <button class="size-guide-modal-close" @click="isOpen = false">
            <span class="screen-reader-text"><?php esc_html_e('Close size guide', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
        </button>
        
        <div class="size-guide-modal-content">
            <h2 class="size-guide-title"><?php esc_html_e('Size Guide', 'aqualuxe'); ?></h2>
            
            <div class="size-guide-tabs" x-data="{ activeTab: 'tab-1' }">
                <div class="size-guide-tabs-nav">
                    <button class="size-guide-tab-button" :class="{ 'active': activeTab === 'tab-1' }" @click="activeTab = 'tab-1'">
                        <?php esc_html_e('Size Chart', 'aqualuxe'); ?>
                    </button>
                    <button class="size-guide-tab-button" :class="{ 'active': activeTab === 'tab-2' }" @click="activeTab = 'tab-2'">
                        <?php esc_html_e('How to Measure', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <div class="size-guide-tabs-content">
                    <div class="size-guide-tab-panel" x-show="activeTab === 'tab-1'">
                        <?php if ($product_type === 'men') : ?>
                            <h3><?php esc_html_e('Men\'s Size Chart', 'aqualuxe'); ?></h3>
                            <table class="size-guide-table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html($size_attribute_name); ?></th>
                                        <th><?php esc_html_e('US', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('EU', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('UK', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Chest (inches)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Waist (inches)', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>34</td>
                                        <td>44</td>
                                        <td>34</td>
                                        <td>34-36</td>
                                        <td>28-30</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>36</td>
                                        <td>46</td>
                                        <td>36</td>
                                        <td>36-38</td>
                                        <td>30-32</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>38-40</td>
                                        <td>48-50</td>
                                        <td>38-40</td>
                                        <td>38-40</td>
                                        <td>32-34</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>42-44</td>
                                        <td>52-54</td>
                                        <td>42-44</td>
                                        <td>42-44</td>
                                        <td>36-38</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>46</td>
                                        <td>56</td>
                                        <td>46</td>
                                        <td>46-48</td>
                                        <td>40-42</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>48</td>
                                        <td>58</td>
                                        <td>48</td>
                                        <td>48-50</td>
                                        <td>44-46</td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php elseif ($product_type === 'women') : ?>
                            <h3><?php esc_html_e('Women\'s Size Chart', 'aqualuxe'); ?></h3>
                            <table class="size-guide-table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html($size_attribute_name); ?></th>
                                        <th><?php esc_html_e('US', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('EU', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('UK', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Bust (inches)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Waist (inches)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Hips (inches)', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>0-2</td>
                                        <td>32-34</td>
                                        <td>4-6</td>
                                        <td>31-32</td>
                                        <td>24-25</td>
                                        <td>34-35</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>4-6</td>
                                        <td>36-38</td>
                                        <td>8-10</td>
                                        <td>33-34</td>
                                        <td>26-27</td>
                                        <td>36-37</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>8-10</td>
                                        <td>40-42</td>
                                        <td>12-14</td>
                                        <td>35-36</td>
                                        <td>28-29</td>
                                        <td>38-39</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>12-14</td>
                                        <td>44-46</td>
                                        <td>16-18</td>
                                        <td>37-39</td>
                                        <td>30-32</td>
                                        <td>40-42</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>16-18</td>
                                        <td>48-50</td>
                                        <td>20-22</td>
                                        <td>40-42</td>
                                        <td>33-35</td>
                                        <td>43-45</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>20-22</td>
                                        <td>52-54</td>
                                        <td>24-26</td>
                                        <td>43-45</td>
                                        <td>36-38</td>
                                        <td>46-48</td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php elseif ($product_type === 'kids') : ?>
                            <h3><?php esc_html_e('Kids\' Size Chart', 'aqualuxe'); ?></h3>
                            <table class="size-guide-table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html($size_attribute_name); ?></th>
                                        <th><?php esc_html_e('Age', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Height (inches)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Weight (lbs)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Chest (inches)', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Waist (inches)', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2T</td>
                                        <td>2</td>
                                        <td>33-36</td>
                                        <td>30-32</td>
                                        <td>21</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td>3T</td>
                                        <td>3</td>
                                        <td>36-39</td>
                                        <td>32-35</td>
                                        <td>22</td>
                                        <td>20.5</td>
                                    </tr>
                                    <tr>
                                        <td>4T</td>
                                        <td>4</td>
                                        <td>39-42</td>
                                        <td>35-41</td>
                                        <td>23</td>
                                        <td>21</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>5</td>
                                        <td>42-45</td>
                                        <td>41-46</td>
                                        <td>24</td>
                                        <td>21.5</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>6</td>
                                        <td>45-48</td>
                                        <td>46-52</td>
                                        <td>25</td>
                                        <td>22</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>7</td>
                                        <td>48-51</td>
                                        <td>52-58</td>
                                        <td>26</td>
                                        <td>22.5</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>8</td>
                                        <td>51-54</td>
                                        <td>58-66</td>
                                        <td>27</td>
                                        <td>23</td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <h3><?php esc_html_e('General Size Chart', 'aqualuxe'); ?></h3>
                            <table class="size-guide-table">
                                <thead>
                                    <tr>
                                        <th><?php echo esc_html($size_attribute_name); ?></th>
                                        <th><?php esc_html_e('US', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('EU', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('UK', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XS</td>
                                        <td>0-2</td>
                                        <td>32-34</td>
                                        <td>4-6</td>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>4-6</td>
                                        <td>36-38</td>
                                        <td>8-10</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>8-10</td>
                                        <td>40-42</td>
                                        <td>12-14</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>12-14</td>
                                        <td>44-46</td>
                                        <td>16-18</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>16-18</td>
                                        <td>48-50</td>
                                        <td>20-22</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>20-22</td>
                                        <td>52-54</td>
                                        <td>24-26</td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        
                        <div class="size-guide-note">
                            <p><?php esc_html_e('Note: This size chart is for reference only. Actual sizes may vary by brand, style, and personal preference.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="size-guide-tab-panel" x-show="activeTab === 'tab-2'">
                        <h3><?php esc_html_e('How to Measure', 'aqualuxe'); ?></h3>
                        
                        <div class="measurement-instructions">
                            <?php if ($product_type === 'men' || $product_type === '') : ?>
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Chest', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around the fullest part of your chest, keeping the measuring tape horizontal.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Waist', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around your natural waistline, keeping the tape comfortably loose.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Inseam', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure from the crotch to the bottom of your leg.', 'aqualuxe'); ?></p>
                                </div>
                            <?php elseif ($product_type === 'women') : ?>
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Bust', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around the fullest part of your bust, keeping the measuring tape horizontal.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Waist', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around your natural waistline, keeping the tape comfortably loose.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Hips', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around the fullest part of your hips.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Inseam', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure from the crotch to the bottom of your leg.', 'aqualuxe'); ?></p>
                                </div>
                            <?php elseif ($product_type === 'kids') : ?>
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Height', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure from the top of the head to the bottom of the feet.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Chest', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around the fullest part of the chest.', 'aqualuxe'); ?></p>
                                </div>
                                
                                <div class="measurement-item">
                                    <h4><?php esc_html_e('Waist', 'aqualuxe'); ?></h4>
                                    <p><?php esc_html_e('Measure around the natural waistline.', 'aqualuxe'); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="measurement-tips">
                                <h4><?php esc_html_e('Tips for Accurate Measurements', 'aqualuxe'); ?></h4>
                                <ul>
                                    <li><?php esc_html_e('Use a fabric measuring tape for best results.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Keep the measuring tape snug but not tight.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Have someone help you for more accurate measurements.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Measure yourself in your underwear or lightweight clothing.', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Stand straight with your feet together when measuring.', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sizeGuide', () => ({
            isOpen: false,
            
            init() {
                // Listen for size guide button clicks
                document.addEventListener('click', (e) => {
                    if (e.target.closest('.size-guide-button')) {
                        e.preventDefault();
                        this.isOpen = true;
                    }
                });
            }
        }));
    });
</script>