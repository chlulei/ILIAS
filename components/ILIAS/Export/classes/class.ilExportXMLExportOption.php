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

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilBasicHandler as ilExportHandlerConsumerBasicExportOption;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilCollectionInterface as ilExportHandlerFileInfoCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowIdCollectionInterface;

class ilExportXMLExportOption extends ilExportHandlerConsumerBasicExportOption
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function withExportHandler(ilExportHandlerFactoryInterface $export_handler): ilExportXMLExportOption
    {
        $clone = clone $this;
        $clone->export_handler = $export_handler;
        return $clone;
    }

    public function getExportType(): string
    {
        return "xml";
    }

    public function getExportOptionId(): string
    {
        return "expxml";
    }

    public function publicAccessPossible(): bool
    {
        return true;
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
        $object_id = new ObjectId($context->exportObject()->getId());
        $this->export_handler->repository()->handler()->deleteElements(
            $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$table_row_ids->fileIdentifiers()),
            $this->export_handler->repository()->stakeholder()->withOwnerId($context->user()->getId())
        );
    }

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void {
        $object_id = new ObjectId($context->exportObject()->getId());
        $elements = $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$table_row_ids->fileIdentifiers());
        foreach ($elements as $element) {
            $element->download();
        }
    }

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerFileInfoCollectionInterface {
        $object_id = new ObjectId($context->exportObject()->getId());
        return $this->buildElements($context, $object_id, [], true);
    }

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): ilExportHandlerFileInfoCollectionInterface {
        $object_id = new ObjectId($context->exportObject()->getId());
        return $this->buildElements($context, $object_id, $table_row_ids->fileIdentifiers());
    }

    protected function buildElements(
        ilExportHandlerConsumerContextInterface $context,
        ObjectId $object_id,
        array $ids,
        bool $all_elements = false
    ): ilExportHandlerFileInfoCollectionInterface {
        $collection = $context->fileFactory()->collection();
        $elements = $all_elements
            ? $this->export_handler->repository()->handler()->getElements($object_id)
            : $this->export_handler->repository()->handler()->getElementsByResourceIds($object_id, ...$ids);
        foreach ($elements as $element) {
            $file_info = $context->fileFactory()->fileInfoFromResourceId(
                $element->getResourceId(),
                $context,
                $this
            );
            $collection = $collection->withFileInfo($file_info);
        }
        return $collection;
    }
}
