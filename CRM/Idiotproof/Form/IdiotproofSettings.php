<?php

class CRM_Idiotproof_Form_IdiotproofSettings extends CRM_Settingsform_Form_Settings
{
  function buildQuickForm()
  {
    $this->setSettingFilter(['group' => 'idiotproof']);
    parent::buildQuickForm();
  }
}
