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

use Countable;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilHandlerInterface as ilExportHandlerConsumerFileIdentificationInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilHandlerInterface as ilExportHandlerFileInfoInterface;
use Iterator;

interface ilCollectionInterface extends Iterator, Countable
{
    public function addExportOption(
        ilExportHandlerConsumerExportOptionInterface $export_option,
        string $id
    ): ilCollectionInterface;

    public function getById(string $id): ?ilExportHandlerConsumerExportOptionInterface;

    public function getByIndex(int $index): ?ilExportHandlerConsumerExportOptionInterface;

    public function getIdByIndex(int $index): ?string;

    /**
     * @return array<string, ilExportHandlerFileInfoInterface>
     */
    public function getIdFileInfoPairs(
        int $index,
        ilExportHandlerConsumerContextInterface $context
    ): array;

    public function getMatchingExportOption(
        ilExportHandlerConsumerFileIdentificationInterface $file_identification
    ): ?ilExportHandlerConsumerExportOptionInterface;

    public function getMatchingIdentifier(
        ilExportHandlerConsumerFileIdentificationInterface $file_identification
    ): ?string;

    public function current(): ilExportHandlerConsumerExportOptionInterface;

    public function key(): int;

    public function next(): void;

    public function rewind(): void;

    public function valid(): bool;

    public function count(): int;
}
