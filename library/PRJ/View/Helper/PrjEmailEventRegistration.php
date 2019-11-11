<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/PrjEmailEventRegistration.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: PrjEmailEventRegistration.php 338 2019-01-10 18:44:00Z rq $
 */

/**
 *
 *
 * PRJ_View_Helper_PrjEmailEventRegistration
 *
 *
 */
class PRJ_View_Helper_PrjEmailEventRegistration extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders box.
	 *
	 * @param $mode
	 * @param $eventName
	 * @param $totalPerson
	 * @param $postData
	 * @param $lang
	 * @return string
	 */
	public function prjEmailEventRegistration($mode, $eventName, $totalPerson, $postData, $questionsAndAnswers, $lang, $comment = NULL)
	{
		//get details of user who registered for an event.
		$loginUser = Zend_Auth::getInstance()->getIdentity();

		//check the mode whether it is HTML or TEXT.
		if ($mode == 'html') {
			ob_start();
			?>
			<table style="border: 0; padding:0; margin:0; width:648px;margin-bottom: 20px;font-size: 11px;" border="0" cellspacing="0" cellpadding="0" width="648">
				<tr>
					<td>
						<p style="font-weight: bold;"><?php echo $this->view->translate()->getTranslator()->translate('Benutzername (Eingetragen von)', 'de', $lang); ?>:</p>
					</td>
					<td>
						<p style="font-weight: bold;"><?php echo $loginUser->firstname.' '.$loginUser->lastname; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p style="font-weight: bold;"><?php echo $this->view->translate()->getTranslator()->translate('CH_CODE', 'de', $lang); ?>:</p>
					</td>
					<td>
						<p><?php echo $loginUser->ch_code; ?></p>
					</td>
				</tr>
				<tr>
					<td>
						<p style="font-weight: bold;"><?php echo $this->view->translate()->getTranslator()->translate('Veranstaltungsname', 'de', $lang); ?>:</p>
					</td>
					<td>
						<p><?php echo $eventName; ?></p>
					</td>
				</tr>
			<?php
			if($totalPerson != 0) {
			?>
				<tr>
					<td>
						<p style="font-weight: bold;"><?= $this->view->translate()->getTranslator()->translate('Anzahl Begleitpersonen', 'de', $lang); ?>:</p>
					</td>
					<td>
						<p><?= $totalPerson; ?></p>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">
						<p style="font-weight: bold;"><?= $this->view->translate()->getTranslator()->translate('Name der Begleitpersonen', 'de', $lang); ?>:</p>
					</td>
					<td>
						<?php foreach ($postData as $personKey =>$personValue) { ?>
							<p style="margin: 0;"><?= $personValue; ?></p>
						<?php } ?>
					</td>
				</tr>
			<?php
				foreach($questionsAndAnswers as $index => $questionAndAnswer) {
					if(strlen($questionAndAnswer['question']) && strlen($questionAndAnswer['answer'])) {
			?>
				<tr>
					<td>
						<p style="font-weight: bold;"><?= $questionAndAnswer['question']; ?>:</p>
					</td>
					<td>
					<?php
						if(($questionAndAnswer['answer'] == 'Ja') || ($questionAndAnswer['answer'] == 'Nein')) {
					?>
						<p><?= $this->view->translate()->getTranslator()->translate($questionAndAnswer['answer'], 'de', $lang); ?></p>
					<?php
						} else {
					?>
						<p><?= $questionAndAnswer['answer'] ?></p>
					<?php
						}
					?>
					</td>
				</tr>
				<?php
					}
				}
			} else
			if(($totalPerson == 0) && ($comment != NULL)) {
			?>
				<tr>
					<td>
						<p style="font-weight: bold;"><?= $this->view->translate()->getTranslator()->translate('Grund für die Abmeldung', 'de', $lang); ?>:</p>
					</td>
					<td>
						<p><?= $comment; ?></p>
					</td>
				</tr>
			<?php
			}
			?>
			</table>
			<?php
			return ob_get_clean();

		} else
			if ($mode == 'plain') {

				$returnValue = NULL;
				$returnValue .= '--------------------------------------------------------------------------------------------'. PHP_EOL;
				$returnValue .= $this->view->translate()->getTranslator()->translate('Benutzername (Eingetragen von)', 'de', $lang).': '.$loginUser->firstname.' '.$loginUser->lastname.PHP_EOL;
				$returnValue .= $this->view->translate()->getTranslator()->translate('CH_CODE', 'de', $lang).': '.$loginUser->ch_code.PHP_EOL;
				$returnValue .= $this->view->translate()->getTranslator()->translate('Veranstaltungsname', 'de', $lang).': '.$eventName.PHP_EOL;
				if($totalPerson != 0) {
					$returnValue .= $this->view->translate()->getTranslator()->translate('Anzahl Begleitpersonen', 'de', $lang).': '.$totalPerson.PHP_EOL;
					$returnValue .= $this->view->translate()->getTranslator()->translate('Name der Begleitpersonen', 'de', $lang).': '.PHP_EOL;
					foreach ($postData as $personKey =>$personValue) {
						$returnValue .= "\t".$personValue.PHP_EOL;
					}
				} else
				if(($totalPerson == 0) && ($comment != NULL)) {
					$returnValue .= $this->view->translate()->getTranslator()->translate('Grund für die Abmeldung', 'de', $lang) . ': ' . $comment . PHP_EOL;
				}
				$returnValue .= '--------------------------------------------------------------------------------------------'. PHP_EOL;

				return $returnValue;
			}
	}
}