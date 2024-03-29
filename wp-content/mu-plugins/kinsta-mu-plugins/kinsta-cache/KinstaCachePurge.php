<?php

namespace Kinsta;
class KinstaCachePurge {

    var $KinstaCache;
    var $purge_urls;
    var $has_object_cache;

    function __construct( $KinstaCache ) {
        $this->KinstaCache = $KinstaCache;
        $this->set_purge_urls();
        $this->set_has_object_cache();

        // A post is published
        // A post is updated
        // A post is unpublished
        add_action( 'transition_post_status', array( $this, 'post_actions' ), 10, 3 );

        // A comment is inserted
        add_action( 'wp_insert_comment', array( $this, 'comment_posted_action' ), 10, 2 );

        // A comment is edited
        add_action( 'edit_comment', array( $this, 'comment_edited_action' ) );

        // Unapproved comment is approved
        // approved comment is unapproved or deleted
        add_action( 'transition_comment_status', array( $this, 'comment_transitioned_action' ), 10, 3 );

        // Purge all on menu update
        add_action( 'wp_update_nav_menu', array( $this, 'purge_all' ) );
    }
/* DEBUG */

    function kprint($arr) {
        $return = preg_replace('/(\[.+\])\s+=>\s+Array\s+\(/msiU','$1 => Array (', print_r($arr,true));

        $return = str_replace('    )', ')', $return);
        $return = str_replace('  ', ' ', $return);
        $return = explode( "\n", $return );
        $return = array_slice( $return, 2, -3 );
        $i = 0;
        foreach( $return as $line ) {
            $return[$i] = substr( $line, 2 );
            $return[$i] = str_replace( ' => Array (', '', $return[$i]);
            $return[$i] = preg_replace( '/\[[0-9*?]\] => /', '', $return[$i] );
            $return[$i] = preg_replace( '/\[(.*?)\]/', '$1', $return[$i] );
            if( $return[$i] === '' ) {
                unset( $return[$i] );
            }
            elseif( str_replace( ' ', '', $return[$i] ) === ')' ) {
                unset( $return[$i] );
            }

            $i++;
        }
        $return = implode("\n", $return);

        echo '<div style="white-space:pre">' . $return . '</div>';
    }

