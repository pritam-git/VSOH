<?php

/**
 * L8M
 *
 *
 * @filesource /application/translations/de.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: de.validator.php 7 2014-03-11 16:18:40Z nm $
 */

return array(

	/**
	 * notEmpty
	 */
	'notEmptyInvalid'=>'Die Angabe hat einen ungültigen Typ.',
	'isEmpty'=>'Sie müssen hier eine Angabe machen.',

	/**
	 * digits
	 */
	'notDigits'=>'"%value%" besteht nicht nur aus Zahlen.',
	'digitsStringEmpty'=>'Sie müssen hier eine Angabe machen.',
	'digitsInvalid'=>'Die Angabe hat einen ungültigen Typ.',

	/**
	 * stringLength
	 */
	'stringLengthInvalid'=>'Die Angabe hat einen ungültigen Typ.',
	'stringLengthTooShort'=>'Die Angabe ist kürzer als die erforderliche Mindestlänge von %min% Zeichen.',
	'stringLengthTooLong'=>'Die Angabe ist länger als die mögliche Maximallänge von %max% Zeichen.',

	/**
	 * regex
	 */
	'regexInvalid'=>'Die Angabe hat einen ungültigen Typ.',
	'regexNotMatch'=>'Die Angabe entspricht nicht einer erwarteten Regel.',

	/**
	 * alpha
	 */
	'alphaInvalid'=>'Die Angabe hat einen ungültigen Typ - es wird eine Zeichenkette erwartet.',
	'notAlpha'=>'Die Angabe enthält Zeichen, die keine Buchstaben sind.',
	'alphaStringEmpty'=>'Die Angabe ist eine leere Zeichenkette.',

	/**
	 * identical
	 */
	'notSame'=>'The Angabe stimmt nicht überein.',
	'missingToken'=>'Es wurde keine Angabe gemacht.',

	/**
	 * emailAddress
	 */
	'emailAddressInvalid'=>'Die Email-Adresse sollte ein String sein.',
	'emailAddressInvalidFormat'=>'"%value%" ist keine gültige Email-Adresse im Format "lokaler-teil@host".',
	'emailAddressInvalidHostname'=>'"%hostname%" ist kein gültiger Name für einen Host für die Email-Adresse "%value%".',
	'emailAddressInvalidMxRecord'=>'"%hostname%" scheint keinen gültigen MX Record für die Email-Adresse "%value%" zu besitzen.',
	'emailAddressInvalidSegment'=>'"%hostname%" ist kein Netzwerk-Segment, das geroutet werden kann. Die Email-Adresse "%value%" sollte nicht von einem öffentlichen Netzwerk aufgelöst werden.',
	'emailAddressDotAtom'=>'"%localPart%" kann nicht gegen das dot-atom-Format validiert werden.',
	'emailAddressQuotedString'=>'"%localPart%" kann nicht gegen das quoted-string-Format validiert werden.',
	'emailAddressInvalidLocalPart'=>'"%localPart%" ist kein gültiger lokaler Teil für die Email-Adresse "%value%"',
	'emailAddressLengthExceeded'=>'"%value%" ist zu lang.',

	/**
	 * noRecordExists, recordExists
	 */
	'noRecordFound'=>'Es konnte kein Datensatz mit "%value%" gefunden werden.',
	'recordFound'=>'Ein Datensatz mit "%value%" ist bereits vorhanden.',

);