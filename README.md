File & Directory Browser
========================

This is a simple extension that will mount and mange pre-configured routes that
serve file system directories.

**NOTE:** 
This is extension was designed to s manage serving files that are not currently
located in the site's web root.

**NOTE:** 
The web server user must be able to read the source directory.

Configuration
-------------

Edit the `routes:` key in `app/config/extensions/file-browser.bolt.yml` and add
named entries that define:
  * `mount`  — The base mount URL
  * `source` — The full file system path to the directory to serve

```yaml
routes:
    manuals:
        mount: manuals
        source: /public/manuals
    pictures:
        mount: downloads/pictures
        source: /data/export/media/pictures
    videos:
        mount: downloads/videos
        source: /data/export/media/videos
```
