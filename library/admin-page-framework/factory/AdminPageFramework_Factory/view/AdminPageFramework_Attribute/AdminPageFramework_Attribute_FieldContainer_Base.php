<?php
/**
 Admin Page Framework v3.6.0b07 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_Attribute_FieldContainer_Base extends AdminPageFramework_Attribute_Base {
    protected function _getFormattedAttributes() {
        $_aAttributes = $this->uniteArrays($this->getElementAsArray($this->aArguments, array('attributes', $this->sContext)), $this->aAttributes + $this->_getAttributes());
        $_aAttributes['class'] = $this->getClassAttribute($this->getElement($_aAttributes, 'class', array()), $this->getElement($this->aArguments, array('class', $this->sContext), array()));
        return $_aAttributes;
    }
}