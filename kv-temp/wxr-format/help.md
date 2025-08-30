prepare a **demo content XML file** for AquaLuxe that you can import via:

**WordPress Dashboard → Tools → Import → WordPress → Upload File**

When imported, it will create:

- **Pages**: Home, Shop, Cart, Checkout, About, Contact
- **Sample ornamental fish products** (with placeholder images)
- **Main Menu** and **Footer Menu**
- **WooCommerce shop pages** linked correctly

---

## **📄 aqualuxe-demo.xml** (WXR Format)

Save this as `aqualuxe-demo.xml` and import it.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- This is a WordPress eXtended RSS file for AquaLuxe Demo Content -->
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
    <title>AquaLuxe Demo</title>
    <link>https://aqualuxe.local</link>
    <description>Demo content for AquaLuxe WooCommerce Theme</description>
    <pubDate>Wed, 06 Aug 2025 00:00:00 +0000</pubDate>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>https://aqualuxe.local</wp:base_site_url>
    <wp:base_blog_url>https://aqualuxe.local</wp:base_blog_url>

    <!-- ================== PAGES ================== -->

    <item>
        <title>Home</title>
        <link>https://aqualuxe.local/home/</link>
        <pubDate>Wed, 06 Aug 2025 00:00:00 +0000</pubDate>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[
        <h2>Welcome to AquaLuxe</h2>
        <p>Premium ornamental fish and aquarium products.</p>
        [products limit="4" columns="4" orderby="date" order="DESC"]
        ]]></content:encoded>
    </item>

    <item>
        <title>Shop</title>
        <link>https://aqualuxe.local/shop/</link>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[]]></content:encoded>
    </item>

    <item>
        <title>Cart</title>
        <link>https://aqualuxe.local/cart/</link>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[[woocommerce_cart]]]></content:encoded>
    </item>

    <item>
        <title>Checkout</title>
        <link>https://aqualuxe.local/checkout/</link>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[[woocommerce_checkout]]]></content:encoded>
    </item>

    <item>
        <title>About</title>
        <link>https://aqualuxe.local/about/</link>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[
        <h2>About AquaLuxe</h2>
        <p>AquaLuxe is your trusted source for premium ornamental fish and aquarium care products.</p>
        ]]></content:encoded>
    </item>

    <item>
        <title>Contact</title>
        <link>https://aqualuxe.local/contact/</link>
        <wp:post_type>page</wp:post_type>
        <wp:status>publish</wp:status>
        <content:encoded><![CDATA[
        <h2>Contact Us</h2>
        <p>Email: info@aqualuxe.com</p>
        <p>Phone: +94 71 352 5253</p>
        ]]></content:encoded>
    </item>

    <!-- ================== PRODUCTS ================== -->

    <item>
        <title>Neon Tetra</title>
        <link>https://aqualuxe.local/product/neon-tetra/</link>
        <wp:post_type>product</wp:post_type>
        <wp:status>publish</wp:status>
        <wp:postmeta>
            <wp:meta_key>_price</wp:meta_key>
            <wp:meta_value>3.50</wp:meta_value>
        </wp:postmeta>
        <content:encoded><![CDATA[
        <p>Beautiful small schooling fish perfect for peaceful aquariums.</p>
        ]]></content:encoded>
    </item>

    <item>
        <title>Betta Fish</title>
        <link>https://aqualuxe.local/product/betta-fish/</link>
        <wp:post_type>product</wp:post_type>
        <wp:status>publish</wp:status>
        <wp:postmeta>
            <wp:meta_key>_price</wp:meta_key>
            <wp:meta_value>5.99</wp:meta_value>
        </wp:postmeta>
        <content:encoded><![CDATA[
        <p>Colorful and elegant fish, ideal for single-species tanks.</p>
        ]]></content:encoded>
    </item>

    <item>
        <title>Goldfish</title>
        <link>https://aqualuxe.local/product/goldfish/</link>
        <wp:post_type>product</wp:post_type>
        <wp:status>publish</wp:status>
        <wp:postmeta>
            <wp:meta_key>_price</wp:meta_key>
            <wp:meta_value>2.50</wp:meta_value>
        </wp:postmeta>
        <content:encoded><![CDATA[
        <p>Classic aquarium favorite, hardy and long-lived.</p>
        ]]></content:encoded>
    </item>

    <!-- ================== MENUS ================== -->

    <item>
        <title>Main Menu</title>
        <wp:post_type>nav_menu_item</wp:post_type>
        <wp:status>publish</wp:status>
    </item>

    <item>
        <title>Footer Menu</title>
        <wp:post_type>nav_menu_item</wp:post_type>
        <wp:status>publish</wp:status>
    </item>

</channel>
</rss>
```

---

## **💡 How to Use**

1. Save the above XML as **`aqualuxe-demo.xml`**.
2. Go to **WordPress Admin → Tools → Import → WordPress**.
3. Install the **WordPress Importer** plugin if not installed.
4. Upload `aqualuxe-demo.xml` → Assign posts to an existing or new user → Import attachments if available.
5. After import, go to **WooCommerce → Status → Tools** and **Regenerate Product Lookup Tables**.
6. Set **Home** as static front page in **Settings → Reading**.
7. Assign menus in **Appearance → Menus → Manage Locations**.

---
