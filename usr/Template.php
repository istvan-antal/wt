<?php
class Template extends MicroTemplate {
    public function __construct() {
        $this->setTemplateDirectory(JSOP::getScriptDir().'/templates');
    }
}