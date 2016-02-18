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

require_once(_PS_MODULE_DIR_ . 'listing/classes/listingShop.php');

class listingObject extends ObjectModel
{    
    /**
     * Object vars
     * --
     * @var type 
     */
    public $id_listing;
    public $title;
    public $description;
    public $active;
    public $position;
    public $date_add;
    public $date_upd;
    
    
    
    /**
     * Object Definitions
     * --
     * @var type 
     */
    public static $definition = array(
        'table' => 'listing',
        'primary' => 'id_listing',
        'multilang' => true,
        'multishop' => true,
        'fields' => array(
            //lang fields
            'title'         => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true, /*'shop' => true*/),
            'description'   => array('type' => self::TYPE_HTML, 'validate' => 'isString', 'size' => 3999999999999, 'lang' => true, /*'shop' => true*/),
            //non lang fields
            'active'        => array('type' => self::TYPE_DATE ),
            'position'      => array('type' => self::TYPE_INT ),
            'date_add'      => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd'      => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        )
    );
    
    
    
    public function __construct($id = null, $id_lang = null, $id_shop = null) 
    {    
        ListingShop::addListingAssoTables();
        parent::__construct($id, $id_lang, $id_shop);
    }
    
    
    
    /**
     * Update Position - used in the drop and drag ordering system
     * --
     * @param type $way
     * @param type $position
     * @return boolean
     */
    public function updatePosition($way, $position)
    {        
        $request = 'SELECT `id_listing`, `position` FROM `' .
        _DB_PREFIX_ . 'listing` ORDER BY `position` ASC';
        if (!$res = Db::getInstance()->executeS($request)) {
            return false;
        }

        foreach ($res as $press_item) {
            if ((int) $press_item['id_listing'] == (int) $this->id) {
                $moved_item = $press_item;
            }
        }

        if (!isset($moved_item) || !isset($position)) {
            return false;
        }

        return (
            Db::getInstance()->execute(
                'UPDATE `' . _DB_PREFIX_ . 'listing`
    			SET `position`= `position` ' . ($way ? '- 1' : '+ 1') . '
    			WHERE `position`
    			' . ($way ? '> ' . (int) $moved_item['position'] . ' AND `position` <= ' .
                    (int) $position : '< ' . (int) $moved_item['position'] . '
    			AND `position` >= ' . (int) $position)
            )
        &&
            Db::getInstance()->execute(
                'UPDATE `' . _DB_PREFIX_ . 'listing`
    			SET `position` = ' . (int) $position . '
    			WHERE `id_listing` = ' . (int) $moved_item['id_listing']
            )
        );
    }   
}