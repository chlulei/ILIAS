<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2001 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

require_once "classes/class.ilObjectGUI.php";
require_once("classes/class.ilFileSystemGUI.php");
require_once("classes/class.ilTabsGUI.php");

/**
* Class ilObjAICCLearningModuleGUI
*
* @author Alex Killing <alex.killing@gmx.de>
* $Id$
*
* @extends ilObjectGUI
* @package ilias-core
*/
class ilObjAICCLearningModuleGUI extends ilObjectGUI
{
	/**
	* Constructor
	*
	* @access	public
	*/
	function ilObjAICCLearningModuleGUI($a_data,$a_id,$a_call_by_reference, $a_prepare_output = true)
	{
		global $lng;

		$lng->loadLanguageModule("content");
		$this->type = "alm";
		$this->ilObjectGUI($a_data,$a_id,$a_call_by_reference,$a_prepare_output);
		$this->tabs_gui =& new ilTabsGUI();

	}

	function _forwards()
	{
		return array("ilFileSystemGUI");
	}

	/**
	* execute command
	*/
	function executeCommand()
	{
		$this->fs_gui =& new ilFileSystemGUI($this->object->getDataDirectory());
		$this->getTemplate();
		$this->setLocator();
		$this->setTabs();

		$next_class = $this->ctrl->getNextClass($this);
		$cmd = $this->ctrl->getCmd();

		switch($next_class)
		{
			case "ilfilesystemgui":
//echo "<br>data_dir:".$this->object->getDataDirectory().":";
				/*
				$fs_gui->activateLabels(true, $this->lng->txt("cont_purpose"));
				if ($this->object->getStartFile() != "")
				{
					$fs_gui->labelFile($this->object->getStartFile(),
						$this->lng->txt("cont_startfile"));
				}
				$fs_gui->addCommand($this, "setStartFile", $this->lng->txt("cont_set_start_file"));
				*/
				$ret =& $this->fs_gui->executeCommand();
				break;

			default:
				$cmd = $this->ctrl->getCmd("frameset");
				$ret =& $this->$cmd();
				break;
		}
		$this->tpl->show();
	}


	function viewObject()
	{
		//add template for view button
		$this->tpl->addBlockfile("BUTTONS", "buttons", "tpl.buttons.html");

		// view button
		$this->tpl->setCurrentBlock("btn_cell");
		$this->tpl->setVariable("BTN_LINK","content/aicc_presentation.php?ref_id=".$this->object->getRefID());
		$this->tpl->setVariable("BTN_TARGET"," target=\"ilContObj".$this->object->getID()."\" ");
		$this->tpl->setVariable("BTN_TXT",$this->lng->txt("view"));
		$this->tpl->parseCurrentBlock();

		// view button
		$this->tpl->setCurrentBlock("btn_cell");
		$this->tpl->setVariable("BTN_LINK","content/aicc_edit.php?ref_id=".$this->object->getRefID());
		$this->tpl->setVariable("BTN_TARGET"," target=\"bottom\" ");
		$this->tpl->setVariable("BTN_TXT",$this->lng->txt("edit"));
		$this->tpl->parseCurrentBlock();
	}

