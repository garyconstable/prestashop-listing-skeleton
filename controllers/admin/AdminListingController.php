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



include_once(dirname(__FILE__).'/../../classes/listing.php');



class AdminListingController extends AdminController 
{
    /**
     * Class vars
     * --
     * @var type 
     */
	public $lang = true;
	protected $position_identifier = 'id_listing';
    protected $module;
    
    
    
    /**
     * Admin module constructore
     * --
     */
    public function __construct() 
    {
        //set module vars
        $this->table = 'listing'; 
        $this->className = 'listingObject';     
        $this->lang = true;
        $this->explicitSelect = true;
        $this->list_no_link = true;
        $this->bootstrap = true;
        
        //        $this->context = Context::getContext();
        //        
        //        //set multistore context
        //        if (Tools::getIsset('id_' . $this->table) || Tools::getIsset('submitAdd' . $this->table)) {
        //            $this->multishop_context = Shop::CONTEXT_ALL;
        //        }
       
        //row actions
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        
        //list query sql params
        $this->_group = 'GROUP BY id_listing';
        $this->_defaultOrderBy = 'position';
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        
        //list fields
        $this->fields_list = array(
            'id_listing' => array(
                'title' => $this->l('Id Listing'),
                'align' => 'left',
                'name'  => 'id_listing'
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'align' => 'left',
                'name'  => 'title'
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
            ),
        );
        parent::__construct(); 
    }
    
    
    
    /**
     * Update positions via ajax
     * --
     */
    public function ajaxProcessUpdatePositions()
    {   
        $way = (int) Tools::getValue('way');
        $id_press_item = (int) Tools::getValue('id');
        $positions = Tools::getValue($this->table);
        
        foreach ($positions as $position => $value) {
            
            $pos = explode('_', $value);
            
            if (isset($pos[2]) && (int) $pos[2] === $id_press_item) {
                
                if ($press = new listingObject((int) $pos[2])) {
                    if (isset($position) && $press->updatePosition($way, $position)) {
                        echo 'ok position ' . (int) $position . ' for press item ' . (int) $pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update press item ' . (int) $id_press_item .' to position ' . (int) $position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This press item (' .(int) $id_press_item . ') can t be loaded"}';
                }
                break;
            }
        }
        
        $this->afterSort();
    }
    
    
    
    /**
     * Fix the sort order, the numbers were not contiguous
     * --
     */
    public function afterSort()
    {
        $q = ' select * from ps_listing order by position ';
        $r =  DB::getInstance()->executeS($q);
        
        for($i=0;$i<count($r);$i++){
            
            if(isset($r[$i+1])) {
                
                $current_position = $r[$i]['position'];
                $next_position = $r[$i+1]['position'];
                
                if($next_position !== ($current_position+1) ){
                    $r[$i+1]['position'] = $current_position + 1;
                }
                
            }else{ 
                
            }
        }
        foreach($r as $x){
            $q = ' update ' . _DB_PREFIX_ . 'listing set position = "'.$x['position'].'" where id_listing = "'.$x['id_listing'].'" ';
            if (!Db::getInstance()->execute($q))
                p(array('error!', $q));
        }
    }
    
    
    
    /**
     * Add aditional media, css, js etc..
     * --
     */
    public function setMedia(){
        parent::setMedia();
    }
    
    
    
    /**
     * render the form to be able to add /  edit entries
     * --
     * @return type
     */
    public function renderForm()
    { 
        $this->fields_form = array
        (
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Add / Edit team members'),
                'icon' => 'icon-envelope-alt'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l("Title")." :",
                    'name' => 'title',
                    'size' => 40,
                    'required' => true,
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l("Description")." :",
                    'name' => 'description',
                    'size' => 40,
                    'required' => true,
                    'lang' => true,
                    'cols' => 40,
                    'rows' => 10,
                    'class' => 'rte',
                    'autoload_rte' => true,
                )
            ),
            'submit' => array(
                'title' => $this->l('Save')
            )
        );
        
        
        if (Shop::isFeatureActive()) 
        {
			$this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso',
                //'values' => Shop::getTree()
            );
		}

        return parent::renderForm(); 
    }
    
    
    
    /**
     * postProcess handle every checks before saving products information
     *
     * @return void
     */
    public function postProcess()
    {
        parent::postProcess();
        
        //        if(isset($_POST['action'])  && $_POST['action'] == 'updatePositions'){
        //             parent::postProcess();
        //        }
        //
        //        else if (!$this->redirect_after){
        //            parent::postProcess();
        //        }
    }
    
    
    
    /**
     * Overrided to check if the image's folder exist
     */
    public function processSave(){
        return parent::processSave();
    }
}