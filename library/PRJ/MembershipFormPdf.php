<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/MembershipFormPdf.php
 * @author	   Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: MembershipFormPdf.php 353 2019-11-01 11:32:28Z dp $
 */

/**
 * USAGE
$pdfOptions = array(
'data' => array(
'img_url' => 'ein/eUrl',
'filepath' => '01.04.2010',
),
);
$pdf = new L8M_Pdf_Bill($pdfOptions);
$pdf->createPdfBill();
 *
 */

class PRJ_MembershipFormPdf
{

    /**
     * filename of the membership-form-pdf-file
     */
    protected $_filename;

    /**
     * array with html blocks in it
     * @var array
     */
    protected $_htmlArray = array();
    protected $_data;
    protected $_fontStyle = array(
        'family' => '',
        'weight' => '',
        'size' => '10'
    );

    protected $_types = array(
        'membership-form',
    );

    protected $_typeTexts = array();

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
    public function createPdf($fileDestination = 'F') {
        // check necessary params
        if( empty($this->_data)) {
            throw new Exception('missed params for creating the pdf');
        }

        if (isset($this->_data['type'])) {
            if (!in_array($this->_data['type'], $this->_types)) {
                $this->_data['type'] = $this->_types[0];
            }
        } else {
            $this->_data['type'] = $this->_types[0];
        }

        // create new PDF document
        $pdf = new PRJ_Pdf_Base(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

        $fontPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR;

        $membershipFormFileName = ucfirst($this->_data['type']) . '_' . date('Y-m-d-H-i-s');

        $filename = BASE_PATH . DIRECTORY_SEPARATOR . 'data'.DIRECTORY_SEPARATOR . 'temp'.DIRECTORY_SEPARATOR . 'cache'.DIRECTORY_SEPARATOR . 'TCPDF' . DIRECTORY_SEPARATOR . $membershipFormFileName . '.pdf';
        $this->_filename = $filename;

        $pdf->setPrintHeader(FALSE);
        $pdf->setPrintFooter(FALSE);

        // set margins
        $pdf->SetMargins(0, 0, 0, true);
        // set auto page breaks false
        $pdf->SetAutoPageBreak(false, 0);


        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PRJ_SiteConfig::getOption('company_name'));
        $pdf->SetTitle($membershipFormFileName);
        $pdf->SetKeywords('');
        $pdf->SetProtection(array('modify', 'copy'), '', 'xxxxPasswordxxxx');

        // adds a new page
        $pdf->AddPage();

        $this->_legendTopPosition = 252.5;
        $this->_footerTopPosition = 276;

        $this->_addLayout($pdf);
        $this->_addData($pdf);

        $pdf->Output($filename, $fileDestination);

        return $this->_filename;
    }

    public function setData(array $data) {
        $this->_data = $data;
        return $this;
    }

    public function getData() {
        return $this->_data;
    }

    /**
     * setup page layout
     *
     * @param PRJ_Pdf_Base $pdf
     * @return void
     */
    private function _addLayout($pdf) {
        /**
         * Page background
         */
        $image = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'MembershipForm.jpg';

        $pdf->Image($image, 0, 0, 210, 297, '', '', '', FALSE, 72, '', false, false, 0);
    }

    /**
     * add page data
     *
     * @param PRJ_Pdf_Base $pdf
     * @return void
     */
    private function _addData($pdf) {
        $this->_fillFormCompanyName($pdf);
        $this->_fillFormName($pdf);
        $this->_fillFormManagerName($pdf);
        $this->_fillFormAddress($pdf);
        $this->_fillFormZipAndCity($pdf);
        $this->_fillFormEmail($pdf);
        $this->_fillFormHomepage($pdf);
        $this->_fillFormManagerPhone($pdf);
        $this->_fillFormMobile($pdf);
        $this->_fillFormFax($pdf);
    }

    /**
     * fill up company name
     *
     * @return void
     */
    private function _fillFormCompanyName($pdf) {
        $textString = $this->_data['company'];
        $xPosition = 51.55;
        $yPosition = 104;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(68, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up name
     *
     * @return void
     */
    private function _fillFormName($pdf) {
        $textString = $this->_data['salutation'] . ' ' . $this->_data['firstname'] . ' ' . $this->_data['lastname'];
        $xPosition = 51.55;
        $yPosition = 112.9;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(68, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up manager name
     *
     * @return void
     */
    private function _fillFormManagerName($pdf) {
        $textString = $this->_data['salutation'] . ' ' . $this->_data['firstname'] . ' ' . $this->_data['lastname'];
        $xPosition = 51.55;
        $yPosition = 125.9;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(68, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up address
     *
     * @return void
     */
    private function _fillFormAddress($pdf) {
        $textString = $this->_data['address_line_1'];
        if(isset($this->_data['address_line_2']) && ($this->_data['address_line_2'] != '')) {
            $textString .= '; ' . $this->_data['address_line_2'];
        }
        $xPosition = 35.85;
        $yPosition = 134.2;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(83, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up zip and city
     *
     * @return void
     */
    private function _fillFormZipAndCity($pdf) {
        $textString = $this->_data['zip'] . ' / ' . $this->_data['city'];
        $xPosition = 39.3;
        $yPosition = 143.1;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(80.5, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up email
     *
     * @return void
     */
    private function _fillFormEmail($pdf) {
        $textString = $this->_data['email'];
        $xPosition = 138.25;
        $yPosition = 104;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(52, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up homepage
     *
     * @return void
     */
    private function _fillFormHomepage($pdf) {
        $textString = $this->_data['www'];
        $xPosition = 146.8;
        $yPosition = 112.9;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(45, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up manager phone
     *
     * @return void
     */
    private function _fillFormManagerPhone($pdf) {
        $textString = $this->_data['manager_phone'];
        $xPosition = 140;
        $yPosition = 125.9;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(52.5, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up mobile
     *
     * @return void
     */
    private function _fillFormMobile($pdf) {
        $textString = $this->_data['mobile'];
        $xPosition = 139.25;
        $yPosition = 134.2;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(52.5, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }

    /**
     * fill up fax
     *
     * @return void
     */
    private function _fillFormFax($pdf) {
        $textString = $this->_data['fax'];
        $xPosition = 139.25;
        $yPosition = 143.1;
        $pdf->SetFont($this->_fontStyle['family'], $this->_fontStyle['weight'], $this->_fontStyle['size']);
        $pdf->MultiCell(53.25, 10, $textString, 0, 'L', FALSE, 1, $xPosition, $yPosition);
    }
}