	/**
	* aicc module properties
	*/
	function properties()
	{
		global $rbacsystem, $tree, $tpl;

		// edit button
		$this->tpl->addBlockfile("BUTTONS", "buttons", "tpl.buttons.html");

		// view link
		$this->tpl->setCurrentBlock("btn_cell");
		$this->tpl->setVariable("BTN_LINK", "aicc_presentation.php?ref_id=".$this->object->getRefID());
		$this->tpl->setVariable("BTN_TARGET"," target=\"ilContObj".$this->object->getID()."\" ");
		$this->tpl->setVariable("BTN_TXT",$this->lng->txt("view"));
		$this->tpl->parseCurrentBlock();

		// aicc lm properties
		$this->tpl->addBlockFile("ADM_CONTENT", "adm_content", "tpl.aicc_properties.html", true);
		$this->tpl->setVariable("FORMACTION", $this->ctrl->getFormAction($this));
		$this->tpl->setVariable("TXT_PROPERTIES", $this->lng->txt("cont_lm_properties"));

		// online
		$this->tpl->setVariable("TXT_ONLINE", $this->lng->txt("cont_online"));
		$this->tpl->setVariable("CBOX_ONLINE", "cobj_online");
		$this->tpl->setVariable("VAL_ONLINE", "y");
		if ($this->object->getOnline())
		{
			$this->tpl->setVariable("CHK_ONLINE", "checked");
		}

		// api adapter name
		$this->tpl->setVariable("TXT_API_ADAPTER", $this->lng->txt("cont_api_adapter"));
		$this->tpl->setVariable("VAL_API_ADAPTER", $this->object->getAPIAdapterName());

		// api functions prefix
		$this->tpl->setVariable("TXT_API_PREFIX", $this->lng->txt("cont_api_func_prefix"));
		$this->tpl->setVariable("VAL_API_PREFIX", $this->object->getAPIFunctionsPrefix());

		$this->tpl->setCurrentBlock("commands");
		$this->tpl->setVariable("BTN_NAME", "saveProperties");
		$this->tpl->setVariable("BTN_TEXT", $this->lng->txt("save"));
		$this->tpl->parseCurrentBlock();

	}

	/**
	* save properties
	*/
	function saveProperties()
	{
		$this->object->setOnline(ilUtil::yn2tf($_POST["cobj_online"]));
		$this->object->setAPIAdapterName($_POST["api_adapter"]);
		$this->object->setAPIFunctionsPrefix($_POST["api_func_prefix"]);
		$this->object->update();
		sendInfo($this->lng->txt("msg_obj_modified"), true);
		$this->ctrl->redirect($this, "properties");
	}



	/**
	* no manual AICC creation, only import at the time
	*/
	function createObject()
	{
		$this->importObject();
	}

	/**
	* display dialogue for importing AICC package
	*
	* @access	public
	*/
	function importObject()
	{
		// display import form
		$this->tpl->addBlockFile("ADM_CONTENT", "adm_content", "tpl.alm_import.html");
		$this->tpl->setVariable("FORMACTION", $this->getFormAction("save","adm_object.php?cmd=gateway&ref_id=".
			$_GET["ref_id"]."&new_type=alm"));
		$this->tpl->setVariable("BTN_NAME", "save");
		$this->tpl->setVariable("TXT_UPLOAD", $this->lng->txt("upload"));
		$this->tpl->setVariable("TXT_IMPORT_ALM", $this->lng->txt("import_alm"));
		$this->tpl->setVariable("TXT_SELECT_FILE", $this->lng->txt("select_file"));
		$this->tpl->setVariable("TXT_VALIDATE_FILE", $this->lng->txt("cont_validate_file"));
	}

