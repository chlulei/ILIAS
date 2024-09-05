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

namespace ILIAS\Export\ExportHandler\I\Manager;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Info\Export\Container\ilHandlerInterface as ilExportHandlerContainerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;

interface ilHandlerInterface
{
    public function createContainerExport(
        int $user_id,
        #int $timestamp,
        ilExportHandlerContainerExportInfoInterface $container_export_info
    ): ilExportHandlerRepositoryElementInterface;

    public function createExport(
        int $user_id,
        ilExportHandlerExportInfoInterface $export_info,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface;

    public function getExportInfo(
        ObjectId $object_id,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface;

    /**
     * @param ObjectId[] $object_ids_to_export
     * @param ObjectId[] $object_ids_all
     */
    public function getContainerExportInfo(
        ObjectId $main_entity_object_id,
        array $object_ids_to_export,
        array $object_ids_all
    ): ilExportHandlerContainerExportInfoInterface;
}
