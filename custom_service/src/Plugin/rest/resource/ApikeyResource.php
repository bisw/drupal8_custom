<?php

namespace Drupal\custom_service\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a Api Key Resource.
 *
 * @RestResource(
 *   id = "siteapikey",
 *   label = @Translation("Api Key Resource"),
 *   uri_paths = {
 *     "canonical" = "/page_json/{apikey}/{nid}"
 *   }
 * )
 */
class ApikeyResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get($apikey, $nid) {
    // Get siteapikey var and validate api.
    $config = \Drupal::config('custom_service.settings');
    if ($apikey !== $config->get('siteapikey')) {
      throw new AccessDeniedHttpException('access denied');
    }

    // Check for valid node id.
    if (!is_numeric($nid)) {
      throw new AccessDeniedHttpException('access denied');
    }

    // Validate the node is accessible or not.
    $node = Node::load($nid);
    $node_access = $node->access('view', NULL, TRUE);
    if (!$node_access->isAllowed()) {
      throw new AccessDeniedHttpException('access denied');
    }

    // Validate node type, if it's not page throw access denied.
    $type = $node->type->getValue();
    if (!isset($type[0]['target']) && $type[0]['target_id'] !== 'page') {
      throw new AccessDeniedHttpException('access denied');
    }

    // Create the response object.
    $response = new ResourceResponse($node, 200);
    $response->addCacheableDependency($node);
    $response->addCacheableDependency($node_access);

    return $response;
  }

}