	/**
	* display status information or report errors messages
	* in case of error
	*
	* @access	public
	*/
	function uploadObject()
	{
		global $HTTP_POST_FILES, $rbacsystem;

		// check if file was uploaded
		$source = $HTTP_POST_FILES["aiccfile"]["tmp_name"];
		if (($source == 'none') || (!$source))
		{
			$this->ilias->raiseError("No file selected!",$this->ilias->error_obj->MESSAGE);
		}
		// check create permission
		if (!$rbacsystem->checkAccess("create", $_GET["ref_id"], $_GET["new_type"]))
		{
			$this->ilias->raiseError($this->lng->txt("no_create_permission"), $this->ilias->error_obj->WARNING);
		}


		$file = pathinfo($_FILES["aiccfile"]["name"]);
		$name = substr($file["basename"], 0,
			strlen($file["basename"]) - strlen($file["extension"]) - 1);
		if ($name == "")
		{
			$name = $this->lng->txt("no_title");
		}

		//$maxFileSize=ini_get('upload_max_filesize');

		// create and insert object in objecttree
		include_once("classes/class.ilObjAICCLearningModule.php");
		$newObj = new ilObjAICCLearningModule();
		//$newObj->setType("alm");
		//$dummy_meta =& new ilMetaData();
		//$dummy_meta->setObject($newObj);
		//$newObj->assignMetaData($dummy_meta);
		$newObj->setTitle($name);
		$newObj->setDescription("");
		$newObj->create();
		$newObj->createReference();
		$newObj->putInTree($_GET["ref_id"]);
		$newObj->setPermissions($_GET["ref_id"]);
		$newObj->notify("new",$_GET["ref_id"],$_GET["parent_non_rbac_id"],$_GET["ref_id"],$newObj->getRefId());

		// create data directory, copy file to directory
		$newObj->createDataDirectory();

		// copy uploaded file to data directory
		$file_path = $newObj->getDataDirectory()."/".$_FILES["aiccfile"]["name"];
		move_uploaded_file($_FILES["aiccfile"]["tmp_name"], $file_path);

		ilUtil::unzip($file_path);


		$cifModule=$newObj->getCiFileModule();
		$cifModule->findFiles($newObj->getDataDirectory());
		
		$cifModule->readFiles();
		if (!empty($cifModule->errorText)) {
			$this->ilias->raiseError("<b>Error reading LM-File(s):</b><br>".implode("<br>", $cifModule->errorText), $this->ilias->error_obj->WARNING);
		}
		
		if ($_POST["validate"] == "y") {

			$cifModule->validate();
			if (!empty($cifModule->errorText)) {
				$this->ilias->raiseError("<b>Validation Error(s):</b><br>".implode("<br>", $cifModule->errorText), $this->ilias->error_obj->WARNING);
			}
		}
		
		$cifModule->writeToDatabase($newObj->getId());
/*


include_once("classes/class.ilObjAICCLearningModule.php");
		$cifModule=new AICC_CourseInterchangeFiles();
		$cifModule->findFiles("data/66666666/lm_data/lm_194");
		$cifModule->readFiles();
		if (!empty($cifModule->errorText)) {
			$this->ilias->raiseError("<b>Error reading LM-File(s):</b><br>".implode("<br>", $cifModule->errorText), $this->ilias->error_obj->WARNING);
		}
		$alm_id="194";
		
		$sql="DELETE FROM aicc_tree WHERE alm_id=$alm_id";
		$this->ilias->db->query($sql);
		$sql="DELETE FROM aicc_course  ";//WHERE alm_id=$alm_id";
		$this->ilias->db->query($sql);
		$sql="DELETE FROM aicc_object WHERE alm_id=$alm_id";
		$this->ilias->db->query($sql);
		$sql="DELETE FROM aicc_units ";//WHERE alm_id=$alm_id";
		$this->ilias->db->query($sql);
		
		include_once("content/classes/AICC/class.ilAICCTree.php");
		include_once("content/classes/AICC/class.ilAICCCourse.php");
		include_once("content/classes/AICC/class.ilAICCUnit.php");
		include_once("content/classes/AICC/class.ilAICCBlock.php");
		
		//write course to database
		$course=new ilAICCCourse();
		$course->setALMId($alm_id);
		$course->setSystemId("root");
		$course->setTitle($cifModule->data["crs"]["course"]["course_title"]);
		$course->setDescription($cifModule->data["crs"]["course_description"]["description"]);
		
		$course->setCourseCreator($cifModule->data["crs"]["course"]["course_creator"]);
		$course->setCourseId($cifModule->data["crs"]["course"]["course_id"]);
		$course->setCourseSystem($cifModule->data["crs"]["course"]["course_system"]);
		$course->setCourseTitle($cifModule->data["crs"]["course"]["course_title"]);
		$course->setLevel($cifModule->data["crs"]["course"]["level"]);
		$course->setMaxFieldsCst($cifModule->data["crs"]["course"]["max_fields_cst"]);
		$course->setMaxFieldsOrt($cifModule->data["crs"]["course"]["max_fields_ort"]);
		$course->setTotalAUs($cifModule->data["crs"]["course"]["total_aus"]);
		$course->setTotalBlocks($cifModule->data["crs"]["course"]["total_blocks"]);
		$course->setTotalComplexObj($cifModule->data["crs"]["course"]["total_complex_obj"]);
		$course->setTotalObjectives($cifModule->data["crs"]["course"]["total_objectives"]);
		$course->setVersion($cifModule->data["crs"]["course"]["version"]);
		$course->setMaxNormal($cifModule->data["crs"]["course_behavior"]["max_normal"]);
		$course->setDescription($cifModule->data["crs"]["course_description"]["description"]);
		$course->create();	
		$identifier["root"]=$course->getId();
		
		//all blocks
		foreach ($cifModule->data["cst"] as $row) {
			$system_id=strtolower($row["block"]);
			if ($system_id!="root") {
				$unit=new ilAICCBlock();
				$description=$cifModule->getDescriptor($system_id);
				$unit->setALMId($alm_id);
				$unit->setType("sbl");
				$unit->setTitle($description["title"]);
				$unit->setDescription($description["description"]);
				$unit->setDeveloperId($description["developer_id"]);
				$unit->setSystemId($description["system_id"]);
				$unit->create();
				$identifier[$system_id]=$unit->getId();
			}
		}
	
		//write assignable units to database
		foreach ($cifModule->data["au"] as $row) {
			$sysid=strtolower($row["system_id"]);
			$unit=new ilAICCUnit();
			
			$unit->setAUType($row["type"]);
			$unit->setCommand_line($row["command_line"]);
			$unit->setMaxTimeAllowed($row["max_time_allowed"]);
			$unit->setTimeLimitAction($row["time_limit_action"]);
			$unit->setMaxScore($row["max_score"]);
			$unit->setCoreVendor($row["core_vendor"]);
			$unit->setSystemVendor($row["system_vendor"]);
			$unit->setFilename($row["file_name"]);
			$unit->setMasteryScore($row["mastery_score"]);
			$unit->setWebLaunch($row["web_launch"]);
			$unit->setAUPassword($row["au_password"]);
				
			$description=$cifModule->getDescriptor($sysid);
			$unit->setALMId($alm_id);
			$unit->setType("sau");
			$unit->setTitle($description["title"]);
			$unit->setDescription($description["description"]);
			$unit->setDeveloperId($description["developer_id"]);
			$unit->setSystemId($description["system_id"]);
			$unit->create();
			$identifier[$sysid]=$unit->getId();	
			//echo "unit->create system_id=$sysid=".$unit->getId()."<br>";
		}
		
		//write tree
		$this->sc_tree =& new ilAICCTree($alm_id);
		$this->sc_tree->addTree($alm_id, $identifier["root"]);
		
		//writing members
		foreach ($cifModule->data["cst"] as $row) {
			$members=$row["member"];
			if (!is_array($members))
				$members=array($members);
			$parentid=$identifier[strtolower($row["block"])];

			foreach($members as $member) {
				$memberid=$identifier[strtolower($member)];
				echo "insert node memberid=$memberid    parentid=$parentid<br>";
				$this->sc_tree->insertNode($memberid, $parentid);
			}
		}		
	
		echo "fertig.<br>";
*/
	}

