<?php
namespace Wwwision\Neos\CreationDialogProperties;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Neos\Ui\NodeCreationHandler\NodeCreationHandlerInterface;

/**
 * A Node Creation Handler that takes the incoming data from the Creation Dialog and sets the corresponding node property
 */
class NodeCreationHandler implements NodeCreationHandlerInterface
{

    /**
     * @param NodeInterface $node The newly created node
     * @param array $data incoming data from the creationDialog
     * @return void
     */
    public function handle(NodeInterface $node, array $data)
    {
        foreach ($data as $propertyName => $propertyValue) {
            $node->setProperty($propertyName, $propertyValue);
        }
    }
}
