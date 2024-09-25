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

namespace ILIAS\Poll\Setup;

use ilDatabaseInitializedObjective;
use ilDatabaseUpdatedObjective;
use ilDBConstants;
use ilDBInterface;
use ilFSStoragePoll;
use ILIAS\Setup\Environment;
use ILIAS\Setup\Migration;
use ilIniFilesLoadedObjective;
use ilResourceStorageMigrationHelper;
use ILIAS\Poll\Image\Repository\Stakeholder\Handler as ilPollImageRepositoryStakeholder;

class PollImagesMigration implements Migration
{
    protected ilDBInterface $db;

    public function getLabel(): string
    {
        return "PollImagesMigration";
    }

    public function getDefaultAmountOfStepsPerRun(): int
    {
        return 5;
    }

    public function getPreconditions(
        Environment $environment
    ): array {
        return [
            new ilIniFilesLoadedObjective(),
            new ilDatabaseInitializedObjective(),
            new ilDatabaseUpdatedObjective(),
        ];
    }

    public function prepare(
        Environment $environment
    ): void {
        $this->db = $environment->getResource(Environment::RESOURCE_DATABASE);
    }

    public function step(
        Environment $environment
    ): void {
        $res = $this->db->query("SELECT id, image FROM il_poll WHERE migrated = 0 LIMIT 1");
        $row = $res->fetchAssoc();
        $image = $row["image"] ?? "";
        $id = (int) $row["id"];
        $file_path = $this->getImageFullPath($image, $id);
        $thumbnail_path = $this->getThumbnailImagePath($image, $id);
        $stakeholder = (new ilPollImageRepositoryStakeholder())->withUserId(6);
        $irss_helper = new ilResourceStorageMigrationHelper($stakeholder, $environment);
        $rid = $irss_helper->movePathToStorage($file_path, 6, null, null, false);
        $rid_thumbnail = $irss_helper->movePathToStorage($thumbnail_path, 6, null, null, false);
        $res_existing = $this->db->query("SELECT * FROM il_poll_image WHERE object_id = " . $this->db->quote($id, ilDBConstants::T_INTEGER));
        $row_existing = $res_existing->fetchAssoc();
        if (is_null($row)) {
            $this->db->manipulate(
                "INSERT INTO il_poll_image VALUES "
                . " (" . $this->db->quote($id, ilDBConstants::T_INTEGER)
                . ", " . $this->db->quote($rid->serialize(), ilDBConstants::T_TEXT)
            );
        }
        if (!is_null($row_existing)) {
            $irss_helper->getResourceBuilder()->remove($irss_helper->getResourceBuilder()->get($rid), $stakeholder);
        }
        $irss_helper->getResourceBuilder()->remove($irss_helper->getResourceBuilder()->get($rid_thumbnail), $stakeholder);
        $this->db->manipulate("UPDATE il_poll SET migrated = 1 WHERE id = " . $this->db->quote($id, ilDBConstants::T_INTEGER));
    }

    public function getRemainingAmountOfSteps(): int
    {
        $res = $this->db->query(
            'SELECT COUNT(*) AS count FROM il_poll WHERE migrated = 0'
        );
        $row = $this->db->fetchAssoc($res);
        return (int) $row['count'];
    }

    public function getImageFullPath(string $img, int $id): ?string
    {
        return $this->initStorage($id) . $img;
    }

    protected function getThumbnailImagePath(string $img, int $id): string
    {
        return $this->initStorage($id) . "thb_" . $img;
    }

    protected function initStorage(int $a_id): string
    {
        $storage = new ilFSStoragePoll($a_id);
        $storage->create();
        $path = $storage->getAbsolutePath() . "/";
        return $path;
    }
}
