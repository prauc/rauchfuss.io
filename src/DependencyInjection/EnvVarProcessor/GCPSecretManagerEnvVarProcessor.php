<?php

namespace App\DependencyInjection\EnvVarProcessor;

use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class GCPSecretManagerEnvVarProcessor implements EnvVarProcessorInterface
{
    private $gcpProjectId;
    private $gcpCredentialsFile;

    function __construct($gcpProjectId, $gcpCredentialsFile)
    {
        $this->gcpProjectId = $gcpProjectId;
        $this->gcpCredentialsFile = $gcpCredentialsFile;
    }

    /**
     * Returns the value of the given variable as managed by the current instance.
     *
     * @param string $prefix The namespace of the variable
     * @param string $name The name of the variable within the namespace
     * @param \Closure $getEnv A closure that allows fetching more env vars
     *
     * @return mixed
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException on error
     * @throws \Google\ApiCore\ValidationException
     */
    public function getEnv(string $prefix, string $name, \Closure $getEnv)
    {
        $secretManagerServiceClient = new SecretManagerServiceClient([
            'credentials' => $this->gcpCredentialsFile
        ]);

        try {
            $formattedName = $secretManagerServiceClient->secretVersionName($this->gcpProjectId, $name, 'latest');
            $response = $secretManagerServiceClient->accessSecretVersion($formattedName);
        } finally {
            $secretManagerServiceClient->close();
        }

        return $response->getPayload()->getData();
    }

    /**
     * @return string[] The PHP-types managed by getEnv(), keyed by prefixes
     */
    public static function getProvidedTypes()
    {
        return [
            'gcpsecretmanager' => 'string',
        ];
    }
}