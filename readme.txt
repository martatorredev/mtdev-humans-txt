=== MTDEV Humans TXT ===
Contributors: martatorre
Tags: humans.txt, author, credits
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 8.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create and serve a virtual humans.txt file and add the rel=author link to your site's head. Edit it directly in WordPress, no FTP needed.

== Description ==

MTDEV Humans TXT lets you create and edit your site's **humans.txt** file straight from the WordPress dashboard, without touching code or uploading files over FTP.

The file is served **virtually** at your site root (`https://your-site.com/humans.txt`) through rewrite rules, so it needs no write permissions and no physical file is created. It also adds the recommended tag to the `<head>`:

`<link type="text/plain" rel="author" href="https://your-site.com/humans.txt" />`

Features:

* Content editor with a default template based on the standard (TEAM, THANKS, SITE).
* Virtual `/humans.txt` served as `text/plain`.
* Option to enable or disable the `<head>` link.
* No physical files, multisite friendly.
* Makes no external server calls.
* Translation ready (text domain `mtdev-humans-txt`).

Learn more about the initiative: https://humanstxt.org

Development / source code: https://github.com/martatorredev/mtdev-humans-txt

== Installation ==

1. Upload the `mtdev-humans-txt` folder to the `/wp-content/plugins/` directory, or install it from the WordPress plugin directory.
2. Activate the plugin through the **Plugins** menu.
3. Go to **Settings â†’ Humans.txt** to edit the content.
4. Visit `https://your-site.com/humans.txt` to check the result.

== Frequently Asked Questions ==

= Does it create a physical file on my server? =

No. The content is stored in the database and served virtually at `/humans.txt`. This avoids permission issues and works on multisite.

= The /humans.txt URL returns a 404 =

Go to **Settings â†’ Permalinks** and save the changes to regenerate the rewrite rules.

= Can I disable the head link? =

Yes, from **Settings â†’ Humans.txt** using the matching checkbox.

== Changelog ==

= 1.0.0 =
* Initial release.