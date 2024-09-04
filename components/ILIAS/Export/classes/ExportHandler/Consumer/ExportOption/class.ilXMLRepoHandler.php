<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace ILIAS\Export\ExportHandler\Consumer\ExportOption;

use ILIAS\Data\ObjectId;
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilBasicHandler as ilExportHandlerConsumerBasicExportOption;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilCollectionInterface as ilExportHandlerFileInfoCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowIdCollectionInterface;

class ilXMLRepoHandler extends ilExportHandlerConsumerBasicExportOption
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function getExportType(): string
    {
        return "xml";
    }

    public function getExportOptionId(): string
    {
        return "expxml";
    }

    public function getLabel(
        ilExportHandlerConsumerContextInterface $context
    ): string {
        return $context->ilLng()->txt("exp_create_file") . " (xml)";
    }

    public function onExportOptionSelected(
        ilExportHandlerConsumerContextInterface $context
    ): void {
        $context->ilCtrl()->redirect($context->exportGUIObject(), $context->exportGUIObject()::CMD_EXPORT_XML);
    }

    public function onDeleteFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void {
        $ids = [];
        $object_id = new ObjectId($context->exportObject()->getId());
        foreach ($table_row_ids as $table_row_id) {
            $ids[] = $table_row_id->getFileIdentifier();
        }
        $ref_id = new ReferenceId($context->exportObject()->getRefId());
        $elements = $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$ids);
        $pa_element = $this->export_handler->publicAccess()->repository()->handler()->getElement($object_id);
        foreach ($ids as $id) {
            if (!$pa_element->isStorable()) {
                break;
            }
            if ($pa_element->getIdentification() === $id) {
                $val = $this->export_handler->publicAccess()->repository()->handler()->deleteElement($pa_element);
                break;
            }
        }
        $this->export_handler->repository()->handler()->deleteElements(
            $elements,
            $this->export_handler->repository()->stakeholder()->withOwnerId($context->user()->getId())
        );
    }

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void {
        $ids = [];
        $object_id = new ObjectId($context->exportObject()->getId());
        foreach ($table_row_ids as $table_row_id) {
            $ids[] = $table_row_id->getFileIdentifier();
        }
        $elements = $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$ids);
        $element = $elements->current();
        $element->download();
    }

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerFileInfoCollectionInterface {
        $object_id = new ObjectId($context->exportObject()->getId());
        $collection = $context->fileFactory()->collection();
        $elements = $this->export_handler->repository()->handler()->getElements($object_id);
        $pa_element_identifier = $context->publicAccess()->getPublicAccessFileIdentifier($object_id);
        $pa_possible = $context->publicAccess()->typeRestriction()->isTypeAllowed($object_id, $this->getExportType());
        foreach ($elements as $element) {
            $is_pa_element = $element->getResourceId()->serialize() === $pa_element_identifier;
            $collection = $collection->withFileInfo(
                $this->export_handler->info()->file()->handler()
                ->withResourceId($element->getResourceId())
                ->withType($element->getFileType())
                ->withPublicAccessPossible($pa_possible)
                ->withPublicAccessEnabled($is_pa_element)
            );
        }
        return $collection;
    }

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): ilExportHandlerFileInfoCollectionInterface {
        $ids = [];
        $object_id = new ObjectId($context->exportObject()->getId());
        foreach ($table_row_ids as $table_row_id) {
            $ids[] = $table_row_id->getFileIdentifier();
        }
        $collection = $context->fileFactory()->collection();
        $elements = $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$ids);
        $pa_element_identifier = $context->publicAccess()->getPublicAccessFileIdentifier($object_id);
        $pa_possible = $context->publicAccess()->typeRestriction()->isTypeAllowed($object_id, $this->getExportType());
        foreach ($elements as $element) {
            $is_pa_element = $element->getResourceId()->serialize() === $pa_element_identifier;
            $collection = $collection->withFileInfo(
                $context->fileFactory()->fileInfoFromResourceId(
                    $element->getResourceId(),
                    $element->getFileType(),
                    $pa_possible
                )->withPublicAccessEnabled($is_pa_element)
            );
        }
        return $collection;
    }
}