    function show_debug_info( $info ) {
        global $wp_filter;

        $possible_filters = array( 'kinsta-cache/purge_list', 'kinsta-cache/purge_groups', 'kinsta-cache/purge_urls' );

        ?>
        <style media="screen">
            li.hidden {
                display: none;
            }
        </style>
        <div style="background:#333; color: #fff; position: absolute; top:0px; left:0px; width:100%; min-height:100%; font-family: Monaco, monospace; line-height:1.6em">
            <div style="padding:44px">
                <div style="display:flex; align-items: center; margin-bottom:44px;">
                    <img style="margin-right:11px" width="22px" src="<?php echo plugin_dir_url( __FILE__ ) ?>../shared/images/icon.svg">
                    <div style="font-size:22px">KINSTA CACHE DEBUG</div>
                </div>

                <div style="font-size:22px; display:flex; align-items:center; position:relative;">
                    <div style="left: -55px; position:absolute;width:44px; height:4px; background:#5CC0C0 "></div>
                    <div>Filters</div>
                </div>

                <?php if( empty( $wp_filter['kinsta-cache/purge_list'] ) && empty( $wp_filter['kinsta-cache/purge_groups'] ) ) : ?>
                    <p>No filters have been added that would modify our purge groups or purge list.</p>
                <?php else : ?>
                    <p>The data shown on this page contains the effect of the filters below. Use remove_filter() to see what happens if these filters aren't there.</p>

                    <?php
                        foreach( $possible_filters as $filter ) {
                            if( empty( $wp_filter[$filter]) ) {
                                continue;
                            }
                            echo '<div style="margin-top:22px">' . $filter . '</div>';
                            echo '<ul>';
                            foreach( $wp_filter[$filter]->callbacks as $priorities ) {
                                $hooked_functions = array_values( $priorities );
                                foreach( $hooked_functions as $hooked ) {
                                    if( is_array( $hooked['function'] ) ) {
                                        echo '<li>' . get_class( $hooked['function'][0] ) . '->' . $hooked['function'][1] . '</li>';
                                    }
                                    else {
                                        echo '<li>' . $hooked['function'] . '</li>';
                                    }
                                }
                            }
                            echo '</ul>';
                        }
                    ?>
                <?php endif ?>


                <div style="font-size:22px; display:flex; align-items:center; position:relative; margin-top:44px">
                    <div style="left: -55px; position:absolute;width:44px; height:4px; background:#5CC0C0 "></div>
                    <div>Purged URLs</div>
                </div>

                <?php if( empty($info['filtered_purge_list']) ) : ?>
                    <p>The filtered purge list is empty</p>
                <?php else : ?>

                <script type="text/javascript">
                    function searchUrls( val ) {
                        items = document.getElementById('purgedUrlList').children
                        for( i = 0; i< items.length; ++i ) {
                            if( items[i].getAttribute('data-url').includes(val) ) {
                                items[i].setAttribute('class', '');
                            }
                            else {
                                items[i].setAttribute('class', 'hidden');
                            }
                        }
                    }
                    function clearSearchUrls() {
                        items = document.getElementById('purgedUrlList').children
                        for( i = 0; i< items.length; ++i ) {
                            items[i].setAttribute('class', '');
                        }

                    }
                    function initiateSearchUrls() {
                        val = document.getElementById('purgedUrls').value
                        if( typeof val === 'undefined' || val === null || val === '' ) {
                            clearSearchUrls();
                        }
                        else {
                            searchUrls( val )
                        }
                    }
                </script>
                <input id="purgedUrls" type='text' style="width:400px; padding:11px; margin-top:22px; margin-left:22px; margin-bottom:11px;" placeholder="Search URLs" onKeyUp="initiateSearchUrls()">

                <ul id="purgedUrlList" style="color:#777">
                    <?php
                        foreach( $info['filtered_purge_list'] as $url ) :

                        $base_url = home_url();
                        $scheme = strpos( $base_url, '://' );
                        $base_path = substr( $base_url, $scheme + 3 );

                        $final_path = str_replace($base_path, '', substr( $url, strpos( $url, '://') + 3 ));
                        $final_url = '<span style="color:#fff">' . substr( $url, 0, strpos( $url, '://') + 3 ) . '</span>' . $base_path . '<span style="color:#fff">' . $final_path . '</span>' ;

                    ?>
                        <li data-url="<?php echo $url ?>"><?php echo $final_url ?></li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>

                <div style="font-size:22px; display:flex; align-items:center; position:relative; margin-top:44px">
                    <div style="left: -55px; position:absolute;width:44px; height:4px; background:#5CC0C0 "></div>
                    <div>Request Paths</div>
                </div>

                <?php if( empty($info['filtered_purge_urls']) ) : ?>
                    <p>There are no request paths</p>
                <?php else : ?>

                <script type="text/javascript">
                    function searchRequests( val ) {
                        items = document.getElementById('requestList').children
                        for( i = 0; i< items.length; ++i ) {
                            if( items[i].getAttribute('data-url').includes(val) ) {
                                items[i].setAttribute('class', '');
                            }
                            else {
                                items[i].setAttribute('class', 'hidden');
                            }
                        }
                    }
                    function clearRequests() {
                        items = document.getElementById('requestList').children
                        for( i = 0; i< items.length; ++i ) {
                            items[i].setAttribute('class', '');
                        }

                    }
                    function initiateSearchRequests() {
                        val = document.getElementById('requestSearch').value
                        if( typeof val === 'undefined' || val === null || val === '' ) {
                            clearRequests();
                        }
                        else {
                            searchRequests( val )
                        }
                    }
                </script>
                <input id="requestSearch" type='text' style="width:400px; padding:11px; margin-top:22px; margin-left:22px; margin-bottom:11px;" placeholder="Search Requests" onKeyUp="initiateSearchRequests()">

                <ul id="requestList" style="color:#777">
                    <?php
                        $purge_paths = array();
                        $purge_paths_colored = array();
                        foreach( $this->KinstaCache->config['purge_urls'] as $path ) {
                            $purge_paths[] = str_replace( home_url(), '', $path);
                            $purge_paths_colored[] = '<span style="color:#B1DE90">' . str_replace( home_url(), '', $path) . '</span>';
                        }

                        foreach( $info['filtered_purge_urls'] as $url ) :

                        $base_url = home_url();
                        $scheme = strpos( $base_url, '://' );
                        $base_path = substr( $base_url, $scheme + 3 );

                        $final_path = str_replace($base_path, '', substr( $url, strpos( $url, '://') + 3 ));
                        $final_url = '<span style="color:#fff">' . substr( $url, 0, strpos( $url, '://') + 3 ) . '</span>' . $base_path . '<span style="color:#fff">' . $final_path . '</span>' ;
                        $final_url = str_replace( $purge_paths, $purge_paths_colored, $final_url );

                    ?>
                        <li data-url="<?php echo $url ?>"><?php echo $final_url ?></li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>

                <div style="font-size:22px; display:flex; align-items:center; position:relative; margin-top:44px; margin-bottom:22px;">
                    <div style="left: -55px; position:absolute;width:44px; height:4px; background:#5CC0C0 "></div>
                    <div>Purge Groups</div>
                </div>

                <?php $this->kprint($info['filtered_purge_groups']); ?>

            </div>
        </div>

        <?php
    }

/* GETTERS AND SETTERS */

