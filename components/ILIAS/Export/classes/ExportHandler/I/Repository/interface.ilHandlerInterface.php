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

namespace ILIAS\Export\ExportHandler\I\Repository;

use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilCollectionInterface as ilExportHandlerRepositoryElementCollectionInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerRepositoryResourceStakeholderInterface;

interface ilHandlerInterface
{
    public const TABLE_NAME = "export_files";
    public const TMP_FILE_PATH = "tmp_file_ztopslcaneadw";
    public const TMP_FILE_CONTENT = "tmp_file_content";

    public function createElement(
        ReferenceId $reference_id,
        ilExportHandlerExportInfoInterface $info,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): ilExportHandlerRepositoryElementInterface;

    public function storeElement(ilExportHandlerRepositoryElementInterface $element): bool;

    public function deleteElement(
        ilExportHandlerRepositoryElementInterface $element,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): bool;

    public function deleteElements(
        ilExportHandlerRepositoryElementCollectionInterface $elements,
        ilExportHandlerRepositoryResourceStakeholderInterface $stakeholder
    ): bool;

    public function getElement(ReferenceId $reference_id, string $resource_id_serialized): ?ilExportHandlerRepositoryElementInterface;

    public function getElements(ReferenceId $reference_id): ilExportHandlerRepositoryElementCollectionInterface;

    public function getElementsByResourceIds(ReferenceId $reference_id, string ...$resource_ids_serialized): ilExportHandlerRepositoryElementCollectionInterface;
}
