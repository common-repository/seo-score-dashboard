<?php
/*
Plugin Name: SEO Score Dashboard
Plugin URI: https://seobot.com
Description: Quickly scans and displays the score for the current WordPress website regarding SEO performance. Uses the external <a href="https://seobot.com" target="_blank">SEO Bot</a> service for free SEO metric scanning.
Version: 1.1.2
Author: SEO Bot
Author URI: https://seobot.com
License: GPL v3
Copyright (C) 2016, SEO Bot Inc - support@seobot.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Create the function to output the contents of our Dashboard Widget

function seoscore_dashboard_widget_function() {

	$overall = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$onpage = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$structure = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$technical = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$popularity = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$social = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';

	$pies20 = '<img src="' . plugins_url( 'img/pies/1.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies30 = '<img src="' . plugins_url( 'img/pies/2.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies40 = '<img src="' . plugins_url( 'img/pies/3.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies50 = '<img src="' . plugins_url( 'img/pies/4.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies60 = '<img src="' . plugins_url( 'img/pies/5.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies80 = '<img src="' . plugins_url( 'img/pies/6.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies90 = '<img src="' . plugins_url( 'img/pies/7.png', __FILE__ ) . '" alt="Score Graph" />';
	$pies100 = '<img src="' . plugins_url( 'img/pies/8.png', __FILE__ ) . '" alt="Score Graph" />';

	$status_description = 'Calculating your SEO score, please refresh this page to view the score. This could take up to 10 minutes.';

	// Create curl resource and retrieve content
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://go.seobot.com/api/2zqvdaj2yrqyr04n2w4yl4nei/scores/' . home_url());
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($output);
	
	$advice = "";
	
	if(isset($json->{'status'})) {
		switch(true) {
			case ($json->{'status'} >= 0 && $json->{'status'} <= 1) :
				$status_description = 'Request failed.';
			break;
			case ($json->{'status'} >= 3 && $json->{'status'} <= 6) :
				$status_description = 'Calculating your score, please refresh this page to view the score. This could take up to 10 minutes.';
			break;
			case ($json->{'status'} == 7) :
				$scores = true;
				$status_description = 'Finished, will recalculate every 14 days.';
				$overall = $json->{'scores'}->{'overall'};
				if($json->{'scores'}->{'overall'} < 61) {
					$advice = "Your current overall scores indicates: Not optimized yet.";
				} elseif($json->{'scores'}->{'overall'} < 71) {
					$advice = "Your current overall scores indicates: Moderately optimized. There is a lot of room for improvement.";
				} elseif($json->{'scores'}->{'overall'} < 81) {
					$advice = "Your current overall scores indicates: Reasonably optimized. You are on the right track. Optimize your website even more to climb a couple of search positions or to strengthen your current search position.";
				} elseif($json->{'scores'}->{'overall'} < 81) {
					$advice = "Your current overall scores indicates: Well optimized. Yet there are still some improvements to be made.";
				} elseif($json->{'scores'}->{'overall'} < 101) {
					$advice = "Your current overall scores indicates: Almost perfectly optimized. Your website is optimized. Don't forget that SEO is a continuous process, meaning you are never completely finished.";
				}
				$onpage = $json->{'scores'}->{'onpage'}; //30
				$structure = $json->{'scores'}->{'websitestructure'}; //15
				$technical = $json->{'scores'}->{'technical'}; //20
				$popularity = $json->{'scores'}->{'popularity'}; //25
				$social = $json->{'scores'}->{'socialmedia'}; //10

				$onpage_score = ($onpage / 30) * 100;

				switch(true) {
					case ($onpage_score >= 0 && $onpage_score <= 20) :
						$onpage_graph = $pies20;
					break;
					case ($onpage_score > 20 && $onpage_score <= 30) :
						$onpage_graph = $pies30;
					break;
					case ($onpage_score > 30 && $onpage_score <= 40) :
						$onpage_graph = $pies40;
					break;
					case ($onpage_score > 40 && $onpage_score <= 50) :
						$onpage_graph = $pies50;
					break;
					case ($onpage_score > 50 && $onpage_score <= 60) :
						$onpage_graph = $pies60;
					break;
					case ($onpage_score > 60 && $onpage_score <= 80) :
						$onpage_graph = $pies80;
					break;
					case ($onpage_score > 80 && $onpage_score <= 90) :
						$onpage_graph = $pies90;
					break;
					case ($onpage_score > 90 && $onpage_score <= 100) :
						$onpage_graph = $pies100;
					break;
				}

				$structure_score = ($structure / 15) * 100;

				switch(true) {
					case ($structure_score >= 0 && $structure_score <= 20) :
						$structure_graph = $pies20;
					break;
					case ($structure_score > 20 && $structure_score <= 30) :
						$structure_graph = $pies30;
					break;
					case ($structure_score > 30 && $structure_score <= 40) :
						$structure_graph = $pies40;
					break;
					case ($structure_score > 40 && $structure_score <= 50) :
						$structure_graph = $pies50;
					break;
					case ($structure_score > 50 && $structure_score <= 60) :
						$structure_graph = $pies60;
					break;
					case ($structure_score > 60 && $structure_score <= 80) :
						$structure_graph = $pies80;
					break;
					case ($structure_score > 80 && $structure_score <= 90) :
						$structure_graph = $pies90;
					break;
					case ($structure_score > 90 && $structure_score <= 100) :
						$structure_graph = $pies100;
					break;
				}

				$technical_score = ($technical / 20) * 100;

				switch(true) {
					case ($technical_score >= 0 && $technical_score <= 20) :
						$technical_graph = $pies20;
					break;
					case ($technical_score > 20 && $technical_score <= 30) :
						$technical_graph = $pies30;
					break;
					case ($technical_score > 30 && $technical_score <= 40) :
						$technical_graph = $pies40;
					break;
					case ($technical_score > 40 && $technical_score <= 50) :
						$technical_graph = $pies50;
					break;
					case ($technical_score > 50 && $technical_score <= 60) :
						$technical_graph = $pies60;
					break;
					case ($technical_score > 60 && $technical_score <= 80) :
						$technical_graph = $pies80;
					break;
					case ($technical_score > 80 && $technical_score <= 90) :
						$technical_graph = $pies90;
					break;
					case ($technical_score > 90 && $technical_score <= 100) :
						$technical_graph = $pies100;
					break;
				}

				$popularity_score = ($popularity / 25) * 100;

				switch(true) {
					case ($popularity_score >= 0 && $popularity_score <= 20) :
						$popularity_graph = $pies20;
					break;
					case ($popularity_score > 20 && $popularity_score <= 30) :
						$popularity_graph = $pies30;
					break;
					case ($popularity_score > 30 && $popularity_score <= 40) :
						$popularity_graph = $pies40;
					break;
					case ($popularity_score > 40 && $popularity_score <= 50) :
						$popularity_graph = $pies50;
					break;
					case ($popularity_score > 50 && $popularity_score <= 60) :
						$popularity_graph = $pies60;
					break;
					case ($popularity_score > 60 && $popularity_score <= 80) :
						$popularity_graph = $pies80;
					break;
					case ($popularity_score > 80 && $popularity_score <= 90) :
						$popularity_graph = $pies90;
					break;
					case ($popularity_score > 90 && $popularity_score <= 100) :
						$popularity_graph = $pies100;
					break;
				}

				$social_score = ($social / 10) * 100;

				switch(true) {
					case ($social_score >= 0 && $social_score <= 20) :
						$social_graph = $pies20;
					break;
					case ($social_score > 20 && $social_score <= 30) :
						$social_graph = $pies30;
					break;
					case ($social_score > 30 && $social_score <= 40) :
						$social_graph = $pies40;
					break;
					case ($social_score > 40 && $social_score <= 50) :
						$social_graph = $pies50;
					break;
					case ($social_score > 50 && $social_score <= 60) :
						$social_graph = $pies60;
					break;
					case ($social_score > 60 && $social_score <= 80) :
						$social_graph = $pies80;
					break;
					case ($social_score > 80 && $social_score <= 90) :
						$social_graph = $pies90;
					break;
					case ($social_score > 90 && $social_score <= 100) :
						$social_graph = $pies100;
					break;
				}
				
				$pdfreport = $json->{'pdf-report'};
			break;
		}
	} else {
		$status_description = 'Error, service currently unavailable.';
	}
	
	?>
	
	<style>
		div.score_element {
			margin-bottom:4px;
			text-align:center;
			width:100%;
			border-width:1px;
			border-style: solid;
			border-top-color:#ccc;
			border-bottom-color:#bbb;
			border-left-color:#ccc;
			border-right-color:#ccc;
			background: #ffffff;
			display: block;
			margin-right:5px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			padding-top:5px;
			padding-bottom: 5px;
			cursor:help;
		}
		
		div.score_element span {
			line-height:16pt;
			text-align:center;
			font-size:8pt;
			padding-bottom: 8px;
		}
		
		div.score_element span.overall_normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		div.score_element span.normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		p.providedby {
			font-size:8pt;
			text-align:right;
		}
		
		div.score_element span.subitem {
			font-size:12pt;
		}
		
		div.score_element span.subitem_small {
			font-size:9pt;
		}
		
		div.score_element span.overall_subitem {
			font-size:14pt;
		}
		
		div.score_element span.overall_subitem_small {
			font-size:11pt;
		}

		div.score_element span.factor_graph img {
			width:100%;
		}
		
		span.score_advice {
			text-decoration:underline;
		}
		
		div#downloadpdf {
			width:100%;
			padding:10px 10px 0px 0px;
		}
		
	</style>
	
	<p>Current status: <?php echo $status_description; ?></p>
	
	<table style="border-collapse: collapse; padding: 0; width:100%; margin-top:5px;">
		<tr>
			<td colspan="9">
				<a title="<?php if ($json->{'status'} == 7) { echo $advice; } ?>"><div class="score_element">
					<span class="overall_normal">Overall Score</span>
					<br />
					<span class="overall_subitem"><?php echo $overall; ?></span>
					<span class="overall_subitem_small"> / 100
					<?php if($scores === true) { ?>
					<br />
					<span class="overall_graph"><img src="<?php echo plugins_url( 'img/bars/' . $overall . '.png', __FILE__ )?>" alt="Overall Score" /></span>
					<?php } ?>
				</div></a>
			</td>
		</tr>
		<tr style="border-bottom:1px solid #BBB;">
			<td>
				<a title="All SEO factors regarding issues that can occur on a Page Specific level. For example, presence of the meta description tag or the proper use of heading tags."><div class="score_element">
					<span class="normal">PS</span>
					<br />
					<span class="subitem"><?php echo $onpage; ?></span>
					<span class="subitem_small"> / 30</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $onpage_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="All SEO factors regarding issues about the internal Website Structure and URLs. For example, do you use search engine friendly URLs."><div class="score_element">
					<span class="normal">WS</span>
					<br />
					<span class="subitem"><?php echo $structure; ?></span>
					<span class="subitem_small"> / 15</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $structure_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="All SEO factors regarding Technical Factors. For example, how fast is your website or are you using a sitemap."><div class="score_element">
					<span class="normal">TF</span>
					<br />
					<span class="subitem"><?php echo $technical; ?></span>
					<span class="subitem_small"> / 20</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $technical_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="Getting a lot of backlinks is good, but getting quality backlinks is more important. This score reviews your Link Building efforts."><div class="score_element">
					<span class="normal">LB</span>
					<br />
					<span class="subitem"><?php echo $popularity; ?></span>
					<span class="subitem_small"> / 25</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $popularity_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="How active are you on Social Media? Even if you don't participate in Social Media, that doesn't mean people dont talk about you."><div class="score_element">
					<span class="normal">SM</span>
					<br />
					<span class="subitem"><?php echo $social; ?></span>
					<span class="subitem_small"> / 10</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $social_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
		</tr>
		<tr>
			<td colspan="9">
				<div id="downloadpdf">
					<form action="<?php echo $pdfreport; ?>" method="post" target="_blank">
						<input type="submit" class="button" value="Download Free Quickscan PDF Report" />
					</form>
				</div>
			</td>
		</tr>
	</table>
	
	<p class="providedby">
		Powered by <a href="https://seobot.com" target="_blank" title="SEO Bot">SEO Bot</a>, created by <a href="https://seology.co.za" target="_blank" title="SEOLOGY">SEOLOGY</a>.
	</p>
	<?php
} 

// Create the function use in the action hook

function seoscore_add_dashboard_widgets() {
	wp_add_dashboard_widget('seoscore_dashboard_widget',  '<img src="' . plugins_url( 'img/small_icon.gif', __FILE__ ) . '" alt="SEO Bot" /> SEO Bot: Search Engine Optimisation Score', 'seoscore_dashboard_widget_function');
	
	// Globalize the metaboxes array, this holds all the widgets for wp-admin

	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashbaord widget from the end of the array

	$example_widget_backup = array('seoscore_dashboard_widget' => $normal_dashboard['seoscore_dashboard_widget']);
	unset($normal_dashboard['seoscore_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning

	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 

	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'seoscore_add_dashboard_widgets' ); // Hint: For Multisite Network Admin Dashboard use wp_network_dashboard_setup instead of wp_dashboard_setup.

?>