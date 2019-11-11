<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/Banner.php
 * @author     Matthias Rogowski <mr@l8m.com>
 * @version    $Id: Banner.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_Banner
 *
 *
 */
class L8M_View_Helper_Banner extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	// TODO

	// Konstanten fortführen.

	// Je nach gesetzter Konstante banner type ausgeben



	/**
	 *
	 * Rectangles and Pop-Ups
	 */

	/*
	 * size 300 x 250px
	 * @var	string
	 */
	const BANNER_MEDIUM_RECTANGLE	= 'BANNER_MEDIUM_RECTANGLE';

	/*
	 * size 250 x 250px
	 * @var string
	 */
	const BANNER_SQUARE_POP_UP 		= 'BANNER_SQUARE_POP_UP';

	/*
	 * size 240 x 400px
	 * @var string
	 */
	const BANNER_VERTICAL_RECTANGLE = 'BANNER_VERTICAL_RECTANGLE';

	/*
	 * size 336x280px
	 * @var	string
	 */
	const BANNER_LARGE_RECTANGLE 	= 'BANNER_LARGE_RECTANGLE';

	/*
	 * size 180 x 150px
	 * @var	string
	 */
	const BANNER_RECTANGLE			= 'BANNER_RECTANGLE';

	/*
	 * size 300 x 100px
	 * @var	string
	 */
	const BANNER_RECTANGLE_3_1		= 'BANNER_RECTANGLE_3_1';

	/*
	 * size 720 x 300px
	 * @var	string
	 */
	const BANNER_POP_UNDER			= 'BANNER_POP_UNDER';

	/*
	 * size 400 x 400px
	 * @var	string
	 */
	const BANNER_SUPERSTITIAL		= 'BANNER_SUPERSTITIAL';

	/**
	 *
	 * Banner and Buttons
	 */

	/*
	 * size 486 x 60px
	 * @var	string
	 */
	const BANNER_FULL_BANNER		= 'BANNER_FULL_BANNER';

	/*
	 * size 234 x 60px
	 * @var	string
	 */
	const BANNER_HALF_BANNER		= 'BANNER_HALF_BANNER';

	/*
	 * size 88 x 31px
	 * @var	string
	 */
	const BANNER_MICRO_BAR			= 'BANNER_MICRO_BAR';

	/*
	 * size 120 x 90px
	 * @var	string
	 */
	const BANNER_BUTTON_1			= 'BANNER_BUTTON_1';

	/*
	 * size 120 x 60px
	 * @var	string
	 */
	const BANNER_BUTTON_2			= 'BANNER_BUTTON_2';

	/*
	 * size 120 x 240px
	 * @var	string
	 */
	const BANNER_VERTICAL_BANNER	= 'BANNER_VERTICAL_BANNER';

	/*
	 * size 125 x 125px
	 * @var	string
	 */
	const BANNER_SQUARE_BUTTON		= 'BANNER_SQUARE_BUTTON';

	/*
	 * size 728 x 90px
	 * @var	string
	 */
	const BANNER_SUPERBANNER		= 'BANNER_SUPERBANNER';


	/**
	 *
	 * Skyscrapers
	 */

	/*
	 * size 160 x 600px
	 * @var	string
	 */
	const BANNER_WIDE_SCYSCRAPER	= 'BANNER_WIDE_SCYSCRAPER';

	/*
	 * size 120 x 6000px
	 * @var	string
	 */
	const BANNER_SKYSCRAPER			= 'BANNER_SKYSCRAPER';

	/*
	 * size 300 x 600px
	 * @var	string
	 */
	const BANNER_HALF_PAGE_AD		= 'BANNER_HALF_PAGE_AD';

	/**
	 * Javascript
	 * @var string
	 */
	const BANNER_JS_SCRIPT			= 'BANNER_JS_SCRIPT';

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
	 * @param  string $type
     * @return string
     */
    public function banner($type = NULL)
    {
    	ob_start();

		$banner = Default_Modules_Banner_Model_Ad::getInstance();
		$banner->setBanner($type);
?>
<!-- banner begin -->
<a href="<?php echo $banner->getLinkUri(); ?>" alt="<?php echo $banner->getLinkAltText; ?>" title="<?php echo $banner->getLinkTitle(); ?>" target="_blank">
<img src="<?php echo $banner->getBannerURI(); ?>" alt="<?php echo $banner->getBannerAltText(); ?>" width="<?php echo $banner->getBannerWidth(); ?>" height="<?php echo $banner->getBannerHeight(); ?>" />
</a>
<!-- banner end -->
<?php
        return ob_get_clean();
    }
}