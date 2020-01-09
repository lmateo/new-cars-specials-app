<?php
  /**
   * Index
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: index.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid"> 
  <!--/* Home Slider Start */-->
  <?php include("home_slider.tpl.php");?>
  <!--/* Home Slider End */--> 
  
  <!--/* Home Search Start */-->
  <?php include("home_search.tpl.php");?>
  <!--/* Home Search End */--> 
  
  <!--/* Featured Section Start */-->
  <?php include("home_featured.tpl.php");?>
  <!--/* Featured Section Ends */--> 
  
  <!--/* Brands Section Start */-->
  <?php include("home_brands.tpl.php");?>
  <!--/* Brands Section Ends */--> 
  
  <!--/* Review Section Start */-->
  <?php include("home_reviews.tpl.php");?>
  <!--/* Review Section Start */--> 
  
  <!--/* Newsletter Section Start */-->
  <?php include("newsletter.tpl.php");?>
  <!--/* Newsletter Section Ends */--> 
</div>