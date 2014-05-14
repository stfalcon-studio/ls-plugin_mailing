<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Сущность пользователя
 *
 * @package modules.user
 * @since   1.0
 */
class PluginMailing_ModuleUser_EntityUser extends PluginMailing_Inherit_ModuleUser_EntityUser
{

    public function getUserSubscribes()
    {
        if ($this->_aData['user_subscribes']) {
            $aResult = @json_decode($this->_aData['user_subscribes']);
            return !is_array($aResult) ? array() : $aResult;
        }

        return array();
    }


    public function isSubscribe($type)
    {
        if (in_array($type, $this->getUserSubscribes())) {
            return true;
        }

        return false;
    }

    public function addSubscribe($sSubscribeName)
    {
        if (!$this->isSubscribe($sSubscribeName)) {
            $this->_aData['user_subscribes'] = json_encode(array_merge($this->getUserSubscribes(), array($sSubscribeName)));
        }
    }

    public function removeSubscribe($sSubscribeName)
    {
        if ($this->isSubscribe($sSubscribeName)) {
            $aSubscribes = array_flip($this->getUserSubscribes());
            unset($aSubscribes[$sSubscribeName]);
            $aSubscribes = array_values(array_flip($aSubscribes));
            $this->_aData['user_subscribes'] = json_encode($aSubscribes);
        }
    }
}