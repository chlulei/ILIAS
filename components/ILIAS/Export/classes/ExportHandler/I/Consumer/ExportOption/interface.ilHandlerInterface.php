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

use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilCollectionInterface as ilExportHandlerConsumerFileCollectionInterface;
use ILIAS\Export\ExportHandler\I\Table\RowId\ilCollectionInterface as ilExportHandlerTableRowIdCollectionInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\Repository\Element\ilCollectionInterface as ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface;

interface ilHandlerInterface
{
    public function getExportType(): string;

    public function getExportOptionId(): string;

    public function getLabel(
        ilExportHandlerConsumerContextInterface $context
    ): string;

    public function onPublicAccessTypeRestrictionsChanged(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerPublicAccessTypeRestrictionRepitoryElementCollectionInterface $allowed_types
    ): void;

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

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerConsumerFileCollectionInterface;

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerTableRowIdCollectionInterface $table_row_ids
    ): ilExportHandlerConsumerFileCollectionInterface;
}
