<?php

// Generated e107 Plugin Admin Area

require_once '../../../class2.php';
if (!getperms('P')) {
    e107::redirect('admin');
    exit;
}

require_once 'admin_leftmenu.php';

class forum_tools_ui extends e_admin_ui
{
    protected $pluginTitle = 'Forum Extended';
    protected $pluginName = 'forum_extended';

    protected $listOrder = 'thread_id DESC';

    protected $prefs = [
            'plugin_organization' => ['title' => 'Github Organization For Sync', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'help' => '', 'writeParms' => []],
            'plugin_repo' => ['title' => 'Github Repo For Sync', 'tab' => 0, 'type' => 'dropdown', 'data' => 'str', 'help' => '', 'writeParms' => []],
            'plugin_branch' => ['title' => 'Github Branch For Sync', 'tab' => 0, 'type' => 'text', 'data' => 'str', 'help' => '', 'writeParms' => []],
        ];

    public function __construct($request, $response, $params = [])
    {
        parent::__construct($request, $response, $params = []);

        if (!empty($_POST['githubSyncProcess'])) {
            $this->getRequest()->setAction('synced');
        }
    }

    public function init()
    {
        // This code may be removed once plugin development is complete.
        if (!e107::isInstalled('forum')) {
            e107::getMessage()->addWarning("This plugin doesn't work without forum plugin");
        }

        $this->prefs['plugin_repo']['writeParms']['optArray'] = ['forum_extended' => 'forum_extended', 'forum' => 'forum'];
    }

    // left-panel help menu area. (replaces e_help.php used in old plugins)
    public function renderHelp()
    {
        $caption = LAN_HELP;
        $text = 'Some help text';

        switch ($this->getAction()) {
            case 'prefs':
                $text = 'Set correct names for repo you want to sync. Only plugins can be synced this way.';
            break;
            case 'sync':
                $text = 'Set in preferences what plugin you want to sync. Fork repo and change paths to your version.';
            break;

            default:
            break;
        }

        return ['caption' => $caption, 'text' => $text];
    }

    public function syncPage()
    {
        $frm = e107::getForm();
        $mes = e107::getMessage();
        $pref = e107::pref();

        $key = 'github';
        $val['label'] = DBLAN_112;
        $val['diz'] = DBLAN_115;

        $plugPrefs = e107::getPlugConfig('forum_extended')->getPref();

        $organization = $plugPrefs['plugin_organization'];
        $repo = $plugPrefs['plugin_repo'];
        $branch = $plugPrefs['plugin_branch'];

        $text = "<h4 style='margin-bottom:3px'><a href='".e_SELF.'?mode='.$key."' title=\"".$val['label'].'">'.$val['label'].'</a></h4><small>'.$val['diz'].'</small>';

        if (!getperms('0')) {
            $text = e107::getMessage()->addError('Only main admin can use this functionality!');
            $text = $mes->render();

            return $text;
        }

        $remotefile = "https://codeload.github.com/{$organization}/{$repo}/zip/{$branch}";
        $note = 'You are syncing with repo: <b>'.$remotefile;
        $note .= '</b><br>You can put this URL to the browser and download the file manually. If you click on the button below, you will overwrite existing files.';

        e107::getMessage()->addWarning($note);

        $text = $mes->render();

        $min_php_version = '7.4';

        if (version_compare(PHP_VERSION, $min_php_version, '<')) {
            $mes->addWarning('The minimum required PHP version is <strong>'.$min_php_version.'</strong>. You are using PHP version <strong>'.PHP_VERSION.'</strong>. <br /> Syncing with Github has been disabled to avoid broken fuctionality.'); // No need to translate, developer mode only
        } else {
            $message = $frm->open('githubSync');
            $message .= '<p>'.DBLAN_116.' <b>'.e_SYSTEM.'temp</b> '.DBLAN_117.' </p>';
            $message .= $frm->button('githubSyncProcess', 1, 'delete', DBLAN_113);
            $message .= $frm->close();

            $mes->addInfo($message);
        }

        $text .= $mes->render();

        return $text;
    }

