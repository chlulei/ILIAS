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

use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilCollectionInterface as ilExportHandlerConsumerExportOptionCollectionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilHandlerInterface as ilExportHandlerConsumerExportOptionInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilHandlerInterface as ilExportHandlerConsumerFileIdentificationInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilHandlerInterface as ilExportHandlerFileInfoInterface;

class ilCollection implements ilExportHandlerConsumerExportOptionCollectionInterface
{
    protected const KEY_OPTION = "option";
    protected const KEY_ID = "id";

    protected ilExportHandlerFactoryInterface $export_handler;
    protected array $elements;
    protected int $index;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->elements = [];
        $this->index = 0;
        $this->export_handler = $export_handler;
    }

    public function addExportOption(
        ilExportHandlerConsumerExportOptionInterface $export_option,
        string $id
    ): ilExportHandlerConsumerExportOptionCollectionInterface {
        $clone = clone $this;
        $clone->elements[] = [
            self::KEY_OPTION => $export_option,
            self::KEY_ID => $id
        ];
        return $clone;
    }

    public function getByIndex(int $index): ?ilExportHandlerConsumerExportOptionInterface
    {
        return $this->elements[$index][self::KEY_OPTION] ?? null;
    }

    public function getById(string $id): ?ilExportHandlerConsumerExportOptionInterface
    {
        foreach ($this->elements as $element) {
            if ($element[self::KEY_ID] === $id) {
                return $element[self::KEY_OPTION];
            }
        }
        return null;
    }

    public function getIdByIndex(int $index): ?string
    {
        return $this->elements[$index][self::KEY_ID] ?? null;
    }

    /**
     * @return array<string, ilExportHandlerFileInfoInterface>
     */
    public function getIdFileInfoPairs(
        int $index,
        ilExportHandlerConsumerContextInterface $context
    ): array {
        $element = $this->elements[$index] ?? null;
        if(is_null($element)) {
            return [];
        }
        $pairs = [];
        /** @var ilExportHandlerConsumerExportOptionInterface $export_option */
        $export_option = $element[self::KEY_OPTION];
        $id = $element[self::KEY_ID];
        foreach ($export_option->getFiles($context) as $file) {
            $file_id = $this->export_handler->consumer()->file()->identification()->handler()
                ->withFileId($file->getFileIdentifier())
                ->withExportOptionId($id);
            $pairs[$file_id->compositId()] = $file;
        }
        return $pairs;
    }

    public function getMatchingIdentifier(ilExportHandlerConsumerFileIdentificationInterface $file_identification): ?string
    {
        foreach ($this->elements as $element) {
            if ($element[self::KEY_ID] === $file_identification->getExportOptionId()) {
                return $element[self::KEY_ID];
            }
        }
        return null;
    }

    public function getMatchingExportOption(
        ilExportHandlerConsumerFileIdentificationInterface $file_identification
    ): ?ilExportHandlerConsumerExportOptionInterface {
        global $DIC;
        for ($i = 0; $i < 20; $i++) {
            $DIC->logger()->root()->debug("-----------------------");
        }
        foreach ($this->elements as $element) {
            $DIC->logger()->root()->debug($element[self::KEY_ID] . " === " . $file_identification->getExportOptionId());

            if ($element[self::KEY_ID] === $file_identification->getExportOptionId()) {
                return $element[self::KEY_OPTION];
            }
        }
        return null;
    }

    public function current(): ilExportHandlerConsumerExportOptionInterface
    {
        return $this->elements[$this->index][self::KEY_OPTION];
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->index++;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return isset($this->elements[$this->index]);
    }

    public function count(): int
    {
        return count($this->elements);
    }
}
