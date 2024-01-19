<?php

namespace App\Models\Traits;

use Aws\Exception\AwsException;
use Exception;
use Illuminate\Support\Facades\Log;

trait TennantAdvanceFeatures
{
    public $advancedFeatures;

    /**
     * Add custom columns to fillable columns
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function convertToCollection($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array->$key = self::convertToCollection((object) $value);
            }
        }

        return $array;
    }

    /**
     * Init the features. Create a collection with the features. All items are converted to object
     */
    public function initFeatures()
    {
        if ($this->features) {
            $this->advancedFeatures = self::convertToCollection(collect($this->features));
        }

        return $this;
    }

    /**
     * Get a feature from the collection
     * @description - This is an amazing function to call any propertie from the collection
     * @param array $args - The name of the feature. If the feature is a collection, you can pass more than one argument
     * @return mixed
     */
    public function getFeature(...$args)
    {
        $arr = $this->advancedFeatures;
        foreach ($args as $arg) {
            if (is_object($arr)) {
                if (! \property_exists($arr, $arg)) {
                    return null;
                    /** we can return null to not fire an error in front end , if need */
                    // throw new Exception('Propertie ' . $arg . ' dont exists in this collection', 500);
                }
                $arr = $arr->$arg;
            } else {
                return null;
            }
        }

        return $arr;
    }

    /**
     * Create dynamic bucket on aws for individual tenant
     */
    public function createS3Bucket()
    {

        $awsConfig = config('filesystems.disks.attachments');
        $config = [
            'version' => 'latest',
            'region' => $awsConfig['region'],
            'endpoint' => $awsConfig['endpoint'],
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $awsConfig['key'],
                'secret' => $awsConfig['secret'],
            ],
        ];

        $s3Client = new \Aws\S3\S3Client($config);
        try {
            return $s3Client->createBucket(
                [
                    'Bucket' => $this->bucketName,
                    'CreateBucketConfiguration' => ['LocationConstraint' => $awsConfig['region'] . '-smart'],
                ]
            );
        } catch (AwsException $e) {
            if ($e->getAwsErrorCode() != 'BucketAlreadyExists') {
                Log::error($e);
            }
        }
    }
}
