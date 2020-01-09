<?php
  /**
   * Stats Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: stats.class.php, v1.00 2014-04-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Stats
  {
	  const sTable = "stats";
	  
	  
	  private static $db;

      /**
       * Stats::__construct()
       * 
       * @return
       */
      public function __construct()
      {
		  self::$db = Db::run();

      }

      /**
       * Stats::getAccountMapStats()
       * 
       * @return
       */
      public function getAccountMapStats()
      {
		  
          $sql = "
		  SELECT
			 COUNT(*) as hits,
			 c.abbr as country_abbr,
			 c.name as country_name      
		  FROM
			 `" . Users::mTable . "` as u 
		  LEFT JOIN
			 `" . Content::cTable . "` as c 
				ON u.country = c.abbr
				WHERE u.active = 'y'
		  GROUP BY
			 c.abbr";

          $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;

      }

      /**
       * Stats::mainCounters()
       * 
       * @return
       */
	  public static function mainCounters()
	  {
	      $data = new stdClass;
		  $data->users = self::$db->count(Users::mTable);
		  $data->active = self::$db->count(Items::lTable);
		  $data->expire = self::$db->count(Items::lTable, "DATE(expire) < DATE(NOW())");
		  $data->pending = self::$db->count(Items::lTable, "DATE(expire) > DATE(NOW()) AND status = 0");
		  $data->week = self::$db->count(Users::mTable, "YEARWEEK(created, " . App::get('Core')->weekstart .  ") = YEARWEEK(CURDATE(), " . App::get('Core')->weekstart .  ")");
		  
		  $sql = ("
			SELECT 
			  nice_title as title,
			  DATE_FORMAT(expire,'%Y-%m-%d') as expires,
			  id
			FROM
			  `" . Items::lTable . "` 
			WHERE MONTH(expire) = MONTH(NOW())
			AND YEAR(expire) = YEAR(NOW());
		  ");
		  $data->listings = self::$db->pdoQuery($sql)->results();
          
		  return $data;
	
	  }
	  
      /**
       * Stats::getSalesStats()
       * 
       * @return
       */
	  public static function getSalesStats()
	  {
	
		  $data = array();
	
		  for ($i = 1; $i <= 12; $i++) {
			  $reg_data[$i] = array(
				  'month' => date('M', mktime(0, 0, 0, $i)),
				  'sales' => 0,
				  'amount' => 0);
		  }
	
		  $sql = "
		  SELECT 
			COUNT(id) AS sales,
			SUM(rate_amount) AS amount,
			created 
		  FROM
			`" . Content::txTable . "` 
		  WHERE status =?
		  GROUP BY created;";
		  
		  $query = self::$db->pdoQuery($sql, array(1));
		  foreach ($query->results() as $result) {
			  $reg_data[date('n', strtotime($result->created))] = array(
				  'month' => Utility::dodate("MMM", date('M', strtotime($result->created))),
				  'sales' => $result->sales,
				  'amount' => $result->amount);
		  }
	
		  $totalsum = 0;
		  $totalsales = 0;
		  foreach ($reg_data as $key => $value) {
			  $data['sales'][] = array($key, $value['sales']);
			  $data['amount'][] = array($key, $value['amount']);
			  $totalsum += $value['amount'];
			  $totalsales += $value['sales'];
		  }
	
		  $data['totalsum'] = $totalsum;
		  $data['totalsales'] = $totalsales;
		  $data['sales_str'] = implode(",", array_column($data["sales"], 1));
		  $data['amount_str'] = implode(",", array_column($data["amount"], 1));
		  
		  return ($data) ? $data : 0;
	
	  } 
	  
      /**
       * Stats::getAllVisits()
       * 
       * @return
       */
	  public static function getAllVisits()
	  {

		  $data = array();
		  $data['xaxis'] = array();
		  $data['visits']['label'] = Lang::$word->VISITS;
		  
		  for ($i = 1; $i <= 12; $i++) {
			  $data['xaxis'][] = array($i, Utility::dodate("MMM", date('M', mktime(0, 0, 0, $i))));
			  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'visits' => 0);
		  }
	
		  $sql = ("
			SELECT 
			  SUM(visits) AS visits,
			  created 
			FROM
			  `" . Stats::sTable . "` 
			WHERE YEAR(created) = YEAR(NOW())
			GROUP BY created;
		  ");
		  $query = self::$db->pdoQuery($sql);
		  foreach ($query->results() as $result) {
			  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'visits' => $result->visits);
		  }
	
		  foreach ($reg_data as $key => $value) {
			  $data['visits']['data'][] = array($key, $value['visits']);
		  }
	  
		  print json_encode($data);
	
	  }

      /**
       * Stats::topFiveVisits()
       * 
       * @return
       */
	  public static function topFiveVisits()
	  {
	
		  $sql = "
			  SELECT 
				SUM(v.visits) AS total,
				l.nice_title as title
			  FROM
				`" . Stats::sTable . "` AS v 
				LEFT JOIN `" . Items::lTable . "` AS l 
				  ON l.id = v.listing_id 
			  WHERE l.status = 1 
			  AND DATE(l.expire) > DATE(NOW())
			  GROUP BY v.listing_id
			  ORDER BY total DESC
			  LIMIT 5;";
		  $query = self::$db->pdoQuery($sql)->results();
	      
		  $temp_array = array();
		  $sum = 0;
		  foreach ($query as $value) {
			  $temp_array[] = array('data' => $value->total);
		  }
		  $sum = array_reduce($temp_array, function ($v1, $v2)
		  {
			  return $v1 + $v2['data']; }
		  );
	      
		  $color = array("primary","negative","warning","positive","purple");
		  foreach ($query as $k => $value) {
			  $data['visits'][] = array(
				  'label' => $value->title .' - <b>' . $value->total . '</b>',
				  'data' => number_format($value->total / $sum * 100, 2),
				  );
			  $data['html'][] = '
			  <div><span class="wojo ' . $color[$k] . ' label push-right">' . number_format($value->total / $sum * 100, 2) . '%</span>' . $value->title .'</div>
			  <div class="wojo thin progress ' . $color[$k] . '" data-percent="' . number_format($value->total / $sum * 100, 2) . '">
				<div class="meter"></div>
			  </div>
			  ' ;
		  }
	      $data['sum'] = $sum;
		  print json_encode($data);
	
	  }
	  
      /**
       * Stats::getMainStats()
       * 
       * @return
       */
	  public static function getMainStats()
	  {

		  $data['xaxis'] = array();
		  $reg_data = array();
	
		  $sql = "
		  SELECT 
			SUM(p.total) AS gross,
			SUM(p.rate_amount) AS net,
			COUNT(p.membership_id) AS total,
			SUM(p.tax) AS taxes,
			SUM(p.coupon) AS coupon,
			m.title,
			m.price 
		  FROM
			`" . Content::txTable . "` AS p 
			LEFT JOIN `" . Content::msTable . "` AS m 
			  ON m.id = p.membership_id 
		  WHERE p.status = 1 
		  GROUP BY m.id
		  ORDER BY gross DESC;";
		  $query = self::$db->pdoQuery($sql);
		  
		  foreach ($query->results() as $result) {
			  $reg_data[] = array(
			  'gross' => $result->gross, 
			  'net' => $result->net, 
			  'total' => $result->total, 
			  'price' => $result->price,
			  'coupon' => $result->coupon,
			  'taxes' => $result->taxes,
			  'title' => $result->title
			  );
		  }
	
		  foreach ($reg_data as $key => $value) {
			  $data['xaxis'][] = array($key, $value['title']);
			  
			  $data['gross']['data'][] = array($key, $value['gross']);
			  $data['gross']['label'] = Lang::$word->TRX_GROSS;
			  $data['gross']['color'] = "#9ACA40";
	
			  $data['net']['data'][] = array($key, $value['net']);
			  $data['net']['label'] = Lang::$word->TRX_NET;
			  $data['net']['color'] = "#1ca8dd";
			  
			  $data['total']['data'][] = array($key, $value['total']);
			  $data['total']['label'] = Lang::$word->TRX_TOTSALES;
			  $data['total']['color'] = "#d25b5b";
			  
			  $data['tax']['data'][] = array($key, $value['taxes']);
			  $data['tax']['label'] = Lang::$word->TRX_TAX;
			  $data['tax']['color'] = "#d9499a";
			  
			  $data['coupon']['data'][] = array($key, $value['coupon']);
			  $data['coupon']['label'] = Lang::$word->TRX_COUPON;
			  $data['coupon']['color'] = "#00b5ad";
		  }
		  
		  print json_encode($data);
	
	  }
  }
?>