	function upload()
	{
		$this->uploadObject();
	}

	/**
	* save new learning module to db
	*/
	function saveObject()
	{
		global $rbacadmin;

		$this->uploadObject();

		// always call parent method first to create an object_data entry & a reference
		// $newObj = parent::saveObject();
		// TODO: fix MetaDataGUI implementation to make it compatible to use parent call

		// create and insert object in objecttree
		/*
		include_once("classes/class.ilObjAICCLearningModule.php");
		$newObj = new ilObjLearningModule();
		$newObj->setType("lm");
		$newObj->setTitle("dummy");			// set by meta_gui->save
		$newObj->setDescription("dummy");	// set by meta_gui->save
		$newObj->create();
		$newObj->createReference();
		$newObj->putInTree($_GET["ref_id"]);
		$newObj->setPermissions($_GET["ref_id"]);
		$newObj->notify("new",$_GET["ref_id"],$_GET["parent_non_rbac_id"],$_GET["ref_id"],$newObj->getRefId());

		// save meta data
		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($newObj);
		$meta_gui->save();

		// create learning module tree
		$newObj->createLMTree();

		unset($newObj);

		// always send a message
		sendInfo($this->lng->txt("slm_added"),true);

		header("Location:".$this->getReturnLocation("save","adm_object.php?".$this->link_params));
		exit();*/

		sendInfo($this->lng->txt("alm_added"), true);
		ilUtil::redirect($this->getReturnLocation("save","adm_object.php?".$this->link_params));

	}

