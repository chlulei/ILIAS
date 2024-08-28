<?php

namespace ILIAS\Export\ExportHandler\Part;

use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Part\Component\ilFactoryInterface as ilExportHanlderPartComponentFactoryInterface;
use ILIAS\Export\ExportHandler\I\Part\Container\ilFactoryInterface as ilExportHanlderPartContainerFactoryInterface;
use ILIAS\Export\ExportHandler\Part\Container\ilFactory as ilExportHanlderPartContainerFactory;
use ILIAS\Export\ExportHandler\I\Part\ilFactoryInterface as ilExportHandlerPartFactoryInterface;
use ILIAS\Export\ExportHandler\I\Part\Manifest\ilFactoryInterface as ilExportHanlderPartManifestFactoryInterface;
use ILIAS\Export\ExportHandler\Part\Component\ilFactory as ilExportHanlderPartComponentFactory;
use ILIAS\Export\ExportHandler\Part\Manifest\ilFactory as ilExportHanlderPartManifestFactory;

class ilFactory implements ilExportHandlerPartFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;

    public function __construct(ilExportHandlerFactoryInterface $export_handler)
    {
        $this->export_handler = $export_handler;
    }

    public function manifest(): ilExportHanlderPartManifestFactoryInterface
    {
        return new ilExportHanlderPartManifestFactory($this->export_handler);
    }

    public function component(): ilExportHanlderPartComponentFactoryInterface
    {
        return new ilExportHanlderPartComponentFactory($this->export_handler);
    }

    public function container(): ilExportHanlderPartContainerFactoryInterface
    {
        return new ilExportHanlderPartContainerFactory($this->export_handler);
    }
}
