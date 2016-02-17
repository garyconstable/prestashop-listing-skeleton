<?php

include_once(dirname(__FILE__).'/../../classes/listing.php');

class AdminListingController extends AdminController 
{
	public $lang = true;
    
	protected $actions_available = array('view', 'edit', 'delete', 'duplicate');
   
	protected $position_identifier = 'id_listing';
    
    protected $module;

    public function __construct() 
    {
        
        $this->table = 'listing'; 
        $this->className = 'listingObject';     
        $this->lang = true;
        $this->explicitSelect = true;
        $this->list_no_link = true;
        //$this->shop = true;
		//$this->multilang_shop = true;
            
        $this->bootstrap = true;
        
        //        //multistore context
        //        if (Tools::getIsset('id_' . $this->table) || Tools::getIsset('add' . $this->table)) {
        //            $this->multishop_context = Shop::CONTEXT_ALL;
        //        }
        //        
        //        // http://doc.prestashop.com/display/PS15/Specifics+of+multistore+module+development
        //        $this->multishop_context = Shop::CONTEXT_SHOP;
        
        $this->multishop_context = Shop::CONTEXT_ALL;
        
        //remove dupes using a group
        $this->_group = 'GROUP BY id_listing';
        
        // Generat action on list   
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        
        $this->_defaultOrderBy = 'position';
        
        // This adds a multiple deletion button
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        
        //and define the field to display in the admin table
        $this->fields_list = array(
            'id_listing' => array(
                'title' => $this->l('Id Listing'),
                'align' => 'left',
                'width' => '100%',
                'name'  => 'id_listing'
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
        
        $this->multishop_context = -1;
        $this->multishop_context_group = true;
        
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
        
        //        if (Shop::isFeatureActive()){
        //            $this->fields_form['input'][] = array(
        //                'type' => 'shop',
        //                'label' => $this->l('Shop association'),
        //                'name' => 'checkBoxShopAsso');
        //        }
        
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