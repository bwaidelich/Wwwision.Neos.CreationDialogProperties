<?php
namespace Wwwision\Neos\CreationDialogProperties;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\Property\TypeConverter\PersistentObjectConverter;
use Neos\Neos\Ui\NodeCreationHandler\NodeCreationHandlerInterface;
use Neos\Utility\TypeHandling;
use Neos\Utility\ObjectAccess;

/**
 * A Node Creation Handler that takes the incoming data from the Creation Dialog and sets the corresponding node property
 */
class NodeCreationHandler implements NodeCreationHandlerInterface
{

    /**
     * @Flow\Inject
     * @var PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @param NodeInterface $node The newly created node
     * @param array $data incoming data from the creationDialog
     * @return void
     */
    public function handle(NodeInterface $node, array $data)
    {
        $propertyMappingConfiguration = $this->propertyMapper->buildPropertyMappingConfiguration();
        $propertyMappingConfiguration->forProperty('*')->allowAllProperties();
        $propertyMappingConfiguration->setTypeConverterOption(PersistentObjectConverter::class, PersistentObjectConverter::CONFIGURATION_OVERRIDE_TARGET_TYPE_ALLOWED, true);

        foreach ($data as $propertyName => $propertyValue) {
            $propertyType = TypeHandling::normalizeType($node->getNodeType()->getPropertyType($propertyName));
            if ($propertyType !== 'references' && $propertyType !== 'reference' && $propertyType !== TypeHandling::getTypeForValue($propertyValue)) {
                $propertyValue = $this->propertyMapper->convert($propertyValue, $propertyType, $propertyMappingConfiguration);
            }
            if ($propertyName{0} === '_') {
                ObjectAccess::setProperty($node, substr($propertyName, 1), $propertyValue);
            } else {
                $node->setProperty($propertyName, $propertyValue);
            }
        }
    }
}
