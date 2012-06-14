<?php
if($page!=0)
{
    $previous_node_link = previous_node($node, NULL, NULL, NULL);
    $next_node_link = next_node($node, NULL, NULL, NULL);    
    
    print '<div class="previous-next-links">';
    if($previous_node_link && $next_node_link)
    {
        print $previous_node_link.' '.$next_node_link;
    }
    else if($previous_node_link)
    {
        print $previous_node_link;
    }
    else if($next_node_link)
    {
        print $next_node_link;
    }
    print '</div>';
}