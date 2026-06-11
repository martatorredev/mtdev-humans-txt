# MTDEV Humans TXT

Create and serve a virtual `humans.txt` file and add the `rel=author` link to your site's `<head>`. Edit it directly in WordPress, no FTP needed.

This is the development home of the plugin. The published version lives in the
[WordPress.org Plugin Directory](https://wordpress.org/plugins/) (pending review).

## Features

- Serves a virtual `/humans.txt` (no physical file written to disk).
- Adds the recommended `<link rel="author" type="text/plain">` tag to the site head (toggleable).
- Editable content from **Settings → Humans.txt**, with a default template based on the [humanstxt.org](https://humanstxt.org) standard.
- Output as `text/plain` with UTF-8 encoding.
- No external server calls. Multisite friendly. Clean uninstall.

## Installation

1. Download or clone this repository into `wp-content/plugins/mtdev-humans-txt`.
2. Activate **MTDEV Humans TXT** from the Plugins screen.
3. Go to **Settings → Humans.txt** to edit the content.
4. Visit `https://your-site.com/humans.txt` to verify.

> If `/humans.txt` returns a 404 right after activating, go to **Settings → Permalinks** and save to flush rewrite rules.

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
