<?php

namespace Tests\Feature\Models;

use App\Models\Outage;
use App\Models\OutageReason;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutageReasonTest extends TestCase
{
    use RefreshDatabase;

    public function test_reason_relationship_resolves_its_outages(): void
    {
        $reason = OutageReason::create(['name' => 'Maintenance']);
        $outage = new Outage([
            'zone_id' => \App\Models\Zone::create(['name' => 'Dhaka', 'slug' => 'dhaka'])->id,
            'title' => 'Maintenance outage',
            'outage_type' => 'planned',
            'started_at' => '2026-10-12 08:00:00',
            'status' => 'active',
        ]);
        $reason->outages()->save($outage);

        $this->assertTrue($reason->outages->contains($outage));
    }
}
