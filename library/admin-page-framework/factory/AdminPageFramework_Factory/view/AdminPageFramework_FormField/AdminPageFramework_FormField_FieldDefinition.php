<?php
/**
 Admin Page Framework v3.5.7b01 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_FormField_FieldDefinition extends AdminPageFramework_FormField_Base {
    protected function _constructFieldsArray(&$aField, &$aOptions) {
        $_mSavedValue = $this->_getStoredInputFieldValue($aField, $aOptions);
        $_aFields = $this->_getFieldsWithSubs($aField, $_mSavedValue);
        $this->_setSavedFieldsValue($_aFields, $_mSavedValue, $aField);
        $this->_setFieldsValue($_aFields);
        return $_aFields;
    }
    private function _getFieldsWithSubs(array $aField, $mSavedValue) {
        $aFirstField = array();
        $aSubFields = array();
        $this->_divideMainAndSubFields($aField, $aFirstField, $aSubFields);
        $this->_fillRepeatableElements($aField, $aSubFields, $mSavedValue);
        $this->_fillSubFields($aSubFields, $aFirstField);
        return array_merge(array($aFirstField), $aSubFields);
    }
    private function _divideMainAndSubFields(array $aField, array & $aFirstField, array & $aSubFields) {
        foreach ($aField as $_nsIndex => $_mFieldElement) {
            if (is_numeric($_nsIndex)) {
                $aSubFields[] = $_mFieldElement;
            } else {
                $aFirstField[$_nsIndex] = $_mFieldElement;
            }
        }
    }
    private function _fillRepeatableElements(array $aField, array & $aSubFields, $mSavedValue) {
        if (!$aField['repeatable']) {
            return;
        }
        $_aSavedValues = ( array )$mSavedValue;
        unset($_aSavedValues[0]);
        foreach ($_aSavedValues as $_iIndex => $vValue) {
            $aSubFields[$_iIndex - 1] = isset($aSubFields[$_iIndex - 1]) && is_array($aSubFields[$_iIndex - 1]) ? $aSubFields[$_iIndex - 1] : array();
        }
    }
    private function _fillSubFields(array & $aSubFields, array $aFirstField) {
        foreach ($aSubFields as & $_aSubField) {
            $_aLabel = $this->getElement($_aSubField, 'label', $this->getElement($aFirstField, 'label', null));
            $_aSubField = $this->uniteArrays($_aSubField, $aFirstField);
            $_aSubField['label'] = $_aLabel;
        }
    }
    private function _setSavedFieldsValue(array & $aFields, $mSavedValue, $aField) {
        $_bHasSubFields = count($aFields) > 1 || $aField['repeatable'] || $aField['sortable'];
        if (!$_bHasSubFields) {
            $aFields[0]['_saved_value'] = $mSavedValue;
            $aFields[0]['_is_multiple_fields'] = false;
            return;
        }
        foreach ($aFields as $_iIndex => & $_aThisField) {
            $_aThisField['_saved_value'] = $this->getElement($mSavedValue, $_iIndex, null);
            $_aThisField['_is_multiple_fields'] = true;
        }
    }
    private function _setFieldsValue(array & $aFields) {
        foreach ($aFields as & $_aField) {
            $_aField['_is_value_set_by_user'] = isset($_aField['value']);
            $_aField['value'] = $this->_getSetFieldValue($_aField);
        }
    }
    private function _getSetFieldValue(array $aField) {
        if (isset($aField['value'])) {
            return $aField['value'];
        }
        if (isset($aField['_saved_value'])) {
            return $aField['_saved_value'];
        }
        if (isset($aField['default'])) {
            return $aField['default'];
        }
        return null;
    }
    private function _getStoredInputFieldValue($aField, $aOptions) {
        if (!isset($aField['section_id']) || '_default' == $aField['section_id']) {
            return $this->getElement($aOptions, $aField['field_id'], null);
        }
        if (isset($aField['_section_index'])) {
            return $this->getElement($aOptions, array($aField['section_id'], $aField['_section_index'], $aField['field_id']), null);
        }
        return $this->getElement($aOptions, array($aField['section_id'], $aField['field_id']), null);
    }
}