	/**
	* permission form
	*/
	function perm()
	{
		$this->setFormAction("permSave", "aicc_edit.php?cmd=permSave&ref_id=".$_GET["ref_id"].
			"&obj_id=".$_GET["obj_id"]);
		$this->setFormAction("addRole", "aicc_edit.php?ref_id=".$_GET["ref_id"].
			"&obj_id=".$_GET["obj_id"]."&cmd=addRole");
		$this->permObject();
	}

	/**
	* save permissions
	*/
	function permSave()
	{
		$this->setReturnLocation("permSave",
			"aicc_edit.php?ref_id=".$_GET["ref_id"]."&obj_id=".$_GET["obj_id"]."&cmd=perm");
		$this->permSaveObject();
	}

	/**
	* add role
	*/
	function addRole()
	{
		$this->setReturnLocation("addRole",
			"aicc_edit.php?ref_id=".$_GET["ref_id"]."&obj_id=".$_GET["obj_id"]."&cmd=perm");
		$this->addRoleObject();
	}

	/**
	* show owner of learning module
	*/
	function owner()
	{
		$this->ownerObject();
	}

	/**
	* choose meta data section
	* (called by administration)
	*/
	function chooseMetaSectionObject($a_target = "")
	{
		if ($a_target == "")
		{
			$a_target = "adm_object.php?ref_id=".$this->object->getRefId();
		}

		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($this->object);
		$meta_gui->edit("ADM_CONTENT", "adm_content",
			$a_target, $_REQUEST["meta_section"]);
	}

	/**
	* choose meta data section
	* (called by module)
	*/
	function chooseMetaSection()
	{
		$this->chooseMetaSectionObject($this->ctrl->getLinkTarget($this));
	}

	/**
	* add meta data object
	* (called by administration)
	*/
	function addMetaObject($a_target = "")
	{
		if ($a_target == "")
		{
			$a_target = "adm_object.php?ref_id=".$this->object->getRefId();
		}

		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($this->object);
		$meta_name = $_POST["meta_name"] ? $_POST["meta_name"] : $_GET["meta_name"];
		$meta_index = $_POST["meta_index"] ? $_POST["meta_index"] : $_GET["meta_index"];
		if ($meta_index == "")
			$meta_index = 0;
		$meta_path = $_POST["meta_path"] ? $_POST["meta_path"] : $_GET["meta_path"];
		$meta_section = $_POST["meta_section"] ? $_POST["meta_section"] : $_GET["meta_section"];
		if ($meta_name != "")
		{
			$meta_gui->meta_obj->add($meta_name, $meta_path, $meta_index);
		}
		else
		{
			sendInfo($this->lng->txt("meta_choose_element"), true);
		}
		$meta_gui->edit("ADM_CONTENT", "adm_content", $a_target, $meta_section);
	}

	/**
	* add meta data object
	* (called by module)
	*/
	function addMeta()
	{
		$this->addMetaObject($this->ctrl->getLinkTarget($this));
	}


	/**
	* delete meta data object
	* (called by administration)
	*/
	function deleteMetaObject($a_target = "")
	{
		if ($a_target == "")
		{
			$a_target = "adm_object.php?ref_id=".$this->object->getRefId();
		}

		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($this->object);
		$meta_index = $_POST["meta_index"] ? $_POST["meta_index"] : $_GET["meta_index"];
		$meta_gui->meta_obj->delete($_GET["meta_name"], $_GET["meta_path"], $meta_index);
		$meta_gui->edit("ADM_CONTENT", "adm_content", $a_target, $_GET["meta_section"]);
	}

	/**
	* delete meta data object
	* (called by module)
	*/
	function deleteMeta()
	{
		$this->deleteMetaObject($this->ctrl->getLinkTarget($this));
	}

	/**
	* edit meta data
	* (called by administration)
	*/
	function editMetaObject($a_target = "")
	{
		if ($a_target == "")
		{
			$a_target = "adm_object.php?ref_id=".$this->object->getRefId();
		}

		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($this->object);
		$meta_gui->edit("ADM_CONTENT", "adm_content", $a_target, $_GET["meta_section"]);
	}

