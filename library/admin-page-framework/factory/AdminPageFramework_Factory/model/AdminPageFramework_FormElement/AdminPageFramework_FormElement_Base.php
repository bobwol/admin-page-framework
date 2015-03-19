<?php
/**
 Admin Page Framework v3.5.7b01 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_FormElement_Base extends AdminPageFramework_WPUtility {
    public function dropRepeatableElements(array $aOptions) {
        foreach ($aOptions as $_sFieldOrSectionID => $_aSectionOrFieldValue) {
            if ($this->isSection($_sFieldOrSectionID)) {
                $_aFields = $_aSectionOrFieldValue;
                $_sSectionID = $_sFieldOrSectionID;
                if (!$this->isCurrentUserCapable($_sSectionID)) {
                    continue;
                }
                if ($this->isRepeatableSection($_sSectionID)) {
                    unset($aOptions[$_sSectionID]);
                    continue;
                }
                if (!is_array($_aFields)) {
                    continue;
                }
                foreach ($_aFields as $_sFieldID => $_aField) {
                    if (!$this->isCurrentUserCapable($_sSectionID, $_sFieldID)) {
                        continue;
                    }
                    if ($this->isRepeatableField($_sFieldID, $_sSectionID)) {
                        unset($aOptions[$_sSectionID][$_sFieldID]);
                        continue;
                    }
                }
                continue;
            }
            $_sFieldID = $_sFieldOrSectionID;
            if (!$this->isCurrentUserCapable('_default', $_sFieldID)) {
                continue;
            }
            if ($this->isRepeatableField($_sFieldID, '_default')) {
                unset($aOptions[$_sFieldID]);
            }
        }
        return $aOptions;
    }
    private function isCurrentUserCapable($sSectionID, $sFieldID = '') {
        if (!$sFieldID) {
            return isset($this->aSections[$sSectionID]['capability']) ? current_user_can($this->aSections[$sSectionID]['capability']) : true;
        }
        return isset($this->aFields[$sSectionID][$sFieldID]['capability']) ? current_user_can($this->aFields[$sSectionID][$sFieldID]['capability']) : true;
    }
    private function isRepeatableSection($sSectionID) {
        return (isset($this->aSections[$sSectionID]['repeatable']) && $this->aSections[$sSectionID]['repeatable']);
    }
    private function isRepeatableField($sFieldID, $sSectionID) {
        return (isset($this->aFields[$sSectionID][$sFieldID]['repeatable']) && $this->aFields[$sSectionID][$sFieldID]['repeatable']);
    }
    public function isSection($sID) {
        if ($this->isNumericInteger($sID)) {
            return false;
        }
        if (!array_key_exists($sID, $this->aSections)) {
            return false;
        }
        if (!array_key_exists($sID, $this->aFields)) {
            return false;
        }
        $_bIsSeciton = false;
        foreach ($this->aFields as $_sSectionID => $_aFields) {
            if ($_sSectionID == $sID) {
                $_bIsSeciton = true;
            }
            if (array_key_exists($sID, $_aFields)) {
                return false;
            }
        }
        return $_bIsSeciton;
    }
    public function getFieldsModel(array $aFields = array()) {
        $_aFieldsModel = array();
        $aFields = empty($aFields) ? $this->aFields : $aFields;
        foreach ($aFields as $_sSectionID => $_aFields) {
            if ($_sSectionID != '_default') {
                $_aFieldsModel[$_sSectionID] = $_aFields;
                continue;
            }
            foreach ($_aFields as $_sFieldID => $_aField) {
                $_aFieldsModel[$_aField['field_id']] = $_aField;
            }
        }
        return $_aFieldsModel;
    }
    public function _sortByOrder($a, $b) {
        return isset($a['order'], $b['order']) ? $a['order'] - $b['order'] : 1;
    }
    public function applyFiltersToFields($oCaller, $sClassName) {
        foreach ($this->aConditionedFields as $_sSectionID => $_aSubSectionOrFields) {
            foreach ($_aSubSectionOrFields as $_sIndexOrFieldID => $_aSubSectionOrField) {
                if ($this->isNumericInteger($_sIndexOrFieldID)) {
                    $_sSubSectionIndex = $_sIndexOrFieldID;
                    $_aFields = $_aSubSectionOrField;
                    $_sSectionSubString = $this->getAOrB('_default' == $_sSectionID, '', "_{$_sSectionID}");
                    foreach ($_aFields as $_aField) {
                        $this->aConditionedFields[$_sSectionID][$_sSubSectionIndex][$_aField['field_id']] = $this->addAndApplyFilter($oCaller, "field_definition_{$sClassName}{$_sSectionSubString}_{$_aField['field_id']}", $_aField, $_sSubSectionIndex);
                    }
                    continue;
                }
                $_aField = $_aSubSectionOrField;
                $_sSectionSubString = $this->getAOrB('_default' == $_sSectionID, '', "_{$_sSectionID}");
                $this->aConditionedFields[$_sSectionID][$_aField['field_id']] = $this->addAndApplyFilter($oCaller, "field_definition_{$sClassName}{$_sSectionSubString}_{$_aField['field_id']}", $_aField);
            }
        }
        $this->aConditionedFields = $this->addAndApplyFilter($oCaller, "field_definition_{$sClassName}", $this->aConditionedFields);
        $this->aConditionedFields = $this->formatFields($this->aConditionedFields, $this->sFieldsType, $this->sCapability);
    }
}