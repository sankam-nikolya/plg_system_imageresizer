<?php
/**
 * @package  plg_system_imageresizer
 *
 * @copyright   Copyright (C) 2011 - 2016 SNAKAM, Inc. All rights reserved.
 * @license  GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

require_once __DIR__ . '/lib/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

class plgSystemImageresizer extends JPlugin
{
	public $extensions = array(
			'jpg',
			'jpeg',
			'png',
			'gif'
		);

    public function resizeImage($image, $width, $height = null) {
        $file = JPATH_ROOT . '/' . $image;

        if (!JFile::exists($file)) {
            return $image;
        }

        $image_ext = mb_strtolower(JFile::getExt($file));

        if(!in_array($image_ext, $this->extensions)) {
        	return $image;
        }

        $image_name = md5($image) . '.' . $image_ext;

        $thumbs_main_folder = JPATH_ROOT . '/cache/images/';
        $thumb_folder = $thumbs_main_folder . $width . 'x' . $height . '/';
        $thumb = $thumb_folder . $image_name;

        if (!JFile::exists($thumb)) {
            try {
                if (!JFolder::exists($thumbs_main_folder)) {
                    JFolder::create($thumbs_main_folder);
                    JFile::write($thumbs_main_folder . '/index.html', '');
                }

                if (!JFolder::exists($thumb_folder)) {
                    JFolder::create($thumb_folder);
                    JFile::write($thumb_folder . '/index.html', '');
                }

                $properties = JImage::getImageFileProperties($file);

                $imgObject = Image::make($file);
                if(empty($height)) {
                    if ($properties->width != $width) {
                        $imgObject->resize($width, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $imgObject->save($thumb);
                    } else {
                        return $image;
                    }
                } elseif (empty($width)) {
                    if ($properties->height != $height) {
                        $imgObject->resize(null, $height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $imgObject->save($thumb);
                    } else {
                        return $image;
                    }
                } else {
                    if ($properties->width != $width && $properties->height != $height) {
                        $imgObject->fit($width, $height, function ($constraint) {
                            $constraint->upsize();
                        });
                        $imgObject->save($thumb);
                    } else {
                        return $image;
                    }
                }
            } catch (Exception $e) {
                return $image;
            }
        }

        if (JFile::exists($thumb)) {

            JPluginHelper::importPlugin('system');
            $dispatcher = JEventDispatcher::getInstance();
            
            try {
                $dispatcher->trigger('compressImage', array('file' => $thumb));
            } catch (Exception $e) {
                // error;
            }

            return str_replace(JPATH_ROOT . '/', '', $thumb);
        } else {
            return $image;
        }
    }

}
