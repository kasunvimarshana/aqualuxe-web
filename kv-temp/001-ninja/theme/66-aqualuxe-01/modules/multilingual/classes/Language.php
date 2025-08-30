<?php
/**
 * Language Class
 *
 * @package AquaLuxe\Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

/**
 * Language Class
 */
class Language {
    /**
     * Language code
     *
     * @var string
     */
    private $code;

    /**
     * Language name
     *
     * @var string
     */
    private $name;

    /**
     * Language flag
     *
     * @var string
     */
    private $flag;

    /**
     * Language locale
     *
     * @var string
     */
    private $locale;

    /**
     * Is RTL
     *
     * @var bool
     */
    private $rtl;

    /**
     * Constructor
     *
     * @param string $code
     * @param array $args
     */
    public function __construct($code, $args) {
        $this->code = $code;
        $this->name = $args['name'] ?? '';
        $this->flag = $args['flag'] ?? '';
        $this->locale = $args['locale'] ?? '';
        $this->rtl = $args['rtl'] ?? false;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function get_code() {
        return $this->code;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get flag
     *
     * @return string
     */
    public function get_flag() {
        return $this->flag;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function get_locale() {
        return $this->locale;
    }

    /**
     * Is RTL
     *
     * @return bool
     */
    public function is_rtl() {
        return $this->rtl;
    }

    /**
     * Get flag URL
     *
     * @return string
     */
    public function get_flag_url() {
        return AQUALUXE_URI . 'modules/multilingual/assets/flags/' . $this->flag . '.png';
    }

    /**
     * Get language URL
     *
     * @return string
     */
    public function get_url() {
        $url = add_query_arg('lang', $this->code);
        return $url;
    }
}