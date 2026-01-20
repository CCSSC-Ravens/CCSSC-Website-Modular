<?php

namespace App\Modules\Committee\Http\Controllers;

use App\Models\Committee;
use Illuminate\Routing\Controller;

class CommitteePageController extends Controller
{
    /**
     * Committee configuration data using Committee model names.
     * Colors follow the homepage palette: reds, oranges, golds, maroons
     */
    private function getCommittees(): array
    {
        return [
            [
                'id' => 'ravens',
                'name' => Committee::RAVENS,
                'shortName' => 'Ravens',
                'gradient' => 'from-[#B13407] to-[#800000]',
                'color' => '#B13407',
                'description' => 'Known for their intelligence and complex social structures. The Ravens - RND committee drives innovation through research and development, exploring new technologies and solutions to advance the CCS community.',
                'tags' => ['Research', 'Development', 'Innovation'],
            ],
            [
                'id' => 'herons',
                'name' => Committee::HERONS,
                'shortName' => 'Herons',
                'gradient' => 'from-[#A42503] to-[#6B1A00]',
                'color' => '#A42503',
                'description' => 'Long-legged coastal birds often found in wetlands. The Herons - CommEX committee handles external communications, building bridges with other organizations and maintaining the council\'s public presence.',
                'tags' => ['Communications', 'External Affairs', 'Public Relations'],
            ],
            [
                'id' => 'canary',
                'name' => Committee::CANARY,
                'shortName' => 'Canary',
                'gradient' => 'from-[#FFB800] to-[#B13407]',
                'color' => '#FFB800',
                'description' => 'Small songbirds popular for their bright colors and melodic voices. The Canary - Creatives committee brings the council\'s vision to life through design, visual arts, and creative content that captures the spirit of CCS.',
                'tags' => ['Design', 'Visual Arts', 'Creative Content'],
            ],
            [
                'id' => 'nightingale',
                'name' => Committee::NIGHTINGALE,
                'shortName' => 'Nightingale',
                'gradient' => 'from-[#8F2203] to-[#4A1200]',
                'color' => '#8F2203',
                'description' => 'Famous for its powerful and beautiful song, often heard at night. The Nightingale - Broadcasting committee amplifies the voice of CCS through podcasts, videos, and multimedia content that reaches every student.',
                'tags' => ['Broadcasting', 'Multimedia', 'Content Creation'],
            ],
            [
                'id' => 'falcons',
                'name' => Committee::FALCONS,
                'shortName' => 'Falcons',
                'gradient' => 'from-[#C24112] to-[#800000]',
                'color' => '#C24112',
                'description' => 'High-speed birds of prey known for their incredible hunting vision. The Falcons - Events committee orchestrates memorable experiences, from academic seminars to social gatherings that unite the CCS community.',
                'tags' => ['Events', 'Planning', 'Community Building'],
            ],
        ];
    }

    /**
     * Generate particle configuration for background animation
     */
    private function generateParticles(int $count = 15): array
    {
        $particles = [];
        for ($i = 0; $i < $count; $i++) {
            $particles[] = [
                'size' => rand(50, 150),
                'left' => rand(0, 100),
                'top' => rand(100, 200),
                'delay' => rand(0, 10),
                'duration' => rand(15, 25),
            ];
        }
        return $particles;
    }

    public function index()
    {
        $committees = $this->getCommittees();
        $particles = $this->generateParticles();
        $committeeCount = count($committees);

        return view('committee::index', compact('committees', 'particles', 'committeeCount'));
    }
}
