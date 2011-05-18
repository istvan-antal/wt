<?php $module_root = explode('.',$module_name); $module_root = $module_root[0];?>
/*global jQuery<?php if ($module_root != $module_name):?>,<?php echo $module_root?><?php endif;?>*/
"use strict";
<?php if ($module_root == $module_name):?>
var <?php echo $module_root?>;
<?php endif;?>

(function ($) {
    /** @namespace */
    <?php echo $module_name ?> = {};
}(jQuery));