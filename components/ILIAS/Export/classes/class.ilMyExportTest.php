<?php

use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ILIAS\Filesystem\Stream\Streams;
use ILIAS\Filesystem\Util\Archive\Zip;
use ILIAS\Filesystem\Util\Archive\ZipOptions;
use ILIAS\ResourceStorage\Stakeholder\AbstractResourceStakeholder;
use ILIAS\ResourceStorage\Identification\ResourceIdentification;

class ilMyExportTest
{
    protected const TABLE_NAME = "dummy_test1";
    protected const OUT_PATH = "/var/www/files/test10/temp";
    protected const ZIP_FILE_NAME = "test.zip";
    protected const TEST_FILE_FOLDER = __DIR__ . "/TestFiles";

    protected ResourcesStorageService $irss;
    protected ilLogger $logger;
    protected ilDBInterface $db;

    public function __construct()
    {
        global $DIC;
        $this->irss = $DIC->resourceStorage();
        $this->logger = $DIC->logger()->root();
        $this->db = $DIC->database();
        $this->initRidReferenceTable();
    }

    protected function initRidReferenceTable(): void
    {
        if ($this->db->tableExists(self::TABLE_NAME)) {
            return;
        }
        $this->db->createTable(self::TABLE_NAME, [
            'rid' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
        ]);
        $this->db->addPrimaryKey(self::TABLE_NAME, ["rid"]);
    }

    protected function saveRid(string $rid): void
    {
        $this->db->insert(self::TABLE_NAME, [
            "rid" => ["text",$rid]
        ]);
    }

    protected function removeRid(string $rid): void
    {
        $this->db->manipulate("DELETE FROM " . $this->db->quoteIdentifier(self::TABLE_NAME) . " WHERE rid = " . $this->db->quote($rid));
    }

    protected function getStakeHolder(): AbstractResourceStakeholder
    {
        return new class () extends AbstractResourceStakeholder {
            public function __construct()
            {
            }
            public function getId(): string
            {
                return "test_export";
            }

            public function getOwnerOfNewResources(): int
            {
                return 6;
            }
        };
    }

    /**
     * @return string[]
     */
    protected function getFilePaths(): array
    {
        $files = scandir(self::TEST_FILE_FOLDER);
        $ignore = ['.', '..'];
        $result_paths = [];
        $result_file_paths = [];
        $paths = [[self::TEST_FILE_FOLDER, scandir(self::TEST_FILE_FOLDER)]];
        while (count($paths) > 0) {
            $current_path = $paths[0][0];
            $current_files = $paths[0][1];
            array_shift($paths);
            foreach ($current_files as $file) {
                $target_path = $current_path . "/" . $file;
                if (in_array($file, $ignore)) {
                    continue;
                }
                if (is_dir($target_path)) {
                    $paths[] = [$target_path, scandir($target_path)];
                }
                if (is_file($target_path)) {
                    $result_paths[] = $current_path;
                    $result_file_paths[] = $target_path;
                }
            }
        }
        return $result_file_paths;
    }

    protected function pathInsideZipOf(string $file): string
    {
        return substr($file, strlen(__DIR__) + 1);
    }

    protected function buildZip(array $file_paths): Zip
    {
        $streams = [];
        foreach ($file_paths as $file_path) {
            $streams[$this->pathInsideZipOf($file_path)] = Streams::ofResource(fopen($file_path, 'rb'));
        }
        $options = (new ZipOptions())
            ->withZipOutputName(self::ZIP_FILE_NAME)
            ->withZipOutputPath(self::OUT_PATH);
        $zip = new Zip(
            $options,
            ...$streams
        );
        return $zip;
    }

