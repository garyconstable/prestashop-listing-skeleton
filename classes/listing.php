<?php

if (!defined('_PS_VERSION_')) exit;

class listingObject extends ObjectModel
{    
    public $id_listing;
    public $title;
    public $description;
    public $active;
    public $position;
    public $date_add;
    public $date_upd;
    
    
    
    public static $definition = array(
        'table' => 'listing',
        'primary' => 'id_listing',
        'multilang' => true,
		'multilang_shop' => true,
        'fields' => array(
            //lang fields
            'title'         => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true, 'shop' => true),
            'description'   => array('type' => self::TYPE_HTML, 'validate' => 'isString', 'size' => 3999999999999, 'lang' => true, 'shop' => true),
            //non lang fields
            'active'        => array('type' => self::TYPE_DATE ),
            'position'      => array('type' => self::TYPE_INT ),
            'date_add'      => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd'      => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        )
    );
    
    
    
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