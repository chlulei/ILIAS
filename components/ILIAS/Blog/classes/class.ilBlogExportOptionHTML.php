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

use ILIAS\Export\ExportHandler\Consumer\ExportOption\BasicLegacyHandler as ilBasicLegacyExportOption;
use ILIAS\Export\ExportHandler\I\Consumer\Context\HandlerInterface as ilExportHandlerConsumerContextInterface;
use ILIAS\DI\Container;

class ilBlogExportOptionHTML extends ilBasicLegacyExportOption
{
    protected ilLanguage $lng;

    public function init(
        Container $DIC
    ): void {
        $this->lng = $DIC->language();
        parent::init($DIC);
    }

    public function isPublicAccessPossible(): bool
    {
        return true;
    }

    public function getExportType(): string
    {
        return 'html';
    }

    public function getExportOptionId(): string
    {
        return 'ilBlogExportOptionHTML';
    }

    public function getSupportedRepositoryObjectTypes(): array
    {
        return ['blog'];
    }

    public function getLabel(): string
    {
        return $this->lng->txt("html");
    }

    public function onExportOptionSelected(
        ilExportHandlerConsumerContextInterface $context
    ): void {
        $this->ctrl->redirectByClass(ilObjBlogGUI::class, "createExportFile");
    }
}
