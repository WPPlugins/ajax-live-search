<?php
//This file defines administration fields

if ( ! defined( 'ABSPATH' ) ) {	
	exit; // Exit if accessed directly.
}

if (! function_exists( 'WE_als' ) ) {
	return; //Nothing to do here
}

WE_als( 'ajax-live-search' )->queue_control( 'home', array(
		'type' => 'card',
		'class' => 'col s12 m6 large',
		'card_wrapper_start' => '<div class="elementa-row">',
		'card_wrapper_end' => '</div>',
		'full_width' => true,
		'cards' => array(
			

			'1' => array(
				'card_title'     => 'Premium Version',
				'card_content' => '<ul class="list-group">
					<li class="list-group-item active hoverable"><a title="Click to securely purchase via PayPal." style="text-decoration: none;" class="white-text" href="https://pay.paddle.com/checkout/506334/">$19 BUY NOW</a></li>
					<li class="list-group-item">Earn money by displaying your affiliate links / ads as sponsored results. This feature has increased our revenue by 300%.</li>					
					<li class="list-group-item">Speed up your website by loading query suggestions from Google etc.</li>
					<li class="list-group-item">Cache search results for even faster speeds.</li>
					<li class="list-group-item">Chose from 10+ Preloader images when loading results via ajax/live search.</li>
					<li class="list-group-item">Informs Google analytics about your visitor searches when you enable live search or ajax search feature.</li>
					<li class="list-group-item">Shows your users a "Did you Mean" suggestion when their search returns few results.</li>
					<li class="list-group-item active hoverable"><a title="Click to securely purchase via PayPal." style="text-decoration: none;" class="white-text" href="https://pay.paddle.com/checkout/506334/">$19 BUY NOW</a></li>
				</ul>',
			),
			
			
			'2' => array(
				'card_title'     => 'Important!',
				'class'     => 'card-inverse bg-inverse',
				'card_content' => "Both Ajax and Live search won't work unless you configure your theme to support it. <a class='card-link' href='https://ajaxlivesearch.xyz/getting-started/#theme-compatibility'>This tutorial</a> shows you how to configure it.",
			),
			
			'3' => array(
				'card_title'     => 'Review',
				'card_content' => "Loving this plugin? Why not give us a 5* review on <a class='card-link' href='https://wordpress.org/plugins/ajax-live-search'>WordPress.org</a>. We would appreciate it.",
			),
		),
		'section' =>'Home',
	) );
	
$total_searches = (int) als_lite()->search->total_searches();
$searches_without_results = (int) als_lite()->search->total_searches( 'hits = 0' );
$searches_with_results = (int) als_lite()->search->total_searches('hits > 0');

	WE_als( 'ajax-live-search' )->queue_control( 'als-home-stats', array(
		'type' => 'card',
		'class' => 'col s12 m4 align-centre',
		'card_wrapper_start' => '<div class="elementa-row">',
		'card_wrapper_end' => '</div>',
		'full_width' => true,
		'cards' => array(
			
			'1' => array(
				'card_class' => 'blue white-text',
				'card_content' => "<span style='font-size: 2rem; display:block; margin-bottom: 1rem; min-height: 40px;'>$total_searches</span> Total Searches",
			),
			
			'2' => array(
				'card_class' => 'blue  white-text',
				'card_content' => "<span style='font-size: 2rem; display:block; margin-bottom: 1rem;'>$searches_without_results</span> Searches Without Results",
			),
			
			'3' => array(
				'card_class' => 'blue white-text',
				'card_content' => "<span style='font-size: 2rem; display:block; margin-bottom: 1rem; min-height: 40px;'>$searches_with_results</span> Searches With Results",
			),
			
		),
		'section' =>'Statistics',
	) );

	WE_als( 'ajax-live-search' )->queue_control( 'als-home-stats', array(
		'type' => 'card',
		'full_width' => true,
		'cards' => array(
			
			'1' => array(
				'card_title' => 'Popular Searches',
				'class' => 'rounded-right rounded-0 ',
				'card_content' => als_lite()->search->show_searches('WHERE 1=1 ORDER BY searches DESC limit 6', false ),
			
			),	

			'2' => array(
				'card_title' => 'Searches with most results',
				'class' => 'rounded-right rounded-0 ',
				'card_content' => als_lite()->search->show_searches( 'WHERE hits > 0 ORDER BY hits DESC limit 10' , false ),
			
			),
			
			'3' => array(
				'card_title' => 'Searches with least results',
				'class' => 'rounded-right rounded-0 ',
				'card_content' => als_lite()->search->show_searches( 'WHERE hits > 0 ORDER BY hits ASC limit 10' , false ),
			
			),
		),
		'section' =>'Statistics',
	) );

