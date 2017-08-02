<?php
/*
	Plugin Name:ECT SEO 
	Plugin URI: http://www.ecommercetemplates.com//wp-plugins.asp
	Description:This plugin will automatically add meta description tags and will set title the for all ECT store pages.
	Author:Andy Chapman
	Author URI:http://www.ecommercetemplates.com
	Version:1.5
*/ 

add_filter('pre_get_document_title', 'filter_pagetitle',10,1);
add_action('wp_head', 'add_cs_meta',1);
add_shortcode('ect_seo','ect_seo_fun_short');

function ect_seo_fun_short()
{
	remove_action('wp_head','_wp_render_title_tag',1);
	remove_action('wp_head','add_cs_meta',1);
	$T=ect_seo_fun('t') ;
	echo '<title>'.$T.'</title>';
	add_cs_meta();
}
function filter_pagetitle($title) 
{
	$Ttl=ect_seo_fun('t');
	return $Ttl;
} 
function add_cs_meta()
{
	$Meta=ect_seo_fun('m');
	echo '<meta name="Description" content="'.$Meta.'" />';
}
function GetCatInfoPDetail($PID)
{ 	
	global $db_username,$db_password,$db_name,$db_host,$ECTWPDB,$productid;	

	if(!empty($db_username) && !empty($PID))	
	{		
		$SiteRoot=WP_CONTENT_DIR;		
		include_once $SiteRoot."/../vsadmin/includes.php";		
		include_once $SiteRoot."/../vsadmin/db_conn_open.php";	
		if(!$ECTWPDB)		
		{	
			$ECTWPDB=new wpdb($db_username, $db_password, $db_name, $db_host);
			$PArr=$ECTWPDB->get_row("select pID,pName,pSection from products where pID='".$PID."'");
		$Cats=$ECTWPDB->get_row("select sectionName,topsection from sections where sectionID=".$PArr->pSection);		$Cats1=$ECTWPDB->get_row("select sectionName from sections where sectionID=".$Cats->topsection);			

			$Data=array(
			'PName'=>$PArr->pName,
			'PId'=>$PArr->pID,					
			'SecName'=>$Cats->sectionName,	
			'CatName'=>$Cats1->sectionName
			);	
			return $Data;		
		}		
	}
}
function GetCatName($CatID,$Sec='')
{
	global $db_username,$db_password,$db_name,$db_host,$ECTWPDB;

	if(!empty($db_username) && !empty($CatID))
	{
		$SiteRoot=WP_CONTENT_DIR;
		include_once $SiteRoot."/../vsadmin/includes.php";
		include_once $SiteRoot."/../vsadmin/db_conn_open.php";
		if(!$ECTWPDB)
		{
			$ECTWPDB=new wpdb($db_username, $db_password, $db_name, $db_host);
		}
		//$Tbls=$ECTWPDB->get_results("show tables");
		if(empty($Sec))
		{
			$Cats=$ECTWPDB->get_row("select sectionName from sections where sectionID=".$CatID);
			if(!empty($Cats))
				return $Cats->sectionName;
		} 
		else
		{
			$Cats=$ECTWPDB->get_row("select topSection from sections where sectionID=".$CatID);			
			if(!empty($Cats))
			{
				$Cats1=$ECTWPDB->get_row("select sectionName,topSection from sections where sectionID=".$Cats->topSection);
				if(!empty($Cats1))					return $Cats1->sectionName;
			}
		}
		
	}
	return $CatID;
}
function ect_seo_fun($m) 
{	
	$Title=$Meta=$Key='';
	global $pagetitle,$topsection,$sectionname,$sectiondescription,$productname,$productid,$productdescription,$usecategoryname;
	$DataArrTemp=array($pagetitle,$topsection,$sectionname,$sectiondescription,$productname,$productid,$productdescription);

	$BlogInfo=esc_attr(get_bloginfo('name', 'display'))	;
	if(is_front_page() || is_home())
	{
		return GenerateSeoTags('front_page',$Title,$Meta,$Key,$m);
	}
	
	if(@$GLOBALS['ectcartpage']=='proddetail')
	{		
		$prod=esc_sql($_GET['prod']);
		$Pod=isset($productid) ? $productid : $prod;
		$PTitleInfo=GetCatInfoPDetail($Pod);

		$Title=esc_attr(get_bloginfo('name', 'display')).' store: '.$productname. " | " . $sectionname. " | " . $productid;		
		$Srh=array('%SHOW_BLOG_NAME%','%PROD_NAME%','%SEC_NAME%','%PROD_ID%','%CAT_NAME%');
		$BlogInfo=esc_attr(get_bloginfo('name', 'display'))	;
		$productname=empty($productname) ? $PTitleInfo['PName'] : $productname;
		$sectionname=empty($sectionname) ? $PTitleInfo['SecName'] : $sectionname;

		$productid=empty($productid) ? $PTitleInfo['PId'] : $productid;
		$topsection=empty($topsection) ? $PTitleInfo['CatName'] : $topsection;

		$Rep=array($BlogInfo,$productname,$sectionname,$productid,$topsection);	
		$Frm=get_option('ect_seo_pdts',true);				
		$Title=str_replace($Srh,$Rep,$Frm);			
		$Meta=str_replace('"','&quot;',$productdescription);
		return GenerateSeoTags('proddetail',$Title,$Meta,$Key,$m);
	}
	elseif(@$GLOBALS['ectcartpage']=='products' )
	{
		$catt=esc_sql($_GET['cat']);
		$Cat=GetCatName($catt);
		$SecName=GetCatName($catt,'sec');
	//	$Title=esc_attr(get_bloginfo('name', 'display')).' store: ';
		
		$Srh=array('%SHOW_BLOG_NAME%','%PROD_NAME%','%SEC_NAME%','%PROD_ID%','%CAT_NAME%');
		$BlogInfo=esc_attr(get_bloginfo('name', 'display'))	;

		$sectionname=empty($sectionname) ? $SecName : $sectionname;
		$topsection=empty($topsection) ? $Cat : $topsection;

		$Rep=array($BlogInfo,$productname,$sectionname,$productid,$topsection);		
		$Frm=get_option('ect_seo_pts',true);		$Title=str_replace($Srh,$Rep,$Frm);	
	
		/*if($topsection!= "")
			$Title=$Title.$topsection . ",3 ";
		$Title=$Title.$sectionname;*/
		$Meta=str_replace('"','&quot;',$sectiondescription);
		

        return GenerateSeoTags('products',$Title,$Meta,$Key,$m);
	}
	elseif(@$GLOBALS['ectcartpage']=='categories')
	{
		$catt=esc_sql($_GET['cat']);
		$Cat=GetCatName($catt);
		$SecName=GetCatName($catt,'sec');
		//$Title=esc_attr(get_bloginfo('name', 'display')).' store: ';
		//	if($topsection!= "") 
		//		$Title=$Title.$topsection. ", ";
		//	$Title=$Title.$sectionname;
		$Meta=str_replace('"','&quot;',$sectiondescription);
	
		$Srh=array('%SHOW_BLOG_NAME%','%PROD_NAME%','%SEC_NAME%','%PROD_ID%','%CAT_NAME%');
		$BlogInfo=esc_attr(get_bloginfo('name', 'display'))	;

		$topsection=!empty($topsection ) ? $topsection : $Cat;

		$sectionname=$sectionname;	
		if(!$sectionname)
			$sectionname=$SecName;
		$Rep=array($BlogInfo,$productname,$sectionname,$productid,$topsection);
		$Frm='%CAT_NAME% | %SHOW_BLOG_NAME% ';
		$Title=str_replace($Srh,$Rep,$Frm);
		if($topsection)
			return GenerateSeoTags('categories',$Title,$Meta,$Key,$m,1);
		else
			return GenerateSeoTags('categories','','','',$m);
	}
	elseif(@$GLOBALS['ectcartpage']=='cart')
	{
		$Title	='Shopping cart and checkout for '.esc_attr(get_bloginfo('name', 'display'));
		$Meta	='Online store shopping cart and checkout for '.esc_attr(get_bloginfo('name', 'display'));
		$Key	=''; 
		return GenerateSeoTags('cart',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='affiliate')
	{
		$Title='Affiliate program for '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' affiliate program';
		return GenerateSeoTags('affiliate',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='clientlogin')
	{
		$Title='Customer account for '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' client login';
		return GenerateSeoTags('clientlogin',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='giftcertificate')
	{
		$Title='Purchase a gift certificate from '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' gift certificates';
		return GenerateSeoTags('giftcertificate',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='orderstatus')
	{
		$Title='Check the status of your order on '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' order status';
		return GenerateSeoTags('orderstatus',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='search')
	{
		$Title='Search for products on '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' search';
		return GenerateSeoTags('search',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='sorry')
	{
		$Title='Sorry - there seems to be a problem with the order';
		$Meta='Sorry - there seems to be a problem with the order';
		return GenerateSeoTags('sorry',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='thanks')
	{
		$Title='Thank you for purchasing from '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' confirmation page';
		return GenerateSeoTags('thanks',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	elseif(@$GLOBALS['ectcartpage']=='tracking')
	{
		$Title='Track your purchase from '.esc_attr(get_bloginfo('name', 'display'));
		$Meta=esc_attr(get_bloginfo('name', 'display')).' tracking page';
		return GenerateSeoTags('tracking',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
	}
	else
		return GenerateSeoTags('',$Title,$Meta,$Key,$m).' - '.$BlogInfo;
}
add_action('admin_menu','ect_seo_nav');
function ect_seo_nav()
{
	add_menu_page('ECT SEO','ECT SEO','manage_options','ect_seo','ect_seo_nav_fun',plugin_dir_url(__FILE__).'img/ect28x28.png',1104);
}
function ect_seo_nav_fun()
{
	$PagesArr=array('front_page','products','categories','cart','affiliate','clientlogin','giftcertificate','orderstatus','search','sorry','thanks','tracking');
	$Exc=array('proddetail');	
	
	$msg=esc_sql($_GET['msg']);
?>
	<h2>ECT SEO MANAGER</h2>
	<?php echo (!empty($msg)) ? 'Settings Saved' : '';?>
	<ul class="ect_Seo_top">
		<li>Page</li>
		<li>Title</li>
		<li>Meta Description</li>
		<li>&nbsp;</li>
	</ul>
	<form method="post">
		<ul class="ect_seo">
			<?php foreach($PagesArr as $P):?>
			<?php if(!in_array($P,$Exc)):?>
			<li>
				<label><?php echo ucfirst(str_replace('_',' ',$P));?></label>
				<input type="text" name="seo_data_title[<?php echo $P?>]" value="<?php echo stripslashes(get_option('ect_seo_'.$P.'_title'))?>" />
				<input type="text" name="seo_data_metacnt[<?php echo $P?>]" value="<?php echo stripslashes(get_option('ect_seo_'.$P.'_meta_description'))?>" />
			</li>	

			<?php endif;?>
			<?php endforeach;?>	
		</ul>
		<h2>Title elements positon settings</h2>
		<ul class="ect_seo">	
			<li>				
				<label>Product Detail Page </label>	
				<input type="text" name="pdts" value="<?php echo get_option('ect_seo_pdts',true)?>" style="width:666px"/><br />
				<code class="code">%SHOW_BLOG_NAME% store | %PROD_NAME% | %SEC_NAME% | %PROD_ID% | %CAT_NAME% </code>	
			</li>
			<li>				
				<label>Product Page </label>	
				<input type="text" name="pts" value="<?php echo get_option('ect_seo_pts',true)?>" style="width:666px"/><br />
				<code class="code">%SHOW_BLOG_NAME% store |: %SEC_NAME% | %CAT_NAME% </code>	
			</li>
			<li>				
				<label>Category Page </label>	
				<input type="text" name="cts" value="<?php echo get_option('ect_seo_cts',true)?>" style="width:666px"/><br />
				<code class="code">%SHOW_BLOG_NAME% store | %SEC_NAME% | %CAT_NAME% </code>	
			</li>
			<li><input type="submit" value="Update Settings"/>
			<span style="float:right;">Use:<code>%SHOW_BLOG_NAME%</code></span></li>
		</ul>
	</form>	<style>		.ect_seo li .code		{			margin-left:155px;		}
		.ect_seo li label
		{
			width:150px;
			display:inline-block;
		}
		.ect_seo li input[type=text]
		{
			width:330px;
		}
		.ect_Seo_top li
		{
			float:left;
			font-weight:bold;
			width:284px;
		}
		.ect_Seo_top li:last-child
		{
			float:none;
		}
	</style>
<?php
	if(!empty($_POST))
	{
		$PagesArr2=array('front_page','products','categories','cart','affiliate','clientlogin','giftcertificate','orderstatus','search','sorry','thanks','tracking');	
		
		$pdts=esc_sql($_POST['pdts']);
		$pts=esc_sql($_POST['pts']);
		$cts=esc_sql($_POST['cts']);
		$seo_data_metacnt=esc_sql($_POST['seo_data_metacnt']);
		$seo_data_title=esc_sql($_POST['seo_data_title']);
			
		update_option('ect_seo_pdts',$pdts);
		update_option('ect_seo_pts',$pts);
		update_option('ect_seo_cts',$cts);
		if(!empty($seo_data_title) && !empty($seo_data_metacnt))
		{
			for($i=0;$i<count($seo_data_title);$i++)
			{
				update_option('ect_seo_'.$PagesArr2[$i].'_title',$seo_data_title[$PagesArr2[$i]]);
				update_option('ect_seo_'.$PagesArr2[$i].'_meta_description',$seo_data_metacnt[$PagesArr2[$i]]);
			}
			echo '<script type="text/javascript">window.location="admin.php?page=ect_seo&msg=1"</script>';
		}
	
	}
}

function GenerateSeoTags($P,$DefTitle='',$DefDesc='',$DefKey='',$type='t',$Off=false)
{

	global $post;
		global $pagetitle,$topsection,$sectionname,$sectiondescription,$productname,$productid,$productdescription;
		$Title=get_option('ect_seo_'.$P.'_title');
		$Meta=get_option('ect_seo_'.$P.'_meta_description');
		$Key=get_option('ect_seo_'.$P.'_meta_keywords');
		if($P=='products' || $P=='categories')
		{
			$cat=esc_sql($_GET['cat']);
			if(empty($pagetitle) && isset($cat) && !empty($cat))
				$Title=$Meta='';
		}
		if($Off)
			$Title='';
		$Title	=!empty($Title) ? $Title : $DefTitle;
		$Meta	=!empty($Meta) ? $Meta : $DefDesc;
		$Key	=!empty($Key) ? $Key : $DefKey;
		$BlogInfo=esc_attr(get_bloginfo('name', 'display'));
		$Title1=get_option('ect_seo_'.$post->ID.'_title');
		$Meta1=get_option('ect_seo_'.$post->ID.'_meta_description');
		if(!empty($post->ID) && !empty($Title1))
			$Title=$Title1;
		if(!empty($post->ID) && !empty($Meta1))	

		$Meta=$Meta1;
	//echo $productname;
	if(empty($Title))
		$Title=$post->post_title;
	$T	=str_replace("%SHOW_BLOG_NAME%",$BlogInfo,$Title);
	$M	=str_replace("%SHOW_BLOG_NAME%",$BlogInfo,$Meta);
	$K	=str_replace("%SHOW_BLOG_NAME%",$BlogInfo,$Key);


	
	$DynamicMetaPages=array('proddetail','products','categories');
	if($P=='front_page')
		return ($type=='t') ? $T : $M;
	elseif(!empty($Title))
	{
		if(in_array($P,$DynamicMetaPages))	
		{	
			$DescP=($P!='proddetail') ? $sectiondescription : $productdescription;		
			if(!empty($Title))		
			{			
				//$Srh=array('%SHOW_BLOG_NAME%','%PROD_NAME%','%SEC_NAME%','%PROD_ID%','%CAT_NAME%');
				//$BlogInfo=esc_attr(get_bloginfo('name', 'display'))	;
				//$Rep=array($BlogInfo,$productname,$sectionname,$productid,$Cat);
				return ($type=='t') ? $T : $M;	
			}		
			else		
			{				
				$ts=($topsection != "") ? $topsection . ", " : $sectionname;
				if($P=='proddetail')			
				{				
					$ts=$productname . ", " . $sectionname . ", " . $productid;
					if($NoPID)					
						$ts=$productname . ", " . $sectionname;			
				}			
				return ($type=='t') ? $T : $M;
			}					
		}
		else
			return ($type=='t') ? $T : $M;
	}		
	else
	{
	
		return ($type=='t') ? $T : $M;	
	}
}
add_action( 'add_meta_boxes', 'ect_seo_box_fun' );
function ect_seo_box_fun()
{
	$screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) 
	    add_meta_box('ect_cust_seo_mb',__( 'ECT SEO ', 'myplugin_textdomain' ),'ect_seo_b',$screen);
}
function ect_seo_b()
{
	global $post;
	echo '<ul class="ect_seo">
		<li>
			<label>Page Title</label>
			<input type="text" name="ect_seo_title" value="'.get_option('ect_seo_'.$post->ID.'_title').'"/>
		</li>

		<li>
			<label>Page Meta Description</label>
			<textarea name="ect_seo_desc" >'.get_option('ect_seo_'.$post->ID.'_meta_description').'</textarea>
		</li>
	</ul><style>
		.ect_seo li input[type=text]
		{
			width:320px;
		}
		.ect_seo li textarea
		{
			width:320px;
			height:85px;
		}
		.ect_seo li label
		{
			width:138px;
			display:inline-block;
			float:left;
		}
	</style>';
}
add_action( 'save_post', 'ect_seo_save_post_fun' );
function ect_seo_save_post_fun()
{
	global $post;
	$ect_seo_title=esc_sql($_POST['ect_seo_title']);
	$ect_seo_desc=esc_sql($_POST['ect_seo_desc']);
	update_option('ect_seo_'.$post->ID.'_title',$ect_seo_title);
	update_option('ect_seo_'.$post->ID.'_meta_description',$ect_seo_desc);
}
?>