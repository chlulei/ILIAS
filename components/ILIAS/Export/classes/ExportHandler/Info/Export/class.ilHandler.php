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

namespace ILIAS\Export\ExportHandler\Info\Export;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\Component\ilCollectionInterface as ilExportHandlerExportComponentInfoCollectionInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\Component\ilHandlerInterface as ilExportHandlerExportComponentInfoInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHandlerExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Target\ilHandlerInterface as ilExportHandlerTargetInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\Container\ilHandlerInterface as ilExportHandlerContainerExportInfoInterface;

class ilHandler implements ilExportHandlerExportInfoInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilExportHandlerTargetInterface $export_target;
    protected ilExportHandlerExportComponentInfoCollectionInterface $component_export_infos;
    protected ilExportHandlerContainerExportInfoInterface $container_export_info;
    protected array $component_counts;
    protected bool $reuse_export;
    protected int $time_stamp;
    protected int $set_number;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
        $this->component_export_infos = $this->export_handler->info()->export()->component()->collection();
        $this->component_counts = [];
    }

    protected function getPathInContainer(string $export_folder_name, string $component, int $component_count): string
    {
        return $export_folder_name . DIRECTORY_SEPARATOR . $component . DIRECTORY_SEPARATOR . "set_" . $component_count . DIRECTORY_SEPARATOR . "export.xml";
    }

    protected function initComponentInfos(): void
    {
        $component_info = $this->export_handler->info()->export()->component()->handler()->withExportTarget($this->getTarget());
        $this->component_export_infos = $this->export_handler->info()->export()->component()->collection();
        foreach ($this->recComponentInfos($component_info) as $component_info) {
            if (!isset($this->component_counts[$component_info->getTarget()->getComponent()])) {
                $this->component_counts[$component_info->getTarget()->getComponent()] = -1;
            }
            $this->component_counts[$component_info->getTarget()->getComponent()] += 1;
            $path_in_container = $this->getPathInContainer(
                $this->getExportFolderName(),
                $component_info->getTarget()->getComponent(),
                $this->component_counts[$component_info->getTarget()->getComponent()]
            );
            $this->component_export_infos = $this->component_export_infos->withComponent($component_info->withPathInContainer($path_in_container));
        }
    }

    protected function recComponentInfos(
        ilExportHandlerExportComponentInfoInterface $component_info
    ): ilExportHandlerExportComponentInfoCollectionInterface {
        $new_component_infos = $this->export_handler->info()->export()->component()->collection();
        foreach ($component_info->getHeadComponentInfos() as $head_component_info) {
            $new_component_infos = $new_component_infos->mergedWith($this->recComponentInfos($head_component_info));
        }
        $new_component_infos = $new_component_infos->withComponent($component_info);
        foreach ($component_info->getTailComponentInfos() as $tail_component_info) {
            $new_component_infos = $new_component_infos->mergedWith($this->recComponentInfos($tail_component_info));
        }
        return $new_component_infos;
    }

    public function withTarget(
        ilExportHandlerTargetInterface $export_target,
        int $timestamp
    ): ilExportHandlerExportInfoInterface {
        $clone = clone $this;
        $clone->export_target = $export_target;
        $clone->time_stamp = $timestamp;
        $clone->initComponentInfos();
        return $clone;
    }

    public function withSetNumber(int $set_number): ilExportHandlerExportInfoInterface
    {
        $clone = clone $this;
        $clone->set_number = $set_number;
        return $clone;
    }

    public function withContainerExportInfo(
        ilExportHandlerContainerExportInfoInterface $container_export_info
    ): ilExportHandlerExportInfoInterface {
        $clone = clone $this;
        $clone->container_export_info = $container_export_info;
        return $clone;
    }

    public function withReuseExport(
        bool $reuse_export
    ): ilExportHandlerExportInfoInterface {
        $clone = clone $this;
        $clone->reuse_export = $reuse_export;
        return $clone;
    }

    public function getResueExport(): bool
    {
        return $this->reuse_export;
    }

    public function getTarget(): ilExportHandlerTargetInterface
    {
        return $this->export_target;
    }

    public function getTargetObjectId(): ObjectId
    {
        return new ObjectId($this->export_target->getObjectIds()[0]);
    }

    public function getComponentCount(ilExportHandlerExportComponentInfoInterface $component_info)
    {
        return $this->component_counts[$component_info->getTarget()->getComponent()] ?? -1;
    }

    public function getComponentInfos(): ilExportHandlerExportComponentInfoCollectionInterface
    {
        return $this->component_export_infos;
    }

    public function getExportFolderName(): string
    {
        return $this->time_stamp . '__' . IL_INST_ID . '__' . $this->export_target->getType() . '_' . $this->export_target->getObjectIds()[0];
        ;
    }

    public function getZipFileName(): string
    {
        return $this->time_stamp . '__' . IL_INST_ID . '__' . $this->export_target->getType() . '_' . $this->export_target->getObjectIds()[0] . ".zip";
    }

    public function getHTTPPath(): string
    {
        return ILIAS_HTTP_PATH;
    }

    public function getInstallationId(): string
    {
        return (string) IL_INST_ID;
    }

    public function getInstallationUrl(): string
    {
        return ILIAS_HTTP_PATH;
    }

    public function getSetNumber(): int
    {
        return $this->set_number;
    }
}
