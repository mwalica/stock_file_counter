<?php

use App\Models\Day;
use App\Models\MediaType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    public int $media_type_id = 1;
    public int $amount = 1;
    public string $date;


    public function rules(): array
    {
        return [
            'media_type_id' => ['required', 'integer', 'min:1'],
            'amount' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date']
        ];
    }

    public function mount()
    {
        $this->date = Carbon::now()->toDateString();
    }

    #[Computed]
    public function mediaTypes(): Collection
    {
        return MediaType::all();
    }

    #[Computed]
    public function daily(): Collection
    {
        return Day::query()->with('mediaType')->latest('date')->get();
    }

    #[Computed]
    public function totalAmount(): int
    {
        return $this->daily()->sum('amount');
    }

    //helpers
    private function resetForm(): void
    {
        $this->reset('media_type_id', 'amount');
        $this->date = Carbon::now()->toDateString();
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();
        Day::create([
            'media_type_id' => $this->media_type_id,
            'amount' => $this->amount,
            'date' => $this->date
        ]);

        $this->resetForm();
        Flux::modal('day-form')->close();
    }

    public function add(Day $day): void
    {
        $day->increment('amount');
    }

    public function sub(Day $day): void
    {
        if ($day->amount > 1) {
            $day->decrement('amount');
        } else {
            Flux::toast(
                text: "Do you want delete this item?",
                variant: 'warning',
                action: [
                    'label' => "Yes",
                    'event' => 'delete',
                    'params' => ['day' => $day]
                ],
                duration: 0
            );
        }
    }

    #[On('delete')]
    public function delete(Day $day): void
    {
        $day->delete();
    }
};
?>

<div class="mx-auto max-w-4xl">
    <flux:heading level="3" size="xl" class="mb-4">PhotoStock Files</flux:heading>

    <flux:card body="flush" variant="muted">
        <flux:card.header>
            <flux:card.heading>All processes files: <strong>{{$this->totalAmount}}</strong></flux:card.heading>
            <flux:card.actions>
                <flux:modal.trigger name="day-form">
                    <flux:button variant="outline" color="violet" icon:trailing="plus" class="cursor-pointer me-2">Add</flux:button>
                </flux:modal.trigger>
            </flux:card.actions>
        </flux:card.header>

        <flux:card.body>
            <flux:table bleed>
                <flux:table.rows>
                    @forelse($this->daily as $day)
                        <flux:table.row :key="$day->id">
                            <flux:table.cell class="w-px whitespace-nowrap">
                                <div class="rounded-full size-3 {{$day->amountColor}}"></div>
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-700 w-px whitespace-nowrap">{{$day->dateFormatted}}</flux:table.cell>
                            <flux:table.cell class="text-zinc-700 w-full" align="center"><flux:badge color="{{$day->mediaType->badgeColor}}" size="sm">{{$day->mediaType->name}}</flux:badge></flux:table.cell>
                            <flux:table.cell variant="strong" class="flex gap-3 justify-center items-center">
                                <flux:button
                                    variant="filled"
                                    icon="minus" size="xs" wire:click="sub({{$day->id}})"
                                             class="cursor-pointer"/>
                                <span class="w-[4ch] text-center">{{$day->amount}}</span>
                                <flux:button
                                    variant="filled"
                                    icon="plus" size="xs" wire:click="add({{$day->id}})"
                                             class="cursor-pointer"/>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" align="center">No create days in DB</flux:table.cell>
                        </flux:table.row>
                    @endforelse

                </flux:table.rows>
            </flux:table>
        </flux:card.body>
    </flux:card>

    <flux:modal name="day-form" class="w-2/3 max-w-3xl space-y-6">
        <div>
            <flux:heading size="xl">Add day</flux:heading>
        </div>
        <flux:separator/>
        <form wire:submit="save" autocomplete="off" class="space-y-8">
            <flux:input type="number" label="Amount" wire:model="amount" badge="required" min="1"/>
            <flux:field>
                <flux:label badge="required">Type:</flux:label>
                <flux:select variant="listbox" placeholder="Select type..." wire:model="media_type_id">
                    @foreach($this->mediaTypes as $mediaType)
                        <flux:select.option value="{{$mediaType->id}}">{{$mediaType->name}}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="media_type_id"/>
            </flux:field>
            <flux:date-picker wire:model="date" label="Date" badge="required"/>
            <flux:button type="submit" variant="primary" class="cursor-pointer">Save</flux:button>
        </form>
    </flux:modal>
</div>
