# WP Insert Code

[**License MIT**](https://github.com/javiervilchezl/wp-insert-code/blob/master/LICENSE)

Insert code fragments in the header, footer and functions.php of your Wordpress.

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
En el caso de crear fragmentos de códigos para el functions.php no es necesario ninguna etiqueta para php, te dejo un ejemplo para un nuevo fragmento de codigo para el functions.php

```bash
# Modify the footer text in the administration part
function custom_text_footer_admin() {
return 'Thank you for believing in ...';
}
add_action( 'admin_footer_text', 'custom_text_footer_admin' );
```





