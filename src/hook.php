<?php

function push_repository_dispatch_event($post_id, $post)
{
  $data = "'{" . '"event_type": "' . esc_attr(get_option('github_event_type')) . '"}' . "'";
  $url = 'https://api.github.com/repos/' . esc_attr(get_option('github_account')) . '/' . esc_attr(get_option('github_repository')) . '/dispatches';
  $token = esc_attr(get_option('github_token'));

  exec("curl -H \"Authorization: token ${token}\" -H \"Accept: application/vnd.github.everest-preview+json\" \"${url}\" -d ${data}");
}

function webhook_transition_post_status($new_status, $old_status, $post)
{
  if( $old_status != 'publish' && $new_status == 'publish' ) {
    push_repository_dispatch_event($post->ID, $post);
  }
  if( $old_status == 'publish' && $new_status == 'draft' ) {
    push_repository_dispatch_event($post->ID, $post);
  }
  if( $old_status == 'publish' && $new_status == 'private' ) {
    push_repository_dispatch_event($post->ID, $post);
  }
  if( $old_status == 'publish' && $new_status == 'pending' ) {
    push_repository_dispatch_event($post->ID, $post);
  }
  if( $old_status == 'publish' && $new_status == 'trash' ) {
    push_repository_dispatch_event($post->ID, $post);
  }
}

add_action('transition_post_status', 'webhook_transition_post_status', 10, 3);
?>
