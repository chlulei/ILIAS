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

namespace ILIAS\Export\ExportHandler;

use ilAccessHandler;
use ilCtrlInterface;
use ilDBInterface;
use ILIAS\Export\ExportHandler\Consumer\ilFactory as ilExportHandlderConsumerFactory;
use ILIAS\Export\ExportHandler\I\Consumer\ilFactoryInterface as ilExportHandlderConsumerFactoryInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Info\ilFactoryInterface as ilExportHandlerInfoFactoryInterface;
use ILIAS\Export\ExportHandler\I\Manager\ilFactoryInterface as ilExportHandlerManagerFactoryInterface;
use ILIAS\Export\ExportHandler\I\Part\ilFactoryInterface as ilExportHandlerPartFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilFactoryInterface as ilExportHandlerPublicAccessFactoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilFactoryInterface as ilExportHandlerRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\I\Target\ilFactoryInterface as ilExportHandlerTargetFactoryInterface;
use ILIAS\Export\ExportHandler\Info\ilFactory as ilExportHandlerInfoFactory;
use ILIAS\Export\ExportHandler\Manager\ilFactory as ilExportHandlerManagerFactory;
use ILIAS\Export\ExportHandler\Part\ilFactory as ilExportHandlerPartFactory;
use ILIAS\Export\ExportHandler\PublicAccess\ilFactory as ilExportHandlerPublicAccessFactory;
use ILIAS\Export\ExportHandler\Repository\ilFactory as ilExportHandlerRepositoryFactory;
use ILIAS\Export\ExportHandler\Target\ilFactory as ilExportHandlerTargetFactory;
use ILIAS\Filesystem\Filesystems;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ILIAS\StaticURL\Services as StaticUrl;
use ilLanguage;
use ilObjUser;

class ilFactory implements ilExportHandlerFactoryInterface
{
    protected ilDBInterface $db;
    protected ilLanguage $lng;
    protected ilCtrlInterface $ctrl;
    protected ResourcesStorageService $irss;
    protected Filesystems $filesystems;
    protected StaticURL $static_url;
    protected ilObjUser $user;
    protected ilAccessHandler $il_access;

    public function __construct()
    {
        global $DIC;
        $this->db = $DIC->database();
        $this->irss = $DIC->resourceStorage();
        $this->filesystems = $DIC->filesystem();
        $this->static_url = $DIC['static_url'];
        $this->lng = $DIC->language();
        $this->ctrl = $DIC->ctrl();
        $this->user = $DIC->user();
        $this->il_access = $DIC->access();
    }

    public function part(): ilExportHandlerPartFactoryInterface
    {
        return new ilExportHandlerPartFactory($this);
    }

    public function info(): ilExportHandlerInfoFactoryInterface
    {
        return new ilExportHandlerInfoFactory($this, $this->irss);
    }

    public function target(): ilExportHandlerTargetFactoryInterface
    {
        return new ilExportHandlerTargetFactory($this);
    }

    public function repository(): ilExportHandlerRepositoryFactoryInterface
    {
        return new ilExportHandlerRepositoryFactory($this, $this->db, $this->irss, $this->filesystems);
    }

    public function publicAccess(): ilExportHandlerPublicAccessFactoryInterface
    {
        return new ilExportHandlerPublicAccessFactory($this, $this->irss, $this->db, $this->static_url);
    }

    public function manager(): ilExportHandlerManagerFactoryInterface
    {
        return new ilExportHandlerManagerFactory($this);
    }

    public function consumer(): ilExportHandlderConsumerFactoryInterface
    {
        return new ilExportHandlderConsumerFactory(
            $this,
            $this->ctrl,
            $this->lng,
            $this->db,
            $this->user,
            $this->irss,
            $this->il_access
        );
    }
}
