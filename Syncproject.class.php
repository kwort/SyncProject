<?php

class SyncProject
{
	private $path;
	private $fetch;
	private $projects;

	const SYNC_MASTER = "Votre branche est à jour avec 'origin\\/master'";
	const SYNC_ADV_MASTER = "Votre branche est en avance sur 'origin\\/master'";
	const SYNC_RTD_MASTER = "Votre branche est en retard sur 'origin\\/master'";
	const FILE_NOT_FOLLOW = "Fichiers non suivis";
	const MODIF_VALID = "Modifications qui seront validées";
	const MODIF_NOT_VALID = "Modifications qui ne seront pas validées";

	public function __construct($path, $fetch)
	{
		$path = realpath($path);

		if (!file_exists($path)) {
			throw new Exception("Le dossier n'existe pas", 1);
		}

		if (!file_exists($path."/syncProject.lock")) {
			throw new Exception("Le dossier n'est pas un dossier syncProject, ajouter un fichier syncProject.lock pour executer la commande", 1);
		}

		$this->path = $path;
		$this->fetch = $fetch;
		$this->projects = [];
	}

	private function getProjects($path, &$projects)
	{
		$dirs = array_diff(scandir($path), array('..', '.'));
		foreach ($dirs as $dir) {
			$dir = $path."/".$dir;
			if (is_dir($dir)) {
				if (file_exists($dir."/.git")) {
					$projects[] = [basename($dir) => ['path' => $dir]];
				} else {
					$this->getProjects($dir, $projects);
				}
			}
		}
	}

	private function getStatus()
	{
		if (empty($this->projects)) return [];

		$curent = `pwd`;

		foreach ($this->projects as $projectName => &$project) {
			$project = &$project[key($project)];

			$projectPath = $project['path'];
			$gitStatus = `cd "$projectPath" && git status`;

			if ($this->fetch) {
				$result = `cd "$projectPath" && git fetch`;
				echo $result;
			}

			$project['status'] = [];
			$project['status']['SYNC_MASTER'] = preg_match('/'.self::SYNC_MASTER.'/', $gitStatus);
			$project['status']['SYNC_ADV_MASTER'] = preg_match('/'.self::SYNC_ADV_MASTER.'/', $gitStatus);
			$project['status']['SYNC_RTD_MASTER'] = preg_match('/'.self::SYNC_RTD_MASTER.'/', $gitStatus);
			$project['status']['FILE_NOT_FOLLOW'] = preg_match('/'.self::FILE_NOT_FOLLOW.'/', $gitStatus);
			$project['status']['MODIF_VALID'] = preg_match('/'.self::MODIF_VALID.'/', $gitStatus);
			$project['status']['MODIF_NOT_VALID'] = preg_match('/'.self::MODIF_NOT_VALID.'/', $gitStatus);
		}
	}

	private function tableView()
	{
		$view = "";
		$view .= $this->getHeaderView();
		foreach ($this->projects as $project) {
			$view .= $this->getRowView(key($project), $project[key($project)]);
		}
		$view .= $this->getFooterView();

		echo $view;
	}

	private function getHeaderView()
	{
		$view = "";
		$view .= "+-------------------------------------------------------------------+\n";
		$view .= "| Projects                                         | Local | Master |\n";
		$view .= "+-------------------------------------------------------------------+\n";

		return $view;
	}

	private function getRowView($projectName, $project)
	{
		$view = "";

		if ( strlen($projectName) > 48 ) {
			$projectName = substr($projectName, 0, 45)."...";
		}

		$view .= "| ".$projectName.str_repeat(" ", (48-strlen($projectName)))." |";

		if ( 
			$project['status']['FILE_NOT_FOLLOW'] || $project['status']['MODIF_VALID'] || $project['status']['MODIF_NOT_VALID'] 
		) {
			$view .= " NO     |";
		} else {
			$view .= " YES    |";
		}

		if ( $project['status']['SYNC_MASTER'] ) {
			$view .= " YES   |";
		} else {
			$view .= " NO    |";
		}

		$view .= "\n";

		return $view;
	}

	private function getFooterView()
	{
		$view = "";
		$view .= "+-------------------------------------------------------------------+\n";

		return $view;
	}

	public function execute()
	{
		$this->getProjects($this->path, $this->projects);
		$this->getStatus();
		$this->tableView();
	}
}