    protected function buildZipFromStrings(array $file_paths, array $contents): Zip
    {
        $options = (new ZipOptions())
            ->withZipOutputName(self::ZIP_FILE_NAME)
            ->withZipOutputPath(self::OUT_PATH);
        $zip = new Zip(
            $options
        );
        for ($i = 0; $i < count($file_paths); $i++) {
            $file_path = $this->pathInsideZipOf($file_paths[$i]);
            $content = $contents[$i];
            $zip->addStream(
                Streams::ofString($content),
                $file_path
            );
        }
        return $zip;
    }

    public function storeCompleteFromFiles(): void
    {
        $zip = $this->buildZip($this->getFilePaths());
        $rid = $this->irss->manageContainer()->containerFromStream(
            $zip->get(),
            $this->getStakeHolder()
        );
        $this->saveRid($rid->serialize());
        $download = $this->irss->consume()->download($rid);
        $download->run();
    }

    public function storeIterativeFromFiles(): void
    {
        $file_paths = $this->getFilePaths();
        $zip = $this->buildZip([array_shift($file_paths)]);
        $rid = $this->irss->manageContainer()->containerFromStream(
            $zip->get(),
            $this->getStakeHolder()
        );
        foreach ($file_paths as $file_path) {
            $this->irss->manageContainer()->addStreamToContainer(
                $rid,
                Streams::ofResource(fopen($file_path, 'rb')),
                $this->pathInsideZipOf($file_path)
            );
        }
        $this->saveRid($rid->serialize());
        $download = $this->irss->consume()->download($rid);
        $download->run();
    }

    public function storeCompleteFromString(): void
    {
        $file_paths = $this->getFilePaths();
        $contents = array_fill(0, count($file_paths), "AAAAA");
        $zip = $this->buildZipFromStrings($file_paths, $contents);
        $rid = $this->irss->manageContainer()->containerFromStream(
            $zip->get(),
            $this->getStakeHolder()
        );
        $this->saveRid($rid->serialize());
        $download = $this->irss->consume()->download($rid);
        $download->run();
    }

    public function storeIterativeFromString(): void
    {
        $file_paths = $this->getFilePaths();
        $contents = array_fill(0, count($file_paths), "AAAAA");
        $zip = $this->buildZipFromStrings([array_shift($file_paths)], [array_shift($contents)]);
        $rid = $this->irss->manageContainer()->containerFromStream(
            $zip->get(),
            $this->getStakeHolder()
        );
        for ($i = 0; $i < count($file_paths); $i++) {
            $file_path = $this->pathInsideZipOf($file_paths[$i]);
            $content = $contents[$i];
            $this->irss->manageContainer()->addStreamToContainer(
                $rid,
                Streams::ofString($content),
                $file_path
            );
        }
        $this->saveRid($rid->serialize());
        $download = $this->irss->consume()->download($rid);
        $download->run();
    }

    public function deleteAllResources(): void
    {
        $res = $this->db->query("SELECT rid FROM " . self::TABLE_NAME);
        $resource_id_strs = [];
        while ($row = $res->fetchAssoc()) {
            $resource_id_strs[] = $row['rid'];
        }
        foreach ($resource_id_strs as $resource_id_str) {
            $rid = $this->irss->manageContainer()->find($resource_id_str);
            if (!is_null($rid)) {
                $this->irss->manageContainer()->remove($rid, $this->getStakeHolder());
            }
            $this->removeRid($resource_id_str);
            $this->logger->debug("Removed Resource: " . $resource_id_str);
        }
    }

    public function delete(): void
    {
        $res = $this->db->query("SELECT rid FROM il_resource");
        $resource_id_strs = [];
        while ($row = $res->fetchAssoc()) {
            $resource_id_strs[] = $row['rid'];
        }
        foreach ($resource_id_strs as $resource_id_str) {
            $rid = $this->irss->manageContainer()->find($resource_id_str);
            if (!is_null($rid)) {
                $this->irss->manageContainer()->remove($rid, $this->getStakeHolder());
            }
            $this->logger->debug("Removed Resource: " . $resource_id_str);
        }
    }
}
