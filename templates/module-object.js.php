<?php $module_root = explode('.',$module_name); $module_root = $module_root[0];?>
/*global jQuery<?php if ($module_root != $module_name):?>,<?php echo $module_root?><?php endif;?>*/
"use strict";
<?php if ($module_root == $module_name):?>
var <?php echo $module_root?>;
<?php endif;?>

(function ($) {
    /**
     * Description of constructor.
     * @class Description of <?php echo $module_name ?>.
     * @constructs
     */
    <?php echo $module_name ?> = function () {
        var self = /** @lends <?php echo $module_name ?># */ {
        };
        
        return self;
    };
}(jQuery));