<?php
require_once('Plugin.class.php');
/* Локальное дополнение к модулю Plugin */
class PluginMailing_ModulePlugin extends ModulePlugin {
    /**
    * Возвращает список активированных плагинов в системе
    *
    * @return array
    */
    private $aPluginLists = null;
    public function GetActivePlugins() {
        /**
        * Вызываем родительский метод
        */
        if ($this->aPluginLists == null) {
            $this->aPluginLists =parent::GetActivePlugins();
	}
        return $this->aPluginLists;
    }
        
    /**
    * Проверям, активный плагин или нет
    * @param  string sPluginName
    * @return boolean
    */
    public function IsActivePlugins($sPluginName) {
        $aPlugins = $this->GetActivePlugins();
        if (count($aPlugins)<1 ) {
            return false;
        }
        if (!array_search($sPluginName, $aPlugins )){
            return false;
        } 
        return true;
    }
}