    /**
     * Set the has_object_cache property value
     *
     * The property will be true if the object-cache.php file exists in the
     * content directory.
     *
     */
    function set_has_object_cache() {
        $this->has_object_cache = false;
        if( file_exists( WP_CONTENT_DIR . '/object-cache.php') ) {
            $this->has_object_cache = true;
        }
    }

    /**
     * Set purge_urls property value
     *
     * The value comes from the config of the whole plugin which is defined in
     * kinsta-cache.php and is used in building the KinstaCache object
     *
     */
    function set_purge_urls() {
        $this->initiate_purge_urls = $this->KinstaCache->config['purge_urls'];
    }


/* POST AND COMMENT ACTIONS THAT INITIATE PURGING */


    /**
     * Post actions
     *
     * This function is fired whenever a post's status is transitioned. We also
     * detect if an event is needed at all. If there are no 'post added' events
     * checked in the settings we don't do anything.
     *
     * Publish events happen when the post status transitions from anything except
     * publish to publish
     *
     * Updates happen when a post transitions from publish to publish
     *
     * Unpublish events happen when a post transitions from publish to anything
     * except publish.
     *
     * In each case the purge function is called with the action and the post ID
     *
     */
    function post_actions( $new_status, $old_status, $post ) {
        // A new post has been published
        if( $new_status == 'publish' && $old_status != 'publish' && $this->is_event_used( 'post_added' ) ) {
            $this->initiate_purge( 'post_added', $post->ID );
        }
        // A published post is updated
        if( $new_status == 'publish' && $old_status == 'publish' && $this->is_event_used( 'post_modified' )  ) {
            $this->initiate_purge( 'post_modified', $post->ID );
        }
        // A published post is unpublished
        if( $new_status != 'publish' && $old_status == 'publish' && $this->is_event_used( 'post_unpublished' ) ) {
            $this->initiate_purge( 'post_unpublished', $post->ID );
        }
    }

    /**
     * Comment inserted action
     *
     * This function is fired whenever a comment is inserted.  We also
     * detect if an event is needed at all. If there are no 'comment added'
     * events checked in the settings we don't do anything
     *
     * The purge function is called with the action and the post ID
     *
     */
    function comment_posted_action( $comment_id, $comment ) {
        // An approved comment is inserted
        if( $comment->comment_approved == 1 && $this->is_event_used( 'comment_added' ) ) {
            $this->initiate_purge( 'comment_added', $comment->comment_post_ID );
        }
    }

