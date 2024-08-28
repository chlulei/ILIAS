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

namespace ILIAS\Export\ExportHandler\Manager;

use ILIAS\components\ResourceStorage\Container\Wrapper\ZipReader;
use ILIAS\Data\ReferenceId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilHandlerInterface as ilExportHandlerManagerInterface;
use ILIAS\Export\ExportHandler\I\Repository\Element\ilHandlerInterface as ilExportHandlerRepositoryElementInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilResourceStakeholderInterface as ilExportHandlerResourceStakeholderInterface;
use ILIAS\Export\ExportHandler\I\Target\ilHandlerInterface as ilExportHandlerTargetInterface;
use ILIAS\Export\ExportHandler\Info\Export\ilHandler as ilExportHandlerExportInfo;
use ILIAS\Export\ExportHandler\Target\ilHandler as ilExportHandlerTarget;
use ILIAS\Filesystem\Stream\FileStream;
use ILIAS\Filesystem\Stream\Streams;
use ilImportExportFactory;
use ilObject;
use ilObjUser;
use ILIAS\Export\ExportHandler\I\Manager\ReferenceId\ilCollectionInterface as ilExportHandlerManagerReferenceIdCollectionInterface;

use function Sabre\VObject\write;

class ilHandler implements ilExportHandlerManagerInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function createContainerExport(
        int $user_id,
        int $timestamp,
        ReferenceId $main_entity_ref_id,
        ilExportHandlerManagerReferenceIdCollectionInterface $ref_id_collection
    ): ilExportHandlerRepositoryElementInterface {
        $set_id = 1;
        $main_entity_export_info = $this->export_handler->manager()->handler()->getExportInfoOfRefId(
            $main_entity_ref_id,
            $timestamp
        )->withSetNumber($set_id);
        ;
        $main_element = $this->export_handler->manager()->handler()->createExportElementByRefId(
            $main_entity_ref_id,
            $user_id,
            $timestamp,
            "set_" . $set_id++
        );
        $export_infos = $this->export_handler->info()->export()->collection()->withExportInfo($main_entity_export_info);
        foreach ($ref_id_collection as $ref_id_handler) {
            $ref_id = $ref_id_handler->getReferenceId();
            $element = null;
            $export_info = null;
            if ($ref_id_handler->getReuseExport()) {
                $element = $this->export_handler->repository()->handler()->getElements($ref_id->toObjectId()->toInt())->newest();
                $export_info = $this->getExportInfoOfRefId($ref_id, $element->getLastModified()->getTimestamp())->withSetNumber($set_id);
            }
            if (!$ref_id_handler->getReuseExport()) {
                $element = $this->createExportElementByRefId($ref_id, $user_id, $timestamp, "");
                $export_info = $this->getExportInfoOfRefId($ref_id, $timestamp)->withSetNumber($set_id);
            }

            $zip_reader = new ZipReader($element->getStream());
            $zip_structure = $zip_reader->getStructure();
            foreach ($zip_structure as $path_inside_zip => $item) {
                if ($item['is_dir']) {
                    continue;
                }
                $stream = $zip_reader->getItem($path_inside_zip, $zip_structure)[0];
                $main_element->write($stream, "set_" . $set_id . DIRECTORY_SEPARATOR . $path_inside_zip);
            }
            $export_infos = $export_infos->withExportInfo($export_info);
            $set_id++;
        }
        $container = $this->export_handler->part()->container()->handler()
            ->withExportInfos($export_infos)
            ->withMainEntityExportInfo($main_entity_export_info);
        $main_element->write(Streams::ofString($container->getContainerManifestXML()), "manifest.xml");
        return $main_element;
    }

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler
    ) {
        $this->export_handler = $export_handler;
    }

    protected function writeToElement(
        string $path_in_container,
        ilExportHandlerExportInfo $export_info,
        ilExportHandlerRepositoryElementInterface $element
    ): void {
        $manifest = $this->export_handler->part()->manifest()->handler()
            ->withInfo($export_info);
        $element->write(Streams::ofString($manifest->getXML()), $path_in_container . DIRECTORY_SEPARATOR . $export_info->getExportFolderName() . DIRECTORY_SEPARATOR . "manifest.xml");
        foreach ($export_info->getComponentInfos() as $component_info) {
            $component = $this->export_handler->part()->component()->handler()
                ->withExportInfo($export_info)
                ->withComponentInfo($component_info);
            $element->write(Streams::ofString($component->getXML()), $path_in_container . DIRECTORY_SEPARATOR . $component_info->getPathInContainer());
        }
    }

    public function createExportElement(
        ilObject $source,
        int $user_id,
        int $timestamp,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface {
        $stakeholder = $this->getStakeholderOfUserId($user_id);
        $export_info = $this->getExportInfoOfObject($source, $timestamp);
        $element = $this->export_handler->repository()->handler()->createElement(
            $source->getId(),
            $export_info,
            $stakeholder
        );
        $this->writeToElement($path_in_container, $export_info, $element);
        return $element;
    }

    public function createExportElementByRefId(
        ReferenceId $ref_id,
        int $user_id,
        int $timestamp,
        string $path_in_container
    ): ilExportHandlerRepositoryElementInterface {
        $stakeholder = $this->getStakeholderOfUserId($user_id);
        $export_info = $this->getExportInfoOfRefId($ref_id, $timestamp);
        $element = $this->export_handler->repository()->handler()->createElement(
            $ref_id->toObjectId()->toInt(),
            $export_info,
            $stakeholder
        );
        $this->writeToElement($path_in_container, $export_info, $element);
        return $element;
    }

    public function appendObjectExport(
        ilObject $source,
        int $timestamp,
        string $path_in_container,
        ilExportHandlerRepositoryElementInterface $element
    ): void {
        $this->writeToElement($path_in_container, $this->getExportInfoOfObject($source, $timestamp), $element);
    }

    public function appendObjectExportByRefId(
        ReferenceId $ref_id,
        int $timestamp,
        string $path_in_container,
        ilExportHandlerRepositoryElementInterface $element
    ): void {
        $this->writeToElement($path_in_container, $this->getExportInfoOfRefId($ref_id, $timestamp), $element);
    }

    public function getTargetOfObject(ilObject $source): ilExportHandlerTargetInterface
    {
        $type = $source->getType();
        $class = ilImportExportFactory::getExporterClass($type);
        $comp = ilImportExportFactory::getComponentForExport($type);
        return $this->export_handler->target()->handler()
            ->withTargetRelease("")
            ->withType($type)
            ->withObjectIds([$source->getId()])
            ->withClassname($class)
            ->withComponent($comp);
    }

    public function getTargetOfRefId(
        ReferenceId $ref_id
    ): ilExportHandlerTargetInterface {
        $obj_id = $ref_id->toObjectId()->toInt();
        $type = ilObject::_lookupType($obj_id);
        $class = ilImportExportFactory::getExporterClass($type);
        $comp = ilImportExportFactory::getComponentForExport($type);
        return $this->export_handler->target()->handler()
            ->withTargetRelease("")
            ->withType($type)
            ->withObjectIds([$obj_id])
            ->withClassname($class)
            ->withComponent($comp);
    }

    public function getExportInfoOfObject(
        ilObject $source,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface {
        return $this->export_handler->info()->export()->handler()
            ->withTarget($this->getTargetOfObject($source), $time_stamp);
    }

    public function getExportInfoOfRefId(
        ReferenceId $ref_id,
        int $time_stamp
    ): ilExportHandlerExportInfoInterface {
        return $this->export_handler->info()->export()->handler()
            ->withTarget($this->getTargetOfRefId($ref_id), $time_stamp);
    }

    public function getStakeholderOfUser(ilObjUser $user): ilExportHandlerResourceStakeholderInterface
    {
        return $this->export_handler->repository()->stakeholder()->withOwnerId($user->getId());
    }

    public function getStakeholderOfUserId(int $user_id): ilExportHandlerResourceStakeholderInterface
    {
        return $this->export_handler->repository()->stakeholder()->withOwnerId($user_id);
    }

    public function createRefIdCollection(
        array $ref_ids_export,
        array $ref_ids_all
    ): ilExportHandlerManagerReferenceIdCollectionInterface {
        $ref_ids = $this->export_handler->manager()->referenceId()->collection();
        foreach ($ref_ids_all as $ref_id) {
            $ref_ids = $ref_ids->withReferenceId(
                $this->export_handler->manager()->referenceId()->handler()
                    ->withReferenceId(new ReferenceId($ref_id))
                    ->withReuseExport(!in_array($ref_id, $ref_ids_export))
            );
        }
        return $ref_ids;
    }
}
