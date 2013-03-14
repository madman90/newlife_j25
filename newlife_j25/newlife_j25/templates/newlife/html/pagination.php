<?php
/**
 * Created by JetBrains PhpStorm.
 * User: irish
 * Date: 16.02.13
 * Time: 9:57
 * To change this template use File | Settings | File Templates.
 */
function pagination_list_render($list)
{

    // Reverse output rendering for right-to-left display.
    $html = '<span class="pagination-prev">' . $list['previous']['data'] . '</span>';
    $html .= '<span class="pagination-next">' . $list['next']['data'] . '</span>';
//    $html .= '<div class="pagin_wrapper">';
    $html .= '<ul>';

    foreach ($list['pages'] as $page)
    {
        $html .= '<li>' . $page['data'] . '</li>';
    }

    $html .= '</ul>';
//    $html .= '</div>';
    return $html;
}