<?php


//include_once(dirname(__FILE__).'/../../classes/team.php');
//include_once(dirname(__FILE__).'/../../team.php');


class AdminListingController extends AdminController 
{
    /** @var boolean Automatically join language table if true */
	public $lang = false;

    /** @var string ORDER BY clause determined by field/arrows in list header */
	protected $_orderBy;

	/** @var string Order way (ASC, DESC) determined by arrows in list header */
	protected $_orderWay;
    
    /** @var array list of available actions for each list row - default actions are view, edit, delete, duplicate */
	protected $actions_available = array('view', 'edit', 'delete', 'duplicate');
    
    /** @var string	identifier to use for changing positions in lists (can be omitted if positions cannot be changed) */
	protected $position_identifier;
	protected $position_group_identifier;
    
    /** @var string module */
    protected $module;


    public function __construct() 
    {
        $this->table = 'listing'; 
        $this->className = 'listingObject';     
        $this->lang = true;
        $this->explicitSelect = true;
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
        $this->_group = 'GROUP BY id_team';
        
        // Generat action on list   
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        
        // This adds a multiple deletion button
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        
        //and define the field to display in the admin table
        $this->fields_list = array(
            'id_team' => array(
                'title' => $this->l('Id Team'),
                'align' => 'left',
                'width' => '100%',
                'name'  => 'id_team'
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'align' => 'left',
                'width' => '100%',
                'name'  => 'name'
            ),
        );
        parent::__construct(); 
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
                    'label' => $this->l("Name")." :",
                    'name' => 'name',
                    'size' => 40,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l("Role")." :",
                    'name' => 'role',
                    'size' => 40,
                    'required' => true,
                    'lang' => true
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l("Intro")." :",
                    'name' => 'intro',
                    'size' => 40,
                    'required' => true,
                    'lang' => true,
                    'cols' => 40,
                    'rows' => 10,
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l("About me")." :",
                    'name' => 'about_me',
                    'size' => 40,
                    'required' => true,
                    'lang' => true,
                    'cols' => 40,
                    'rows' => 10,
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l("Img")." :",
                    'name' => 'img',
                    'size' => 40,
                    'required' => true,
                ),
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
    public function postProcess(){
        if (!$this->redirect_after){
            parent::postProcess();
        }
    }
    
    
    
    /**
     * Overrided to check if the image's folder exist
     */
    public function processSave(){
        return parent::processSave();
    }
    
    
    
}