<?php
/**
 Admin Page Framework v3.5.7b01 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_UserMeta_Model extends AdminPageFramework_UserMeta_Router {
    public function _replyToRegisterFormElements($oScreen) {
        $this->_loadFieldTypeDefinitions();
        $this->oForm->format();
        $this->oForm->applyConditions();
        $this->_registerFields($this->oForm->aConditionedFields);
    }
    protected function _setOptionArray($iUserID) {
        if (!$iUserID) {
            return;
        }
        $_aOptions = array();
        foreach ($this->oForm->aConditionedFields as $_sSectionID => $_aFields) {
            if ('_default' == $_sSectionID) {
                foreach ($_aFields as $_aField) {
                    $_aOptions[$_aField['field_id']] = get_user_meta($iUserID, $_aField['field_id'], true);
                }
            }
            $_aOptions[$_sSectionID] = get_user_meta($iUserID, $_sSectionID, true);
        }
        $_aOptions = $this->oUtil->addAndApplyFilter($this, 'options_' . $this->oProp->sClassName, $_aOptions);
        $_aLastInput = isset($_GET['field_errors']) && $_GET['field_errors'] ? $this->oProp->aLastInput : array();
        $_aOptions = $_aLastInput + $this->oUtil->getAsArray($_aOptions);
        $this->oProp->aOptions = $_aOptions;
    }
    public function _replyToSaveFieldValues($iUserID) {
        if (!current_user_can('edit_user', $iUserID)) {
            return;
        }
        $_aInput = $this->oForm->getUserSubmitDataFromPOST($this->oForm->aConditionedFields, $this->oForm->aConditionedSections);
        $_aInputRaw = $_aInput;
        $_aSavedMeta = $iUserID ? $this->_getSavedMetaArray($iUserID, array_keys($_aInput)) : array();
        $_aInput = $this->oUtil->addAndApplyFilters($this, "validation_{$this->oProp->sClassName}", call_user_func_array(array($this, 'validate'), array($_aInput, $_aSavedMeta, $this)), $_aSavedMeta, $this);
        if ($this->hasFieldError()) {
            $this->_setLastInput($_aInputRaw);
        }
        $this->oForm->updateMetaDataByType($iUserID, $_aInput, $this->oForm->dropRepeatableElements($_aSavedMeta), $this->oForm->sFieldsType);
    }
    private function _getSavedMetaArray($iUserID, array $aKeys) {
        $_aSavedMeta = array();
        foreach ($aKeys as $_sKey) {
            $_aSavedMeta[$_sKey] = get_post_meta($iUserID, $_sKey, true);
        }
        return $_aSavedMeta;
    }
}