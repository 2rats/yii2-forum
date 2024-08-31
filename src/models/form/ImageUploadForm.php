<?php

namespace rats\forum\models\form;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use rats\forum\models\File;
use Yii;

class ImageUploadForm extends Model
{
    public $file;

    private const UPLOAD_SUBDIR = 'post-images' . DIRECTORY_SEPARATOR;

    private const IMAGE_RESOLUTION = 1024;

    private const IMAGE_JPG_QUALITY = 60;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, webp'],
        ];
    }

    private function getFilePath(): string
    {
        return Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . File::UPLOAD_PATH . self::UPLOAD_SUBDIR;
    }

    private function uploadConvertedImage(): File
    {
        $filename = $this->getUniqueFilename();
        try {
            $imagine = Image::resize($this->file->tempName, self::IMAGE_RESOLUTION, self::IMAGE_RESOLUTION);
            $path = $this->getFilePath() . $filename;
            $imagine->save($path, ['jpeg_quality' => self::IMAGE_JPG_QUALITY]);
        } catch (\Exception $e) {
            return false;
        }

        $fileModel = new File([
            'filename' => self::UPLOAD_SUBDIR . $filename,
        ]);
        if ($fileModel->save()) {
            return $fileModel;
        }
        unlink($path);
        return false;
    }

    private function getUniqueFilename(): string
    {
        $filename = md5(rand() . time()) . '.jpg';
        $path = $this->getFilePath();
        while (file_exists($path . $filename)) {
            $filename = md5(rand() . time()) . '.jpg';
        }
        return $filename;
    }

    public function load($data, $formName = null)
    {
        $this->file = UploadedFile::getInstanceByName('file');
        return $this->file !== null;
    }

    /**
     * Uploads the image and returns the path to the image.
     *
     * @return bool|File
     */
    public function upload()
    {
        if ($this->validate()) {
            return $this->uploadConvertedImage();
        }
        return false;
    }
}
