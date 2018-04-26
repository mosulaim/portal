<?php
	/**
	 * Class to hold various commonly used functions
	 *
	 * $Id: Misc.php,v 1.171 2008/03/17 21:35:48 ioguix Exp $
	 */

	class Misc {
		// Tracking string to include in HREFs
		var $href;
		// Tracking string to include in forms
		var $form;

		/* Constructor */
		function Misc() {
		}

		/**
		 * Checks if dumps are properly set up
		 * @param $all (optional) True to check pg_dumpall, false to just check pg_dump
		 * @return True, dumps are set up, false otherwise
		 */
		function isDumpEnabled($all = false) {
			$info = $this->getServerInfo();
			return !empty($info[$all ? 'pg_dumpall_path' : 'pg_dump_path']);
		}

		/**
		 * Sets the href tracking variable
		 */
		function setHREF() {
			$this->href = $this->getHREF();
		}

		/**
		 * Get a href query string, excluding objects below the given object type (inclusive)
		 */
		function getHREF($exclude_from = null) {
			$href = '';
			if (isset($_REQUEST['server']) && $exclude_from != 'server') {
				$href .= 'server=' . urlencode($_REQUEST['server']);
				if (isset($_REQUEST['database']) && $exclude_from != 'database') {
					$href .= '&database=' . urlencode($_REQUEST['database']);
					if (isset($_REQUEST['schema']) && $exclude_from != 'schema') {
						$href .= '&schema=' . urlencode($_REQUEST['schema']);
					}
				}
			}
			return htmlentities($href);
		}

		function getHREFSubject($subject) {

			$vars = array();

			switch($subject) {
				case 'root':
					return 'redirect.php?subject=root';
					break;
				case 'server':
					$vars = array (
						'server' => $_REQUEST['server'],
						'subject' => 'server'
					);
					break;
				case 'report':
					return 'reports.php?'. http_build_query(array(
						'server' => $_REQUEST['server'],
						'subject' => 'report',
						'report' => $_REQUEST['report']
					), '', '&amp;');
					break;
				case 'role':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'role',
						'action' => 'properties',
						'rolename' => $_REQUEST['rolename']
					);
					break;
				case 'database':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'database',
						'database' => $_REQUEST['database'],
					);
					break;
				case 'schema':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'schema',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema']
					);
					break;
				case 'slony_cluster':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'slony_cluster',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'slony_cluster' => $_REQUEST['slony_cluster']
					);
					break;
				case 'table':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'table',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'table' => $_REQUEST['table']
					);
					break;
				case 'selectrows':
					return 'tables.php?'. http_build_query(array(
						'server' => $_REQUEST['server'],
						'subject' => 'table',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'table' => $_REQUEST['table'],
						'action' => 'confselectrows'
					), '', '&amp;');
					break;
				case 'view':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'view',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'view' => $_REQUEST['view']
					);
					break;
				case 'fulltext':
				case 'ftscfg':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'fulltext',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'action' => 'viewconfig',
						'ftscfg' => $_REQUEST['ftscfg']
					);
					break;
				case 'function':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'function',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'function' => $_REQUEST['function'],
						'function_oid' => $_REQUEST['function_oid']
					);
					break;
				case 'aggregate':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'aggregate',
						'action' => 'properties',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'aggrname' => $_REQUEST['aggrname'],
						'aggrtype' => $_REQUEST['aggrtype']
					);
					break;
				case 'slony_node':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'slony_cluster',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'no_id' => $_REQUEST['no_id'],
						'no_name' => $_REQUEST['no_name']
					);
					break;
				case 'slony_set':
					$vars = array(
						'server' => $_REQUEST['server'],
						'subject' => 'slony_set',
						'database' => $_REQUEST['database'],
						'schema' => $_REQUEST['schema'],
						'slony_set_id' => $_REQUEST['slony_set'],
						'slony_set' => $_REQUEST['slony_set']
					);
					break;
				case 'column':
					if (isset($_REQUEST['table']))
						$vars = array(
							'server' => $_REQUEST['server'],
							'subject' => 'column',
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table'],
							'column' => $_REQUEST['column']
						);
					else
						$vars = array(
							'server' => $_REQUEST['server'],
							'subject' => 'column',
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'view' => $_REQUEST['view'],
							'column' => $_REQUEST['column']
						);
					break;
				default:
					return false;
			}

			return 'redirect.php?'. http_build_query($vars, '', '&amp;');
		}

		/**
		 * Sets the form tracking variable
		 */
		function setForm() {
			$this->form = '';
			if (isset($_REQUEST['server'])) {
				$this->form .= "<input type=\"hidden\" name=\"server\" value=\"" . htmlspecialchars($_REQUEST['server']) . "\" />\n";
				if (isset($_REQUEST['database'])) {
					$this->form .= "<input type=\"hidden\" name=\"database\" value=\"" . htmlspecialchars($_REQUEST['database']) . "\" />\n";
					if (isset($_REQUEST['schema'])) {
						$this->form .= "<input type=\"hidden\" name=\"schema\" value=\"" . htmlspecialchars($_REQUEST['schema']) . "\" />\n";
					}
				}
			}
		}

		/**
		 * Render a value into HTML using formatting rules specified
		 * by a type name and parameters.
		 *
		 * @param $str The string to change
		 *
		 * @param $type Field type (optional), this may be an internal PostgreSQL type, or:
		 *			yesno    - same as bool, but renders as 'Yes' or 'No'.
		 *			pre      - render in a <pre> block.
		 *			nbsp     - replace all spaces with &nbsp;'s
		 *			verbatim - render exactly as supplied, no escaping what-so-ever.
		 *			callback - render using a callback function supplied in the 'function' param.
		 *
		 * @param $params Type parameters (optional), known parameters:
		 *			null     - string to display if $str is null, or set to TRUE to use a default 'NULL' string,
		 *			           otherwise nothing is rendered.
		 *			clip     - if true, clip the value to a fixed length, and append an ellipsis...
		 *			cliplen  - the maximum length when clip is enabled (defaults to $conf['max_chars'])
		 *			ellipsis - the string to append to a clipped value (defaults to $lang['strellipsis'])
		 *			tag      - an HTML element name to surround the value.
		 *			class    - a class attribute to apply to any surrounding HTML element.
		 *			align    - an align attribute ('left','right','center' etc.)
		 *			true     - (type='bool') the representation of true.
		 *			false    - (type='bool') the representation of false.
		 *			function - (type='callback') a function name, accepts args ($str, $params) and returns a rendering.
		 *			lineno   - prefix each line with a line number.
		 *			map      - an associative array.
		 *
		 * @return The HTML rendered value
		 */
		function printVal($str, $type = null, $params = array()) {
			global $lang, $conf, $data;

			// Shortcircuit for a NULL value
			if (is_null($str))
				return isset($params['null'])
						? ($params['null'] === true ? '<i>NULL</i>' : $params['null'])
						: '';

			if (isset($params['map']) && isset($params['map'][$str])) $str = $params['map'][$str];

			// Clip the value if the 'clip' parameter is true.
			if (isset($params['clip']) && $params['clip'] === true) {
				$maxlen = isset($params['cliplen']) && is_integer($params['cliplen']) ? $params['cliplen'] : $conf['max_chars'];
				$ellipsis = isset($params['ellipsis']) ? $params['ellipsis'] : $lang['strellipsis'];
				if (strlen($str) > $maxlen) {
					$str = substr($str, 0, $maxlen-1) . $ellipsis;
				}
			}

			$out = '';

			switch ($type) {
				case 'int2':
				case 'int4':
				case 'int8':
				case 'float4':
				case 'float8':
				case 'money':
				case 'numeric':
				case 'oid':
				case 'xid':
				case 'cid':
				case 'tid':
					$align = 'right';
					$out = nl2br(htmlspecialchars($str));
					break;
				case 'yesno':
					if (!isset($params['true'])) $params['true'] = $lang['stryes'];
					if (!isset($params['false'])) $params['false'] = $lang['strno'];
					// No break - fall through to boolean case.
				case 'bool':
				case 'boolean':
					if (is_bool($str)) $str = $str ? 't' : 'f';
					switch ($str) {
						case 't':
							$out = (isset($params['true']) ? $params['true'] : $lang['strtrue']);
							$align = 'center';
							break;
						case 'f':
							$out = (isset($params['false']) ? $params['false'] : $lang['strfalse']);
							$align = 'center';
							break;
						default:
							$out = htmlspecialchars($str);
					}
					break;
				case 'bytea':
					$tag = 'div';
					$class = 'pre';
					$out = $data->escapeBytea($str);
					break;
				case 'errormsg':
					$tag = 'pre';
					$class = 'error';
					$out = htmlspecialchars($str);
					break;
				case 'pre':
					$tag = 'pre';
					$out = htmlspecialchars($str);
					break;
				case 'prenoescape':
					$tag = 'pre';
					$out = $str;
					break;
				case 'nbsp':
					$out = nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($str)));
					break;
				case 'verbatim':
					$out = $str;
					break;
				case 'callback':
					$out = $params['function']($str, $params);
					break;
				case 'prettysize':
					if ($str == -1) 
						$out = $lang['strnoaccess'];
					else
					{
						$limit = 10 * 1024;
						$mult = 1;
						if ($str < $limit * $mult)
							$out = $str.' '.$lang['strbytes'];
						else
						{
							$mult *= 1024;
							if ($str < $limit * $mult)
								$out = floor(($str + $mult / 2) / $mult).' '.$lang['strkb'];
							else
							{
								$mult *= 1024;
								if ($str < $limit * $mult)
									$out = floor(($str + $mult / 2) / $mult).' '.$lang['strmb'];
								else
								{
									$mult *= 1024;
									if ($str < $limit * $mult)
										$out = floor(($str + $mult / 2) / $mult).' '.$lang['strgb'];
									else
									{
										$mult *= 1024;
										if ($str < $limit * $mult)
											$out = floor(($str + $mult / 2) / $mult).' '.$lang['strtb'];
									}
								}
							}
						}
					}
					break;
				case 'slonystatus':
					switch ($str) {
					case 'insync':
						$out = $lang['strhealthy'];
						break;
					case 'outofsync':
						$out = $lang['stroutofsync'];
						break;
					default:
						$out = $lang['strunknown'];
					}
					break;
				default:
					// If the string contains at least one instance of >1 space in a row, a tab
					// character, a space at the start of a line, or a space at the start of
					// the whole string then render within a pre-formatted element (<pre>).
					if (preg_match('/(^ |  |\t|\n )/m', $str)) {
						$tag = 'pre';
						$class = 'data';
						$out = htmlspecialchars($str);
					} else {
						$out = nl2br(htmlspecialchars($str));
					}
			}

			if (isset($params['class'])) $class = $params['class'];
			if (isset($params['align'])) $align = $params['align'];

			if (!isset($tag) && (isset($class) || isset($align))) $tag = 'div';

			if (isset($tag)) {
				$alignattr = isset($align) ? " style=\"text-align: {$align}\"" : '';
				$classattr = isset($class) ? " class=\"{$class}\"" : '';
				$out = "<{$tag}{$alignattr}{$classattr}>{$out}</{$tag}>";
			}

			// Add line numbers if 'lineno' param is true
			if (isset($params['lineno']) && $params['lineno'] === true) {
				$lines = explode("\n", $str);
				$num = count($lines);
				if ($num > 0) {
					$temp = "<table>\n<tr><td class=\"{$class}\" style=\"vertical-align: top; padding-right: 10px;\"><pre class=\"{$class}\">";
					for ($i = 1; $i <= $num; $i++) {
						$temp .= $i . "\n";
					}
					$temp .= "</pre></td><td class=\"{$class}\" style=\"vertical-align: top;\">{$out}</td></tr></table>\n";
					$out = $temp;
				}
				unset($lines);
			}

			return $out;
		}

		/**
		 * A function to recursively strip slashes.  Used to
		 * enforce magic_quotes_gpc being off.
		 * @param &var The variable to strip
		 */
		function stripVar(&$var) {
			if (is_array($var)) {
				foreach($var as $k => $v) {
					$this->stripVar($var[$k]);

					/* magic_quotes_gpc escape keys as well ...*/
					if (is_string($k)) {
						$ek = stripslashes($k);
						if ($ek !== $k) {
							$var[$ek] = $var[$k];
							unset($var[$k]);
						}
					}
				}
			}
			else
				$var = stripslashes($var);
		}

		/**
		 * Print out the page heading and help link
		 * @param $title Title, already escaped
		 * @param $help (optional) The identifier for the help link
		 */
		function printTitle($title, $help = null) {
			global $data, $lang;

			echo "<h2>";
			$this->printHelp($title, $help);
			echo "</h2>\n";
		}

		/**
		 * Print out a message
		 * @param $msg The message to print
		 */
		function printMsg($msg) {
			if ($msg != '') echo "<p class=\"message\">{$msg}</p>\n";
		}

		/**
		 * Creates a database accessor
		 */
		function getDatabaseAccessor($database, $server_id = null) {
			global $lang, $conf, $misc, $_connection;

			$server_info = $this->getServerInfo($server_id);

			// Perform extra security checks if this config option is set
			if ($conf['extra_login_security']) {
				// Disallowed logins if extra_login_security is enabled.
				// These must be lowercase.
				$bad_usernames = array('pgsql', 'postgres', 'root', 'administrator');

				$username = strtolower($server_info['username']);

				if ($server_info['password'] == '' || in_array($username, $bad_usernames)) {
					unset($_SESSION['webdbLogin'][$_REQUEST['server']]);
					$msg = $lang['strlogindisallowed'];
					include('./login.php');
					exit;
				}
			}

			// Create the connection object and make the connection
			$_connection = new Connection(
				$server_info['host'],
				$server_info['port'],
				$server_info['sslmode'],
				$server_info['username'],
				$server_info['password'],
				$database
			);

			// Get the name of the database driver we need to use.
			// The description of the server is returned in $platform.
			$_type = $_connection->getDriver($platform);
			if ($_type === null) {
				printf($lang['strpostgresqlversionnotsupported'], $postgresqlMinVer);
				exit;
			}
			$this->setServerInfo('platform', $platform, $server_id);
			$this->setServerInfo('pgVersion', $_connection->conn->pgVersion, $server_id);

			// Create a database wrapper class for easy manipulation of the
			// connection.
			include_once('./classes/database/' . $_type . '.php');
			$data = new $_type($_connection->conn);
			$data->platform = $_connection->platform;

			/* we work on UTF-8 only encoding */
			$data->execute("SET client_encoding TO 'UTF-8'");

			return $data;
		}


		/**
		 * Prints the page header.  If global variable $_no_output is
		 * set then no header is drawn.
		 * @param $title The title of the page
		 * @param $script script tag
		 */
		function printHeader($title = '', $script = null, $frameset = false) {
			global $appName, $lang, $_no_output, $conf;

			if (!isset($_no_output)) {
				header("Content-Type: text/html; charset=utf-8");
				// Send XHTML headers, or regular XHTML strict headers
				echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
				if ($frameset == true) {
					echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n";
				} else if (isset($conf['use_xhtml_strict']) && $conf['use_xhtml_strict']) {
					echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-Strict.dtd\">\n";
				} else {
					echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
				}
				echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"{$lang['applocale']}\" lang=\"{$lang['applocale']}\"";
				if (strcasecmp($lang['applangdir'], 'ltr') != 0) echo " dir=\"", htmlspecialchars($lang['applangdir']), "\"";
				echo ">\n";

				echo "<head>\n";
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
				// Theme
				echo "<link rel=\"stylesheet\" href=\"themes/{$conf['theme']}/global.css\" type=\"text/css\" />\n";
				echo "<link rel=\"shortcut icon\" href=\"images/themes/{$conf['theme']}/Favicon.ico\" type=\"image/vnd.microsoft.icon\" />\n";
				echo "<link rel=\"icon\" type=\"image/png\" href=\"images/themes/{$conf['theme']}/Introduction.png\" />\n";
				echo "<title>", htmlspecialchars($appName);
				if ($title != '') echo htmlspecialchars(" - {$title}");
				echo "</title>\n";

				if ($script) echo "{$script}\n";
				echo "</head>\n";
			}
		}

		/**
		 * Prints the page footer
		 * @param $doBody True to output body tag, false otherwise
		 */
		function printFooter($doBody = true) {
			global $_reload_browser, $_reload_drop_database;
			global $lang, $_no_bottom_link;

			if ($doBody) {
				if (isset($_reload_browser)) $this->printReload(false);
				elseif (isset($_reload_drop_database)) $this->printReload(true);
				if (!isset($_no_bottom_link)) 
					echo "<a href=\"#\" class=\"bottom_link\">".$lang['strgotoppage']."</a>";

				echo "</body>\n";
			}
			echo "</html>\n";
		}

		/**
		 * Prints the page body.
		 * @param $doBody True to output body tag, false otherwise
		 * @param $bodyClass - name of body class
		 */
		function printBody($bodyClass = '', $doBody = true ) {
			global $_no_output;

			if (!isset($_no_output)) {
				if ($doBody) {
					$bodyClass = htmlspecialchars($bodyClass);
					echo "<body", ($bodyClass == '' ? '' : " class=\"{$bodyClass}\"");
					echo ">\n";
				}
			}
		}

		/**
		 * Outputs JavaScript code that will reload the browser
		 * @param $database True if dropping a database, false otherwise
		 */
		function printReload($database) {
			echo "<script type=\"text/javascript\">\n";
			if ($database)
				echo "\tparent.frames.browser.location.href=\"browser.php\";\n";
			else
				echo "\tparent.frames.browser.location.reload();\n";
			echo "</script>\n";
		}

		/**
		 * Display navigation tabs
		 * @param $tabs An associative array of tabs definitions, see printNav() for an example.
		 * @param $activetab The name of the tab to be highlighted.
		 */
		function printTabs($tabs, $activetab) {
			global $misc, $conf, $data, $lang;

			if (is_string($tabs)) {
				$_SESSION['webdbLastTab'][$tabs] = $activetab;
				$tabs = $this->getNavTabs($tabs);
			}

			echo "<table class=\"tabs\"><tr>\n";
			#echo "<div class=\"tabs\">\n";

			# FIXME: don't count hidden tabs
			$width = (int)(100 / count($tabs)).'%';

			foreach ($tabs as $tab_id => $tab) {
				$active = ($tab_id == $activetab) ? ' active' : '';

				if (!isset($tab['hide']) || $tab['hide'] !== true) {

					$tablink = "<a" . $this->printActionUrl($tab, $_REQUEST, 'href') . ">";

					if (isset($tab['icon']) && $icon = $this->icon($tab['icon']))
						$tablink .= "<span class=\"icon\"><img src=\"{$icon}\" alt=\"{$tab['title']}\" /></span>";

					$tablink .= "<span class=\"label\">{$tab['title']}</span></a>";

					echo "<td style=\"width: {$width}\" class=\"tab{$active}\">";
					#echo "<span class=\"tab{$active}\" style=\"white-space:nowrap;\">";

					if (isset($tab['help']))
						$this->printHelp($tablink, $tab['help']);
					else
						echo $tablink;

					echo "</td>\n";
					#echo "</span>\n";
				}
			}

			echo "</tr></table>\n";
			#echo "</div>\n";
		}

		/**
		 * Retrieve the tab info for a specific tab bar.
		 * @param $section The name of the tab bar.
		 */
		function getNavTabs($section) {
			global $data, $lang, $conf, $slony;

			$hide_advanced = ($conf['show_advanced'] === false);

			switch ($section) {
				case 'root':
					return array (
						'intro' => array (
							'title' => $lang['strintroduction'],
							'url'   => "intro.php",
							'icon'  => 'Introduction',
						),
						'servers' => array (
							'title' => $lang['strservers'],
							'url'   => "servers.php",
							'icon'  => 'Servers',
						),
					);

				case 'server':
				case 'report':
					$hide_users = !$data->isSuperUser();
					$tmp = array (
						'databases' => array (
							'title' => $lang['strdatabases'],
							'url'   => 'all_db.php',
							'urlvars' => array('subject' => 'server'),
							'help'  => 'pg.database',
							'icon'  => 'Databases',
						)
					);
					if ($data->hasRoles()) {
						$tmp = array_merge($tmp, array(
							'roles' => array (
								'title' => $lang['strroles'],
								'url'   => 'roles.php',
								'urlvars' => array('subject' => 'server'),
								'hide'  => $hide_users,
								'help'  => 'pg.role',
								'icon'  => 'Roles',
							)
						));
					}
					else {
						$tmp = array_merge($tmp, array(
							'users' => array (
								'title' => $lang['strusers'],
								'url'   => 'users.php',
								'urlvars' => array('subject' => 'server'),
								'hide'  => $hide_users,
								'help'  => 'pg.user',
								'icon'  => 'Users',
							),
							'groups' => array (
								'title' => $lang['strgroups'],
								'url'   => 'groups.php',
								'urlvars' => array('subject' => 'server'),
								'hide'  => $hide_users,
								'help'  => 'pg.group',
								'icon'  => 'UserGroups',
							)
						));
					}

					$tmp = array_merge($tmp, array(
						'account' => array (
							'title' => $lang['straccount'],
							'url'   => $data->hasRoles() ? 'roles.php' : 'users.php',
							'urlvars' => array('subject' => 'server', 'action' => 'account'),
							'hide'  => !$hide_users,
							'help'  => 'pg.role',
							'icon'  => 'User',
						),
						'tablespaces' => array (
							'title' => $lang['strtablespaces'],
							'url'   => 'tablespaces.php',
							'urlvars' => array('subject' => 'server'),
							'hide'  => (!$data->hasTablespaces()),
							'help'  => 'pg.tablespace',
							'icon'  => 'Tablespaces',
						),
						'export' => array (
							'title' => $lang['strexport'],
							'url'   => 'all_db.php',
							'urlvars' => array('subject' => 'server', 'action' => 'export'),
							'hide'  => (!$this->isDumpEnabled()),
							'icon'  => 'Export',
						),
						'reports' => array (
							'title' => $lang['strreports'],
							'url'   => 'reports.php',
							'urlvars' => array('subject' => 'server'),
							'hide' => !$conf['show_reports'],
							'icon' => 'Reports',
						),
					));
					return $tmp;
					break;
				case 'database':
					$tabs = array (
						'schemas' => array (
							'title' => $lang['strschemas'],
							'url'   => 'schemas.php',
							'urlvars' => array('subject' => 'database'),
							'help'  => 'pg.schema',
							'icon'  => 'Schemas',
						),
						'sql' => array (
							'title' => $lang['strsql'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'sql', 'new' => 1),
							'help'  => 'pg.sql',
							'tree'  => false,
							'icon'  => 'SqlEditor'
						),
						'find' => array (
							'title' => $lang['strfind'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'find'),
							'tree'  => false,
							'icon'  => 'Search'
						),
						'variables' => array (
							'title' => $lang['strvariables'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'variables'),
							'help'  => 'pg.variable',
							'tree'  => false,
							'icon'  => 'Variables',
						),
						'processes' => array (
							'title' => $lang['strprocesses'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'processes'),
							'help'  => 'pg.process',
							'tree'  => false,
							'icon'  => 'Processes',
						),
						'locks' => array (
							'title' => $lang['strlocks'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'locks'),
							'help'  => 'pg.locks',
							'tree'  => false,
							'icon'  => 'Key',
						),
						'admin' => array (
							'title' => $lang['stradmin'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'admin'),
							'tree'  => false,
							'icon'  => 'Admin',
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array('subject' => 'database'),
							'hide'  => (!isset($data->privlist['database'])),
							'help'  => 'pg.privilege',
							'tree'  => false,
							'icon'  => 'Privileges',
						),
						'languages' => array (
							'title' => $lang['strlanguages'],
							'url'   => 'languages.php',
							'urlvars' => array('subject' => 'database'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.language',
							'icon'  => 'Languages',
						),
						'casts' => array (
							'title' => $lang['strcasts'],
							'url'   => 'casts.php',
							'urlvars' => array('subject' => 'database'),
							'hide'  => ($hide_advanced),
							'help'  => 'pg.cast',
							'icon'  => 'Casts',
						),
						'slony' => array (
							'title' => 'Slony',
							'url'   => 'plugin_slony.php',
							'urlvars' => array('subject' => 'database', 'action' => 'clusters_properties'),
							'hide'  => !isset($slony),
							'help'  => '',
							'icon'  => 'Replication',
						),
						'export' => array (
							'title' => $lang['strexport'],
							'url'   => 'database.php',
							'urlvars' => array('subject' => 'database', 'action' => 'export'),
							'hide'  => (!$this->isDumpEnabled()),
							'tree'  => false,
							'icon'  => 'Export',
						),
					);
					return $tabs;

				case 'schema':
					$tabs = array (
						'tables' => array (
							'title' => $lang['strtables'],
							'url'   => 'tables.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.table',
							'icon'  => 'Tables',
						),
						'views' => array (
							'title' => $lang['strviews'],
							'url'   => 'views.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.view',
							'icon'  => 'Views',
						),
						'sequences' => array (
							'title' => $lang['strsequences'],
							'url'   => 'sequences.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.sequence',
							'icon'  => 'Sequences',
						),
						'functions' => array (
							'title' => $lang['strfunctions'],
							'url'   => 'functions.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.function',
							'icon'  => 'Functions',
						),
						'fulltext' => array (
							'title' => $lang['strfulltext'],
							'url'   => 'fulltext.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.fts',
							'tree'  => true,
							'icon'  => 'Fts',
						),
						'domains' => array (
							'title' => $lang['strdomains'],
							'url'   => 'domains.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.domain',
							'icon'  => 'Domains',
						),
						'aggregates' => array (
							'title' => $lang['straggregates'],
							'url'   => 'aggregates.php',
							'urlvars' => array('subject' => 'schema'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.aggregate',
							'icon'  => 'Aggregates',
						),
						'types' => array (
							'title' => $lang['strtypes'],
							'url'   => 'types.php',
							'urlvars' => array('subject' => 'schema'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.type',
							'icon'  => 'Types',
						),
						'operators' => array (
							'title' => $lang['stroperators'],
							'url'   => 'operators.php',
							'urlvars' => array('subject' => 'schema'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.operator',
							'icon'  => 'Operators',
						),
						'opclasses' => array (
							'title' => $lang['stropclasses'],
							'url'   => 'opclasses.php',
							'urlvars' => array('subject' => 'schema'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.opclass',
							'icon'  => 'OperatorClasses',
						),
						'conversions' => array (
							'title' => $lang['strconversions'],
							'url'   => 'conversions.php',
							'urlvars' => array('subject' => 'schema'),
							'hide'  => $hide_advanced,
							'help'  => 'pg.conversion',
							'icon'  => 'Conversions',
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array('subject' => 'schema'),
							'help'  => 'pg.privilege',
							'tree'  => false,
							'icon'  => 'Privileges',
						),
						'export' => array (
							'title' => $lang['strexport'],
							'url'   => 'schemas.php',
							'urlvars' => array('subject' => 'schema', 'action' => 'export'),
							'hide'  => (!$this->isDumpEnabled()),
							'tree'  => false,
							'icon'  => 'Export',
						),
					);
					if (!$data->hasFTS()) unset($tabs['fulltext']);
					return $tabs;

				case 'table':
					return array (
						'columns' => array (
							'title' => $lang['strcolumns'],
							'url'   => 'tblproperties.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'icon'  => 'Columns',
							'branch'=> true,
						),
						'indexes' => array (
							'title' => $lang['strindexes'],
							'url'   => 'indexes.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'help'  => 'pg.index',
							'icon'  => 'Indexes',
							'branch'=> true,
						),
						'constraints' => array (
							'title' => $lang['strconstraints'],
							'url'   => 'constraints.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'help'  => 'pg.constraint',
							'icon'  => 'Constraints',
							'branch'=> true,
						),
						'triggers' => array (
							'title' => $lang['strtriggers'],
							'url'   => 'triggers.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'help'  => 'pg.trigger',
							'icon'  => 'Triggers',
							'branch'=> true,
						),
						'rules' => array (
							'title' => $lang['strrules'],
							'url'   => 'rules.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'help'  => 'pg.rule',
							'icon'  => 'Rules',
							'branch'=> true,
						),
						'admin' => array (
							'title' => $lang['stradmin'],
							'url'   => 'tables.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table'), 'action' => 'admin'),
							'icon'  => 'Admin',
						),
						'info' => array (
							'title' => $lang['strinfo'],
							'url'   => 'info.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'icon'  => 'Statistics',
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table')),
							'help'  => 'pg.privilege',
							'icon'  => 'Privileges',
						),
						'import' => array (
							'title' => $lang['strimport'],
							'url'   => 'tblproperties.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table'), 'action' => 'import'),
							'icon'  => 'Import',
							'hide'	=> false,
						),
						'export' => array (
							'title' => $lang['strexport'],
							'url'   => 'tblproperties.php',
							'urlvars' => array('subject' => 'table', 'table' => field('table'), 'action' => 'export'),
							'icon'  => 'Export',
							'hide'	=> false,
						),
					);

				case 'view':
					return array (
						'columns' => array (
							'title' => $lang['strcolumns'],
							'url'   => 'viewproperties.php',
							'urlvars' => array('subject' => 'view', 'view' => field('view')),
							'icon'  => 'Columns',
							'branch'=> true,
						),
						'definition' => array (
							'title' => $lang['strdefinition'],
							'url'   => 'viewproperties.php',
							'urlvars' => array('subject' => 'view', 'view' => field('view'), 'action' => 'definition'),
							'icon'  => 'Definition'
						),
						'rules' => array (
							'title' => $lang['strrules'],
							'url'   => 'rules.php',
							'urlvars' => array('subject' => 'view', 'view' => field('view')),
							'help'  => 'pg.rule',
							'icon'  => 'Rules',
							'branch'=> true,
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array('subject' => 'view', 'view' => field('view')),
							'help'  => 'pg.privilege',
							'icon'  => 'Privileges',
						),
						'export' => array (
							'title' => $lang['strexport'],
							'url'   => 'viewproperties.php',
							'urlvars' => array('subject' => 'view', 'view' => field('view'), 'action' => 'export'),
							'icon'  => 'Export',
							'hide'	=> false,
						),
					);

				case 'function':
					return array (
						'definition' => array (
							'title' => $lang['strdefinition'],
							'url'   => 'functions.php',
							'urlvars' => array(
									'subject' => 'function',
									'function' => field('function'),
									'function_oid' => field('function_oid'),
									'action' => 'properties',
								),
							'icon'  => 'Definition',
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array(
									'subject' => 'function',
									'function' => field('function'),
									'function_oid' => field('function_oid'),
								),
							'icon'  => 'Privileges',
						),
					);

				case 'aggregate':
					return array (
						'definition' => array (
							'title' => $lang['strdefinition'],
							'url'   => 'aggregates.php',
							'urlvars' => array(
									'subject' => 'aggregate',
									'aggrname' => field('aggrname'),
									'aggrtype' => field('aggrtype'),
									'action' => 'properties',
								),
							'icon'  => 'Definition',
						),
					);

				case 'role':
					return array (
						'definition' => array (
							'title' => $lang['strdefinition'],
							'url'   => 'roles.php',
							'urlvars' => array(
									'subject' => 'role',
									'rolename' => field('rolename'),
									'action' => 'properties',
								),
							'icon'  => 'Definition',
						),
					);

				case 'popup':
					return array (
						'sql' => array (
							'title' => $lang['strsql'],
							'url'   => 'sqledit.php',
							'urlvars' => array('subject' => 'schema', 'action' => 'sql'),
							'help'  => 'pg.sql',
							'icon'  => 'SqlEditor',
						),
						'find' => array (
							'title' => $lang['strfind'],
							'url'   => 'sqledit.php',
							'urlvars' => array('subject' => 'schema', 'action' => 'find'),
							'icon'  => 'Search',
						),
					);

				case 'slony_cluster':
					return array (
						'properties' => array (
							'title' => $lang['strproperties'],
							'url'   => 'plugin_slony.php',
							'urlvars' => array(
									'subject' => 'slony_cluster',
									'action' => 'cluster_properties',
									'slony_cluster' => field('slony_cluster')
								),
							'help'  => '',
							'tree'  => false,
							'icon'  => 'Cluster',
						),
						'nodes' => array (
							'title' => $lang['strnodes'],
							'url'   => 'plugin_slony.php',
							'urlvars' => array(
									'subject' => 'slony_cluster',
									'action' => 'nodes_properties',
									'slony_cluster' => field('slony_cluster')
								),
							'branch' => 'nodes',
							'help'  => '',
							'icon'  => 'Nodes',
						),
						'sets' => array (
							'title' => $lang['strrepsets'],
							'url'   => 'plugin_slony.php',
							'urlvars' => array(
								'subject' => 'slony_cluster',
								'action' => 'sets_properties',
								'slony_cluster' => field('slony_cluster')
							),
							'branch' => 'sets',
							'help'  => '',
							'icon'  => 'ReplicationSets',
						),
					);

				case 'column':
					return array(
						'properties' => array (
							'title'		=> $lang['strcolprop'],
							'url'		=> 'colproperties.php',
							'urlvars'	=> array(
								'subject' => 'column',
								'table' => field('table'),
								'column' => field('column')
							),
							'icon'		=> 'Column'
						),
						'privileges' => array (
							'title' => $lang['strprivileges'],
							'url'   => 'privileges.php',
							'urlvars' => array(
								'subject' => 'column',
								'table' => field('table'),
								'column' => field('column')
							),
							'help'  => 'pg.privilege',
							'icon'  => 'Privileges',
						)
					);

                case 'fulltext':
                    return array (
                        'ftsconfigs' => array (
                            'title' => $lang['strftstabconfigs'],
                            'url'   => 'fulltext.php',
                            'urlvars' => array('subject' => 'schema'),
                            'hide'  => !$data->hasFTS(),
                            'help'  => 'pg.ftscfg',
                            'tree'  => true,
                            'icon'  => 'FtsCfg',
                        ),
                        'ftsdicts' => array (
                            'title' => $lang['strftstabdicts'],
                            'url'   => 'fulltext.php',
                            'urlvars' => array('subject' => 'schema', 'action' => 'viewdicts'),
                            'hide'  => !$data->hasFTS(),
                            'help'  => 'pg.ftsdict',
                            'tree'  => true,
                            'icon'  => 'FtsDict',
                        ),
                        'ftsparsers' => array (
                            'title' => $lang['strftstabparsers'],
                            'url'   => 'fulltext.php',
                            'urlvars' => array('subject' => 'schema', 'action' => 'viewparsers'),
                            'hide'  => !$data->hasFTS(),
                            'help'  => 'pg.ftsparser',
                            'tree'  => true,
                            'icon'  => 'FtsParser',
                        ),
                    );

				default:
					return array();
			}
		}

		/**
		 * Get the URL for the last active tab of a particular tab bar.
		 */
		function getLastTabURL($section) {
			global $data;

			$tabs = $this->getNavTabs($section);

			if (isset($_SESSION['webdbLastTab'][$section]) && isset($tabs[$_SESSION['webdbLastTab'][$section]]))
				$tab = $tabs[$_SESSION['webdbLastTab'][$section]];
			else
				$tab = reset($tabs);

			return isset($tab['url']) ? $tab : null;
		}

		function printTopbar() {
			global $lang, $conf, $appName, $appVersion, $appLangFiles;

			$server_info = $this->getServerInfo();

			echo "<div class=\"topbar\"><table style=\"width: 100%\"><tr><td>";

			if ($server_info && isset($server_info['platform']) && isset($server_info['username'])) {
				echo sprintf($lang['strtopbar'],
					'<span class="platform">'.htmlspecialchars($server_info['platform']).'</span>',
					'<span class="host">'.htmlspecialchars((empty($server_info['host'])) ? 'localhost':$server_info['host']).'</span>',
					'<span class="port">'.htmlspecialchars($server_info['port']).'</span>',
					'<span class="username">'.htmlspecialchars($server_info['username']).'</span>');
			} else {
				echo "<span class=\"appname\">$appName</span> <span class=\"version\">$appVersion</span>";
			}

			echo "</td>";

			if (isset($_REQUEST['server'])) {
				$sql_url = "sqledit.php?{$this->href}&amp;action=";
				$sql_window_id = htmlspecialchars('sqledit:'.$_REQUEST['server']);
				$history_url = "history.php?{$this->href}&amp;action=pophistory";
				$history_window_id = htmlspecialchars('history:'.$_REQUEST['server']);
				$logout_shared = isset($_SESSION['sharedUsername']) ?
					' onclick="return confirm(\''. $lang['strconfdropcred']. '\')"':
					'';

				echo "<td style=\"text-align: right\">";
				echo "<ul class=\"toplink\">\n\t<li><a class=\"toplink\" href=\"{$sql_url}sql\" target=\"sqledit\" onclick=\"window.open('{$sql_url}sql','{$sql_window_id}','toolbar=no,width=700,height=500,resizable=yes,scrollbars=yes').focus(); return false;\">{$lang['strsql']}</a></li>\n";
				echo "\t<li><a class=\"toplink\" href=\"{$history_url}\" onclick=\"window.open('{$history_url}','{$history_window_id}','toolbar=no,width=800,height=600,resizable=yes,scrollbars=yes').focus(); return false;\">{$lang['strhistory']}</a></li>\n";
				echo "\t<li><a class=\"toplink\" href=\"{$sql_url}find\" target=\"sqledit\" onclick=\"window.open('{$sql_url}find','{$sql_window_id}','toolbar=no,width=700,height=500,resizable=yes,scrollbars=yes').focus(); return false;\">{$lang['strfind']}</a></li>\n";
 				echo "\t<li><a class=\"toplink\" href=\"servers.php?action=logout&amp;logoutServer=".htmlspecialchars($server_info['host']).":".htmlspecialchars($server_info['port']).":".htmlspecialchars($server_info['sslmode'])."\"{$logout_shared}>{$lang['strlogout']}</a></li>\n";
 				echo "</ul>\n";
				echo "</td>";
			}
/*
			echo "<td style=\"text-align: right; width: 1%\">";

			echo "<form method=\"get\"><select name=\"language\" onchange=\"this.form.submit()\">\n";
			$language = isset($_SESSION['webdbLanguage']) ? $_SESSION['webdbLanguage'] : 'english';
			foreach ($appLangFiles as $k => $v) {
				echo "<option value=\"{$k}\"",
					($k == $language) ? ' selected="selected"' : '',
					">{$v}</option>\n";
			}
			echo "</select>\n";
			echo "<noscript><input type=\"submit\" value=\"Set Language\"></noscript>\n";
			foreach ($_GET as $key => $val) {
				if ($key == 'language') continue;
				echo "<input type=\"hidden\" name=\"$key\" value=\"", htmlspecialchars($val), "\" />\n";
			}
			echo "</form>\n";

			echo "</td>";
*/
			echo "</tr></table></div>\n";
		}

		/**
		 * Display a bread crumb trail.
		 */
		function printTrail($trail = array()) {
			global $lang;

			$this->printTopbar();

			if (is_string($trail)) {
				$trail = $this->getTrail($trail);
			}

			echo "<div class=\"trail\"><table><tr>";

			foreach ($trail as $crumb) {
				echo "<td class=\"crumb\">";
				$crumblink = "<a";

				if (isset($crumb['url']))
					$crumblink .= " href=\"{$crumb['url']}\"";

				if (isset($crumb['title']))
					$crumblink .= " title=\"{$crumb['title']}\"";

				$crumblink .= ">";

				if (isset($crumb['title']))
					$iconalt = $crumb['title'];
				else
					$iconalt = 'Database Root';

				if (isset($crumb['icon']) && $icon = $this->icon($crumb['icon']))
					$crumblink .= "<span class=\"icon\"><img src=\"{$icon}\" alt=\"{$iconalt}\" /></span>";

				$crumblink .= "<span class=\"label\">" . htmlspecialchars($crumb['text']) . "</span></a>";

				if (isset($crumb['help']))
					$this->printHelp($crumblink, $crumb['help']);
				else
					echo $crumblink;

				echo "{$lang['strseparator']}";
				echo "</td>";
			}

			echo "</tr></table></div>\n";
		}

		/**
		 * Create a bread crumb trail of the object hierarchy.
		 * @param $object The type of object at the end of the trail.
		 */
		function getTrail($subject = null) {
			global $lang, $conf, $data, $appName;

			$trail = array();
			$vars = '';
			$done = false;

			$trail['root'] = array(
				'text'  => $appName,
				'url'   => 'redirect.php?subject=root',
				'icon'  => 'Introduction'
			);

			if ($subject == 'root') $done = true;

			if (!$done) {
				$server_info = $this->getServerInfo();
				$trail['server'] = array(
					'title' => $lang['strserver'],
					'text'  => $server_info['desc'],
					'url'   => $this->getHREFSubject('server'),
					'help'  => 'pg.server',
					'icon'  => 'Server'
				);
			}
			if ($subject == 'server') $done = true;

			if (isset($_REQUEST['report']) && !$done) {
				$trail['report'] = array(
					'title' => $lang['strreport'],
					'text'  => $_REQUEST['report'],
					'url'   => $this->getHREFSubject('report'),
					'icon'  => 'Report'
				);
			}

			if (isset($_REQUEST['database']) && !$done) {
				$trail['database'] = array(
					'title' => $lang['strdatabase'],
					'text'  => $_REQUEST['database'],
					'url'   => $this->getHREFSubject('database'),
					'help'  => 'pg.database',
					'icon'  => 'Database'
				);
			} elseif (isset($_REQUEST['rolename']) && !$done) {
				$trail['role'] = array(
					'title' => $lang['strrole'],
					'text'  => $_REQUEST['rolename'],
					'url'   => $this->getHREFSubject('role'),
					'help'  => 'pg.role',
					'icon'  => 'Roles'
				);
			}
			if ($subject == 'database' || $subject == 'role' || $subject == 'report') $done = true;

			if (isset($_REQUEST['schema']) && !$done) {
				$trail['schema'] = array(
					'title' => $lang['strschema'],
					'text'  => $_REQUEST['schema'],
					'url'   => $this->getHREFSubject('schema'),
					'help'  => 'pg.schema',
					'icon'  => 'Schema'
				);
			}
			if ($subject == 'schema') $done = true;

			if (isset($_REQUEST['slony_cluster']) && !$done) {
				$trail['slony_cluster'] = array(
					'title' => 'Slony Cluster',
					'text'  => $_REQUEST['slony_cluster'],
					'url'   => $this->getHREFSubject('slony_cluster'),
					'help'  => 'sl.cluster',
					'icon'  => 'Cluster'
				);
			}
			if ($subject == 'slony_cluster') $done = true;

			if (isset($_REQUEST['table']) && !$done) {
				$trail['table'] = array(
					'title' => $lang['strtable'],
					'text'  => $_REQUEST['table'],
					'url'   => $this->getHREFSubject('table'),
					'help'  => 'pg.table',
					'icon'  => 'Table'
				);
			} elseif (isset($_REQUEST['view']) && !$done) {
				$trail['view'] = array(
					'title' => $lang['strview'],
					'text'  => $_REQUEST['view'],
					'url'   => $this->getHREFSubject('view'),
					'help'  => 'pg.view',
					'icon'  => 'View'
				);
			} elseif (isset($_REQUEST['ftscfg']) && !$done) {
				$trail['ftscfg'] = array(
					'title' => $lang['strftsconfig'],
					'text'  => $_REQUEST['ftscfg'],
					'url'   => $this->getHREFSubject('ftscfg'),
					'help'  => 'pg.ftscfg.example',
					'icon'  => 'Fts'
				);
			}
			if ($subject == 'table' || $subject == 'view' || $subject == 'ftscfg') $done = true;

			if (!$done && !is_null($subject)) {
				switch ($subject) {
					case 'function':
						$trail[$subject] = array(
							'title' => $lang['str'.$subject],
							'text'  => $_REQUEST[$subject],
							'url'   => $this->getHREFSubject('function'),
							'help'  => 'pg.function',
							'icon'  => 'Function'
						);
						break;
					case 'aggregate':
						$trail[$subject] = array(
							'title' => $lang['straggregate'],
							'text'  => $_REQUEST['aggrname'],
							'url'   => $this->getHREFSubject('aggregate'),
							'help'  => 'pg.aggregate',
							'icon'  => 'Aggregate'
						);
						break;
					case 'slony_node':
						$trail[$subject] = array(
							'title' => 'Slony Node',
							'text'  => $_REQUEST['no_name'],
							'help'  => 'sl.'.$subject,
							'icon'  => 'Node'
						);
						break;
					case 'slony_set':
						$trail[$subject] = array(
							'title' => $lang['str'.$subject],
							'text'  => $_REQUEST[$subject],
							'help'  => 'sl.'.$subject,
							'icon'  => 'AvailableReplicationSet'
						);
						break;
					case 'column':
						$trail['column'] = array (
							'title' => $lang['strcolumn'],
							'text'  => $_REQUEST['column'],
							'icon'	=> 'Column',
							'url'   => $this->getHREFSubject('column')
						);
						break;
					default:
						if (isset($_REQUEST[$subject])) {
							switch ($subject) {
								case 'domain': $icon = 'Domain'; break;
								case 'sequence': $icon = 'Sequence'; break;
								case 'type': $icon = 'Type'; break;
								case 'operator': $icon = 'Operator'; break;
								default: $icon = null; break;
							}
							$trail[$subject] = array(
								'title' => $lang['str'.$subject],
								'text'  => $_REQUEST[$subject],
								'help'  => 'pg.'.$subject,
								'icon'  => $icon,
							);
						}
				}
			}

			return $trail;
		}

		/**
		 * Do multi-page navigation.  Displays the prev, next and page options.
		 * @param $page the page currently viewed
		 * @param $pages the maximum number of pages
		 * @param $url the url to refer to with the page number inserted
		 * @param $max_width the number of pages to make available at any one time (default = 20)
		 */
		function printPages($page, $pages, $url, $max_width = 20) {
			global $lang;

			$window = 10;

			if ($page < 0 || $page > $pages) return;
			if ($pages < 0) return;
			if ($max_width <= 0) return;

			if ($pages > 1) {
				echo "<p style=\"text-align: center\">\n";
				if ($page != 1) {
					$temp = str_replace('%s', 1, $url);
					echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strfirst']}</a>\n";
					$temp = str_replace('%s', $page - 1, $url);
					echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strprev']}</a>\n";
				}

				if ($page <= $window) {
					$min_page = 1;
					$max_page = min(2 * $window, $pages);
				}
				elseif ($page > $window && $pages >= $page + $window) {
					$min_page = ($page - $window) + 1;
					$max_page = $page + $window;
				}
				else {
					$min_page = ($page - (2 * $window - ($pages - $page))) + 1;
					$max_page = $pages;
				}

				// Make sure min_page is always at least 1
				// and max_page is never greater than $pages
				$min_page = max($min_page, 1);
				$max_page = min($max_page, $pages);

				for ($i = $min_page; $i <= $max_page; $i++) {
					$temp = str_replace('%s', $i, $url);
					if ($i != $page) echo "<a class=\"pagenav\" href=\"{$temp}\">$i</a>\n";
					else echo "$i\n";
				}
				if ($page != $pages) {
					$temp = str_replace('%s', $page + 1, $url);
					echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strnext']}</a>\n";
					$temp = str_replace('%s', $pages, $url);
					echo "<a class=\"pagenav\" href=\"{$temp}\">{$lang['strlast']}</a>\n";
				}
				echo "</p>\n";
			}
		}

		/**
		 * Displays link to the context help.
		 * @param $str   - the string that the context help is related to (already escaped)
		 * @param $help  - help section identifier
		 */
		function printHelp($str, $help) {
			global $lang, $data;

			echo $str;
			if ($help) {
				echo "<a class=\"help\" href=\"";
				echo htmlspecialchars("help.php?help=".urlencode($help)."&server=".urlencode($_REQUEST['server']));
				echo "\" title=\"{$lang['strhelp']}\" target=\"phppgadminhelp\">{$lang['strhelpicon']}</a>";
			}
		}

		/**
		 * Outputs JavaScript to set default focus
		 * @param $object eg. forms[0].username
		 */
		function setFocus($object) {
			echo "<script type=\"text/javascript\">\n";
			echo "   document.{$object}.focus();\n";
			echo "</script>\n";
		}

		/**
		 * Outputs JavaScript to set the name of the browser window.
		 * @param $name the window name
		 * @param $addServer if true (default) then the server id is
		 *        attached to the name.
		 */
		function setWindowName($name, $addServer = true) {
			echo "<script type=\"text/javascript\">\n";
			echo "//<![CDATA[\n";
			echo "   window.name = '{$name}", ($addServer ? ':'.htmlspecialchars($_REQUEST['server']) : ''), "';\n";
			echo "//]]>\n";
			echo "</script>\n";
		}

		/**
		 * Converts a PHP.INI size variable to bytes.  Taken from publically available
		 * function by Chris DeRose, here: http://www.php.net/manual/en/configuration.directives.php#ini.file-uploads
		 * @param $strIniSize The PHP.INI variable
		 * @return size in bytes, false on failure
		 */
		function inisizeToBytes($strIniSize) {
			// This function will take the string value of an ini 'size' parameter,
			// and return a double (64-bit float) representing the number of bytes
			// that the parameter represents. Or false if $strIniSize is unparseable.
			$a_IniParts = array();

			if (!is_string($strIniSize))
				return false;

			if (!preg_match ('/^(\d+)([bkm]*)$/i', $strIniSize,$a_IniParts))
				return false;

			$nSize = (double) $a_IniParts[1];
			$strUnit = strtolower($a_IniParts[2]);

			switch($strUnit) {
				case 'm':
					return ($nSize * (double) 1048576);
				case 'k':
					return ($nSize * (double) 1024);
				case 'b':
				default:
					return $nSize;
			}
		}

		/**
		 * Display a URL given an action associative array.
		 * @param $action An associative array of the follow properties:
		 *			'url'  => The first part of the URL (before the ?)
		 *			'urlvars' => Associative array of (URL variable => field name)
		 *						these are appended to the URL
		 *			'urlfn' => Function to apply to URL before display
		 * @param $fields Field data from which 'urlfield' and 'vars' are obtained.
		 * @param $attr If supplied then the URL will be quoted and prefixed with
		 *				'$attr='.
		 */
		function printActionUrl(&$action, &$fields, $attr = null) {
			$url = value($action['url'], $fields);

			if ($url === false) return '';

			if (!empty($action['urlvars'])) {
				$urlvars = value($action['urlvars'], $fields);
			} else {
				$urlvars = array();
			}

			if (isset($urlvars['subject'])) {
				$subject = value($urlvars['subject'], $fields);
				if (isset($_REQUEST['server']) && $subject != 'root') {
					$urlvars['server'] = $_REQUEST['server'];
					if (isset($_REQUEST['database']) && $subject != 'server') {
						$urlvars['database'] = $_REQUEST['database'];
						if (isset($_REQUEST['schema']) && $subject != 'database') {
							$urlvars['schema'] = $_REQUEST['schema'];
						}
					}
				}
			}

			$sep = '?';
			foreach ($urlvars as $var => $varfield) {
				$url .= $sep . value_url($var, $fields) . '=' . value_url($varfield, $fields);
				$sep = '&';
			}

			$url = htmlentities($url, ENT_QUOTES, 'UTF-8');

			if ($attr !== null && $url != '')
				return ' '.$attr.'="'.$url.'"';
			else
				return $url;
		}

		function getRequestVars($subject = '') {
			$v = array();
			if (!empty($subject))
				$v['subject'] = $subject;
			if (isset($_REQUEST['server']) && $subject != 'root') {
				$v['server'] = $_REQUEST['server'];
				if (isset($_REQUEST['database']) && $subject != 'server') {
					$v['database'] = $_REQUEST['database'];
					if (isset($_REQUEST['schema']) && $subject != 'database') {
						$v['schema'] = $_REQUEST['schema'];
					}
				}
			}
			return $v;
		}

		function printUrlVars(&$vars, &$fields) {
			foreach ($vars as $var => $varfield) {
				echo "{$var}=", urlencode($fields[$varfield]), "&amp;";
			}
		}

		/**
		 * Display a table of data.
		 * @param $tabledata A set of data to be formatted, as returned by $data->getDatabases() etc.
		 * @param $columns   An associative array of columns to be displayed:
		 *			$columns = array(
		 *				column_id => array(
		 *					'title' => Column heading,
		 *					'field' => Field name for $tabledata->fields[...],
		 *					'help'  => Help page for this column,
		 *				), ...
		 *			);
		 * @param $actions   Actions that can be performed on each object:
		 *			$actions = array(
		 *				* multi action support
		 *				* parameters are serialized for each entries and given in $_REQUEST['ma']
		 *				'multiactions' => array(
		 *					'keycols' => Associative array of (URL variable => field name), // fields included in the form
		 *					'url' => URL submission,
		 *					'default' => Default selected action in the form.
		 *									if null, an empty action is added & selected
		 *				),
		 *				* actions *
		 *				action_id => array(
		 *					'title' => Action heading,
		 *					'url'   => Static part of URL.  Often we rely
		 *							   relative urls, usually the page itself (not '' !), or just a query string,
		 *					'vars'  => Associative array of (URL variable => field name),
		 *					'multiaction' => Name of the action to execute.
		 *										Add this action to the multi action form
		 *				), ...
		 *			);
		 * @param $nodata    (optional) Message to display if data set is empty.
		 * @param $pre_fn    (optional) Name of a function to call for each row,
		 *					 it will be passed two params: $rowdata and $actions,
		 *					 it may be used to derive new fields or modify actions.
		 *					 It can return an array of actions specific to the row,
		 *					 or if nothing is returned then the standard actions are used.
		 *					 (see tblproperties.php and constraints.php for examples)
		 *					 The function must not must not store urls because
		 *					 they are relative and won't work out of context.
		 */
		function printTable(&$tabledata, &$columns, &$actions, $nodata = null, $pre_fn = null) {
			global $data, $conf, $misc, $lang;

			if ($has_ma = isset($actions['multiactions']))
				$ma = $actions['multiactions'];
			unset($actions['multiactions']);

			if ($tabledata->recordCount() > 0) {

				// Remove the 'comment' column if they have been disabled
				if (!$conf['show_comments']) {
					unset($columns['comment']);
				}

				if (isset($columns['comment'])) {
					// Uncomment this for clipped comments.
					// TODO: This should be a user option.
					//$columns['comment']['params']['clip'] = true;
				}

				if ($has_ma) {
					echo "<script src=\"multiactionform.js\" type=\"text/javascript\"></script>\n";
					echo "<form id=\"multi_form\" action=\"{$ma['url']}\" method=\"post\" enctype=\"multipart/form-data\">\n";
					if (isset($ma['vars']))
						foreach ($ma['vars'] as $k => $v)
							echo "<input type=\"hidden\" name=\"$k\" value=\"$v\" />";
				}

				echo "<table>\n";
				echo "<tr>\n";
				// Display column headings
				if ($has_ma) echo "<th></th>";
				foreach ($columns as $column_id => $column) {
					switch ($column_id) {
						case 'actions':
							if (sizeof($actions) > 0) echo "<th class=\"data\" colspan=\"", count($actions), "\">{$column['title']}</th>\n";
							break;
						default:
							echo "<th class=\"data\">";
							if (isset($column['help']))
								$this->printHelp($column['title'], $column['help']);
							else
								echo $column['title'];
							echo "</th>\n";
							break;
					}
				}
				echo "</tr>\n";

				// Display table rows
				$i = 0;
				while (!$tabledata->EOF) {
					$id = ($i % 2) + 1;

					unset($alt_actions);
					if (!is_null($pre_fn)) $alt_actions = $pre_fn($tabledata, $actions);
					if (!isset($alt_actions)) $alt_actions =& $actions;

					echo "<tr class=\"data{$id}\">\n";
					if ($has_ma) {
						foreach ($ma['keycols'] as $k => $v)
							$a[$k] = $tabledata->fields[$v];
						echo "<td>";
						echo "<input type=\"checkbox\" name=\"ma[]\" value=\"". htmlentities(serialize($a), ENT_COMPAT, 'UTF-8') ."\" />";
						echo "</td>\n";
					}

					foreach ($columns as $column_id => $column) {

						// Apply default values for missing parameters
						if (isset($column['url']) && !isset($column['vars'])) $column['vars'] = array();

						switch ($column_id) {
							case 'actions':
								foreach ($alt_actions as $action) {
									if (isset($action['disable']) && $action['disable'] === true) {
										echo "<td></td>\n";
									} else {
										echo "<td class=\"opbutton{$id}\">";
										echo "<a href=\"{$action['url']}";
										if ($action['url'] === '') echo '?';
										$misc->printUrlVars($action['vars'], $tabledata->fields);
										if (isset($action['target']))
											echo "\" target=\"{$action['target']}";
										echo "\">{$action['title']}</a></td>\n";
									}
								}
								break;
							default:
								echo "<td>";
								$val = value($column['field'], $tabledata->fields);
								if (!is_null($val)) {
									if (isset($column['url'])) {
										echo "<a href=\"{$column['url']}";
										$misc->printUrlVars($column['vars'], $tabledata->fields);
										echo "\">";
									}
									$type = isset($column['type']) ? $column['type'] : null;
									$params = isset($column['params']) ? $column['params'] : array();
									echo $misc->printVal($val, $type, $params);
									if (isset($column['url'])) echo "</a>";
								}

								echo "</td>\n";
								break;
						}
					}
					echo "</tr>\n";

					$tabledata->moveNext();
					$i++;
				}
				echo "</table>\n";

				// Multi action table footer w/ options & [un]check'em all
				if ($has_ma) {
					// if default is not set or doesn't exist, set it to null
					if (!isset($ma['default']) || !isset($actions[$ma['default']]))
						$ma['default'] = null;
					echo "<br />\n";
					echo "<table>\n";
					echo "<tr>\n";
					echo "<th class=\"data\" style=\"text-align: left\" colspan=\"3\">{$lang['stractionsonmultiplelines']}</th>\n";
					echo "</tr>\n";
					echo "<tr class=\"row1\">\n";
					echo "<td>";
					echo "<a href=\"#\" onclick=\"javascript:checkAll(true);\">{$lang['strselectall']}</a> / ";
					echo "<a href=\"#\" onclick=\"javascript:checkAll(false);\">{$lang['strunselectall']}</a></td>\n";
					echo "<td>&nbsp;--->&nbsp;</td>\n";
					echo "<td>\n";
					echo "\t<select name=\"action\">\n";
					if ($ma['default'] == null)
						echo "\t\t<option value=\"\">--</option>\n";
					foreach($actions as $k => $a)
						if (isset($a['multiaction']))
							echo "\t\t<option value=\"{$a['multiaction']}\"", ($ma['default']  == $k? ' selected="selected"': ''), ">{$a['title']}</option>\n";
					echo "\t</select>\n";
					echo "<input type=\"submit\" value=\"{$lang['strexecute']}\" />\n";
					echo $misc->form;
					echo "</td>\n";
					echo "</tr>\n";
					echo "</table>\n";
					echo '</form>';
				};

				return true;
			} else {
				if (!is_null($nodata)) {
					echo "<p>{$nodata}</p>\n";
				}
				return false;
			}
		}

		/** Produce XML data for the browser tree
		 * @param $treedata A set of records to populate the tree.
		 * @param $attrs Attributes for tree items
		 *        'text' - the text for the tree node
		 *        'icon' - an icon for node
		 *        'openIcon' - an alternative icon when the node is expanded
		 *        'toolTip' - tool tip text for the node
		 *        'action' - URL to visit when single clicking the node
		 *        'iconAction' - URL to visit when single clicking the icon node
		 *        'branch' - URL for child nodes (tree XML)
		 *        'expand' - the action to return XML for the subtree
		 *        'nodata' - message to display when node has no children
		 *        'nohead' - suppress headers and opening <tree> tag
		 *        'nofoot' - suppress closing </tree> tag
		 */
		function printTreeXML(&$treedata, &$attrs) {
			global $conf, $lang;

			if (!isset($attrs['nohead']) || $attrs['nohead'] === false) {
				header("Content-Type: text/xml; charset=UTF-8");
				header("Cache-Control: no-cache");

				echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

				echo "<tree>\n";
			}

			if ($treedata->recordCount() > 0) {
				while (!$treedata->EOF) {
					$rec =& $treedata->fields;

					echo "<tree";
					echo value_xml_attr('text', $attrs['text'], $rec);
					echo value_xml_attr('action', $attrs['action'], $rec);
					echo value_xml_attr('src', $attrs['branch'], $rec);

					$icon = $this->icon(value($attrs['icon'], $rec));
					echo value_xml_attr('icon', $icon, $rec);
					echo value_xml_attr('iconaction', $attrs['iconAction'], $rec);

					if (!empty($attrs['openicon'])) {
						$icon = $this->icon(value($attrs['openIcon'], $rec));
					}
					echo value_xml_attr('openicon', $icon, $rec);

					echo value_xml_attr('tooltip', $attrs['toolTip'], $rec);

					echo " />\n";

					$treedata->moveNext();
				}
			} else {
				$msg = isset($attrs['nodata']) ? $attrs['nodata'] : $lang['strnoobjects'];
				echo "<tree text=\"{$msg}\" onaction=\"tree.getSelected().getParent().reload()\" icon=\"", $this->icon('ObjectNotFound'), "\" />\n";
			}

			if (!isset($attrs['nofoot']) || $attrs['nofoot'] === false) {
				echo "</tree>\n";
			}
		}

		function adjustTabsForTree(&$tabs) {
			include_once('./classes/ArrayRecordSet.php');

			foreach ($tabs as $i => $tab) {
				if ((isset($tab['hide']) && $tab['hide'] === true) || (isset($tab['tree']) && $tab['tree'] === false)) {
					unset($tabs[$i]);
				}
			}
			return new ArrayRecordSet($tabs);
		}

		function icon($icon) {
			global $conf;
			$path = "images/themes/{$conf['theme']}/{$icon}";
			if (file_exists($path.'.png')) return $path.'.png';
			if (file_exists($path.'.gif')) return $path.'.gif';
			$path = "images/themes/default/{$icon}";
			if (file_exists($path.'.png')) return $path.'.png';
			if (file_exists($path.'.gif')) return $path.'.gif';
			return '';
		}

		/**
		 * Function to escape command line parameters
		 * @param $str The string to escape
		 * @return The escaped string
		 */
		function escapeShellArg($str) {
			global $data, $lang;

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				// Due to annoying PHP bugs, shell arguments cannot be escaped
				// (command simply fails), so we cannot allow complex objects
				// to be dumped.
				if (preg_match('/^[_.[:alnum:]]+$/', $str))
					return $str;
				else {
					echo $lang['strcannotdumponwindows'];
					exit;
				}
			}
			else
				return escapeshellarg($str);
		}

		/**
		 * Function to escape command line programs
		 * @param $str The string to escape
		 * @return The escaped string
		 */
		function escapeShellCmd($str) {
			global $data;

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$data->fieldClean($str);
				return '"' . $str . '"';
			}
			else
				return escapeshellcmd($str);
		}

		/**
		 * Get list of servers' groups if existing in the conf
		 * @return a recordset of servers' groups
		 */
		function getServersGroups() {
			global $conf, $lang;
			$grps = array();
			
			foreach ($conf['srv_groups'] as $i => $group) {
				$grps[$i] = array(
					'id' => $i,
					'desc' => $group['desc'],
				);				
			}
			
			$grps['all'] = array(
				'id' => 'all', 
				'desc' => $lang['strallservers'],
			);

			include_once('./classes/ArrayRecordSet.php');
			return new ArrayRecordSet($grps);
		}
		

		/**
		 * Get list of servers
		 * @param $recordset return as RecordSet suitable for printTable if true,
		 *                   otherwise just return an array.
		 * @param $group a group name to filter the returned servers using $conf[srv_groups]
		 */
		function getServers($recordset = false, $group = false) {
			global $conf;

			$logins = isset($_SESSION['webdbLogin']) && is_array($_SESSION['webdbLogin']) ? $_SESSION['webdbLogin'] : array();
			$srvs = array();

			if (($group !== false) and ($group !== 'all'))
				$group = array_fill_keys(explode(',', $conf['srv_groups'][$group]['servers']), 1);
			
			foreach($conf['servers'] as $idx => $info) {
				$server_id = $info['host'].':'.$info['port'].':'.$info['sslmode'];
				if (($group === false) 
					or (isset($group[$idx]))
					or ($group === 'all')
				) {
					$server_id = $info['host'].':'.$info['port'].':'.$info['sslmode'];
					
					if (isset($logins[$server_id])) $srvs[$server_id] = $logins[$server_id];
					else $srvs[$server_id] = $info;

					$srvs[$server_id]['id'] = $server_id;
				}
			}

			function _cmp_desc($a, $b) {
				return strcmp($a['desc'], $b['desc']);
			}
			uasort($srvs, '_cmp_desc');

			if ($recordset) {
				include_once('./classes/ArrayRecordSet.php');
				return new ArrayRecordSet($srvs);
			}
			return $srvs;
		}

		/**
		 * Validate and retrieve information on a server.
		 * If the parameter isn't supplied then the currently
		 * connected server is returned.
		 * @param $server_id A server identifier (host:port)
		 * @return An associative array of server properties
		 */
		function getServerInfo($server_id = null) {
			global $conf, $_reload_browser, $lang;

			if ($server_id === null && isset($_REQUEST['server']))
				$server_id = $_REQUEST['server'];

			// Check for the server in the logged-in list
			if (isset($_SESSION['webdbLogin'][$server_id]))
				return $_SESSION['webdbLogin'][$server_id];

			// Otherwise, look for it in the conf file
			foreach($conf['servers'] as $idx => $info) {
				if ($server_id == $info['host'].':'.$info['port'].':'.$info['sslmode']) {
					// Automatically use shared credentials if available
					if (!isset($info['username']) && isset($_SESSION['sharedUsername'])) {
						$info['username'] = $_SESSION['sharedUsername'];
						$info['password'] = $_SESSION['sharedPassword'];
						$_reload_browser = true;
						$this->setServerInfo(null, $info, $server_id);
					}

					return $info;
				}
			}

			if ($server_id === null){
				return null;
			} else {
				// Unable to find a matching server, are we being hacked?
				echo $lang['strinvalidserverparam'];
				exit;
			}
		}

		/**
		 * Set server information.
		 * @param $key parameter name to set, or null to replace all
		 *             params with the assoc-array in $value.
		 * @param $value the new value, or null to unset the parameter
		 * @param $server_id the server identifier, or null for current
		 *                   server.
		 */
		function setServerInfo($key, $value, $server_id = null)
		{
			if ($server_id === null && isset($_REQUEST['server']))
				$server_id = $_REQUEST['server'];

			if ($key === null) {
				if ($value === null)
					unset($_SESSION['webdbLogin'][$server_id]);
				else
					$_SESSION['webdbLogin'][$server_id] = $value;
			} else {
				if ($value === null)
					unset($_SESSION['webdbLogin'][$server_id][$key]);
				else
					$_SESSION['webdbLogin'][$server_id][$key] = $value;
			}
		}
		
		/**
		 * Set the current schema
		 * @param $schema The schema name
		 * @return 0 on success
		 * @return $data->seSchema() on error
		 */
		function setCurrentSchema($schema) {
			global $data;
			
			$status = $data->setSchema($schema);
			if($status != 0)
				return $status;

			$_REQUEST['schema'] = $schema;
			$this->setHREF();
			return 0;
		}

		/**
		 * Save the given SQL script in the history 
		 * of the database and server.
		 * @param $script the SQL script to save.
		 */
		function saveScriptHistory($script) {
			list($usec, $sec) = explode(' ', microtime());
			$time = ((float)$usec + (float)$sec);
			$_SESSION['history'][$_REQUEST['server']][$_REQUEST['database']]["$time"] = array(
				'query' => $script,
				'paginate' => (!isset($_REQUEST['paginate'])? 'f':'t'),
				'queryid' => $time,
			);
		}
	
		/*
		 * Output dropdown list to select server and 
		 * databases form the popups windows.
		 * @param $onchange Javascript action to take when selections change.
		 */	
		function printConnection($onchange) {
			global $data, $lang, $misc;

			echo "<table style=\"width: 100%\"><tr><td>\n";
			echo "<label>";
			$misc->printHelp($lang['strserver'], 'pg.server');
			echo "</label>";
			echo ": <select name=\"server\" {$onchange}>\n";
			
			$servers = $misc->getServers();
			foreach($servers as $info) {
				if (empty($info['username'])) continue; // not logged on this server
				echo "<option value=\"", htmlspecialchars($info['id']), "\"",
					((isset($_REQUEST['server']) && $info['id'] == $_REQUEST['server'])) ? ' selected="selected"' : '', ">",
					htmlspecialchars("{$info['desc']} ({$info['id']})"), "</option>\n";
			}
			echo "</select>\n</td><td style=\"text-align: right\">\n";
			
			// Get the list of all databases
			$databases = $data->getDatabases();

			if ($databases->recordCount() > 0) {

				echo "<label>";
				$misc->printHelp($lang['strdatabase'], 'pg.database');
				echo ": <select name=\"database\" {$onchange}>\n";
				
				//if no database was selected, user should select one
				if (!isset($_REQUEST['database']))
					echo "<option value=\"\">--</option>\n";
				
				while (!$databases->EOF) {
					$dbname = $databases->fields['datname'];
					echo "<option value=\"", htmlspecialchars($dbname), "\"",
						((isset($_REQUEST['database']) && $dbname == $_REQUEST['database'])) ? ' selected="selected"' : '', ">",
						htmlspecialchars($dbname), "</option>\n";
					$databases->moveNext();
				}
				echo "</select></label>\n";
			}
			else {
				$server_info = $misc->getServerInfo();
				echo "<input type=\"hidden\" name=\"database\" value=\"", 
					htmlspecialchars($server_info['defaultdb']), "\" />\n";
			}
			
			echo "</td></tr></table>\n";
		}

		/**
		 * returns an array representing FKs definition for a table, sorted by fields
		 * or by constraint.
		 * @param $table The table to retrieve FK contraints from
		 * @returns the array of FK definition:
		 *   array(
		 *     'byconstr' => array(
		 *       constrain id => array(
		 *         confrelid => foreign relation oid
		 *         f_schema => foreign schema name
		 *         f_table => foreign table name
		 *         pattnums => array of parent's fields nums
		 *         pattnames => array of parent's fields names
		 *         fattnames => array of foreign attributes names
		 *       )
		 *     ),
		 *     'byfield' => array(
		 *       attribute num => array (constraint id, ...)
		 *     ),
		 *     'code' => HTML/js code to include in the page for auto-completion
		 *   )
		 **/
		function getAutocompleteFKProperties($table) {
			global $data;

			$fksprops = array(
				'byconstr' => array(),
				'byfield' => array(),
				'code' => ''
			);

			$constrs = $data->getConstraintsWithFields($table);

			if (!$constrs->EOF) {
				$conrelid = $constrs->fields['conrelid'];
				while(!$constrs->EOF) {
					if ($constrs->fields['contype'] == 'f') {
						if (!isset($fksprops['byconstr'][$constrs->fields['conid']])) {
							$fksprops['byconstr'][$constrs->fields['conid']] = array (
								'confrelid' => $constrs->fields['confrelid'],
								'f_table' => $constrs->fields['f_table'],
								'f_schema' => $constrs->fields['f_schema'],
								'pattnums' => array(),
								'pattnames' => array(),
								'fattnames' => array()
							);
						}

						$fksprops['byconstr'][$constrs->fields['conid']]['pattnums'][] = $constrs->fields['p_attnum'];
						$fksprops['byconstr'][$constrs->fields['conid']]['pattnames'][] = $constrs->fields['p_field'];
						$fksprops['byconstr'][$constrs->fields['conid']]['fattnames'][] = $constrs->fields['f_field'];

						if (!isset($fksprops['byfield'][$constrs->fields['p_attnum']]))
							$fksprops['byfield'][$constrs->fields['p_attnum']] = array();
						$fksprops['byfield'][$constrs->fields['p_attnum']][] = $constrs->fields['conid'];
					}
					$constrs->moveNext();
				}

				$fksprops['code'] = "<script type=\"text/javascript\">\n";
				$fksprops['code'] .= "var constrs = {};\n";
				foreach ($fksprops['byconstr'] as $conid => $props) {
					$fksprops['code'] .= "constrs.constr_{$conid} = {\n";
					$fksprops['code'] .= 'pattnums: ['. implode(',',$props['pattnums']) ."],\n";
					$fksprops['code'] .= "f_table:'". addslashes(htmlentities($props['f_table'], ENT_QUOTES, 'UTF-8')) ."',\n";
					$fksprops['code'] .= "f_schema:'". addslashes(htmlentities($props['f_schema'], ENT_QUOTES, 'UTF-8')) ."',\n";
					$_ = '';
					foreach ($props['pattnames'] as $n) {
						$_ .= ",'". htmlentities($n, ENT_QUOTES, 'UTF-8') ."'";
					}
					$fksprops['code'] .= 'pattnames: ['. substr($_, 1) ."],\n";

					$_ = '';
					foreach ($props['fattnames'] as $n) {
						$_ .= ",'". htmlentities($n, ENT_QUOTES, 'UTF-8') ."'";
					}

					$fksprops['code'] .= 'fattnames: ['. substr($_, 1) ."]\n";
					$fksprops['code'] .= "};\n";
				}

				$fksprops['code'] .= "var attrs = {};\n";
				foreach ($fksprops['byfield'] as $attnum => $cstrs ) {
					$fksprops['code'] .= "attrs.attr_{$attnum} = [". implode(',', $fksprops['byfield'][$attnum]) ."];\n";
				}

				$fksprops['code'] .= "var table='". addslashes(htmlentities($table, ENT_QUOTES, 'UTF-8')) ."';";
				$fksprops['code'] .= "var server='". htmlentities($_REQUEST['server'], ENT_QUOTES, 'UTF-8') ."';";
				$fksprops['code'] .= "var database='". addslashes(htmlentities($_REQUEST['database'], ENT_QUOTES, 'UTF-8')) ."';";
				$fksprops['code'] .= "</script>\n";

				$fksprops['code'] .= '<div id="fkbg"></div>';
				$fksprops['code'] .= '<div id="fklist"></div>';
				$fksprops['code'] .= '<script src="libraries/js/jquery.js" type="text/javascript"></script>';
				$fksprops['code'] .= '<script src="js/ac_insert_row.js" type="text/javascript"></script>';
			}

			else /* we have no foreign keys on this table */
				return false;

			return $fksprops;
		}
	}
?>
