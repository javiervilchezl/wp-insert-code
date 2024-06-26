# WP Insert Code

[**License MIT**](https://github.com/javiervilchezl/wp-insert-code/blob/master/LICENSE)

Insert code snippet in the header, footer and functions.php of your Wordpress.

![WP-Insert-Code-Snippet---gestion-de-code-snippet](https://github.com/javiervilchezl/wp-insert-code/assets/161946280/479245c2-b5ba-45e3-b06c-28a789d67339)

![WP-Insert-Code-Snippet-agregar-nuevo-code-snippet-](https://github.com/javiervilchezl/wp-insert-code/assets/161946280/a4179155-61d1-47b9-946e-73ea10a17a8a)


## Install

Download it compressed in zip, in the plugin panel of your WordPress select upload plugin, then activate it.

## Uses

You will be able to create them independently and thus have them fully controlled, being able to enable or disable any of them at any time.

>**Note**: When you create a new code fragment for the header or footer, we consider that you want to include javascript or css, in either of these 2 cases use the corresponding opening and closing tags

```bash
# using css
<style>write your code here. ..</style>
# using JavaScript
<script>write your code here...</script>
```
In the case of creating code fragments for functions.php, no tag for php is necessary, I leave you an example for a new code fragment for functions.php

```bash
# Modify the footer text in the administration part
function custom_text_footer_admin() {
return 'Thank you for believing in ...';
}
add_action( 'admin_footer_text', 'custom_text_footer_admin' );
```





