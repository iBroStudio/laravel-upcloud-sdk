<?php

namespace IBroStudio\Upcloud\Database\Factories;

use IBroStudio\DataRepository\ValueObjects\Authentication\SshKey;
use IBroStudio\Upcloud\Models\UpcloudSshKey;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpcloudSshKeyFactory extends Factory
{
    protected $model = UpcloudSshKey::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->userName(),
            'key' => SshKey::from(
                user: 'ibro',
                public: 'ecdsa-sha2-nistp256 AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAAAIbmlzdHAyNTYAAABBBFvXWSVYzRnjxYsz/xKjOjAaPjzg98MMHaDulQYczTX28xlsMmFkviCeCCv7CLh19ydoH4LNKpvgTGiMXz8ib68= worker@envoyer.',
            ),
            'autodeploy' => $this->faker->boolean(),
        ];
    }

    public function withSshUser(string $sshUser): Factory
    {
        return $this->state(function (array $attributes) use ($sshUser) {
            return [
                'key' => SshKey::from(
                    user: $sshUser,
                    public: 'ecdsa-sha2-nistp256 AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAAAIbmlzdHAyNTYAAABBBFvXWSVYzRnjxYsz/xKjOjAaPjzg98MMHaDulQYczTX28xlsMmFkviCeCCv7CLh19ydoH4LNKpvgTGiMXz8ib68= worker@envoyer.',
                ),
            ];
        });
    }
}
