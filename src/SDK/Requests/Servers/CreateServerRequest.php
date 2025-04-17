<?php

namespace IBroStudio\Upcloud\SDK\Requests\Servers;

use IBroStudio\Upcloud\Data\UpcloudCreateServerData;
use IBroStudio\Upcloud\Data\UpcloudServerData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateServerRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected UpcloudCreateServerData $serverData) {}

    public function resolveEndpoint(): string
    {
        return '/server/';
    }

    protected function defaultBody(): array
    {
        return ['server' => $this->serverData->toArray()];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return UpcloudServerData::from($response);
    }
}
/*
{
    "server": {
        "zone":"de-fra1",
        "title":"Test Server 1744616934",
        "hostname":"test1744616934.ibro.studio",
        "plan":"DEV-1xCPU-2GB",
        "storage_devices": {
            "storage_device": [
                {
                    "type":"template",
                    "size":5,
                    "title":"Ubuntu Server 24.04 LTS (Noble Numbat)",
                    "encrypted":"no",
                    "labels":[],
                    "action":"clone",
                    "storage":"01000000-0000-4000-8000-000030240200"
                },
                {
                    "size":10,
                    "tier":"maxiops",
                    "title":"disk-os",
                    "encrypted":"no",
                    "action":"create"
                }
            ]
        },
        "networking": {
            "interfaces": {
                "interface": [
                    {
                        "ip_addresses": {
                            "ip_address": [
                                {"family":"IPv4"}
                            ]
                        },
                        "type": "public"
                    },
                    {
                        "ip_addresses": {
                            "ip_address": [
                                {"family":"IPv4"}
                            ]
                        },
                        "type": "utility"
                    }
                ]
            }
        },
        "login_user": {
            "username": "upclouduser",
            "ssh_keys": {
                "ssh_key": [
                    "ecdsa-sha2-nistp256 AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAAAIbmlzdHAyNTYAAABBBFvXWSVYzRnjxYsz\/xKjOjAaPjzg98MMHaDulQYczTX28xlsMmFkviCeCCv7CLh19ydoH4LNKpvgTGiMXz8ib68= worker@envoyer."
                ]
            }
        }
    }
}
    */
