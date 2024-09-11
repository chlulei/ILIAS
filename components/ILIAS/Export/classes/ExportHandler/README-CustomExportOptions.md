### Table of Contents
- [Custom Export Options](#custom-export-options)
- [Methods](#methods)
  - [Method init](#method-init)
  - [Method getExportType](#method-getexporttype)
  - [Method getExportOptionId](#method-getexportoptionid)
  - [Method getSupportedRepositoryObjectTypes](#method-getsupportedrepositoryobjecttypes)
  - [Method isPublicAccessPossible](#method-ispublicaccesspossible)
  - [Method getLabel](#method-getlabel)
  - [Method onExportOptionSelected](#method-onexportoptionselected)
  - [Method onDeleteFiles](#method-ondeletefiles)
  - [Method onDownloadFiles](#method-ondownloadfiles)
  - [Method onDownloadWithLink]()
  - [Method getFiles](#method-getfiles)
  - [Method getFileSelection](#method-getfileselection)
- [Example implementation](#example-implementation)

### Custom Export Options
The export allows for custom export options.
Custom Export Options extend the class _ILIAS\Export\ExportHandler\Consumer\ExportOption\ilBasicHandler_:
```php
use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilBasicHandler as ilBasicExportOption;
```

### Methods
The inheriting classes need to define methods as described.

#### Method init:
```php
abstract protected function init();
```
Is called once on creation of the object.

#### Method getExportType:
```php
public function getExportType(): string;
```
This method returns the type of the export. For example _xml_. 

#### Method getExportOptionId:
```php
public function getExportOptionId(): string;
```
This method returns a unique identifier.
The export option is addressed by using this identifier.
For example _expxml_.
If multiple export options share an identifier, than they cannot be displayed together in the export tab.

#### Method getSupportedRepositoryObjectTypes:
```php
public function getSupportedRepositoryObjectTypes(): array
```
This method returns an array of repository object types, for example \['crs', 'grp'].
The returned array is used to determine in wich export tab the export option is displayed.
The export option is displayed in the export tab of each repository objects that matches one of the types. 

#### Method isPublicAccessPossible:
```php
public function isPublicAccessPossible(
    ilExportHandlerConsumerContextInterface $context
): bool;
```
This method returns true if it is allowed to mark the files managed by the export option as public accessable and false otherwise.
By default this setting is set to false.
Files that are marked as public accessable can be accessed via download links and automaticaly harvested.
The return value sould never be set to true if the export option manages files with private information.

#### Method getLabel:
```php
public function getLabel(
    ilExportHandlerConsumerContextInterface $context
): string;
```
This method provides the label used by ui elements to display the export option in the export tab. For example a button.
_context_ allows access to **ilLanguage** if needed.

#### Method onExportOptionSelected:
```php
public function onExportOptionSelected(
    ilExportHandlerConsumerContextInterface $context
): void;
```
This method implements the behavior that occurs on the selection of the export option.
For example, the standard xml export forwards to the export selection table gui.
_context_ can be used to access **ilCtrlInterface** and other usefull dependencies.

#### Method onDeleteFiles:
```php
public function onDeleteFiles(
    ilExportHandlerConsumerContextInterface $context,
    ilExportHandlerTableRowIdCollectionInterface $table_row_ids
): void;
```
This method implements deletion of files that match the file identifiers provieded _table_row_ids_.
_table_row_ids_ is a collection and can be iterated over.
To access all file identifers as a _string[]_, _table_row_ids->fileIdentifiers_ can be used.
Alternative a file identifier can be optained via:
```php
/** @var ILIAS\Export\ExportHandler\I\Table\RowId\ilHandlerInterface $table_row_id */
/** @var ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface $table_row_ids */
foreach ($table_row_ids as $table_row_id) {
    $file_identifier = $table_row_id->getFileIdentifier();
    $export_option_id = $table_row_id->getExportOptionId();
}
```
_export_option_id_ is the return value of _getExportOptionId_.
_file_identifier_ is either the file name or a resource id.
Which one depends on the implementation of _getFiles_ explained further below.

#### Method onDownloadFiles:
```php
public function onDownloadFiles(
    ilExportHandlerConsumerContextInterface $context,
    ilExportHandlerTableRowIdCollectionInterface $table_row_ids
): void;
```
This method implements the download of files that match the file identifiers provieded _table_row_ids_. 


#### Method onDownloadWithLink:
```php
    public function onDownloadWithLink(
        ReferenceId $reference_id,
        ilExportHandlerTableRowIdInterface $table_row_id
    ): void;
```
This method implements the download of files that match the file identifier provieded by _table_row_id_.
It is called if a resource is accessed with a download link.

#### Method getFiles:
```php
public function getFiles(
    ilExportHandlerConsumerContextInterface $context
): ilExportHandlerFileInfoCollectionInterface;
```
This method collects all files that the export option has stored for the object in the current context and returns them as a file info collection.
The current object can be accessed with _context->exportObject()_.
The file collection and file infos can be created with a file factory provided by _context_ (_context->fileFactory()_).
A file info can be created in two ways, either from a **ResourceIdentification** or a **SplFileInfo**.
This choice determines value of the file identifiers that are provided to the other methods by _table_row_ids_.
If the file info is created by using a **ResourceIdentification**, than the value returned by _table_row_id->getFileIdentifier()_ is the serialized resource identification.
If the file info is created by using a **SplFileInfo**, than the value returned by _table_row_id->getFileIdentifier()_ is a composit id of the export option id and the file name.
The composit id structure is:
```
<export option id>:<file name>
```

#### Method getFileSelection:
```php
public function getFileSelection(
    ilExportHandlerConsumerContextInterface $context,
    ilExportHandlerTableRowIdCollectionInterface $table_row_ids
): ilExportHandlerFileInfoCollectionInterface;
```
Similar to _getFiles_, but should only return the files that match the file identifiers supplied by _table_row_ids_.

### Example implementation:
Implementation of the default xml export option is located here: "components/ILIAS/Export/classes/class.ilExportXMLExportOption.php"