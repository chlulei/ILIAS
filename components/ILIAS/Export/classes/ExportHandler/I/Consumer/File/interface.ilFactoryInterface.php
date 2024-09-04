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

namespace ILIAS\Export\ExportHandler\I\Consumer\File;

use ILIAS\Export\ExportHandler\I\Info\File\ilCollectionInterface as ilExportHandlerFileInfoCollectionInterface;
use ILIAS\Export\ExportHandler\I\Info\File\ilHandlerInterface as ilExportHandlerFileInfoInterface;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;
use SplFileInfo;

interface ilFactoryInterface
{
    public function collection(): ilExportHandlerFileInfoCollectionInterface;

    public function fileInfoFromSplFileInfo(
        SplFileInfo $spl_file_info,
        string $type,
        bool $public_access_possible
    ): ilExportHandlerFileInfoInterface;

    public function fileInfoFromResourceId(
        ResourceIdentification $resource_id,
        string $type,
        bool $public_access_possible
    ): ilExportHandlerFileInfoInterface;
}