	/**
	* edit meta data
	* (called by module)
	*/
	function editMeta()
	{
		$this->editMetaObject($this->ctrl->getLinkTarget($this));
	}

	/**
	* save meta data
	* (called by administration)
	*/
	function saveMetaObject($a_target = "")
	{
		if ($a_target == "")
		{
			$a_target = "adm_object.php?cmd=editMeta&ref_id=".$this->object->getRefId();
		}

		include_once "classes/class.ilMetaDataGUI.php";
		$meta_gui =& new ilMetaDataGUI();
		$meta_gui->setObject($this->object);
		$meta_gui->save($_POST["meta_section"]);
		ilUtil::redirect(ilUtil::appendUrlParameterString($a_target,
			"meta_section=" . $_POST["meta_section"]));
	}

	/**
	* save meta data
	* (called by module)
	*/
	function saveMeta()
	{
		$this->saveMetaObject($this->ctrl->getLinkTarget($this, "editMeta"));
	}


	/**
	* output main header (title and locator)
	*/
	function getTemplate()
	{
		global $lng;

		$this->tpl->addBlockFile("CONTENT", "content", "tpl.adm_content.html");
		//$this->tpl->setVariable("HEADER", $a_header_title);
		$this->tpl->addBlockFile("STATUSLINE", "statusline", "tpl.statusline.html");
		//$this->tpl->setVariable("TXT_LOCATOR",$this->lng->txt("locator"));
	}

