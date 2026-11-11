<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new
#[Layout('layouts.login')]
#[Title('Login to Photo Counter')]
class extends Component {

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public function authenticate(): void
    {
        $this->validate();
        $valid = Auth::attempt(['email' => $this->email, 'password' => $this->password], true);

        if ($valid) {
            session()->regenerate();
            $this->redirectIntended(navigate: true);
        } else {;
            Flux::toast(duration: 2000, text: 'Incorrect email and/or password');
        }
    }


};
?>

<div class="mx-auto max-w-3xl flex justify-center items-center h-full">
    <flux:card class="w-full h-fit -translate-y-4">
        <flux:card.header>
            <flux:card.heading  level="4" size="xl" class="text-center">
                Login
                <flux:icon.log-in class="inline -translate-y-px" />
            </flux:card.heading>
        </flux:card.header>
        <flux:card.body>
            <form wire:submit="authenticate" class="space-y-8">
                <flux:input type="email" wire:model="email" label="E-mail" />
                <flux:input type="password" wire:model="password" label="Password" />
                <flux:button type="submit" variant="primary" class="w-fit cursor-pointer">Login</flux:button>
            </form>
        </flux:card.body>
    </flux:card>
</div>
