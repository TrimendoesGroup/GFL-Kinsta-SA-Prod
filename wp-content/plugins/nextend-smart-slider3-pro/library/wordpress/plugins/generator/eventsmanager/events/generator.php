<?php

N2Loader::import('libraries.slider.generator.N2SmartSliderGeneratorAbstract', 'smartslider');

class N2GeneratorEventsManagerEvents extends N2GeneratorAbstract {

    private $order = array(
        '_event_start_date',
        'asc'
    );

    protected function eventTimes($event_all_day, $event_start_time, $event_end_time, $event_start_date, $event_end_date) {
        $replace = '';
        if (function_exists('get_option')) {
            $start = strtotime($event_start_date . " " . $event_start_time);
            $end   = strtotime($event_end_date . " " . $event_end_time);
            if (!$event_all_day) {
                $time_format = (get_option('dbem_time_format')) ? get_option('dbem_time_format') : get_option('time_format');
                if ($event_start_time != $event_end_time) {
                    $replace = date_i18n($time_format, $start) . get_option('dbem_times_separator') . date_i18n($time_format, $end);
                } else {
                    $replace = date_i18n($time_format, $start);
                }
            } else {
                $replace = get_option('dbem_event_all_day_message');
            }
        }
        return $replace;
    }

    protected function eventDates($event_start_time, $event_end_time, $event_start_date, $event_end_date) {
        $replace = '';
        if (function_exists('get_option')) {
            $start       = strtotime($event_start_date . " " . $event_start_time);
            $end         = strtotime($event_end_date . " " . $event_end_time);
            $date_format = (get_option('dbem_date_format')) ? get_option('dbem_date_format') : get_option('date_format');
            if ($event_start_date != $event_end_date) {
                $replace = date_i18n($date_format, $start) . get_option('dbem_dates_separator') . date_i18n($date_format, $end);
            } else {
                $replace = date_i18n($date_format, $start);
            }
        }
        return $replace;
    }

    protected function _getData($count, $startIndex) {
        global $wpdb, $post;
        $tmpPost = $post;
        $data    = array();

        $tax_query  = array();
        $meta_query = array();

        $categories = explode('||', $this->data->get('categories', 0));
        if (!in_array(0, $categories)) {
            $tax_query[] = array(
                'taxonomy' => 'event-categories',
                'field'    => 'term_id',
                'terms'    => $categories
            );
        }

        $tags = explode('||', $this->data->get('tags', 0));
        if (!in_array(0, $tags)) {
            $tax_query[] = array(
                'taxonomy' => 'event-tags',
                'field'    => 'term_id',
                'terms'    => $tags
            );
        }

        $locations    = explode('||', $this->data->get('locations', 0));
        $locationTown = str_replace(",", "','", $this->data->get('locationtown', ''));
        if (!empty($locationTown)) {
            $locationTown = "'" . $locationTown . "'";
        }
        $locationState = str_replace(",", "','", $this->data->get('locationstate', ''));
        if (!empty($locationState)) {
            $locationState = "'" . $locationState . "'";
        }

        if (!in_array(0, $locations)) {
            $query = "SELECT location_id FROM " . $wpdb->base_prefix . "em_locations WHERE post_id IN (" . implode(',', $locations) . ")";
            if (!empty($locationTown)) {
                $query .= " AND location_town IN (" . $locationTown . ")";
            }
            if (!empty($locationState)) {
                $query .= " AND location_state IN (" . $locationState . ")";
            }
        } else {
            if (!empty($locationTown)) {
                $query = "SELECT location_id FROM " . $wpdb->base_prefix . "em_locations WHERE location_town IN (" . $locationTown . ")";
                if (!empty($locationState)) {
                    $query .= " AND location_state IN (" . $locationState . ")";
                }
            } else if (!empty($locationState)) {
                $query = "SELECT location_id FROM " . $wpdb->base_prefix . "em_locations WHERE location_state IN (" . $locationState . ")";
            }
        }

        if (isset($query)) {
            $locations   = $wpdb->get_results($query);
            $locationIDs = array();
            for ($i = 0; $i < count($locations); $i++) {
                $locationIDs[$i] = $locations[$i]->location_id;
            }
            if (count($locationIDs)) {
                $meta_query[] = array(
                    'key'   => '_location_id',
                    'value' => $locationIDs
                );
            } else {
                return null;
            }
        }


        $today = strtotime(date('Y-m-d', current_time('timestamp')));

        switch ($this->data->get('started', '0')) {
            case 1:
                $meta_query[] = array(
                    'key'     => '_start_ts',
                    'value'   => $today,
                    'compare' => '<'
                );
                break;
            case -1:
                $meta_query[] = array(
                    'key'     => '_start_ts',
                    'value'   => $today,
                    'compare' => '>='
                );
                break;
        }

        switch ($this->data->get('ended', '-1')) {
            case 1:
                $meta_query[] = array(
                    'key'     => '_end_ts',
                    'value'   => $today,
                    'compare' => '<'
                );
                break;
            case -1:
                $meta_query[] = array(
                    'key'     => '_end_ts',
                    'value'   => $today,
                    'compare' => '>='
                );
                break;
        }

        $args = array(
            'offset'           => $startIndex,
            'posts_per_page'   => $count,
            'post_parent'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => false,
            'post_type'        => 'event',
            'tax_query'        => $tax_query,
            'meta_query'       => $meta_query
        );

        $this->order = explode("|*|", $this->data->get('order', 'event_start_date|*|asc'));

        add_filter('posts_orderby', array(
            $this,
            'posts_orderby'
        ));
        add_filter('posts_fields', array(
            $this,
            'posts_fields'
        ));
        add_filter('posts_join', array(
            $this,
            'posts_join'
        ));

        $posts = get_posts($args);

        remove_filter('posts_orderby', array(
            $this,
            'posts_orderby'
        ));
        remove_filter('posts_fields', array(
            $this,
            'posts_fields'
        ));
        remove_filter('posts_join', array(
            $this,
            'posts_join'
        ));
        $custom_start_time = $this->data->get('custom_start_time', 0);
        $custom_end_date   = $this->data->get('custom_end_date', 0);
        $custom_end_time   = $this->data->get('custom_end_time', 0);
        $start_time        = $this->data->get('start_time', '');
        $end_date          = $this->data->get('end_date', '');
        $end_time          = $this->data->get('end_time', '');
        for ($i = 0; $i < count($posts); $i++) {
            $post = $posts[$i];
            //post data
            $data[$i]['title']       = $post->post_title;
            $data[$i]['description'] = $post->post_content;
            $data[$i]['excerpt']     = $post->post_excerpt;
            $data[$i]['image']       = N2ImageHelper::dynamic(wp_get_attachment_url(get_post_thumbnail_id($post->ID)));
            $thumbnail               = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID, 'thumbnail'));
            if ($thumbnail[0]) {
                $data[$i]['thumbnail'] = N2ImageHelper::dynamic($thumbnail[0]);
            } else {
                $data[$i]['thumbnail'] = $data[$i]['image'];
            }
            $data[$i]['url'] = get_permalink($post->ID);

