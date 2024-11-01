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

    public const DIR_PATH_POST = 'post-images';

    public const DIR_PATH_PROFILE = 'profile-images';

    private const IMAGE_RESOLUTION = 1024;

    private const IMAGE_JPG_QUALITY = 80;

    /**
     * @var bool Whether to skip the validation if the file is empty.
     */
    public bool $skipOnEmpty = false;

    /**
     * @var bool Whether to use the active name for the file.
     */
    public bool $useActiveName = false;

    public function __construct($subdir)
    {
        parent::__construct();
        $this->UPLOAD_SUBDIR = $subdir . DIRECTORY_SEPARATOR;
    }

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => $this->skipOnEmpty, 'extensions' => 'png, jpg, jpeg, webp, gif', 'maxSize' => 1024 * 1024 * 5],
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
        if (!file_exists($this->getFilePath() . $this->getFilePathPrefix())) {
            mkdir($this->getFilePath() . $this->getFilePathPrefix(), 0777, true);
        }

        $filename = $this->getUniqueFilename($this->file->extension === 'gif' ? 'gif' : 'jpg');
        $path = $this->getFilePath() . $filename;

        if($this->file->extension === 'gif') {
            move_uploaded_file($this->file->tempName, $path);
        } else {
            $imagine = Image::resize($this->file->tempName, self::IMAGE_RESOLUTION, self::IMAGE_RESOLUTION);
            $imagine->save($path, ['jpeg_quality' => self::IMAGE_JPG_QUALITY]);
        }

        $fileModel = new File([
            'filename' => $this->UPLOAD_SUBDIR . $filename,
        ]);
        if ($fileModel->save()) {
            return $fileModel;
        }
        unlink($path);
        throw new \Exception('Failed to save file model ' . print_r($fileModel->getErrors(), true));
    }

    private function getUniqueFilename(string $extension = 'jpg'): string
    {
        $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.' . $extension;
        $path = $this->getFilePath();
        while (file_exists($path . $filename)) {
            $filename = $this->getFilePathPrefix() . md5(rand() . time()) . '.' . $extension;;
        }
        return $filename;
    }

    public function load($data, $formName = null)
    {
        if($this->useActiveName){
            $this->file = UploadedFile::getInstance($this, 'file');
        } else {
            $this->file = UploadedFile::getInstanceByName('file');
        }
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
