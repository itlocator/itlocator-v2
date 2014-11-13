<?php

class postsWidgetItlocation extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        global $pagenow;

        parent::__construct(
                'posts_widget_itlocation', // Base ID
                'ItLocator Recent Posts', // Name
                array('description' => __('Display Posts as widget items', 'twentyten')) // Args
        );

        $this->directory = plugins_url('/', __FILE__);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    function widget($args, $instance) {
        extract($args);
        extract($instance);

        $title = apply_filters('widget_title', empty($title) ? '' : $title );

        $posttypes = $instance['posttype'];

        $all_posts = array();
        foreach ($posttypes as $posttype) {
            $args = array(
                'posts_per_page' => 5,
                'post_type' => $posttype
            );
            $itlocation_posts = get_posts($args);
            if (count($itlocation_posts)) {
                foreach ($itlocation_posts as $post) {
                    $idx = $post->ID;
                    $all_posts[$idx]['ID'] = $post->ID;
                    $all_posts[$idx]['post_title'] = $post->post_title;
                    $all_posts[$idx]['post_content'] = $post->post_content;
                    $all_posts[$idx]['post_author'] = $post->post_author;
                    $all_posts[$idx]['post_date'] = $post->post_date;
                }
            }
        }

        if ($instance['order'] == 'DESC')
            krsort($all_posts);
        else
            ksort($all_posts);

        $thumbnail = $instance['thumbnail'];
        $thumbsize = $instance['thumbsize'];
        $number = $instance['number'];

        include( $this->getTemplateHierarchy('posts_widget') );
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update($new_instance, $old_instance) {

        // Get our defaults to test against
        $this->posttypes = get_post_types(array('public' => true), 'objects');
        $this->thumbsizes = get_intermediate_image_sizes();
        $this->orders = array(
            'DESC' => __('Newer comments first', 'twentyten'),
            'ASC' => __('Older comments first', 'twentyten'),
        );

        $pt_names = get_post_types(array('public' => true), 'names');

        // Validate posttype submissions
        $posttypes = array();
        foreach ($new_instance['posttype'] as $pt) {
            if (in_array($pt, $pt_names))
                $posttypes[] = $pt;
        }
        if (empty($posttypes))
            $posttypes[] = 'post';

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['posttype'] = $posttypes;
        $instance['number'] = (int) $new_instance['number'];
        $instance['order'] = ( array_key_exists($new_instance['order'], $this->orders) ? $new_instance['order'] : 'DESC' );
        $instance['thumbnail'] = ( isset($new_instance['thumbnail']) ? (int) $new_instance['thumbnail'] : '0' );
        $instance['thumbsize'] = ( in_array($new_instance['thumbsize'], $this->thumbsizes) ? $new_instance['thumbsize'] : '' );

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form($instance) {
        $this->posttypes = get_post_types(array('public' => true), 'objects');
        $this->thumbsizes = get_intermediate_image_sizes();

        $this->orders = array(
            'DESC' => __('Newer comments first', 'twentyten'),
            'ASC' => __('Older comments first', 'twentyten'),
        );

        $instance = wp_parse_args((array) $instance, array(
            'title' => '',
            'posttype' => array('post'),
            'number' => '3',
            'order' => 'desc',
            'thumbnail' => '0'
                ));

        extract($instance);

        include( $this->getTemplateHierarchy('admin') );
    }

    /**
     * Loads theme files in appropriate hierarchy: 1) child theme,
     * 2) parent template, 3) plugin resources. will look in the flexible-posts-widget/
     * directory in a theme and the views/ directory in the plugin
     *
     * Based on a function in the amazing image-widget
     * 
     * @param string $template template file to search for
     * @return template path
     * */
    public function getTemplateHierarchy($template) {

        // whether or not .php was added
        $template_slug = preg_replace('/.php$/', '', $template);
        $template = $template_slug . '.php';
        $file = 'views/' . $template;
        return apply_filters('itlocation_template_' . $template, $file);
    }

    /**
     * 
     */
    public function posttype_checklist($posttype) {

        //Get pubic post type objects
        $posttypes = get_post_types(array('public' => true), 'objects');

        $output = '<ul class="categorychecklist posttypechecklist form-no-clear">';
        foreach ($posttypes as $type) {
            $output .= "\n<li>" . '<label class="selectit"><input value="' . esc_attr($type->name) . '" type="checkbox" name="' . $this->get_field_name('posttype') . '[]"' . checked(in_array($type->name, (array) $posttype), true, false) . ' /> ' . esc_html($type->labels->name) . "</label></li>\n";
        }
        $output .= "</ul>\n";

        echo ( $output );
    }

}

// class DPE_Flexible_Posts_Widget