            $start                  = strtotime($post->event_start_date . ' ' . $post->event_start_time);
            $data[$i]['start_date'] = date_i18n(get_option('date_format'), $start);
            if ($post->event_all_day && $custom_start_time) {
                $data[$i]['start_time'] = $start_time;
            } else {
                $data[$i]['start_time'] = date_i18n(get_option('time_format'), $start);
            }

            $end = strtotime($post->event_end_date . ' ' . $post->event_end_time);
            if ($post->event_all_day && $custom_end_date) {
                $data[$i]['end_date'] = $end_date;
            } else {
                $data[$i]['end_date'] = date_i18n(get_option('date_format'), $end);
            }

            if ($post->event_all_day && $custom_end_time) {
                $data[$i]['end_time'] = $end_time;
            } else {
                $data[$i]['end_time'] = date_i18n(get_option('time_format'), $end);
            }

            $data[$i]['event_times'] = $this->eventTimes($post->event_all_day, $post->event_start_time, $post->event_end_time, $post->event_start_date, $post->event_end_date);
            $data[$i]['event_dates'] = $this->eventDates($post->event_start_time, $post->event_end_time, $post->event_start_date, $post->event_end_date);

            $data[$i]['ID'] = $post->ID;

            $rsvp                  = strtotime($post->event_rsvp_date . ' ' . $post->event_rsvp_time);
            $data[$i]['rsvp_date'] = date_i18n(get_option('date_format'), $rsvp);
            $data[$i]['rsvp_time'] = date_i18n(get_option('time_format'), $rsvp);

            $data[$i]['rsvp_spaces']        = $post->event_rsvp_spaces;
            $data[$i]['spaces']             = $post->event_spaces;
            $data[$i]['location_name']      = $post->location_name;
            $data[$i]['location_address']   = $post->location_address;
            $data[$i]['location_town']      = $post->location_town;
            $data[$i]['location_state']     = $post->location_state;
            $data[$i]['location_postcode']  = $post->location_postcode;
            $data[$i]['location_region']    = $post->location_region;
            $data[$i]['location_country']   = $post->location_country;
            $data[$i]['location_latitude']  = $post->location_latitude;
            $data[$i]['location_longitude'] = $post->location_longitude;
            $data[$i]['ticket_name']        = $post->ticket_name;
            $data[$i]['ticket_description'] = $post->ticket_description;
            $data[$i]['ticket_price']       = $post->ticket_price;
            $data[$i]['ticket_start']       = $post->ticket_start;
            $data[$i]['ticket_end']         = $post->ticket_end;
            $data[$i]['ticket_min']         = $post->ticket_min;
            $data[$i]['ticket_max']         = $post->ticket_max;
            $data[$i]['ticket_spaces']      = $post->ticket_spaces;
        }

        wp_reset_postdata();
        $post = $tmpPost;
        if ($post) setup_postdata($post);

        return $data;
    }

    public function posts_fields($fields) {
        return 'events.*, locations.*, tickets.*, ' . $fields;
    }

    public function posts_join($join) {
        global $wpdb;

        return $join . " LEFT JOIN {$wpdb->prefix}em_events AS events ON {$wpdb->posts}.ID = events.post_id " . " LEFT JOIN {$wpdb->prefix}em_locations AS locations ON events.location_id = locations.location_id " . " LEFT JOIN {$wpdb->prefix}em_tickets AS tickets ON events.event_id = tickets.event_id ";
    }

    public function posts_orderby($orderby) {
        $orderby = ' ';
        if (substr($this->order[0], 0, 1) == '_') { //fallback for previous versions
            $this->order[0] = substr($this->order[0], 1);
        }
        if ($this->order[0] == 'title' || $this->order[0] == 'post_title') {
            $this->order[0] = 'post_title'; //fallback for old version selections
            global $wpdb;
            $orderby = $wpdb->prefix . 'posts.';
        }
        $orderby .= $this->order[0] . ' ' . $this->order[1];
        return $orderby;
    }
}
