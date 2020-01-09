<?php
  /**
   * Crumbs
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: crumbs.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

?>
<?php
  $length = count($core->_url);

  switch ($length) {
      case 4:

          break;

      case 3:
          switch (Url::getAction()) {
              case "edit":
                  switch (Url::getOption()) {
                      case "staff":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("staff") . '" class="section">' . Lang::$word->M_TITLE6 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE1 . '</div>';
                          break;

                      case "members":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE4 . '</div>';
                          break;

                      case "countries":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("countries") . '" class="section">' . Lang::$word->CNT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CNT_TITLE1 . '</div>';
                          break;

                      case "gateways":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("gateways") . '" class="section">' . Lang::$word->GW_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GW_TITLE1 . '</div>';
                          break;

                      case "pages":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("pages") . '" class="section">' . Lang::$word->PAG_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_TITLE1 . '</div>';
                          break;

                      case "news":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("news") . '" class="section">' . Lang::$word->NWA_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWA_TITLE1 . '</div>';
                          break;
						  
                      case "slider":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("slider") . '" class="section">' . Lang::$word->SLD_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLD_TITLE1 . '</div>';
                          break;
						  
                      case "coupons":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("coupons") . '" class="section">' . Lang::$word->DC_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->DC_TITLE1 . '</div>';
                          break;
						  
                      case "menus":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("menus") . '" class="section">' . Lang::$word->MENU_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MENU_TITLE1 . '</div>';
                          break;

                      case "faq":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("faq") . '" class="section">' . Lang::$word->FAQ_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_TITLE1 . '</div>';
                          break;

                      case "categories":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("categories") . '" class="section">' . Lang::$word->CAT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CAT_TITLE1 . '</div>';
                          break;
                          
                       case "bodystyle":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("bodystyle") . '" class="section">' . Lang::$word->BS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BS_TITLE1 . '</div>';
                          break;

                      case "gateways":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("gateways") . '" class="section">' . Lang::$word->GW_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GW_TITLE1 . '</div>';
                          break;

                      case "packages":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("packages") . '" class="section">' . Lang::$word->MSM_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MSM_TITLE1 . '</div>';
                          break;

                      case "locations":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("locations") . '" class="section">' . Lang::$word->LOC_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LOC_TITLE1 . '</div>';
                          break;
						  
                      case "items":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE1 . '</div>';
                          break;
                          
                      case "webspecials":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE1 . '</div>';
                          break;

                  }
                  break;

              case "add":
                  switch (Url::getOption()) {
                      case "staff":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("staff") . '" class="section">' . Lang::$word->M_TITLE6 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE2 . '</div>';
                          break;

                      case "members":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE2 . '</div>';
                          break;

                      case "pages":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("pages") . '" class="section">' . Lang::$word->PAG_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_TITLE2 . '</div>';
                          break;

                      case "news":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("news") . '" class="section">' . Lang::$word->NWA_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->NWA_TITLE2 . '</div>';
                          break;
						  
                      case "slider":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("slider") . '" class="section">' . Lang::$word->SLD_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->SLD_TITLE2 . '</div>';
                          break;
						  
                      case "coupons":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("coupons") . '" class="section">' . Lang::$word->DC_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->DC_TITLE2 . '</div>';
                          break;
						  
                      case "faq":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("faq") . '" class="section">' . Lang::$word->FAQ_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_TITLE2 . '</div>';
                          break;

                      case "categories":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("categories") . '" class="section">' . Lang::$word->CAT_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CAT_TITLE2 . '</div>';
                          break;
                          
                       case "bodystyle":
                          	echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("bodystyle") . '" class="section">' . Lang::$word->BS_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->BS_TITLE2 . '</div>';
                          	break;

                      case "packages":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("packages") . '" class="section">' . Lang::$word->MSM_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->MSM_TITLE2 . '</div>';
                          break;

                      case "locations":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("locations") . '" class="section">' . Lang::$word->LOC_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LOC_TITLE2 . '</div>';
                          break;
						  
                      case "items":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE2 . '</div>';
                          break;
                          
                       case "webspecials":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE1 . '</div>';
                          break;
                          
                  }
                  break;

              case "view":
                  switch (Url::getOption()) {
                      case "members":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE3 . '</div>';
                          break;

                      case "items":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE3 . '</div>';
                          break;
                          
                       case "webspecials":
                          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE3 . '</div>';
                          break;

                  }
                  break;

              case "privileges":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("roles") . '" class="section">' . Lang::$word-> M_TITLE4 . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE5 . '</div>';
                  break;

              case "activity":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE5 . '</div>';
                  break;

              case "cars":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE6 . '</div>';
                  break;
                  
               case "dealership":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE3 . '</div>';
                  break;
                  
               case "webspecialsalert":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE3 . '</div>';
                  break;

              case "print":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LPR_TITLE . '</div>';
                  break;

              case "images":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->GAL_TITLE . '</div>';
                  break;

              case "stats":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE4 . '</div>';
                  break;

              case "payments":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("members") . '" class="section">' . Lang::$word->CL_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TRX_TITLE2 . '</div>';
                  break;

          }
          break;

      case 2:
          switch (Url::getOption()) {
              case "staff":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->ACCOUNTS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE6 . '</div>';
                  break;

              case "members":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->ACCOUNTS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->CL_TITLE . '</div>';
                  break;

              case "roles":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->ACCOUNTS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE4 . '</div>';
                  break;

              case "myaccount":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->ACCOUNTS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->MY_ACCOUNT . '</div>';
                  break;

              case "accstats":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->ACCOUNTS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->M_TITLE3 . '</div>';
                  break;

              case "mailer":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->EMN_TITLE . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->EMN_TITLE1 . '</div>';
                  break;
              
              case "webspecialsmailer":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> </a> <div class="divider"></div> <a href="' . Url::adminUrl("webspecials") . '" class="section">' . Lang::$word->WSP_TITLE . '</a><div class="divider"></div> <div class="section">' . Lang::$word->EMN_TITLE . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->EMN_TITLE1 . '</div>';
                  break;

              case "countries":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CONF . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->CNT_TITLE . '</div>';
                  break;

              case "bans":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CONF . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->BL_TITLE . '</div>';
                  break;

              case "configuration":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CONF . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->CF_TITLE . '</div>';
                  break;

              case "etemplates":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->ET_TITLE . '</div>';
                  break;

              case "lmanager":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->LMG_TITLE . '</div>';
                  break;

              case "pages":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->PAG_TITLE . '</div>';
                  break;

              case "news":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->NWA_TITLE . '</div>';
                  break;

              case "menus":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->MENU_TITLE . '</div>';
                  break;
				  
              case "faq":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->FAQ_TITLE . '</div>';
                  break;

              case "slider":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->SLD_TITLE . '</div>';
                  break;

              case "reviews":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->SRW_TITLE . '</div>';
                  break;
				  
              case "coupons":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CON . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->DC_TITLE . '</div>';
                  break;
				  
              case "features":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->FEAT_TITLE . '</div>';
                  break;

              case "makes":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' .
                      Lang::$word->MAKE_TITLE . '</div>';
                  break;

              case "models":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->MODL_TITLE . '</div>';
                  break;

              case "conditions":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->COND_TITLE . '</div>';
                  break;

              case "transmissions":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->TRNS_TITLE . '</div>';
                  break;

              case "fuel":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->FUEL_TITLE . '</div>';
                  break;
                  
              case "year":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->YEAR_TITLE . '</div>';
                  break;
                  
               case "deal":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->DEAL_TITLE . '</div>';
                  break;
                  
                case "lease":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->LEASE_TITLE . '</div>';
                  break;

              case "categories":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' .
                      Lang::$word->CAT_TITLE . '</div>';
                  break;
                  
              case "bodystyle":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' .
                     Lang::$word->BS_TITLE . '</div>';
                  	 break;

              case "gateways":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CONF . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->GW_TITLE . '</div>';
                  break;

              case "packages":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_CONF . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->MSM_TITLE . '</div>';
                  break;

              case "transactions":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->TRX_TITLE . '</div>';
                  break;

              case "locations":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LOC_TITLE . '</div>';
                  break;
				  	  
              case "items":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE . '</div>';
                  break;
                  
              case "webspecials":
                  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->WSP_TITLE . '</div>';
                  break;

			  case "pending":
				  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <a href="' . Url::adminUrl("items") . '" class="section">' . Lang::$word->LST_TITLE . '</a> <div class="divider"></div> <div class="active section">' . Lang::$word->LST_TITLE5 . '</div>';
				  break;
				  
			
			 case "qinvupload":
				  echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a> <div class="divider"></div> <div class="section">' . Lang::$word->AMD_ITEMS . '</div> <div class="divider"></div> <div class="active section">' . Lang::$word->AMD_MI . '</div>';
				  break;

          }
          break;

      default:
          echo '<a href="' . ADMINURL . '/" class="section">' . Lang::$word->DASH . '</a>';
          break;

  }
?>