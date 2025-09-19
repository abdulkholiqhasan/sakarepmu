<?php

use Livewire\Volt\Component;
use App\Services\SettingsService;

new class extends Component {
    public string $site_title = '';
    public string $site_tagline = '';
    public string $site_url = '';
    public string $admin_email = '';
    public string $timezone = '';

    public function mount(): void
    {
        $settings = new SettingsService();

        $this->site_title = $settings->get('site_title', config('app.name'));
        $this->site_tagline = $settings->get('site_tagline', 'Just another site');
        $this->site_url = $settings->get('site_url', config('app.url'));
        $this->admin_email = $settings->get('admin_email', config('mail.from.address', ''));
        $this->timezone = $settings->get('timezone', config('app.timezone', 'UTC'));
    }

    public function save(): void
    {
        $this->validate([
            'site_title' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_url' => ['required', 'url', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'timezone' => ['required', 'string', 'max:100'],
        ]);

        $settings = new SettingsService();

        $settings->set([
            'site_title' => $this->site_title,
            'site_tagline' => $this->site_tagline,
            'site_url' => $this->site_url,
            'admin_email' => $this->admin_email,
            'timezone' => $this->timezone,
        ]);

        $this->dispatch('general-saved');
    }
}; ?>

@section('title', __('General'))

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('General')" :subheading="__('Site title, tagline, and other general settings')">
        <form wire:submit="save" class="my-6 w-full space-y-6">
            <flux:input wire:model="site_title" :label="__('Site Title')" type="text" required />

            <flux:input wire:model="site_tagline" :label="__('Tagline')" type="text" />

            <flux:input wire:model="site_url" :label="__('Site URL')" type="url" required />

            <flux:input wire:model="admin_email" :label="__('Admin Email')" type="email" required />

            <flux:select wire:model="timezone" :label="__('Timezone')">
                @foreach(\App\Support\Timezone::list() as $tz)
                    <option value="{{ $tz }}">{{ $tz }}</option>
                @endforeach
            </flux:select>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="save-general">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="general-saved">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
