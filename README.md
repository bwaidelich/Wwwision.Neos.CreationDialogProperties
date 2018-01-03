# Wwwision.Neos.CreationDialogProperties

Simple package to easily expose Node properties to the *CreationDialog* that's available since Neos 3.3.

## DISCLAIMER / BACKGROUND

Neos 3.3 ships with a great new feature called *CreationDialog* that allows NodeType definitions to specify
a dialog that is displayed upon creation of a corresponding node.

**Note:** Unfortunately that feature is not yet documented, you can read more about it and follow
the state of that in the corresponding [ticket](https://github.com/neos/neos-ui/issues/1469).

By default a custom *nodeCreationHandler* has to be implemented to process the data of the *CreationDialog**.
This package provides a generic handler that allows to easily expose certain node properties to be displayed
in the dialog.

This package is in an **experimental** state at the moment and there is still some
[discussion](https://neos-project.slack.com/archives/C0U0KEGDQ/p1513932060000034) going on in the
Neos Team as to whether this approach has any drawbacks.

## Usage

1. Install package via `composer require wwwision/neos-creationdialogproperties`
2. Extend NodeType definitions, adding the `showInCreationDialog` option:

### Example Node Type Configuration

```yaml
'Some.Package:Some.NodeType':
  superTypes:
    'Neos.Neos:Content': true
  ui:
    label: 'Some Content Element'
    # ...
  properties:
    'property1':
      type: string
      options:
        showInCreationDialog: true
      validation:
        'Neos.Neos/Validation/NotEmptyValidator': []
      ui:
        label: 'Mandatory link property'
        inspector:
          editor: 'Neos.Neos/Inspector/Editors/LinkEditor'
    'property2':
      type: string
      options:
        showInCreationDialog: true
      ui:
        label: 'Optional selector property'
        inspector:
          editor: 'Neos.Neos/Inspector/Editors/SelectBoxEditor'
          editorOptions:
            values:
              'value1':
                label: 'Label 01'
              'value2':
                label: 'Label 02'
```

The above will result in the following Creation Dialog to be displayed whenever
a node of the corresponding type is inserted:

![Screenshot of the creation dialog](/CreationDialog.png "Neos Creation Dialog")
