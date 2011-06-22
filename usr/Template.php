<?php
/*!
 * 
 * WebTool
 * http://www.istvan-antal.ro/wt.html
 *
 * Copyright 2011, Antal István Miklós
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.istvan-antal.ro/open-source.html
 * 
 */
class Template extends MicroTemplate {
    public function __construct() {
        $this->setTemplateDirectory(JSOP::getScriptDir().'/templates');
    }
}