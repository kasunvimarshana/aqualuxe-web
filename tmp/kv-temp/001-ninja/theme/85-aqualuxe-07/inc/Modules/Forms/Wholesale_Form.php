<?php
namespace Aqualuxe\Modules\Forms;

class Wholesale_Form
{
    public static function render(): string
    {
        $action = esc_url(admin_url('admin-post.php'));
        $submitted = isset($_GET['submitted']);
        $html = '';
        if ($submitted) {
            $html .= '<div class="notice notice-success"><p>' . esc_html__('Thank you. Your wholesale application was submitted.', 'aqualuxe') . '</p></div>';
        }
        $html .= '<form method="post" action="' . $action . '" class="aqlx-form aqlx-form--wholesale">';
        $html .= wp_nonce_field('aqualuxe_wholesale', '_aqlx', true, false);
        $html .= '<input type="hidden" name="action" value="aqualuxe_wholesale_submit" />';
        $html .= '<div><label>' . esc_html__('Business Name', 'aqualuxe') . ' *<input type="text" name="business_name" required></label></div>';
        $html .= '<div><label>' . esc_html__('Contact Person', 'aqualuxe') . ' *<input type="text" name="contact_person" required></label></div>';
        $html .= '<div><label>' . esc_html__('Email', 'aqualuxe') . ' *<input type="email" name="email" required></label></div>';
        $html .= '<div><label>' . esc_html__('Phone', 'aqualuxe') . '<input type="tel" name="phone"></label></div>';
        $html .= '<div><label>' . esc_html__('Country', 'aqualuxe') . ' *<input type="text" name="country" required></label></div>';
        $html .= '<div><label>' . esc_html__('Website', 'aqualuxe') . '<input type="url" name="website"></label></div>';
        $html .= '<div><label>' . esc_html__('Business Type', 'aqualuxe') . '<select name="business_type"><option>Retail</option><option>Wholesale</option><option>Distributor</option></select></label></div>';
        $html .= '<div><label>' . esc_html__('Estimated Monthly Volume', 'aqualuxe') . '<input type="text" name="monthly_volume"></label></div>';
        $html .= '<div><label>' . esc_html__('Message', 'aqualuxe') . '<textarea name="message" rows="5"></textarea></label></div>';
        $html .= '<button type="submit">' . esc_html__('Apply for Wholesale', 'aqualuxe') . '</button>';
        $html .= '</form>';
        return $html;
    }
}
