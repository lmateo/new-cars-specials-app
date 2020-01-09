<?php
  /**
   * Core Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: core.class.php, v1.00 2014-06-05 10:12:05 gewa Exp $
   */

  if (!defined("_WOJO"))
  	die('Direct access to this location is not allowed.');

  class Core
  {

  	const sTable = "settings";
  	public static $language;

  	public $_url;
  	public $_urlParts;


  	/**
  	 * Core::__construct()
  	 * 
  	 * @return
  	 */
  	public function __construct()
  	{
  		$data = $this->getSettings();
  		$this->_getUrl();
  		$this->_urlParts();
  		($this->dtz) ? ini_set('date.timezone', $this->dtz) : date_default_timezone_set('UTC');
  	}


  	/**
  	 * Core::getSettings()
  	 * 
  	 * @return
  	 */
  	private function getSettings()
  	{
  		$row = Db::run()->select(self::sTable, null, array('id' => 1))->result();

  		$this->company = $row->company;
  		$this->site_dir = $row->site_dir;
  		$this->site_email = $row->site_email;
  		$this->webspecials_email = $row->webspecials_email;
  		$this->address = $row->address;
  		$this->city = $row->city;
  		$this->state = $row->state;
  		$this->zip = $row->zip;
  		$this->phone = $row->phone;
  		$this->fax = $row->fax;
  		$this->country = $row->country;
  		$this->fb = $row->fb;
  		$this->twitter = $row->twitter;
  		$this->logo = $row->logo;
  		$this->short_date = $row->short_date;
  		$this->long_date = $row->long_date;
  		$this->time_format = $row->time_format;
  		$this->dtz = $row->dtz;
  		$this->locale = $row->locale;
  		$this->lang = $row->lang;
  		$this->weekstart = $row->weekstart;
  		$this->theme = $row->theme;
  		$this->perpage = $row->perpage;
  		$this->sperpage = $row->sperpage;
  		$this->featured = $row->featured;
  		$this->currency = $row->currency;
  		$this->offline = $row->offline;
  		$this->offline_msg = $row->offline_msg;
  		$this->offline_d = $row->offline_d;
  		$this->offline_t = $row->offline_t;
  		$this->eucookie = $row->eucookie;
  		$this->sbackup = $row->sbackup;
  		$this->tax = $row->tax;
  		$this->number_sold = $row->number_sold;
  		$this->notify_admin = $row->notify_admin;
  		$this->notify_email = $row->notify_email;
  		$this->pagesize = $row->pagesize;
  		$this->inv_info = $row->inv_info;
  		$this->inv_note = $row->inv_note;
  		$this->show_home = $row->show_home;
  		$this->home_content = $row->home_content;
  		$this->show_slider = $row->show_slider;
  		$this->show_news = $row->show_news;
  		$this->autoapprove = $row->autoapprove;
  		$this->trans_list = $row->trans_list;
  		$this->fuel_list = $row->fuel_list;
  		$this->cond_list = $row->cond_list;
		$this->cond_list_alt = $row->cond_list_alt;
  		$this->odometer = $row->odometer;
  		$this->minprice = $row->minprice;
  		$this->maxprice = $row->maxprice;
  		$this->minsprice = $row->minsprice;
  		$this->maxsprice = $row->maxsprice;
  		$this->minyear = $row->minyear;
  		$this->maxyear = $row->maxyear;
  		$this->minkm = $row->minkm;
  		$this->maxkm = $row->maxkm;
  		$this->color = $row->color;
		$this->makes = $row->makes;
		$this->year_list = $row->year_list;
		$this->year_ws_list = $row->year_ws_list;
		$this->category_list = $row->category_list;
		$this->bodystyle_list = $row->bodystyle_list;
		$this->specialstype_list = $row->specialstype_list;
		$this->make_list = $row->make_list;
		$this->model_list = $row->model_list;
  		$this->vinapi = $row->vinapi;
		$this->mapapi = $row->mapapi;
  		$this->analytics = $row->analytics;
  		$this->metakeys = $row->metakeys;
  		$this->metadesc = $row->metadesc;
  		$this->mailer = $row->mailer;
  		$this->smtp_host = $row->smtp_host;
  		$this->smtp_user = $row->smtp_user;
  		$this->smtp_pass = $row->smtp_pass;
  		$this->smtp_port = $row->smtp_port;
  		$this->sendmail = $row->sendmail;
  		$this->is_ssl = $row->is_ssl;

  		$this->wojov = $row->wojov;
  		$this->wojon = $row->wojon;

  	}


  	/**
  	 * Core::processConfig()
  	 * 
  	 * @return
  	 */
  	public function processConfig()
  	{
  		$validate = Validator::instance();
  		$validate->addSource($_POST);

  		$validate->addRule('company', 'string', true, 3, 100, Lang::$word->CF_COMPANY);
  		$validate->addRule('site_email', 'email');
  		$validate->addRule('webspecials_email', 'email');
  		$validate->addRule('address', 'string', true, 3, 100, Lang::$word->ADDRESS);
  		$validate->addRule('city', 'string', true, 2, 50, Lang::$word->CITY);
  		$validate->addRule('state', 'string', true, 2, 30, Lang::$word->STATE);
  		$validate->addRule('zip', 'string', true, 2, 20, Lang::$word->ZIP);
  		$validate->addRule('country', 'string', true, 2, 100, Lang::$word->COUNTRY);
  		$validate->addRule('phone', 'string', false);
  		$validate->addRule('fax', 'string', false);
  		$validate->addRule('lang', 'string', true, 2, 2, Lang::$word->CF_LANG);
  		$validate->addRule('theme', 'string', true, 3, 20, Lang::$word->CF_THEME);
  		$validate->addRule('locale', 'string', true, 3, 100, Lang::$word->CF_LOCALES);
  		$validate->addRule('mailer', 'string', true, 3, 20, Lang::$word->CF_MAILER);
  		$validate->addRule('site_dir', 'string', false);
  		$validate->addRule('perpage', 'numeric', true, 1, 2, Lang::$word->CF_PERPAGE);
  		$validate->addRule('sperpage', 'numeric', true, 1, 2, Lang::$word->CF_SPERPAGE);
  		$validate->addRule('fb', 'string', false);
  		$validate->addRule('twitter', 'string', false);
  		$validate->addRule('vinapi', 'string', false);
		$validate->addRule('mapapi', 'string', false);
  		$validate->addRule('short_date', 'string', false);
  		$validate->addRule('long_date', 'string', false);
  		$validate->addRule('time_format', 'string', false);
  		$validate->addRule('dtz', 'string', false);
  		$validate->addRule('locale', 'string', false);
  		$validate->addRule('weekstart', 'numeric', false);
  		$validate->addRule('pagesize', 'string', false);
  		$validate->addRule('show_home', 'numeric', false);
  		$validate->addRule('show_slider', 'numeric', false);
  		$validate->addRule('show_news', 'numeric', false);
  		$validate->addRule('autoapprove', 'numeric', false);
  		$validate->addRule('offline_d_submit', 'string', false);
  		$validate->addRule('offline_t_submit', 'string', false);
  		$validate->addRule('currency', 'string', true, 1, 6, Lang::$word->CF_CURRENCY);
  		$validate->addRule('tax', 'numeric', false);
  		$validate->addRule('number_sold', 'numeric', true, 1, 2, Lang::$word->CF_SOLD);
  		$validate->addRule('notify_admin', 'numeric', false);
  		$validate->addRule('notify_email', 'string', false);
  		$validate->addRule('featured', 'numeric', false);
  		$validate->addRule('odometer', 'string', true, 1, 20, Lang::$word->CF_SPEAD);
  		$validate->addRule('mailer', 'string', false);
  		$validate->addRule('analytics', 'string', false);
  		$validate->addRule('pagesize', 'string', false);
  		$validate->addRule('eucookie', 'numeric', false, 1, 1);
  		$validate->addRule('offline', 'numeric', false, 1, 1);
  		$validate->addRule('is_ssl', 'numeric', false, 1, 1);
  		$validate->addRule('smtp_port', 'numeric', false, 2, 6);
  		$validate->addRule('smtp_user', 'string', false, 2, 30);
  		$validate->addRule('smtp_pass', 'string', false, 2, 30);
  		$validate->addRule('smtp_host', 'string', false, 2, 100);
  		$validate->addRule('sendmail', 'string', false, 2, 60);

  		if ($_POST['notify_admin'] == 1) {
  			$validate->addRule('notify_email', 'email');
  		}

  		switch ($_POST['mailer']) {
  			case "SMTP":
  				$validate->addRule('smtp_host', 'string', true, 2, 100, Lang::$word->CF_SMTP_HOST);
  				$validate->addRule('smtp_user', 'string', true, 2, 30, Lang::$word->CF_SMTP_USER);
  				$validate->addRule('smtp_pass', 'string', true, 2, 30, Lang::$word->CF_SMTP_PASS);
  				$validate->addRule('smtp_port', 'numeric', true, 2, 6, Lang::$word->CF_SMTP_PORT);
  				break;

  			case "SMAIL":
  				$validate->addRule('sendmail', 'string', true, 2, 60, Lang::$word->CF_SMAILPATH);
  				break;
  		}

  		if (!empty($_FILES['logo']['name']) and empty(Message::$msgs)) {
  			$upl = Upload::instance(3145728, "png,jpg");
  			$upl->process("logo", UPLOADS, false, "logo", false);
  		}

  		if (!empty($_FILES['logoi']['name']) and empty(Message::$msgs)) {
  			$upl = Upload::instance(3145728, "png,jpg");
  			$upl->process("logoi", UPLOADS, false, "print_logo", false);
  		}

  		$validate->run();
  		if (empty(Message::$msgs)) {
  			$data = array(
  				'company' => $validate->safe->company,
  				'site_dir' => $validate->safe->site_dir,
  				'site_email' => $validate->safe->site_email,
  				'webspecials_email' => $validate->safe->webspecials_email,
  				'address' => $validate->safe->address,
  				'city' => $validate->safe->city,
  				'state' => $validate->safe->state,
  				'zip' => $validate->safe->zip,
  				'country' => $validate->safe->country,
  				'phone' => $validate->safe->phone,
  				'fax' => $validate->safe->fax,
  				'fb' => $validate->safe->fb,
  				'twitter' => $validate->safe->twitter,
  				'short_date' => $validate->safe->short_date,
  				'long_date' => $validate->safe->long_date,
  				'time_format' => $validate->safe->time_format,
  				'dtz' => $validate->safe->dtz,
  				'lang' => $validate->safe->lang,
  				'weekstart' => $validate->safe->weekstart,
  				'locale' => $validate->safe->locale,
  				'theme' => $validate->safe->theme,
  				'offline' => $validate->safe->offline,
  				'offline_msg' => $_POST['offline_msg'],
  				'offline_d' => empty($validate->safe->offline_d_submit) ? Db::toDate() : $validate->safe->offline_d_submit,
  				'offline_t' => empty($validate->safe->offline_t_submit) ? Db::toDate() : $validate->safe->offline_t_submit,
  				'perpage' => $validate->safe->perpage,
  				'sperpage' => $validate->safe->sperpage,
  				'featured' => $validate->safe->featured,
  				'tax' => $validate->safe->tax,
  				'number_sold' => $validate->safe->number_sold,
  				'notify_admin' => $validate->safe->notify_admin,
  				'notify_email' => $validate->safe->notify_email,
  				'show_home' => $validate->safe->show_home,
  				'show_slider' => $validate->safe->show_slider,
  				'show_news' => $validate->safe->show_news,
  				'autoapprove' => $validate->safe->autoapprove,
  				'currency' => $validate->safe->currency,
  				'eucookie' => $validate->safe->eucookie,
  				'odometer' => $validate->safe->odometer,
  				'pagesize' => $validate->safe->pagesize,
  				'metakeys' => $_POST['metakeys'],
  				'metadesc' => $_POST['metadesc'],
  				'analytics' => $validate->safe->analytics,
  				'vinapi' => $validate->safe->vinapi,
				'mapapi' => $validate->safe->mapapi,
  				'inv_info' => $_POST['inv_info'],
  				'inv_note' => $_POST['inv_note'],
  				'mailer' => $validate->safe->mailer,
  				'sendmail' => $validate->safe->sendmail,
  				'smtp_host' => $validate->safe->smtp_host,
  				'smtp_user' => $validate->safe->smtp_user,
  				'smtp_pass' => $validate->safe->smtp_pass,
  				'smtp_port' => $validate->safe->smtp_port,
  				'is_ssl' => $validate->safe->is_ssl,
  				);

  			if (!empty($_FILES['logo']['name'])) {
  				$data['logo'] = $upl->fileInfo['fname'];
  			}
  			Db::run()->update(self::sTable, $data, array('id' => 1));
  			Message::msgReply(Db::run()->affected(), 'success', Lang::$word->CF_UPDATED);
  		} else {
  			Message::msgSingleStatus();
  		}
  	}


  	/**
  	 * Core::_getUrl()
  	 * 
  	 * @return
  	 */
  	protected function _getUrl()
  	{
  		$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : "index";
  		$url = Validator::sanitize($url, "string");
  		$this->_url = explode('/', $url);
  		Debug::addMessage('params', '_url', $this->_url);
  	}


  	/**
  	 * Core::_urlparts()
  	 * 
  	 * @return
  	 */
  	protected function _urlparts()
  	{
  		$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : "index";
  		$url = Validator::sanitize($url, "string");
  		$this->_urlParts = $url;
  	}

      /**
       * Core::calculateTax()
       * 
	   * @param bool $uid
       * @return
       */
	  public static function calculateTax($uid = false)
	  {
		  if(App::get("Core")->tax) {
			  if ($uid) {
				  $cnt = App::get("Db")->first(Users::mTable, array("country"),  array("id" => $uid));
				  $row = App::get("Db")->first(Content::cTable, array("vat"),  array("abbr" => $cnt->country));
			  } else {
				  $row = App::get("Db")->first(Content::cTable, array("vat"),  array("abbr" => Auth::$userdata->country));
			  }
		
			  return Utility::formatNumber($row->vat / 100);
		  } else {
			  return 0.00;
		  }
	  }
	  
      /**
       * Core::getCart()
       * 
	   * @param bool $uid
       * @return
       */
	  public static function getCart($uid = false)
	  {
		  $id = ($uid) ? intval($uid) : App::get("Auth")->uid;
		  $row = App::get("Db")->first(Items::xTable, null, array("uid" => $id));
		  
		  return ($row) ? $row : 0; 
	  }
	  
  	/**
  	 * Core::Meta()
  	 * 
  	 * @return
  	 */
  	public function Meta()
  	{

  		$meta = '';
  		$meta .= '<meta charset="utf-8">';
  		$extraClass = '';
  		$title = App::get('Core')->company . " - " . Lang::$word->META_404;
		$result = '';
		$metakeys = $this->metakeys;
		$metadesc = $this->metadesc;

  		switch (count($this->_url)) {
  			case 2:
  				switch ($this->_url[0]) {
  					case URL_PAGE:
  						if ($result = App::get('Content')->renderPage()) {
  							$title = $result->title . " - " . App::get('Core')->company;
  							$extraClass = "content-page";
  							$template = THEME . "/page.tpl.php";
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;

  					case URL_BRAND:
  						if ($result = App::get('Items')->getBrand()) {
  							$title = $result->name . " - " . App::get('Core')->company;
  							$extraClass = "make-page";
  							$template = THEME . "/make.tpl.php";
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;

  					case URL_SELLER:
  						if ($result = App::get('Items')->getSeller()) {
  							$title = $result->name . " - " . App::get('Core')->company;
  							$extraClass = "seller-page";
  							$template = THEME . "/seller.tpl.php";
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;

  					case URL_BODY:
  						if ($result = App::get('Items')->getBodyType()) {
  							$title = $result->name . " - " . App::get('Core')->company;
  							$extraClass = "category-page";
  							$template = THEME . "/category.tpl.php";
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;

  					default:
  						$template = THEME . "/404.tpl.php";
  						break;
  				}
  				break;

  			case 3:
  				switch ($this->_url[0]) {
  					case URL_ITEM:
  						if ($result = App::get('Items')->getSingleListing()) {
  							$title = $result->nice_title . " - " . App::get('Core')->company;
  							$extraClass = "item-page";
  							$template = THEME . "/item.tpl.php";
							$metakeys = $result->metakey;
							$metadesc = $result->metadesc;
							App::get('Items')->updateHits($result->id);
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;
						
  					default:
  						$template = THEME . "/404.tpl.php";
  						break;
  				}
  				break;

  			case 1:
  				switch ($this->_url[0]) {
  					case "index":
  						$title = Lang::$word->WELCOME2 . " " . App::get('Core')->company . "!";
  						$template = THEME . "/index.tpl.php";
  						break;

  					case URL_LOGIN:
  						$title = App::get('Core')->company . " - " . Lang::$word->META_LOGIN;
  						$extraClass = "login-page";
  						$template = THEME . "/login.tpl.php";
  						break;

  					case URL_REGISTER:
  						$title = App::get('Core')->company . " - " . Lang::$word->META_REG;
  						$extraClass = "register-page";
  						$template = THEME . "/register.tpl.php";
  						break;

  					case URL_ACCOUNT:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "account-page";
  						$template = THEME . "/account.tpl.php";
  						break;

  					case URL_MYLISTINGS:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "mylistings-page";
  						$template = THEME . "/mylistings.tpl.php";
  						break;

  					case URL_MYSETTINGS:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "mysettings-page";
  						$template = THEME . "/mysettings.tpl.php";
  						break;

  					case URL_ADDLISTING:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "add-page";
  						$template = THEME . "/addlisting.tpl.php";
  						break;
						
  					case URL_MYLOCATIONS:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "mylocations-page";
  						$template = THEME . "/mylocations.tpl.php";
  						break;
						
  					case URL_MYREVIEWS:
  						$title = str_replace("[NAME]", App::get('Auth')->username, Lang::$word->META_ACC) . " - " . App::get('Core')->company;
  						$extraClass = "myreviews-page";
  						$template = THEME . "/myreviews.tpl.php";
  						break;
						
						/*
  					case URL_EDIT:
  						if ($result = App::get('Items')->getUserListing()) {
  							$title = Lang::$word->LST_SUB1 . " - " . $result->title;
  							$extraClass = "edit-page";
  							$template = THEME . "/edit.tpl.php";
  						} else {
							$template = THEME . "/404.tpl.php";
  						}
  						break;
						*/
  					case URL_SEARCH:
					    $result = App::get('Items')->fullSearch();
  						$title = Lang::$word->SEARCHR . " - " . App::get('Core')->company;
  						$extraClass = "search-page";
  						$template = THEME . "/search.tpl.php";
  						break;
						
  					case URL_BRANDS:
  						    $result = App::get('Items')->renderBrands(6);
							$title = Lang::$word->META_BRANDS . " - " . App::get('Core')->company;
  							$extraClass = "brands-page";
  							$template = THEME . "/brands.tpl.php";
  						break;

  					case URL_LISTINGS:
  						    $result = App::get('Items')->renderListings();
							$title = Lang::$word->META_LISTINGS . " - " . App::get('Core')->company;
  							$extraClass = "listings-page";
  							$template = THEME . "/listings.tpl.php";
  						break;
							
  					default:
  						$template = THEME . "/404.tpl.php";
  						break;
  				}
  				break;

  			default:
  				$template = THEME . "/404.tpl.php";
  				break;
  		}

  		$menus = App::get('Content')->renderMenus();

        $meta .= "<title>" . $title . "</title>\n";
  		$meta .= "<meta name=\"keywords\" content=\"" . $metakeys . "\">\n";
  		$meta .= "<meta name=\"description\" content=\"" . $metadesc . "\">\n";
  		$meta .= "<meta name=\"dcterms.rights\" content=\"" . App::get('Core')->company . " &copy; All Rights Reserved\">\n";
  		$meta .= "<meta name=\"robots\" content=\"index\">\n";
  		$meta .= "<meta name=\"robots\" content=\"follow\">\n";
  		$meta .= "<meta name=\"revisit-after\" content=\"1 day\">\n";
  		$meta .= "<meta name=\"generator\" content=\"Powered by CDP v" . App::get('Core')->wojov . "\">\n";
  		$meta .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">\n";
  		$meta .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . SITEURL . "/assets/favicons/favicon.ico\">\n";
  		$meta .= "<link rel=\"apple-touch-icon-precomposed\" sizes=\"144x144\" href=\"" . SITEURL . "/assets/favicons/apple-touch-icon-144x144.png\">\n";
  		$meta .= "<link rel=\"apple-touch-icon-precomposed\" sizes=\"152x152\" href=\"" . SITEURL . "/assets/favicons/apple-touch-icon-152x152.png\">\n";
  		$meta .= "<meta name=\"application-name\" content=\"" . App::get('Core')->company . "\">\n";
  		$meta .= "<meta name=\"msapplication-TileColor\" content=\"#FFFFFF\">\n";
  		$meta .= "<meta name=\"msapplication-TileImage\" content=\"" . SITEURL . "/assets/favicons/mstile-144x144.png\">\n";

  		$data = new stdClass();
  		$data->meta = $meta;
  		$data->menus = $menus;
  		$data->result = $result;
  		$data->template = $template;
  		$data->bodyClass = $extraClass;

  		return $data;
  	}
  }