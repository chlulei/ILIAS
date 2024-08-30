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

use ilDashboardGUI;
use ILIAS\Export\ExportHandler\ilFactory as ilExportHandler;
use ILIAS\StaticURL\Context;
use ILIAS\StaticURL\Handler\BaseHandler;
use ILIAS\StaticURL\Handler\Handler as StaticURLHandler;
use ILIAS\StaticURL\Request\Request;
use ILIAS\StaticURL\Response\Factory;
use ILIAS\StaticURL\Response\Response;

class ilStaticUrlHandler
{
    public const NAMESPACE = "export";
    public const DOWNLOAD = 'download';

    protected ilExportHandler $export_handler;

    public function __construct()
    {
        $this->export_handler = new ilExportHandler();
    }

    public function getNamespace(): string
    {
        return self::NAMESPACE;
    }

    public function handle(Request $request, Context $context, Factory $response_factory): Response
    {
        $operation = $request->getAdditionalParameters()[0] ?? null;
        $object_id = $request->getReferenceId()->toObjectId()->toInt() ?? -1;
        $ref_id = $request->getReferenceId();
        $access_granted = false;
        if ($context->isUserLoggedIn() and $context->checkPermission("read", $ref_id->toInt())) {
            $access_granted = true;
        }
        if ($context->getUserId() === ANONYMOUS_USER_ID and $context->isPublicSectionActive()) {
            $access_granted = true;
        }
        if (!$access_granted or $operation !== self::DOWNLOAD or $object_id === -1) {
            return $response_factory->can($context->ctrl()->getLinkTargetByClass(ilDashboardGUI::class));
        }
        $element = $this->export_handler->publicAccess()->repository()->handler()->getElement($ref_id);
        $element->download();
        return $response_factory->cannot();
    }
}
