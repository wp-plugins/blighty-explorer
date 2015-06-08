<?php
/**

Copyright (C) 2015 Chris Murfin

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**/

function bex_folder( $atts ) {
	global $dropbox;
			
	$rootFolder = trailingslashit(get_option('bex_folder'));
		
	$mapIcons = array(
		"page_white_sound" => "music",
		"page_white_film" => "film"
	);
	
	if (!empty($_GET["folder"])) {
		$folder = esc_attr($_GET["folder"]);
		$folder = ltrim($folder, ".");
	} else {
		$folder = "";
	}
	
	$workingFolder = trailingslashit($rootFolder .$folder);
	
	$folder = trailingslashit($folder);

	if (!empty($_GET["file"])) {
		$file = esc_attr($_GET["file"]);
	} else {
		$file = null;
	} 
	
	$access_token = bex_load_token("access");
	if(empty($access_token)) {
		$out = 'Dropbox not authorized. Check the settings for Blighty Explorer (bex_folder - 1)';
		return $out;
	}

	$dropbox->SetAccessToken($access_token);
	if (!$dropbox->IsAuthorized()) {
		$out = 'Dropbox not authorized. Check the settings for Blighty Explorer (bex_folder - 2)';
		return $out;
	}
	
	$out .= '<pre class="bex-wrapper">';
	if (!is_null($file)) {
		$url = $dropbox->GetLink($rootFolder .$file,false,false);
		echo '<script language="javascript">window.open("'.$url.'");</script>';
	}
	$files = $dropbox->GetFiles($workingFolder);
	
	uasort($files,"bex_sort_compare");
	
	global $wp;
	if (!empty($wp->query_string)) {
		$thisQS = '?' .$wp->query_string .'&';
	} else {
		$thisQS = '?';
	}
	
	$pluginPath = plugin_dir_path( __FILE__ );

	$out .= '<img class="bex-img" src="' .plugins_url( 'icons/folder_explore.png', __FILE__ ) .'" /> ';	
	$out .= '<a href="' .$thisQS .'folder=/">Home</a><br />';
	if (substr($folder, 0, strlen($rootFolder)) == $rootFolder) {
		$folder = substr($folder, strlen($rootFolder));
	} 
	if (strlen($folder) > 1) {
		$splits = explode("/",untrailingslashit($folder));
		$size = count($splits);
		$j = 1;
		for ($i = 0; $i < $size; $i++) {
			$slashpos = strpos($folder,"/",$j);
			$j = $slashpos + 1;
			$out .= str_repeat("&nbsp;",$i * 2 + 2) ." &raquo; ";
			$out .= '<a href="' .$thisQS .'folder=' .substr($folder,0,$slashpos) .'">' .$splits[$i] .'</a><br />';
		}				
	}
	$out .= '<br />';
	$out .= '<div class="bex-table">';
	
	$i = 1;
	foreach ($files as $file) {		
	
		$filePath = $file->path;
		$filePathWorking = $filePath;
		$len = strlen($rootFolder);
		if (strcasecmp(substr($filePath,0,$len),$rootFolder) == 0) {
			$filePath = urlencode(substr($filePath,$len));
		}
		$len = strlen($workingFolder);
		if (strcasecmp(substr($filePathWorking,0,$len),$workingFolder) == 0) {
			$filePathWorking = substr($filePathWorking,$len);
		}

		if ($file->is_dir && $filePathWorking == UPLOADS_FOLDER) {
		// Do nothing, i.e. suppress displaying the UPLOADS_FOLDER...
		} else {
			$i = 1 - $i;
		
			$out .= '<div class="bex-row-' .$i .'">';
			if ($file->is_dir) {
				$out .= '<div class="bex-cell"><img class="bex-img" src="' .plugins_url( 'icons/folder.png', __FILE__ ) .'" />&nbsp;';
				$out .= '<a href="' .$thisQS .'folder=' .$filePath .'">' .$filePathWorking ."</a></div>";
				if (get_option('bex_show_moddate')) {
					$out .= '<div class="bex-cell-r">&nbsp;</div>';
				}
				if (get_option('bex_show_size')) {
					$out .= '<div class="bex-cell-r">&nbsp;</div>';
				}
			} else {
				$icon = $file->icon;
				if (!empty($mapIcons[$icon])) {
					$icon = $mapIcons[$icon];
				}
				$out .= '<div class="bex-cell"><img class="bex-img" src="' .plugins_url( 'icons/'. $icon .'.png', __FILE__ ) .'" />&nbsp;';
				$out .= '<a href="' .$thisQS .'folder=' .$folder . '&file=' .$filePath .'">' .$filePathWorking ."</a></div>";
				if (get_option('bex_show_moddate')) {
					$out .= '<div class="bex-cell-r">' .substr($file->modified,5,17) . '</div>';
				}
				if (get_option('bex_show_size')) {
					$out .= '<div class="bex-cell-r">' .$file->size .'</div>';
				}
			}
			$out .= '</div>';
		}
	}			
	$out .= '</div>';
	$out .= '</pre>';
	
	return $out;
}

function bex_sort_compare($a, $b) {

	if ($a->is_dir == $b->is_dir) {
        return strcmp($a->path, $b->path);
    } else if ($a->is_dir) {
    	return -1;
    } else {
    	return 1;
    }

}

?>