    public function syncedPage()
    {
        $result = $this->unzipGithubArchive();

        if ($result === false) {
            e107::getMessage()->addError(DBLAN_118);

            return null;
        }

        $success = $result['success'];
        $error = $result['error'];
		$skipped = $result['skipped'];

        //		$message = e107::getParser()->lanVars(DBLAN_121, array('x'=>$oldPath, 'y'=>$newPath));

        if (!empty($success)) {
            e107::getMessage()->addSuccess(print_a($success, true));
        }

        if (!empty($skipped)) {
            e107::getMessage()->setTitle('Skipped', E_MESSAGE_INFO)->addInfo(print_a($skipped, true));
        }

        if (!empty($error)) {
            //e107::getMessage()->addError(print_a($error,true));
            e107::getMessage()->setTitle('Ignored', E_MESSAGE_WARNING)->addWarning(print_a($error, true));
        }

        e107::getRender()->tablerender(SEP.DBLAN_112, e107::getMessage()->render());

        e107::getCache()->clearAll('system');
    }

  
    /**
     * Download and extract a zipped copy of e107 plugin. Copy of method from file_class.php
     *
     * @param string $url              "core" to download the e107 core from Git master or
     *                                 a custom download URL
     * @param string $destination_path The e107 root where the downloaded archive should be extracted,
     *                                 with a directory separator at the end
     *
     * @return array|bool FALSE on failure;
     *                    An array of successful and failed path extractions
     */
    public function unzipGithubArchive($url = 'plugin', $destination_path = e_BASE)
    {
        $plugPrefs = e107::getPlugConfig('forum_extended')->getPref();

        $organization = $plugPrefs['plugin_organization'];
        $repo = $plugPrefs['plugin_repo'];
        $branch = $plugPrefs['plugin_branch'];

        $destination_path = $destination_path.e107::getFolder('PLUGINS');

        $localfile = "{$repo}.zip";
        $remotefile = "https://codeload.github.com/{$organization}/{$repo}/zip/{$branch}";
        $excludes = [
                "{$repo}-{$branch}/.codeclimate.yml",
                "{$repo}-{$branch}/.editorconfig",
                "{$repo}-{$branch}/.gitignore",
                "{$repo}-{$branch}/.gitmodules",
                "{$repo}-{$branch}/CONTRIBUTING.md", // moved to ./.github/CONTRIBUTING.md
                "{$repo}-{$branch}/LICENSE",
                "{$repo}-{$branch}/README.md",
                "{$repo}-{$branch}/composer.json",
                "{$repo}-{$branch}/composer.lock",
                "{$repo}-{$branch}/install.php",
                "{$repo}-{$branch}/favicon.ico",
                "{$repo}-{$branch}/e107_config.php",
            ];
        $excludeMatch = [
                '/.github/',
                '/e107_tests/',
            ];

        // Delete any existing file.
        if (file_exists(e_TEMP.$localfile)) {
            unlink(e_TEMP.$localfile);
        }

        $result = e107::getFile()->getRemoteFile($remotefile, $localfile);

        if ($result === false) {
            return false;
        }

        chmod(e_TEMP.$localfile, 0755);
        require_once e_HANDLER.'pclzip.lib.php';

        $zipBase = str_replace('.zip', '', $localfile); // eg. e107-master

        $newFolders = [
                 "{$repo}-{$branch}" => $destination_path."{$repo}/",
            ];

        $srch = array_keys($newFolders);
        $repl = array_values($newFolders);

        $archive = new PclZip(e_TEMP.$localfile);

        $unarc = ($fileList = $archive->extract(PCLZIP_OPT_PATH, e_TEMP, PCLZIP_OPT_SET_CHMOD, 0755)); // Store in TEMP first.

        $error = [];
        $success = [];
        $skipped = [];
 
		foreach($unarc as $k => $v)
		{
			if($this->matchFound($v['stored_filename'], $excludeMatch) ||
				in_array($v['stored_filename'], $excludes))
			{
				$skipped[] = $v['stored_filename'];
				continue;
			}

			$oldPath = $v['filename'];
			$newPath = str_replace($srch, $repl, $v['stored_filename']);

			if($v['folder'] == 1 && is_dir($newPath))
			{
				// $skipped[] =  $newPath. " (already exists)";
				continue;
			}
			@mkdir(dirname($newPath), 0755, true);
			if(!rename($oldPath, $newPath))
			{
				$error[] = $newPath;
			}
			else
			{
				$success[] = $newPath;
			}

		}

        $this->logData($destination_path."{$repo}/");

        return ['success' => $success, 'error' => $error, 'skipped' => $skipped];
    }

    /**
     * @param $file
     * @param $array
     *
     * @return bool
     */
    private function matchFound($file, $array)
    {
        if (empty($array)) {
            return false;
        }

        foreach ($array as $term) {
            if (strpos($file, $term) !== false) {
                return true;
            }
        }

        return false;
    }

    public function logData($destination_path, $message = null)
    {
        if ($fp = @fopen($destination_path.'synced.txt')) {
            $contents = @fwrite($fp, date('r'));
            @fclose($fp);
        }
    }
}
 
new forum_extended_adminArea();

require_once e_ADMIN.'auth.php';
e107::getAdminUI()->runPage();

require_once e_ADMIN.'footer.php';
exit;
