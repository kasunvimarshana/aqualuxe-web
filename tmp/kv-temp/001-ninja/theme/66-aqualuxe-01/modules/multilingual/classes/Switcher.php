<?php
/**
 * Language Switcher Class
 *
 * @package AquaLuxe\Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

/**
 * Language Switcher Class
 */
class Switcher {
    /**
     * Languages
     *
     * @var array
     */
    private $languages;

    /**
     * Current language
     *
     * @var string
     */
    private $current_language;

    /**
     * Constructor
     *
     * @param array $languages
     * @param string $current_language
     */
    public function __construct($languages, $current_language) {
        $this->languages = $languages;
        $this->current_language = $current_language;
    }

    /**
     * Render language switcher
     *
     * @param string $style
     * @param bool $show_flags
     * @param bool $show_names
     */
    public function render($style = 'dropdown', $show_flags = true, $show_names = true) {
        switch ($style) {
            case 'list':
                $this->render_list($show_flags, $show_names);
                break;
            case 'buttons':
                $this->render_buttons($show_flags, $show_names);
                break;
            case 'menu':
                $this->render_menu($show_flags, $show_names);
                break;
            case 'dropdown':
            default:
                $this->render_dropdown($show_flags, $show_names);
                break;
        }
    }

    /**
     * Render dropdown
     *
     * @param bool $show_flags
     * @param bool $show_names
     */
    private function render_dropdown($show_flags, $show_names) {
        $current_language = $this->languages[$this->current_language] ?? null;

        if (!$current_language) {
            return;
        }

        ?>
        <div class="language-switcher language-switcher--dropdown">
            <div class="language-switcher__current">
                <?php if ($show_flags) : ?>
                    <img src="<?php echo esc_url($current_language->get_flag_url()); ?>" alt="<?php echo esc_attr($current_language->get_name()); ?>" class="language-switcher__flag" />
                <?php endif; ?>
                
                <?php if ($show_names) : ?>
                    <span class="language-switcher__name"><?php echo esc_html($current_language->get_name()); ?></span>
                <?php endif; ?>
                
                <span class="language-switcher__arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </span>
            </div>
            
            <ul class="language-switcher__dropdown">
                <?php foreach ($this->languages as $code => $language) : ?>
                    <li class="language-switcher__item <?php echo $code === $this->current_language ? 'language-switcher__item--active' : ''; ?>">
                        <a href="<?php echo esc_url($language->get_url()); ?>" class="language-switcher__link">
                            <?php if ($show_flags) : ?>
                                <img src="<?php echo esc_url($language->get_flag_url()); ?>" alt="<?php echo esc_attr($language->get_name()); ?>" class="language-switcher__flag" />
                            <?php endif; ?>
                            
                            <?php if ($show_names) : ?>
                                <span class="language-switcher__name"><?php echo esc_html($language->get_name()); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Render list
     *
     * @param bool $show_flags
     * @param bool $show_names
     */
    private function render_list($show_flags, $show_names) {
        ?>
        <ul class="language-switcher language-switcher--list">
            <?php foreach ($this->languages as $code => $language) : ?>
                <li class="language-switcher__item <?php echo $code === $this->current_language ? 'language-switcher__item--active' : ''; ?>">
                    <a href="<?php echo esc_url($language->get_url()); ?>" class="language-switcher__link">
                        <?php if ($show_flags) : ?>
                            <img src="<?php echo esc_url($language->get_flag_url()); ?>" alt="<?php echo esc_attr($language->get_name()); ?>" class="language-switcher__flag" />
                        <?php endif; ?>
                        
                        <?php if ($show_names) : ?>
                            <span class="language-switcher__name"><?php echo esc_html($language->get_name()); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

    /**
     * Render buttons
     *
     * @param bool $show_flags
     * @param bool $show_names
     */
    private function render_buttons($show_flags, $show_names) {
        ?>
        <div class="language-switcher language-switcher--buttons">
            <?php foreach ($this->languages as $code => $language) : ?>
                <a href="<?php echo esc_url($language->get_url()); ?>" class="language-switcher__button <?php echo $code === $this->current_language ? 'language-switcher__button--active' : ''; ?>">
                    <?php if ($show_flags) : ?>
                        <img src="<?php echo esc_url($language->get_flag_url()); ?>" alt="<?php echo esc_attr($language->get_name()); ?>" class="language-switcher__flag" />
                    <?php endif; ?>
                    
                    <?php if ($show_names) : ?>
                        <span class="language-switcher__name"><?php echo esc_html($language->get_name()); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render menu
     *
     * @param bool $show_flags
     * @param bool $show_names
     */
    private function render_menu($show_flags, $show_names) {
        $current_language = $this->languages[$this->current_language] ?? null;

        if (!$current_language) {
            return;
        }

        ?>
        <div class="language-switcher language-switcher--menu">
            <a href="#" class="language-switcher__toggle">
                <?php if ($show_flags) : ?>
                    <img src="<?php echo esc_url($current_language->get_flag_url()); ?>" alt="<?php echo esc_attr($current_language->get_name()); ?>" class="language-switcher__flag" />
                <?php endif; ?>
                
                <?php if ($show_names) : ?>
                    <span class="language-switcher__name"><?php echo esc_html($current_language->get_name()); ?></span>
                <?php endif; ?>
            </a>
            
            <ul class="sub-menu language-switcher__submenu">
                <?php foreach ($this->languages as $code => $language) : ?>
                    <?php if ($code === $this->current_language) continue; ?>
                    <li class="menu-item language-switcher__item">
                        <a href="<?php echo esc_url($language->get_url()); ?>" class="language-switcher__link">
                            <?php if ($show_flags) : ?>
                                <img src="<?php echo esc_url($language->get_flag_url()); ?>" alt="<?php echo esc_attr($language->get_name()); ?>" class="language-switcher__flag" />
                            <?php endif; ?>
                            
                            <?php if ($show_names) : ?>
                                <span class="language-switcher__name"><?php echo esc_html($language->get_name()); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}