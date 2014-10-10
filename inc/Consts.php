<?php



	$pgv_pluginname = "PG-VIP"; // نام پلاگین
	$pgv_shortname = "PG-V";
	$PGVDM=array("localhost","www.persiancondom.com","persiancondom.com");
/* ---------------------------------------------------------
تعریف آرایه های تنظیمات
----------------------------------------------------------- */
	 $pgv_plugin_options = array (
    	array( "name" => __("One Month",'PG-VIP'),
    		"desc" => __("One Month VIP Price",'PG-VIP'),
    		"id" => $pgv_shortname."_One_Month",
    		"type" => "text",
    		"std" => 5000),
 
    	array( "name" => __("Three Months",'PG-VIP'),
    		"desc" =>  __("Three Months VIP Price",'PG-VIP'),
    		"id" => $pgv_shortname."_Three_Months",    
			"type" => "text",
    		"std" => 15000),
	
     	array( "name" =>  __("Six Months",'PG-VIP'),
    		"desc" =>  __("Six Months VIP Price",'PG-VIP'),
    		"id" => $pgv_shortname."_Six_Months",    
			"type" => "text",
    		"std" => 30000),
    	array( "name" =>  __("Twelve Months",'PG-VIP'),
    		"desc" =>  __("Twelve Months VIP Price",'PG-VIP'),
    		"id" => $pgv_shortname."_Twelve_Months",
    		"type" => "text",
    		"std" => 60000)
			);
?>