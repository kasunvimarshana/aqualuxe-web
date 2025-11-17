<?php
namespace Aqualuxe\Modules\Forms;

class Export_Quote_Form
{
    public static function render(): string
    {
        $action = esc_url(admin_url('admin-post.php'));
        $submitted = isset($_GET['submitted']);
        $html = '';
        if ($submitted) {
            $html .= '<div class="notice notice-success"><p>' . esc_html__('Thank you. Your export quote request was submitted.', 'aqualuxe') . '</p></div>';
        }
        $html .= '<form method="post" action="' . $action . '" class="aqlx-form aqlx-form--export">';
        $html .= wp_nonce_field('aqualuxe_export', '_aqlx', true, false);
        $html .= '<input type="hidden" name="action" value="aqualuxe_export_submit" />';
        $html .= '<div><label>' . esc_html__('Company/Individual', 'aqualuxe') . ' *<input type="text" name="name" required></label></div>';
        $html .= '<div><label>' . esc_html__('Email', 'aqualuxe') . ' *<input type="email" name="email" required></label></div>';
        $html .= '<div><label>' . esc_html__('Phone/WhatsApp', 'aqualuxe') . '<input type="tel" name="phone"></label></div>';
        $html .= '<div><label>' . esc_html__('Destination Country', 'aqualuxe') . ' *<input type="text" name="country" required></label></div>';
        $html .= '<div><label>' . esc_html__('City / Airport', 'aqualuxe') . '<input type="text" name="airport"></label></div>';
        $html .= '<div><label>' . esc_html__('Preferred Incoterm', 'aqualuxe') . '<input type="text" name="incoterm" placeholder="EXW / FOB / CIF"></label></div>';
        $html .= '<div><label>' . esc_html__('Desired Date', 'aqualuxe') . '<input type="date" name="date"></label></div>';
        $html .= '<div><label>' . esc_html__('Items of Interest', 'aqualuxe') . '<textarea name="items" rows="5" placeholder="Species/equipment list, quantities, sizes..."></textarea></label></div>';
        $html .= '<div><label>' . esc_html__('Message', 'aqualuxe') . '<textarea name="message" rows="5"></textarea></label></div>';
        $html .= '<button type="submit">' . esc_html__('Request Export Quote', 'aqualuxe') . '</button>';
        $html .= '</form>';
        return $html;
    }
}
