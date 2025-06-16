<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use App\Settings\GeneralSettings;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'Paramètres';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.settings';
    public ?array $data = [];
    
    public function mount(): void
    {
        $settings = app(GeneralSettings::class);
        $this->form->fill($settings->toArray());
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Section::make('Informations générales')
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Nom du site')
                                    ->required(),
                                TextInput::make('site_url')
                                    ->label('URL du site')
                                    ->required(),
                                FileUpload::make('site_logo')
                                    ->label('Logo du site')
                                    ->directory('site'),
                                Textarea::make('site_description')
                                    ->label('Description du site')
                                    ->rows(3),
                            ]),
                        
                        Section::make('Coordonnées')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->label('Email de contact')
                                    ->email(),
                                TextInput::make('contact_phone')
                                    ->label('Téléphone de contact'),
                                Textarea::make('contact_address')
                                    ->label('Adresse')
                                    ->rows(3),
                            ]),
                        
                        Section::make('Réseaux sociaux')
                            ->schema([
                                TextInput::make('social_facebook')
                                    ->label('Facebook URL'),
                                TextInput::make('social_instagram')
                                    ->label('Instagram URL'),
                                TextInput::make('social_twitter')
                                    ->label('Twitter URL'),
                                TextInput::make('social_youtube')
                                    ->label('YouTube URL'),
                            ]),
                        
                        Section::make('SEO')
                            ->schema([
                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3),
                                TextInput::make('meta_keywords')
                                    ->label('Meta Keywords'),
                                TextInput::make('google_analytics_id')
                                    ->label('Google Analytics ID'),
                            ]),
                    ]),
            ]);
    }
    
    public function submit(): void
    {
        $settings = app(GeneralSettings::class);
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $settings->$key = $value;
        }
        
        $settings->save();
        
        $this->notify('success', 'Les paramètres ont été sauvegardés avec succès.');
    }
}
