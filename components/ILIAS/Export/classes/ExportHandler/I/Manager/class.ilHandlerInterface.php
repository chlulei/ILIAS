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

use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerResourceStakeholderInterface;
use ILIAS\Export\ExportHandler\I\Target\ilHandlerInterface as ilExportHandlerTargetInterface;
use ilObject;
use ilObjUser;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilCollectionInterface as ilExportHandlerManagerReferenceIdCollectionInterface;

interface ilHandlerInterface
{
    public function createContainerExport(
        int $user_id,
        int $timestamp,
        ReferenceId $main_entity_ref_id,
        ilExportHandlerManagerReferenceIdCollectionInterface $ref_id_collection
    ): ilExportHandlerRepositoryElementInterface;

    public function createExportElement(
        ilObject $source,
        int $user_id,
        int $timestamp,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface;

    public function createExportElementByRefId(
        ReferenceId $ref_id,
        int $user_id,
        int $timestamp,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface;

    public function appendObjectExport(
        ilObject $source,
        int $timestamp,
        string $path_in_container,
        ilExportHandlerRepositoryElementInterface $element
    ): void;

    public function appendObjectExportByRefId(
        ReferenceId $ref_id,
        int $timestamp,
        string $path_in_container,
        ilExportHandlerRepositoryElementInterface $element
    ): void;

    public function getTargetOfObject(
        ilObject $source
    ): ilExportHandlerTargetInterface;

    public function getTargetOfRefId(
        ReferenceId $ref_id
    ): ilExportHandlerTargetInterface;

    public function getExportInfoOfObject(
        ilObject $source,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface;

    public function getExportInfoOfRefId(
        ReferenceId $ref_id,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface;

    /**
     * @param int[] $ref_ids_export
     * @param int[] $ref_ids_reuse
     */
    public function createRefIdCollection(
        array $ref_ids_export,
        array $ref_ids_all
    ): ilExportHandlerManagerReferenceIdCollectionInterface;
}
