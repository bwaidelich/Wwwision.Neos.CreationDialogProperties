<?php
namespace Wwwision\Neos\CreationDialogProperties;

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;

/**
 * Node Type post processor that looks for properties flagged with "showInCreationDialog" and sets the "nodeCreationHandler" configuration accordingly
 *
 * Example NodeTypes.yaml configuration:
 *
 * 'Some.Node:Type':
 *   # ...
 *   properties:
 *     'someProperty':
 *       type: string
 *       options:
 *         showInCreationDialog: true
 *       ui:
 *         label: 'Link'
 *         inspector:
 *           editor: 'Neos.Neos/Inspector/Editors/LinkEditor'
 *
 * Will be converted to:
 *
 * 'Some.Node:Type':
 *   # ...
 *   ui:
 *     creationDialog:
 *       elements:
 *         'someProperty':
 *           type: string
 *           ui:
 *             label: 'Link'
 *             editor: 'Neos.Neos/Inspector/Editors/LinkEditor'
 *   properties:
 *     'someProperty':
 *       # ...
 *
 */
class CreationDialogPostprocessor implements NodeTypePostprocessorInterface
{

    /**
     * @param NodeType $nodeType (uninitialized) The node type to process
     * @param array $configuration input configuration
     * @param array $options The processor options
     * @return void
     */
    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        if (!isset($configuration['properties'])) {
            return;
        }
        $hasCreationDialogProperties = false;
        foreach ($configuration['properties'] as $propertyName => $propertyConfiguration) {
            if (!isset($propertyConfiguration['options']['showInCreationDialog']) || $propertyConfiguration['options']['showInCreationDialog'] !== true) {
                continue;
            }
            $hasCreationDialogProperties = true;
            $configuration['ui']['creationDialog']['elements'][$propertyName] = $this->convertPropertyConfiguration($propertyName, $propertyConfiguration);
        }
        if ($hasCreationDialogProperties) {
            $configuration['options']['nodeCreationHandlers']['Wwwision.Neos.CreationDialogProperties']['nodeCreationHandler'] = NodeCreationHandler::class;
        }
    }

    /**
     * Converts a NodeType property configuration to the corresponding creationDialog "element" configuration
     *
     * @param string $propertyName
     * @param array $configuration
     * @return array
     */
    private function convertPropertyConfiguration(string $propertyName, array $configuration): array
    {
        $convertedConfiguration = [
            'type' => $configuration['type'] ?? 'string',
            'ui' => [
                'label' => $configuration['ui']['label'] ?? $propertyName,
            ],
        ];
        if (isset($configuration['required'])) {
            $convertedConfiguration['required'] = $configuration['required'];
        }
        if (isset($configuration['validation'])) {
            $convertedConfiguration['validation'] = $configuration['validation'];
        }
        $convertedConfiguration['ui']['editor'] = $configuration['ui']['inspector']['editor'] ?? 'Neos.Neos/Inspector/Editors/TextFieldEditor';
        if (isset($configuration['ui']['inspector']['editorOptions'])) {
            $convertedConfiguration['ui']['editorOptions'] = $configuration['ui']['inspector']['editorOptions'];
        }
        return $convertedConfiguration;
    }
}
