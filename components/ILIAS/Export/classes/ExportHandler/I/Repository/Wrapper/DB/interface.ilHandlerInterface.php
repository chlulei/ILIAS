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

namespace ILIAS\Export\ExportHandler\I\Repository\Wrapper\DB;

use ILIAS\Export\ExportHandler\I\Repository\Element\ilCollectionInterface as ilExportHandlerRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\Key\ilCollectionInterface as ilExportHandlerRepositoryKeyCollectionInterface;

interface ilHandlerInterface
{
    public const TABLE_NAME = "export_files";

    public function store(
        ilExportHandlerRepositoryElementInterface $element
    ): void;

    public function getElements(
        ilExportHandlerRepositoryKeyCollectionInterface $keys
    ): ilExportHandlerRepositoryElementCollectionInterface;

    public function deleteElements(
        ilExportHandlerRepositoryKeyCollectionInterface $keys
    ): void;
}
