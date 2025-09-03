<?php
return [
  // Core providers
  Aqualuxe\Providers\Assets_Service_Provider::class => true,
  Aqualuxe\Providers\Security_Service_Provider::class => true,
  Aqualuxe\Providers\REST_Service_Provider::class => true,
  Aqualuxe\Providers\SEO_Service_Provider::class => true,
  Aqualuxe\Providers\A11y_Service_Provider::class => true,
  Aqualuxe\Providers\Performance_Service_Provider::class => true,
  Aqualuxe\Providers\Customizer_Service_Provider::class => true,
  Aqualuxe\Providers\Admin_Service_Provider::class => true,
  Aqualuxe\Providers\Patterns_Service_Provider::class => true,

  // Domain providers
  Aqualuxe\Providers\Tenancy_Service_Provider::class => true,
  Aqualuxe\Providers\Multilingual_Service_Provider::class => true,
  Aqualuxe\Providers\Currency_Service_Provider::class => true,
  Aqualuxe\Providers\Roles_Service_Provider::class => true,
  Aqualuxe\Providers\Skins_Service_Provider::class => true,
  Aqualuxe\Providers\Vendors_Service_Provider::class => true,
  Aqualuxe\Providers\Content_Types_Service_Provider::class => true,
  Aqualuxe\Shortcodes\Shortcodes_Service_Provider::class => true,
  Aqualuxe\Providers\Importer_Service_Provider::class => true,
  Aqualuxe\Providers\Woo_UI_Service_Provider::class => true,
  Aqualuxe\Providers\Woo_Layout_Service_Provider::class => true,
];
