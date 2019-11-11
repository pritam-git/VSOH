<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ModelForm/Export/Pdf.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: Pdf.php 556 2018-01-18 19:43:01Z nm $
 */

class L8M_ModelForm_Export_Pdf
{


	public function __construct(array $options = null)
	{
		if(is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}


	/**
	 *
	 * @param string $fileDestination
	 * Destination where to send the document. It can take one of the following values:
	 * I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
	 * D: send to the browser and force a file download with the name given by name.
	 * F: save to a local file with the name given by name.
	 * S: return the document as a string. name is ignored.
	 * FI: combine F + I
	 * FD: combine F + D
	 *
	 */
	public function createPdf($fileDestination = 'D') {

		// check necessary params
		if (empty($this->_data)) {
			throw new Exception('missed params for creating the pdf');
		}

		// create new PDF document
		$pdf = new L8M_ModelForm_Export_Pdf_Base(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', TRUE);

		/**
		 * set definitions
		 */
		$pdf->SetAutoPageBreak(TRUE, 0);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('HAHN media group ag.');
		$pdf->SetTitle('');
		$pdf->SetKeywords('');
		$pdf->SetProtection(array('modify', 'copy'));

		// adds a new page
		$pdf->AddPage();

		$this->_addFirstPageLayout($pdf);

		/**
		 * save and output the pdf file
		 */
		if (isset($this->_data['filename']) &&
			$this->_data['filename']) {

			$filenamePD = $this->_data['filename'];
			if (substr($filenamePD, -4) != '.pdf') {
				$filenamePD .= '.pdf';
			}
		} else {
			$filenamePD = 'list.pdf';
		}

		if ($fileDestination == 'I' ||
			$fileDestination == 'D') {

			$filename = $filenamePD;
		} else {
			$filename = BASE_PATH . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR . 'temp'.DIRECTORY_SEPARATOR . 'cache'.DIRECTORY_SEPARATOR . 'TCPDF' . DIRECTORY_SEPARATOR . $filenamePD;
		}

		$pdf->Output($filename, $fileDestination);

		return;
	}

	public function Header() {}

	public function Footer() {}

	public function setData(array $data) {
		$this->_data = $data;
		return $this;
	}

	public function getData() {
		return $this->_data;
	}

	/**
	 * setup first page layout
	 *
	 * @param L8M_ModelForm_Export_Pdf_Base $pdf
	 * @return void
	 */
	private function _addFirstPageLayout($pdf)
	{
		/**
		 * max width is 210
		 * max height is 297
		 */

		$lineHeight = 4;
		$topPosition = 17;
		$leftPosition = 17;

		$xPosition = $topPosition;
		$yPosition = $leftPosition;

		if (isset($this->_data['headline']) &&
			strlen($this->_data['headline']) > 0) {

			$pdf->SetFont('helvetica', 'B', 12);
			$stringHeight = $pdf->GetStringHeight(176, $this->_data['headline']);
			$pdf->MultiCell(176, $stringHeight, $this->_data['headline'], 0, 'L', FALSE, 1, $yPosition, $xPosition);
			$xPosition = $xPosition + $stringHeight;

			if (isset($this->_data['subheadline']) &&
				strlen($this->_data['subheadline']) > 0) {

				$pdf->SetFont('helvetica', 'B', 10);
				$stringHeight = $pdf->GetStringHeight(176, $this->_data['subheadline']);
				$pdf->MultiCell(176, $stringHeight, $this->_data['subheadline'], 0, 'L', FALSE, 1, $yPosition, $xPosition);
				$xPosition = $xPosition + $stringHeight;
			}
			$xPosition = $xPosition + $lineHeight;
		}

		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		$heightArray = array();
		foreach ($this->_data['definition'] as $definition) {
			if ($definition['show']) {
				if (strlen($definition['title']) > 0) {
					$stringHeight = $pdf->GetStringHeight($definition['width'], $definition['title']);
					$heightArray[] = $stringHeight;
					$pdf->MultiCell($definition['width'], $stringHeight, $definition['title'], 0, $definition['align'], FALSE, 1, $yPosition, $xPosition);
				}
				$yPosition = $yPosition + $definition['width'];
			}
		}

		$xPosition = $xPosition + max($heightArray);
		$yPosition = $leftPosition;

		foreach ($this->_data['datas'] as $dataItems) {
			$heightArray = array();
			foreach ($this->_data['definition'] as $definitionKey => $definitionArray) {
				if ($definitionArray['show']) {
					if (isset($dataItems['cell'][$definitionKey]) &&
						strlen($dataItems['cell'][$definitionKey]) > 0) {

						if ($dataItems['cell'][$definitionKey] instanceof Default_Model_MediaImage) {
							$mediaModel = $dataItems['cell'][$definitionKey];
							$heightArray[] = $mediaModel->height / 4;
						} else {
							if ($dataItems['cell'][$definitionKey] instanceof Default_Model_Media) {
								$textString = $dataItems['cell'][$definitionKey]->file_name;
							} else {
								$textString = $dataItems['cell'][$definitionKey];
							}
							if (strlen($textString) > 0) {
								$heightArray[] = $pdf->GetStringHeight($definitionArray['width'], $textString);
							}
						}
					}
				}
			}

			if ($xPosition + max($heightArray) > 290) {
				$pdf->AddPage();
				$xPosition = $topPosition;
				$yPosition = $leftPosition;
			}

			$heightArray = array();
			foreach ($this->_data['definition'] as $definitionKey => $definitionArray) {
				if ($definitionArray['show']) {
					if (array_key_exists($definitionKey, $dataItems['cell'])) {
						if ($dataItems['cell'][$definitionKey] instanceof Default_Model_MediaImage) {
							$mediaModel = $dataItems['cell'][$definitionKey];
							$pdf->Image($mediaModel->getStoredFilePath(), $yPosition, $xPosition, ($mediaModel->width / 4), ($mediaModel->height / 4), '', '', TRUE, 72);
							$heightArray[] = $mediaModel->height / 4;
							$yPosition = $yPosition + $definitionArray['width'];
						} else {
							if ($dataItems['cell'][$definitionKey] instanceof Default_Model_Media) {
								$textString = $dataItems['cell'][$definitionKey]->file_name;
							} else {
								$textString = $dataItems['cell'][$definitionKey];
							}
							if (strlen($textString) > 0) {
								$stringHeight = $pdf->GetStringHeight($definitionArray['width'], $textString);
								$heightArray[] = $stringHeight;
								$pdf->MultiCell($definitionArray['width'], $stringHeight, $textString, 0, $definitionArray['align'], FALSE, 1, $yPosition, $xPosition);
							}
							$yPosition = $yPosition + $definitionArray['width'];
						}
					}
				}
			}
			$xPosition = $xPosition + max($heightArray);
			$yPosition = $leftPosition;
		}
	}
}