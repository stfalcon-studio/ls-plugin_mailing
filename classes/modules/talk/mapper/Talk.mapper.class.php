<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Mailing Plugin Hook class for LiveStreet
 *
 * Mapper for talk 
 */
class PluginMailing_ModuleTalk_MapperTalk extends PluginMailing_Inherit_ModuleTalk_MapperTalk {

    /**
     * Возвращает список идентификаторов писем, удовлетворяющих условию фильтра
     * + не выводит письма из рассылки для отправителя если на них нету ответов
     *
     * @param  array $aFilter
     * @param  int $iCount
     * @param  int $iCurrPage
     * @param  int $iPerPage
     * @return array
     */
    public function GetTalksByFilter($aFilter,&$iCount,$iCurrPage,$iPerPage) {
        $sql = "SELECT
					tu.talk_id
				FROM
					".Config::Get('db.table.talk_user')." as tu,
					".Config::Get('db.table.talk')." as t,
					".Config::Get('db.table.user')." as u
				WHERE
					tu.talk_id=t.talk_id
					AND tu.talk_user_active = ?d
					AND u.user_id=t.user_id
					{ AND tu.user_id = ?d }
					{ AND t.talk_date <= ? }
					{ AND t.talk_date >= ? }
					{ AND t.talk_title LIKE ? }
					{ AND u.user_login = ? }
					{ AND t.user_id = ? }
                    AND NOT (t.talk_user_ip = '".Config::Get('IP_SENDER')."'
                        AND t.talk_count_comment = 0
                        AND tu.user_id = t.user_id)
				ORDER BY t.talk_date_last desc, t.talk_date desc
				LIMIT ?d, ?d
					";

		$aTalks=array();
		if (
			$aRows=$this->oDb->selectPage(
				$iCount,
				$sql,
				ModuleTalk::TALK_USER_ACTIVE,
				(!empty($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP),
				(!empty($aFilter['date_max']) ? $aFilter['date_max'] : DBSIMPLE_SKIP),
				(!empty($aFilter['date_min']) ? $aFilter['date_min'] : DBSIMPLE_SKIP),
				(!empty($aFilter['keyword']) ? $aFilter['keyword'] : DBSIMPLE_SKIP),
				(!empty($aFilter['user_login']) ? $aFilter['user_login'] : DBSIMPLE_SKIP),
				(!empty($aFilter['sender_id']) ? $aFilter['sender_id'] : DBSIMPLE_SKIP),
				($iCurrPage-1)*$iPerPage,
				$iPerPage
			)
		) {
			foreach ($aRows as $aRow) {
				$aTalks[]=$aRow['talk_id'];
			}
		}
		return $aTalks;
    }
}