    /**
     * Comment edited action
     *
     * This function is fired whenever a comment is edited.  We also
     * detect if an event is needed at all. If there are no 'comment modified'
     * events checked in the settings we don't do anything
     *
     * The purge function is called with the action and the post ID
     *
     */
    function comment_edited_action( $comment_id ) {
        // An comment is edited
        if( $this->is_event_used( 'comment_modified' ) ) {
            $comment = get_comment( $comment_id );
            $this->initiate_purge( 'comment_modified', $comment->comment_post_ID );
        }
    }

    /**
     * Comment status transition action
     *
     * This function is fired whenever a comment status is transitioned.  We also
     * detect if an event is needed at all. If there are no 'comment published'
     * or 'comment unpublished' events checked in the settings (respectively)
     * we don't do anything.
     *
     * When a comment is approved its status changes to approved from anything
     * except approved.
     *
     * When a comment is unapproved its comment changes from approved to anything
     * except approved.
     *
     * The purge function is called with the action and the post ID
     *
     */
    function comment_transitioned_action( $new_status, $old_status, $comment ) {
        // Unapproved comment is approved
        if( $new_status == 'approved' && $old_status != 'approved' && $this->is_event_used( 'comment_published' ) ) {
            $this->initiate_purge( 'comment_added', $comment->comment_post_ID );
        }

        // approved comment is unapproved or deleted
        if( $new_status != 'approved' && $old_status == 'approved' && $this->is_event_used( 'comment_unpublished' )) {
            $this->initiate_purge( 'comment_unpublished', $comment->comment_post_ID );
        }
    }

    /* FULLE PAGE CACGE PURGE RELATED FUNCTIONS */

    /**
     * Purge Initiation
     *
     * This function is called whenever an action is taken. It compiles all the
     * URLs that need to be purged. The various functions used take user
     * settings into account.
     *
     * For example, if the user sets the plugin to not purge the static home
     * page no URL is returned.
     *
     * Two hooks are available for modifying the list of purged elements.
     * kinsta-cache/purge_groups can be used to modify the segmented array.
     * This can not be used for adding custom URLs, only the modification of
     * existing structures like date, author, taxonomy archives etc.
     *
     * The kinsta-cache/purge_list filter can be used to add/modify individual
     * purge URLs.
     */
    function initiate_purge( $event, $post_id ) {
        $post = get_post( $post_id );
        if( $post->post_type == 'nav_menu_item' ) {
            return;
        }

        $purge_groups = array();

        // Static home page purging
        $purge_groups['static_home'] = $this->purge_url_static_home();
        $purge_groups['blog'] = $this->purge_url_blog( $event );
        $purge_groups['taxonomies'] = $this->purge_url_taxonomies( $event, $post );
        $purge_groups['date_archives'] = $this->purge_url_date_archives( $event, $post );
        $purge_groups['author_archives'] = $this->purge_url_author_archives( $event, $post );
        $purge_groups['post_type'] = $this->purge_url_post_type( $event, $post );
        $purge_groups['single'] = $this->purge_url_single( $event, $post );

        $filtered_purge_groups = apply_filters( 'kinsta-cache/purge_groups', $purge_groups );
        $purge_list = $this->build_purge_list( $filtered_purge_groups );

        $filtered_purge_list = apply_filters( 'kinsta-cache/purge_list', $purge_list );

        $purge_urls = $this->build_purge_urls( $filtered_purge_list );
        $filtered_purge_urls = apply_filters( 'kinsta-cache/purge_urls', $purge_urls );

        if( defined( 'KINSTA_CACHE_DEBUG' ) ) {
            $info = array(
                'filtered_purge_groups' => $filtered_purge_groups,
                'filtered_purge_list' => $filtered_purge_list,
                'filtered_purge_urls' => $filtered_purge_urls,
            );
            $this->show_debug_info( $info );
            exit();
        }

        foreach( $filtered_purge_urls as $purge_url ) {
            $result = wp_remote_get( $purge_url, array(
	         	'headers' => array(
		         	'Accept-Encoding' => 'gzip, deflate, sdch'
	         	),
                'timeout' => 4,
	         	'sslverify' => false
            ));
        }
    }

