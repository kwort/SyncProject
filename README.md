Syncproject
===========

Check recursivly all project to search git project and say if local and master is sync.
First column say is project are uncommit and unsurvey file.
Second colum say if mast avec updated

Installation
-----------

	sudo su
	cd /opt
	git clone
	chmod a+x /opt/Syncproject/syncproject.php
	ln -s /opt/Syncproject/syncproject.php /usr/bin/syncproject

Usage
-----

	syncproject [FOLDER]
	Create a syncproject.lock file for not accidently parse a big (a.e "/")

Params
------

	Constant variable are used for check information, change it for your language

	const SYNC_MASTER = "Votre branche est à jour avec 'origin\\/master'";
	const SYNC_ADV_MASTER = "Votre branche est en avance sur 'origin\\/master'";
	const SYNC_RTD_MASTER = "Votre branche est en retard sur 'origin\\/master'";
	const FILE_NOT_FOLLOW = "Fichiers non suivis";
	const MODIF_VALID = "Modifications qui seront validées";
	const MODIF_NOT_VALID = "Modifications qui ne seront pas validées";


Exemple of result :
-------------------

'''bash
+-------------------------------------------------------------------+
| Projects                                         | Local | Master |
+-------------------------------------------------------------------+
| XamppSwitcher                                    | NO     | YES   |
| chosenByGroup                                    | NO     | YES   |
| SnippetSymfony                                   | NO     | YES   |
| hello-world-react                                | YES    | YES   |
| powering-up-with-react                           | NO     | YES   |
| slim-framework-3-with-grafikart                  | YES    | YES   |
+-------------------------------------------------------------------+
'''