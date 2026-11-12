<?php

use App\Models\Day;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('PhotoStock File Counter')]
class extends Component {

    public array $yearAmount;

    public function mount()
    {
        $this->yearAmount = Day::query()
            ->select(DB::raw('YEAR(date) as year'), DB::raw('SUM(amount) as total'))
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->map(fn($row) => [
                'year' => (string)$row->year,
                'total' => (int)$row->total
            ])
            ->all();
    }
};
?>

<div class="mx-auto max-w-4xl">
    <flux:heading level="3" size="xl" class="mb-4">Summary</flux:heading>


    <flux:card body="flush" variant="muted">
        <flux:card.header>
            <flux:card.heading size="lg">Yearly amount</flux:card.heading>
        </flux:card.header>
        <flux:card.body>
            <flux:chart wire:model="yearAmount" class="aspect-[3/1]">
                <flux:chart.svg>
                    <flux:chart.bar field="total" width="50%" class="text-accent"/>

                    <flux:chart.axis axis="x" field="year" scale="categorical">
                        <flux:chart.axis.tick/>
                    </flux:chart.axis>

                    <flux:chart.axis axis="y">
                        <flux:chart.axis.grid/>
                        <flux:chart.axis.tick/>
                    </flux:chart.axis>
                </flux:chart.svg>

                <flux:chart.tooltip>
                    <flux:chart.tooltip.heading field="year"/>
                    <flux:chart.tooltip.value field="total" label="Total"/>
                </flux:chart.tooltip>
            </flux:chart>
        </flux:card.body>


    </flux:card>
</div>
