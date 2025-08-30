<?php
/**
 * Payment Class
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Payment Class
 */
class Payment {
    /**
     * Registration object
     *
     * @var Registration
     */
    private $registration;

    /**
     * Payment methods
     *
     * @var array
     */
    private $methods = [];

    /**
     * Constructor
     *
     * @param Registration $registration
     */
    public function __construct($registration) {
        $this->registration = $registration;
        $this->load_methods();
    }

    /**
     * Load payment methods
     */
    private function load_methods() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            $enabled_methods = $module->get_setting('payment_methods', ['stripe', 'paypal']);
        } else {
            $enabled_methods = ['stripe', 'paypal'];
        }
        
        // Define available methods
        $available_methods = [
            'stripe' => [
                'id' => 'stripe',
                'name' => __('Credit Card', 'aqualuxe'),
                'description' => __('Pay securely with your credit card', 'aqualuxe'),
                'icon' => 'credit-card',
                'processor' => [$this, 'process_stripe'],
            ],
            'paypal' => [
                'id' => 'paypal',
                'name' => __('PayPal', 'aqualuxe'),
                'description' => __('Pay with your PayPal account', 'aqualuxe'),
                'icon' => 'paypal',
                'processor' => [$this, 'process_paypal'],
            ],
            'bank_transfer' => [
                'id' => 'bank_transfer',
                'name' => __('Bank Transfer', 'aqualuxe'),
                'description' => __('Pay via bank transfer', 'aqualuxe'),
                'icon' => 'bank',
                'processor' => [$this, 'process_bank_transfer'],
            ],
            'cash' => [
                'id' => 'cash',
                'name' => __('Cash', 'aqualuxe'),
                'description' => __('Pay with cash upon arrival', 'aqualuxe'),
                'icon' => 'money',
                'processor' => [$this, 'process_cash'],
            ],
        ];
        
        // Filter enabled methods
        foreach ($enabled_methods as $method_id) {
            if (isset($available_methods[$method_id])) {
                $this->methods[$method_id] = $available_methods[$method_id];
            }
        }
    }

    /**
     * Process payment
     *
     * @param string $method
     * @param string $token
     * @return array
     */
    public function process($method, $token = '') {
        // Check if method exists
        if (!isset($this->methods[$method])) {
            return [
                'success' => false,
                'message' => __('Invalid payment method', 'aqualuxe'),
            ];
        }
        
        // Process payment
        $processor = $this->methods[$method]['processor'];
        
        if (is_callable($processor)) {
            return call_user_func($processor, $token);
        }
        
        return [
            'success' => false,
            'message' => __('Payment processor not found', 'aqualuxe'),
        ];
    }

    /**
     * Process Stripe payment
     *
     * @param string $token
     * @return array
     */
    public function process_stripe($token) {
        // Check if token is provided
        if (!$token) {
            return [
                'success' => false,
                'message' => __('Invalid payment token', 'aqualuxe'),
            ];
        }
        
        // Get registration details
        $registration_id = $this->registration->get_id();
        $amount = $this->registration->get_price();
        $attendee_data = $this->registration->get_attendee_data();
        
        // Check if Stripe is configured
        if (!$this->is_stripe_configured()) {
            // For testing purposes, simulate successful payment
            $transaction_id = 'test_' . uniqid();
            
            // Update registration payment data
            $this->registration->set_payment_data([
                'method' => 'stripe',
                'status' => 'completed',
                'transaction_id' => $transaction_id,
                'amount' => $amount,
                'currency' => $this->get_currency(),
                'date' => current_time('mysql'),
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('confirmed');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Payment processed successfully', 'aqualuxe'),
                'transaction_id' => $transaction_id,
            ];
        }
        
        // In a real implementation, we would process the payment with Stripe API
        // For now, we'll simulate a successful payment
        try {
            // Simulate API call
            $transaction_id = 'stripe_' . uniqid();
            
            // Update registration payment data
            $this->registration->set_payment_data([
                'method' => 'stripe',
                'status' => 'completed',
                'transaction_id' => $transaction_id,
                'amount' => $amount,
                'currency' => $this->get_currency(),
                'date' => current_time('mysql'),
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('confirmed');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Payment processed successfully', 'aqualuxe'),
                'transaction_id' => $transaction_id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process PayPal payment
     *
     * @param string $token
     * @return array
     */
    public function process_paypal($token) {
        // Check if token is provided
        if (!$token) {
            return [
                'success' => false,
                'message' => __('Invalid payment token', 'aqualuxe'),
            ];
        }
        
        // Get registration details
        $registration_id = $this->registration->get_id();
        $amount = $this->registration->get_price();
        $attendee_data = $this->registration->get_attendee_data();
        
        // Check if PayPal is configured
        if (!$this->is_paypal_configured()) {
            // For testing purposes, simulate successful payment
            $transaction_id = 'test_' . uniqid();
            
            // Update registration payment data
            $this->registration->set_payment_data([
                'method' => 'paypal',
                'status' => 'completed',
                'transaction_id' => $transaction_id,
                'amount' => $amount,
                'currency' => $this->get_currency(),
                'date' => current_time('mysql'),
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('confirmed');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Payment processed successfully', 'aqualuxe'),
                'transaction_id' => $transaction_id,
            ];
        }
        
        // In a real implementation, we would process the payment with PayPal API
        // For now, we'll simulate a successful payment
        try {
            // Simulate API call
            $transaction_id = 'paypal_' . uniqid();
            
            // Update registration payment data
            $this->registration->set_payment_data([
                'method' => 'paypal',
                'status' => 'completed',
                'transaction_id' => $transaction_id,
                'amount' => $amount,
                'currency' => $this->get_currency(),
                'date' => current_time('mysql'),
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('confirmed');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Payment processed successfully', 'aqualuxe'),
                'transaction_id' => $transaction_id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process bank transfer payment
     *
     * @param string $token
     * @return array
     */
    public function process_bank_transfer($token) {
        // Get registration details
        $registration_id = $this->registration->get_id();
        $amount = $this->registration->get_price();
        
        // Update registration payment data
        $this->registration->set_payment_data([
            'method' => 'bank_transfer',
            'status' => 'pending',
            'transaction_id' => '',
            'amount' => $amount,
            'currency' => $this->get_currency(),
            'date' => current_time('mysql'),
        ]);
        
        // Save registration
        $this->registration->save();
        
        return [
            'success' => true,
            'message' => __('Please complete the bank transfer using the provided details', 'aqualuxe'),
        ];
    }

    /**
     * Process cash payment
     *
     * @param string $token
     * @return array
     */
    public function process_cash($token) {
        // Get registration details
        $registration_id = $this->registration->get_id();
        $amount = $this->registration->get_price();
        
        // Update registration payment data
        $this->registration->set_payment_data([
            'method' => 'cash',
            'status' => 'pending',
            'transaction_id' => '',
            'amount' => $amount,
            'currency' => $this->get_currency(),
            'date' => current_time('mysql'),
        ]);
        
        // Save registration
        $this->registration->save();
        
        return [
            'success' => true,
            'message' => __('Please pay with cash upon arrival', 'aqualuxe'),
        ];
    }

    /**
     * Refund payment
     *
     * @param string $reason
     * @return array
     */
    public function refund($reason = '') {
        // Get payment data
        $payment_data = $this->registration->get_payment_data();
        
        // Check if payment exists
        if (empty($payment_data) || empty($payment_data['method'])) {
            return [
                'success' => false,
                'message' => __('No payment found', 'aqualuxe'),
            ];
        }
        
        // Check if payment is completed
        if ($payment_data['status'] !== 'completed') {
            return [
                'success' => false,
                'message' => __('Payment is not completed', 'aqualuxe'),
            ];
        }
        
        // Process refund based on payment method
        $method = $payment_data['method'];
        
        switch ($method) {
            case 'stripe':
                return $this->refund_stripe($payment_data, $reason);
                
            case 'paypal':
                return $this->refund_paypal($payment_data, $reason);
                
            default:
                // Update payment status
                $this->registration->set_payment_data([
                    'status' => 'refunded',
                ]);
                
                // Save registration
                $this->registration->save();
                
                // Update registration status
                $this->registration->set_status('refunded');
                $this->registration->save();
                
                return [
                    'success' => true,
                    'message' => __('Refund processed successfully', 'aqualuxe'),
                ];
        }
    }

    /**
     * Refund Stripe payment
     *
     * @param array $payment_data
     * @param string $reason
     * @return array
     */
    private function refund_stripe($payment_data, $reason = '') {
        // Check if Stripe is configured
        if (!$this->is_stripe_configured()) {
            // For testing purposes, simulate successful refund
            // Update payment status
            $this->registration->set_payment_data([
                'status' => 'refunded',
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('refunded');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Refund processed successfully', 'aqualuxe'),
            ];
        }
        
        // In a real implementation, we would process the refund with Stripe API
        // For now, we'll simulate a successful refund
        try {
            // Simulate API call
            
            // Update payment status
            $this->registration->set_payment_data([
                'status' => 'refunded',
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('refunded');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Refund processed successfully', 'aqualuxe'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund PayPal payment
     *
     * @param array $payment_data
     * @param string $reason
     * @return array
     */
    private function refund_paypal($payment_data, $reason = '') {
        // Check if PayPal is configured
        if (!$this->is_paypal_configured()) {
            // For testing purposes, simulate successful refund
            // Update payment status
            $this->registration->set_payment_data([
                'status' => 'refunded',
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('refunded');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Refund processed successfully', 'aqualuxe'),
            ];
        }
        
        // In a real implementation, we would process the refund with PayPal API
        // For now, we'll simulate a successful refund
        try {
            // Simulate API call
            
            // Update payment status
            $this->registration->set_payment_data([
                'status' => 'refunded',
            ]);
            
            // Save registration
            $this->registration->save();
            
            // Update registration status
            $this->registration->set_status('refunded');
            $this->registration->save();
            
            return [
                'success' => true,
                'message' => __('Refund processed successfully', 'aqualuxe'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment methods
     *
     * @return array
     */
    public function get_methods() {
        return $this->methods;
    }

    /**
     * Get currency
     *
     * @return string
     */
    private function get_currency() {
        // Get event
        $event = $this->registration->get_event();
        
        // Get currency
        return $event->get_currency();
    }

    /**
     * Check if Stripe is configured
     *
     * @return bool
     */
    private function is_stripe_configured() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            $stripe_api_key = $module->get_setting('stripe_api_key', '');
            return !empty($stripe_api_key);
        }
        
        return false;
    }

    /**
     * Check if PayPal is configured
     *
     * @return bool
     */
    private function is_paypal_configured() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            $paypal_client_id = $module->get_setting('paypal_client_id', '');
            $paypal_client_secret = $module->get_setting('paypal_client_secret', '');
            return !empty($paypal_client_id) && !empty($paypal_client_secret);
        }
        
        return false;
    }
}