## Installation

### 1- create wishlist folder in your child theme and upload files.

### 2 - Add These code to your function.php of child theme:

```php 
require_once get_stylesheet_directory() . '/wishlist/post-wishlist.php';
function enqueue_custom_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . 'wishlist/custom-script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
```
