## Folder Structure

The following outlines the folder structure for a base installation of this library.

  * /assets/ :
    * contains all of the static assets for the module ( including JS and CSS )
    * /css/ :
      * contains admin and theme CSS files
    * /js/ :
      * contains admin, frontend and shared JS files
    * /lib/ :
      * contains any utilized libraries
    * /scss/ :
      * SCSS preprocessor for CSS containing admin/frontend/shared folders
    * /templates/ :
      * contains all templates used for 'virtual pages'
  * /build/ :
    * contains files minified by the Grunt system
  * /classes/ :
    * core classes
  * /controllers/ :
    * controller code, for use with route callbacks
  * /helpers/ :
    * any third-party code that would belong to "core" but is specific to this instance of the library. These are not auto-loaded and should be included manually on a needed basis. Can use App::helper('ClassName'); to return an instance of the helper. If you want to use a completely static class, you should include it manually either in your functions.php or on a script-by-script basis.
  * /includes/ :
    * routes.php :
      * contains the router declaration
  * /models/ :
    * contains custom post type initialization and data accessors
  * autoload.php :
    * bootstrap file, should be included from your functions.php. Will auto-load everything but helpers.

There are examples provided of models, controllers, and helpers, all within their appropriate placement in the folder structure. These examples, and the source in `/classes/` is heavily commented.

Reading `autoload.php`'s comments is recommended.

For setup:

1. Rename the "example" folder to your choice.
2. Add the following lines to the theme's functions.php file:
```
define('MODULE_NAME', 'example');
require_once("modules/{MODULE_NAME}/autoload.php");
```
3. Change the 'example' text in the define statement to match what you renamed the directory.
4. To run the development grunt commands:
  * cd {module_name}/assets
  * npm install
5. Once that finishes, you should be able to run `grunt watch` from the assets/ directory (it will minify to the build/ directory)