    function detect_scheme( $purge_list = array() ) {
        $site_url_scheme = parse_url( home_url(), PHP_URL_SCHEME );

        if( $site_url_scheme === 'https' ) {
            return 'https';
        }

        $response = wp_remote_head( home_url( '', 'https'), array(
            'timeout' => 3
        ));

        if( !is_wp_error( $response ) ) {
            return 'https';
        }

        return 'http';
    }

    function get_scheme() {
        if( $this->KinstaCache->settings['options']['https'] === 'auto' ) {
            return $this->detect_scheme();
        }
        else {
            return $this->KinstaCache->settings['options']['https'];
        }
    }

    function build_purge_urls( $purge_list ) {
        $scheme = $this->get_scheme();

        $search = array( home_url('', 'http'), home_url( '', 'https') );
        $replace = home_url( '', $scheme );

        foreach( $this->initiate_purge_urls as $key => $url ) {
            $this->initiate_purge_urls[$key] = str_replace( $search, $replace, $url );
        }

        $purge_urls = array();
        foreach( $purge_list as $url ) {
            $purge_urls[] = str_replace( $search, $this->initiate_purge_urls['path'], $url );
            if( $this->KinstaCache->settings['options']['has_mobile_plugin'] ) {
                $purge_urls[] = str_replace( $search, $this->initiate_purge_urls['mobilepath'], $url );
            }
        }

        return $purge_urls;
    }


    function build_purge_list( $purge_urls ) {

        $purge_list = array();

        if( !empty( $purge_urls['static_home'] ) ) {
            $purge_list[] = $purge_urls['static_home'];
        }

        if( !empty( $purge_urls['blog'] ) ) {
            $purge_list[] = $purge_urls['blog']['url'];
            $purge_list = array_merge( $purge_list, $purge_urls['blog']['archives'] );
            $purge_list = array_merge( $purge_list, $purge_urls['blog']['feeds'] );
        }

        if( !empty( $purge_urls['taxonomies'] ) ) {
            foreach( $purge_urls['taxonomies'] as $taxonomy ) {
                foreach( $taxonomy as $term ) {
                    $purge_list[] = $term['url'];
                    $purge_list = array_merge( $purge_list, $term['archives'] );
                    $purge_list = array_merge( $purge_list, $term['feeds'] );
                }
            }
        }

        if( !empty( $purge_urls['date_archives'] ) ) {
            foreach( $purge_urls['date_archives'] as $archive ) {
                $purge_list[] = $archive['url'];
                $purge_list = array_merge( $purge_list, $archive['archives'] );
                $purge_list = array_merge( $purge_list, $archive['feeds'] );
            }
        }

        if( !empty( $purge_urls['author_archives'] ) ) {
            $purge_list[] = $purge_urls['author_archives']['url'];
            $purge_list = array_merge( $purge_list, $purge_urls['author_archives']['archives'] );
            $purge_list = array_merge( $purge_list, $purge_urls['author_archives']['feeds'] );
        }

        if( !empty( $purge_urls['post_type'] ) ) {
            $purge_list[] = $purge_urls['post_type']['url'];
            $purge_list = array_merge( $purge_list, $purge_urls['post_type']['archives'] );
            $purge_list = array_merge( $purge_list, $purge_urls['post_type']['feeds'] );
        }

        if( !empty( $purge_urls['single'] ) ) {
            $purge_list = array_merge( $purge_list, $purge_urls['single'] );
        }


        return $purge_list;
    }


