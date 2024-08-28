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
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilCollectionInterface as ilExportHandlerConsumerFileIdentificationCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilCollectionInterface as ilExportHandlerConsumerFileCollectionInterface;

interface ilHandlerInterface
{
    public function getExportType(): string;

    public function getLabel(
        ilExportHandlerConsumerContextInterface $context
    ): string;

    public function onExportOptionSelected(
        ilExportHandlerConsumerContextInterface $context
    ): void;

    public function onDeleteFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerFileIdentificationCollectionInterface $file_identifications
    ): void;

    public function onDownloadFiles(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerFileIdentificationCollectionInterface $file_identifications
    ): void;

    public function getFiles(
        ilExportHandlerConsumerContextInterface $context
    ): ilExportHandlerConsumerFileCollectionInterface;

    public function getFileSelection(
        ilExportHandlerConsumerContextInterface $context,
        ilExportHandlerConsumerFileIdentificationCollectionInterface $file_identifications
    ): ilExportHandlerConsumerFileCollectionInterface;
}
