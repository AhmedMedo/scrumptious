<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Verifier;

use App\Libraries\Services\AppCheck\AppCheckConfiguration;
use App\Libraries\Services\AppCheck\Decoder\AppCheckTokenDecoder;
use App\Libraries\Services\AppCheck\Exception\AppCheckTokenException;
use App\Libraries\Services\AppCheck\Http\AppCheckClientRouter;
use App\Libraries\Services\AppCheck\Model\Factory\AppCheckTokenFactory;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Support\Facades\Log;

class AppCheckTokenVerifier
{
    public function __construct(
        private readonly AppCheckTokenDecoder $decoder,
        private readonly AppCheckTokenFactory $tokenFactory,
        private readonly AppCheckClientRouter $router,
        private readonly AppCheckConfiguration $configuration
    ) {
    }

    public function verify(string $signature): bool
    {
        $token = $this->tokenFactory->fromDecoded(
            $this->decoder->decode($signature)
        );

        // in case of empty token, we just log a warning and return true
        // maybe Google API is down
        if ($token === null) {
            Bugsnag::notifyError('AppCheck token is empty.', 'Appcheck token cannot be resolved.');
            return true;
        }

        if (! $token->hasIssuer($this->router->getProjectUrl())) {
            throw AppCheckTokenException::invalid('Wrong issuer.');
        }

        if (! $token->isDestinedForProject($this->configuration->projectId())) {
            throw AppCheckTokenException::invalid('Recipient outside the audience.');
        }

        return true;
    }
}
