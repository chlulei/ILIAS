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
use ILIAS\Export\ExportHandler\I\Consumer\Context\ilHandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilFactoryInterface as ilExportHandlerConsumerFileFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilHandlerInterface as ilExportHandlerPublicAccessInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ilLanguage;
use ilObject;
use ilObjUser;

class ilHandler implements ilExportHandlerConsumerContextInterface
{
    protected ilCtrlInterface $ctrl;
    protected ilLanguage $lng;
    protected ilDBInterface $db;
    protected ResourcesStorageService $irss;
    protected ilExportGUI $export_gui;
    protected ilObject $export_object;
    protected ilObjUser $user;
    protected ilExportHandlerConsumerFileFactoryInterface $file_factory;
    protected ilAccessHandler $il_access;

    public function __construct(
        ilCtrlInterface $ctrl,
        ilLanguage $lng,
        ilDBInterface $db,
        ResourcesStorageService $irss,
        ilObjUser $user,
        ilExportGUI $export_gui,
        ilObject $export_object,
        ilExportHandlerConsumerFileFactoryInterface $file_factory,
        ilAccessHandler $il_access
    ) {
        $this->ctrl = $ctrl;
        $this->lng = $lng;
        $this->db = $db;
        $this->irss = $irss;
        $this->user = $user;
        $this->export_gui = $export_gui;
        $this->export_object = $export_object;
        $this->file_factory = $file_factory;
        $this->il_access = $il_access;
    }

    public function ilCtrl(): ilCtrlInterface
    {
        return $this->ctrl;
    }

    public function ilLng(): ilLanguage
    {
        return $this->lng;
    }

    public function ilDB(): ilDBInterface
    {
        return $this->db;
    }

    public function irss(): ResourcesStorageService
    {
        return $this->irss;
    }

    public function user(): ilObjUser
    {
        return $this->user;
    }

    public function exportGUIObject(): ilExportGUI
    {
        return $this->export_gui;
    }

    public function exportObject(): ilObject
    {
        return $this->export_object;
    }

    public function fileFactory(): ilExportHandlerConsumerFileFactoryInterface
    {
        return $this->file_factory;
    }

    public function ilAccess(): ilAccessHandler
    {
        return $this->il_access;
    }
}