    function purge_url_single( $event, $post ) {
        if( empty( $this->KinstaCache->settings['rules']['post'][$event] ) ) {
            return;
        }

        $single = array();
        $url = get_permalink( $post->ID );
        $single[] = $url;

        $nextpages = substr_count( $post->post_content, '<!--nextpage-->' );

        if( $nextpages > 0 ) {
            for( $i = 1; $i<=$nextpages; $i++ ) {
                $single[] = $url . $i . '/';
            }
        }


        return $single;
    }

    /**
     * Gets the URL of the static home page for purging. Only returns a url
     * if there is a static home page and it is set to be purged
     */
    function purge_url_static_home() {
        if( $this->KinstaCache->settings['options']['purge_static_home'] == true && get_option( 'page_on_front' ) != 0 ) {
            return get_permalink( get_option( 'page_on_front' ) );
        }
    }

    /**
     * Gets the URL of the blog and archive pages that need to be purged
     */
    function purge_url_blog( $event ) {
        if( empty( $this->KinstaCache->settings['rules']['blog'][$event] ) ) {
            return;
        }

        $blog_archives = array();

        $blog_url = ( get_option( 'page_for_posts' ) != 0 ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url();

        $blog_url = ( substr( $blog_url, -1 ) == '/' ) ? $blog_url : $blog_url . '/';

        $blog_archives['url'] = $blog_url;
        $blog_archives['archives'] = array();
        $blog_archives['feeds'] = array();

        $pages = ceil( $this->count_posts() / get_option( 'posts_per_page' ) );
        $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_blog'] );

        for( $i = 2; $i <= $max_pages; $i++ ) {
            $blog_archives['archives'][] = $blog_url . 'page/' . $i;
        }

        if( $this->KinstaCache->settings['options']['purge_blog_feeds'] ) {
            $blog_archives['feeds'][] = $blog_url . 'feed/';
            $blog_archives['feeds'][] = $blog_url . 'rdf/';
            $blog_archives['feeds'][] = $blog_url . 'atom/';
        }

        return $blog_archives;
    }


    function purge_url_taxonomies( $event, $post ) {
        if( empty( $this->KinstaCache->settings['rules']['archive'][$event] ) ) {
            return;
        }

        $taxonomy_urls = array();

        $taxonomies = get_taxonomies();
        unset( $taxonomies['nav_menu'] );
        unset( $taxonomies['link_category'] );
        $taxonomies = array_values( $taxonomies );

        $terms = wp_get_object_terms( $post->ID, $taxonomies );

        // Term Archives
        if( !empty( $terms ) ) {
            foreach( $terms as $term ) {
                $term_link = get_term_link( $term );

                $taxonomy_urls[$term->taxonomy][$term->slug]['url'] = $term_link;
                $taxonomy_urls[$term->taxonomy][$term->slug]['archives'] = array();
                $taxonomy_urls[$term->taxonomy][$term->slug]['feeds'] = array();
                $archive_count = $this->count_posts( array(
                    'tax_query' => array(
                        array(
                            'taxonomy' => $term->taxonomy,
                            'field'    => 'id',
                            'terms'    => $term->term_id,
                        ),
                    ),
                ));

                $pages = ceil( $archive_count / get_option( 'posts_per_page' ) );
                $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_archives'] );

                for( $i = 2; $i <= $max_pages; $i++ ) {
                    $taxonomy_urls[$term->taxonomy][$term->slug]['archives'][] = $term_link . 'page/' . $i;
                }

                if( $this->KinstaCache->settings['options']['purge_archive_feeds'] == true ) {
                    $taxonomy_urls[$term->taxonomy][$term->slug]['feeds'][] = $term_link . 'feed/';
                    $taxonomy_urls[$term->taxonomy][$term->slug]['feeds'][] = $term_link . 'atom/';
                    $taxonomy_urls[$term->taxonomy][$term->slug]['feeds'][] = $term_link . 'rdf/';
                }

            }
        }

        return $taxonomy_urls;

    }


    function purge_url_date_archives( $event, $post ) {
        if( empty( $this->KinstaCache->settings['rules']['archive'][$event] ) ) {
            return;
        }

        $date_archives = array();
        $date = explode( '-', get_the_date( 'Y-n-d', $post->ID ) );

        $year_archive = get_year_link( $date[0] );
        $date_archives['year']['url'] = $year_archive;
        $date_archives['year']['archives'] = array();
        $date_archives['year']['feeds'] = array();

        if( $this->KinstaCache->settings['options']['purge_date_archives'] == true  ) {
            $year_count = $this->count_posts( array(
                'year' => $date[0]
            ));

            $pages = ceil( $year_count / get_option( 'posts_per_page' ) );
            $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_archives'] );

            for( $i = 2; $i <= $max_pages; $i++ ) {
                $date_archives['year']['archives'][] = $year_archive . 'page/' . $i;
            }
        }

        $month_archive = get_month_link( $date[0], $date[1] );
        $date_archives['month']['url'] = $month_archive;
        $date_archives['month']['archives'] = array();
        $date_archives['month']['feeds'] = array();

        if( $this->KinstaCache->settings['options']['purge_archive_feeds'] == true  ) {
            $month_count = $this->count_posts( array(
                'year' => $date[0],
                'monthnum' => $date[1]
            ));

            $pages = ceil( $month_count / get_option( 'posts_per_page' ) );
            $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_archives'] );

            for( $i = 2; $i <= $max_pages; $i++ ) {
                $date_archives['month']['archives'][] = $month_archive . 'page/' . $i;
            }
        }

        return $date_archives;

    }


    function purge_url_author_archives( $event, $post ) {
        if( empty( $this->KinstaCache->settings['rules']['archive'][$event] ) ) {
            return;
        }

        $author_archives = array();
        $author_url = get_author_posts_url( $post->post_author );
        $author_archives['url'] = $author_url;
        $author_archives['archives'] = array();
        $author_archives['feeds'] = array();

        $author_count = $this->count_posts( array(
            'author' => $post->post_author
        ));

        $pages = ceil( $author_count / get_option( 'posts_per_page' ) );
        $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_archives'] );

        for( $i = 2; $i <= $max_pages; $i++ ) {
            $author_archives['archives'][] = $author_url . 'page/' . $i;
        }

        if( $this->KinstaCache->settings['options']['purge_archive_feeds'] == true  ) {
            $author_archives['feeds'][] = $author_url . 'feed/';
            $author_archives['feeds'][] = $author_url . 'rdf/';
            $author_archives['feeds'][] = $author_url . 'atom/';
        }

        return $author_archives;

    }

    function purge_url_post_type( $event, $post ) {
        if( empty( $this->KinstaCache->settings['rules']['archive'][$event] ) ) {
            return;
        }

        $post_type_archives = array();
        if( !in_array( $post->post_type, array( 'post', 'page', 'attachment', 'revision', 'navigation_menu' ) ) ) {
            $post_type_url = get_post_type_archive_link( $post->post_type );
            $post_type_archives['url'] = $post_type_url;
            $post_type_archives['archives'] = array();
            $post_type_archives['feeds'] = array();
            $post_type_count = $this->count_posts( array(
                'post_type' => $post->post_type
            ));

            $pages = ceil( $post_type_count / get_option( 'posts_per_page' ) );
            $max_pages = min( $pages, $this->KinstaCache->settings['options']['page_depth_archives'] );

            for( $i = 2; $i <= $max_pages; $i++ ) {
                $post_type_archives['archives'][] = $post_type_url . 'page/' . $i;
            }

            if( $this->KinstaCache->settings['options']['purge_archive_feeds'] == true  ) {
                $post_type_archives['feeds'][] = $post_type_url . 'feed/';
                $post_type_archives['feeds'][] = $post_type_url . 'rdf/';
                $post_type_archives['feeds'][] = $post_type_url . 'atom/';
            }

        }

        return $post_type_archives;

    }


    /**
     * Purge object Cache
     *
     * The object cache is only purged when the user manually clears all Caches
     * or the object cache, it is not initiated on post/comment actions
     *
     */
    function purge_object_cache() {
        wp_cache_flush();
    }


    function is_event_used( $event ) {
        $key = array_search( 1, array_column($this->KinstaCache->settings['rules'], $event ));
        if( $key === false ) {
            return false;
        }

        return true;
    }

    function calculate_archive_paths( $archives ) {
        $posts_per_page = get_option( 'posts_per_page' );
        foreach( $archives as $archive ) {
            $paths[] = str_replace( home_url(), '', $archive['url'] );
            $pages = ceil( $archive['count'] / $posts_per_page );
            for( $i = 2; $i<=$pages;  $i++ ) {
                $paths[] = str_replace( home_url(), '', $archive['url'] . 'page/' . $i . '/');
            }
        }

        return $paths;
    }

    function calculate_feed_paths( $archives ) {
        $posts_per_page = get_option( 'posts_per_page' );
        $feed_types = array( '', 'rdf/', 'atom/' );

        foreach( $archives as $type => $archive ) {
            if( in_array( $type, array( 'year', 'month', 'day' ) ) ) {
                break;
            }
            $path = str_replace( home_url(), '', $archive['url'] );
            foreach( $feed_types as $feed_type ) {
                $paths[] = $path . 'feed/' . $feed_type;
            }
        }

        return $paths;
    }

    function get_archives( $post ) {
        // Prepare taxonomies
        $taxonomies = get_taxonomies();
        unset( $taxonomies['nav_menu'] );
        unset( $taxonomies['link_category'] );
        $taxonomies = array_values( $taxonomies );
        $terms = wp_get_object_terms( $post->ID, $taxonomies );

        // Author Archive
        $archives['author']['url'] = get_author_posts_url( $post->post_author );
        $archives['author']['count'] = $this->count_posts(array( 'author' => $post->post_author ));

        // Term Archives
        if( !empty( $terms ) ) {
            foreach( $terms as $term ) {
                $archives[$term->taxonomy . '|' . $term->slug]['url'] = get_term_link( $term );
                $archives[$term->taxonomy . '|' . $term->slug]['count'] = $this->count_posts( array(
                    'tax_query' => array(
                        array(
                            'taxonomy' => $term->taxonomy,
                            'field'    => 'id',
                            'terms'    => $term->term_id,
                        ),
                    ),
                ));
            }
        }

        // Date archives
        $time = strtotime( $post->post_date );
        $year = date( 'Y', $time );
        $month = date( 'm', $time );
        $day = date( 'd', $time );

        $archives['year']['url'] = get_year_link( $year ) ;
        $archives['year']['count'] = $this->count_posts( array( 'year' => $year ) );

        $archives['month']['url'] = get_month_link( $year, $month ) ;
        $archives['month']['count'] = $this->count_posts( array( 'year' => $year, 'monthnum' => $month ) );

        $archives['day']['url'] = get_day_link( $year, $month, $day ) ;
        $archives['day']['count'] = $this->count_posts( array( 'year' => $year, 'monthnum' => $month, 'day' => $day ) );

        return $archives;
    }


    function count_posts( $args = array() ) {

        $defaults = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'fields' => 'ids',
            'no_found_rows' => true,
            'posts_per_page' => -1
        );

        $args = wp_parse_args( $args, $defaults );

        $posts = new \WP_Query( $args );

        return count( $posts->posts );

    }

    function purge_all() {
		wp_remote_get(  $this->initiate_purge_urls['all'], array(
	        'sslverify' => false,
            'timeout' => 5
        ));
        wp_cache_flush();
    }

    function purge_full_page_cache() {
        wp_remote_get(  $this->initiate_purge_urls['all'], array(
	        'sslverify' => false,
            'timeout' => 5
        ));
    }

}
