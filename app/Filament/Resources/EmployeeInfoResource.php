<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeInfoResource\Pages;
use App\Filament\Resources\EmployeeInfoResource\RelationManagers;
use App\Models\EmployeeInfo;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class EmployeeInfoResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = EmployeeInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'view_all',
            'create',
            'update',
            'restore',
            'restore_any',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'filter',
            'terminate'
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (Gate::allows('view_all_employee::info')) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()->whereHas('user', fn(Builder $query) => $query->where('id', auth()->id()));
        }

    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $user = User::find($state);
                        $set('staff_no', $user->staff_no);
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('staff_no')
                    ->disabled()
                    ->numeric(),
                Forms\Components\Select::make('location_id')
                    ->relationship('location', 'location')
                    ->required(),
                Forms\Components\TextInput::make('place_of_work')
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->after('start_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.staff_no')
                    ->label('Staff no')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->getStateUsing(function (Model $record) {
                        $startDate = Carbon::parse($record->start_date);
                        $endDate = Carbon::parse($record->end_date);

                        $diff = $startDate->diffForHumans($endDate, true);
                        return $diff;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->searchable()
                    ->preload()
                    ->label('Staff Number')
                    ->visible(Gate::allows('filter_employee::info'))
                    ->options(User::query()->whereNotNull('staff_no')->pluck('staff_no', 'id')),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('terminate')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->visible(fn(EmployeeInfo $record) => Gate::allows('terminate_employee::info') && $record->end_date == null)
                    ->action(function (EmployeeInfo $record) {
                        $record->end_date = now()->toDate();
                        $record->save();
                        Notification::make()
                            ->title('Terminated successfully')
                            ->success()
                            ->send();
                    })
            ])
            ->striped()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeInfos::route('/'),
            'create' => Pages\CreateEmployeeInfo::route('/create'),
            'edit' => Pages\EditEmployeeInfo::route('/{record}/edit'),
        ];
    }
}