	/**
	* show tracking data
	*/
	function showTrackingItems()
	{

		include_once "./classes/class.ilTableGUI.php";

		// load template for table
		$this->tpl->addBlockfile("ADM_CONTENT", "adm_content", "tpl.table.html");
		// load template for table content data
		$this->tpl->addBlockfile("TBL_CONTENT", "tbl_content", "tpl.scorm_track_items.html", true);

		$num = 1;

		$this->tpl->setVariable("FORMACTION", "adm_object.php?ref_id=".$this->ref_id."$obj_str&cmd=gateway");

		// create table
		$tbl = new ilTableGUI();

		// title & header columns
		$tbl->setTitle($this->lng->txt("cont_tracking_items"));

		$tbl->setHeaderNames(array($this->lng->txt("title")));

		$header_params = array("ref_id" => $this->ref_id, "cmd" => $_GET["cmd"],
			"cmdClass" => get_class($this));
		$cols = array("title");
		$tbl->setHeaderVars($cols, $header_params);
		$tbl->setColumnWidth(array("100%"));

		// control
		$tbl->setOrderColumn($_GET["sort_by"]);
		$tbl->setOrderDirection($_GET["sort_order"]);
		$tbl->setLimit($_GET["limit"]);
		$tbl->setOffset($_GET["offset"]);
		$tbl->setMaxCount($this->maxcount);

		//$this->tpl->setVariable("COLUMN_COUNTS",count($this->data["cols"]));
		//$this->showActions(true);

		// footer
		$tbl->setFooter("tblfooter",$this->lng->txt("previous"),$this->lng->txt("next"));
		#$tbl->disable("footer");

		$items = $this->object->getTrackingItems();

		//$objs = ilUtil::sortArray($objs, $_GET["sort_by"], $_GET["sort_order"]);
		$tbl->setMaxCount(count($items));
		$items = array_slice($items, $_GET["offset"], $_GET["limit"]);

		$tbl->render();
		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$this->tpl->setCurrentBlock("tbl_content");
				$this->tpl->setVariable("TXT_ITEM_TITLE", $item->getTitle());
				$this->ctrl->setParameter($this, "obj_id", $item->getId());
				$this->tpl->setVariable("LINK_ITEM",
					$this->ctrl->getLinkTarget($this, "showTrackingItem"));

				$css_row = ilUtil::switchColor($i++, "tblrow1", "tblrow2");
				$this->tpl->setVariable("CSS_ROW", $css_row);
				$this->tpl->parseCurrentBlock();
			}
		} //if is_array
		else
		{
			$this->tpl->setCurrentBlock("notfound");
			$this->tpl->setVariable("TXT_OBJECT_NOT_FOUND", $this->lng->txt("obj_not_found"));
			$this->tpl->setVariable("NUM_COLS", $num);
			$this->tpl->parseCurrentBlock();
		}
	}

	/**
	* show tracking data
	*/
	function showTrackingItem()
	{

		include_once "./classes/class.ilTableGUI.php";

		// load template for table
		$this->tpl->addBlockfile("ADM_CONTENT", "adm_content", "tpl.table.html");
		// load template for table content data
		$this->tpl->addBlockfile("TBL_CONTENT", "tbl_content", "tpl.scorm_track_item.html", true);

		$num = 4;

		$this->tpl->setVariable("FORMACTION", "adm_object.php?ref_id=".$this->ref_id."$obj_str&cmd=gateway");

		// create table
		$tbl = new ilTableGUI();

		include_once("content/classes/SCORM/class.ilSCORMItem.php");
		$sc_item =& new ilSCORMItem($_GET["obj_id"]);

		// title & header columns
		$tbl->setTitle($sc_item->getTitle());

		$tbl->setHeaderNames(array($this->lng->txt("firstname"),$this->lng->txt("lastname"),
			$this->lng->txt("cont_status"), $this->lng->txt("cont_credits"),
			$this->lng->txt("cont_total_time")));

		$header_params = array("ref_id" => $this->ref_id, "cmd" => $_GET["cmd"],
			"cmdClass" => get_class($this), "obj_id" => $_GET["obj_id"]);
		$cols = array("user", "status", "credits", "total_time");
		$tbl->setHeaderVars($cols, $header_params);
		//$tbl->setColumnWidth(array("25%",));

		// control
		$tbl->setOrderColumn($_GET["sort_by"]);
		$tbl->setOrderDirection($_GET["sort_order"]);
		$tbl->setLimit($_GET["limit"]);
		$tbl->setOffset($_GET["offset"]);
		$tbl->setMaxCount($this->maxcount);

		//$this->tpl->setVariable("COLUMN_COUNTS",count($this->data["cols"]));
		//$this->showActions(true);

		// footer
		$tbl->setFooter("tblfooter",$this->lng->txt("previous"),$this->lng->txt("next"));
		#$tbl->disable("footer");

		$tr_data = $sc_item->getAllTrackingData();

		//$objs = ilUtil::sortArray($objs, $_GET["sort_by"], $_GET["sort_order"]);
		$tbl->setMaxCount(count($tr_data));
		$tr_data = array_slice($tr_data, $_GET["offset"], $_GET["limit"]);

		$tbl->render();
		if (count($tr_data) > 0)
		{
			foreach ($tr_data as $data)
			{
				$this->tpl->setCurrentBlock("tbl_content");
				$this->tpl->setVariable("VAL_FIRSTNAME", $data["user_firstname"]);
				$this->tpl->setVariable("VAL_LASTNAME", $data["user_lastname"]);
				$this->tpl->setVariable("VAL_STATUS", $data["lesson_status"]);
				$this->tpl->setVariable("VAL_CREDITS", $data["mastery_score"]);
				$this->tpl->setVariable("VAL_TOTAL_TIME", $data["total_time"]);

				$css_row = ilUtil::switchColor($i++, "tblrow1", "tblrow2");
				$this->tpl->setVariable("CSS_ROW", $css_row);
				$this->tpl->parseCurrentBlock();
			}
		} //if is_array
		else
		{
			$this->tpl->setCurrentBlock("notfound");
			$this->tpl->setVariable("TXT_OBJECT_NOT_FOUND", $this->lng->txt("obj_not_found"));
			$this->tpl->setVariable("NUM_COLS", $num);
			$this->tpl->parseCurrentBlock();
		}
	}

	/**
	* output main frameset of media pool
	* left frame: explorer tree of folders
	* right frame: media pool content
	*/
	function frameset()
	{
		$this->tpl = new ilTemplate("tpl.aicc_edit_frameset.html", false, false, "content");
		$this->tpl->setVariable("REF_ID",$this->ref_id);
		$this->tpl->show();
	}

	/**
	* set locator
	*/
	function setLocator($a_tree = "", $a_id = "", $scriptname="adm_object.php")
	{
		global $ilias_locator, $tree;
		if (!defined("ILIAS_MODULE"))
		{
			parent::setLocator();
		}
		else
		{
			$a_tree =& $tree;
			$a_id = $_GET["ref_id"];

			$this->tpl->addBlockFile("LOCATOR", "locator", "tpl.locator.html");

			$path = $a_tree->getPathFull($a_id);

			// this is a stupid workaround for a bug in PEAR:IT
			$modifier = 1;

			if (!empty($_GET["obj_id"]))
			{
				$modifier = 0;
			}

			// ### AA 03.11.10 added new locator GUI class ###
			$i = 1;

			if ($this->object->getType() != "grp" && ($_GET["cmd"] == "delete" || $_GET["cmd"] == "edit"))
			{
				unset($path[count($path) - 1]);
			}

			foreach ($path as $key => $row)
			{

				if ($key < count($path) - $modifier)
				{
					$this->tpl->touchBlock("locator_separator");
				}

				$this->tpl->setCurrentBlock("locator_item");
				if ($row["child"] != $a_tree->getRootId())
				{
					$this->tpl->setVariable("ITEM", $row["title"]);
				}
				else
				{
					$this->tpl->setVariable("ITEM", $this->lng->txt("repository"));
				}
				if($row["type"] == "alm")
				{
					$this->tpl->setVariable("LINK_ITEM", "aicc_edit.php?ref_id=".$row["child"]);
				}
				else
				{
					$this->tpl->setVariable("LINK_ITEM", "../repository.php?ref_id=".$row["child"]);
				}
				//$this->tpl->setVariable("LINK_TARGET", " target=\"bottom\" ");

				$this->tpl->parseCurrentBlock();

				$this->tpl->setCurrentBlock("locator");

				// ### AA 03.11.10 added new locator GUI class ###
				// navigate locator
				if ($row["child"] != $a_tree->getRootId())
				{
					$ilias_locator->navigate($i++,$row["title"],"../repository.php?ref_id=".$row["child"],"bottom");
				}
				else
				{
					$ilias_locator->navigate($i++,$this->lng->txt("repository"),"../repository.php?ref_id=".$row["child"],"bottom");
				}
			}

			/*
			if (DEBUG)
			{
				$debug = "DEBUG: <font color=\"red\">".$this->type."::".$this->id."::".$_GET["cmd"]."</font><br/>";
			}

			$prop_name = $this->objDefinition->getPropertyName($_GET["cmd"],$this->type);

			if ($_GET["cmd"] == "confirmDeleteAdm")
			{
				$prop_name = "delete_object";
			}*/

			$this->tpl->setVariable("TXT_LOCATOR",$debug.$this->lng->txt("locator"));
			$this->tpl->parseCurrentBlock();
		}

	}


	/**
	* output tabs
	*/
	function setTabs()
	{
		$this->getTabs($this->tabs_gui);
		$this->tpl->setVariable("TABS", $this->tabs_gui->getHTML());
		$this->tpl->setVariable("HEADER", $this->object->getTitle());
	}

	/**
	* adds tabs to tab gui object
	*
	* @param	object		$tabs_gui		ilTabsGUI object
	*/
	function getTabs(&$tabs_gui)
	{
		if ($this->ctrl->getCmd() == "delete")
		{
			return;
		}

		// properties
		$tabs_gui->addTarget("properties",
			$this->ctrl->getLinkTarget($this, "properties"), "properties",
			get_class($this));

		// file system gui tabs
		if (is_object($this->fs_gui))
		{
			$this->fs_gui->getTabs($tabs_gui);
		}

		// edit meta
		$tabs_gui->addTarget("cont_tracking_data",
			$this->ctrl->getLinkTarget($this, "showTrackingItems"), "showTrackingData",
			get_class($this));

		// edit meta
		$tabs_gui->addTarget("meta_data",
			$this->ctrl->getLinkTarget($this, "editMeta"), "editMeta",
			get_class($this));

		// perm
		$tabs_gui->addTarget("perm_settings",
			$this->ctrl->getLinkTarget($this, "perm"), "perm",
			get_class($this));

		// owner
		$tabs_gui->addTarget("owner",
			$this->ctrl->getLinkTarget($this, "owner"), "owner",
			get_class($this));
	}


} // END class.ilObjAICCLearningModule
?>
