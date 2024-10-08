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
use ILIAS\Data\ObjectId;

class ilForumExportOptionHTML extends ilBasicLegacyExportOption
{
    protected ilLanguage $lng;

    public function init(Container $DIC): void
    {
        $this->lng = $DIC->language();
        parent::init($DIC);
    }

    public function getExportType(): string
    {
        return 'html';
    }

    public function getExportOptionId(): string
    {
        return 'ilForumExportOptionHTML';
    }

    public function getSupportedRepositoryObjectTypes(): array
    {
        return ['frm'];
    }

    public function getLabel(): string
    {
        $this->lng->loadLanguageModule('exp');
        return $this->lng->txt('exp_html');
    }

    public function onExportOptionSelected(ilExportHandlerConsumerContextInterface $context): void
    {
        $fex_gui = new ilForumExportGUI();
        $fex_gui->exportHTML();
        $this->ctrl->redirectByClass(ilObjForumGUI::class, 'export');
    }
}
