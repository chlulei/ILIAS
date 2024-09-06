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

namespace ILIAS\Export\ExportHandler\Consumer\Context;

use ilAccessHandler;
use ilCtrlInterface;
use ilDBInterface;
use ilExportGUI;
use ILIAS\Export\ExportHandler\Consumer\Context\ilHandler as ilExportHandlerConsumerContext;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilFactoryInterface as ilExportHandlerConsumerContextFactoryInterface;
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ilLanguage;
use ilObject;
use ilObjUser;

class ilFactory implements ilExportHandlerConsumerContextFactoryInterface
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

    public function handler(
        ilExportGUI $export_gui,
        ilObject $export_object
    ): ilExportHandlerConsumerContextInterface {
        return new ilExportHandlerConsumerContext(
            $this->ctrl,
            $this->lng,
            $this->db,
            $this->irss,
            $this->user,
            $export_gui,
            $export_object,
            $this->export_handler->consumer()->file(),
            $this->il_access
        );
    }
}
