<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once(_PS_CLASS_DIR_.'shop/Shop.php');

class ListingShop extends ShopCore
{
    /**
     * Define the assoc shop tables
     * --
     * @var type 
     */
    protected static $listing_shop_tables = array(
        'listing' => array('type' => 'shop')
    );
    
    
    
    /**
     * Assoc the tables when it comes to sav ethe entry
     * --
     */
	public static function addListingAssoTables()
	{
        foreach (self::$listing_shop_tables as $key => $val){
            ShopCore::$asso_tables[$key] = $val;
        }
	}
}