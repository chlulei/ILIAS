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

namespace ILIAS\Export\ExportHandler\I\Consumer\File\Identification;

interface ilHandlerInterface
{
    public function withExportOptionId(string $export_option_id): ilHandlerInterface;

    public function withFileId(string $file_id): ilHandlerInterface;

    public function withCompositId(string $composit_id): ilHandlerInterface;

    public function getExportOptionId(): string;

    public function getFileId(): string;

    public function compositId(): string;
}
