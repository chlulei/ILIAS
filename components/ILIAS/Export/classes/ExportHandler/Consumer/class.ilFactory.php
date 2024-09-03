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

namespace ILIAS\Export\ExportHandler\Consumer;

use ilAccessHandler;
use ilCtrlInterface;
use ilDBInterface;
use ILIAS\Export\ExportHandler\Consumer\Context\ilFactory as ilExportHandlerConsumerContextFactory;
use ILIAS\Export\ExportHandler\Consumer\ExportOption\ilFactory as ilExportHandlerConsumerExportOptionFactory;
use ILIAS\Export\ExportHandler\Consumer\File\ilFactory as ilExportHandlerConsumerFileFactory;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilFactoryInterface as ilExportHandlerConsumerContextFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ExportOption\ilFactoryInterface as ilExportHandlerConsumerExportOptionFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilFactoryInterface as ilExportHandlerConsumerFileFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ilFactoryInterface as ilExportHandlerConsumerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\ilHandlerInterface as ilExportHandlerConsumerInterface;
use ILIAS\Export\ExportHandler\Consumer\ilHandler as ilExportHandlerConsumer;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ilLanguage;
use ilObjUser;

class ilFactory implements ilExportHandlerConsumerFactoryInterface
{
    protected ilCtrlInterface $ctrl;
    protected ilLanguage $lng;
    protected ilDBInterface $db;
    protected ResourcesStorageService $irss;
    protected ilObjUser $user;
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ilAccessHandler $il_access;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ilCtrlInterface $ctrl,
        ilLanguage $lng,
        ilDBInterface $db,
        ilObjUser $user,
        ResourcesStorageService $irss,
        ilAccessHandler $il_access
    ) {
        $this->export_handler = $export_handler;
        $this->lng = $lng;
        $this->ctrl = $ctrl;
        $this->db = $db;
        $this->irss = $irss;
        $this->user = $user;
        $this->il_access = $il_access;
    }

    public function handler(): ilExportHandlerConsumerInterface
    {
        return new ilExportHandlerConsumer($this->export_handler);
    }

    public function exportOption(): ilExportHandlerConsumerExportOptionFactoryInterface
    {
        return new ilExportHandlerConsumerExportOptionFactory($this->export_handler);
    }

    public function context(): ilExportHandlerConsumerContextFactoryInterface
    {
        return new ilExportHandlerConsumerContextFactory(
            $this->export_handler,
            $this->ctrl,
            $this->lng,
            $this->db,
            $this->user,
            $this->irss,
            $this->il_access
        );
    }

    public function file(): ilExportHandlerConsumerFileFactoryInterface
    {
        return new ilExportHandlerConsumerFileFactory($this->export_handler);
    }
}
