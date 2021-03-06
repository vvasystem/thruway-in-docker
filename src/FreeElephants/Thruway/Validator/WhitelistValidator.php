<?php

namespace FreeElephants\Thruway\Validator;

use FreeElephants\Thruway\Jwt\JwtDecoderAdapterInterface;
use FreeElephants\Thruway\Jwt\JwtValidatorInterface;
use FreeElephants\Thruway\KeyValueStorage\KeyValueStorageInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class WhitelistValidator implements JwtValidatorInterface
{
    /**
     * @var KeyValueStorageInterface
     */
    private $storage;
    /**
     * @var JwtDecoderAdapterInterface
     */
    private $jwtDecoderAdapter;

    public function __construct(KeyValueStorageInterface $storage, JwtDecoderAdapterInterface $jwtDecoderAdapter)
    {
        $this->storage = $storage;
        $this->jwtDecoderAdapter = $jwtDecoderAdapter;
    }

    public function isValid(string $signature): bool
    {
        $jwt = $this->jwtDecoderAdapter->decode($signature);
        if (isset($jwt->authid, $jwt->authroles) && is_array($jwt->authroles)) {
            return $this->storage->offsetExists($jwt->authid);
        }

        return false;
    }
}