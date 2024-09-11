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

namespace ILIAS\Export\ExportHandler\I\Consumer\ExportOption;

use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilCollectionInterface as ilExportHandlerFileInfoCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowIdCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilHandlerInterface as ilExportHandlerTableRowIdInterface;

interface ilHandlerInterface
{
    public function getExportType(): string;

    public function getExportOptionId(): string;

    public function getSupportedRepositoryObjectTypes(): array;

    public function isPublicAccessPossible(): bool;

    public function getLabel(
        ilExportHandlerConsumerContextInterface $context
    ): string;

    public function onExportOptionSelected(
        ilExportHandlerConsumerContextInterface $context
    ): void;

    public function onDeleteFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void;

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): void;

    public function onDownloadWithLink(
        ReferenceId $reference_id,
        ilExportHandlerTableRowIdInterface $table_row_id
    ): void;

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerFileInfoCollectionInterface;

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): ilExportHandlerFileInfoCollectionInterface;
}
