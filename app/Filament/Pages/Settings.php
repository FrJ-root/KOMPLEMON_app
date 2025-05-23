<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $slug = 'settings';
    
    public $site_name;
    public $site_description;
    public $contact_email;
    public $contact_phone;
    public $logo;
    
    public function mount(): void
    {
        $this->form->fill([
            'site_name' => setting('site_name', 'KOMPLEMON'),
            'site_description' => setting('site_description', ''),
            'contact_email' => setting('contact_email', ''),
            'contact_phone' => setting('contact_phone', ''),
            'logo' => setting('logo', ''),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Settings')
                    ->schema([
                        TextInput::make('site_name')
                            ->required(),
                        Textarea::make('site_description'),
                    ]),
                    
                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('contact_email')
                            ->email(),
                        TextInput::make('contact_phone'),
                    ]),
                    
                Section::make('Appearance')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->directory('logos'),
                    ]),
            ]);
    }
    
    public function submit(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            setting([$key => $value]);
        }
        
        setting()->save();
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
    
    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('submit'),
        ];
    }
}
