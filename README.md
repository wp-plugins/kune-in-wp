# kune-wp-plugin


## Development
Use `grunt watch` to watch and update generated files (ccs and js).

## Initial scaffold

Scaffold generated via grunt-wp-plugin(https://github.com/10up/grunt-wp-plugin) with this standard directory structure:

    /plugin
    .. /assets
    .. .. /css
    .. .. .. /src
    .. .. .. /sass
    .. .. .. /less
    .. .. /js
    .. .. .. /src
    .. /images
    .. .. /src
    .. /includes
    .. /languages
    .. .. plugin.pot
    .. .gitignore
    .. Gruntfile.js
    .. plugin.php
    .. readme.php

### CSS Sass

The goal here is that you only ever edit files in the related /sass source directory and Grunt will automatically build and minify your final stylesheets directly in `/css`.

If you're using Sass or Less, the raw files will be processed into `/css/filename.css` and minified into `/css/filename.min.css`.

If you're using vanilla CSS, the source files will be minified into `/css/filename.min.css`.

### JavaScript

You should only ever be modifying script files in the `/js/src` directory.  Grunt will automatically concatenate and minify your scripts into `/js/filename.js` and `/js/filename.min.js`.  These generated files should never be modified directly.

### Images

The `/images/src` directory exists only to allow you to keep track of source files (like PSDs or separate images that have been merged into sprites).  This helps keep source files under version control, and allows you to bundle them with the distribution of this AGPL plugin.
