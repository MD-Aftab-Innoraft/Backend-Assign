<?php

/**
 * @file
 * Documentation for the hook_count_nodes_view().
 */

use Drupal\node\NodeInterface;

/**
 * The hook_count_nodes_view counts number of times the node has been viewed.
 *
 * @param Drupal\node\NodeInterface $node
 *   The node being viewed.
 */
function hook_count_nodes(NodeInterface $node) {
  // Custom logic for displaying view count.
}
