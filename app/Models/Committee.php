<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Committee extends Model
{
    use HasFactory;

    /**
     * Predefined committee names.
     */
    public const RAVENS = 'Ravens - RND';
    public const HERONS = 'Herons - CommEX';
    public const CANARY = 'Canary - Creatives';
    public const NIGHTINGALE = 'Nightingale - Broadcasting';
    public const FALCONS = 'Falcons - Events';

    /**
     * Get the default committees with their values.
     *
     * @return list<array{name: string, description: string|null}>
     */
    public static function defaultCommittees(): array
    {
        return [
            ['name' => self::RAVENS, 'description' => 'Known for their intelligence and complex social structures.'],
            ['name' => self::HERONS, 'description' => 'Long-legged coastal birds often found in wetlands.'],
            ['name' => self::CANARY, 'description' => 'Small songbirds popular for their bright colors and melodic voices.'],
            ['name' => self::NIGHTINGALE, 'description' => 'Famous for its powerful and beautiful song, often heard at night.'],
            ['name' => self::FALCONS, 'description' => 'High-speed birds of prey known for their incredible hunting vision.'],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    /**
     * Get the organization users for the committee.
     */
    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }
}
