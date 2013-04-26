<?php
// Define the post type text here, allowing us to quickly re-use this code in other projects
$ac_pt = 'faq'; // must be the registered post type
$ac_pt_p = 'FAQs';
$ac_pt_s = 'FAQ';

// No need to modify these 2
$ac_pt_pp = $ac_pt_p . ' Pending';
$ac_pt_sp = $ac_pt_s . ' Pending';


$args = array(
    'public' => true,
    '_builtin' => false
);
$output = 'object';
$operator = 'and';

$num_posts = wp_count_posts( $ac_pt );
$num = number_format_i18n( $num_posts->publish );
$text = _n( $ac_pt_s, $ac_pt_p, intval( $num_posts->publish ) );

if( current_user_can( 'edit_posts' ) ) {
    $num = "<a href='edit.php?post_type=$ac_pt'>$num</a>";
    $text = "<a href='edit.php?post_type=$ac_pt'>$text</a>";
}

echo '<td class="first b b-' . $ac_pt . '">' . $num . '</td>';
echo '<td class="t ' . $ac_pt . '">' . $text . '</td>';
echo '</tr>';

if( $num_posts->pending > 0 ) {
    $num = number_format_i18n( $num_posts->pending );
    $text = _n( $ac_pt_sp, $ac_pt_pp, intval( $num_posts->pending ) );

    if( current_user_can( 'edit_posts' ) ) {
        $num = "<a href='edit.php?post_status=pending&post_type='$ac_pt'>$num</a>";
        $text = "<a href='edit.php?post_status=pending&post_type=$ac_pt'>$text</a>";
    }

    echo '<td class="first b b-' . $ac_pt . '">' . $num . '</td>';
    echo '<td class="t ' . $ac_pt . '">' . $text . '</td>';
    echo '</tr>';
}

$faq_args = array( 'name' => 'group' );

$taxonomies = get_taxonomies( $faq_args, $output, $operator );

foreach( $taxonomies as $taxonomy ) {
    $num_terms = wp_count_terms( $taxonomy->name );
    $num = number_format_i18n( $num_terms );
    $text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name, intval( $num_terms ) );
    if( current_user_can( 'manage_categories' ) ) {

        $num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
        $text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
    }
    echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
    echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
}