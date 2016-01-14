<?php


if (!defined('_PS_VERSION_')) exit;


class listingObject extends ObjectModel
{    
    public $name;
    public $role;
    public $intro;
    public $about_me;
    public $img;
    public $order;
    public $active;
    
    
    public static $definition = array(
        'table' => 'listing',
        'primary' => 'id_listing',
        'multilang' => true,
		'multilang_shop' => true,
        'fields' => array(
            'title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'lang' => true, 'shop' => true),
            'description' => array('type' => self::TYPE_HTML, 'validate' => 'isString', 'size' => 3999999999999, 'lang' => true, 'shop' => true),
            'active' => array('type' => self::TYPE_INT ),
            'position' => array('type' => self::TYPE_INT ),
        )
    );
    
}