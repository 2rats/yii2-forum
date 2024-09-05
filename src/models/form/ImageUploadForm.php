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

    private $UPLOAD_SUBDIR;

    private const IMAGE_RESOLUTION = 1024;

    private const IMAGE_JPG_QUALITY = 60;

    public function __construct($subdir)
    {
        parent::__construct();
        $this->UPLOAD_SUBDIR = $subdir . DIRECTORY_SEPARATOR;
    }

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, webp'],
        ];
    }

    private function getFilePathPrefix(): string
    {
        return date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR;
    }

    private function getFilePath(): string
    {
        return Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . File::UPLOAD_PATH . $this->UPLOAD_SUBDIR;
    }

    private function uploadConvertedImage(): File
    {
        $filename = $this->getUniqueFilename();
        $imagine = Image::resize($this->file->tempName, self::IMAGE_RESOLUTION, self::IMAGE_RESOLUTION);
        $path = $this->getFilePath() . $filename;
        if (!file_exists($this->getFilePath() . $this->getFilePathPrefix())) {
            mkdir($this->getFilePath() . $this->getFilePathPrefix(), 0777, true);
        }
        $imagine->save($path, ['jpeg_quality' => self::IMAGE_JPG_QUALITY]);

        $fileModel = new File([
            'filename' => $this->UPLOAD_SUBDIR . $filename,
        ]);
        if ($fileModel->save()) {
            return $fileModel;
        }
        unlink($path);
        throw new \Exception('Failed to save file model ' . print_r($fileModel->getErrors(), true));
    }

    private function getUniqueFilename(): string
    {
        $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.jpg';
        $path = $this->getFilePath();
        while (file_exists($path . $filename)) {
            $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.jpg';
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
