<?php

namespace App\Filament\Admin\Resources\BannerAdsResource\Pages;

use App\Filament\Admin\Resources\BannerAdsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBannerAds extends EditRecord
{
    protected static string $resource = BannerAdsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
