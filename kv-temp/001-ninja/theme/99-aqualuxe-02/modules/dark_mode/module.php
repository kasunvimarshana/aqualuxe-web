<?php
namespace AquaLuxe\Modules\DarkMode;

class Module
{
    public function boot(): void
    {
        \add_action('wp_footer', [$this, 'inline_script']);
    }

    public function inline_script(): void
    {
        ?>
        <script>
        // Persist dark mode preference via cookie; accessible without JS too (server uses cookie to set body class)
        (function(){
            var btn = document.getElementById('darkToggle');
            if(!btn) return;
            function setCookie(name, value, days) {
                var d = new Date();
                d.setTime(d.getTime() + (days*24*60*60*1000));
                document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
            }
            function getCookie(name) {
                var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return match ? match[2] : null;
            }
            var dark = getCookie('aqlx_dark') === '1';
            if (dark) { document.documentElement.classList.add('dark'); }
            btn.addEventListener('click', function(){
                dark = !dark;
                setCookie('aqlx_dark', dark ? '1' : '0', 365);
                document.documentElement.classList.toggle('dark', dark);
                btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
            });
        })();
        </script>
        <?php
    }
}
