<?php

/**
 * L8M
 *
 *
 * @filesource /\tion/views/helpers/TmceTeamMembers.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: TmceTeamMembers.php 16 2019-01-10 13:20:38Z dp $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceTeamMembers
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceTeamMembers extends L8M_View_Helper
{

	private $_file;
	private $_postedData;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a contentBoxes.
	 *
	 * @return string
	 */
	public function tmceTeamMembers()
	{
		$teamMembersCollection = Doctrine_Query::create()
			->from('Default_Model_TeamMembers')
			->orderBy('position')
			->execute()
		;

		$content = '<div class="membersContainer col-xs-12">';
		foreach($teamMembersCollection as $teamMemberModel) {
			$content .= '<div class="memberCard col-md-4 col-sm-6 col-xs-12 p-10">';
			$content .= '<div class="memberDetailsContainer col-xs-12 p-0">';
			$content .= '<div class="memberImageContainer">';
			$content .= '<div class="memberImage col-xs-12 h-100 p-0" style="background-image: url(' . $teamMemberModel->MediaImage->getLink() . ')">';
			$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="memberInfo pl-15">';
			$content .= '<div class="memberName memberInfoItem col-xs-12 p-0"><strong>' . $teamMemberModel->name . '</strong></div>';
			$content .= '<div class="memberTitle memberInfoItem col-xs-12 p-0"><strong>' . $teamMemberModel->title . '</strong></div>';
			$content .= '<div class="memberFunction memberInfoItem col-xs-12 p-0">' . $teamMemberModel->function . '</div>';
			$content .= '<div class="memberDescription memberInfoItem col-xs-12 p-0">' . $teamMemberModel->description . '</div>';
			$content .= '<div class="memberEmail memberInfoItem col-xs-12 p-0 pt-10"><a href = "mailto: ' . $teamMemberModel->email . '">' . $teamMemberModel->email . '</a></div>';
			$content .= '</div>';
			$content .= '</div>';
			$content .= '</div>';
		}
		$content .= '</div>';
		$content .= '<style>';
		$content .= '.memberCard {display: flex; flex-direction: row;}';
		$content .= '.memberDetailsContainer {display: table;}';
		$content .= '.memberImageContainer {width: 130px; min-width: 130px; display: table-cell;}';
		$content .= '.memberImage {background-position: center; background-size: cover; background-repeat: no-repeat; min-height: 200px;}';
		$content .= '.memberInfo {width: calc(100% - 135px); display: table-cell; vertical-align: top;}';
		$content .= '.memberInfoItem {font-size: 13px; display: flex; flex-direction: column;}';
		$content .= '.memberName {font-weight: bold;}';
		$content .= '</style>';

		return $content;
	}
}