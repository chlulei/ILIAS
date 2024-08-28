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

namespace ILIAS\Export\ExportHandler\Consumer\File\Identification;

use ILIAS\Export\ExportHandler\I\Consumer\File\Identification\ilHandlerInterface as ilExportHandlerConsumerFileIdentificationInterface;

class ilHandler implements ilExportHandlerConsumerFileIdentificationInterface
{
    protected string $export_option_id;
    protected string $file_id;

    public function withExportOptionId(string $export_option_id): ilExportHandlerConsumerFileIdentificationInterface
    {
        $clone = clone $this;
        $clone->export_option_id = $export_option_id;
        return $clone;
    }

    public function withFileId(string $file_id): ilExportHandlerConsumerFileIdentificationInterface
    {
        $clone = clone $this;
        $clone->file_id = $file_id;
        return $clone;
    }

    public function withCompositId(string $composit_id): ilExportHandlerConsumerFileIdentificationInterface
    {
        $parts = explode(':', $composit_id);
        $clone = clone $this;
        $clone->export_option_id = $parts[0];
        $clone->file_id = $parts[1];
        return $clone;
    }

    public function compositId(): string
    {
        return $this->export_option_id . ":" . $this->file_id;
    }

    public function getExportOptionId(): string
    {
        return $this->export_option_id;
    }

    public function getFileId(): string
    {
        return $this->file_id;
    }
}