WE_als( 'ajax-live-search' )->queue_control( 'enable-autocomplete', array (
		'type' => 'switch',
		'title' => __( 'Autocomplete', 'ajax-live-search' ),
		'description' => __( 'Automatically completes the current query being typed in the search box', 'ajax-live-search' ),
		'default'  => '1',
		'enabled'  => 'Enabled',
        'disabled' => 'Disabled',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-autocomplete-count', array (
		'type' => 'number',
		'description' => __( 'Number of autocomplete suggestions', 'ajax-live-search' ),
		'title' => __( 'Count', 'ajax-live-search' ),
		'default'  => '5',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-autocomplete-engine', array (
		'type' => 'select',
		'description' => __( 'Engine used to load autocomplete suggestions', 'ajax-live-search' ),
		'title' => __( 'Engine', 'ajax-live-search' ),
		'default'  => 'google',
		'options'  => apply_filters( 'als-autocomplete-engines', array(
                    'local' => __( 'local database', 'ajax-live-search' ),
                )),
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-autocomplete-table', array (
		'type' => 'select',
		'description' => __( 'Database table to be used in case engine is set to Local database.', 'ajax-live-search' ),
		'title' => __( 'DB Table', 'ajax-live-search' ),
		'default'  => 'posts',
		'options'  => array(
                    'posts' => __( 'Post titles', 'ajax-live-search' ),
                    'previous' => __( 'Previous searches', 'ajax-live-search' ),
                ),
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'border-autocomplete', array (
		'type' => 'color',
		'description' => __( 'Set to empty if you don\'t want a border around the main container', 'ajax-live-search' ),
		'title' => __( 'Main Border', 'ajax-live-search' ),
		'default'  => '#ccc',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'border-single-autocomplete', array (
		'type' => 'color',
		'description' => __( 'Set to empty if you don\'t want a border above each displayed result', 'ajax-live-search' ),
		'title' => __( 'Secondary Border', 'ajax-live-search' ),
		'default'  => '#f2f2f2',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
	
WE_als( 'ajax-live-search' )->queue_control( 'autocomplete-background', array (
		'type' => 'color',
		'title' => __( 'Background', 'ajax-live-search' ),
		'default'  => '#fff',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'autocomplete-suggestion-color', array (
		'type' => 'color',
		'description' => __( 'Styles the shown suggestions', 'ajax-live-search' ),
		'title' => __( 'Color', 'ajax-live-search' ),
		'default'  => '#333',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
	
WE_als( 'ajax-live-search' )->queue_control( 'autocomplete-suggestion-matched', array (
		'type' => 'color',
		'description' => __( 'Styles the matched part of the autocomplete results.', 'ajax-live-search' ),
		'title' => __( 'Secondary Color', 'ajax-live-search' ),
		'default'  => '#1f8dd6',
		'full_width' => false,
		'section'  => 'Query Completion',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'index-opt-info', array (
		'type' => 'alert',
		'text' => __( 'It may take sometime before all posts start showing up in the search results.', 'ajax-live-search' ),
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-index-batches', array (
		'type' => 'number',
		'description' => __( 'Number of posts to periodically index.', 'ajax-live-search' ),
		'title' => __( 'Batches', 'ajax-live-search' ),
		'default'  => '10',
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'fields-to-index', array (
		'type' => 'multiselect',
		'description' => __( 'Select the fields that should be added to the index.', 'ajax-live-search' ),
		'title' => __( 'Fields', 'ajax-live-search' ),
		'default'  => array( 'title', 'content', 'excerpt'  ),
		'options'  => array(
                    'title' => 'Title',
                    'content' => 'Content',
                    'comment' => 'Comments',
					'excerpt' => 'Excerpts',
                    'url' => 'Permalinks',
                    
                ),
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'searchable-post-types', array (
		'type' => 'multiselect',
		'description' => __( 'Limit results to this post types.', 'ajax-live-search' ),
		'title' => __( 'Post Types', 'ajax-live-search' ),
		'data'     => 'post_type',
		'default'  => array( 'attachment', 'page', 'post', 'product'  ),
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'excluded-cats', array (
		'type' => 'multiselect',
		'description' => __( 'Exclude posts from these categories', 'ajax-live-search' ),
		'title' => __( 'Categories', 'ajax-live-search' ),
		'data'     => 'categories',
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'excluded-tags', array (
		'type' => 'multiselect',
		'description' => __( 'Exclude posts with these tags', 'ajax-live-search' ),
		'title' => __( 'Tags', 'ajax-live-search' ),
		'data'     => 'tags',
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'excluded-user-posts', array (
		'type' => 'multiselect',
		'description' => __( 'Exclude posts by these authors', 'ajax-live-search' ),
		'title' => __( 'Authors', 'ajax-live-search' ),
		'data'     => 'users',
		'full_width' => false,
		'section'  => 'Indexing',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-ajax-search', array (
		'type' => 'switch',
		'description' => __( 'Results will be loaded without reloading the page.', 'ajax-live-search' ),
		'title' => __( 'Ajax Search', 'ajax-live-search' ),
		'default'  => '0',
		'full_width' => false,
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-live-search', array (
		'type' => 'switch',
		'description' => __( 'Results will be shown while the user types.', 'ajax-live-search' ),
		'title' => __( 'Live Search', 'ajax-live-search' ),
		'default'  => '0',
		'full_width' => false,
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-live-search-loop-file', array (
		'type' => 'text',
		'description' => __( 'Path to the file that your theme uses to render search results.', 'ajax-live-search' ) . '<a href="https://ajaxlivesearch.xyz/getting-started/#theme-compatibility"> Confused?</a>',
		'title' => __( 'Template', 'ajax-live-search' ),
		'default'  => 'template-parts/content-search',
		'hint'  => 'Needed for ajax and live search to work.',
		'full_width' => false,
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-snippet-enable', array (
		'type' => 'switch',
		'description' => __( 'Try turning this off if searches starting slowing down', 'ajax-live-search' ),
		'title' => __( 'Snippets', 'ajax-live-search' ),
		'subtitle' => __( 'Enable custom snippets', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-snippet-highlight', array (
		'type' => 'switch',
		'title' => __( 'Highlight', 'ajax-live-search' ),
		'subtitle' => __( 'Highlight custom snippets', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-snippet-color', array (
		'type' => 'color',
		'subtitle' => __( 'Highlight color', 'ajax-live-search' ),
		'title' => __( 'Color', 'ajax-live-search' ),
		'default'  => '#000',
		'full_width' => false,
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-snippet-length', array (
		'type' => 'number',
		'subtitle' => __( 'Length of custom snippets', 'ajax-live-search' ),
		'title' => __( 'Length', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '180',
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-snippet-tags', array (
		'type' => 'textarea',
		'textarea_rows' => 3,
		'description' => __( 'HTML tags that should be preserved in the snippet.', 'ajax-live-search' ),
		'title' => __( 'Tags', 'ajax-live-search' ),
		'subtitle' => __( 'Allowed HTML tags', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => 'strong, a',
		'section'  => 'Searching',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-fuzzy-search', array (
		'type' => 'switch',
		'title' => __( 'Fuzzy Search', 'ajax-live-search' ),
		'description' => __( 'This allows us to fallback to fuzzy search when normal search produces no results', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-wordpress-search', array (
		'type' => 'switch',
		'title' => __( 'WordPress Search', 'ajax-live-search' ),
		'description' => __( 'This allows us to fallback to the inbuilt search when normal search produces no results', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Ranking',
	) );

	
WE_als( 'ajax-live-search' )->queue_control( 'als-favour-new', array (
		'type' => 'switch',
		'title' => __( 'Favor  new content', 'ajax-live-search' ),
		'description' => __( 'Newer content will be assigned higher wheights using linear regression', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-favour-popular', array (
		'type' => 'switch',
		'title' => __( 'Favor  popular', 'ajax-live-search' ),
		'description' => __( 'Popular content will be assigned higher wheights using linear regression', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-order-by', array (
		'type' => 'select',
		'title' => __( 'Order By', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => 'post__in',
		'options'  => array(
                    'post__in' => __( 'Relevance (recommended)', 'ajax-live-search' ),
                    'title' => __( 'Title', 'ajax-live-search' ),
                    'date' => __( 'Date Published', 'ajax-live-search' ),
					'rand' => __( 'Random', 'ajax-live-search' ),
                    'comment_count' => __( 'Popularity', 'ajax-live-search' ),
					'type' => __( 'Post Type', 'ajax-live-search' ),
					'author' => __( 'Author', 'ajax-live-search' ),
                    
                ),
		'section'  => 'Ranking',
	) );

WE_als( 'ajax-live-search' )->queue_control( 'als-post-type-weight-title', array (
		'type' => 'title',
		'title' => __( 'Weights', 'ajax-live-search' ),
		'subtitle' => __( 'Select how individual content should be weighted', 'ajax-live-search' ),
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-title-weight', array (
		'type' => 'select',
		'title' => __( 'Title', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '10',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-content-weight', array (
		'type' => 'select',
		'title' => __( 'Content', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '2',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
		
WE_als( 'ajax-live-search' )->queue_control( 'als-excerpt-weight', array (
		'type' => 'select',
		'title' => __( 'Excerpt', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '3',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
			
WE_als( 'ajax-live-search' )->queue_control( 'als-url-weight', array (
		'type' => 'select',
		'title' => __( 'Permalink', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '15',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-comment-weight', array (
		'type' => 'select',
		'title' => __( 'Comment', 'ajax-live-search' ),
		'full_width' => false,
		'default'  => '1',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
	
WE_als( 'ajax-live-search' )->queue_control( 'als-post-type-weight-title', array (
		'type' => 'title',
		'title' => __( 'Post Types', 'ajax-live-search' ),
		'subtitle' => __( 'Select how individual post types should be weighted', 'ajax-live-search' ),
		'section'  => 'Ranking',
	) );
	
$post_types = WE_als( 'ajax-live-search' )->get_data( 'post_type' );
foreach( $post_types as $slug => $label ){
	
	WE_als( 'ajax-live-search' )->queue_control( "als-$slug-weight", array (
		'type' => 'select',
		'title' => $label,
		'full_width' => false,
		'default'  => '1',
		'options' => array_combine( range(1, 20), range(1, 20)),
		'section'  => 'Ranking',
	) );
	
}

	WE_als( 'ajax-live-search' )->queue_control( 'als-import', array (
		'type' => 'import',
		'title' => 'Import/Export settings',
		'section' => 'import / Export'
	) );
	
	WE_als( 'ajax-live-search' )->queue_control( 'als-save2', array (
		'type' => 'save',
		'value' => 'Save',
		'full_width' => true,
	) );