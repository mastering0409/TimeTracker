<?php
/**
 * Template Name: Time Tracker Pages
 *
 * Page with Left Sidebar that Includes Links to Important Time Tracker Pages
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Templates;
require_once TT_PLUGIN_DIR_INC . "class-time-tracker-activator-pages.php";
use Logically_Tech\Time_Tracker\Inc as Inc;
use function Logically_Tech\Time_Tracker\Inc\check_page_status;
use function Logically_Tech\Time_Tracker\Inc\log_tt_misc;
use function Logically_Tech\Time_Tracker\Inc\check_for_pagination;
use function Logically_Tech\Time_Tracker\Inc\add_pagination;


$pagination = check_for_pagination();
if ($pagination['Flag'] == true) {
	global $wp_query;
	$current_page = get_query_var( 'paged', 1 ) == 0 ? 1 : get_query_var('paged', 1);
}
?>
<!----------begin header section----------->
	<?php
	get_header();
	?>
<!----------end header section----------->

<!----------begin CONTENT section----------->
<div id="tt-all-content" class="content tt-content">

	<!----------begin sidebar section----------->
		<div id="tt-sidebar" class="sidebar tt-sidebar">
		<?php
		include(TT_PLUGIN_DIR . '/templates/tt-sidebar.php');
		?>
		</div>
	<!----------end sidebar section----------->

	<!----------begin mobile menu section----------->
		<div id="tt-mobile-menu" class="tt-mobile-menu">
			<button class="tt-mobile-menu-button" onclick="tt_open_mobile_menu()">Time Tracker Menu</button>
			<!-- Navigation Links -->
			<div id="tt-nav-links">
				<?php
				include(TT_PLUGIN_DIR . '/templates/tt-sidebar.php')
				?>
			</div>
		</div>
	<!----------end mobile menu section----------->

	<!----------begin primary section----------->
		<div id="tt-primary" class="content-area tt-content">
		
			<!----------begin page header section----------->
				<header id="tt-page-header" class="page-header tt-page-header">
				<h1 class="page-title tt-page-title">
				<?php
				$title = esc_html( get_the_title() );
				if ( ( strpos($title, 'Private:') !== false ) || ( strpos($title, 'Protected:') !== false ) ) {
					$title = substr($title, strpos($title, ": ") +2 );
				}
				echo $title;
				?>
				</h1>
				<?php

				$status = check_page_status(get_the_ID());        
				if ($status !== 'private') {
					?>
					<div class="tt-warning">NOTE: This page may be visible to the public. You may wish to change it to 'private' in the pages menu.</div>
					<?php
					log_tt_misc('Page Visibility: The page named: ' . get_the_title() . ', ID=' . get_the_ID() . ', returned a status of ' . $status);
				}

				?>
				<a href="<?php echo TT_HOME ?>" class="tt-header-button">Home</a>
				</header>
			<!----------end page header----------->
			
			<!----------TT error alert, if necessary----------->
				<?php echo do_shortcode('[tt_error_alert]'); ?>  
			<!----------end TT error alert----------->
			
			<!----------begin main section----------->
				<main id="tt-main" class="site-main tt-main" role="main">

				<!----------begin content section----------->
					
					<div class="page-content tt-page-content">
					
					<!---before content pagination--->
					<?php 
					if ($pagination['Flag'] == true) {
						?>
						<div class="tt-pagination-nav">
						<?php
						echo add_pagination($pagination['RecordCount'], $pagination['RecordsPerPage'], $current_page, $pagination['PreviousText'], $pagination['NextText']);
						?>
						</div>
						<?php					
					}
					?>
					<!---end before content pagination--->
					
					<?php the_content(); ?>
					
					<!---after content pagination--->
					<?php 
					if ($pagination['Flag'] == true) {
						?>
						<div class="tt-pagination-nav">
						<?php
						echo add_pagination($pagination['RecordCount'], $pagination['RecordsPerPage'], $current_page, $pagination['PreviousText'], $pagination['NextText']);
						?>
						</div>
						<?php					
					}
					?>
					<!---end after content pagination--->
					
					</div>
					<!----------end content section----------->
		
				</main>
			<!----------end main section----------->
		
		</div>
	<!----------end primary section----------->

</div>
<!----------end CONTENT section----------->

<!----------begin footer section----------->
	<?php
	get_footer();
	?>
<!----------end primary section----------->